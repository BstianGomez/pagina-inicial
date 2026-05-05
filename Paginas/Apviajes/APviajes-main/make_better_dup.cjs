const fs = require('fs');

function improveIndex() {
    const path = 'resources/views/reports/index.blade.php';
    let content = fs.readFileSync(path, 'utf8');

    // Add CSS for duplicate rows
    if (!content.includes('.is-duplicate-row')) {
        const styleInject = `        .reports-table tbody tr:hover td {
            background: #f8fbff;
        }

        .reports-table tbody tr.is-duplicate-row td {
            background-color: #fff1f2 !important;
            border-bottom: 1px solid #ffe4e6 !important;
        }
        .reports-table tbody tr.is-duplicate-row:hover td {
            background-color: #ffe4e6 !important;
        }
        .reports-table tbody tr.is-duplicate-row td:first-child {
            box-shadow: inset 4px 0 0 #f43f5e;
        }
        
        .badge-duplicate {
            display: inline-flex;
            align-items: center;
            background: #fff;
            border: 1px solid #fda4af;
            color: #e11d48;
            font-size: 0.6rem;
            font-weight: 800;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            letter-spacing: 0.05em;
            box-shadow: 0 1px 2px rgba(225, 29, 72, 0.1);
            margin-left: 0.5rem;
        }`;
        content = content.replace('        .reports-table tbody tr:hover td {\n            background: #f8fbff;\n        }', styleInject);
    }

    // Fix TR
    const searchRow = `                                        $rowClasses = $isDuplicate ? '' : 'group hover:-translate-y-0.5';
                                        $rowStyle = $isDuplicate ? 'background-color: #fef2f2; border-left: 4px solid #ef4444;' : '';
                                    @endphp
                                <tr class="{{ $rowClasses }} cursor-pointer transition-all duration-200" style="{{ $rowStyle }}" onclick="if(!event.target.closest('input')) window.location='{{ route('reports.show', $report) }}'">`;
    const replaceRow = `                                        $rowClasses = $isDuplicate ? 'is-duplicate-row' : 'group hover:-translate-y-0.5';
                                    @endphp
                                <tr class="{{ $rowClasses }} cursor-pointer transition-all duration-200" onclick="if(!event.target.closest('input')) window.location='{{ route('reports.show', $report) }}'">`;
    content = content.replace(searchRow, replaceRow);

    // Fix Title
    const searchTitle = `<span class="reports-title-cell block flex items-center gap-2">
                                            <span style="{{ $isDuplicate ? 'color: #b91c1c;' : '' }}">{{ $report->title }}</span>
                                            @if($isDuplicate)
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-bold uppercase tracking-widest" style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5;" title="Posible solicitud duplicada por coincidir mismo usuario y monto.">
                                                    Posible Duplicado
                                                </span>
                                            @endif
                                        </span>`;
    const replaceTitle = `<span class="reports-title-cell block flex items-center">
                                            <span class="truncate max-w-[200px] block" style="{{ $isDuplicate ? 'color: #be123c;' : '' }}">{{ $report->title }}</span>
                                            @if($isDuplicate)
                                                <span class="badge-duplicate" title="Posible solicitud duplicada por coincidir mismo usuario y monto.">
                                                    <svg style="width:10px;height:10px;margin-right:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    Repetida
                                                </span>
                                            @endif
                                        </span>`;
    content = content.replace(searchTitle, replaceTitle);
    
    fs.writeFileSync(path, content, 'utf8');
}

try { improveIndex(); console.log('Index improved'); } catch(e) { console.error(e); }
