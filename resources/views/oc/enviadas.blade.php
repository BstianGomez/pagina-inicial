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
                            <a href="{{ route('oc.gestor') }}" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
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

@endsection
