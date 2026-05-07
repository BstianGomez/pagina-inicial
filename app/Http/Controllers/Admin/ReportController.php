<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rendicion\Report;
use App\Models\Rendicion\Expense;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function oc()
    {
        // Stats
        $statusCounts = DB::table('oc_solicitudes')
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // Chart 1: Monto por CECO
        $sumByCeco = DB::table('oc_solicitudes')
            ->select('ceco', DB::raw('SUM(monto) as total_monto'))
            ->groupBy('ceco')
            ->orderByDesc('total_monto')
            ->limit(8)
            ->get();

        // Chart 2: Gasto mensual
        $sumByMonth = DB::table('oc_solicitudes')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(monto) as total_monto'))
            ->groupBy('month')
            ->orderBy('month')
            ->limit(6)
            ->get();

        $stats = [
            'total' => DB::table('oc_solicitudes')->count(),
            'pending' => $statusCounts['Solicitada'] ?? 0,
            'sent' => $statusCounts['Enviada'] ?? 0,
            'accepted' => $statusCounts['Aceptada'] ?? 0,
            'rejected' => $statusCounts['Rechazada'] ?? 0,
            'facturado' => $statusCounts['Facturado'] ?? 0,
        ];

        return view('admin.reports.oc', compact('stats', 'sumByCeco', 'sumByMonth'));
    }

    public function viajes()
    {
        // Stats
        $stats = [
            'total' => DB::table('solicitudes')->count(),
            'pending' => DB::table('solicitudes')->where('estado', 'pendiente')->count(),
            'approved' => DB::table('solicitudes')->where('estado', 'aprobado')->count(),
            'rejected' => DB::table('solicitudes')->where('estado', 'rechazado')->count(),
            'gasto_total' => DB::table('solicitudes')
                ->join('gestiones', 'solicitudes.id', '=', 'gestiones.solicitud_id')
                ->sum(DB::raw('gestiones.monto_pasaje + COALESCE(gestiones.monto_hotel, 0) + COALESCE(gestiones.monto_traslado, 0)')),
        ];

        // Chart 1: Mensual
        $mensual = DB::table('solicitudes')
            ->selectRaw('DATE_FORMAT(fecha_viaje, "%b %Y") as mes, COUNT(*) as cantidad')
            ->where('fecha_viaje', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('mes')
            ->orderByRaw('MIN(fecha_viaje)')
            ->get();

        // Chart 2: Destinos
        $destinos = DB::table('solicitudes')
            ->select('destino', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('destino')
            ->orderByDesc('cantidad')
            ->limit(6)
            ->get();

        // Chart 3: Distribución por Tipo (Internos vs Externos)
        $tipos = [
            'internos' => DB::table('solicitudes')->where('tipo', 'interno')->count(),
            'externos' => DB::table('solicitudes')->where('tipo', 'externo')->count(),
        ];

        $cecoMap = [
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

        // Chart 4: Distribución por CECO (Cantidad)
        $cecos = DB::table('solicitudes')
            ->select('ceco', DB::raw('COUNT(*) as cantidad'))
            ->whereNotNull('ceco')
            ->groupBy('ceco')
            ->orderByDesc('cantidad')
            ->limit(6)
            ->get()
            ->map(function($item) use ($cecoMap) {
                $item->ceco = $cecoMap[$item->ceco] ?? $item->ceco;
                return $item;
            });

        // Chart 5: Gasto por CECO (Monto)
        $gastos_cecos = DB::table('solicitudes')
            ->join('gestiones', 'solicitudes.id', '=', 'gestiones.solicitud_id')
            ->select('solicitudes.ceco', DB::raw('SUM(gestiones.monto_pasaje + COALESCE(gestiones.monto_hotel, 0) + COALESCE(gestiones.monto_traslado, 0)) as total'))
            ->whereNotNull('solicitudes.ceco')
            ->groupBy('solicitudes.ceco')
            ->orderByDesc('total')
            ->limit(6)
            ->get()
            ->map(function($item) use ($cecoMap) {
                $item->ceco = $cecoMap[$item->ceco] ?? $item->ceco;
                return $item;
            });

        return view('admin.reports.viajes', compact('stats', 'mensual', 'destinos', 'tipos', 'cecos', 'gastos_cecos'));
    }

    public function rendicion()
    {
        $inProcessStatuses = [
            Report::STATUS_SUBMITTED,
            Report::STATUS_PENDING_MANAGER_APPROVAL,
            Report::STATUS_OBSERVED_BY_MANAGER,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
            Report::STATUS_UNDER_REVIEW,
            Report::STATUS_OBSERVED_BY_REVIEWER,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
            Report::STATUS_APPROVED_BY_MANAGER,
            Report::STATUS_APPROVED_BY_REVIEWER,
            Report::STATUS_PENDING_PAYMENT,
        ];

        $stats = [
            'borrador'     => Report::where('status', Report::STATUS_DRAFT)->count(),
            'enviados'     => Report::where('status', Report::STATUS_SUBMITTED)->count(),
            'en_proceso'   => Report::whereIn('status', $inProcessStatuses)->count(),
            'rechazadas'   => Report::where('status', 'like', 'Rechazada%')->count(),
            'reembolsadas' => Report::whereIn('status', [Report::STATUS_REIMBURSED, Report::STATUS_CLOSED])->count(),
            'total'        => Report::count(),
        ];

        // Chart 1: Monthly trend
        $monthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthly[] = [
                'label' => $month->translatedFormat('M Y'),
                'amount' => (float) Report::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_amount')
            ];
        }

        // Chart 2: Categories
        $categoryStats = Expense::with('category')
            ->get()
            ->groupBy(fn($e) => $e->category->name ?? 'Otros')
            ->map(fn($group) => (float) $group->sum('amount'))
            ->sortByDesc(fn($v) => $v)
            ->take(6);

        return view('admin.reports.rendicion', compact('stats', 'monthly', 'categoryStats'));
    }

    public function downloadData(Request $request, $type)
    {
        return response()->streamDownload(function () use ($type) {
            echo "Reporte Detallado de " . strtoupper($type) . "\n";
            echo "Generado el: " . date('Y-m-d H:i:s') . "\n";
            echo "ID,Fecha,Monto,Estado\n";
            echo "1,2026-05-01,50000,Aprobado\n";
        }, "datos_{$type}_" . date('Ymd') . ".csv");
    }
}
