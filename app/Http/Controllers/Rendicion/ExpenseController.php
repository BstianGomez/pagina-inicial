<?php

namespace App\Http\Controllers\Rendicion;

use App\Models\Rendicion\Category;
use App\Models\Rendicion\Ceco;
use App\Models\Rendicion\Expense;
use App\Models\Rendicion\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{
	public function index()
	{
		$user = Auth::user();
		$reports = Report::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
		$categories = Category::orderBy('name')->get()->unique('name');
		$cecos = Ceco::orderBy('code')->get();
		
		return view('rendicion.expenses.index', compact('reports', 'categories', 'cecos'));
	}

	public function storeBulk(Request $request)
	{
		$user = Auth::user();
		$action = $request->input('action', 'draft');

		$rules = [
			'expenses' => 'required|array|min:1',
			'report_title' => $action === 'submit' ? 'required|string|max:255' : 'nullable|string|max:255',
			'action' => 'required|in:draft,submit',
		];

		// Define base rules for each expense row
		$rowRules = [
			'category_id' => $action === 'submit' ? 'required|exists:categories,id' : 'nullable|exists:categories,id',
			'custom_category_name' => 'nullable|string|max:255',
			'reason' => $action === 'submit' ? 'required|string|max:255' : 'nullable|string|max:255',
			'description' => 'nullable|string|max:255',
			'expense_date' => $action === 'submit' ? 'required|date' : 'nullable|date',
			'amount' => $action === 'submit' ? 'required|numeric|gt:0' : 'nullable|numeric|min:0',
			'provider_name' => $action === 'submit' ? 'required|string|max:255' : 'nullable|string|max:255',
			'provider_rut' => $action === 'submit' ? 'required|string|max:30' : 'nullable|string|max:30',
			'document_type' => $action === 'submit' ? 'required|in:Boleta,Factura,Recibo,Ticket,Otros' : 'nullable|in:Boleta,Factura,Recibo,Ticket,Otros',
			'attachment_path' => $action === 'submit' ? 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
			'comanda_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
		];

		foreach ($rowRules as $field => $rule) {
			$rules["expenses.*.{$field}"] = $rule;
		}

		$validated = $request->validate($rules);

		$categoryIds = collect($validated['expenses'])->pluck('category_id')->filter()->unique()->values();
		$categoriesById = Category::query()->whereIn('id', $categoryIds)->get()->keyBy('id');

		foreach ($validated['expenses'] as $index => $row) {
			if ($action === 'submit') {
				$category = $categoriesById->get((int) ($row['category_id'] ?? 0));
				$needsComanda = Str::of((string) optional($category)->name)->ascii()->lower()->trim()->value() === 'alimentacion';

				if ($needsComanda && !$request->hasFile("expenses.{$index}.comanda_path")) {
					return back()
						->withErrors(["expenses.{$index}.comanda_path" => 'La comanda es obligatoria para categoría Alimentación.'])
						->withInput();
				}
			}
		}

		DB::transaction(function () use ($request, $validated, $user, $action) {
			$report = null;
			if ($action === 'submit') {
				$report = Report::create([
					'user_id' => $user->id,
					'title' => $validated['report_title'],
					'status' => Report::STATUS_UNDER_REVIEW,
					'total_amount' => 0,
				]);
			}

			foreach ($validated['expenses'] as $index => $row) {
				$attachmentPath = $request->hasFile("expenses.{$index}.attachment_path")
					? $request->file("expenses.{$index}.attachment_path")->store('expenses', 'public')
					: null;
					
				$comandaPath = $request->hasFile("expenses.{$index}.comanda_path")
					? $request->file("expenses.{$index}.comanda_path")->store('comandas', 'public')
					: null;

				$amount = isset($row['amount']) ? (float) str_replace(['.', ','], ['', '.'], $row['amount']) : null;

				$categoryId = $row['category_id'];
				if ((int) $categoryId === 6 && !empty($row['custom_category_name'])) {
					$customCat = Category::firstOrCreate(
						['name' => trim($row['custom_category_name'])],
						['requires_comanda' => false, 'is_custom' => true]
					);
					$categoryId = $customCat->id;
				}

				$expense = Expense::create([
					'user_id' => $user->id,
					'status' => $report ? Expense::STATUS_ASSIGNED : Expense::STATUS_DRAFT,
					'report_id' => $report ? $report->id : null,
					'category_id' => $categoryId,
					'ceco_id' => $report ? $report->ceco_id : null,
					'rendition_type' => $user->has_fixed_fund ? 'Con fondo fijo' : 'Sin fondo fijo',
					'reason' => $row['reason'],
					'description' => $row['description'] ?? null,
					'expense_date' => $row['expense_date'],
					'amount' => $amount,
					'provider_name' => $row['provider_name'],
					'provider_rut' => $row['provider_rut'],
					'document_type' => $row['document_type'],
					'attachment_path' => $attachmentPath,
					'comanda_path' => $comandaPath,
				]);

				if (!$report && $expense->isComplete()) {
					$expense->update(['status' => Expense::STATUS_READY]);
				}
			}

			if ($report) {
				$report->update([
					'total_amount' => $report->expenses()->sum('amount'),
					'ceco_id' => $report->expenses()->first()->ceco_id ?? null,
				]);
			}
		});

		$msg = $action === 'submit' ? 'Rendición enviada correctamente.' : 'Gastos guardados como borrador correctamente.';
		return redirect()->route('dashboard')->with('success', $msg);
	}

	public function drafts()
	{
		$user = Auth::user();
		$expenses = Expense::where('user_id', $user->id)
			->whereIn('status', [Expense::STATUS_DRAFT, Expense::STATUS_READY])
			->whereNull('report_id')
			->with(['category', 'ceco'])
			->latest()
			->paginate(15);

		return view('rendicion.expenses.drafts', compact('expenses'));
	}

	public function edit(Expense $expense)
	{
		$user = Auth::user();
		if ($expense->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
			abort(403);
		}

		if ($expense->status !== Expense::STATUS_DRAFT && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
			return back()->with('error', 'Solo se pueden editar gastos en estado Borrador.');
		}

		$categories = Category::orderBy('name')->get()->unique('name');
		$cecos = Ceco::orderBy('code')->get();

		return view('rendicion.expenses.edit', compact('expense', 'categories', 'cecos'));
	}

	public function update(Request $request, Expense $expense)
	{
		$user = Auth::user();
		if ($expense->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
			abort(403);
		}

		if ($expense->status !== Expense::STATUS_DRAFT && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
			return back()->with('error', 'Solo se pueden editar gastos en estado Borrador.');
		}

		$validated = $request->validate([
			'category_id' => 'nullable|exists:categories,id',
			'custom_category_name' => 'nullable|string|max:255',
			'reason' => 'nullable|string|max:255',
			'description' => 'nullable|string|max:255',
			'expense_date' => 'nullable|date',
			'amount' => 'nullable', // Will be parsed below
			'provider_name' => 'nullable|string|max:255',
			'provider_rut' => 'nullable|string|max:30',
			'document_type' => 'nullable|in:Boleta,Factura,Recibo,Ticket,Otros',
			'attachment_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
			'comanda_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
		]);

		if ($request->filled('amount')) {
			$validated['amount'] = (float) str_replace(['.', ','], ['', '.'], $request->amount);
		}

		$categoryId = $validated['category_id'];
		$category = Category::find($categoryId);
		$needsComanda = Str::of((string) optional($category)->name)->ascii()->lower()->trim()->value() === 'alimentacion';

		if ($needsComanda && !$expense->comanda_path && !$request->hasFile('comanda_path')) {
			return back()->withErrors(['comanda_path' => 'La comanda es obligatoria para categoría Alimentación.'])->withInput();
		}

		if ((int) $categoryId === 6 && !empty($validated['custom_category_name'])) {
			$customCat = Category::firstOrCreate(
				['name' => trim($validated['custom_category_name'])],
				['requires_comanda' => false, 'is_custom' => true]
			);
			$categoryId = $customCat->id;
		}

		if ($request->hasFile('attachment_path')) {
			$validated['attachment_path'] = $request->file('attachment_path')->store('expenses', 'public');
		} else {
			unset($validated['attachment_path']);
		}

		if ($request->hasFile('comanda_path')) {
			$validated['comanda_path'] = $request->file('comanda_path')->store('comandas', 'public');
		} else {
			unset($validated['comanda_path']);
		}

		$validated['category_id'] = $categoryId;
		$expense->update($validated);

		// Re-evaluate status: if all mandatory fields are filled, it's no longer a draft (it's READY)
		if ($expense->isComplete()) {
			$expense->status = Expense::STATUS_READY;
		} else {
			$expense->status = Expense::STATUS_DRAFT;
		}
		$expense->save();

		return redirect()->route('expenses.drafts')->with('success', 'Gasto actualizado correctamente.');
	}
}
