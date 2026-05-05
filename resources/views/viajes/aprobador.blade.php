@extends('viajes.layouts.dashboard')

@section('title', 'Panel de Aprobador')
@section('subtitle', 'Gestiona las aprobaciones pendientes')

@section('header')
<div class="banner">
    <h1>Panel de Aprobador</h1>
    <p>Revisa y decide sobre las solicitudes de viaje pendientes.</p>
</div>
@endsection

@section('content')

@if(session('success'))
<div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:14px 20px;border-radius:12px;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- ── PENDIENTES ───────────────────────────────────── --}}
<div class="card" style="padding:0;overflow:hidden;margin-bottom:28px;">
    <div style="padding:18px 24px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;">
        <h3 style="font-size:16px;font-weight:700;color:var(--ink);margin:0;">Solicitudes Pendientes</h3>
        <span style="background:#fef9c3;color:#a16207;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">
            {{ $pendientes->count() }} pendiente{{ $pendientes->count() !== 1 ? 's' : '' }}
        </span>
    </div>

    @if($pendientes->isEmpty())
    <div style="padding:48px;text-align:center;color:var(--muted);">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:40px;height:40px;margin:0 auto 12px;opacity:0.3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p style="font-size:14px;font-weight:600;margin:0;">No hay solicitudes pendientes</p>
    </div>
    @else
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:2px solid var(--line);">
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Solicitante</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Destino</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Fecha Viaje</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Motivo</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Tipo</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">CECO</th>
                <th style="padding:12px 20px;text-align:center;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendientes as $sol)
            <tr style="border-bottom:1px solid var(--line);" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                <td style="padding:14px 20px;">
                    <div style="font-weight:600;color:var(--ink);">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name }}</div>
                    <div style="font-size:12px;color:var(--muted);">{{ $sol->tipo === 'externo' ? $sol->correo_externo : $sol->solicitante->email }}</div>
                </td>
                <td style="padding:14px 20px;">
                    <div style="font-weight:600;color:var(--ink);">{{ $sol->destino }}</div>
                    @if($sol->monto_estimado)
                    <div style="font-size:12px;font-weight:700;color:#15803d;margin-top:2px;">
                        Est: ${{ number_format($sol->monto_estimado, 0, ',', '.') }}
                    </div>
                    @endif
                    @if($sol->pv && count($sol->pv) > 0)
                    <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:4px;">
                        @foreach($sol->pv as $n)
                        <span style="background:#f1f5f9;color:#475569;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:700;border:1px solid #e2e8f0;">PV #{{ $n }}</span>
                        @endforeach
                    </div>
                    @endif
                </td>
                <td style="padding:14px 20px;color:var(--muted);font-size:13px;">{{ $sol->fecha_viaje->format('d M, Y') }}</td>
                <td style="padding:14px 20px;color:var(--muted);font-size:13px;max-width:160px;">{{ Str::limit($sol->motivo, 50) }}</td>
                <td style="padding:14px 20px;">
                    @if($sol->tipo === 'interno')
                        <span style="background:#eff6ff;color:#1d4ed8;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;">Interno</span>
                    @else
                        <span style="background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;">Externo</span>
                    @endif
                </td>
                <td style="padding:14px 20px;font-size:12px;color:var(--muted);">{{ $sol->ceco }}</td>
                <td style="padding:14px 20px;text-align:center;">
                    <div style="display:flex;gap:8px;justify-content:center;">
                        <button onclick="verDetalles(this)" data-json="{{ json_encode($sol) }}"
                            style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all 0.2s;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detalles
                        </button>
                        <button onclick="abrirModal('aprobar', {{ $sol->id }}, '{{ addslashes($sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name) }}')"
                            style="background:#f0fdf4;color:#15803d;border:1px solid #86efac;border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all 0.2s;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Aprobar
                        </button>
                        <button onclick="abrirModal('rechazar', {{ $sol->id }}, '{{ addslashes($sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name) }}')"
                            style="background:#fff1f2;color:#be123c;border:1px solid #fecdd3;border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all 0.2s;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Rechazar
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- ── HISTORIAL ────────────────────────────────────── --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:18px 24px;border-bottom:1px solid var(--line);">
        <h3 style="font-size:16px;font-weight:700;color:var(--ink);margin:0;">Historial de Decisiones</h3>
    </div>
    @if($historial->isEmpty())
    <div style="padding:40px;text-align:center;color:var(--muted);font-size:13px;">Sin historial aún.</div>
    @else
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:2px solid var(--line);">
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Solicitante</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Destino</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Aprobador</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Decisión</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Fecha y Hora</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Comentario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historial as $sol)
            <tr style="border-bottom:1px solid var(--line);" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                <td style="padding:14px 20px;font-weight:600;color:var(--ink);">
                    {{ $sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name }}
                </td>
                <td style="padding:14px 20px;color:var(--muted);font-size:13px;">{{ $sol->destino }}</td>
                <td style="padding:14px 20px;color:var(--muted);font-size:13px;">
                    {{ $sol->aprobador?->name ?? '—' }}
                </td>
                <td style="padding:14px 20px;">
                    @if($sol->estado === 'aprobado' || $sol->estado === 'gestionado')
                        <span style="background:#f0fdf4;color:#15803d;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">✓ Aprobado</span>
                    @elseif($sol->estado === 'rechazado')
                        <span style="background:#fff1f2;color:#be123c;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">✕ Rechazado</span>
                    @endif
                </td>
                <td style="padding:14px 20px;color:var(--muted);font-size:13px;">
                    {{ ($sol->aprobado_en ?? $sol->rechazado_en)?->format('d-m-Y H:i') ?? '—' }}
                </td>
                <td style="padding:14px 20px;color:var(--muted);font-size:13px;font-style:italic;">
                    {{ $sol->comentario_aprobador ?? $sol->comentario_rechazo ?? '—' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- ── MODAL ────────────────────────────────────────── --}}
