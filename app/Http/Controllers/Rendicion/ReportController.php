<?php

namespace App\Http\Controllers\Rendicion;

use App\Models\Rendicion\Category;
use App\Models\Rendicion\Ceco;
use App\Models\Rendicion\Expense;
use App\Models\Rendicion\Report;
use App\Models\User;
use App\Mail\Rendicion\ReportStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function projectIndex(Request $request)
    {
        return $this->index($request, true);
    }

    public function index(Request $request, $onlyProjects = false)
    {
        $user = Auth::user();
        $query = Report::query()->with(['user', 'expenses.category', 'expenses.ceco']);

        if ($onlyProjects) {
            $query->whereNotNull('project_number');
        } else {
            $query->whereNull('project_number');
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'iLike', "%{$search}%")
                    ->orWhere('id', 'cast(id as text) iLike ?', ["%{$search}%"])
                    ->orWhereHas('user', function ($sq) use ($search) {
                        $sq->where('name', 'iLike', "%{$search}%");
                    });
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('expenses', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } elseif ($request->filled('status_group')) {
            $statusGroup = $request->input('status_group');

            if ($statusGroup === 'in_process') {
                $query->whereIn('status', [
                    Report::STATUS_PENDING_MANAGER_APPROVAL,
                    Report::STATUS_UNDER_REVIEW,
                    Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
                    Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
                ]);
            } elseif ($statusGroup === 'accepted') {
                $query->whereIn('status', [
                    Report::STATUS_APPROVED_BY_MANAGER,
                    Report::STATUS_APPROVED_BY_REVIEWER,
                    Report::STATUS_PENDING_PAYMENT,
                ]);
            } elseif ($statusGroup === 'rejected') {
                $query->where('status', 'like', 'Rechazada%');
            } elseif ($statusGroup === 'reimbursed') {
                $query->where('status', Report::STATUS_REIMBURSED);
            }
        }

        if ($request->filled('ceco')) {
            $query->whereHas('expenses', function ($q) use ($request) {
                $q->where('ceco_id', $request->ceco);
            });
        }

        if ($user->hasRole('Gestor')) {
            $visibleInboxStatuses = [
                Report::STATUS_PENDING_MANAGER_APPROVAL,
                Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
            ];

            $query->where(function($q) use ($user, $visibleInboxStatuses) {
                $q->where('user_id', $user->id)
                  ->orWhereIn('status', $visibleInboxStatuses);
            });
        } elseif (!$user->hasAnyRole(['Superadmin', 'Admin', 'Aprobador'])) {
            $query->where('user_id', $user->id);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get()->unique('name');
        $cecos = Ceco::whereNotIn('code', ['TI-001', 'ADM-002', 'RRHH-003', 'COM-004'])->orderBy('code')->get();
        
        $statuses = [
            Report::STATUS_DRAFT,
            Report::STATUS_PENDING_MANAGER_APPROVAL,
            Report::STATUS_OBSERVED_BY_MANAGER,
            Report::STATUS_APPROVED_BY_MANAGER,
            Report::STATUS_REJECTED_BY_MANAGER,
            Report::STATUS_UNDER_REVIEW,
            Report::STATUS_OBSERVED_BY_REVIEWER,
            Report::STATUS_APPROVED_BY_REVIEWER,
            Report::STATUS_PENDING_PAYMENT,
            Report::STATUS_REIMBURSED,
        ];

        $isProject = $onlyProjects;
        return view('rendicion.reports.index', compact('reports', 'categories', 'statuses', 'cecos', 'isProject'));
    }

    public function bulkExport(Request $request)
    {
        $reportIds = $request->input('selected_reports', []);
        
        if (empty($reportIds)) {
            return back()->with('error', 'Debe seleccionar al menos una rendición para exportar.');
        }

        $user = Auth::user();
        $query = Report::whereIn('id', $reportIds)->with(['user', 'expenses.category', 'expenses.ceco']);

        // Security check: only export what they can see
        if ($user->hasRole('Gestor')) {
            $visibleStatuses = [
                Report::STATUS_PENDING_MANAGER_APPROVAL,
                Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
                Report::STATUS_APPROVED_BY_MANAGER,
            ];
            $query->whereIn('status', $visibleStatuses);
        } elseif (!$user->hasAnyRole(['Superadmin', 'Admin', 'Aprobador'])) {
            $query->where('user_id', $user->id);
        }

        $reports = $query->get();

        if ($reports->isEmpty()) {
            return back()->with('error', 'No se encontraron rendiciones válidas para exportar.');
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rendiciones_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for Excel
            
            fputcsv($file, ['ID', 'Solicitante', 'Título', 'Fecha Creación', 'Estado', 'Monto Total', 'CECO principal', 'Categoría principal']);

            foreach ($reports as $report) {
                $expense = $report->expenses->first();
                fputcsv($file, [
                    $report->id,
                    $report->user->name,
                    $report->title,
                    $report->created_at->format('d/m/Y H:i'),
                    $report->status,
                    $report->total_amount,
                    $expense->ceco->code ?? 'N/A',
                    $expense->category->name ?? 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function inbox(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['Gestor', 'Aprobador', 'Admin', 'Superadmin'])) {
            abort(403);
        }

        $query = Report::query()->with(['user', 'expenses.category', 'expenses.ceco']);

        $targetStatuses = [];

        if ($user->hasRole('Gestor')) {
            $targetStatuses = array_merge($targetStatuses, [
                Report::STATUS_PENDING_MANAGER_APPROVAL,
                Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
                Report::STATUS_APPROVED_BY_MANAGER,
            ]);
        }

        if ($user->hasRole('Aprobador')) {
            $targetStatuses = array_merge($targetStatuses, [
                Report::STATUS_UNDER_REVIEW,
                Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
            ]);
        }

        if ($user->hasAnyRole(['Admin', 'Superadmin'])) {
            $targetStatuses = Report::pendingStatuses();
        }

        $query->whereIn('status', array_unique($targetStatuses));

        $reports = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get()->unique('name');
        $statuses = Report::pendingStatuses();

        return view('rendicion.reports.inbox', compact('reports', 'categories', 'statuses'));
    }

    public function show(Report $report)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['Superadmin', 'Admin', 'Gestor', 'Aprobador']) && $report->user_id !== $user->id) {
            abort(403);
        }

        $report->load(['expenses.category', 'expenses.ceco', 'comments.user', 'user']);
        return view('rendicion.reports.show', compact('report'));
    }

    public function create(Request $request)
    {
        // Todos los usuarios autenticados pueden crear rendiciones
        $projectNumber = $request->query('project') ? 'PROYECTO_PENDIENTE' : null;

        $report = Report::create([
            'user_id' => Auth::id(),
            'title' => 'Borrador de rendición',
            'status' => Report::STATUS_DRAFT,
            'total_amount' => 0,
            'project_number' => $projectNumber,
        ]);

        return redirect()->route('rendicion.expenses.createStep1', ['report' => $report->id]);
    }

    public function createStep1(Report $report)
    {
        $user = Auth::user();
        if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!$report->isEditableByRequester() && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede editar en su estado actual.');
        }

        $cecos = Ceco::query()->whereNotIn('code', ['TI-001', 'ADM-002', 'RRHH-003', 'COM-004'])->orderBy('code')->get();
        $expense = $report->expenses()->latest()->first();

        // For project reports: fetch distinct project numbers from available expenses
        $availableProjects = [];
        if ($report->project_number) {
            $availableProjects = \App\Models\Rendicion\Expense::where('user_id', $user->id)
                ->whereNotNull('project_number')
                ->whereNull('report_id')
                ->whereIn('status', [\App\Models\Rendicion\Expense::STATUS_DRAFT, \App\Models\Rendicion\Expense::STATUS_READY])
                ->distinct()
                ->pluck('project_number')
                ->sort()
                ->values()
                ->toArray();
        }

        return view('rendicion.reports.create-step1', compact('report', 'expense', 'cecos', 'availableProjects'));
    }

    public function storeStep1(Request $request, Report $report)
    {
        $user = Auth::user();
        if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!$report->isEditableByRequester() && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede modificar en su estado actual.');
        }

        

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ceco_id' => 'nullable|exists:cecos,id',
            'project_number' => $report->project_number ? 'required|string|max:255' : 'nullable|string|max:255',
            'project_prefix' => 'nullable|string|in:OT,OC,OP',
        ]);

        $projectNumber = $validated['project_number'] ?? $report->project_number;
        if ($request->filled('project_prefix') && $request->filled('project_number')) {
            $projectNumber = Str::upper(trim($request->project_prefix)) . '-' . Str::upper(trim($request->project_number));
        }

        $report->update([
            'title' => $validated['title'], 
            'ceco_id' => $validated['ceco_id'],
            'project_number' => $projectNumber
        ]);

        // "Continuar" from Step 1 should always move to Step 2 (expense selection).
        if ($request->input('action') !== 'draft') {
            return redirect()
                ->route('rendicion.expenses.createStep2', ['report' => $report->id])
                ->with('success', 'Información general guardada. Continúe con la selección de gastos.');
        }

        

        $isDraft = $request->input('action') === 'draft';

        if (!$isDraft && $report->expenses()->where('amount', '<=', 0)->exists()) {
            return redirect()
                ->route('rendicion.expenses.createStep2', ['report' => $report->id])
                ->with('error', 'No se puede enviar la solicitud con monto 0. Complete un monto mayor a 0 en todos los gastos.');
        }

        $nextStatus = $isDraft
            ? Report::STATUS_DRAFT
            : match ($report->status) {
                Report::STATUS_OBSERVED_BY_MANAGER => Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
                Report::STATUS_OBSERVED_BY_REVIEWER => Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
                default => Report::STATUS_UNDER_REVIEW,
            };

        $oldStatus = $report->status;

        $report->update([
            'title' => $validated['title'],
            'status' => $nextStatus,
            'total_amount' => $report->expenses()->sum('amount'),
        ]);

        if (!$isDraft) {
            $comment = $report->comments()->create([
                'user_id' => $user->id,
                'comment' => 'Informe enviado por solicitante.',
                'from_status' => $oldStatus,
                'to_status' => $nextStatus,
            ]);

            $rolesToNotify = match ($nextStatus) {
                Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER => ['Gestor'],
                default => ['Aprobador'],
            };

            $this->notifyReportStatusChanged($report, $comment, $rolesToNotify);
        }

        $message = $isDraft ? 'Rendición guardada como borrador.' : 'Rendición enviada correctamente.';

        return redirect()->route('rendicion.dashboard')->with('success', $message);
    }

    public function createStep2(Report $report)
    {
        $user = Auth::user();
        if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!$report->isEditableByRequester() && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede editar en su estado actual.');
        }

        $assignedExpenses = $report->expenses()->with('category', 'ceco')->get();
        $isProjectReport = !empty($report->project_number);

        $availableExpenses = \App\Models\Rendicion\Expense::where('amount', '>', 0)
            ->where(function ($q) use ($user, $report, $isProjectReport) {
                $q->where(function ($subQ) use ($user, $report, $isProjectReport) {
                    $subQ->where('user_id', $user->id)
                         ->whereNull('report_id');

                    if ($isProjectReport) {
                        // For project reports: show all expenses that have ANY project_number
                        $subQ->whereIn('status', [\App\Models\Rendicion\Expense::STATUS_DRAFT, \App\Models\Rendicion\Expense::STATUS_READY])
                             ->whereNotNull('project_number');
                    } else {
                        // For normal reports: only show READY expenses with no project number
                        $subQ->where('status', \App\Models\Rendicion\Expense::STATUS_READY)
                             ->whereNull('project_number');
                    }
                })->orWhereHas('report', function ($subQ) use ($user, $report) {
                    $subQ->where('user_id', $user->id)
                        ->where('id', '!=', $report->id)
                        ->whereIn('status', [
                            \App\Models\Rendicion\Report::STATUS_OBSERVED_BY_MANAGER,
                            \App\Models\Rendicion\Report::STATUS_OBSERVED_BY_REVIEWER,
                            \App\Models\Rendicion\Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
                            \App\Models\Rendicion\Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
                        ]);
                });
            })
            ->with(['category', 'ceco', 'report'])
            ->latest('expense_date')
            ->latest('id')
            ->get();

        $expense = $assignedExpenses->first();
        $categories = Category::query()->orderBy('name')->get()->unique('name');

        return view('rendicion.reports.create-step2', compact('report', 'expense', 'categories', 'assignedExpenses', 'availableExpenses'));
    }

    public function toggleExpense(Request $request, Report $report, Expense $expense)
    {
        $user = Auth::user();
        $hasAdminAccess = $user->hasAnyRole(['Superadmin', 'Admin', 'Super Admin']);

        if (!$hasAdminAccess) {
            if ($report->user_id !== $user->id || $expense->report->user_id !== $user->id) {
                abort(403);
            }
        }

        if (!$report->isEditableByRequester() && !$hasAdminAccess) {
            return response()->json(['success' => false, 'message' => 'El informe no se puede editar.'], 422);
        }

        $oldReportId = $expense->report_id;

        if ((int) $oldReportId === (int) $report->id) {
            // Remove from current selection: change to draft
            $expense->update([
                'report_id' => null,
                'status' => \App\Models\Rendicion\Expense::STATUS_DRAFT,
                'user_id' => $report->user_id
            ]);
            $report->update(['total_amount' => $report->expenses()->sum('amount')]);

            return response()->json(['success' => true, 'action' => 'removed']);
        }

        // Add to current report.
        $expense->update([
            'report_id' => $report->id,
            'ceco_id' => $report->ceco_id,
            'status' => \App\Models\Rendicion\Expense::STATUS_ASSIGNED
        ]);

        if ($oldReportId) {
            $oldReport = Report::find($oldReportId);
            if ($oldReport) {
                if ($oldReport->status === Report::STATUS_DRAFT && $oldReport->expenses()->count() === 0) {
                    $oldReport->delete();
                } else {
                    $oldReport->update(['total_amount' => $oldReport->expenses()->sum('amount')]);
                }
            }
        }

        $report->update(['total_amount' => $report->expenses()->sum('amount')]);

        return response()->json(['success' => true, 'action' => 'added']);
    }

    public function storeStep2(Request $request, Report $report)
    {
        $user = Auth::user();
        if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!$report->isEditableByRequester() && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            return back()->with('error', 'Este informe no se puede modificar en su estado actual.');
        }

        $action = $request->input('action', 'submit');
        $totalAmount = (float) $report->expenses()->sum('amount');

        if ($totalAmount <= 0) {
            return back()->with('error', 'Debe seleccionar al menos un gasto con monto mayor a 0 para continuar.');
        }

        $oldStatus = $report->status;

        if ($action === 'draft') {
            $report->update([
                'status' => Report::STATUS_DRAFT,
                'total_amount' => $totalAmount,
            ]);

            return redirect()
                ->route('rendicion.expenses.createStep2', ['report' => $report->id])
                ->with('success', 'Reporte guardado. Puede continuar agregando gastos.');
        }

        $nextStatus = match ($report->status) {
            Report::STATUS_OBSERVED_BY_MANAGER => Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
            Report::STATUS_OBSERVED_BY_REVIEWER => Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
            default => Report::STATUS_UNDER_REVIEW,
        };

        $report->update([
            'status' => $nextStatus,
            'total_amount' => $totalAmount,
        ]);

        $comment = $report->comments()->create([
            'user_id' => $user->id,
            'comment' => 'Informe enviado por solicitante desde selección de gastos.',
            'from_status' => $oldStatus,
            'to_status' => $nextStatus,
        ]);

        $rolesToNotify = match ($nextStatus) {
            Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER => ['Gestor'],
            default => ['Aprobador'],
        };

        $this->notifyReportStatusChanged($report, $comment, $rolesToNotify);

        return redirect()->route('rendicion.dashboard')->with('success', 'Rendición enviada correctamente.');
    }

    public function approve(Request $request, Report $report)
    {
        $user = Auth::user();
        $oldStatus = $report->status;
        $nextStatus = null;
        $hasFullAccess = $user->hasAnyRole(['Admin', 'Superadmin', 'Super Admin']);
        $canManagerApprove = $hasFullAccess || $user->can('reports.approve_manager') || $user->hasRole('Gestor');
        $canReviewerApprove = $hasFullAccess || $user->can('reports.approve_reviewer') || $user->hasRole('Aprobador');
        
        // Fase 1 — Aprobación Gestor (primera revisión)
        if ($canReviewerApprove && in_array($report->status, [
            Report::STATUS_UNDER_REVIEW,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
        ], true)) {
            $nextStatus = Report::STATUS_PENDING_MANAGER_APPROVAL;
        } 
        
        // Fase 2 — Aprobación Aprobador (después del Gestor)
        elseif ($canManagerApprove && in_array($report->status, [
            Report::STATUS_PENDING_MANAGER_APPROVAL,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
        ], true)) {
            $nextStatus = Report::STATUS_APPROVED_BY_MANAGER;
        }

        if ($nextStatus) {
            $report->update(['status' => $nextStatus]);
            
            $defaultComment = ($request->isMethod('get')) ? 'Aprobado vía email.' : 'Aprobado.';
            
            $comment = $report->comments()->create([
                'user_id' => $user->id,
                'comment' => $request->comment ?? $defaultComment,
                'from_status' => $oldStatus,
                'to_status' => $nextStatus,
            ]);

            $rolesToNotify = match ($nextStatus) {
                Report::STATUS_PENDING_MANAGER_APPROVAL => ['Gestor'],
                Report::STATUS_APPROVED_BY_MANAGER => ['Solicitante'],
                default => [],
            };
            $this->notifyReportStatusChanged($report, $comment, $rolesToNotify);

            return back()->with('success', 'Rendición aprobada correctamente.');
        }

        return back()->with('error', 'No tiene permisos para aprobar esta rendición en su estado actual.');
    }

    public function reject(Request $request, Report $report)
    {
        // Require comment only if NOT a signed GET request
        if (!$request->isMethod('get')) {
            $request->validate(['comment' => 'required|string']);
        }
        
        $user = Auth::user();
        $oldStatus = $report->status;
        $nextStatus = null;
        $hasFullAccess = $user->hasAnyRole(['Admin', 'Superadmin', 'Super Admin']);
        $canManagerReject = $hasFullAccess || $user->can('reports.reject_manager') || $user->hasRole('Gestor');
        $canReviewerReject = $hasFullAccess || $user->can('reports.reject_reviewer') || $user->hasRole('Aprobador');

        // Fase 1 — Rechazo Gestor
        if ($canReviewerReject && in_array($report->status, [
            Report::STATUS_UNDER_REVIEW,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
        ], true)) {
            $nextStatus = Report::STATUS_REJECTED_BY_REVIEWER;
        } 
        
        // Fase 2 — Rechazo Aprobador
        elseif ($canManagerReject && in_array($report->status, [
            Report::STATUS_PENDING_MANAGER_APPROVAL,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
        ], true)) {
            $nextStatus = Report::STATUS_REJECTED_BY_MANAGER;
        }

        if (!$nextStatus) {
            return back()->with('error', 'No tiene permisos para rechazar esta rendición en su estado actual.');
        }

        $report->update(['status' => $nextStatus]);
        
        $defaultComment = ($request->isMethod('get')) ? 'Rechazado vía email.' : $request->comment;
        
        $comment = $report->comments()->create([
            'user_id' => $user->id,
            'comment' => $defaultComment,
            'from_status' => $oldStatus,
            'to_status' => $nextStatus,
        ]);

        $this->notifyReportStatusChanged($report, $comment);

        return back()->with('success', 'Rendición rechazada correctamente.');
    }

    public function observe(Request $request, Report $report)
    {
        $request->validate(['comment' => 'required|string']);
        
        $user = Auth::user();
        $oldStatus = $report->status;
        $nextStatus = null;
        $hasFullAccess = $user->hasAnyRole(['Admin', 'Superadmin', 'Super Admin']);
        $canManagerObserve = $hasFullAccess || $user->can('reports.observe_manager') || $user->hasRole('Gestor');
        $canReviewerObserve = $hasFullAccess || $user->can('reports.observe_reviewer') || $user->hasRole('Aprobador');

        // Fase 1 — Observación Gestor
        if ($canReviewerObserve && in_array($report->status, [
            Report::STATUS_UNDER_REVIEW,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
        ], true)) {
            $nextStatus = Report::STATUS_OBSERVED_BY_REVIEWER;
        } 
        
        // Fase 2 — Observación Aprobador
        elseif ($canManagerObserve && in_array($report->status, [
            Report::STATUS_PENDING_MANAGER_APPROVAL,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
        ], true)) {
            $nextStatus = Report::STATUS_OBSERVED_BY_MANAGER;
        }

        if (!$nextStatus) {
            return back()->with('error', 'No tiene permisos para observar esta rendición en su estado actual.');
        }

        $report->update(['status' => $nextStatus]);

        $comment = $report->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment,
            'from_status' => $oldStatus,
            'to_status' => $nextStatus,
        ]);

        $this->notifyReportStatusChanged($report, $comment);

        return back()->with('success', 'Observación enviada correctamente.');
    }

    public function pay(Report $report)
    {
        if (!Auth::user()->hasAnyRole(['Gestor', 'Admin', 'Superadmin', 'Super Admin'])) {
            return back()->with('error', 'No tiene permisos para registrar reembolsos.');
        }

        if (!in_array($report->status, [Report::STATUS_APPROVED_BY_MANAGER, Report::STATUS_PENDING_PAYMENT], true)) {
            return back()->with('error', 'Este informe no está listo para devolución.');
        }

        $oldStatus = $report->status;
        $report->update(['status' => Report::STATUS_REIMBURSED]);
        $comment = $report->comments()->create([
            'user_id' => Auth::id(),
            'comment' => 'Marcada como reembolsada.',
            'from_status' => $oldStatus,
            'to_status' => Report::STATUS_REIMBURSED,
        ]);

        $this->notifyReportStatusChanged($report, $comment);

        return back()->with('success', 'Rendición marcada como reembolsada.');
    }

    public function addExpense(Report $report)
    {
        $user = Auth::user();
        if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        if (!$report->isEditableByRequester()) {
            return back()->with('error', 'No se pueden añadir gastos a este informe en su estado actual.');
        }

        $categories = Category::orderBy('name')->get()->unique('name');
        
        $availableExpenses = Expense::where('user_id', $user->id)
            ->where('status', Expense::STATUS_READY)
            ->whereNull('report_id')
            ->orderBy('expense_date', 'desc')
            ->get();

        return view('rendicion.reports.add-expense', compact('report', 'categories', 'availableExpenses'));
    }

    public function storeNewExpense(Request $request, Report $report)
    {
        $user = Auth::user();
        if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'custom_category_name' => 'nullable|string|max:255',
            'ceco_id' => 'nullable|exists:cecos,id',
        ]);

        $categoryId = $validated['category_id'];
        if ((int) $categoryId === 6 && !empty($request->custom_category_name)) {
            $customCat = Category::firstOrCreate(
                ['name' => trim($request->custom_category_name)],
                ['requires_comanda' => false, 'is_custom' => true]
            );
            $categoryId = $customCat->id;
        }

        $expense = Expense::create([
            'report_id' => $report->id,
            'category_id' => $categoryId,
            'ceco_id' => $report->ceco_id ?? ($validated['ceco_id'] ?? null),
            'status' => Expense::STATUS_ASSIGNED,
            'rendition_type' => $this->resolveRenditionTypeByUserId($report->user_id),
            'reason' => 'Pendiente',
            'expense_date' => now()->toDateString(),
            'amount' => 0,
            'provider_name' => 'Pendiente',
            'provider_rut' => 'Pendiente',
            'document_type' => 'Boleta',
            'attachment_path' => null,
        ]);

        return redirect()->route('rendicion.expenses.edit', $expense)->with('success', 'Categoría seleccionada. Ahora completa los detalles del gasto.');
    }

    public function updateExpenseValidation(Request $request, Expense $expense)
    {
        if (!Auth::user()->hasAnyRole(['Aprobador', 'Admin', 'Superadmin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'field' => 'required|string|in:is_doc_valid,is_amount_valid,is_date_valid,is_duplicity_valid',
            'value' => 'required|boolean',
        ]);

        $expense->update([
            $validated['field'] => $validated['value']
        ]);

        return response()->json(['success' => true]);
    }

        public function destroy(Report $report)
        {
            $user = Auth::user();
            if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
                abort(403);
            }
            if ($report->status !== Report::STATUS_DRAFT) {
                return back()->with('error', 'Solo se pueden eliminar informes en estado Borrador.');
            }
            // Delete attached files
            foreach ($report->expenses as $expense) {
                if ($expense->attachment_path && $expense->attachment_path !== 'pendiente') {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->attachment_path);
                }
                if ($expense->comanda_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->comanda_path);
                }
            }
            $report->expenses()->delete();
            $report->comments()->delete();
            $report->delete();
            return redirect()->route('rendicion.reports.index')->with('success', 'Informe eliminado correctamente.');
        }

    public function destroyExpense(Expense $expense)
    {
        $user = Auth::user();
        $report = $expense->report;

        // Si el gasto tiene informe, validamos permisos sobre el informe
        if ($report) {
            if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
                abort(403);
            }
            if (!$report->isEditableByRequester() && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
                return back()->with('error', 'No se puede eliminar este gasto en el estado actual del informe.');
            }
            if ($report->expenses()->count() <= 1) {
                return back()->with('error', 'El informe debe tener al menos un gasto. Elimina el informe completo si deseas cancelarlo.');
            }
        } else {
            // Si no tiene informe (es borrador/listo), validamos que sea el dueño
            if ($expense->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
                abort(403);
            }
        }

        // Eliminar archivos adjuntos
        if ($expense->attachment_path && !in_array($expense->attachment_path, ['pendiente', 'Pendiente'])) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->attachment_path);
        }
        if ($expense->comanda_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->comanda_path);
        }

        $expense->delete();

        if ($report) {
            $report->update(['total_amount' => $report->expenses()->sum('amount')]);
        }

        return back()->with('success', 'Gasto eliminado correctamente.');
    }

        public function cancel(Report $report)
        {
            $user = Auth::user();
            if ($report->user_id !== $user->id && !$user->hasAnyRole(['Superadmin', 'Admin'])) {
                abort(403);
            }
            $nonCancellable = [
                Report::STATUS_REIMBURSED,
                Report::STATUS_CLOSED,
                Report::STATUS_CANCELLED,
            ];
            if (in_array($report->status, $nonCancellable, true)) {
                return back()->with('error', 'Este informe no puede ser anulado.');
            }
            $oldStatus = $report->status;
            $report->update(['status' => Report::STATUS_CANCELLED]);
            $report->comments()->create([
                'user_id' => $user->id,
                'comment' => 'Informe anulado por ' . $user->name . '.',
                'from_status' => $oldStatus,
                'to_status' => Report::STATUS_CANCELLED,
            ]);
            return back()->with('success', 'Informe anulado correctamente.');
        }

        public function enableEdit(Report $report)
        {
            $user = Auth::user();
            if (!$user->hasAnyRole(['Aprobador', 'Superadmin', 'Admin'])) {
                abort(403);
            }
            $allowedStatuses = [
                Report::STATUS_SUBMITTED,
                Report::STATUS_UNDER_REVIEW,
                Report::STATUS_PENDING_MANAGER_APPROVAL,
                Report::STATUS_APPROVED_BY_MANAGER,
            ];
            if (!in_array($report->status, $allowedStatuses, true)) {
                return back()->with('error', 'No se puede habilitar edición en el estado actual.');
            }
            $oldStatus = $report->status;
            $report->update(['status' => Report::STATUS_OBSERVED_BY_REVIEWER]);
            $report->comments()->create([
                'user_id' => $user->id,
                'comment' => 'Edición habilitada por el gestor. El solicitante puede corregir el informe.',
                'from_status' => $oldStatus,
                'to_status' => Report::STATUS_OBSERVED_BY_REVIEWER,
            ]);
            $this->notifyReportStatusChanged($report, null, ['Solicitante']);
            return back()->with('success', 'Edición habilitada. El solicitante recibirá una notificación.');
        }

    private function requiresComandaForCategory(?Category $category): bool
    {
        if (!$category) {
            return false;
        }

        $normalizedName = Str::of($category->name)
            ->ascii()
            ->lower()
            ->trim()
            ->value();

        return $normalizedName === 'alimentacion';
    }

    private function resolveRenditionTypeByUserId(int $userId): string
    {
        $owner = User::query()->select(['id', 'has_fixed_fund'])->find($userId);

        return $owner?->has_fixed_fund ? 'Con fondo fijo' : 'Sin fondo fijo';
    }

    private function notifyReportStatusChanged(Report $report, $comment = null, array $rolesToNotify = []): void
    {
        $recipients = collect();

        // Notify requester only if they are NOT the one who performed the action
        if ($report->user->email && $report->user_id !== auth()->id()) {
            $recipients->push($report->user->email);
        }

        // If 'Solicitante' was requested, ensure the owner is included
        if (in_array('Solicitante', $rolesToNotify) && $report->user->email) {
            $recipients->push($report->user->email);
        }

        // Filter out pseudo-roles that don't exist in the database for Spatie
        $spatieRoles = array_values(array_filter($rolesToNotify, function ($role) {
            return strtolower($role) !== 'solicitante';
        }));

        // Notify specified Spatie roles
        if (!empty($spatieRoles)) {
            $roleEmails = User::role($spatieRoles)
                ->whereNotNull('email')
                ->where('id', '!=', auth()->id())
                ->pluck('email');
            
            $recipients = $recipients->concat($roleEmails);
        }

        $uniqueRecipients = $recipients
            ->map(fn ($email) => mb_strtolower(trim((string) $email)))
            ->filter()
            ->unique()
            ->values();

        // Generate Signed URLs for one-click manager actions
        $approveUrl = null;
        $rejectUrl = null;

        if (in_array($report->status, [
            Report::STATUS_UNDER_REVIEW,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
            Report::STATUS_PENDING_MANAGER_APPROVAL,
            Report::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
        ], true)) {
            $approveUrl = URL::temporarySignedRoute('rendicion.reports.approve.signed', now()->addDays(7), ['report' => $report->id]);
            $rejectUrl = URL::temporarySignedRoute('rendicion.reports.reject.signed', now()->addDays(7), ['report' => $report->id]);
        }

        foreach ($uniqueRecipients as $email) {
            Mail::to($email)->send(new ReportStatusChanged($report, $comment, $approveUrl, $rejectUrl));
        }
    }
}
