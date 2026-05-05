<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Report;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Ceco;

$user = User::first();
$category = Category::first();
$ceco = Ceco::first();

if (!$user || !$category || !$ceco) {
    echo "No user, category or ceco found.\n";
    exit(1);
}

// 1
$r1 = Report::create([
    'title' => 'Rendición de Prueba 1 - Origen',
    'status' => App\Models\Report::STATUS_SUBMITTED,
    'user_id' => $user->id,
]);
Expense::create([
    'report_id' => $r1->id,
    'category_id' => $category->id,
    'ceco_id' => $ceco->id,
    'amount' => 99990,
    'expense_date' => now()->format('Y-m-d'),
    'provider_rut' => '99.999.999-9',
    'provider_name' => 'Proveedor Duplicado Test',
    'receipt_type' => 'boleta',
    'receipt_number' => '123456',
    'rendition_type' => 'Nacional', // or equivalent
    'reason' => 'Test',
    'document_type' => 'Boleta',
    'attachment_path' => 'dummy/path',
]);

// 2 (Duplicate)
$r2 = Report::create([
    'title' => 'Rendición de Prueba 2 - Duplicado',
    'status' => App\Models\Report::STATUS_SUBMITTED,
    'user_id' => $user->id,
]);
Expense::create([
    'report_id' => $r2->id,
    'category_id' => $category->id,
    'ceco_id' => $ceco->id,
    'amount' => 99990,
    'expense_date' => now()->format('Y-m-d'),
    'provider_rut' => '99.999.999-9',
    'provider_name' => 'Proveedor Duplicado Test',
    'receipt_type' => 'boleta',
    'receipt_number' => '123456',
    'rendition_type' => 'Nacional',
    'reason' => 'Test',
    'document_type' => 'Boleta',
    'attachment_path' => 'dummy/path',
]);

// 3
$r3 = Report::create([
    'title' => 'Rendición de Prueba 3 - Limpia',
    'status' => App\Models\Report::STATUS_SUBMITTED,
    'user_id' => $user->id,
]);
Expense::create([
    'report_id' => $r3->id,
    'category_id' => $category->id,
    'ceco_id' => $ceco->id,
    'amount' => 15500,
    'expense_date' => now()->format('Y-m-d'),
    'provider_rut' => '88.888.888-8',
    'provider_name' => 'Proveedor Normal',
    'receipt_type' => 'boleta',
    'receipt_number' => '987654',
    'rendition_type' => 'Nacional',
    'reason' => 'Test',
    'document_type' => 'Boleta',
    'attachment_path' => 'dummy/path',
]);

echo "3 rendiciones creadas con exito. La 1 y la 2 comparten un gasto duplicado de 99,990.\n";