<div id="modalDecision" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(4px);z-index:2000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:20px;padding:36px;width:100%;max-width:480px;box-shadow:0 25px 60px rgba(0,0,0,0.2);position:relative;">
        <button onclick="cerrarModal()" style="position:absolute;top:16px;right:16px;background:none;border:none;cursor:pointer;color:var(--muted);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div id="modalIcono" style="width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;"></div>
        <h3 id="modalTitulo" style="font-size:20px;font-weight:700;margin:0 0 6px;"></h3>
        <p id="modalSubtitulo" style="font-size:14px;color:var(--muted);margin:0 0 24px;"></p>

        <div style="background:#f8fafc;border:1px solid var(--line);border-radius:12px;padding:14px 16px;margin-bottom:20px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Aprobador</div>
                <div style="font-size:13px;font-weight:600;color:var(--ink);">{{ Auth::user()->name }}</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Fecha y hora</div>
                <div style="font-size:13px;font-weight:600;color:var(--ink);" id="fechaHoraActual"></div>
            </div>
        </div>

        <form id="formDecision" method="POST">
            @csrf
            <div class="form-field">
                <label class="form-label">Comentario <span id="spanOpcional" style="color:var(--muted);font-weight:400;">(opcional)</span></label>
                <textarea id="textComentario" name="comentario" class="form-control" rows="3" placeholder="Escribe un comentario..."></textarea>
                <div id="errorComentario" style="display:none;color:#be123c;font-size:12px;font-weight:500;margin-top:6px;">
                    El comentario es obligatorio al rechazar una solicitud.
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
                <button type="button" onclick="cerrarModal()" style="background:#f1f5f9;color:var(--ink);border:none;padding:10px 20px;border-radius:10px;font-weight:600;cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit" id="btnConfirmar" style="border:none;padding:10px 24px;border-radius:10px;font-weight:700;cursor:pointer;font-size:14px;color:white;transition:all 0.2s;">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detallado para Aprobador -->
