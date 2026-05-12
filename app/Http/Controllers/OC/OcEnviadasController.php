<?php

namespace App\Http\Controllers\OC;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OcEnviadasController extends Controller
{
    /**
     * Display a listing of OC enviadas.
     */
    public function index(Request $request)
    {
        $query = DB::table('oc_enviadas')
            ->leftJoin('oc_solicitudes', 'oc_solicitudes.id', '=', 'oc_enviadas.oc_solicitud_id')
            ->select(
                'oc_enviadas.*', 
                'oc_solicitudes.ceco as sol_ceco',
                'oc_solicitudes.proveedor as sol_proveedor',
                'oc_solicitudes.rut as sol_rut',
                'oc_solicitudes.descripcion as sol_descripcion',
                'oc_solicitudes.monto as sol_monto',
                'oc_solicitudes.estado as solicitud_estado', 
                'oc_solicitudes.estado_facturacion as solicitud_estado_facturacion', 
                'oc_solicitudes.tipo_solicitud', 
                'oc_solicitudes.tipo_documento'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('oc_enviadas.numero_oc', 'like', "%{$search}%")
                  ->orWhere('oc_enviadas.proveedor', 'like', "%{$search}%")
                  ->orWhere('oc_enviadas.descripcion', 'like', "%{$search}%")
                  ->orWhere('oc_enviadas.rut', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('oc_solicitudes.tipo_solicitud', $request->tipo);
        }

        $rows = $query->orderByDesc('oc_enviadas.created_at')
            ->paginate(50)
            ->withQueryString();

        return view('oc.enviadas', ['rows' => $rows]);
    }

    /**
     * Download PDF for a specific OC enviada.
     */
    public function downloadPdf($id)
    {
        $oc = DB::table('oc_enviadas')
            ->leftJoin('oc_solicitudes', 'oc_solicitudes.id', '=', 'oc_enviadas.oc_solicitud_id')
            ->select(
                'oc_enviadas.*',
                'oc_solicitudes.tipo_documento as solicitud_tipo_documento',
                'oc_solicitudes.created_at as solicitud_created_at'
            )
            ->where('oc_enviadas.id', $id)
            ->first();

        if (! $oc) {
            abort(404, 'Orden de compra no encontrada');
        }

        // If there is an uploaded file, serve it directly
        if (! empty($oc->file_path) && \Illuminate\Support\Facades\Storage::disk('local')->exists($oc->file_path)) {
            return \Illuminate\Support\Facades\Storage::disk('local')->download($oc->file_path, 'OC_'.$oc->numero_oc.'.pdf');
        }

        // if no file exists and no fallback is desired
        abort(404, 'No hay archivo PDF subido para esta orden de compra.');
    }

    /**
     * Store a sent OC.
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('OC_SEND_ATTEMPT', [
            'oc_solicitud_id' => $request->input('oc_solicitud_id'),
            'numero_oc' => $request->input('numero_oc'),
            'user_id' => auth()->id(),
        ]);

        $request->validate([
            'oc_solicitud_id' => 'required|integer',
            'numero_oc' => 'required|string',
            'ceco' => 'nullable|string',
            'tipo_solicitud' => 'nullable|string',
            'proveedor' => 'nullable|string',
            'email_proveedor' => 'nullable|email',
            'rut' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'cantidad' => 'nullable|integer',
            'monto' => 'nullable',
            'oc_file' => 'required|file|mimes:pdf|max:10240', // Mandatory PDF, 10MB
            'comentario' => 'nullable|string',
        ]);

        try {
            $monto = $this->normalizeAmount($request->input('monto'));

            if (! is_null($monto) && $monto > 999999999999.99) {
                throw ValidationException::withMessages([
                    'monto' => 'El monto excede el máximo permitido.',
                ]);
            }

            // Verificar si ya está facturada
            $solicitud = DB::table('oc_solicitudes')->where('id', $request->input('oc_solicitud_id'))->first();
            if (!$solicitud || $solicitud->estado === 'Facturado' || ($solicitud->estado_facturacion ?? '') === 'Facturado') {
                throw new \Exception('No se puede enviar una orden de compra ya facturada.');
            }

            // Subir archivo
            $filePath = null;
            if ($request->hasFile('oc_file')) {
                $file = $request->file('oc_file');
                $safeName = 'OC_' . $request->input('numero_oc') . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('ocs_subidas', $safeName, 'local');
            }

            // Insertar en oc_enviadas
            $idEnviada = DB::table('oc_enviadas')->insertGetId([
                'oc_solicitud_id' => $request->input('oc_solicitud_id'),
                'numero_oc' => $request->input('numero_oc'),
                'ceco' => $request->input('ceco'),
                'tipo_solicitud' => $request->input('tipo_solicitud'),
                'proveedor' => $request->input('proveedor'),
                'email_proveedor' => $request->input('email_proveedor'),
                'rut' => $request->input('rut'),
                'descripcion' => $request->input('descripcion'),
                'cantidad' => $request->input('cantidad'),
                'monto' => $monto,
                'file_path' => $filePath,
                'comentario' => $request->input('comentario'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Actualizar estado de la solicitud a 'Enviada'
            DB::table('oc_solicitudes')
                ->where('id', $request->input('oc_solicitud_id'))
                ->update([
                    'estado' => 'Enviada',
                    'updated_at' => now(),
                ]);

            // Obtener los datos para el correo
            $oc = DB::table('oc_enviadas')->where('id', $idEnviada)->first();

            // Notificar al usuario (automático)
            try {
                if ($oc) {
                    $recipientEmail = !empty($oc->email_proveedor) ? $oc->email_proveedor : auth()->user()->email;
                    \Illuminate\Support\Facades\Mail::to($recipientEmail)
                        ->bcc(auth()->user()->email)
                        ->send(new \App\Mail\OC\OcProveedorMail($oc));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error enviando mail al proveedor: ' . $e->getMessage());
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'OC enviada correctamente']);
            }

            return redirect()->route('oc.enviadas')->with('success', 'OC enviada correctamente');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    /**
     * Send email for a specific OC enviada.
     */
    public function sendEmail($id)
    {
        try {
            $oc = DB::table('oc_enviadas')->where('id', $id)->first();
            if (!$oc) {
                return response()->json(['success' => false, 'message' => 'OC no encontrada'], 404);
            }

            // Verificar estado actual de la solicitud vinculada
            $solicitud = DB::table('oc_solicitudes')->where('id', $oc->oc_solicitud_id)->first();
            if ($solicitud) {
                $status = strtolower((string)$solicitud->estado);
                if ($status === 'enviada') {
                    return response()->json(['success' => false, 'message' => 'Esta orden de compra ya ha sido enviada al proveedor.'], 400);
                }
                if ($status === 'facturado' || strtolower((string)($solicitud->estado_facturacion ?? '')) === 'facturado') {
                    return response()->json(['success' => false, 'message' => 'No se puede enviar una orden ya facturada.'], 400);
                }
            }

            if (empty($oc->file_path)) {
                return response()->json(['success' => false, 'message' => 'Primero debe subir el archivo de la OC'], 400);
            }

            $recipientEmail = !empty($oc->email_proveedor) ? $oc->email_proveedor : auth()->user()->email;
            \Illuminate\Support\Facades\Mail::to($recipientEmail)
                ->bcc(auth()->user()->email)
                ->send(new \App\Mail\OC\OcProveedorMail($oc));

            // Actualizar estado a 'Enviada'
            DB::table('oc_solicitudes')
                ->where('id', $oc->oc_solicitud_id)
                ->update(['estado' => 'Enviada', 'updated_at' => now()]);

            return response()->json(['success' => true, 'message' => 'Email enviado correctamente al proveedor']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error re-enviando mail al proveedor: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error enviando email: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing sent OC.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'numero_oc' => 'required|string',
            'email_proveedor' => 'nullable|email',
            'descripcion' => 'nullable|string',
            'cantidad' => 'nullable|integer',
            'monto' => 'nullable',
            'oc_file' => 'nullable|file|mimes:pdf|max:10240', // PDF optional for update
            'comentario' => 'nullable|string',
        ]);

        try {
            $existing = DB::table('oc_enviadas')->where('id', $id)->first();
            if ($existing) {
                $solicitud = DB::table('oc_solicitudes')->where('id', $existing->oc_solicitud_id)->first();
                if ($solicitud) {
                    $status = strtolower((string)$solicitud->estado);
                    $billingStatus = strtolower((string)($solicitud->estado_facturacion ?? ''));
                    
                    if ($status === 'facturado' || $billingStatus === 'facturado') {
                        throw new \Exception('No se puede editar una orden de compra ya facturada.');
                    }
                    
                    if ($status === 'enviada') {
                        throw new \Exception('No se puede editar una orden de compra que ya ha sido enviada al proveedor.');
                    }
                }
            }

            $monto = $this->normalizeAmount($request->input('monto'));

            $data = [
                'numero_oc' => $request->input('numero_oc'),
                'email_proveedor' => $request->input('email_proveedor'),
                'descripcion' => $request->input('descripcion'),
                'cantidad' => $request->input('cantidad'),
                'monto' => $monto,
                'comentario' => $request->input('comentario'),
                'updated_at' => now(),
            ];

            // Subir nuevo archivo si se proporciona
            if ($request->hasFile('oc_file')) {
                $file = $request->file('oc_file');
                $safeName = 'OC_' . $request->input('numero_oc') . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('ocs_subidas', $safeName, 'local');
                $data['file_path'] = $filePath;
            }

            DB::table('oc_enviadas')->where('id', $id)->update($data);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'OC actualizada correctamente']);
            }

            return redirect()->route('oc.enviadas')->with('success', 'OC actualizada correctamente');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->with('error', 'Error actualizando OC: '.$e->getMessage());
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
