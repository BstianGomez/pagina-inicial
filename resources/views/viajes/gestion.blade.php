@extends('viajes.layouts.dashboard')

@section('title', 'Gestión de Solicitudes')
@section('subtitle', 'Flujo de estimación y cumplimiento de viajes')

@section('header')
<div class="banner">
    <h1>Gestión de Solicitudes</h1>
    <p>Ingresa estimaciones para nuevas solicitudes o completa los datos de las aprobadas.</p>
</div>
@endsection

@section('content')

@if(session('success'))
<div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:14px 20px;border-radius:12px;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background:#fff1f2;border:1px solid #fecdd3;color:#be123c;padding:14px 20px;border-radius:12px;margin-bottom:24px;">
    {{ session('error') }}
</div>
@endif

{{-- ── 1. FASE DE ESTIMACIÓN ────────────────────────── --}}
<div class="card" style="padding:0;overflow:hidden;margin-bottom:28px;">
    <div style="padding:18px 24px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;background:#fffbeb;">
        <h3 style="font-size:15px;font-weight:800;color:#92400e;margin:0;text-transform:uppercase;letter-spacing:0.5px;">1. Fase de Estimación</h3>
        <span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:800;">
            {{ $pendientesEstimacion->count() }} PENDIENTE{{ $pendientesEstimacion->count() !== 1 ? 'S' : '' }}
        </span>
    </div>

    @if($pendientesEstimacion->isEmpty())
    <div style="padding:40px;text-align:center;color:var(--muted);">
        <p style="font-size:13px;margin:0;">No hay nuevas solicitudes esperando estimación de costo.</p>
    </div>
    @else
    <div style="display:flex;flex-direction:column;">
        @foreach($pendientesEstimacion as $sol)
        <div style="border-bottom:1px solid var(--line);">
            <div style="padding:16px 20px;display:flex;align-items:center;gap:16px;cursor:pointer;background:#fff;" onclick="toggleForm('est-{{ $sol->id }}')">
                <div style="width:36px;height:36px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg fill="none" stroke="#d97706" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:700;color:var(--ink);font-size:14px;">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name }}</div>
                    <div style="font-size:12px;color:var(--muted);">{{ $sol->destino }} · {{ $sol->fecha_viaje->format('d M, Y') }}</div>
                </div>
                <button class="btn-primary" style="padding:6px 12px;font-size:11px;background:#d97706;border:none;">Estimar Costo</button>
            </div>
            <div id="form-est-{{ $sol->id }}" style="display:none;padding:24px;background:#fff9f0;border-top:1px solid #fef3c7;">
                {{-- Resumen de Requerimientos para Estimación --}}
                <div style="background:rgba(217, 119, 6, 0.05); border:1px solid rgba(217, 119, 6, 0.1); border-radius:12px; padding:20px; margin-bottom:24px;">
                    <h4 style="margin:0 0 16px; font-size:11px; font-weight:800; color:#b45309; text-transform:uppercase; letter-spacing:1px;">Requerimientos de la Solicitud</h4>
                    
                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px;">
                        <div>
                            <div style="font-size:11px; color:#b45309; font-weight:700; margin-bottom:4px;">MOTIVO DEL VIAJE</div>
                            <div style="font-size:13px; color:#92400e; font-weight:600; line-height:1.4;">{{ $sol->motivo }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px; color:#b45309; font-weight:700; margin-bottom:4px;">ITINERARIO</div>
                            <div style="font-size:13px; color:#92400e; font-weight:600;">
                                {{ $sol->fecha_viaje->format('d/m/Y') }} ➔ {{ $sol->fecha_retorno?->format('d/m/Y') ?? '—' }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:11px; color:#b45309; font-weight:700; margin-bottom:4px;">SERVICIOS SOLICITADOS</div>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                @if($sol->alojamiento)
                                    <span style="background:#fef3c7; color:#92400e; padding:3px 8px; border-radius:6px; font-size:10px; font-weight:800; border:1px solid #fcd34d;">🏨 HOTEL</span>
                                @endif
                                @if($sol->traslado)
                                    <span style="background:#e0f2fe; color:#0369a1; padding:3px 8px; border-radius:6px; font-size:10px; font-weight:800; border:1px solid #bae6fd;">🚗 TRASLADO</span>
                                @endif
                                @if(!$sol->alojamiento && !$sol->traslado)
                                    <span style="font-size:12px; color:#94a3b8; font-style:italic;">Solo pasaje</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($sol->gastos && count($sol->gastos) > 0)
                    <div style="margin-top:16px; padding-top:16px; border-top:1px dashed rgba(217, 119, 6, 0.2);">
                        <div style="font-size:11px; color:#b45309; font-weight:700; margin-bottom:8px;">GASTOS EXTRAS / OTROS</div>
                        <ul style="margin:0; padding-left:18px; font-size:12px; color:#92400e; font-weight:600;">
                            @foreach($sol->gastos as $gasto)
                                <li>{{ $gasto['descripcion'] ?? 'Gasto sin descripción' }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('viajes.gestion.estimar', $sol) }}">
                    @csrf
                    <div style="max-width:400px;">
                        <label class="form-label" style="color:#92400e;">Monto Estimado Total (CLP)</label>
                        <div style="position:relative;">
                            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#92400e;font-weight:700;">$</span>
                            <input type="number" name="monto_estimado" class="form-control" style="padding-left:30px;border-color:#fcd34d;" placeholder="Ej: 450000" required>
                        </div>
                        <p style="font-size:11px;color:#b45309;margin-top:8px;">Este monto será visible para el aprobador para su decisión final.</p>
                        <button type="submit" class="btn-primary" style="margin-top:12px;background:#d97706;width:100%;">Enviar para Aprobación</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ── 2. FASE DE FINALIZACIÓN ──────────────────────── --}}
