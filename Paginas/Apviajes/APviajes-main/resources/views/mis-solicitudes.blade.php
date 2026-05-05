@extends('layouts.dashboard')

@section('title', 'Mis Solicitudes')
@section('subtitle', 'Historial de solicitudes enviadas')

@section('header')
<div class="ms-banner">
    <div class="ms-banner-content">
        <div>
            @php 
                $rol = Auth::user()->rol ?? 'usuario';
                $esPrivilegiado = in_array($rol, ['admin', 'super_admin', 'aprobador', 'gestor']);
            @endphp
            <h1 class="ms-banner-title">{{ $esPrivilegiado ? 'Panel de Solicitudes Global' : 'Mis Solicitudes' }}</h1>
            <p class="ms-banner-sub">{{ $esPrivilegiado ? 'Consulta y gestiona todas las solicitudes de viaje del sistema.' : 'Consulta y filtra el estado de todas tus solicitudes de viaje.' }}</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <button onclick="exportExcel()" class="ms-btn-excel" title="Descargar Excel">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Excel
            </button>
            <a href="/solicitudes" class="ms-btn-new">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Nueva solicitud
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
<div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:14px 20px;border-radius:12px;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- ── KPI CARDS ──────────────────────────────────────── --}}
<div class="ms-kpi-grid">
    <div class="ms-kpi" id="card-total" onclick="filtrarTabla('todos')" style="border-left: 6px solid #1d4ed8;">
        <div class="ms-kpi-icon" style="background:#eff6ff;">
            <svg fill="none" stroke="#1d4ed8" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label" style="color:#1d4ed8;">Total</span>
            <span class="ms-kpi-value" style="color:#1e293b;">{{ $total }}</span>
            <span class="ms-kpi-desc">solicitudes enviadas</span>
        </div>
    </div>
    <div class="ms-kpi" id="card-pendiente" onclick="filtrarTabla('pendiente')" style="border-left: 6px solid #a16207;">
        <div class="ms-kpi-icon" style="background:#fef9c3;">
            <svg fill="none" stroke="#a16207" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label" style="color:#a16207;">En Proceso</span>
            <span class="ms-kpi-value" style="color:#1e293b;">{{ $pendientes }}</span>
            <span class="ms-kpi-desc">en espera</span>
        </div>
    </div>
    <div class="ms-kpi" id="card-aprobado" onclick="filtrarTabla('aprobado')" style="border-left: 6px solid #15803d;">
        <div class="ms-kpi-icon" style="background:#f0fdf4;">
            <svg fill="none" stroke="#15803d" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label" style="color:#15803d;">Aprobadas</span>
            <span class="ms-kpi-value" style="color:#1e293b;">{{ $aprobadas }}</span>
            <span class="ms-kpi-desc">aprobadas</span>
        </div>
    </div>
    <div class="ms-kpi" id="card-rechazado" onclick="filtrarTabla('rechazado')" style="border-left: 6px solid #be123c;">
        <div class="ms-kpi-icon" style="background:#fff1f2;">
            <svg fill="none" stroke="#be123c" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label" style="color:#be123c;">Rechazadas</span>
            <span class="ms-kpi-value" style="color:#1e293b;">{{ $rechazadas }}</span>
            <span class="ms-kpi-desc">rechazadas</span>
        </div>
    </div>
</div>

<hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 10px 0 25px; opacity: 0.6;">

{{-- ── FILTROS ─────────────────────────────────────────── --}}
<div class="ms-filters">
    <div class="ms-search-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="ms-search-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input id="busqueda" type="text" placeholder="Buscar por destino, motivo..." class="ms-search-input" oninput="aplicarFiltros()">
    </div>
    <div class="ms-filter-group">
        <select id="filtroEstado" class="ms-select" onchange="aplicarFiltros()">
            <option value="todos">Todos los estados</option>
            <option value="pendiente">En Estimación</option>
            <option value="en_aprobacion">Pendiente Aprobación</option>
            <option value="aprobado">Aprobado</option>
            <option value="rechazado">Rechazado</option>
            <option value="gestionado">Completado</option>
        </select>
        <select id="filtroTipo" class="ms-select" onchange="aplicarFiltros()">
            <option value="todos">Interno / Externo</option>
            <option value="interno">Interno</option>
            <option value="externo">Externo</option>
        </select>
        <button onclick="resetFiltros()" class="ms-btn-reset">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Limpiar
        </button>
    </div>
