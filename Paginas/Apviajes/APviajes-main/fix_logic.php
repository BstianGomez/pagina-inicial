<?php

$files = [
    'app/Http/Controllers/ReportController.php',
    'resources/views/reports/show.blade.php',
    'resources/views/reports/index.blade.php',
    'resources/views/dashboard.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    $content = str_replace("'Aprobador'", "'%%TEMP%%'", $content);
    $content = str_replace("'Gestor'", "'Aprobador'", $content);
    $content = str_replace("'%%TEMP%%'", "'Gestor'", $content);
    
    $content = str_replace('"Aprobador"', '"%%TEMP%%"', $content);
    $content = str_replace('"Gestor"', '"Aprobador"', $content);
    $content = str_replace('"%%TEMP%%"', '"Gestor"', $content);

    // Also swap the literal headers!
    // Original: Aprobación del Gestor -> Fase 1
    // Original: Aprobación del Aprobador -> Fase 2
    // Let's swap the literal UI titles so they make sense for the person looking at them
    $content = str_replace("Aprobación del Gestor", "%%TEMP_TITLE%%", $content);
    $content = str_replace("Aprobación del Aprobador", "Aprobación del Gestor", $content);
    $content = str_replace("%%TEMP_TITLE%%", "Aprobación del Aprobador", $content);

    file_put_contents($file, $content);
}

echo "Roles swapped precisely.\n";
