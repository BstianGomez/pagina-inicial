@extends('oc.layouts.dashboard')

@section('title', 'Dashboard Estadístico OC')

@section('header')
<div class="ms-banner">
    <div>
        <h1 class="ms-banner-title">Análisis de Operaciones</h1>
        <p class="ms-banner-sub">Monitoreo de gastos, KPIs y evolución mensual</p>
    </div>
    <div style="display: flex; gap: 0.5rem; align-items: center;">
        <button id="sendAllGmailBtn" class="ms-btn-new" style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.3); display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; font-size: 0.78rem; text-transform: uppercase; font-weight: 800; border-radius: 0.75rem; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Enviar por Gmail
        </button>
        <button onclick="downloadPdf()" class="ms-btn-new" style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.3); display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; font-size: 0.78rem; text-transform: uppercase; font-weight: 800; border-radius: 0.75rem; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Exportar PDF
        </button>
        <a href="{{ route('oc.export', request()->all()) }}" class="ms-btn-new" style="background:#ffffff; color:#0f6bb6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); border:none; display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; font-size: 0.78rem; text-transform: uppercase; font-weight: 800; border-radius: 0.75rem; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Exportar CSV
        </a>
    </div>
</div>
@endsection

@section('content')

<!-- ── FILTERS ────────────────────────────────────────── -->
<div class="ms-filters">
    <form class="filters" method="GET" action="{{ route('oc.dashboard') }}" style="display: flex; gap: 1rem; flex: 1; align-items: flex-end; flex-wrap: wrap;">
        <div style="display: flex; gap: 1rem; flex: 1;">
            <div style="flex: 1;">
                <label class="ms-kpi-label">Desde</label>
                <input id="from" name="from" class="ms-search-input" type="date" value="{{ $filters['from'] ?? '' }}" style="padding-left: 1rem;" />
            </div>
            <div style="flex: 1;">
                <label class="ms-kpi-label">Hasta</label>
                <input id="to" name="to" class="ms-search-input" type="date" value="{{ $filters['to'] ?? '' }}" style="padding-left: 1rem;" />
            </div>
        </div>
        
        <div style="width: 250px;">
            <label class="ms-kpi-label">CECO</label>
            <select id="ceco" name="ceco" class="ms-select" style="width: 100%;">
                <option value="">Todos los CECO</option>
                @foreach($cecos as $c)
                    <option value="{{ $c }}" {{ ($filters['ceco'] ?? '') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>

        <button class="ms-btn-reset ms-btn-new" type="submit" style="padding: 0.75rem 2rem;">
            Actualizar Dashboard
        </button>
        <a href="{{ route('oc.dashboard') }}" class="ms-btn-reset">Limpiar</a>
    </form>
</div>

<!-- ── KPI GRID ────────────────────────────────────────── -->
<div class="ms-kpi-grid">
    <div class="ms-kpi">
        <div class="ms-kpi-icon" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Solicitadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Solicitada'] ?? 0 }}</span>
        </div>
    </div>
    <div class="ms-kpi">
        <div class="ms-kpi-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Aceptadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Aceptada'] ?? 0 }}</span>
        </div>
    </div>
    <div class="ms-kpi">
        <div class="ms-kpi-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Rechazadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Rechazada'] ?? 0 }}</span>
        </div>
    </div>
    <div class="ms-kpi">
        <div class="ms-kpi-icon" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Facturadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Facturado'] ?? 0 }}</span>
        </div>
    </div>
</div>

<!-- ── CHARTS ─────────────────────────────────────────── -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <div class="ms-table-card">
        <div class="ms-table-header">
            <h3 class="ms-table-title">Distribución por CECO</h3>
        </div>
        <div style="padding: 2rem; height: 350px;">
            <canvas id="chartByCeco"></canvas>
        </div>
    </div>
    <div class="ms-table-card">
        <div class="ms-table-header">
            <h3 class="ms-table-title">Evolución de Gasto Mensual</h3>
        </div>
        <div style="padding: 2rem; height: 350px;">
            <canvas id="chartByMonth"></canvas>
        </div>
    </div>
</div>

<!-- ── LISTADO ────────────────────────────────────────── -->
<div class="ms-table-card">
    <div class="ms-table-header">
        <h3 class="ms-table-title">Detalle de Solicitudes</h3>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>CECO</th>
                    <th>Estado</th>
                    <th>Proveedor</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                <tr>
                    <td style="font-weight: 800; color: var(--brand-primary);">{{ $row->ceco }}</td>
                    <td>
                        <span class="chip" style="background: rgba(15, 107, 182, 0.05); color: var(--brand-primary);">{{ $row->estado }}</span>
                    </td>
                    <td><div style="font-weight: 700;">{{ Str::limit($row->proveedor, 35) }}</div></td>
                    <td style="font-weight: 800; font-family: 'Outfit';">
                        ${{ number_format($row->monto, 0, ',', '.') }}
                    </td>
                    <td>{{ \Illuminate\Support\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('oc.gestor') }}" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding: 1.5rem; background: #fcfdfe; border-top: 1px solid var(--border-color);">
        {{ $rows->appends(request()->all())->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Modal Gmail -->
<div id="gmailModal" class="ms-modal" onclick="closeModalOnFondo(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 450px;">
        <div class="ms-modal-header">
            <h2 class="ms-table-title">Exportar Dashboard</h2>
            <button class="ms-btn-reset" style="padding: 0.5rem;" onclick="closeGmailModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="gmailSubmitForm" method="POST" action="{{ route('oc.send_gmail_all') }}" style="padding: 2.5rem;">
            @csrf
            <input type="hidden" name="grafico1" id="grafico1Input">
            <input type="hidden" name="grafico2" id="grafico2Input">
            <div style="margin-bottom: 1.5rem;">
                <label class="ms-kpi-label" style="display: block; margin-bottom: 0.75rem;">Correo de destino</label>
                <input type="email" name="gmail" required class="ms-search-input" placeholder="ejemplo@correo.com" style="padding-left: 1rem;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="ms-btn-reset" onclick="closeGmailModal()">Cancelar</button>
                <button type="submit" class="ms-btn-reset ms-btn-new">Enviar Reporte</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Iniciando Charts...");
        
        const cecoLabels = {!! json_encode($sumByCeco->keys()->toArray()) !!};
        const cecoData = {!! json_encode($sumByCeco->values()->toArray()) !!};
        const monthLabels = {!! json_encode($sumByCecoMonth->keys()->toArray()) !!};
        const monthData = {!! json_encode($sumByCecoMonth->values()->toArray()) !!};

        console.log("CECO Labels:", cecoLabels);
        console.log("CECO Data:", cecoData);
        console.log("Month Labels:", monthLabels);
        console.log("Month Data:", monthData);

        // Chart por CECO (Bar Chart)
        const canvasCeco = document.getElementById('chartByCeco');
        if (canvasCeco) {
            if (cecoData.length === 0) {
                console.warn("No hay datos para el gráfico de CECO");
                const ctx = canvasCeco.getContext('2d');
                ctx.font = "14px sans-serif";
                ctx.fillStyle = "#94a3b8";
                ctx.textAlign = "center";
                ctx.fillText("No hay datos disponibles para mostrar", canvasCeco.width / 2, canvasCeco.height / 2);
            } else {
                const ctxCeco = canvasCeco.getContext('2d');
                new Chart(ctxCeco, {
                    type: 'bar',
                data: {
                    labels: cecoLabels,
                    datasets: [{
                        label: 'Gasto por CECO ($)',
                        data: cecoData,
                        backgroundColor: '#0f6bb6',
                        borderRadius: 8,
                        hoverBackgroundColor: '#b69950',
                    }]
                },
                options: {
                    indexAxis: 'y', // Barras horizontales para mejor lectura de CECOs
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Total: $' + context.raw.toLocaleString('es-CL');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString('es-CL');
                                }
                            }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold' } }
                        }
                    }
                }
            });
        }
    }

        // Chart por Mes
        const canvasMonth = document.getElementById('chartByMonth');
        if (canvasMonth) {
            if (monthData.length === 0) {
                console.warn("No hay datos para el gráfico mensual");
                const ctx = canvasMonth.getContext('2d');
                ctx.font = "14px sans-serif";
                ctx.fillStyle = "#94a3b8";
                ctx.textAlign = "center";
                ctx.fillText("No hay datos suficientes", canvasMonth.width / 2, canvasMonth.height / 2);
            } else {
                const ctxMonth = canvasMonth.getContext('2d');
                new Chart(ctxMonth, {
                    type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Monto Total ($)',
                        data: monthData,
                        borderColor: '#0f6bb6',
                        backgroundColor: 'rgba(15, 107, 182, 0.08)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0f6bb6',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0,0,0,0.03)' },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString('es-CL');
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    }
});

    const gmailModal = document.getElementById('gmailModal');
    const sendAllBtn = document.getElementById('sendAllGmailBtn');

    if (sendAllBtn) {
        sendAllBtn.onclick = function() {
            // Capturar charts antes de abrir modal si es necesario
            document.getElementById('grafico1Input').value = document.getElementById('chartByCeco').toDataURL('image/png');
            document.getElementById('grafico2Input').value = document.getElementById('chartByMonth').toDataURL('image/png');
            gmailModal.classList.add('active');
        }
    }

    function closeGmailModal() {
        gmailModal.classList.remove('active');
    }

    function closeModalOnFondo(e) {
        if (e.target.classList.contains('ms-modal')) closeGmailModal();
    }

    window.downloadPdf = function() {
        const element = document.querySelector('.main-content') || document.body;
        const opt = {
            margin:       10,
            filename:     'OC_Dashboard.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };
        html2pdf().set(opt).from(element).save();
    };
</script>
@endpush