</div>

{{-- ── TABLA ───────────────────────────────────────────── --}}
<div class="ms-table-card">
    <div class="ms-table-header">
        <div>
            <h3 class="ms-table-title">Historial de Solicitudes</h3>
            <span id="contadorResultados" class="ms-table-count"></span>
        </div>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table" id="tablaSolicitudes">
            <thead>
                <tr>
                    <th>ID</th>
                    @if($esPrivilegiado)
                    <th>Solicitante</th>
                    @endif
                    <th>Destino</th>
                    <th>Fecha Viaje</th>
                    <th>Retorno</th>
                    <th>Motivo</th>
                    <th>CECO</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Detalles</th>
                    <th>Enviada</th>
                </tr>
            </thead>
            <tbody id="tbodySolicitudes">
                @forelse($solicitudes as $sol)
                <tr data-estado="{{ $sol->estado }}"
                    data-tipo="{{ $sol->tipo }}"
                    data-texto="{{ strtolower($sol->destino . ' ' . $sol->motivo . ' ' . $sol->ceco . ' ' . ($sol->solicitante->name ?? '')) }}"
                    onclick="abrirModal({{ $sol->toJson() }})"
                    style="cursor:pointer;">
                    <td class="ms-td-id no-wrap">#{{ $sol->id }}</td>
                    @if($esPrivilegiado)
                    <td class="ms-td-muted no-wrap" style="padding: 10px 8px;">
                        <div style="font-weight:700; color:var(--ink); font-size:12px;">{{ $sol->solicitante->name ?? 'N/A' }}</div>
                    </td>
                    @endif
                    <td class="ms-td-dest no-wrap" style="font-size:13px;">{{ $sol->destino }}</td>
                    <td class="ms-td-muted no-wrap" style="font-size:12px;">{{ $sol->fecha_viaje->format('d M, Y') }}</td>
                    <td class="ms-td-muted no-wrap" style="font-size:12px;">{{ $sol->fecha_retorno?->format('d M, Y') ?? '—' }}</td>
                    <td class="ms-td-muted" style="min-width:180px; white-space:normal; font-size:12px;">{{ $sol->motivo }}</td>
                    <td class="ms-td-muted no-wrap" style="font-size:11px;">{{ $sol->ceco }}</td>
                    <td class="no-wrap" style="padding: 10px 8px;">
                        @if($sol->tipo === 'interno')
                            <span class="chip chip-interno">Interno</span>
                        @else
                            <span class="chip chip-externo">Externo</span>
                        @endif
                    </td>
                    <td>
                        @if($sol->estado === 'pendiente')
                            <span class="chip chip-pendiente">⚖️ En Estimación</span>
                        @elseif($sol->estado === 'en_aprobacion')
                            <span class="chip chip-info">⏳ Pend. Aprobación</span>
                        @elseif($sol->estado === 'aprobado')
                            <span class="chip chip-aprobado">✓ Aprobado</span>
                        @elseif($sol->estado === 'rechazado')
                            <span class="chip chip-rechazado">✕ Rechazado</span>
                        @elseif($sol->estado === 'gestionado')
                            <span class="chip chip-gestionado">★ Completado</span>
                        @endif
                    </td>
                    <td>
                        @if($sol->estado === 'rechazado' && $sol->comentario_rechazo)
                            <span style="font-size:12px;color:#be123c;font-style:italic;">
                                {{ Str::limit($sol->comentario_rechazo, 40) }}
                            </span>
                        @elseif($sol->estado === 'gestionado' && $sol->gestion)
                            <span style="font-size:12px;color:#7c3aed;font-weight:600;">
                                Reserva: {{ $sol->gestion->nro_reserva ?? '—' }}
                                @if($sol->archivos->count())
                                    &nbsp;·&nbsp;
                                    @foreach($sol->archivos as $arch)
                                    <a href="{{ $arch->urlDescarga() }}" style="color:#1d4ed8;text-decoration:none;">
                                        📎 {{ Str::limit($arch->nombre_original, 15) }}
                                    </a>
                                    @endforeach
                                @endif
                            </span>
                        @elseif($sol->estado === 'en_aprobacion')
                            <span style="font-size:12px;color:#0b5fa5;">Estimada: ${{ number_format($sol->monto_estimado, 0, ',', '.') }}</span>
                        @elseif($sol->estado === 'aprobado')
                            <span style="font-size:12px;color:#15803d;">Enviada al gestor</span>
                        @else
                            <span class="ms-td-muted">—</span>
                        @endif
                    </td>
                    <td class="ms-td-muted">{{ $sol->created_at->format('d M, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="padding:64px 24px;text-align:center;color:var(--muted);">
                        <div style="width:56px;height:56px;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                            <svg fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p style="font-size:15px;font-weight:700;margin:0 0 6px;color:var(--ink);">Sin solicitudes aún</p>
                        <p style="font-size:13px;margin:0 0 16px;">¿Necesitas viajar? Crea tu primera solicitud.</p>
                        <a href="/solicitudes" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Nueva solicitud
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL DETALLES ──────────────────────────────────── --}}
<div id="modalDetalles" class="ms-modal" onclick="cerrarModal(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()">
        <div class="ms-modal-header">
            <div>
                <h2 id="m-titulo">Detalles de Solicitud</h2>
                <span id="m-id" style="font-family:monospace; color:var(--muted);">#000</span>
            </div>
            <button class="ms-modal-close" onclick="cerrarModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="ms-modal-body">
            <div class="ms-detail-grid">
                <div class="ms-detail-section">
                    <h4>Información del Viajero</h4>
                    <div class="ms-detail-item"><b>Nombre:</b> <span id="m-nombre"></span></div>
                    <div id="m-externo-info" style="display:none;">
                        <div class="ms-detail-item"><b>RUT:</b> <span id="m-rut"></span></div>
                        <div class="ms-detail-item"><b>Email:</b> <span id="m-email-ext"></span></div>
                        <div class="ms-detail-item"><b>Cargo:</b> <span id="m-cargo"></span></div>
                    </div>
                    <div class="ms-detail-item"><b>Tipo:</b> <span id="m-tipo"></span></div>
                </div>
                <div class="ms-detail-section">
                    <h4>Detalles del Viaje</h4>
                    <div class="ms-detail-item"><b>Destino:</b> <span id="m-destino"></span></div>
                    <div class="ms-detail-item"><b>CECO:</b> <span id="m-ceco"></span></div>
                    <div class="ms-detail-item"><b>Fecha Viaje:</b> <span id="m-fecha-v"></span></div>
                    <div class="ms-detail-item"><b>Fecha Retorno:</b> <span id="m-fecha-r"></span></div>
                </div>
                <div class="ms-detail-section" style="grid-column: span 2;">
                    <h4>Motivo y Logística</h4>
                    <div class="ms-detail-item" style="display:block; margin-bottom:12px;"><b>Motivo:</b> <p id="m-motivo" style="margin:4px 0 0; color:var(--muted); font-size:13px;"></p></div>
                    <div style="display:flex; gap:20px;">
                        <div class="ms-detail-item"><b>Alojamiento:</b> <span id="m-alojamiento"></span></div>
                        <div class="ms-detail-item"><b>Traslado:</b> <span id="m-traslado"></span></div>
                    </div>
                </div>
                <div id="m-gastos-section" class="ms-detail-section" style="grid-column: span 2; display:none;">
                    <h4>Gastos Extras Solicitados</h4>
                    <ul id="m-gastos-list" style="margin:0; padding-left:20px; font-size:13px; color:var(--muted);"></ul>
                </div>
                <div id="m-status-section" class="ms-detail-section" style="grid-column: span 2; background:#f8fafc; padding:16px; border-radius:12px; border:1px solid var(--line);">
                    <h4>Estado y Resolución</h4>
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <div>
                            <div class="ms-detail-item"><b>Estado Actual:</b> <span id="m-estado"></span></div>
                            <div id="m-aprobado-info" style="display:none;">
                                <div class="ms-detail-item"><b>Aprobado por:</b> <span id="m-aprobador"></span></div>
                                <div class="ms-detail-item"><b>Comentario:</b> <span id="m-com-aprob"></span></div>
                            </div>
                            <div id="m-rechazado-info" style="display:none;">
                                <div class="ms-detail-item"><b>Rechazado por:</b> <span id="m-rechazador"></span></div>
                                <div class="ms-detail-item" style="color:#be123c;"><b>Motivo Rechazo:</b> <span id="m-com-rech"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.ms-banner {
    background: linear-gradient(135deg, #0b5fa5 0%, #1470c2 100%);
    border-radius: 20px; padding: 28px 36px; margin-bottom: 28px;
    box-shadow: 0 8px 32px rgba(15,107,182,0.18); position: relative; overflow: hidden;
}
.ms-banner::after { content:''; position:absolute; top:-60px; right:-60px; width:220px; height:220px; background:rgba(255,255,255,0.06); border-radius:50%; pointer-events:none; }
.ms-banner-content { display:flex; justify-content:space-between; align-items:center; gap:16px; }
.ms-banner-title { font-family:"Space Grotesk",sans-serif; font-size:26px; font-weight:800; color:#fff; margin:0 0 6px; letter-spacing:-0.5px; }
.ms-banner-sub { font-size:14px; color:rgba(255,255,255,0.8); margin:0; }
.ms-btn-new { display:flex; align-items:center; gap:8px; background:#f59e0b; border:none; color:#fff; text-decoration:none; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:700; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition:all 0.2s; white-space:nowrap; cursor:pointer; }
.ms-btn-new:hover { background:#d97706; transform:translateY(-1px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); }
.ms-btn-excel { display:flex; align-items:center; gap:8px; background:#10b981; border:none; color:#fff; text-decoration:none; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:700; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition:all 0.2s; white-space:nowrap; cursor:pointer; }
.ms-btn-excel:hover { background:#059669; transform:translateY(-1px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); }

.ms-kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
.ms-kpi { background:#fff; border:1px solid #f1f5f9; border-radius:16px; padding:20px; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; gap:16px; box-shadow:0 2px 8px rgba(0,0,0,0.04); position: relative; }
.ms-kpi:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.08); }
.ms-kpi.active { background: #f8fafc; box-shadow:0 0 0 3px rgba(15,107,182,0.08),0 4px 16px rgba(0,0,0,0.06); border-color: #e2e8f0; }
.ms-kpi-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.ms-kpi-body { display:flex; flex-direction:column; gap:2px; }
.ms-kpi-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:var(--muted); }
.ms-kpi-value { font-size:28px; font-weight:800; line-height:1.1; }
.ms-kpi-desc { font-size:12px; color:var(--muted); }

.ms-filters { background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; margin-bottom:16px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; box-shadow:0 2px 8px rgba(0,0,0,0.03); }
.ms-search-wrap { flex:1; min-width:220px; position:relative; }
.ms-search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
.ms-search-input { width:100%; padding:9px 14px 9px 38px; border:1px solid var(--line); border-radius:10px; font-family:inherit; font-size:13px; background:#f8fafc; color:var(--ink); transition:all 0.2s; }
.ms-search-input:focus { outline:none; border-color:var(--brand); background:#fff; box-shadow:0 0 0 3px rgba(15,107,182,0.08); }
.ms-filter-group { display:flex; align-items:center; gap:10px; }
.ms-select { padding:9px 14px; border:1px solid var(--line); border-radius:10px; font-family:inherit; font-size:13px; background:#f8fafc; color:var(--ink); cursor:pointer; }
.ms-select:focus { outline:none; border-color:var(--brand); }
.ms-btn-reset { display:flex; align-items:center; gap:6px; padding:9px 16px; border:1px solid var(--line); border-radius:10px; background:#fff; font-family:inherit; font-size:13px; font-weight:500; color:var(--muted); cursor:pointer; transition:all 0.2s; }
.ms-btn-reset:hover { border-color:#94a3b8; color:var(--ink); }

.ms-table-card { background:#fff; border:1px solid var(--line); border-radius:20px; box-shadow:0 2px 12px rgba(0,0,0,0.04); margin-bottom: 20px; }
.ms-table-header { padding:18px 24px; border-bottom: 1px solid var(--line); display:flex; justify-content:space-between; align-items:center; background:#fdfdfd; border-radius: 20px 20px 0 0; }
.ms-table-title { font-size:15px; font-weight:700; color:var(--ink); margin:0; }
.ms-table-count { font-size:12px; color:var(--muted); margin-top:2px; display:block; }
.ms-table-wrapper { overflow-x: auto; width: 100%; border-radius: 0 0 20px 20px; }
.ms-table { width:100%; border-collapse:collapse; table-layout: auto; }
.ms-table thead tr { background:#f8fafc; }
.ms-table th { padding:10px 8px; text-align:left; font-size:9px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--line); white-space:nowrap; }
.ms-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background 0.15s; }
.ms-table tbody tr:last-child { border-bottom:none; }
.ms-table tbody tr:hover { background:#f8fafc; }
.ms-table td { padding:10px 8px; vertical-align:middle; font-size: 12px; }
.ms-table td.no-wrap { white-space: nowrap; }
.ms-td-id { font-size: 10px; font-weight:700; color:#94a3b8; font-family:monospace; }
.ms-td-comment { font-size:11px; color:var(--muted); font-style:italic; max-width:140px; }

.chip { display:inline-flex; align-items:center; padding:3px 8px; border-radius:20px; font-size:11px; font-weight:600; white-space:nowrap; }
.chip-interno   { background:#eff6ff; color:#1d4ed8; }
.chip-externo   { background:#fef3c7; color:#92400e; }
.chip-aprobado  { background:#f0fdf4; color:#15803d; }
.chip-pendiente { background:#fef9c3; color:#a16207; }
.chip-rechazado { background:#fff1f2; color:#be123c; }
.chip-gestionado{ background:#f5f3ff; color:#7c3aed; }
.chip-info      { background:#e0f2fe; color:#0369a1; }

/* Modal Styles */
.ms-modal { position: fixed; inset: 0; background: rgba(16,24,40,0.4); backdrop-filter: blur(4px); z-index: 2000; display: none; align-items: center; justify-content: center; padding: 20px; animation: fadeIn 0.2s ease-out; }
.ms-modal.active { display: flex; }
.ms-modal-content { background: #fff; border-radius: 24px; width: 100%; max-width: 650px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 50px rgba(0,0,0,0.15); animation: slideUp 0.3s ease-out; }
.ms-modal-header { padding: 24px 32px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: #fff; z-index: 10; }
.ms-modal-header h2 { font-size: 20px; font-weight: 800; margin: 0; color: var(--ink); }
.ms-modal-close { background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--muted); transition: all 0.2s; }
.ms-modal-close:hover { background: #e2e8f0; color: var(--ink); }
.ms-modal-body { padding: 32px; }

.ms-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.ms-detail-section h4 { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--brand); margin: 0 0 16px; border-bottom: 1px solid #eef2ff; padding-bottom: 8px; }
.ms-detail-item { font-size: 14px; color: var(--ink); margin-bottom: 8px; }
.ms-detail-item b { color: var(--muted); font-weight: 500; margin-right: 4px; }

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
@endpush

@push('scripts')
<script>
var filtroActivo = 'todos';

function filtrarTabla(estado) {
    filtroActivo = estado;
    document.getElementById('filtroEstado').value = estado;
    document.querySelectorAll('.ms-kpi').forEach(el => el.classList.remove('active'));
    var mapa = { todos:'card-total', pendiente:'card-pendiente', aprobado:'card-aprobado', rechazado:'card-rechazado', gestionado:'card-aprobado' };
    if (mapa[estado]) document.getElementById(mapa[estado])?.classList.add('active');
    aplicarFiltros();
}

function aplicarFiltros() {
    var busqueda = document.getElementById('busqueda').value.toLowerCase();
    var estado   = document.getElementById('filtroEstado').value;
    var tipo     = document.getElementById('filtroTipo').value;
    var filas    = document.querySelectorAll('#tbodySolicitudes tr');
    var visibles = 0;
    filas.forEach(function(fila) {
        var okTexto  = busqueda === '' || (fila.getAttribute('data-texto') || '').toLowerCase().includes(busqueda);
        var okEstado = estado  === 'todos' || fila.getAttribute('data-estado') === estado;
        var okTipo   = tipo    === 'todos' || fila.getAttribute('data-tipo')   === tipo;
        fila.style.display = (okTexto && okEstado && okTipo) ? '' : 'none';
        if (okTexto && okEstado && okTipo) visibles++;
    });
    document.getElementById('sinResultados').style.display = visibles === 0 ? 'block' : 'none';
    document.getElementById('contadorResultados').textContent = visibles + ' resultado' + (visibles !== 1 ? 's' : '');
}

function resetFiltros() {
    document.getElementById('busqueda').value = '';
    document.getElementById('filtroEstado').value = 'todos';
    document.getElementById('filtroTipo').value = 'todos';
    document.querySelectorAll('.ms-kpi').forEach(el => el.classList.remove('active'));
    aplicarFiltros();
}

document.addEventListener('DOMContentLoaded', aplicarFiltros);

function abrirModal(sol) {
    console.log("Abriendo detalle:", sol);
    document.getElementById('m-id').textContent = '#' + String(sol.id).padStart(3, '0');
    document.getElementById('m-nombre').textContent = sol.tipo === 'externo' ? sol.nombre_externo : (sol.solicitante ? sol.solicitante.name : 'N/A');
    
    const extInfo = document.getElementById('m-externo-info');
    if (sol.tipo === 'externo') {
        extInfo.style.display = 'block';
        document.getElementById('m-rut').textContent = sol.rut || '—';
        document.getElementById('m-email-ext').textContent = sol.correo_externo || '—';
        document.getElementById('m-cargo').textContent = sol.cargo_externo || '—';
    } else {
        extInfo.style.display = 'none';
    }

    document.getElementById('m-tipo').innerHTML = `<span class="chip chip-${sol.tipo}">${sol.tipo.toUpperCase()}</span>`;
    document.getElementById('m-destino').textContent = sol.destino;
    document.getElementById('m-ceco').textContent = sol.ceco;
    document.getElementById('m-fecha-v').textContent = formatDate(sol.fecha_viaje);
    document.getElementById('m-fecha-r').textContent = sol.fecha_retorno ? formatDate(sol.fecha_retorno) : '—';
    document.getElementById('m-motivo').textContent = sol.motivo;
    document.getElementById('m-alojamiento').textContent = sol.alojamiento ? 'SÍ' : 'NO';
    document.getElementById('m-traslado').textContent = sol.traslado ? 'SÍ' : 'NO';

    const gSec = document.getElementById('m-gastos-section');
    const gList = document.getElementById('m-gastos-list');
    if (sol.gastos && sol.gastos.length > 0) {
        gSec.style.display = 'block';
        gList.innerHTML = sol.gastos.map(g => `<li>${g.descripcion}</li>`).join('');
    } else {
        gSec.style.display = 'none';
    }

    const mEst = document.getElementById('m-estado');
    mEst.innerHTML = `<span class="chip chip-${sol.estado}">${sol.estado.toUpperCase()}</span>`;

    // Reset resolution sections
    document.getElementById('m-aprobado-info').style.display = 'none';
    document.getElementById('m-rechazado-info').style.display = 'none';

    if (sol.estado === 'aprobado' || sol.estado === 'gestionado') {
        document.getElementById('m-aprobado-info').style.display = 'block';
        document.getElementById('m-aprobador').textContent = sol.aprobador ? sol.aprobador.name : 'Sistena';
        document.getElementById('m-com-aprob').textContent = sol.comentario_aprobador || 'Sin comentarios';
    } else if (sol.estado === 'rechazado') {
        document.getElementById('m-rechazado-info').style.display = 'block';
        document.getElementById('m-rechazador').textContent = sol.rechazador ? sol.rechazador.name : 'Sistema';
        document.getElementById('m-com-rech').textContent = sol.comentario_rechazo || 'Sin comentarios';
    }

    document.getElementById('modalDetalles').classList.add('active');
}

function cerrarModal(e) {
    document.getElementById('modalDetalles').classList.remove('active');
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
}

function exportExcel() {
    const table = document.getElementById('tablaSolicitudes');
    const rows = Array.from(table.querySelectorAll('tr'));
    
    // Filter rows to only include visible ones
    const visibleRows = rows.filter(row => row.style.display !== 'none');
    
    let csvContent = "\uFEFF"; // UTF-8 BOM for Excel
    
    visibleRows.forEach(row => {
        const cols = Array.from(row.querySelectorAll('th, td'));
        const rowData = cols.map(col => {
            let text = col.innerText.replace(/"/g, '""').trim();
            return `"${text}"`;
        }).join(';'); 
        csvContent += rowData + "\r\n";
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", "solicitudes_viajes_" + new Date().toISOString().split('T')[0] + ".csv");
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    if (typeof Toast !== 'undefined') {
        Toast.show('¡Éxito!', 'Archivo Excel generado correctamente', 'success');
    }
}
</script>
@endpush

@endsection