<div id="modalDetalles" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(8px);z-index:3000;align-items:center;justify-content:center;padding:20px;">
    <div style="background:white;border-radius:24px;width:100%;max-width:640px;max-height:90vh;overflow-y:auto;box-shadow:0 25px 50px -12px rgba(0,0,0,0.3);position:relative;animation: modalFadeIn 0.3s ease-out;">
        <div style="position:sticky;top:0;background:rgba(255,255,255,0.95);backdrop-filter:blur(10px);padding:20px 32px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;z-index:10;">
            <div>
                <h3 style="margin:0;font-size:18px;font-weight:800;color:#0f172a;">Detalles Completos</h3>
                <p style="margin:2px 0 0;font-size:12px;color:#64748b;">Revisión profunda de la solicitud de viaje</p>
            </div>
            <button onclick="cerrarModalDetalles()" style="background:#f1f5f9;border:none;width:36px;height:36px;border-radius:12px;cursor:pointer;color:#64748b;display:flex;align-items:center;justify-content:center;transition:all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div id="contenidoDetalles" style="padding:32px;">
            <!-- Se llena dinámicamente con JS -->
        </div>

        <div style="padding:20px 32px;background:#f8fafc;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;border-radius:0 0 24px 24px;">
            <button onclick="cerrarModalDetalles()" style="background:white;border:1px solid #e2e8f0;padding:10px 24px;border-radius:12px;font-weight:700;color:#475569;cursor:pointer;font-size:14px;box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                Cerrar vista
            </button>
        </div>
    </div>
</div>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

<script>
var accionActual = '';
var BASE = '{{ url('viajes//solicitudes') }}';

function abrirModal(accion, id, nombre) {
    accionActual = accion;
    document.getElementById('textComentario').value = '';
    document.getElementById('errorComentario').style.display = 'none';

    var ahora = new Date();
    document.getElementById('fechaHoraActual').textContent =
        ahora.toLocaleDateString('es-CL') + ' ' + ahora.toLocaleTimeString('es-CL', {hour:'2-digit',minute:'2-digit'});

    var form = document.getElementById('formDecision');

    if (accion === 'aprobar') {
        form.action = BASE + '/' + id + '/aprobar';
        document.getElementById('modalIcono').style.background = '#f0fdf4';
        document.getElementById('modalIcono').innerHTML = '<svg fill="none" stroke="#15803d" viewBox="0 0 24 24" style="width:26px;height:26px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
        document.getElementById('modalTitulo').textContent = 'Aprobar Solicitud';
        document.getElementById('modalTitulo').style.color = '#15803d';
        document.getElementById('modalSubtitulo').textContent = 'Aprobando solicitud de ' + nombre + '.';
        document.getElementById('spanOpcional').style.display = 'inline';
        document.getElementById('btnConfirmar').style.background = 'linear-gradient(135deg,#16a34a,#15803d)';
        document.getElementById('btnConfirmar').style.boxShadow = '0 4px 15px rgba(22,163,74,0.35)';
    } else {
        form.action = BASE + '/' + id + '/rechazar';
        document.getElementById('modalIcono').style.background = '#fff1f2';
        document.getElementById('modalIcono').innerHTML = '<svg fill="none" stroke="#be123c" viewBox="0 0 24 24" style="width:26px;height:26px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>';
        document.getElementById('modalTitulo').textContent = 'Rechazar Solicitud';
        document.getElementById('modalTitulo').style.color = '#be123c';
        document.getElementById('modalSubtitulo').textContent = 'Rechazando solicitud de ' + nombre + '. Comentario obligatorio.';
        document.getElementById('spanOpcional').style.display = 'none';
        document.getElementById('btnConfirmar').style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
        document.getElementById('btnConfirmar').style.boxShadow = '0 4px 15px rgba(239,68,68,0.35)';
    }

    document.getElementById('modalDecision').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modalDecision').style.display = 'none';
}

document.getElementById('formDecision').addEventListener('submit', function(e) {
    if (accionActual === 'rechazar' && document.getElementById('textComentario').value.trim() === '') {
        e.preventDefault();
        document.getElementById('errorComentario').style.display = 'block';
        document.getElementById('textComentario').style.borderColor = '#ef4444';
    }
});

document.getElementById('modalDecision').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

