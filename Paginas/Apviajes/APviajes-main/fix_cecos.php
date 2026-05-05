<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Ceco;
use App\Models\Expense;
use Illuminate\Contracts\Console\Kernel;

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Mappings from mnemonic old codes to official numeric ones
$mappings = [
    'TI-001' => '20131',   // TI
    'COM-004' => '20147',  // Comunicaciones
    'ADM-002' => '20002',  // Finanzas y Operaciones
    'RRHH-003' => '20003', // Desarrollo Organizacional (HR replacement)
];

foreach ($mappings as $oldCode => $newCode) {
    echo "Processing CECO Mapping: $oldCode ... ";
    $old = Ceco::where('code', $oldCode)->first();
    $new = Ceco::where('code', $newCode)->first();
    
    if ($old && $new) {
        Expense::where('ceco_id', $old->id)->update(['ceco_id' => $new->id]);
        $old->delete();
        echo "[MERGED into $newCode]\n";
    } elseif ($old) {
        $old->delete();
        echo "[DELETED - No target numeric code found]\n";
    } else {
        echo "[NOT FOUND - Already clean]\n";
    }
}

echo "CECO Cleanup complete.\n";
