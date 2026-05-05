<?php
$str = file_get_contents('resources/views/dashboard.blade.php');

$old = <<<PHP
                            // Detectar si es posiblemente un duplicado (mismo usuario, mismo monto, mayor a 0)
                            \$isDuplicate = \$report->total_amount > 0 && 
                                \App\Models\Report::where('user_id', \$report->user_id)
                                    ->where('total_amount', \$report->total_amount)
                                    ->where('status', '!=', \App\Models\Report::STATUS_DRAFT)
                                    ->where('id', '!=', \$report->id)
                                    ->exists();
                            
                            \$rowClasses = \$isDuplicate ? 'is-duplicate-row hover:bg-slate-50/80 transition-colors duration-150 group cursor-pointer' : 'hover:bg-slate-50/80 transition-colors duration-150 group cursor-pointer';
PHP;

$new = <<<PHP
                            // Detectar si es posiblemente un duplicado iterando sus gastos y validando con boletas de la DB
                            \$isDuplicate = \$report->has_duplicate_expenses;
                            
                            \$rowClasses = \$isDuplicate ? 'is-duplicate-row bg-red-50/20 hover:bg-red-50/80 transition-colors duration-150 group cursor-pointer' : 'hover:bg-slate-50/80 transition-colors duration-150 group cursor-pointer';
PHP;

$str = str_replace($old, $new, $str);

file_put_contents('resources/views/dashboard.blade.php', $str);
