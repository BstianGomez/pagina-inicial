<x-layouts.app page_title="Analytics" page_subtitle="Resumen estadístico de tus rendiciones.">
    <x-slot name="header_title">Analytics de Rendiciones</x-slot>

    <style>
        /* ── tokens ────────────────────────── */
        :root {
            --blue:   #0f6bb6;
            --green:  #22c55e;
            --amber:  #f59e0b;
            --red:    #ef4444;
            --purple: #8b5cf6;
            --slate:  #64748b;
        }

        /* ── layout ────────────────────────── */
        .an-page   { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.75rem; }
        .an-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
        .an-title  { font-size: 1.5rem; font-weight: 900; color: #0f172a; letter-spacing: -0.03em; }
        .an-sub    { font-size: 0.85rem; color: #64748b; margin-top: 0.15rem; }

        /* ── export btn ────────────────────── */
        .btn-export {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 1.25rem; border-radius: 0.75rem;
            background: var(--blue); color: #fff; font-weight: 800;
            font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.06em;
            text-decoration: none; transition: all 180ms ease;
            box-shadow: 0 4px 12px rgba(15,107,182,.25);
        }
        .btn-export:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(15,107,182,.35); }

        /* ── stat cards ────────────────────── */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
        .stat-card {
            border-radius: 1.25rem;
            padding: 1.25rem 1rem 1.1rem 1.25rem;
            display: flex; flex-direction: column; gap: 0.35rem;
            border-left: 4px solid transparent;
            transition: transform 180ms ease, box-shadow 180ms ease, opacity 180ms ease;
            position: relative; overflow: hidden;
            cursor: pointer; user-select: none;
        }
        .stat-card::before {
            content: '';
            position: absolute; inset: 0;
            border-radius: inherit;
            border: 1px solid rgba(0,0,0,.07);
            pointer-events: none;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
        .stat-card.is-active { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,.18); }
        .stat-card.is-active::before { border-width: 2px; border-color: rgba(0,0,0,.15); }
        .stat-card.is-dimmed { opacity: 0.38; transform: none; }
        .stat-dot  { width: 0.55rem; height: 0.55rem; border-radius: 9999px; display: inline-block; }
        .stat-label{ font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.4rem; }
        .stat-val  { font-size: 2.1rem; font-weight: 900; line-height: 1; }
        .stat-amt  { font-size: 0.72rem; font-weight: 700; opacity: 0.65; }

        /* ── chart panels ──────────────────── */
        .chart-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media(max-width:768px){ .chart-grid { grid-template-columns: 1fr; } }
        .chart-panel {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 1.25rem;
            padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;
        }
        .chart-panel.wide { grid-column: 1 / -1; }
        .panel-title { font-size: 0.78rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em; color: #334155; }

        /* ── table ─────────────────────────── */
        .an-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        .an-table th { background: #f8fafc; color: #64748b; font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.08em; padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .an-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; color: #334155; font-weight: 500; }
        .an-table tr:last-child td { border-bottom: none; }
        .an-table tr:hover td { background: #f8fafc; }
        .status-pill {
            display: inline-flex; align-items: center; padding: 0.2rem 0.6rem;
            border-radius: 9999px; font-size: 0.6rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.06em;
        }
    </style>

    <div class="an-page">

        {{-- Header --}}
        <div class="an-header">
            <div>
                <h1 class="an-title">Analytics de Rendiciones</h1>
                <p class="an-sub">{{ $isAdmin ? 'Vista global de todas las rendiciones' : 'Resumen de tus propias rendiciones' }} · Actualizado {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('analytics.index', ['export' => 'csv']) }}" class="btn-export">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exportar CSV
            </a>
        </div>

        {{-- KPI Cards --}}
        <div class="stat-grid" id="stat-grid">

            <div class="stat-card" data-filter="all"
                 style="background:linear-gradient(135deg,#faf5ff 0%,#f3e8ff 100%);border-left-color:#a855f7;">
                <span class="stat-label" style="color:#9333ea;"><span class="stat-dot" style="background:#a855f7;"></span>Total</span>
                <span class="stat-val" style="color:#6b21a8;">{{ $counts['total'] }}</span>
                <span class="stat-amt" style="color:#a855f7;">${{ number_format($amounts['total'], 0, ',', '.') }}</span>
            </div>

            <div class="stat-card" data-filter="Borrador"
                 style="background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);border-left-color:#eab308;">
                <span class="stat-label" style="color:#d97706;"><span class="stat-dot" style="background:#eab308;"></span>Borrador</span>
                <span class="stat-val" style="color:#92400e;">{{ $counts['borrador'] }}</span>
                <span class="stat-amt" style="color:#d97706;">${{ number_format($amounts['borrador'], 0, ',', '.') }}</span>
            </div>

            <div class="stat-card" data-filter="Enviado"
                 style="background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);border-left-color:#3b82f6;">
                <span class="stat-label" style="color:#2563eb;"><span class="stat-dot" style="background:#3b82f6;"></span>Enviadas</span>
                <span class="stat-val" style="color:#1d4ed8;">{{ $counts['enviados'] }}</span>
                <span class="stat-amt" style="color:#3b82f6;">${{ number_format($amounts['enviados'], 0, ',', '.') }}</span>
            </div>

            <div class="stat-card" data-filter="proceso"
                 style="background:linear-gradient(135deg,#eef2ff 0%,#e0e7ff 100%);border-left-color:#6366f1;">
                <span class="stat-label" style="color:#4f46e5;"><span class="stat-dot" style="background:#6366f1;"></span>En Proceso</span>
                <span class="stat-val" style="color:#3730a3;">{{ $counts['en_proceso'] }}</span>
                <span class="stat-amt" style="color:#4f46e5;">${{ number_format($amounts['en_proceso'], 0, ',', '.') }}</span>
            </div>

            <div class="stat-card" data-filter="Rechazada"
                 style="background:linear-gradient(135deg,#fff5f5 0%,#fee2e2 100%);border-left-color:#ef4444;">
                <span class="stat-label" style="color:#dc2626;"><span class="stat-dot" style="background:#ef4444;"></span>Rechazadas</span>
                <span class="stat-val" style="color:#b91c1c;">{{ $counts['rechazadas'] }}</span>
                <span class="stat-amt" style="color:#ef4444;">${{ number_format($amounts['rechazadas'], 0, ',', '.') }}</span>
            </div>

            <div class="stat-card" data-filter="Reembolsada"
                 style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border-left-color:#22c55e;">
                <span class="stat-label" style="color:#16a34a;"><span class="stat-dot" style="background:#22c55e;"></span>Reembolsadas</span>
                <span class="stat-val" style="color:#15803d;">{{ $counts['reembolsadas'] }}</span>
                <span class="stat-amt" style="color:#22c55e;">${{ number_format($amounts['reembolsadas'], 0, ',', '.') }}</span>
            </div>

            <div class="stat-card" data-filter="Anulada"
                 style="background:linear-gradient(135deg,#f9fafb 0%,#f3f4f6 100%);border-left-color:#9ca3af;">
                <span class="stat-label" style="color:#6b7280;"><span class="stat-dot" style="background:#9ca3af;"></span>Anuladas</span>
                <span class="stat-val" style="color:#4b5563;">{{ $counts['anuladas'] }}</span>
                <span class="stat-amt" style="color:#9ca3af;">${{ number_format($amounts['anuladas'], 0, ',', '.') }}</span>
            </div>

        </div>

        {{-- Charts row --}}
        <div class="chart-grid">

            {{-- Doughnut: distribución por estado --}}
            <div class="chart-panel">
                <p class="panel-title">Distribución por Estado</p>
                <canvas id="chartDonut" style="max-height:280px;"></canvas>
            </div>

            {{-- Bar: categorías --}}
            <div class="chart-panel">
                <p class="panel-title">Gasto por Categoría</p>
                <canvas id="chartCat" style="max-height:280px;"></canvas>
            </div>

            {{-- Line: tendencia mensual --}}
            <div class="chart-panel wide">
                <p class="panel-title">Tendencia Últimos 6 Meses</p>
                <canvas id="chartTrend" style="max-height:220px;"></canvas>
            </div>
        </div>

        {{-- Table --}}
        <div class="chart-panel" style="padding:0;overflow:hidden;">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                <p class="panel-title" style="margin:0;">Listado de Rendiciones</p>
                <span style="font-size:0.72rem;color:#94a3b8;font-weight:700;" id="row-count">{{ $recentReports->count() }} registros</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="an-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            @if($isAdmin)<th>Solicitante</th>@endif
                            <th>Estado</th>
                            <th>Gastos</th>
                            <th style="text-align:right;">Monto</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="report-tbody">
                        @forelse($recentReports as $r)
                        @php
                            $pillMap = [
                                'Borrador'     => ['bg'=>'#fffbeb','text'=>'#92400e'],
                                'Enviado'      => ['bg'=>'#dbeafe','text'=>'#1d4ed8'],
                                'Reembolsada'  => ['bg'=>'#dcfce7','text'=>'#16a34a'],
                                'Cerrada'      => ['bg'=>'#f3f4f6','text'=>'#374151'],
                                'Anulada'      => ['bg'=>'#f3f4f6','text'=>'#9ca3af'],
                            ];
                            $pill = collect($pillMap)->first(fn($v,$k) => str_starts_with($r->status, $k))
                                ?: (str_contains($r->status,'Rechazada') ? ['bg'=>'#fee2e2','text'=>'#dc2626']
                                  : ['bg'=>'#fef3c7','text'=>'#d97706']);
                        @endphp
                        <tr data-status="{{ $r->status }}">
                            <td style="color:#94a3b8;font-weight:600;">{{ $r->id }}</td>
                            <td style="font-weight:700;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $r->title }}</td>
                            @if($isAdmin)<td>{{ $r->user->name ?? '—' }}</td>@endif
                            <td>
                                <span class="status-pill" style="background:{{ $pill['bg'] }};color:{{ $pill['text'] }};">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td style="text-align:center;">{{ $r->expenses->count() }}</td>
                            <td style="text-align:right;font-weight:800;">${{ number_format($r->total_amount, 0, ',', '.') }}</td>
                            <td style="color:#94a3b8;">{{ $r->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('reports.show', $r) }}" style="font-size:0.7rem;font-weight:800;color:#0f6bb6;text-decoration:none;">Ver →</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 8 : 7 }}" style="text-align:center;color:#64748b;padding:1rem;">
                                No hay rendiciones recientes.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                {{ $recentReports->links() }}
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        Chart.defaults.font.family = "'Inter', 'DM Sans', sans-serif";
        Chart.defaults.color = '#64748b';

        const counts         = @json($counts);
        const amounts        = @json($amounts);
        const monthlyLabels  = @json($monthlyLabels);
        const chartsByFilter = @json($chartsByFilter);
        const catColors      = ['#3b82f6','#8b5cf6','#f59e0b','#22c55e','#ef4444','#06b6d4','#f97316','#64748b'];

        // ── Donut ────────────────────────────────────────────────
        const donutChart = new Chart(document.getElementById('chartDonut'), {
            type: 'doughnut',
            data: {
                labels: ['Borrador', 'Enviadas', 'En Proceso', 'Rechazadas', 'Reembolsadas', 'Anuladas'],
                datasets: [{
                    data: [counts.borrador, counts.enviados, counts.en_proceso, counts.rechazadas, counts.reembolsadas, counts.anuladas],
                    backgroundColor: ['#eab308','#3b82f6','#f59e0b','#ef4444','#22c55e','#9ca3af'],
                    borderWidth: 3, borderColor: '#fff',
                    hoverOffset: 10,
                }]
            },
            options: {
                cutout: '68%',
                plugins: {
                    legend: { position: 'right', labels: { padding: 14, font: { size: 11, weight: '700' }, boxWidth: 10, borderRadius: 6 } },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} rendicion${ctx.raw !== 1 ? 'es' : ''}` } }
                }
            }
        });

        // ── Category bar ─────────────────────────────────────────────
        const allCatData = chartsByFilter['all'];
        const catChart = new Chart(document.getElementById('chartCat'), {
            type: 'bar',
            data: {
                labels: allCatData.catLabels,
                datasets: [{
                    label: 'Monto ($)',
                    data: allCatData.catAmounts,
                    backgroundColor: catColors.slice(0, allCatData.catLabels.length),
                    borderRadius: 8,
                }]
            },
            options: {
                indexAxis: 'y',
                animation: { duration: 400 },
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' $' + ctx.raw.toLocaleString('es-CL') } } },
                scales: { x: { grid: { color: '#f1f5f9' }, ticks: { callback: v => '$' + (v / 1000).toFixed(0) + 'k' } }, y: { grid: { display: false } } }
            }
        });

        // ── Trend line ─────────────────────────────────────────────
        const allTrendData = chartsByFilter['all'];
        const trendChart = new Chart(document.getElementById('chartTrend'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [
                    {
                        label: 'Rendiciones',
                        data: allTrendData.monthlyCounts,
                        borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,.08)',
                        fill: true, tension: 0.4, pointBackgroundColor: '#3b82f6', pointRadius: 5, yAxisID: 'y',
                    },
                    {
                        label: 'Monto ($)',
                        data: allTrendData.monthlyAmounts,
                        borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,.05)',
                        fill: true, tension: 0.4, pointBackgroundColor: '#22c55e', pointRadius: 5, yAxisID: 'y1',
                    }
                ]
            },
            options: {
                animation: { duration: 400 },
                plugins: { legend: { position: 'top', labels: { font: { size: 11, weight: '700' }, boxWidth: 10, borderRadius: 6, padding: 14 } } },
                scales: {
                    y:  { grid: { color: '#f1f5f9' }, ticks: { stepSize: 1 } },
                    y1: { position: 'right', grid: { display: false }, ticks: { callback: v => '$' + (v/1000).toFixed(0) + 'k' } },
                    x:  { grid: { display: false } }
                }
            }
        });

        // ── Update charts with filter data ─────────────────────────────
        const donutSliceMap = { 'Borrador':0,'Enviado':1,'proceso':2,'Rechazada':3,'Reembolsada':4,'Anulada':5 };

        function updateCharts(filter) {
            const data = chartsByFilter[filter] || chartsByFilter['all'];

            // Categoria
            catChart.data.labels = data.catLabels;
            catChart.data.datasets[0].data = data.catAmounts;
            catChart.data.datasets[0].backgroundColor = catColors.slice(0, data.catLabels.length);
            catChart.update();

            // Tendencia
            trendChart.data.datasets[0].data = data.monthlyCounts;
            trendChart.data.datasets[1].data = data.monthlyAmounts;
            trendChart.update();

            // Donut: resaltar segmento
            const sliceIdx = donutSliceMap[filter];
            const meta = donutChart.getDatasetMeta(0);
            meta.data.forEach((arc, i) => {
                arc.options.offset = (filter !== 'all' && i === sliceIdx) ? 18 : 0;
                const baseColors = ['#94a3b8','#3b82f6','#f59e0b','#ef4444','#22c55e','#9ca3af'];
                arc.options.backgroundColor = (filter !== 'all' && i !== sliceIdx)
                    ? baseColors[i] + '50'
                    : baseColors[i];
            });
            donutChart.update();
        }

        // ── Interactive card filter ────────────────────────────────
        const cards    = document.querySelectorAll('.stat-card');
        const allRows  = Array.from(document.querySelectorAll('#report-tbody tr[data-status]'));
        const rowCount = document.getElementById('row-count');
        let activeFilter = null;

        const inProcessKeywords = ['Pendiente aprobaci','En revisi','Subsanada','Aprobada por','Pendiente pago','Observada'];
        function isProceso(s) { return inProcessKeywords.some(k => s.includes(k)); }

        function applyFilter(filter) {
            activeFilter = filter;

            // Cards: dim or active
            cards.forEach(c => {
                if (filter === 'all') {
                    c.classList.remove('is-active', 'is-dimmed');
                } else if (c.dataset.filter === filter) {
                    c.classList.add('is-active'); c.classList.remove('is-dimmed');
                } else {
                    c.classList.add('is-dimmed'); c.classList.remove('is-active');
                }
            });

            // Table rows
            let visible = 0;
            allRows.forEach(row => {
                const status = row.dataset.status || '';
                const show = filter === 'all'
                    || status.startsWith(filter)
                    || (filter === 'proceso' && isProceso(status))
                    || (filter === 'Rechazada' && status.includes('Rechazada'));
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (rowCount) rowCount.textContent = visible + ' registro' + (visible !== 1 ? 's' : '');

            // Charts
            updateCharts(filter);
        }

        cards.forEach(card => {
            card.addEventListener('click', () => {
                const f = card.dataset.filter;
                applyFilter(activeFilter === f ? 'all' : f);
            });
        });
    })();
    </script>
</x-layouts.app>
