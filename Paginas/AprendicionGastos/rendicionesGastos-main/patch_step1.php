<?php
$str = file_get_contents('app/Http/Controllers/ReportController.php');

$oldStep1 = <<<PHP
    public function createStep1(Report \$report)
    {
        \$user = Auth::user();
        if (\$report->user_id !== \$user->id && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!\$report->isEditableByRequester() && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede editar en su estado actual.');
        }

        \$cecos = Ceco::query()->whereNotIn('code', ['TI-001', 'ADM-002', 'RRHH-003', 'COM-004'])->orderBy('code')->get();
        \$expense = \$report->expenses()->latest()->first();

        return view('reports.create-step1', compact('report', 'expense', 'cecos'));
    }

    public function storeStep1(Request \$request, Report \$report)
    {
        \$user = Auth::user();
        if (\$report->user_id !== \$user->id && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!\$report->isEditableByRequester() && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede modificar en su estado actual.');
        }

        \$expense = \$report->expenses()->latest()->first();
        if (!\$expense) {
            return back()->with('error', 'No se encontró un gasto asociado al informe.');
        }

        \$validated = \$request->validate([
            'title' => 'required|string|max:255',
            'ceco_id' => 'required|exists:cecos,id',
        ]);

        \$expense->update([
            'ceco_id' => \$validated['ceco_id'],
        ]);

        \$selectedCategory = \$expense->category;
        if (\$this->requiresComandaForCategory(\$selectedCategory) && empty(\$expense->comanda_path)) {
            return redirect()
                ->route('expenses.createStep2', ['report' => \$report->id])
                ->with('error', 'La comanda es obligatoria solo para la categoría Alimentación. Adjunte la comanda en el paso de detalles del gasto.');
        }

        \$isDraft = \$request->input('action') === 'draft';

        if (!\$isDraft && \$report->expenses()->where('amount', '<=', 0)->exists()) {
            return redirect()
                ->route('expenses.createStep2', ['report' => \$report->id])
                ->with('error', 'No se puede enviar la solicitud con monto 0. Complete un monto mayor a 0 en todos los gastos.');
        }

        \$nextStatus = \$isDraft
            ? Report::STATUS_DRAFT
            : Report::STATUS_UNDER_REVIEW;

        \$report->update([
            'title' => \$validated['title'],
            'status' => \$nextStatus,
            'total_amount' => \$report->expenses()->sum('amount'),
        ]);

        if (!\$isDraft) {
            \$report->comments()->create([
                'user_id' => \$user->id,
                'comment' => 'Informe enviado por el solicitante.',
                'from_status' => Report::STATUS_DRAFT,
                'to_status' => Report::STATUS_UNDER_REVIEW,
            ]);

            return redirect()->route('expenses.index')->with('success', 'Rendición enviada correctamente para revisión.');
        }

        return redirect()->route('expenses.createStep2', ['report' => \$report->id])->with('success', 'Borrador guardado. Puede agregar los detalles del gasto.');
    }
PHP;

$newStep1 = <<<PHP
    public function createStep1(Report \$report)
    {
        \$user = Auth::user();
        if (\$report->user_id !== \$user->id && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!\$report->isEditableByRequester() && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede editar en su estado actual.');
        }

        return view('reports.create-step1', compact('report'));
    }

    public function storeStep1(Request \$request, Report \$report)
    {
        \$user = Auth::user();
        if (\$report->user_id !== \$user->id && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!\$report->isEditableByRequester() && !\$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede modificar en su estado actual.');
        }

        \$validated = \$request->validate([
            'title' => 'required|string|max:255',
        ]);

        \$isDraft = \$request->input('action') === 'draft';

        \$report->update([
            'title' => \$validated['title'],
        ]);

        // If they just say "Siguiente" (not draft), send them to Step 2
        return redirect()->route('expenses.createStep2', ['report' => \$report->id]);
    }
PHP;

$str = str_replace($oldStep1, $newStep1, $str);
file_put_contents('app/Http/Controllers/ReportController.php', $str);
