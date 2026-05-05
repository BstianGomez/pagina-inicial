<?php
$str = file_get_contents('resources/views/dashboard.blade.php');

$old = <<<'PHP'
                                @if($isDuplicate)
                                    <span class="badge-duplicate mt-1" title="Posible solicitud duplicada por coincidir mismo usuario y monto.">
                                        <svg style="width:10px;height:10px;margin-right:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Repetida
                                    </span>
                                @endif
PHP;

$new = <<<'PHP'
                                @if($isDuplicate)
                                    @php
                                        $dupes = $report->duplicate_reports;
                                        $dupeText = $dupes->count() > 0 ? 'Repetida con rep. #' . $dupes->pluck('id')->implode(', #') : 'Repetida';
                                        $titleText = $dupes->count() > 0 ? 'Coincide con las rendiciones: #' . $dupes->pluck('id')->implode(', #') : 'Posible solicitud duplicada por coincidir mismo RUT y monto.';
                                    @endphp
                                    <span class="badge-duplicate mt-1" title="{{ $titleText }}">
                                        <svg style="width:10px;height:10px;margin-right:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        {{ $dupeText }}
                                    </span>
                                @endif
PHP;

$str = str_replace($old, $new, $str);
file_put_contents('resources/views/dashboard.blade.php', $str);

$strIndex = file_get_contents('resources/views/reports/index.blade.php');
$oldIndex = <<<'PHP'
                                            @if($isDuplicate)
                                                <span class="badge-duplicate" title="Posible solicitud duplicada por coincidir mismo usuario y monto.">
                                                    <svg style="width:10px;height:10px;margin-right:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    Repetida
                                                </span>
                                            @endif
PHP;

$newIndex = <<<'PHP'
                                            @if($isDuplicate)
                                                @php
                                                    $dupes = $report->duplicate_reports;
                                                    $dupeText = $dupes->count() > 0 ? 'Repetida con rep. #' . $dupes->pluck('id')->implode(', #') : 'Repetida';
                                                    $titleText = $dupes->count() > 0 ? 'Coincide con las rendiciones: #' . $dupes->pluck('id')->implode(', #') : 'Posible solicitud duplicada por coincidir mismo RUT y monto.';
                                                @endphp
                                                <span class="badge-duplicate" title="{{ $titleText }}">
                                                    <svg style="width:10px;height:10px;margin-right:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    {{ $dupeText }}
                                                </span>
                                            @endif
PHP;

$strIndex = str_replace($oldIndex, $newIndex, $strIndex);
file_put_contents('resources/views/reports/index.blade.php', $strIndex);

