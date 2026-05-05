const fs = require('fs');
const path = 'resources/views/reports/index.blade.php';
let content = fs.readFileSync(path, 'utf8');

// Update the TR line
const searchStr = `<tr class="group cursor-pointer transition-all duration-200 hover:-translate-y-0.5" onclick="if(!event.target.closest('input')) window.location='{{ route('reports.show', $report) }}'">`;

const replaceStr = `                                    @php
                                        // Detectar si es posiblemente un duplicado (mismo usuario, mismo monto, mayor a 0)
                                        $isDuplicate = $report->total_amount > 0 && 
                                            \\App\\Models\\Report::where('user_id', $report->user_id)
                                                ->where('total_amount', $report->total_amount)
                                                ->where('status', '!=', \\App\\Models\\Report::STATUS_DRAFT)
                                                ->where('id', '!=', $report->id)
                                                ->exists();
                                                
                                        $rowClasses = $isDuplicate 
                                            ? 'bg-red-50 hover:bg-red-100 border-l-4 border-red-500'
                                            : 'group hover:-translate-y-0.5';
                                    @endphp
                                <tr class="{{ $rowClasses }} cursor-pointer transition-all duration-200" onclick="if(!event.target.closest('input')) window.location='{{ route('reports.show', $report) }}'">`;

content = content.replace(searchStr, replaceStr);

// To make it more clear, maybe add a small red badge near the title or the amount?
const titleColSearch = `<div class="reports-title-cell font-bold text-slate-800 group-hover:text-sofofa-blue transition-colors">
                                            {{ $report->title }}
                                        </div>`;

const titleColReplace = `<div class="reports-title-cell font-bold {{ $isDuplicate ? 'text-red-700' : 'text-slate-800 group-hover:text-sofofa-blue' }} transition-colors flex items-center gap-2">
                                            {{ $report->title }}
                                            @if($isDuplicate)
                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-extrabold text-red-700" title="Posible solicitud duplicada por coincidir mismo usuario y monto.">
                                                    Repetido
                                                </span>
                                            @endif
                                        </div>`;

content = content.replace(titleColSearch, titleColReplace);

fs.writeFileSync(path, content, 'utf8');
