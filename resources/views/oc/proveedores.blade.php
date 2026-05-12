@extends('oc.layouts.dashboard')

@section('title', 'Gestión de Proveedores')
@section('subtitle', 'Administración del catálogo maestro de proveedores')

@section('header')
<div class="ms-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1 class="ms-banner-title">Directorio de Proveedores</h1>
            <p class="ms-banner-sub">Gestione la base de datos de proveedores, información bancaria y datos fiscales.</p>
        </div>
        <button onclick="openModal()" class="ms-btn-new" style="background: white; color: var(--brand-primary); border: none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nuevo Proveedor
        </button>
    </div>
</div>
@endsection

@section('content')

<!-- ── KPI SUMMARY ────────────────────────────────────── -->
<div class="ms-kpi-grid" style="margin-bottom: 2rem;">
    <div class="ms-kpi active">
        <div class="ms-kpi-icon" style="background: rgba(15, 107, 182, 0.1); color: var(--brand-primary);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Proveedores Totales</span>
            <span class="ms-kpi-value">{{ $rows->total() }}</span>
            <span class="ms-kpi-desc">Registrados en sistema</span>
        </div>
    </div>
</div>

<!-- ── TOOLBAR ────────────────────────────────────────── -->
<div class="ms-filters" style="margin-bottom: 2rem;">
    <form id="filterForm" method="GET" action="{{ route('oc.proveedores.index') }}" style="display: flex; gap: 1rem; flex: 1; align-items: center;">
        <div class="ms-search-wrap" style="flex: 1; max-width: 500px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="ms-search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" name="search" class="ms-search-input" placeholder="Buscar por RUT, Nombre o Razón Social..." value="{{ request('search') }}">
        </div>
        <select name="sort" class="ms-select" onchange="this.form.submit()">
            <option value="recent" {{ request('sort', 'recent') === 'recent' ? 'selected' : '' }}>Más reciente</option>
            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Más antiguo</option>
        </select>
        <button type="submit" class="ms-btn-reset" style="background: var(--brand-primary); color: white; border: none; padding: 0.75rem 1.5rem;">
            Filtrar
        </button>
        @if(request('search'))
            <a href="{{ route('oc.proveedores.index') }}" class="ms-btn-reset" title="Limpiar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </a>
        @endif
    </form>
    <a href="{{ route('oc.proveedores.download', request()->all()) }}" class="ms-btn-excel" style="padding: 0.75rem 1.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        Exportar Excel
    </a>
</div>

