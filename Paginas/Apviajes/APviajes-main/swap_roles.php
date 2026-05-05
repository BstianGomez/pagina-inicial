<?php

$files = [
    'app/Http/Controllers/ReportController.php',
    'resources/views/reports/show.blade.php',
    'database/seeders/RolePermissionSeeder.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // We want to swap the usage of 'Gestor' and 'Aprobador' 
    // when they are passed as strings or in role arrays
    // e.g. 'Gestor' -> '%%GESTOR%%', 'Aprobador' -> 'Gestor', '%%GESTOR%%' -> 'Aprobador'
    // But be careful not to break lowercase phrases unless intended. Let's do exact match swaps for PHP/blade role checks.
    
    // We only swap exactly 'Gestor' and 'Aprobador'
    $content = str_replace("'Gestor'", "'%%TEMP%%'", $content);
    $content = str_replace("'Aprobador'", "'Gestor'", $content);
    $content = str_replace("'%%TEMP%%'", "'Aprobador'", $content);
    
    $content = str_replace('"Gestor"', '"%%TEMP%%"', $content);
    $content = str_replace('"Aprobador"', '"Gestor"', $content);
    $content = str_replace('"%%TEMP%%"', '"Aprobador"', $content);

    // Let's also swap literal text shown in UI for buttons or headers?
    // "Gestor" -> "Aprobador" inside blade
    // Actually, maybe it's better to just swap the exact roles array mapping in Controller and Blade.
    
    file_put_contents($file, $content);
}

echo "Roles swapped in source files.\n";
