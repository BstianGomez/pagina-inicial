@extends('viajes.layouts.dashboard')

@section('title', 'Reportes y Estadísticas')
@section('subtitle', 'Análisis visual del portal de viajes')

@section('header')
<div class="ms-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1 class="ms-banner-title">Panel de Inteligencia</h1>
            <p class="ms-banner-sub">Visualice tendencias, gastos y distribución de solicitudes en tiempo real.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <button onclick="Dashboard.openGmailModal()" class="ms-btn-new" style="background:#ea4335; display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; font-size: 0.78rem; text-transform: uppercase; font-weight: 800; border: none; border-radius: 0.75rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Enviar por Gmail
            </button>
            <button onclick="Dashboard.exportPDF()" class="ms-btn-new" style="background:#e11d48; display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; font-size: 0.78rem; text-transform: uppercase; font-weight: 800; border: none; border-radius: 0.75rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Exportar PDF
            </button>
            <button onclick="Dashboard.exportExcel()" class="ms-btn-new" style="background:#0f6bb6; display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; font-size: 0.78rem; text-transform: uppercase; font-weight: 800; border: none; border-radius: 0.75rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exportar CSV
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')

<div id="pdf-export-root">
    <!-- ── FILTROS GLOBALES ────────────────────────── -->
    <div class="ms-filters" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            <span style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--text-main);">Filtro por CECO:</span>
        </div>
        <select id="global-ceco-filter" class="ms-select" style="min-width: 250px;" onchange="Dashboard.fetchData()">
            <option value="all">Todos los Centros de Costo</option>
        </select>
        <button onclick="Dashboard.resetFilters()" class="ms-btn-reset" style="padding: 0.75rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
        </button>
    </div>

    <!-- ── KPI GRID ────────────────────────────────── -->
    <div class="ms-kpi-grid" id="kpi-container" style="margin-bottom: 2rem;">
        <!-- Se llena por JS -->
    </div>

    <!-- ── CHARTS ROW 1 ────────────────────────────── -->
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div class="ms-table-card" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 class="ms-table-title" style="font-size: 1.1rem;">Tendencia de Viajes</h3>
                <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">ÚLTIMOS 6 MESES</span>
            </div>
            <div style="height: 300px;">
                <canvas id="canvas-monthly"></canvas>
            </div>
        </div>

        <div class="ms-table-card" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 class="ms-table-title" style="font-size: 1.1rem;">Estados</h3>
                <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">DISTRIBUCIÓN</span>
            </div>
            <div style="position: relative; height: 200px; display: flex; align-items: center; justify-content: center;">
                <canvas id="canvas-status"></canvas>
                <div id="status-total-box" style="position: absolute; text-align: center;">
                    <b style="font-size: 2rem; display: block; line-height: 1;">0</b>
                    <span style="font-size: 0.65rem; text-transform: uppercase; font-weight: 800; color: var(--text-muted);">Total</span>
                </div>
            </div>
            <div id="status-legend" style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem;"></div>
        </div>
    </div>

    <!-- ── CHARTS ROW 2 ────────────────────────────── -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div class="ms-table-card" style="padding: 2rem;">
            <h3 class="ms-table-title" style="font-size: 1.1rem; margin-bottom: 2rem;">Principales Destinos</h3>
            <div style="height: 300px;">
                <canvas id="canvas-destinations"></canvas>
            </div>
        </div>
        <div class="ms-table-card" style="padding: 2rem;">
            <h3 class="ms-table-title" style="font-size: 1.1rem; margin-bottom: 2rem;">Inversión por CECO</h3>
            <div style="height: 300px;">
                <canvas id="canvas-ceco-spending"></canvas>
            </div>
        </div>
    </div>

    <!-- ── REGISTRO DETALLADO ───────────────────────── -->
    <div class="ms-table-card">
        <div class="ms-table-header">
            <h3 class="ms-table-title">Registro de Actividad</h3>
            <div style="display: flex; gap: 1rem;">
                <input type="text" id="table-search" placeholder="Buscar registros..." class="ms-search-input" style="width: 250px;" onkeyup="Dashboard.filterTable()">
            </div>
        </div>
        <div class="ms-table-wrapper">
            <table class="ms-table" id="main-data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Viajero</th>
                        <th>Destino</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr><td colspan="5" style="text-align:center; padding: 4rem; color: var(--text-muted);">Cargando datos maestros...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Gmail -->
