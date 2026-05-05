<?php
$str = file_get_contents('app/Http/Controllers/ReportController.php');

$str = preg_replace('/\$expense\s*=\s*\$report->expenses\(\)->latest\(\)->first\(\);\s*if\s*\(!\$expense\)\s*\{\s*return back\(\)->with\(\'error\',\s*\'No se encontró un gasto asociado al informe\.\'\);\s*\}/', '', $str);

$str = preg_replace('/\$expense->update\(\[\s*\'ceco_id\' => \$validated\[\'ceco_id\'\],\s*\]\);/', '\$report->update([\'title\' => \$validated[\'title\'], \'ceco_id\' => \$validated[\'ceco_id\']]);', $str);

$str = preg_replace('/\$selectedCategory\s*=\s*\$expense->category;\s*if\s*\(\$this->requiresComandaForCategory\(\$selectedCategory\).+?Adjunte la comanda en el paso de detalles del gasto\.\'\);\s*\}/s', '', $str);

file_put_contents('app/Http/Controllers/ReportController.php', $str);
