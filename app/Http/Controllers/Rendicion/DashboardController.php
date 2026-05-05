<?php

namespace App\Http\Controllers\Rendicion;

use App\Models\Rendicion\Expense;
use App\Models\Rendicion\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $inProcessStatuses = [
            Report::STATUS_SUBMITTED,
            Report::STATUS_PENDING_MANAGER_APPROVAL,
            Report::STATUS_OBSERVED_BY_MANAGER,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
            Report::STATUS_UNDER_REVIEW,
            Report::STATUS_OBSERVED_BY_REVIEWER,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
        ];

        $acceptedStatuses = [
            Report::STATUS_APPROVED_BY_MANAGER,
            Report::STATUS_APPROVED_BY_REVIEWER,
            Report::STATUS_PENDING_PAYMENT,
        ];

        $rejectedStatuses = [
            Report::STATUS_REJECTED_BY_MANAGER,
            Report::STATUS_REJECTED_BY_REVIEWER,
            Report::STATUS_CANCELLED,
        ];

        $reimbursedStatuses = [
            Report::STATUS_REIMBURSED,
            Report::STATUS_CLOSED,
        ];

        $stats = [
            'total_amount' => 0,
            'total_count' => 0,
            'in_process' => 0,
            'accepted' => 0,
            'rejected' => 0,
            'reimbursed' => 0,
        ];

        $fixedFundSummary = null;

        $isAdmin = $user->hasAnyRole(['Superadmin', 'Admin', 'Gestor', 'Aprobador']);

        $query = Report::query();
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('status', '!=', Report::STATUS_DRAFT);
            });
        }

        $statusGroup = $request->input('status_group');

        $stats['total_count'] = (clone $query)->count();
        $stats['total_amount'] = (float) (clone $query)->sum('total_amount');
        $stats['in_process'] = (clone $query)->whereIn('status', $inProcessStatuses)->count();
        $stats['accepted'] = (clone $query)->whereIn('status', $acceptedStatuses)->count();
        $stats['rejected'] = (clone $query)->whereIn('status', $rejectedStatuses)->count();
        $stats['reimbursed'] = (clone $query)->whereIn('status', $reimbursedStatuses)->count();

        $reportsQuery = (clone $query)
            ->with(['user', 'expenses.category', 'expenses.ceco'])
            ->latest();

        if ($statusGroup === 'in_process') {
            $reportsQuery->whereIn('status', $inProcessStatuses);
        } elseif ($statusGroup === 'accepted') {
            $reportsQuery->whereIn('status', $acceptedStatuses);
        } elseif ($statusGroup === 'rejected') {
            $reportsQuery->where('status', 'like', 'Rechazada%');
        } elseif ($statusGroup === 'reimbursed') {
            $reportsQuery->where('status', Report::STATUS_REIMBURSED);
        } else {
            $statusGroup = null;
        }

        $reports = $reportsQuery->paginate(10)->withQueryString();

        if ($user->has_fixed_fund) {
            $excludedStatuses = [
                Report::STATUS_REJECTED_BY_MANAGER,
                Report::STATUS_REJECTED_BY_REVIEWER,
                Report::STATUS_REIMBURSED,
                Report::STATUS_CLOSED,
                Report::STATUS_CANCELLED,
            ];

            $usedFixedFund = (float) Expense::query()
                ->where('rendition_type', 'Con fondo fijo')
                ->whereHas('report', function ($reportQuery) use ($user, $excludedStatuses) {
                    $reportQuery
                        ->where('user_id', $user->id)
                        ->whereNotIn('status', $excludedStatuses);
                })
                ->sum('amount');

            $assignedFixedFund = (float) $user->fixed_fund_amount;

            $fixedFundSummary = [
                'used' => $usedFixedFund,
                'remaining' => $assignedFixedFund - $usedFixedFund,
            ];
        }

        $draftExpensesCount = Expense::where('user_id', $user->id)
            ->where('status', Expense::STATUS_DRAFT)
            ->whereNull('report_id')
            ->count();

        return view('rendicion.dashboard', compact('reports', 'stats', 'statusGroup', 'fixedFundSummary', 'draftExpensesCount'));
    }

    public function analytics(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasAnyRole(['Superadmin', 'Admin', 'Gestor', 'Aprobador']);

        $query = Report::query();
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('status', '!=', Report::STATUS_DRAFT);
            });
        }

        // ── Status counts ──────────────────────────────────────────────
        $allReports = (clone $query)->get();

        $statusGroups = [
            'Borrador'      => Report::STATUS_DRAFT,
            'Enviados'      => Report::STATUS_SUBMITTED,
            'En Proceso'    => null, // multiple
            'Rechazadas'    => null,
            'Reembolsadas'  => Report::STATUS_REIMBURSED,
            'Cerradas'      => Report::STATUS_CLOSED,
            'Anuladas'      => Report::STATUS_CANCELLED,
        ];

        $inProcessStatuses = [
            Report::STATUS_PENDING_MANAGER_APPROVAL,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
            Report::STATUS_APPROVED_BY_MANAGER,
            Report::STATUS_UNDER_REVIEW,
            Report::STATUS_OBSERVED_BY_MANAGER,
            Report::STATUS_OBSERVED_BY_REVIEWER,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
            Report::STATUS_APPROVED_BY_REVIEWER,
            Report::STATUS_PENDING_PAYMENT,
        ];

        $counts = [
            'borrador'     => (clone $query)->where('status', Report::STATUS_DRAFT)->count(),
            'enviados'     => (clone $query)->where('status', Report::STATUS_SUBMITTED)->count(),
            'en_proceso'   => (clone $query)->whereIn('status', $inProcessStatuses)->count(),
            'rechazadas'   => (clone $query)->where('status', 'like', 'Rechazada%')->count(),
            'reembolsadas' => (clone $query)->whereIn('status', [Report::STATUS_REIMBURSED, Report::STATUS_CLOSED])->count(),
            'anuladas'     => (clone $query)->where('status', Report::STATUS_CANCELLED)->count(),
            'total'        => (clone $query)->count(),
        ];

        $amounts = [
            'borrador'     => (float)(clone $query)->where('status', Report::STATUS_DRAFT)->sum('total_amount'),
            'enviados'     => (float)(clone $query)->where('status', Report::STATUS_SUBMITTED)->sum('total_amount'),
            'en_proceso'   => (float)(clone $query)->whereIn('status', $inProcessStatuses)->sum('total_amount'),
            'rechazadas'   => (float)(clone $query)->where('status', 'like', 'Rechazada%')->sum('total_amount'),
            'reembolsadas' => (float)(clone $query)->whereIn('status', [Report::STATUS_REIMBURSED, Report::STATUS_CLOSED])->sum('total_amount'),
            'anuladas'     => (float)(clone $query)->where('status', Report::STATUS_CANCELLED)->sum('total_amount'),
            'total'        => (float)(clone $query)->sum('total_amount'),
        ];

        // ── Monthly trend (last 6 months) ─────────────────────────────
        $monthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $label = $month->translatedFormat('M Y');
            $count = (clone $query)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $amount = (float)(clone $query)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
            $monthly[] = ['label' => $label, 'count' => $count, 'amount' => $amount];
        }

        // ── Category breakdown ─────────────────────────────────────────
        $categoryStats = \App\Models\Rendicion\Expense::with('category')
            ->whereHas('report', function ($q) use ($user, $isAdmin) {
                if (!$isAdmin) {
                    $q->where('user_id', $user->id);
                } else {
                    $q->where(function ($sq) use ($user) {
                        $sq->where('user_id', $user->id)
                           ->orWhere('status', '!=', \App\Models\Rendicion\Report::STATUS_DRAFT);
                    });
                }
            })
            ->get()
            ->groupBy(fn($e) => $e->category->name ?? 'Otros')
            ->map(fn($group) => ['count' => $group->count(), 'amount' => $group->sum('amount')])
            ->sortByDesc('amount')
            ->take(8);

        // ── Recent reports for export table ───────────────────────────
        $recentReports = (clone $query)
            ->with(['user', 'expenses'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // ── Export CSV ────────────────────────────────────────────────
        if ($request->query('export') === 'csv') {
            $exportReports = (clone $query)->with(['user', 'expenses'])->latest()->get();
            $filename = 'rendiciones_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
            $callback = function () use ($exportReports) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel
                fputcsv($handle, ['ID', 'Título', 'Solicitante', 'Estado', 'Monto Total', 'N° Gastos', 'Fecha Creación'], ';');
                foreach ($exportReports as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->title,
                        $r->user->name ?? '—',
                        $r->status,
                        number_format($r->total_amount, 0, ',', '.'),
                        $r->expenses->count(),
                        $r->created_at->format('d/m/Y'),
                    ], ';');
                }
                fclose($handle);
            };
            return response()->stream($callback, 200, $headers);
        }

        // ── Per-status chart data for interactive charts ─────────────
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthlyLabels[] = now()->subMonths($i)->translatedFormat('M Y');
        }

        $statusKeyConfig = [
            'all'         => fn($q) => $q,
            'Borrador'    => fn($q) => $q->where('status', Report::STATUS_DRAFT),
            'Enviado'     => fn($q) => $q->where('status', Report::STATUS_SUBMITTED),
            'proceso'     => fn($q) => $q->whereIn('status', $inProcessStatuses),
            'Rechazada'   => fn($q) => $q->where('status', 'like', 'Rechazada%'),
            'Reembolsada' => fn($q) => $q->whereIn('status', [Report::STATUS_REIMBURSED, Report::STATUS_CLOSED]),
            'Anulada'     => fn($q) => $q->where('status', Report::STATUS_CANCELLED),
        ];

        $chartsByFilter = [];
        foreach ($statusKeyConfig as $key => $applyStatus) {
            // Category
            $expenses = \App\Models\Rendicion\Expense::with('category')
                ->whereHas('report', function ($rq) use ($user, $isAdmin, $applyStatus, $key) {
                    if (!$isAdmin) {
                        $rq->where('user_id', $user->id);
                    } else {
                        $rq->where(function ($sq) use ($user) {
                            $sq->where('user_id', $user->id)
                               ->orWhere('status', '!=', \App\Models\Rendicion\Report::STATUS_DRAFT);
                        });
                    }
                    
                    if ($key === 'all') {
                        $rq->whereIn('status', [\App\Models\Rendicion\Report::STATUS_REIMBURSED, \App\Models\Rendicion\Report::STATUS_CLOSED]);
                    } else {
                        $applyStatus($rq);
                    }
                })->get()
                ->groupBy(fn($e) => $e->category->name ?? 'Otros')
                ->map(fn($g) => (float) $g->sum('amount'))
                ->sortByDesc(fn($v) => $v)
                ->take(8);

            // Monthly
            $monthlyCounts  = [];
            $monthlyAmounts = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $mQ = $applyStatus(clone $query)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month);
                $monthlyCounts[]  = $mQ->count();
                $monthlyAmounts[] = (float) (clone $mQ)->sum('total_amount');
            }

            $chartsByFilter[$key] = [
                'catLabels'      => $expenses->keys()->values()->all(),
                'catAmounts'     => $expenses->values()->all(),
                'monthlyCounts'  => $monthlyCounts,
                'monthlyAmounts' => $monthlyAmounts,
            ];
        }

        return view('rendicion.analytics', compact('counts', 'amounts', 'monthly', 'categoryStats', 'recentReports', 'isAdmin', 'monthlyLabels', 'chartsByFilter'));
    }
}