<div class="card" style="padding:0;overflow:hidden;margin-bottom:28px;">
    <div style="padding:18px 24px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;background:#f0f9ff;">
        <h3 style="font-size:15px;font-weight:800;color:#0369a1;margin:0;text-transform:uppercase;letter-spacing:0.5px;">2. Fase de Finalización</h3>
        <span style="background:#e0f2fe;color:#0369a1;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:800;">
            {{ $pendientesFinalizacion->count() }} APROBADA{{ $pendientesFinalizacion->count() !== 1 ? 'S' : '' }}
        </span>
    </div>

    @if($pendientesFinalizacion->isEmpty())
    <div style="padding:40px;text-align:center;color:var(--muted);">
        <p style="font-size:13px;margin:0;">No hay solicitudes aprobadas pendientes de finalizar.</p>
    </div>
    @else
    <div style="display:flex;flex-direction:column;">
        @foreach($pendientesFinalizacion as $sol)
        <div style="border-bottom:1px solid var(--line);">
            <div style="padding:16px 20px;display:flex;align-items:center;gap:16px;cursor:pointer;background:#fff;" onclick="toggleForm('fin-{{ $sol->id }}')">
                <div style="width:36px;height:36px;border-radius:10px;background:#e0f2fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg fill="none" stroke="#0284c7" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:700;color:var(--ink);font-size:14px;">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name }}</div>
                    <div style="font-size:12px;color:var(--muted);">
                        {{ $sol->destino }} · {{ $sol->fecha_viaje->format('d M, Y') }} · 
                        <span style="font-weight:700;color:#0369a1;">Est: ${{ number_format($sol->monto_estimado, 0, ',', '.') }}</span>
                    </div>
                </div>
                <button class="btn-primary" style="padding:6px 12px;font-size:11px;background:#0284c7;border:none;">Finalizar Gestión</button>
            </div>

            <div id="form-fin-{{ $sol->id }}" style="display:none;padding:24px;background:#f0f9ff;border-top:1px solid #bae6fd;">
                <form method="POST" action="{{ route('viajes.gestion.store', $sol) }}" enctype="multipart/form-data" class="form-gestion">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                        <div>
                            <label class="form-label">Nº Reserva</label>
                            <input type="text" name="nro_reserva" class="form-control" placeholder="ABC123" required>
                        </div>
                        <div>
                            <label class="form-label">Línea Aérea</label>
                            <input type="text" name="linea_aerea" class="form-control" placeholder="LATAM, Sky..." required>
                        </div>
                        <div>
                            <label class="form-label">Nº Boleto</label>
                            <input type="text" name="nro_boleto" class="form-control" placeholder="000-123" required>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                        <div>
                            <label class="form-label">Monto Pasaje Final (CLP)</label>
                            <input type="number" name="monto_pasaje" class="form-control" required>
                        </div>
                        @if($sol->alojamiento)
                        <div>
                            <label class="form-label">Monto Hotel Final (CLP)</label>
                            <input type="number" name="monto_hotel" class="form-control" required>
                        </div>
                        @endif
                        @if($sol->traslado)
                        <div>
                            <label class="form-label">Monto Traslado Final (CLP)</label>
                            <input type="number" name="monto_traslado" class="form-control" required>
                        </div>
                        @endif
                    </div>

                    <div style="margin-bottom:16px;">
                        <label class="form-label">Adjuntar Tickets/Vouchers (Obligatorio)</label>
                        <input type="file" name="archivos[]" multiple class="form-control" required>
                    </div>

                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="btn-primary">Guardar y Finalizar</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ── HISTORIAL ────────────────────────────────────── --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:18px 24px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;">
        <h3 style="font-size:15px;font-weight:800;color:var(--ink);margin:0;text-transform:uppercase;letter-spacing:0.5px;">Historial Gestionadas</h3>
    </div>
    @if($historial->isEmpty())
    <div style="padding:40px;text-align:center;color:var(--muted);font-size:13px;">Sin historial aún.</div>
    @else
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:2px solid var(--line);">
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;">Solicitante</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;">Destino</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;">Reserva</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;">Costo Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historial as $sol)
            <tr style="border-bottom:1px solid var(--line);">
                <td style="padding:14px 20px;font-weight:600;color:var(--ink);font-size:13px;">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name }}</td>
                <td style="padding:14px 20px;color:var(--muted);font-size:13px;">{{ $sol->destino }}</td>
                <td style="padding:14px 20px;font-size:13px;">{{ $sol->gestion?->nro_reserva }}</td>
                <td style="padding:14px 20px;font-weight:700;color:#15803d;font-size:13px;">
                    ${{ number_format(($sol->gestion?->monto_pasaje ?? 0) + ($sol->gestion?->monto_hotel ?? 0) + ($sol->gestion?->monto_traslado ?? 0), 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<script>
function toggleForm(id) {
    var form = document.getElementById('form-' + id);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>

@endsection
