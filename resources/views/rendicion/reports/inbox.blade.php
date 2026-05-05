<x-rendicion.layouts.app
    page_title="Bandeja de Entrada"
    page_subtitle="Rendiciones que requieren tu revisión.">
    <x-slot name="header_title">Bandeja de Gestión</x-slot>

    <style>
        .inbox-table {
            width: 100%;
            table-layout: fixed;
        }

        .inbox-table th,
        .inbox-table td {
            overflow-wrap: anywhere;
        }

        .inbox-status-cell {
            padding-left: 0.65rem;
            padding-right: 0.65rem;
            overflow: hidden;
        }

        .inbox-status-badge {
            display: inline-flex;
            align-items: center;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
        }

        .badge-duplicate {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.55rem;
            font-weight: 900;
            background: #e11d48;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.3rem;
            max-width: fit-content;
        }
    </style>


    <div class="card-main">
        <div class="section-header-soft p-6 bg-sofofa-blue/5 border-b border-sofofa-blue/10">
            <h3 class="text-lg font-bold text-sofofa-blue">Solicitudes que requieren mi acción</h3>
        </div>
        <div class="overflow-x-hidden">
            <table class="table-sofofa inbox-table">
                <thead>
                    <tr>
                        <th class="text-left px-4 py-4 uppercase tracking-widest text-[11px] font-bold text-slate-500 border-b border-slate-100 w-[9%]">ID</th>
                        <th class="text-left px-4 py-4 uppercase tracking-widest text-[11px] font-bold text-slate-500 border-b border-slate-100 w-[14%]">SOLICITANTE</th>
                        <th class="text-left px-4 py-4 uppercase tracking-widest text-[11px] font-bold text-slate-500 border-b border-slate-100 w-[28%]">TÍTULO</th>
                        <th class="text-left px-4 py-4 uppercase tracking-widest text-[11px] font-bold text-slate-500 border-b border-slate-100 w-[26%]">ESTADO</th>
                        <th class="text-right px-4 py-4 uppercase tracking-widest text-[11px] font-bold text-slate-500 border-b border-slate-100 w-[11%]">MONTO</th>
                        <th class="text-center px-4 py-4 uppercase tracking-widest text-[11px] font-bold text-slate-500 border-b border-slate-100 w-[12%]">GESTIÓN</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($reports as $report)
                                        @php
                        $isDuplicate = $report->has_duplicate_expenses;
                        $inboxRowClasses = $isDuplicate ? 'is-duplicate-inbox bg-red-50/20 hover:bg-red-50/80 transition-colors duration-150 cursor-pointer group' : 'hover:bg-slate-50/80 transition-colors duration-150 cursor-pointer group';
                    @endphp
                                        @php
                        $inboxRowClasses = $isDuplicate ? 'is-duplicate-inbox cursor-pointer group transition-colors duration-150' : 'hover:bg-slate-50/80 transition-colors duration-150 cursor-pointer group';
                    @endphp
                    <tr class="{{ $inboxRowClasses }}" onclick="window.location='{{ route('rendicion.reports.show', $report) }}'">
                        <td class="font-bold text-slate-400 text-sm px-4 whitespace-nowrap">
                            #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="text-slate-700 font-bold text-sm px-4 leading-snug">
                            {{ $report->user->name }}
                        </td>
                        <td class="font-bold text-sm px-4 leading-snug flex flex-col justify-center min-h-[3.5rem] py-2">
                            <span style="{{ $isDuplicate ? 'color: #b91c1c;' : 'color: #1e293b;' }}">{{ $report->title }}</span>
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
                        </td>
                        <td class="inbox-status-cell">
                            @php
                                $statusMap = [
                                    'Borrador' => ['dot' => '#eab308', 'bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a'],
                                    'Enviado' => ['dot' => '#2563eb', 'bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                                    'Pendiente aprobación jefatura' => ['dot' => '#2563eb', 'bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                                    'Subsanada por solicitante (jefatura)' => ['dot' => '#2563eb', 'bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                                    'Aprobada por jefatura' => ['dot' => '#1d4ed8', 'bg' => '#dbeafe', 'text' => '#1e3a8a', 'border' => '#93c5fd'],
                                    'En revisión' => ['dot' => '#4f46e5', 'bg' => '#eef2ff', 'text' => '#3730a3', 'border' => '#c7d2fe'],
                                    'Subsanada por solicitante (Gestor)' => ['dot' => '#4f46e5', 'bg' => '#eef2ff', 'text' => '#3730a3', 'border' => '#c7d2fe'],
                                    'Aprobada por gestor' => ['dot' => '#0f766e', 'bg' => '#ccfbf1', 'text' => '#115e59', 'border' => '#99f6e4'],
                                    'Pendiente pago' => ['dot' => '#d97706', 'bg' => '#fff7ed', 'text' => '#9a3412', 'border' => '#fed7aa'],
                                    'Reembolsada' => ['dot' => '#15803d', 'bg' => '#dcfce7', 'text' => '#166534', 'border' => '#86efac'],
                                    'Rechazada por jefatura' => ['dot' => '#dc2626', 'bg' => '#fef2f2', 'text' => '#b91c1c', 'border' => '#fecaca'],
                                    'Rechazada por gestor' => ['dot' => '#dc2626', 'bg' => '#fef2f2', 'text' => '#b91c1c', 'border' => '#fecaca'],
                                    'Observada por jefatura' => ['dot' => '#ea580c', 'bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#fed7aa'],
                                    'Observada por gestor' => ['dot' => '#ea580c', 'bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#fed7aa'],
                                ];
                                $cfg = $statusMap[$report->status] ?? ['dot' => '#64748b', 'bg' => '#f8fafc', 'text' => '#475569', 'border' => '#cbd5e1'];
                            @endphp
                            <span class="inbox-status-badge" style="display:inline-flex;align-items:center;padding:0.4rem 0.65rem;border-radius:0.65rem;font-size:0.66rem;font-weight:800;background:{{ $cfg['bg'] }};color:{{ $cfg['text'] }};border:1px solid {{ $cfg['border'] }};line-height:1.2;white-space:nowrap;">
                                <span style="height:0.45rem;width:0.45rem;border-radius:999px;background:{{ $cfg['dot'] }};margin-right:0.4rem;"></span>
                                {{ $report->status }}
                            </span>
                        </td>
                        <td class="text-right font-black text-slate-900 text-sm px-4 whitespace-nowrap">
                            ${{ number_format($report->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center py-4">
                            <a href="{{ route('rendicion.reports.show', $report) }}" class="inline-flex items-center justify-center px-4 py-1.5 bg-sofofa-blue text-white text-[9px] font-black rounded-lg hover:bg-sofofa-blue-dark transition-all uppercase tracking-widest shadow-sm group-hover:scale-105">
                                Revisar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200 mb-4 border border-slate-100">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5"/></svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-800">¡Al día!</h4>
                                <p class="text-slate-500 text-sm mt-1">No tienes solicitudes pendientes de tu aprobación en este momento.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/70">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</x-rendicion.layouts.app>
<style>
    .is-duplicate-inbox td {
        background-color: #fef2f2 !important;
    }
    .is-duplicate-inbox:hover td {
        background-color: #fee2e2 !important;
    }
    .is-duplicate-inbox td:first-child { box-shadow: inset 4px 0 0 #ef4444 !important; }
</style>
