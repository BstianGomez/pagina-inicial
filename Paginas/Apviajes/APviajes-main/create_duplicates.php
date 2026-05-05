<?php
use App\Models\Expense;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Str;

$originalExpense = Expense::latest()->first();
$originalReport = Report::find($originalExpense->report_id);

for ($i = 1; $i <= 2; $i++) {
    $report = Report::create([
        'user_id' => $originalReport->user_id,
        'status' => 'Enviado',
        'title' => 'Rendición Duplicada ' . $i,
        'description' => 'Test de duplicación',
        'total_amount' => $originalExpense->amount,
    ]);

    Expense::create([
        'report_id' => $report->id,
        'category_id' => $originalExpense->category_id,
        'ceco_id' => $originalExpense->ceco_id,
        'rendition_type' => $originalExpense->rendition_type,
        'reason' => $originalExpense->reason,
        'description' => 'Boleta repetida ' . $i,
        'expense_date' => $originalExpense->expense_date,
        'amount' => $originalExpense->amount,
        'provider_name' => $originalExpense->provider_name,
        'provider_rut' => $originalExpense->provider_rut,
        'document_type' => $originalExpense->document_type,
        'attachment_path' => $originalExpense->attachment_path,
    ]);
}
echo "2 duplicate reports created.\n";
