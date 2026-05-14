<?php

namespace App\Http\Controllers\Viajes;

use Illuminate\Http\Request;
use App\Models\Viajes\Solicitud;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    private function getCecoMap()
    {
        return [
            '20001' => '20001 - Finanzas General',
            '20002' => '20002 - Finanzas y Operaciones',
            '20003' => '20003 - Desarrollo Organizacional',
            '20004' => '20004 - Operaciones',
            '20005' => '20005 - Servicios Generales',
            '20131' => '20131 - TI',
            '20132' => '20132 - Cursos Internos Fundación',
            '20133' => '20133 - Sofofa Servicios',
            '20134' => '20134 - Cursos Téc y Bec Fun',
            '20136' => '20136 - Gestión de Clientes',
            '20139' => '20139 - Innovación',
            '20143' => '20143 - Gerencias',
            '20147' => '20147 - Comunicaciones',
            '20148' => '20148 - Control de Gestión',
            '20137' => '20137 - Sucursal Concepción',
            '20141' => '20141 - Metro',
            '20144' => '20144 - Academia Latam',
            '20146' => '20146 - Academia Leonera',
            '20150a' => '20150 - Gastos Inversión Academia',
            '20150b' => '20150 - Academia Forma',
            '20151' => '20151 - Gestión Interna',
            '20152' => '20152 - Academia Carozzi',
            '20135' => '20135 - Academia SQM',
            '20153' => '20153 - Inversiones SQM',
            '20006' => '20006 - Formación',
            '20138' => '20138 - Plataforma',
            '20140' => '20140 - Gestión del Conocimiento',
            '20149' => '20149 - Diseño',
        ];
    }

    public function data(Request $request)
    {
        try {
            $cecoMap = $this->getCecoMap();
            $user = auth()->user();
            $rol = $user->rol ?? 'usuario';
            $isPrivileged = in_array($rol, ['admin', 'super_admin', 'aprobador', 'gestor']);
            
            $statusFilter = $request->query('status');
            $cecoFilter = $request->query('ceco');

            // Base query for access control
            $accessQuery = Solicitud::query();
            if (!$isPrivileged) {
                $accessQuery->where('user_id', $user->id);
            }

            // List of unique CECOs for filtering
            $listaCecos = (clone $accessQuery)->distinct()->pluck('ceco')->filter()->values()
                ->map(fn($c) => $cecoMap[$c] ?? $c);

            // Apply CECO filter to the base query for KPIs and everything else
            if ($cecoFilter && $cecoFilter !== 'all') {
                $accessQuery->where('ceco', $cecoFilter);
            }

            // Totales por estado (based on access-controlled and CECO-filtered base query)
            $total = (clone $accessQuery)->count();
            $aprobadas = (clone $accessQuery)->where('estado', 'aprobado')->count();
            $pendientes = (clone $accessQuery)->where('estado', 'pendiente')->count();
            $rechazadas = (clone $accessQuery)->where('estado', 'rechazado')->count();

            // Further filter by status if requested
            $query = clone $accessQuery;
            if ($statusFilter && $statusFilter !== 'total' && $statusFilter !== 'all') {
                $query->where('estado', $statusFilter);
            }

            // Gastos por CECO (Total final) - Only for gestionado requests
            $gastosCecos = (clone $query)
                ->join('gestiones', 'solicitudes.id', '=', 'gestiones.solicitud_id')
                ->select('solicitudes.ceco', DB::raw('SUM(gestiones.monto_pasaje + COALESCE(gestiones.monto_hotel, 0) + COALESCE(gestiones.monto_traslado, 0)) as total'))
                ->groupBy('solicitudes.ceco')
                ->orderByDesc('total')
                ->get()
                ->map(function($item) use ($cecoMap) {
                    $item->ceco = $cecoMap[$item->ceco] ?? $item->ceco;
                    return $item;
                });

            // Por mes (últimos 6 meses)
            $mensual = (clone $query)->selectRaw('DATE_FORMAT(fecha_viaje, "%b %Y") as mes, COUNT(*) as cantidad')
                ->where('fecha_viaje', '>=', now()->subMonths(5)->startOfMonth())
                ->groupBy('mes')
                ->orderByRaw('MIN(fecha_viaje)')
                ->get();

            // Por destino (top 6)
            $destinos = (clone $query)->select('destino', DB::raw('COUNT(*) as cantidad'))
                ->groupBy('destino')
                ->orderByDesc('cantidad')
                ->limit(6)
                ->get();

            // Por CECO (Distribución por cantidad, top 6)
            $cecosDistribucion = (clone $query)->select('ceco', DB::raw('COUNT(*) as cantidad'))
                ->groupBy('ceco')
                ->orderByDesc('cantidad')
                ->limit(6)
                ->get()
                ->map(function($item) use ($cecoMap) {
                    $item->ceco = $cecoMap[$item->ceco] ?? $item->ceco;
                    return $item;
                });

            // Por Tipo (Interno vs Externo)
            $internos = (clone $query)->where('tipo', 'interno')->count();
            $externos = (clone $query)->where('tipo', 'externo')->count();

            // Solicitudes recientes para la tabla
            $recientes = (clone $query)->with('solicitante')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function($s) {
                    return [
                        'id' => $s->id,
                        'nombre' => $s->solicitante->name ?? 'N/A',
                        'destino' => $s->origen ? $s->origen . ' → ' . $s->destino : $s->destino,
                        'fecha' => $s->fecha_viaje ? $s->fecha_viaje->format('d M, Y') : 'N/A',
                        'estado' => $s->estado,
                    ];
                });

            // Gasto Total Acumulado (Sum of all gestionado costs)
            $gastoTotal = (clone $accessQuery)
                ->join('gestiones', 'solicitudes.id', '=', 'gestiones.solicitud_id')
                ->sum(DB::raw('gestiones.monto_pasaje + COALESCE(gestiones.monto_hotel, 0) + COALESCE(gestiones.monto_traslado, 0)'));

            return response()->json([
                'totales' => [
                    'total' => $total,
                    'aprobadas' => $aprobadas,
                    'pendientes' => $pendientes,
                    'rechazadas' => $rechazadas,
                    'gasto_total' => $gastoTotal,
                ],
                'mensual' => $mensual,
                'destinos' => $destinos,
                'cecos' => $cecosDistribucion,
                'gastos_cecos' => $gastosCecos,
                'lista_cecos' => $listaCecos,
                'tipos' => [
                    'internos' => $internos,
                    'externos' => $externos,
                ],
                'recientes' => $recientes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendGmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'pdf' => 'required|string',
            ]);

            $email = $request->input('email');
            $pdfBase64 = $request->input('pdf');

            $parts = explode('base64,', $pdfBase64);
            if (count($parts) === 2) {
                $pdfData = base64_decode($parts[1]);
            } else {
                // Si por alguna razón no viene con el encabezado data URI, intentamos decodificarlo directo
                $pdfData = base64_decode($pdfBase64);
                if (!$pdfData) {
                    return response()->json(['success' => false, 'message' => 'Formato de PDF inválido.']);
                }
            }

            \Illuminate\Support\Facades\Mail::raw('Adjunto encontrarás el reporte PDF del Dashboard de Viajes.', function ($message) use ($email, $pdfData) {
                $message->to($email)
                    ->bcc(auth()->user()->email)
                    ->subject('Dashboard Viajes - Reporte PDF')
                    ->attachData($pdfData, 'dashboard_viajes_' . date('Y-m-d_His') . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error enviando correo de Viajes: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
