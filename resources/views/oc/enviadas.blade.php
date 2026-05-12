@extends('oc.layouts.dashboard')

@section('title', 'Solicitudes Enviadas')

@section('header')
<div class="ms-banner">
    <div>
        <h1 class="ms-banner-title">Archivo de Envío</h1>
        <p class="ms-banner-sub">Historial de órdenes de compra procesadas y enviadas</p>
    </div>
    <a href="{{ route('oc.enviadas.export') }}" class="ms-btn-reset ms-btn-excel">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        Exportar Historial
    </a>
</div>
@endsection

@section('content')

<!-- ── LISTADO ────────────────────────────────────────── -->
<div class="ms-table-card">
    <div class="ms-table-header">
        <h3 class="ms-table-title">Solicitudes con Estado: Enviadas</h3>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>CECO</th>
                    <th>Documento</th>
                    <th>Proveedor</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Fecha Envío</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td style="font-weight: 800; color: var(--brand-primary);">{{ $row->ceco ?: $row->sol_ceco }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $row->tipo_documento }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $row->tipo_solicitud }}</div>
                        </td>
                        <td><div style="font-weight: 700;">{{ Str::limit($row->proveedor ?: $row->sol_proveedor, 30) }}</div></td>
                        <td>
                            <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $row->descripcion ?: $row->sol_descripcion }}
                            </div>
                        </td>
                        <td style="font-weight: 800; font-family: 'Outfit';">
                            ${{ number_format($row->monto ?: $row->sol_monto, 0, ',', '.') }}
                        </td>
                        <td>{{ \Illuminate\Support\Carbon::parse($row->updated_at)->format('d/m/Y H:i') }}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <!-- Ver Detalles -->
                                <button onclick="verDetalles({{ $row->id }})" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem; background: #f1f5f9; color: #64748b;" title="Ver Detalles">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                                <!-- Descargar PDF -->
                                <a href="{{ route('oc.enviadas.pdf', $row->id) }}" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem; background: #ecfdf5; color: #10b981;" title="Descargar OC">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="padding: 4rem; text-align: center; color: var(--text-muted);">No hay solicitudes enviadas recientemente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1.5rem; background: #fcfdfe; border-top: 1px solid var(--border-color);">
        {{ $rows->appends(request()->all())->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- ── MODAL DETALLES ──────────────────────────────────── -->
<div id="modalDetalles" class="ms-modal">
    <div class="ms-modal-content" style="max-width: 800px;">
        <div class="ms-modal-header">
            <h3 class="ms-modal-title">Detalles de la Orden de Compra</h3>
            <button class="ms-modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="ms-modal-body" id="modalBody">
            <div style="text-align: center; padding: 2rem;">Cargando...</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verDetalles(id) {
    const modal = document.getElementById('modalDetalles');
    const body = document.getElementById('modalBody');
    
    modal.classList.add('active');
    body.innerHTML = '<div style="text-align: center; padding: 2rem;"><div class="spinner"></div><p>Cargando información...</p></div>';

    fetch(`/oc/enviadas/${id}`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const oc = res.data;
                const fechaEnvio = new Date(oc.created_at).toLocaleString('es-CL');
                const fechaSol = new Date(oc.solicitud_fecha).toLocaleString('es-CL');
                
                let html = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4 style="margin-bottom: 1rem; color: var(--brand-primary); border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem;">Datos del Envío</h4>
                            <div class="ms-detail-item"><strong>N° Orden de Compra:</strong> <span>${oc.numero_oc}</span></div>
                            <div class="ms-detail-item"><strong>Fecha de Envío:</strong> <span>${fechaEnvio}</span></div>
                            <div class="ms-detail-item"><strong>Proveedor:</strong> <span>${oc.proveedor}</span></div>
                            <div class="ms-detail-item"><strong>Email Destino:</strong> <span>${oc.email_proveedor || 'No registrado'}</span></div>
                            <div class="ms-detail-item"><strong>Comentario Gestor:</strong> <p style="margin-top: 0.5rem; font-style: italic;">${oc.comentario || 'Sin comentarios'}</p></div>
                        </div>
                        <div>
                            <h4 style="margin-bottom: 1rem; color: var(--brand-primary); border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem;">Datos de la Solicitud</h4>
                            <div class="ms-detail-item"><strong>ID Solicitud:</strong> <span>#${oc.oc_solicitud_id}</span></div>
                            <div class="ms-detail-item"><strong>Fecha Solicitud:</strong> <span>${fechaSol}</span></div>
                            <div class="ms-detail-item"><strong>Tipo:</strong> <span>${oc.tipo_solicitud} (${oc.tipo_documento})</span></div>
                            <div class="ms-detail-item"><strong>CECO:</strong> <span>${oc.ceco}</span></div>
                            <div class="ms-detail-item"><strong>Monto:</strong> <span style="font-weight: 800; color: #10b981;">$${new Intl.NumberFormat('es-CL').format(oc.monto)}</span></div>
                        </div>
                    </div>
                    <div style="margin-top: 2rem;">
                        <h4 style="margin-bottom: 1rem; color: var(--brand-primary); border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem;">Descripción del Requerimiento</h4>
                        <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; line-height: 1.5;">
                            ${oc.descripcion}
                        </div>
                    </div>
                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                        <a href="/oc/enviadas/${oc.id}/pdf" class="ms-btn-new" style="background: #10b981;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Descargar PDF de Orden de Compra
                        </a>
                    </div>
                `;
                body.innerHTML = html;
            } else {
                body.innerHTML = '<div class="alert alert-error">No se pudo cargar la información.</div>';
            }
        })
        .catch(err => {
            body.innerHTML = '<div class="alert alert-error">Error de red al cargar detalles.</div>';
        });
}

function cerrarModal() {
    document.getElementById('modalDetalles').classList.remove('active');
}

// Cerrar al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modalDetalles');
    if (event.target == modal) {
        cerrarModal();
    }
}
</script>
<style>
.ms-detail-item { display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9rem; }
.ms-detail-item strong { color: var(--text-muted); }
.ms-detail-item span { font-weight: 700; color: var(--text-main); }
.spinner { width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid var(--brand-primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
@endpush

@endsection
