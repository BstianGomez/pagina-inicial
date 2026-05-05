<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Contracts\Console\Kernel;

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$duplicateNames = Category::select('name')
    ->groupBy('name')
    ->havingRaw('COUNT(*) > 1')
    ->pluck('name');

foreach ($duplicateNames as $name) {
    echo "Processing: $name\n";
    $categories = Category::where('name', $name)->orderBy('id')->get();
    $master = $categories->shift();
    
    foreach ($categories as $duplicate) {
        Expense::where('category_id', $duplicate->id)->update(['category_id' => $master->id]);
        $duplicate->delete();
        echo "  - Deleted duplicate ID: {$duplicate->id}\n";
    }
}

echo "Cleanup complete.\n";
