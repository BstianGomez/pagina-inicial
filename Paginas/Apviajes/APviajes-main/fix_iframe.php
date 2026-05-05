<?php
$file = 'resources/views/reports/show.blade.php';
$content = file_get_contents($file);

$current_iframe = '<iframe src="{{ Storage::url($expense->attachment_path) }}" class="w-full h-[400px]" frameborder="0"></iframe>';
$current_iframe_replacement = <<<HTML
                                                    @if(\Storage::disk('public')->exists(str_replace('public/', '', \$expense->attachment_path)) || \Storage::exists(\$expense->attachment_path))
                                                        <iframe src="{{ Storage::url(\$expense->attachment_path) }}" class="w-full h-[400px]" frameborder="0"></iframe>
                                                    @else
                                                        <div class="flex flex-col items-center justify-center p-8 text-slate-400">
                                                            <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            <span class="text-xs font-bold uppercase tracking-wider">Documento de prueba (sin archivo adjunto)</span>
                                                        </div>
                                                    @endif
HTML;

$sim_iframe = '<iframe src="{{ Storage::url($sim->attachment_path) }}" class="w-full h-[400px]" frameborder="0"></iframe>';
$sim_iframe_replacement = <<<HTML
                                                    @if(\Storage::disk('public')->exists(str_replace('public/', '', \$sim->attachment_path)) || \Storage::exists(\$sim->attachment_path))
                                                        <iframe src="{{ Storage::url(\$sim->attachment_path) }}" class="w-full h-[400px]" frameborder="0"></iframe>
                                                    @else
                                                        <div class="flex flex-col items-center justify-center p-8 text-red-300">
                                                            <svg class="w-12 h-12 mb-3 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            <span class="text-xs font-bold uppercase tracking-wider">Documento de prueba (sin archivo adjunto)</span>
                                                        </div>
                                                    @endif
HTML;

// Since both lines match the exact same pattern except for the variable ($expense vs $sim), I'll match them sequentially or using specific block searches.
$content = str_replace($current_iframe, $current_iframe_replacement, $content);
$content = str_replace($sim_iframe_replacement, $current_iframe, $content); // just in case I over-replaced
$content = str_replace('<iframe src="{{ Storage::url($sim->attachment_path) }}" class="w-full h-[400px]" frameborder="0"></iframe>', $sim_iframe_replacement, $content);

file_put_contents($file, $content);
