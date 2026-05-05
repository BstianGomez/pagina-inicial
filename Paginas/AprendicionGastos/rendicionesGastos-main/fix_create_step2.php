<?php
$str = file_get_contents('app/Http/Controllers/ReportController.php');

$old = <<<PHP
    public function createStep2(Report \$report)
    {
        \$user = Auth::user();
        if (\$report->user_id !== \$user->id && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!\$report->isEditableByRequester() && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede editar en su estado actual.');
        }

        \$expense = \$report->expenses()->latest()->first();
        \$categories = Category::query()->orderBy('name')->get()->unique('name');

        return view('reports.create-step2', compact('report', 'expense', 'categories'));
    }
PHP;

$new = <<<PHP
    public function createStep2(Report \$report)
    {
        \$user = Auth::user();
        if (\$report->user_id !== \$user->id && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!\$report->isEditableByRequester() && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede editar en su estado actual.');
        }

        \$assignedExpenses = \$report->expenses()->with('category', 'ceco')->get();
        \$availableExpenses = \App\Models\Expense::whereHas('report', function(\$q) use (\$user, \$report) {
            \$q->where('user_id', \$user->id)
              ->where('status', \App\Models\Report::STATUS_DRAFT)
              ->where('id', '!=', \$report->id);
        })->with('category', 'ceco', 'report')->get();

        \$expense = \$assignedExpenses->first();
        \$categories = Category::query()->orderBy('name')->get()->unique('name');

        return view('reports.create-step2', compact('report', 'expense', 'categories', 'assignedExpenses', 'availableExpenses'));
    }
PHP;

$str = str_replace($old, $new, $str);

// Just in case it is also in another place, let's fix the redirect from create() too!
// "quiero que cuando le de a nueva solicitud me diriga hacia gastos" 
// Gastos view IS createStep2 (Selección de Gastos).
// BUT wait, in create() it creates one empty expense. We also need to remove that empty expense creation so it doesn't pollute the assigned expenses!
// Let's modify ReportController@create as well.

$oldCreate = <<<PHP
    public function create()
    {
        if (!Auth::user()->hasAnyRole(['Solicitante', 'Superadmin', 'Admin'])) {
            abort(403);
        }

        \$defaultCategory = Category::query()->orderBy('name')->first();
        \$defaultCeco = Ceco::query()
            ->whereNotIn('code', ['TI-001', 'ADM-002', 'RRHH-003', 'COM-004'])
            ->orderBy('code')
            ->first();

        if (!\$defaultCategory || !\$defaultCeco) {
            return back()->with('error', 'No hay categorías o CECO disponibles para iniciar una rendición.');
        }

        \$report = Report::create([
            'user_id' => Auth::id(),
            'title' => 'Borrador de rendición',
            'status' => Report::STATUS_DRAFT,
            'total_amount' => 0,
        ]);

        Expense::create([
            'report_id' => \$report->id,
            'category_id' => \$defaultCategory->id,
            'ceco_id' => \$defaultCeco->id,
            'rendition_type' => \$this->resolveRenditionTypeByUserId(Auth::id()),
            'reason' => 'Pendiente',
            'expense_date' => now()->toDateString(),
            'amount' => 0,
            'provider_name' => 'Pendiente',
            'provider_rut' => 'Pendiente',
            'document_type' => 'Boleta',
            'attachment_path' => 'pendiente',
        ]);

        return redirect()->route('expenses.createStep2', ['report' => \$report->id]);
    }
PHP;

$newCreate = <<<PHP
    public function create()
    {
        if (!Auth::user()->hasAnyRole(['Solicitante', 'Superadmin', 'Admin'])) {
            abort(403);
        }

        \$report = Report::create([
            'user_id' => Auth::id(),
            'title' => 'Borrador de rendición',
            'status' => Report::STATUS_DRAFT,
            'total_amount' => 0,
        ]);

        return redirect()->route('expenses.createStep2', ['report' => \$report->id]);
    }
PHP;

$str = str_replace($oldCreate, $newCreate, $str);

file_put_contents('app/Http/Controllers/ReportController.php', $str);
