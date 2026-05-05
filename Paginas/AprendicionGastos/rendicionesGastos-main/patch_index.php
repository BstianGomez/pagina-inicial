<?php
$str = file_get_contents('resources/views/reports/index.blade.php');

$old = <<<PHP
                                        // Detectar si es posiblemente un duplicado (mismo usuario, mismo monto, mayor a 0)
                                        \$isDuplicate = \$report->total_amount > 0 && 
                                            \App\Models\Report::where('user_id', \$report->user_id)
                                                ->where('total_amount', \$report->total_amount)
                                                ->where('status', '!=', \App\Models\Report::STATUS_DRAFT)
                                                ->where('id', '!=', \$report->id)
                                                ->exists();
                                                
                                                                                \$rowClasses = \$isDuplicate ? 'is-duplicate-row' : 'group hover:-translate-y-0.5';
PHP;

$new = <<<PHP
                                        // Detectar si es posiblemente un duplicado iterando sus gastos
                                        \$isDuplicate = \$report->has_duplicate_expenses;
                                        \$rowClasses = \$isDuplicate ? 'is-duplicate-row bg-red-50/20' : 'group hover:-translate-y-0.5';
PHP;

$str = str_replace($old, $new, $str);
file_put_contents('resources/views/reports/index.blade.php', $str);
