<?php

namespace App\Http\Controllers\Rendicion;

use App\Models\Rendicion\Category;
use App\Models\Rendicion\Ceco;
use App\Models\Rendicion\Expense;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        $categories = Category::query()
            ->orderBy('name')
            ->get()
            ->unique(fn (Category $category) => mb_strtolower(trim($category->name)))
            ->values();

        $cecos = Ceco::query()
            ->orderBy('code')
            ->get()
            ->unique(fn (Ceco $ceco) => trim($ceco->code))
            ->values();

        return view('rendicion.config.index', compact('categories', 'cecos'));
    }

    public function storeCategory(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'requires_comanda' => 'boolean',
        ]);

        Category::create([
            'name' => $validated['name'],
            'requires_comanda' => $request->has('requires_comanda'),
        ]);

        return back()->with('success', 'Categoría creada.');
    }

    public function storeCeco(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cecos,code',
        ]);

        Ceco::create($validated);

        return back()->with('success', 'Centro de Costo creado.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'requires_comanda' => 'boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'requires_comanda' => $request->has('requires_comanda'),
        ]);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function updateCeco(Request $request, Ceco $ceco)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cecos,code,' . $ceco->id,
        ]);

        $ceco->update($validated);

        return back()->with('success', 'Centro de Costo actualizado.');
    }

    public function destroyCategory(Category $category)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'No tienes permisos para eliminar información de catálogo.');
        }

        $normalizedName = mb_strtolower(trim($category->name));
        $idsToDelete = Category::query()
            ->whereRaw('LOWER(TRIM(name)) = ?', [$normalizedName])
            ->pluck('id')
            ->all();

        if (empty($idsToDelete)) {
            return back()->with('error', 'No se encontró la categoría a eliminar.');
        }

        $expensesExist = Expense::query()->whereIn('category_id', $idsToDelete)->exists();
        if ($expensesExist) {
            $fallbackCategoryId = $this->resolveFallbackCategoryId($idsToDelete);
            Expense::query()
                ->whereIn('category_id', $idsToDelete)
                ->update(['category_id' => $fallbackCategoryId]);
        }

        Category::query()->whereIn('id', $idsToDelete)->delete();
        return back()->with('success', 'Categoría eliminada.');
    }

    public function destroyCeco(Ceco $ceco)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'No tienes permisos para eliminar información de catálogo.');
        }

        $normalizedCode = trim($ceco->code);
        $idsToDelete = Ceco::query()
            ->whereRaw('TRIM(code) = ?', [$normalizedCode])
            ->pluck('id')
            ->all();

        if (empty($idsToDelete)) {
            return back()->with('error', 'No se encontró el CECO a eliminar.');
        }

        $expensesExist = Expense::query()->whereIn('ceco_id', $idsToDelete)->exists();
        if ($expensesExist) {
            $fallbackCecoId = $this->resolveFallbackCecoId($idsToDelete);
            Expense::query()
                ->whereIn('ceco_id', $idsToDelete)
                ->update(['ceco_id' => $fallbackCecoId]);
        }

        Ceco::query()->whereIn('id', $idsToDelete)->delete();
        return back()->with('success', 'Centro de Costo eliminado.');
    }

    private function resolveFallbackCategoryId(array $idsToDelete): int
    {
        $fallback = Category::query()
            ->whereNotIn('id', $idsToDelete)
            ->orderByRaw("CASE WHEN LOWER(TRIM(name)) = 'otros' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->first();

        if ($fallback) {
            return $fallback->id;
        }

        $created = Category::create([
            'name' => 'Otros',
            'requires_comanda' => false,
        ]);

        return $created->id;
    }

    private function resolveFallbackCecoId(array $idsToDelete): int
    {
        $fallback = Ceco::query()
            ->whereNotIn('id', $idsToDelete)
            ->orderByRaw("CASE WHEN TRIM(code) = '99999' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->first();

        if ($fallback) {
            return $fallback->id;
        }

        $created = Ceco::create([
            'code' => '99999',
            'name' => 'Sin CECO',
        ]);

        return $created->id;
    }
}
