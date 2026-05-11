@extends('viajes.layouts.dashboard')

@section('title', 'Reportes y Estadísticas')
@section('subtitle', 'Análisis visual del portal de viajes')

@section('content')
<div id="pdf-export-root" style="background: #f5f7fb; padding: 20px; border-radius: 20px;">
    <div class="banner" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1>Reportes y Estadísticas</h1>
            <p>Analiza el comportamiento de las solicitudes de viaje en tiempo real con datos actualizados.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            {{-- Banner buttons removed as per user request --}}
        </div>
    </div>

    {{-- Floating Action Button for PDF --}}
    <button id="btn-pdf-float" onclick="Dashboard.exportPDF()" class="fab-pdf" title="Generar Reporte PDF">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </button>
    
    {{-- 0. Global Filters --}}
    <div style="background: #fff; padding: 16px 24px; border-radius: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <svg fill="none" stroke="#64748b" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <span style="font-size:13px; font-weight:700; color:#1e293b;">Filtro Global:</span>
        </div>
        <select id="global-ceco-filter" class="ms-select" style="min-width: 200px; padding: 8px 12px;" onchange="Dashboard.fetchData()">
            <option value="all">Todos los CECOs</option>
            <!-- Dynamically populated -->
        </select>
        <button onclick="Dashboard.resetFilters()" class="btn-refresh-white" style="background:#f1f5f9; color:#64748b; border:none; width:36px; height:36px; border-radius:10px;" title="Resetear Filtros">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </button>
    </div>

    <div class="reports-container">
    {{-- 1. KPI Section --}}
    <div class="kpi-grid" id="kpi-container">
        <!-- Will be populated by JS -->
        <div class="kpi-skeleton"></div>
        <div class="kpi-skeleton"></div>
        <div class="kpi-skeleton"></div>
        <div class="kpi-skeleton"></div>
    </div>

    {{-- 2. Charts Section - Row 1 --}}
    <div class="charts-row">
        {{-- Status Distribution --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3>{{ Auth::user()->rol === 'usuario' ? 'Estado de Mis Solicitudes' : 'Estado de Solicitudes' }}</h3>
                <span>Distribución porcentual</span>
            </div>
            <div class="chart-body dona-layout">
                <div class="canvas-wrapper">
                    <canvas id="canvas-status"></canvas>
                    <div id="status-total-box" class="total-box"></div>
                </div>
                <div id="status-legend" class="custom-legend"></div>
            </div>
        </div>

        {{-- Monthly Trends --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3>{{ Auth::user()->rol === 'usuario' ? 'Mis Solicitudes por Mes' : 'Solicitudes por Mes' }}</h3>
                <span>Tendencia últimos 6 meses</span>
            </div>
            <div class="chart-body">
                <div class="chart-canvas-wrap">
                    <canvas id="canvas-monthly"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Charts Section - Row 2 --}}
    <div class="charts-row">
        {{-- Top Destinations --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3>Top Destinos</h3>
                <span>Lugares más visitados</span>
            </div>
            <div class="chart-body">
                <div class="chart-canvas-wrap">
                    <canvas id="canvas-destinations"></canvas>
                </div>
            </div>
        </div>

        {{-- Traveler Type --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3>Distribución por Tipo</h3>
                <span>Internos vs Externos</span>
            </div>
            <div class="chart-body">
                <div class="chart-canvas-wrap">
                    <canvas id="canvas-type"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Charts Section - Row 3 --}}
    <div class="charts-row {{ !in_array(Auth::user()->rol ?? 'usuario', ['admin', 'super_admin', 'aprobador', 'gestor']) ? 'full-width-row' : '' }}">
        {{-- CECO Distribution (Quantity) - Privileged Only --}}
        @if(in_array(Auth::user()->rol ?? 'usuario', ['admin', 'super_admin', 'aprobador', 'gestor']))
        <div class="chart-card">
            <div class="chart-header">
                <h3>Distribución por CECO</h3>
                <span>Cant. de solicitudes por centro de costos</span>
            </div>
            <div class="chart-body">
                <div class="mini-chart" style="height: 320px; display: flex; justify-content: center;">
                    <canvas id="canvas-ceco"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- CECO Spending (Total) - Visible for all --}}
        <div class="chart-card">
            <div class="chart-header">
                <h3>{{ Auth::user()->rol === 'usuario' ? 'Mi Gasto por CECO' : 'Gasto Total por CECO' }}</h3>
                <span>Suma de pasajes, hoteles y traslados</span>
            </div>
            <div class="chart-body">
                <div class="mini-chart" style="height: 320px;">
                    <canvas id="canvas-ceco-spending"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Table Section --}}
    <div class="table-container card">
        <div class="table-header">
            <div>
                <h3>{{ Auth::user()->rol === 'usuario' ? 'Mis Registros de Solicitudes' : 'Registro de Solicitudes' }}</h3>
                <p>Listado completo para auditoría</p>
            </div>
            <div class="table-actions">
                <input type="text" id="table-search" placeholder="Buscar..." onkeyup="Dashboard.filterTable()">
                <select id="table-filter-status" onchange="Dashboard.filterTable()">
                    <option value="all">Todos los estados</option>
                    <option value="aprobado">Aprobados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="rechazado">Rechazados</option>
                </select>
            </div>
        </div>
        <div class="scrollable-table">
            <table id="main-data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Solicitante</th>
                        <th>Destino</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted);">Cargando registros...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<style>
    .reports-container { display: flex; flex-direction: column; gap: 24px; }
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
    .charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
    @media (max-width: 1024px) { .charts-row { grid-template-columns: 1fr; } }

    .chart-card { 
        background: #fff; border-radius: 24px; padding: 30px; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid rgba(255,255,255,0.8);
        page-break-inside: avoid;
    }
    .chart-body { position: relative; }
    /* Contenedor con altura fija para evitar que Chart.js encoja en scroll */
    .chart-canvas-wrap {
        position: relative;
        height: 220px;
        width: 100%;
    }
    .chart-canvas-wrap canvas {
        position: absolute;
        top: 0; left: 0;
        width: 100% !important;
        height: 100% !important;
    }  
    .chart-header h3 { margin: 0; font-size: 18px; font-weight: 800; color: #1e293b; }
    .chart-header span { font-size: 12px; color: #64748b; }

    .kpi-card { 
        background: #fff; padding: 24px; border-radius: 20px; 
        display: flex; align-items: center; gap: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: all 0.3s;
        border: 1px solid #f1f5f9;
        cursor: pointer;
        position: relative;
    }
    .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.08); }
    .kpi-card.active { background: #f8fafc; transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); border-color: #e2e8f0; }
    .kpi-icon { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; }

    .table-container { background: #fff; border-radius: 20px; border: 1px solid #e2e8f0; margin-top: 20px; }
    .dona-layout { display: flex; align-items: center; justify-content: center; gap: 40px; flex-wrap: wrap; }
    .canvas-wrapper { position: relative; width: 180px; height: 180px; flex-shrink: 0; }
    .total-box { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }
    .total-box b { display: block; font-size: 28px; color: inherit; line-height: 1.2; }
    .total-box span { font-size: 11px; color: inherit; opacity: 0.7; text-transform: uppercase; font-weight: 700; }

    .custom-legend { display: flex; flex-direction: column; gap: 12px; flex: 1; min-width: 200px; max-width: 320px; }
    .legend-item { display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding-bottom: 8px; border-bottom: 1px solid rgba(148, 163, 184, 0.2); }
    .legend-item:last-child { border-bottom: none; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 8px; }

    .btn-export-white { display:flex; align-items:center; gap:8px; padding:12px 24px; background:#fff; color:#4f46e5; border:none; border-radius:12px; font-weight:800; cursor:pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s; }
    .btn-export-white:hover { background:#1e293b; color:#fff; transform:translateY(-2px); }
    
    .btn-refresh-white { display:flex; align-items:center; justify-content:center; width:46px; height:46px; background:rgba(255,255,255,0.2); color:#fff; border:1px solid rgba(255,255,255,0.3); border-radius:12px; cursor:pointer; transition:all 0.3s; backdrop-filter: blur(8px); }
    .btn-refresh-white:hover { background:rgba(255,255,255,0.4); transform:rotate(180deg); }

    .full-width-row { grid-template-columns: 1fr !important; }
    .mini-chart { width: 100%; height: auto; min-height: 120px; }
    .table-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
    .table-actions { display: flex; gap: 12px; }
    .table-actions input, .table-actions select { padding: 8px 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 13px; }
    
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 12px 24px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    td { padding: 14px 24px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }

    /* PDF Mode */
    /* Floating Action Button Styling */
    .fab-pdf {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: #4f46e5;
        color: #fff;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.4);
        cursor: pointer;
        z-index: 999;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .fab-pdf:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 40px rgba(79, 70, 229, 0.6);
        background: #4338ca;
    }
    .fab-pdf:active { transform: scale(0.95); }

    body.pdf-mode .fab-pdf, body.pdf-mode #btn-pdf-banner { display: none !important; }
    body.pdf-mode .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
    body.pdf-mode .content { padding: 20px !important; width: 1100px !important; }
    body.pdf-mode #pdf-export-root { background: transparent !important; box-shadow: none !important; }
    body.pdf-mode .charts-row { display: flex !important; flex-direction: column !important; gap: 40px !important; }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
const Dashboard = {
    charts: {},
    currentFilter: 'all',
    colors: {
        aprobado: '#10b981',
        pendiente: '#f59e0b',
        rechazado: '#ef4444',
        primary: '#6366f1',
        secondary: '#8b5cf6',
        accent: '#f43f5e'
    },

    init() {
        this.fetchData();
    },

    async fetchData(status = null) {
        try {
            if (status) this.currentFilter = status;
            const cecoEl = document.getElementById('global-ceco-filter');
            const ceco = cecoEl ? cecoEl.value : 'all';
            
            const response = await fetch(`/viajes/reportes/data?status=${this.currentFilter}&ceco=${ceco}`);
            const data = await response.json();
            
            this.renderAll(data);
            this.highlightKPI(this.currentFilter);
        } catch (error) {
            console.error('Error loading data:', error);
        }
    },

    renderAll(data) {
        // Destroy existing charts to prevent overlap
        Object.values(this.charts).forEach(c => c.destroy());
        
        this.populateCecoFilter(data.lista_cecos);
        this.renderKPIs(data.totales);
        this.renderStatusChart(data.totales);
        this.renderMonthlyChart(data.mensual);
        this.renderDestinationsChart(data.destinos);
        this.renderTypeChart(data.tipos);
        
        if (document.getElementById('canvas-ceco')) {
            this.renderCecoChart(data.cecos);
        }
        if (document.getElementById('canvas-ceco-spending')) {
            this.renderCecoSpendingChart(data.gastos_cecos);
        }
        
        this.renderTable(data);
    },

    populateCecoFilter(cecos) {
        const select = document.getElementById('global-ceco-filter');
        const current = select.value;
        
        // Only repopulate if it hasn't been populated yet or the list changed significantly
        if (select.options.length <= 1) {
            cecos.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c;
                opt.textContent = c;
                select.appendChild(opt);
            });
            select.value = current;
        }
    },

    renderKPIs(t) {
        const container = document.getElementById('kpi-container');
        const formattedGasto = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 0 }).format(t.gasto_total || 0);
        
        const items = [
            { 
                id: 'all', label: 'Total Solicitudes', val: t.total, color: '#6366f1', bg: '#eef2ff',
                icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
            },
            { 
                id: 'gasto', label: 'Gasto Total', val: formattedGasto, color: '#0d9488', bg: '#f0fdfa',
                icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            },
            { 
                id: 'aprobado', label: 'Aprobadas', val: t.aprobadas, color: '#10b981', bg: '#ecfdf5',
                icon: 'M5 13l4 4L19 7'
            },
            { 
                id: 'pendiente', label: 'Pendientes', val: t.pendientes, color: '#f59e0b', bg: '#fffbeb',
                icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
            },
            { 
                id: 'rechazado', label: 'Rechazadas', val: t.rechazadas, color: '#ef4444', bg: '#fef2f2',
                icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
            }
        ];
        container.innerHTML = items.map(i => `
            <div class="kpi-card ${this.currentFilter === i.id ? 'active' : ''}" 
                 onclick="Dashboard.fetchData('${i.id}')" 
                 id="kpi-${i.id}"
                 style="border-left: 6px solid ${i.color};">
                <div class="kpi-icon" style="background:${i.bg}">
                    <svg fill="none" stroke="${i.color}" viewBox="0 0 24 24" style="width:26px; height:26px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="${i.icon}"/>
                    </svg>
                </div>
                <div>
                    <span style="font-size:11px; font-weight:800; color:${i.color}; text-transform:uppercase; display:block; margin-bottom:4px; letter-spacing:0.5px;">${i.label}</span>
                    <b style="font-size:28px; color:#1e293b; display:block; line-height:1;">${i.val}</b>
                </div>
            </div>
        `).join('');
    },

    highlightKPI(status) {
        document.querySelectorAll('.kpi-card').forEach(card => card.classList.remove('active'));
        const active = document.getElementById(`kpi-${status}`);
        if (active) active.classList.add('active');
    },

    renderStatusChart(t) {
        document.getElementById('status-total-box').innerHTML = `<b>${t.total}</b><span>Total</span>`;
        const legend = document.getElementById('status-legend');
        const statusMap = [
            { label: 'Aprobadas', val: t.aprobadas, col: this.colors.aprobado },
            { label: 'Pendientes', val: t.pendientes, col: this.colors.pendiente },
            { label: 'Rechazadas', val: t.rechazadas, col: this.colors.rechazado }
        ];
        legend.innerHTML = statusMap.map(s => `
            <div class="legend-item">
                <div><span class="legend-dot" style="background:${s.col}"></span>${s.label}</div>
                <b>${s.val}</b>
            </div>
        `).join('');

        this.charts.status = new Chart(document.getElementById('canvas-status'), {
            type: 'doughnut',
            data: {
                labels: ['Aprobadas', 'Pendientes', 'Rechazadas'],
                datasets: [{
                    data: [t.aprobadas, t.pendientes, t.rechazadas],
                    backgroundColor: [this.colors.aprobado, this.colors.pendiente, this.colors.rechazado],
                    borderWidth: 0, cutout: '80%'
                }]
            },
            options: { 
                plugins: { legend: { display: false } },
                onClick: (e, elements) => {
                    if (elements.length > 0) {
                        const idx = elements[0].index;
                        const statusMap = ['aprobado', 'pendiente', 'rechazado'];
                        this.fetchData(statusMap[idx]);
                    }
                }
            }
        });
    },

    renderMonthlyChart(data) {
        this.charts.monthly = new Chart(document.getElementById('canvas-monthly'), {
            type: 'bar',
            data: {
                labels: data.map(d => d.mes),
                datasets: [{
                    data: data.map(d => d.cantidad),
                    backgroundColor: this.colors.primary + '66',
                    borderColor: this.colors.primary,
                    borderWidth: 2, borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    },

    renderDestinationsChart(data) {
        this.charts.destinations = new Chart(document.getElementById('canvas-destinations'), {
            type: 'bar',
            data: {
                labels: data.map(d => d.destino),
                datasets: [{
                    data: data.map(d => d.cantidad),
                    backgroundColor: this.colors.secondary + '66',
                    borderColor: this.colors.secondary,
                    borderWidth: 2, borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    },

    renderTypeChart(t) {
        this.charts.type = new Chart(document.getElementById('canvas-type'), {
            type: 'bar',
            data: {
                labels: ['Internos', 'Externos'],
                datasets: [{
                    data: [t.internos, t.externos],
                    backgroundColor: [this.colors.primary + 'cc', this.colors.secondary + 'cc'],
                    borderRadius: 6
                }]
            },
            options: { 
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true }, y: { grid: { display: false } } }
            }
        });
    },

    renderCecoChart(data) {
        this.charts.ceco = new Chart(document.getElementById('canvas-ceco'), {
            type: 'pie',
            data: {
                labels: data.map(d => d.ceco || 'N/A'),
                datasets: [{
                    data: data.map(d => d.cantidad),
                    backgroundColor: [
                        this.colors.primary, 
                        this.colors.secondary, 
                        this.colors.accent, 
                        '#f59e0b', 
                        '#10b981', 
                        '#64748b'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'right',
                        align: 'center',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 12, weight: '500' }
                        }
                    } 
                } 
            }
        });
    },

    renderCecoSpendingChart(data) {
        this.charts.cecoSpending = new Chart(document.getElementById('canvas-ceco-spending'), {
            type: 'bar',
            data: {
                labels: data.map(d => d.ceco || 'N/A'),
                datasets: [{
                    label: 'Gasto Total ($)',
                    data: data.map(d => d.total),
                    backgroundColor: this.colors.primary + 'bb',
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                label += new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(context.parsed.y);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 0 }).format(value)
                        }
                    }
                }
            }
        });
    },

    renderTable(data) {
        const body = document.getElementById('table-body');
        body.innerHTML = data.recientes.map(s => `
            <tr data-status="${s.estado}" data-search="${(s.nombre + s.destino).toLowerCase()}">
                <td>#${s.id}</td>
                <td><b>${s.nombre}</b></td>
                <td>${s.destino}</td>
                <td>${s.fecha}</td>
                <td>${this.getStatusBadge(s.estado)}</td>
            </tr>
        `).join('');
    },

    getStatusBadge(status) {
        const configs = {
            aprobado: { bg: '#f0fdf4', col: '#15803d', label: 'Aprobado' },
            pendiente: { bg: '#fef9c3', col: '#a16207', label: 'Pendiente' },
            rechazado: { bg: '#fff1f2', col: '#be123c', label: 'Rechazado' },
            gestionado: { bg: '#f5f3ff', col: '#7c3aed', label: 'Gestionado' }
        };
        const c = configs[status] || { bg: '#f1f5f9', col: '#64748b', label: status };
        return `<span style="background:${c.bg}; color:${c.col}; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; text-transform:capitalize;">${c.label}</span>`;
    },

    resetFilters() {
        this.currentFilter = 'all';
        const cecoEl = document.getElementById('global-ceco-filter');
        if (cecoEl) cecoEl.value = 'all';
        this.fetchData();
    },

    filterTable() {
        const q = document.getElementById('table-search').value.toLowerCase();
        const status = document.getElementById('table-filter-status').value;
        document.querySelectorAll('#table-body tr').forEach(tr => {
            const matchesQ = tr.getAttribute('data-search').includes(q);
            const matchesStatus = status === 'all' || tr.getAttribute('data-status') === status;
            tr.style.display = (matchesQ && matchesStatus) ? '' : 'none';
        });
    },

    exportExcel() {
        const table = document.getElementById('main-data-table');
        const rows = Array.from(table.querySelectorAll('tr'));
        
        let csv = [];
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cols = Array.from(row.querySelectorAll('th, td'))
                    .map(col => `"${col.innerText.replace(/"/g, '""')}"`);
                csv.push(cols.join(','));
            }
        });

        const csvContent = "\uFEFF" + csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.setAttribute('download', 'reporte_viajes_data.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    },

    exportPDF() {
        const element = document.getElementById('pdf-export-root');
        const buttonsToHide = [
            document.getElementById('btn-pdf-banner'),
            document.getElementById('btn-pdf-float'),
            document.getElementById('btn-excel'),
            document.getElementById('btn-refresh')
        ];
        
        buttonsToHide.forEach(btn => { if(btn) btn.style.display = 'none'; });
        document.body.classList.add('pdf-mode');

        const opt = {
            margin: 10,
            filename: 'reporte_viajes_completo.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        if (typeof html2pdf !== 'function') {
            alert('Error: La librería de PDF no se cargó correctamente.');
            return;
        }

        html2pdf().set(opt).from(element).save().then(() => {
            document.body.classList.remove('pdf-mode');
            buttonsToHide.forEach(btn => { if(btn) btn.style.display = 'flex'; });
        }).catch(err => {
            alert('Error al generar PDF: ' + err.message);
            document.body.classList.remove('pdf-mode');
            buttonsToHide.forEach(btn => { if(btn) btn.style.display = 'flex'; });
        });
    }
};

document.addEventListener('DOMContentLoaded', () => Dashboard.init());
</script>
@endpush
@endsection
