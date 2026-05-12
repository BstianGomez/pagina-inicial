<?php

use App\Models\OC\Ceco;
use App\Models\OC\Proveedor;
use App\Models\OC\Cliente;
use App\Models\OC\RazonSocial;
use Illuminate\Support\Facades\DB;

/**
 * Script to import OC data from CSV files.
 */

function getFileLines($path) {
    $content = file_get_contents($path);
    
    // Remove BOM if present
    $bom = pack('H*','EFBBBF');
    $content = preg_replace("/^$bom/", '', $content);
    
    // Detect encoding and convert to UTF-8 if necessary
    $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'UTF-16LE', 'UTF-16BE']);
    if ($encoding && $encoding !== 'UTF-8') {
        $content = mb_convert_encoding($content, 'UTF-8', $encoding);
    }
    
    return explode("\n", $content);
}

function parseCsvLine($line) {
    $line = trim($line, " \t\n\r\0\x0B;");
    if (!$line) return null;
    
    // Handle the weird double-quoting issue seen in debug
    if (str_starts_with($line, '"') && str_ends_with($line, '"')) {
        $line = substr($line, 1, -1);
    }
    
    // Double quotes inside are escaped as ""
    $line = str_replace('""', '"', $line);
    
    // Standard CSV parsing
    return str_getcsv($line, ",", '"');
}

function importCecos($path) {
    echo "Importing CECOs from $path...\n";
    $lines = getFileLines($path);
    if (!$lines) return;

    $count = 0;
    foreach ($lines as $i => $line) {
        if ($i === 0) continue; // Skip header
        
        $data = parseCsvLine($line);
        if (!$data || count($data) < 3) continue;
        
        // Title, CECO, NombreCeco, TipoCeco
        $codigo = trim($data[1] ?? '');
        $nombre = trim($data[2] ?? '');
        $tipo = trim($data[3] ?? '');

        if (!$codigo) continue;

        Ceco::updateOrCreate(
            ['codigo' => $codigo],
            ['nombre' => $nombre, 'tipo' => $tipo]
        );
        $count++;
    }
    echo "Imported $count CECOs.\n";
}

function importProveedores($path) {
    echo "Importing Proveedores from $path...\n";
    $lines = getFileLines($path);
    if (!$lines) return;

    $count = 0;
    foreach ($lines as $i => $line) {
        if ($i === 0) continue; // Skip header
        
        $data = parseCsvLine($line);
        if (!$data || count($data) < 3) continue;

        // Título, Acreedor, RUT
        $nombre = trim($data[0] ?? '');
        $acreedor = trim($data[1] ?? '');
        $rut = trim($data[2] ?? '');

        if (!$acreedor) continue;

        Proveedor::updateOrCreate(
            ['acreedor' => $acreedor],
            ['nombre' => $nombre, 'rut' => $rut]
        );
        $count++;
    }
    echo "Imported $count Proveedores.\n";
}

function importClientes($path) {
    echo "Importing Clientes from $path...\n";
    $lines = getFileLines($path);
    if (!$lines) return;

    $count = 0;
    foreach ($lines as $i => $line) {
        if ($i === 0) continue; // Skip header
        
        $data = parseCsvLine($line);
        if (!$data || count($data) < 8) continue;

        // "Holding Otic,""New N° Fundación"",""N° Fundación"",""Nombre Cliente Fundación"", ...
        // Index 7 is Cod. Deudor
        // Index 3 is Nombre Cliente Fundación
        
        $codigo = trim($data[7] ?? '');
        $nombre = trim($data[3] ?? '');

        if (!$codigo || $codigo == '0') continue;

        Cliente::updateOrCreate(
            ['codigo' => $codigo],
            ['nombre' => $nombre]
        );
        $count++;
    }
    echo "Imported $count Clientes.\n";
}

function importRazonesSociales($path) {
    echo "Importing Razones Sociales from $path...\n";
    $lines = getFileLines($path);
    if (!$lines) return;

    $count = 0;
    foreach ($lines as $i => $line) {
        if ($i === 0) continue; // Skip header
        
        $data = parseCsvLine($line);
        if (!$data || count($data) < 4) continue;

        // CodCliente, Rut, NewCodDeudor, CodDeudor, RazonSocial, RazonSocialRut
        $codCliente = trim($data[0] ?? '');
        $rut = trim($data[1] ?? '');
        $codDeudor = trim($data[3] ?? '');
        $razonSocial = trim($data[4] ?? '');

        if (!$codDeudor) continue;

        $client = Cliente::where('codigo', $codDeudor)->first();
        
        if (!$client) {
            $client = Cliente::updateOrCreate(
                ['codigo' => $codDeudor],
                ['nombre' => $razonSocial]
            );
        }

        RazonSocial::updateOrCreate(
            ['cod_deudor' => $codDeudor, 'cliente_id' => $client->id],
            ['rut' => $rut, 'razon_social' => $razonSocial]
        );
        $count++;
    }
    echo "Imported $count Razones Sociales.\n";
}

// Main execution
$baseDir = __DIR__ . '/../datos_excel';

try {
    DB::beginTransaction();
    
    importCecos($baseDir . '/CECO.csv');
    importProveedores($baseDir . '/Proveedores (1).csv');
    importClientes($baseDir . '/ClientesFundacion.csv');
    importRazonesSociales($baseDir . '/FundRazonSocial.csv');
    
    DB::commit();
    echo "All data imported successfully!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error during import: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
