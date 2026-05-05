<?php
$str = file_get_contents('resources/views/reports/create-step1.blade.php');
$str = preg_replace('/<div[^>]*>\s*<label for="ceco_id"[\s\S]*?<\/div>/', '', $str);
$str = preg_replace('/const cecoSelect = document.getElementById\(\'ceco_id\'\);/', '', $str);
$str = preg_replace('/const cecoOk  = cecoSelect\.value !== \'\';/', 'const cecoOk = true;', $str);
$str = preg_replace('/cecoSelect\.addEventListener\(\'change\', checkForm\);/', '', $str);
file_put_contents('resources/views/reports/create-step1.blade.php', $str);
