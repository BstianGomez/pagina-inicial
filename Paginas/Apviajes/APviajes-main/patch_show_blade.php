<?php
$str = file_get_contents('resources/views/reports/show.blade.php');

$oldBanner = <<<PHP
                                                <span class="text-xs text-orange-700/80 mt-0.5 font-medium">El sistema encontró {{ \$similares->count() }} gasto(s) en otras rendiciones con el mismo RUT (<span class="font-bold underline decoration-dotted">{{ \$expense->provider_rut }}</span>) y monto (<span class="font-bold underline decoration-dotted">\${{ number_format(\$expense->amount, 0, ',', '.') }}</span>).</span>
PHP;

$newBanner = <<<PHP
                                                <span class="text-xs text-orange-700/80 mt-0.5 font-medium">El sistema encontró {{ \$similares->count() }} gasto(s) en otras rendiciones (Rendición <strong>#{{ \$similares->pluck('report_id')->unique()->implode(', #') }}</strong>) con el mismo RUT (<span class="font-bold underline decoration-dotted">{{ \$expense->provider_rut }}</span>) y monto (<span class="font-bold underline decoration-dotted">\${{ number_format(\$expense->amount, 0, ',', '.') }}</span>).</span>
PHP;

$str = str_replace($oldBanner, $newBanner, $str);


$oldModalInfo = <<<PHP
                                                            <p class="text-[9px] text-red-600/80 font-bold mt-0.5 tracking-wide">
                                                                Rendición de <span class="uppercase font-black text-red-800">{{ \$sim->report->user->name ?? 'Usuario Desconocido' }}</span> el {{ date('d/m/Y', strtotime(\$sim->created_at)) }}
                                                            </p>
PHP;

$newModalInfo = <<<PHP
                                                            <p class="text-[9px] text-red-600/80 font-bold mt-0.5 tracking-wide">
                                                                Rendición <strong>#{{ \$sim->report_id }}</strong> de <span class="uppercase font-black text-red-800">{{ \$sim->report->user->name ?? 'Usuario Desconocido' }}</span> el {{ date('d/m/Y', strtotime(\$sim->created_at)) }}
                                                            </p>
PHP;

$str = str_replace($oldModalInfo, $newModalInfo, $str);

file_put_contents('resources/views/reports/show.blade.php', $str);
