<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Models\Solicitud;
use App\Models\ArchivoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GestionController extends Controller
{
    // ── Panel Gestor ─────────────────────────────────────────
    public function index()
    {
        $this->autorizarGestor();

        $pendientesEstimacion = Solicitud::with('solicitante')
            ->pendientes()
            ->latest()
            ->get();

        $pendientesFinalizacion = Solicitud::with('solicitante')
            ->aprobadas()
            ->latest('aprobado_en')
            ->get();

        $historial = Solicitud::with(['solicitante', 'gestion', 'archivos'])
            ->gestionadas()
            ->latest('gestionado_en')
            ->get();

        return view('gestion', compact('pendientesEstimacion', 'pendientesFinalizacion', 'historial'));
    }

    // ── Guardar Estimación ───────────────────────────────────
    public function storeEstimacion(Request $request, Solicitud $solicitud)
    {
        $this->autorizarGestor();

        if (!$solicitud->esPendiente()) {
            return back()->with('error', 'Esta solicitud ya no está en etapa de estimación.');
        }

        $request->validate([
            'monto_estimado' => 'required|numeric|min:0',
        ]);

        $solicitud->update([
            'monto_estimado' => $request->monto_estimado,
            'estado'         => 'en_aprobacion',
        ]);

        return back()->with('success', 'Monto estimado guardado. La solicitud ha sido enviada al aprobador.');
    }

    // ── Completar Gestión ────────────────────────────────────
    public function store(Request $request, Solicitud $solicitud)
    {
        $this->autorizarGestor();

        if (!$solicitud->esAprobada()) {
            return back()->with('error', 'Solo se pueden gestionar solicitudes aprobadas.');
        }

        // Validar que se suban archivos obligatoriamente
        $request->validate([
            'archivos' => 'required|array',
            'archivos.*' => 'file',
        ], [
            'archivos.required' => 'Debe adjuntar al menos un archivo para completar la gestión.',
            'archivos.*.file' => 'Cada archivo debe ser válido.',
        ]);

        // Guardar datos de gestión
        $gestion = Gestion::create([
            'solicitud_id'   => $solicitud->id,
            'gestionado_por' => Auth::id(),
            'nro_reserva'    => $request->nro_reserva,
            'linea_aerea'    => $request->linea_aerea,
            'nro_boleto'     => $request->nro_boleto,
            'hotel'          => $request->hotel,
            'nro_confirmacion'=> $request->nro_confirmacion,
            'checkin'        => $request->checkin ?: null,
            'checkout'       => $request->checkout ?: null,
            'monto_pasaje'   => $request->monto_pasaje ?: null,
            'monto_hotel'    => $request->monto_hotel ?: null,
            'monto_traslado' => $request->monto_traslado ?: null,
            'notas'          => $request->notas,
        ]);

        // Guardar archivos
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $file) {
                $ruta = $file->store("solicitudes/{$solicitud->id}", 'private');
                ArchivoSolicitud::create([
                    'solicitud_id'   => $solicitud->id,
                    'gestion_id'     => $gestion->id,
                    'nombre_original'=> $file->getClientOriginalName(),
                    'ruta'           => $ruta,
                    'mime_type'      => $file->getMimeType(),
                    'tamano'         => $file->getSize(),
                ]);
            }
        }

        // Actualizar estado de la solicitud
        $solicitud->update([
            'estado'        => 'gestionado',
            'gestionado_por'=> Auth::id(),
            'gestionado_en' => now(),
        ]);

        return back()->with('success', 'Gestión completada. El solicitante ya puede ver los datos.');
    }

    // ── Descargar Archivo ────────────────────────────────────
    public function descargar(ArchivoSolicitud $archivo)
    {
        // Solo el solicitante, el gestor o admin pueden descargar
        $user = Auth::user();
        $sol  = $archivo->solicitud;
        $rol  = $user->rol ?? '';

        $puedeDescargar = $sol->user_id === $user->id
            || in_array($rol, ['gestor', 'admin', 'super_admin']);

        if (!$puedeDescargar) {
            abort(403);
        }

        return Storage::disk('private')->download($archivo->ruta, $archivo->nombre_original);
    }

    private function autorizarGestor(): void
    {
        $rol = Auth::user()->rol ?? '';
        if (!in_array($rol, ['gestor', 'admin', 'super_admin'])) {
            abort(403, 'Sin permiso para gestionar solicitudes.');
        }
    }
}
