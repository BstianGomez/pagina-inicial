<?php
$files = [
    'app/Http/Controllers/ReportController.php',
    'resources/views/reports/show.blade.php',
    'resources/views/reports/index.blade.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Reverse the quotes-only swap because we want a global word swap
    $content = str_replace("'Aprobador'", "'%%TEMP%%'", $content);
    $content = str_replace("'Gestor'", "'Aprobador'", $content);
    $content = str_replace("'%%TEMP%%'", "'Gestor'", $content);
    
    $content = str_replace('"Aprobador"', '"%%TEMP%%"', $content);
    $content = str_replace('"Gestor"', '"Aprobador"', $content);
    $content = str_replace('"%%TEMP%%"', '"Gestor"', $content);

    // Now do the deep word swap (Case sensitive first)
    $content = str_replace("Gestor", "%%TEMP%%", $content);
    $content = str_replace("Aprobador", "Gestor", $content);
    $content = str_replace("%%TEMP%%", "Aprobador", $content);

    // And lowercase (gestor <-> aprobador)
    $content = str_replace("gestor", "%%temp%%", $content);
    $content = str_replace("aprobador", "gestor", $content);
    $content = str_replace("%%temp%%", "aprobador", $content);

    // And uppercase (GESTOR <-> APROBADOR)
    $content = str_replace("GESTOR", "%%TEMP_UP%%", $content);
    $content = str_replace("APROBADOR", "GESTOR", $content);
    $content = str_replace("%%TEMP_UP%%", "APROBADOR", $content);

    file_put_contents($file, $content);
}
echo "Deep swap done.\n";
