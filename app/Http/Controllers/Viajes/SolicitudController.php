<?php

namespace App\Http\Controllers\Viajes;

use App\Models\Viajes\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    // ── Mis Solicitudes ──────────────────────────────────────
    public function index()
    {
        $user = Auth::user();
        $rol  = $user->rol ?? 'usuario';
        
        $query = Solicitud::with(['solicitante', 'gestion', 'archivos', 'aprobador']);

        // Si es un rol privilegiado, ve todo. Si no, solo lo suyo.
        if (!$user->isAprobador() && !$user->isGestor()) {
            $query->where('user_id', $user->id);
        }

        $solicitudes = $query->latest()->get();

        $total         = $solicitudes->count();
        $pendientes    = $solicitudes->whereIn('estado', ['pendiente', 'en_aprobacion'])->count();
        $en_aprobacion = $solicitudes->where('estado', 'en_aprobacion')->count();
        $aprobadas     = $solicitudes->whereIn('estado', ['aprobado', 'gestionado'])->count();
        $rechazadas    = $solicitudes->where('estado', 'rechazado')->count();
        $gestionadas   = $solicitudes->where('estado', 'gestionado')->count();

        return view('viajes.mis-solicitudes', compact(
            'solicitudes', 'total', 'pendientes', 'en_aprobacion', 'aprobadas', 'rechazadas', 'gestionadas'
        ));
    }

    // ── Crear Solicitud ──────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'ceco'        => 'required|string',
            'destino'     => 'required|string|max:255',
            'fecha_viaje' => 'required|date',
            'motivo'      => 'required|string',
        ]);

        $gastos = [];
        if ($request->has('gastos')) {
            foreach ($request->gastos as $g) {
                if (!empty($g['descripcion'])) {
                    $gastos[] = ['descripcion' => $g['descripcion']];
                }
            }
        }

        $pv = [];
        if ($request->has('pv')) {
            foreach ($request->pv as $val) {
                if ($val !== null && $val !== '') {
                    $pv[] = $val;
                }
            }
        }

        Solicitud::create([
            'user_id'          => Auth::id(),
            'tipo'             => $request->tipoSolicitud ?? 'interno',
            'nombre_externo'   => $request->nombre_externo,
            'correo_externo'   => $request->correo_externo,
            'rut'              => $request->rut,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'cargo_externo'    => $request->cargo,
            'ceco'             => $request->ceco,
            'destino'          => $request->destino,
            'fecha_viaje'      => $request->fecha_viaje,
            'fecha_retorno'    => $request->fecha_retorno,
            'motivo'           => $request->motivo,
            'alojamiento'      => $request->alojamiento === 'si',
            'traslado'         => $request->traslado === 'si',
            'gastos'           => $gastos,
            'pv'               => $pv,
            'estado'           => 'pendiente',
        ]);

        return redirect()->route('viajes.mis-solicitudes')
            ->with('success', 'Solicitud enviada correctamente. Está pendiente de aprobación.');
    }

    // ── Panel Aprobador ──────────────────────────────────────
    public function panelAprobador()
    {
        $this->autorizarAprobador();
        $pendientes = Solicitud::with('solicitante')
            ->enAprobacion()
            ->latest()
            ->get();

        $historial = Solicitud::with(['solicitante', 'aprobador'])
            ->whereIn('estado', ['aprobado', 'rechazado', 'gestionado'])
            ->latest()
            ->get();

        return view('viajes.aprobador', compact('pendientes', 'historial'));
    }

    // ── Aprobar ──────────────────────────────────────────────
    public function aprobar(Request $request, Solicitud $solicitud)
    {
        $this->autorizarAprobador();

        $solicitud->update([
            'estado'               => 'aprobado',
            'aprobado_por'         => Auth::id(),
            'aprobado_en'          => now(),
            'comentario_aprobador' => $request->comentario,
        ]);

        return back()->with('success', 'Solicitud aprobada correctamente. El gestor ya puede completar los datos.');
    }

    // ── Rechazar ─────────────────────────────────────────────
    public function rechazar(Request $request, Solicitud $solicitud)
    {
        $this->autorizarAprobador();

        $request->validate([
            'comentario' => 'required|string|min:5',
        ], [
            'comentario.required' => 'El comentario es obligatorio al rechazar una solicitud.',
        ]);

        $solicitud->update([
            'estado'             => 'rechazado',
            'rechazado_por'      => Auth::id(),
            'rechazado_en'       => now(),
            'comentario_rechazo' => $request->comentario,
        ]);

        return back()->with('success', 'Solicitud rechazada.');
    }

    private function autorizarAprobador(): void
    {
        if (!Auth::user()->isAprobador()) {
            abort(403, 'Sin permiso para acceder a este panel de aprobación.');
        }
    }
}