<!-- ── LISTADO ────────────────────────────────────────── -->
<div class="ms-table-card">
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 150px;">RUT</th>
                    <th>Proveedor</th>
                    <th>Razón Social & Dirección</th>
                    <th>Datos Bancarios</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                <tr>
                    <td style="font-weight: 800; color: var(--text-muted);">#{{ $row->id }}</td>
                    <td><span class="chip" style="background: #eff6ff; color: #1e40af; font-weight: 800;">{{ $row->rut }}</span></td>
                    <td>
                        <div style="font-weight: 800; color: var(--text-main);">{{ $row->nombre }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $row->correo ?: 'Sin email' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; font-size: 0.85rem;">{{ $row->razon_social }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ Str::limit($row->direccion, 40) }}</div>
                    </td>
                    <td>
                        @if($row->banco)
                            <div style="font-weight: 700; font-size: 0.8rem; color: var(--brand-primary);">{{ $row->banco }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace;">{{ $row->tipo_cuenta }}: {{ $row->numero_cuenta }}</div>
                        @else
                            <span style="font-size: 0.75rem; font-style: italic; color: var(--text-muted);">No registrado</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <button onclick="editModal({{ json_encode($row) }})" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem; color: var(--brand-primary); background: #eff6ff;" title="Editar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <button onclick="confirmDelete(this)" data-url="{{ route('oc.proveedores.destroy', $row->id) }}" data-name="{{ $row->nombre }}" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem; color: #dc2626; background: #fef2f2;" title="Eliminar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="padding: 4rem; text-align: center; color: var(--text-muted);">No se encontraron proveedores.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1.5rem; background: #fcfdfe; border-top: 1px solid var(--border-color);">
        {{ $rows->appends(request()->all())->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- ── MODAL FORM ──────────────────────────────────────── -->
<div id="proveedorModal" class="ms-modal" onclick="closeModalOnFondo(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 700px;">
        <div class="ms-modal-header">
            <h2 id="modal-title-text" class="ms-table-title">Registrar Proveedor</h2>
            <button class="ms-btn-reset" style="padding: 0.5rem;" onclick="closeModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="proveedorForm" method="POST" action="{{ route('oc.proveedores.store') }}" style="padding: 2.5rem;">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-field">
                    <label class="form-label">RUT *</label>
                    <input type="text" name="rut" id="rut" required class="ms-search-input" placeholder="12.345.678-9" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Nombre Comercial *</label>
                    <input type="text" name="nombre" id="nombre" required class="ms-search-input" placeholder="Ej: Servicios Integrales" style="padding-left: 1rem;">
                </div>
                <div class="form-field" style="grid-column: span 2;">
                    <label class="form-label">Razón Social *</label>
                    <input type="text" name="razon_social" id="razon_social" required class="ms-search-input" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="ms-search-input" placeholder="contacto@proveedor.cl" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Acreedor</label>
                    <input type="text" name="acreedor" id="acreedor" class="ms-search-input" style="padding-left: 1rem;">
                </div>
                <div class="form-field" style="grid-column: span 2;">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="ms-search-input" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Comuna</label>
                    <input type="text" name="comuna" id="comuna" class="ms-search-input" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Banco</label>
                    <input type="text" name="banco" id="banco" class="ms-search-input" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Tipo de Cuenta</label>
                    <select name="tipo_cuenta" id="tipo_cuenta" class="ms-select" style="width: 100%;">
                        <option value="">Seleccionar...</option>
                        <option value="Cuenta Corriente">Cuenta Corriente</option>
                        <option value="Cuenta Vista">Cuenta Vista</option>
                        <option value="Cuenta RUT">Cuenta RUT</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Número de Cuenta</label>
                    <input type="text" name="numero_cuenta" id="numero_cuenta" class="ms-search-input" style="padding-left: 1rem;">
                </div>
            </div>
            <div style="margin-top: 3rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="ms-btn-reset" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="ms-btn-new" style="min-width: 200px; justify-content: center;">Guardar Proveedor</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function openModal() {
    document.getElementById('modal-title-text').innerText = 'Registrar Nuevo Proveedor';
    document.getElementById('proveedorForm').reset();
    document.getElementById('proveedorForm').action = "{{ route('oc.proveedores.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('proveedorModal').classList.add('active');
}

function editModal(row) {
    document.getElementById('modal-title-text').innerText = 'Modificar Proveedor';
    document.getElementById('proveedorForm').action = `{{ url('oc/proveedores') }}/${row.id}`;
    document.getElementById('formMethod').value = 'PUT';
    
    document.getElementById('rut').value = row.rut || '';
    document.getElementById('nombre').value = row.nombre || '';
    document.getElementById('razon_social').value = row.razon_social || '';
    document.getElementById('acreedor').value = row.acreedor || '';
    document.getElementById('correo').value = row.correo || '';
    document.getElementById('direccion').value = row.direccion || '';
    document.getElementById('comuna').value = row.comuna || '';
    document.getElementById('banco').value = row.banco || '';
    document.getElementById('tipo_cuenta').value = row.tipo_cuenta || '';
    document.getElementById('numero_cuenta').value = row.numero_cuenta || '';
    
    document.getElementById('proveedorModal').classList.add('active');
}

function closeModal() {
    document.getElementById('proveedorModal').classList.remove('active');
}

function closeModalOnFondo(e) {
    if (e.target.classList.contains('ms-modal')) closeModal();
}

function confirmDelete(button) {
    const url = button.getAttribute('data-url');
    const name = button.getAttribute('data-name');
    Swal.fire({
        title: '¿Eliminar proveedor?',
        text: `Está a punto de eliminar a ${name}. Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush

@endsection