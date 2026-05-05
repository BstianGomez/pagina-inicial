<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Report::all() as $report) {
    $total = $report->expenses()->sum('amount');
    $report->update(['total_amount' => $total]);
    echo "Report {$report->id} updated to $total.\n";
}