<div id="gmailModal" class="ms-modal" onclick="Dashboard.closeModalOnFondo(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 450px;">
        <div class="ms-modal-header">
            <h2 class="ms-table-title">Exportar Dashboard por Email</h2>
            <button class="ms-btn-reset" style="padding: 0.5rem;" onclick="Dashboard.closeGmailModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div style="padding: 2.5rem;">
            <div style="margin-bottom: 1.5rem;">
                <label class="ms-kpi-label" style="display: block; margin-bottom: 0.75rem;">Correo de destino</label>
                <input type="email" id="gmailDestino" required class="ms-search-input" placeholder="ejemplo@correo.com" style="padding-left: 1rem;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="ms-btn-reset" onclick="Dashboard.closeGmailModal()">Cancelar</button>
                <button type="button" class="ms-btn-new" onclick="Dashboard.sendGmail()" id="btnSendGmail" style="border: none;">Enviar Reporte</button>
            </div>
        </div>
    </div>
</div>

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
        primary: '#0f6bb6',
        secondary: '#b69950',
        accent: '#6366f1'
    },

    init() {
        this.fetchData();
    },

    async fetchData(status = null) {
        if (status) this.currentFilter = status;
        const cecoEl = document.getElementById('global-ceco-filter');
        const ceco = cecoEl ? cecoEl.value : 'all';
        
        try {
            const response = await fetch(`/viajes/reportes/data?status=${this.currentFilter}&ceco=${ceco}`);
            const data = await response.json();
            this.renderAll(data);
        } catch (e) {
            console.error("Error loading dashboard data", e);
        }
    },

    renderAll(data) {
        Object.values(this.charts).forEach(c => c.destroy());
        
        this.populateCecoFilter(data.lista_cecos);
        this.renderKPIs(data.totales);
        this.renderStatusChart(data.totales);
        this.renderMonthlyChart(data.mensual);
        this.renderDestinationsChart(data.destinos);
        this.renderCecoSpendingChart(data.gastos_cecos);
        this.renderTable(data.recientes);
    },

    populateCecoFilter(cecos) {
        const select = document.getElementById('global-ceco-filter');
        if (select.options.length <= 1) {
            cecos.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c;
                opt.textContent = c;
                select.appendChild(opt);
            });
        }
    },

    renderKPIs(t) {
        const container = document.getElementById('kpi-container');
        const gasto = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 0 }).format(t.gasto_total || 0);
        
        const items = [
            { id: 'all', label: 'Total Solicitudes', val: t.total, color: '#3b82f6', icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
            { id: 'gasto', label: 'Inversión Total', val: gasto, color: '#10b981', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
            { id: 'aprobado', label: 'Aprobadas', val: t.aprobadas, color: '#0f6bb6', icon: 'M5 13l4 4L19 7' },
            { id: 'pendiente', label: 'Pendientes', val: t.pendientes, color: '#f59e0b', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }
        ];

        container.innerHTML = items.map(i => `
            <div class="ms-kpi ${this.currentFilter === i.id ? 'active' : ''}" onclick="Dashboard.fetchData('${i.id}')">
                <div class="ms-kpi-icon" style="background: ${i.color}15; color: ${i.color};">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="${i.icon}"/></svg>
                </div>
                <div class="ms-kpi-body">
                    <span class="ms-kpi-label">${i.label}</span>
                    <span class="ms-kpi-value">${i.val}</span>
                </div>
            </div>
        `).join('');
    },

    renderStatusChart(t) {
        document.querySelector('#status-total-box b').textContent = t.total;
        const legend = document.getElementById('status-legend');
        const data = [
            { label: 'Aprobadas', val: t.aprobadas, color: this.colors.aprobado },
            { label: 'Pendientes', val: t.pendientes, color: this.colors.pendiente },
            { label: 'Rechazadas', val: t.rechazadas, color: this.colors.rechazado }
        ];

        legend.innerHTML = data.map(s => `
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; font-weight: 700;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: ${s.color};"></div>
                    <span style="color: var(--text-muted);">${s.label}</span>
                </div>
                <span>${s.val}</span>
            </div>
        `).join('');

        this.charts.status = new Chart(document.getElementById('canvas-status'), {
            type: 'doughnut',
            data: {
                labels: data.map(d => d.label),
                datasets: [{
                    data: data.map(d => d.val),
                    backgroundColor: data.map(d => d.color),
                    borderWidth: 0,
                    cutout: '85%'
                }]
            },
            options: { plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false }
        });
    },

    renderMonthlyChart(data) {
        this.charts.monthly = new Chart(document.getElementById('canvas-monthly'), {
            type: 'bar',
            data: {
                labels: data.map(d => d.mes),
                datasets: [{
                    label: 'Solicitudes',
                    data: data.map(d => d.cantidad),
                    backgroundColor: this.colors.primary,
                    borderRadius: 8,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#e2e8f0' } },
                    x: { grid: { display: false } }
                }
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
                    backgroundColor: this.colors.secondary,
                    borderRadius: 6,
                    indexAxis: 'y'
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

    renderCecoSpendingChart(data) {
        this.charts.cecoSpending = new Chart(document.getElementById('canvas-ceco-spending'), {
            type: 'bar',
            data: {
                labels: data.map(d => d.ceco || 'N/A'),
                datasets: [{
                    data: data.map(d => d.total),
                    backgroundColor: this.colors.primary + '80',
                    borderColor: this.colors.primary,
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(ctx.parsed.y)
                        }
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        ticks: { callback: (v) => '$' + (v/1000).toFixed(0) + 'k' }
                    }
                }
            }
        });
    },

    renderTable(list) {
        const body = document.getElementById('table-body');
        body.innerHTML = list.map(s => `
            <tr data-status="${s.estado}" data-search="${(s.nombre + s.destino).toLowerCase()}">
                <td style="font-weight: 800; color: var(--brand-primary);">#${s.id}</td>
                <td style="font-weight: 700;">${s.nombre}</td>
                <td>${s.destino}</td>
                <td style="font-size: 0.8rem; color: var(--text-muted);">${s.fecha}</td>
                <td><span class="chip chip-${s.estado}">${s.estado.toUpperCase()}</span></td>
            </tr>
        `).join('');
    },

    filterTable() {
        const q = document.getElementById('table-search').value.toLowerCase();
        document.querySelectorAll('#table-body tr').forEach(tr => {
            tr.style.display = tr.getAttribute('data-search').includes(q) ? '' : 'none';
        });
    },

    exportExcel() {
        let csv = "\uFEFFID;Viajero;Destino;Fecha;Estado\n";
        document.querySelectorAll('#table-body tr').forEach(tr => {
            if (tr.style.display !== 'none') {
                const cells = Array.from(tr.cells).map(c => c.innerText.trim());
                csv += cells.join(';') + "\n";
            }
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "reporte_viajes_" + new Date().toISOString().split('T')[0] + ".csv";
        link.click();
    },

    exportPDF() {
        const element = document.getElementById('pdf-export-root');
        const opt = {
            margin: 10,
            filename: 'dashboard_viajes_sofo.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    },

    openGmailModal() {
        document.getElementById('gmailModal').classList.add('active');
    },

    closeGmailModal() {
        document.getElementById('gmailModal').classList.remove('active');
    },

    closeModalOnFondo(e) {
        if (e.target.classList.contains('ms-modal')) this.closeGmailModal();
    },

    async sendGmail() {
        const email = document.getElementById('gmailDestino').value;
        if (!email) {
            return Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Ingrese un correo válido', showConfirmButton: false, timer: 3000 });
        }

        const btn = document.getElementById('btnSendGmail');
        btn.disabled = true;
        btn.innerText = 'Generando y enviando...';

        try {
            const element = document.getElementById('pdf-export-root');
            const opt = {
                margin: 10,
                filename: 'dashboard_viajes_sofo.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            // Convert to base64
            const pdfBase64 = await html2pdf().set(opt).from(element).outputPdf('datauristring');
            
            const response = await fetch('/viajes/reportes/send-gmail', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email, pdf: pdfBase64 })
            });

            const data = await response.json();
            if (data.success) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Reporte enviado correctamente', showConfirmButton: false, timer: 3000 });
                this.closeGmailModal();
            } else {
                throw new Error(data.message || 'Error en el envío');
            }
        } catch (error) {
            console.error(error);
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: error.message || 'No se pudo enviar el reporte', showConfirmButton: false, timer: 4000 });
        } finally {
            btn.disabled = false;
            btn.innerText = 'Enviar Reporte';
        }
    }
};

document.addEventListener('DOMContentLoaded', () => Dashboard.init());
</script>
@endpush

@endsection
