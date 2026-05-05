<?php

namespace App\Http\Controllers\OC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\OC\AuditService;
use Illuminate\Validation\ValidationException;

class OcSolicitudController extends Controller
{
    /**
     * Show the form for editing the specified OC solicitud.
     */
    public function edit($id)
    {
        $solicitud = DB::table('oc_solicitudes')->where('id', $id)->first();
        
        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Solo permitir editar si está en Edicion o Ajuste
        if (!in_array($solicitud->estado, ['Edicion', 'Ajuste'])) {
            return redirect()->route('oc.dashboard')->with('error', 'Esta solicitud no se puede editar en su estado actual.');
        }

        // Datos para los selects
        $cecos = DB::table('cecos')->orderBy('codigo')->get();
        $coordinadores = \App\Models\OC\CoordinadorProyecto::orderBy('nombre')->get();
        $tipoServicios = \App\Models\OC\TipoServicio::orderBy('nombre')->get();
        $tipoProyectos = \App\Models\OC\TipoProyecto::orderBy('nombre')->get();

        return view('oc.edit-solicitud', compact('solicitud', 'cecos', 'coordinadores', 'tipoServicios', 'tipoProyectos'));
    }

    /**
     * Update the specified OC solicitud in storage.
     */
    public function update(Request $request, $id)
    {
        $solicitud = DB::table('oc_solicitudes')->where('id', $id)->first();
        
        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        if (!in_array($solicitud->estado, ['Edicion', 'Ajuste'])) {
             return redirect()->route('oc.dashboard')->with('error', 'Esta solicitud no se puede editar en su estado actual.');
        }

        $validated = $request->validate([
            'ceco' => 'required|string',
            'coordinador' => 'required|string',
            'tipo_servicio' => 'required|string',
            'tipo_proyecto' => 'required|string',
            'tipo_documento' => 'required|string',
            'rut_proveedor' => 'required|string',
            'nombre_proveedor' => 'required|string',
            'descripcion' => 'required|string',
            'cantidad' => 'required|integer|min:1',
            'monto' => 'required',
        ]);

        try {
            $monto = $this->normalizeAmount($request->input('monto'));

            $updateData = [
                'ceco' => $validated['ceco'],
                'tipo_documento' => $validated['tipo_documento'],
                'rut' => $validated['rut_proveedor'],
                'proveedor' => $validated['nombre_proveedor'],
                'descripcion' => $validated['descripcion'],
                'cantidad' => $validated['cantidad'],
                'monto' => $monto,
                'estado' => 'Solicitada', // Volver a estado inicial para revisión
                'updated_at' => now(),
            ];

            // Re-armar datos_extra si es necesario, o mantener los antiguos
            $oldExtra = json_decode($solicitud->datos_extra, true) ?? [];
            $newExtra = array_merge($oldExtra, [
                'coordinador' => $validated['coordinador'],
                'tipo_servicio' => $validated['tipo_servicio'],
                'tipo_proyecto' => $validated['tipo_proyecto'],
                'editado_por' => auth()->user()->email,
                'fecha_edicion' => now()->toDateTimeString(),
            ]);
            $updateData['datos_extra'] = json_encode($newExtra, JSON_UNESCAPED_UNICODE);

            DB::table('oc_solicitudes')->where('id', $id)->update($updateData);

            AuditService::log('OC_SOLICITUD_UPDATED', [
                'id' => $id,
                'user' => auth()->user()->email,
                'old_status' => $solicitud->estado
            ]);

            return redirect()->route('oc.dashboard')->with('success', 'Solicitud actualizada correctamente y enviada a revisión.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar la solicitud: ' . $e->getMessage());
        }
    }

    private function normalizeAmount($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $amount = trim((string) $value);
        $amount = preg_replace('/[^\d,.-]/', '', $amount);

        if (str_contains($amount, ',') && str_contains($amount, '.')) {
            if (strrpos($amount, ',') > strrpos($amount, '.')) {
                $amount = str_replace('.', '', $amount);
                $amount = str_replace(',', '.', $amount);
            } else {
                $amount = str_replace(',', '', $amount);
            }
        } elseif (str_contains($amount, ',')) {
            $amount = str_replace('.', '', $amount);
            $amount = str_replace(',', '.', $amount);
        }

        if (substr_count($amount, '.') > 1) {
            $parts = explode('.', $amount);
            $decimal = array_pop($parts);
            $integer = implode('', $parts);
            $amount = $integer.'.'.$decimal;
        }

        if (! is_numeric($amount)) {
            throw ValidationException::withMessages([
                'monto' => 'El formato del monto no es válido.',
            ]);
        }

        return (float) $amount;
    }
}
