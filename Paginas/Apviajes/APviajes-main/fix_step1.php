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

        \$cecos = \App\Models\Ceco::query()->whereNotIn('code', ['TI-001', 'ADM-002', 'RRHH-003', 'COM-004'])->orderBy('code')->get();

        return view('reports.create-step1', compact('report', 'cecos'));
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
            'ceco_id' => 'required|exists:cecos,id',
        ]);

        \$report->update([
            'title' => \$validated['title'],
            'ceco_id' => \$validated['ceco_id'],
        ]);

        return redirect()->route('expenses.createStep2', ['report' => \$report->id]);
    }
PHP;

$str = str_replace($oldStep1, $newStep1, $str);
file_put_contents('app/Http/Controllers/ReportController.php', $str);