function verDetalles(btn) {
    const sol = JSON.parse(btn.getAttribute('data-json'));
    const container = document.getElementById('contenidoDetalles');
    
    // Formatear Gastos
    let gastosHtml = '';
    if (sol.gastos && sol.gastos.length > 0) {
        gastosHtml = sol.gastos.map(g => `
            <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:8px;font-size:13px;color:#334155;">
                <div style="width:6px;height:6px;border-radius:50%;background:#3b82f6;"></div>
                ${g.descripcion}
            </div>
        `).join('');
    } else {
        gastosHtml = '<div style="padding:16px;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;text-align:center;color:#94a3b8;font-size:13px;font-style:italic;">No se registraron gastos adicionales</div>';
    }

    // Formatear PVs
    let pvHtml = '';
    if (sol.pv && sol.pv.length > 0) {
        pvHtml = `
            <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:6px;">
                ${sol.pv.map(n => `<span style="background:#eff6ff;color:#1d4ed8;padding:4px 10px;border-radius:8px;font-size:12px;font-weight:700;border:1px solid #bfdbfe;">PV #${n}</span>`).join('')}
            </div>
        `;
    }

    container.innerHTML = `
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-bottom:32px;">
            <div>
                <h4 style="margin:0 0 16px;font-size:11px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Datos del Itinerario</h4>
                
                <div style="margin-bottom:16px;">
                    <div style="font-size:12px;color:#64748b;margin-bottom:2px;">Destino Principal</div>
                    <div style="font-size:16px;font-weight:800;color:#0f172a;">${sol.destino}</div>
                </div>

                <div style="margin-bottom:20px; padding:12px; background:#f0fdf4; border-radius:12px; border:1px solid #dcfce7;">
                    <div style="font-size:11px; font-weight:800; color:#166534; text-transform:uppercase; margin-bottom:4px;">Monto Estimado (Proyectado)</div>
                    <div style="font-size:22px; font-weight:900; color:#15803d;">$${new Intl.NumberFormat('es-CL').format(sol.monto_estimado || 0)}</div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:4px;">Salida</div>
                        <div style="font-size:14px;font-weight:700;color:#334155;">${new Date(sol.fecha_viaje).toLocaleDateString('es-CL')}</div>
                    </div>
                    <div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:4px;">Retorno</div>
                        <div style="font-size:14px;font-weight:700;color:#334155;">${sol.fecha_retorno ? new Date(sol.fecha_retorno).toLocaleDateString('es-CL') : '—'}</div>
                    </div>
                </div>
            </div>

            <div>
                <h4 style="margin:0 0 16px;font-size:11px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Requerimientos</h4>
                
                <div style="display:flex;gap:12px;margin-bottom:16px;">
                    <div style="flex:1;padding:12px;border-radius:16px;background:${sol.alojamiento ? '#f5f3ff' : '#f8fafc'};border:1.5px solid ${sol.alojamiento ? '#ddd6fe' : '#e2e8f0'};display:flex;flex-direction:column;align-items:center;gap:6px;transition:all 0.2s;">
                        <svg fill="none" stroke="${sol.alojamiento ? '#7c3aed' : '#94a3b8'}" viewBox="0 0 24 24" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span style="font-size:11px;font-weight:800;color:${sol.alojamiento ? '#6d28d9' : '#64748b'};">Hotel</span>
                    </div>
                    <div style="flex:1;padding:12px;border-radius:16px;background:${sol.traslado ? '#ecfeff' : '#f8fafc'};border:1.5px solid ${sol.traslado ? '#cffafe' : '#e2e8f0'};display:flex;flex-direction:column;align-items:center;gap:6px;transition:all 0.2s;">
                        <svg fill="none" stroke="${sol.traslado ? '#0891b2' : '#94a3b8'}" viewBox="0 0 24 24" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        <span style="font-size:11px;font-weight:800;color:${sol.traslado ? '#0e7490' : '#64748b'};">Traslado</span>
                    </div>
                </div>

                ${pvHtml ? `
                <div>
                    <div style="font-size:12px;color:#64748b;margin-bottom:6px;">Documentación PV</div>
                    ${pvHtml}
                </div>
                ` : ''}
            </div>
        </div>

        <div style="margin-bottom:32px;">
            <h4 style="margin:0 0 12px;font-size:11px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Justificación del Viaje</h4>
            <div style="padding:20px;background:#f8fafc;border-radius:16px;border:1px solid #e2e8f0;font-size:14px;line-height:1.7;color:#334155;white-space:pre-wrap;">${sol.motivo}</div>
        </div>

        <div>
            <h4 style="margin:0 0 12px;font-size:11px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;">Gastos Extras Proyectados</h4>
            ${gastosHtml}
        </div>
    `;

    document.getElementById('modalDetalles').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarModalDetalles() {
    document.getElementById('modalDetalles').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.getElementById('modalDetalles').addEventListener('click', function(e) {
    if (e.target === this) cerrarModalDetalles();
});
</script>

@endsection
