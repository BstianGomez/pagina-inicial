<x-rendicion.layouts.app
    page_title="Detalle de Rendición"
    page_subtitle="Información completa del expediente de gasto.">
    <x-slot name="header_title">Detalle de Rendición</x-slot>

    <style>
        .report-detail-minimal {
            background: #f4f7fb;
        }

        .report-page {
            background: #f7fafd;
        }

        .report-head {
            background: #ffffff;
        }

        .report-detail-minimal .report-head {
            padding: 1.1rem 1.35rem 1rem;
            border-bottom: 1px solid #e2eaf3;
        }

        .report-detail-minimal .report-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.96rem;
            font-weight: 600;
            text-decoration: none;
        }

        .report-detail-minimal .report-back:hover {
            color: #1d4ed8;
        }

        .report-title {
            font-size: clamp(1.2rem, 2.2vw, 1.7rem);
            letter-spacing: -0.02em;
            text-transform: none;
            line-height: 1.1;
            margin: 0.5rem 0;
        }

        .report-detail-minimal .report-title {
            margin-top: 0.8rem;
            font-weight: 800;
            color: #0f172a;
        }

        .report-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.66rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #2563eb;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 999px;
            padding: 0.35rem 0.7rem;
        }

        .report-status {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border-radius: 999px;
            border: 1px solid currentColor;
            padding: 0.55rem 0.85rem;
            white-space: nowrap;
        }

        .report-content {
            display: grid;
            gap: 1rem;
            padding: 1rem;
            min-width: 0;
        }

        .report-detail-minimal .report-content {
            padding: 0.95rem;
        }

        .report-layout {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            min-width: 0;
        }

        .report-main,
        .report-timeline,
        .report-side > div {
            border: 1px solid #dfe8f2;
            box-shadow: 0 8px 18px -16px rgba(15, 23, 42, 0.26);
        }

        .report-main {
            border-radius: 1.25rem;
            background: #ffffff;
            padding: 1.1rem;
            min-width: 0;
            overflow: hidden;
        }

        .report-detail-minimal .report-main {
            border-radius: 1rem;
            padding: 1rem;
        }

        .report-section {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }

        .report-expense-card {
            border-radius: 1rem;
            border: 1px solid #e2eaf4;
            box-shadow: none;
        }

        .report-detail-minimal .report-expense-card {
            border-radius: 0.9rem;
            background: #ffffff;
            padding: 1rem;
        }

        .report-total {
            border: 1px solid #dce8f8;
            background: linear-gradient(125deg, #eef5ff 0%, #f8fbff 100%);
            border-radius: 1rem;
            padding: 1.25rem;
        }

        .report-timeline {
            border-radius: 1.25rem;
            background: #ffffff;
            padding: 1.1rem;
            min-width: 0;
            overflow: hidden;
        }

        .report-detail-minimal .report-timeline {
            border-radius: 1rem;
            padding: 1rem;
        }

        .report-side {
            display: grid;
            gap: 0.9rem;
            align-content: start;
            height: fit-content;
            min-width: 0;
            width: 100%;
        }

        .report-detail-minimal .report-side {
            gap: 0.75rem;
        }

        .report-panel {
            border-radius: 1rem;
            background: #ffffff;
            padding: 1rem;
        }

        .report-detail-minimal .report-panel {
            border-radius: 0.9rem;
            padding: 0.9rem;
        }

        .report-action-btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            border-radius: 0.9rem;
            padding: 0.72rem 0.95rem;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            border: 1px solid transparent;
            transition: all 140ms ease;
            text-decoration: none;
            line-height: 1;
        }

        .report-action-btn svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .report-action-btn-neutral {
            background: #ffffff;
            border-color: #d6e1ee;
            color: #475569;
        }

        .report-action-btn-neutral:hover {
            background: #f8fbff;
            border-color: #b9cbe1;
            color: #334155;
            transform: translateY(-1px);
            box-shadow: 0 8px 16px -12px rgba(15, 23, 42, 0.3);
        }

        .report-action-btn-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #b91c1c;
        }

        .report-action-btn-danger:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
            transform: translateY(-1px);
        }

        .report-action-btn-violet {
            background: #f5f3ff;
            border-color: #c4b5fd;
            color: #5b21b6;
        }

        .report-action-btn-violet:hover {
            background: #ede9fe;
            border-color: #a78bfa;
            color: #4c1d95;
            transform: translateY(-1px);
            box-shadow: 0 8px 16px -12px rgba(76, 29, 149, 0.35);
        }

        .report-action-btn-success {
            background: #16a34a;
            border-color: #15803d;
            color: #ffffff;
            box-shadow: 0 12px 24px -18px rgba(22, 163, 74, 0.7);
        }

        .report-action-btn-success:hover {
            background: #15803d;
            border-color: #166534;
            transform: translateY(-1px);
            box-shadow: 0 16px 28px -18px rgba(21, 128, 61, 0.8);
        }

        .report-action-btn-danger-soft {
            background: #fff5f5;
            border-color: #fca5a5;
            color: #dc2626;
            box-shadow: 0 10px 20px -16px rgba(220, 38, 38, 0.55);
        }

        .report-action-btn-danger-soft:hover {
            background: #fee2e2;
            border-color: #ef4444;
            color: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px -16px rgba(220, 38, 38, 0.65);
        }

        .report-action-btn-subtle {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border-radius: 0.9rem;
            padding: 0.64rem 0.85rem;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: #64748b;
            border: 1px solid #dbe6f3;
            background: #f8fbff;
            transition: all 140ms ease;
        }

        .report-action-btn-subtle:hover {
            color: #0f3b73;
            background: #eef5ff;
            border-color: #bfdbfe;
            transform: translateY(-1px);
        }

        .jefatura-actions > * + * {
            margin-top: 0.9rem;
        }

        .tech-validation-panel {
            border-color: #d6e7fb;
            box-shadow: 0 16px 34px -28px rgba(11, 95, 165, 0.42);
        }

        .tech-validation-header {
            display: flex;
            align-items: center;
            gap: 0.72rem;
            margin-bottom: 1.2rem;
        }

        .tech-validation-icon {
            height: 2.2rem;
            width: 2.2rem;
            border-radius: 0.72rem;
            background: linear-gradient(135deg, #0f6bb6 0%, #0b5fa5 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 24px -18px rgba(11, 95, 165, 0.9);
        }

        .tech-validation-title {
            font-size: 0.72rem;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0;
        }

        .tech-checks-shell {
            background: linear-gradient(180deg, #f8fbff 0%, #f2f7fd 100%);
            border: 1px solid #dce8f7;
            border-radius: 1rem;
            padding: 0.9rem;
            margin-bottom: 1.1rem;
        }

        .tech-checks-kicker {
            margin: 0 0 0.6rem;
            text-align: center;
            font-size: 0.62rem;
            font-weight: 900;
            color: #6b86aa;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            line-height: 1.35;
        }

        .tech-check-item {
            border-bottom: 1px solid #dbe7f5;
            padding-bottom: 0.7rem;
            margin-bottom: 0.7rem;
        }

        .tech-check-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .tech-check-provider {
            margin: 0 0 0.5rem;
            color: #1e293b;
            font-size: 0.96rem;
            font-weight: 900;
            line-height: 1.2;
        }

        .tech-check-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.45rem;
        }

        .validation-btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.55rem 0.4rem;
            border-radius: 0.82rem;
            border: 1.5px solid #d4e2f2;
            background: #ffffff;
            color: #7d8ea8;
            font-size: 0.67rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            line-height: 1;
            transition: all 140ms ease;
        }

        .validation-btn:hover:not(:disabled) {
            border-color: #9fc4ec;
            color: #436085;
            transform: translateY(-1px);
        }

        .validation-btn.is-active {
            border-color: #16a34a;
            background: #f0fdf4;
            color: #166534;
            box-shadow: 0 10px 20px -16px rgba(22, 163, 74, 0.8);
        }

        .validation-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .validation-btn-icon {
            width: 0.72rem;
            height: 0.72rem;
            flex-shrink: 0;
        }

        .validation-btn-icon.is-hidden {
            opacity: 0;
        }

        .tech-validation-actions {
            display: grid;
            gap: 0.62rem;
        }

        .tech-approve-btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.52rem;
            padding: 0.86rem 0.7rem;
            border-radius: 0.94rem;
            border: 1px solid #0b5fa5;
            background: linear-gradient(135deg, #0f6bb6 0%, #0b5fa5 100%);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: 0 14px 28px -18px rgba(11, 95, 165, 0.9);
            transition: all 150ms ease;
        }

        .tech-approve-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 30px -18px rgba(11, 95, 165, 0.95);
        }

        .tech-observe-btn {
            width: 100%;
            border: 1px solid #d9e3f1;
            border-radius: 0.9rem;
            background: #f8fbff;
            color: #6f819e;
            font-size: 0.68rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.62rem 0.6rem;
            transition: all 140ms ease;
        }

        .tech-observe-btn:hover {
            color: #c2410c;
            border-color: #f0c3a4;
            background: #fff7ed;
        }

        .report-transfer {
            border-radius: 1rem;
            background: #ffffff;
        }

        .report-detail-minimal .report-kpi {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-top: 0.65rem;
            color: #64748b;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .report-detail-minimal .report-kpi-dot {
            width: 0.4rem;
            height: 0.4rem;
            border-radius: 999px;
            background: #93c5fd;
        }

        .timeline-track {
            position: relative;
            display: grid;
            gap: 1.1rem;
        }

        .timeline-track::before {
            content: "";
            position: absolute;
            left: 1.25rem;
            top: 0.2rem;
            bottom: 0.2rem;
            width: 2px;
            background: #dbe7f5;
        }

        .timeline-item {
            position: relative;
            display: flex;
            gap: 0.9rem;
            align-items: flex-start;
        }

        .timeline-avatar {
            width: 2.5rem;
            height: 2.5rem;
            min-width: 2.5rem;
            border-radius: 999px;
            border: 2px solid #dbe7f5;
            background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 800;
            color: #2563eb;
            box-shadow: 0 6px 14px -10px rgba(37, 99, 235, 0.6);
            z-index: 1;
        }

        @media (min-width: 1280px) {
            .report-content {
                grid-template-columns: minmax(0, 1fr) minmax(280px, 330px);
                align-items: start;
            }

            .report-side {
                position: sticky;
                top: 0.9rem;
            }
        }

        @media (max-width: 1279px) {
            .report-content {
                grid-template-columns: 1fr;
            }

            .report-layout {
                gap: 0.9rem;
            }
        }

        .reject-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 120;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .reject-modal-overlay.is-open {
            display: flex;
        }

        .reject-modal-panel {
            width: min(620px, calc(100vw - 2rem));
            border-radius: 1.35rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 28px 80px -30px rgba(2, 6, 23, 0.45);
            overflow: hidden;
            position: relative;
        }

        .reject-modal-body {
            padding: 1.35rem 1.35rem 1rem;
            text-align: center;
        }

        .reject-modal-icon {
            width: 3.1rem;
            height: 3.1rem;
            margin: 0 auto 0.8rem;
            border-radius: 0.95rem;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reject-modal-title {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .reject-modal-subtitle {
            margin: 0.45rem 0 0;
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.45;
        }

        .reject-modal-textarea {
            width: 100%;
            margin-top: 1rem;
            border-radius: 0.95rem;
            border: 1px solid #dbe3ef;
            background: #f8fafc;
            color: #0f172a;
            padding: 0.95rem 1rem;
            font-size: 0.92rem;
            font-weight: 500;
            resize: vertical;
            min-height: 110px;
            outline: none;
            transition: all 140ms ease;
        }

        .reject-modal-textarea:focus {
            border-color: #ef4444;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
        }

        .reject-modal-footer {
            padding: 0 1.35rem 1.25rem;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.65rem;
        }

        .reject-modal-cancel,
        .reject-modal-accept,
        .reject-modal-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.95rem;
            padding: 0.78rem 0.95rem;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            transition: all 140ms ease;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .reject-modal-cancel {
            border-color: #dbe3ef;
            background: #ffffff;
            color: #64748b;
        }

        .reject-modal-cancel:hover {
            background: #f8fafc;
            color: #475569;
        }

        .reject-modal-accept {
            border-color: #16a34a;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: #ffffff;
            box-shadow: 0 12px 24px -16px rgba(22, 163, 74, 0.85);
        }

        .reject-modal-accept:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            border-color: #15803d;
            transform: translateY(-1px);
        }

        .reject-modal-submit {
            border-color: #dc2626;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            box-shadow: 0 12px 24px -16px rgba(220, 38, 38, 0.85);
        }

        .reject-modal-submit:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
        }

        @media (max-width: 640px) {
            .reject-modal-footer {
                grid-template-columns: 1fr;
            }
        }
    </style>


    @if(session('error'))
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="report-detail-minimal report-page app-main-card flex-1 flex flex-col h-full">
        <!-- Internal Detail Header -->
        <div class="report-head px-7 py-4 border-b border-slate-100">
            <div class="flex items-center gap-4 mb-5">
                <a href="javascript:history.back();" class="report-back group inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-sofofa-blue transition-all">
                    <div class="h-9 w-9 rounded-2xl bg-white border-2 border-slate-100 flex items-center justify-center group-hover:bg-blue-50 group-hover:border-blue-200 transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                    </div>
                    <span>Volver</span>
                </a>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-6">
                <div>
                    <span class="report-kicker">Expediente de gasto</span>
                    <h3 class="report-title font-black text-slate-800">{{ $report->title }}</h3>
                    <div class="report-kpi">
                         <div class="flex items-center gap-2.5 p-2 bg-slate-50/50 border border-slate-100 rounded-2xl">
                            <div class="h-8 w-8 rounded-xl bg-white border-2 border-slate-100 flex items-center justify-center text-[12px] font-black text-slate-600 shadow-sm">{{ strtoupper(substr($report->user->name, 0, 1)) }}</div>
                            <span class="text-[13px] font-bold text-slate-700 pr-3">{{ $report->user->name }}</span>
                         </div>
                        <span class="report-kpi-dot"></span>
                         <span class="text-xs font-semibold text-slate-400 capitalize">{{ $report->created_at->translatedFormat('d F, Y') }}</span>
                    </div>
                </div>
                
                @php
                    $statusMap = [
                        'borrador' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'bullet' => 'bg-slate-400'],
                        'enviado' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'bullet' => 'bg-blue-500'],
                        'pendiente aprobación jefatura' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'bullet' => 'bg-amber-500'],
                        'observada por jefatura' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'bullet' => 'bg-red-500'],
                        'subsanada por solicitante (jefatura)' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'bullet' => 'bg-orange-500'],
                        'aprobada por jefatura' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'bullet' => 'bg-indigo-500'],
                        'rechazada por jefatura' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'bullet' => 'bg-red-600'],
                        'en revisión' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'bullet' => 'bg-indigo-500'],
                        'observada por gestor' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'bullet' => 'bg-amber-600'],
                        'subsanada por solicitante (gestor)' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'bullet' => 'bg-orange-600'],
                        'aprobada por gestor' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'bullet' => 'bg-green-500'],
                        'rechazada por gestor' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'bullet' => 'bg-red-600'],
                        'pendiente pago' => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'bullet' => 'bg-cyan-500'],
                        'reembolsada' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'bullet' => 'bg-teal-500'],
                        'cerrada' => ['bg' => 'bg-slate-200', 'text' => 'text-slate-700', 'bullet' => 'bg-slate-600'],
                    ];
                    $statusKey = mb_strtolower(trim($report->status));
                    $cfg = $statusMap[$statusKey] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'bullet' => 'bg-slate-400'];
                @endphp
                <div class="report-status {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                    <span class="h-2.5 w-2.5 rounded-full {{ $cfg['bullet'] }}"></span>
                    <span>{{ $report->status }}</span>
                </div>
            </div>
        </div>

        <div class="report-content flex-1 overflow-y-auto">
            <div class="report-layout pb-12">
                <!-- Main Content (Left) -->
                <div class="report-main flex-1 space-y-10">

                <div class="report-section">
                    <h4 class="text-[12px] font-bold text-slate-500 uppercase tracking-[0.08em] mb-4">Listado de Comprobantes</h4>
                    <div class="space-y-6">
                        @foreach($report->expenses as $expense)
                        <div class="report-expense-card bg-white p-6 space-y-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="flex items-start gap-4">
                                    <div class="h-12 w-12 bg-white rounded-xl border border-slate-200 flex items-center justify-center text-sofofa-blue">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7"/></svg>
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="px-2 py-0.5 bg-slate-900 text-white text-[9px] font-black rounded uppercase tracking-tighter">{{ $expense->ceco->code ?? 'SIN CECO' }}</span>
                                            <span class="px-2 py-0.5 bg-blue-100 text-sofofa-blue text-[9px] font-black rounded uppercase tracking-tighter">{{ $expense->category->name ?? 'SIN CATEGORÍA' }}</span>
                                        </div>
                                        <h5 class="text-lg font-bold text-slate-800">{{ $expense->provider_name }}</h5>
                                        <p class="text-xs text-slate-500 font-medium">RUT: {{ $expense->provider_rut }} | {{ $expense->description }}</p>
                                    </div>
                                </div>
                                <div class="text-right md:min-w-[220px] md:pl-6 md:border-l md:border-slate-200">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">{{ $expense->expense_date ? date('d/m/Y', strtotime($expense->expense_date)) : 'N/A' }}</p>
                                    <p class="text-3xl font-black text-slate-900">${{ number_format($expense->amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-3 pt-5 border-t border-slate-200">
                                @if($expense->attachment_path)
                                <!-- Botón original restaurado: Ver Boleta -->
                                <a href="{{ asset('rendicion/storage/' . $expense->attachment_path) }}" class="btn-sofofa" target="_blank" onclick="event.stopPropagation();">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span>Descargar Boleta</span>
                                </a>

                                @endif
                                
                                @if($expense->comanda_path)
                                <a href="{{ Storage::url($expense->comanda_path) }}" target="_blank" class="btn-comanda" onclick="event.stopPropagation()">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7"/></svg>
                                    <span>Ver Comanda</span>
                                </a>
                                @endif
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-auto">Doc: {{ $expense->document_type }}</span>
                                                            @if($report->isEditableByRequester() && auth()->id() === $report->user_id)
                                                            <form method="POST" action="{{ route('rendicion.expenses.destroy', $expense) }}" onsubmit="return confirm('¿Eliminar este gasto?')" onclick="event.stopPropagation()">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-red-100 bg-red-50 text-red-500 hover:bg-red-100 text-[9px] font-black uppercase tracking-widest transition-all">
                                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                            @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Total Section -->
                    <div class="report-total mt-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Monto Total Rendición</span>
                            <p class="text-sm text-slate-600 font-medium italic">Calculado automáticamente sobre {{ $report->expenses->count() }} ítems.</p>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-black text-sofofa-blue">${{ number_format($report->total_amount, 0, ',', '.') }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2">Pesos Chilenos (CLP)</span>
                        </div>
                    </div>

                    @if($report->isEditableByRequester() && auth()->id() === $report->user_id)
                    <div class="mt-8 flex justify-center">
                        <a href="{{ route('rendicion.expenses.add', $report) }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white border-2 border-sofofa-blue text-sofofa-blue font-black rounded-2xl hover:bg-blue-50 transition-all text-xs uppercase tracking-widest shadow-lg shadow-blue-500/10">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                            <span>Agregar otro Gasto a este Informe</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Activity / History Section -->
            <div class="report-timeline bg-white rounded-3xl border border-slate-200 p-8 md:p-10 shadow-sm relative z-10 lg:shrink-0 lg:self-start lg:sticky lg:top-6">
                <h4 class="text-[1.85rem] font-bold text-slate-800 mb-6 flex items-center leading-tight">
                    <svg class="h-6 w-6 mr-3 text-sofofa-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Seguimiento de la Solicitud
                </h4>

                <div class="timeline-track">

                    @forelse($report->comments as $comment)
                    <div class="timeline-item">
                        <!-- Bullet -->
                        <div class="flex-shrink-0">
                            <div class="timeline-avatar">
                                <span>{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 pb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-800">{{ $comment->user->name }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="p-5 rounded-2xl bg-slate-50 text-sm text-slate-600 leading-relaxed border border-slate-100">
                                {{ $comment->comment }}
                                @if($comment->from_status != $comment->to_status)
                                <div class="mt-4 pt-4 border-t border-slate-100/50 flex flex-wrap items-center gap-2">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Estado:</span>
                                    <span class="text-[10px] font-bold text-slate-500">{{ $comment->from_status }}</span>
                                    <svg class="h-3 w-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"/></svg>
                                    <span class="text-[10px] font-black text-sofofa-blue">{{ $comment->to_status }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                        <p class="text-sm font-bold text-slate-400">Sin historial de comentarios aún.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar / Actions (Right) -->
        <div class="report-side w-full space-y-6">
            @php $isAdmin = auth()->user()->hasAnyRole(['Admin', 'Superadmin', 'Super Admin']); @endphp
            <!-- Panel Solicitante: Editar / Anular -->
            @if(auth()->id() === $report->user_id || auth()->user()->hasAnyRole(['Admin', 'Superadmin', 'Super Admin']))
            @if($report->isEditableByRequester())
            <div class="report-panel bg-white rounded-3xl border-2 border-slate-100 p-6 shadow-sm space-y-3">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest text-center mb-4">Acciones del Solicitante</p>
                <a href="{{ route('rendicion.expenses.createStep1', $report) }}" class="report-action-btn report-action-btn-primary" style="background: linear-gradient(135deg, #0f6bb6 0%, #1488db 100%); color: white; border: none; box-shadow: 0 14px 24px -16px rgba(20, 136, 219, 0.75);">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Continuar Edición
                </a>
                <form method="POST" action="{{ route('rendicion.reports.cancel', $report) }}" onsubmit="return confirm('¿Anular este informe? Esta acción no se puede deshacer.')">
                    @csrf
                    <button type="submit" class="report-action-btn report-action-btn-neutral">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Anular Informe
                    </button>
                </form>
                <form method="POST" action="{{ route('rendicion.reports.destroy', $report) }}" onsubmit="return confirm('¿Eliminar este informe permanentemente?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="report-action-btn report-action-btn-danger">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Eliminar Informe
                    </button>
                </form>
            </div>
            @elseif(!in_array($report->status, [\App\Models\Rendicion\Report::STATUS_REIMBURSED, \App\Models\Rendicion\Report::STATUS_CLOSED, \App\Models\Rendicion\Report::STATUS_CANCELLED]))
            <div class="report-panel bg-white rounded-3xl border-2 border-slate-100 p-6 shadow-sm">
                <form method="POST" action="{{ route('rendicion.reports.cancel', $report) }}" onsubmit="return confirm('¿Anular este informe? Se notificará a los involucrados.')">
                    @csrf
                    <button type="submit" class="report-action-btn report-action-btn-neutral">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 715.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Anular Informe
                    </button>
                </form>
            </div>
            @endif
            @endif

            <!-- Gestor: Habilitar edición manual -->
            @if(auth()->user()->hasAnyRole(['Aprobador', 'Admin', 'Superadmin', 'Super Admin']) && in_array($report->status, ['Enviado', 'Pendiente aprobación jefatura', 'Aprobada por jefatura', 'En revisión']))
            <div class="report-panel bg-white rounded-3xl border-2 border-violet-100 p-6 shadow-sm">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest text-center mb-4">Gestión</p>
                <form method="POST" action="{{ route('rendicion.reports.enableEdit', $report) }}" onsubmit="return confirm('¿Habilitar edición para el solicitante?')">
                    @csrf
                    <button type="submit" class="report-action-btn report-action-btn-violet">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Habilitar Edición al Solicitante
                    </button>
                </form>
            </div>
            @endif

               <!-- Aprobación del Aprobador (Fase 1) -->
            @if((auth()->user()->can('reports.approve_reviewer') || auth()->user()->hasAnyRole(['Aprobador', 'Admin', 'Superadmin', 'Super Admin'])) && in_array($report->status, ['En revisión', 'Subsanada por solicitante (Gestor)']))
            <div class="report-panel bg-white rounded-3xl border-2 border-blue-100 p-8 shadow-xl shadow-blue-500/5 transition-all">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-[0.1em]">Aprobación del Aprobador</h4>
                </div>
                
                <div class="jefatura-actions">
                    <form action="{{ route('rendicion.reports.approve', $report) }}" method="POST">
                        @csrf
                        <button type="submit" class="report-action-btn report-action-btn-success">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            <span>ACEPTAR SOLICITUD</span>
                        </button>
                    </form>
                    
                    <button type="button" onclick="openRejectModal()" class="report-action-btn report-action-btn-danger-soft">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        <span>RECHAZAR</span>
                    </button>
                    
                    <button type="button" onclick="document.getElementById('observe-modal').classList.remove('hidden')" class="report-action-btn-subtle">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <span>SOLICITAR CORRECCIÓN</span>
                    </button>
                </div>
            </div>
            @endif

            <!-- Aprobación del Gestor (Fase 2) -->
            @if((auth()->user()->can('reports.approve_manager') || auth()->user()->hasAnyRole(['Gestor', 'Admin', 'Superadmin', 'Super Admin'])) && in_array($report->status, ['Pendiente aprobación jefatura', 'Subsanada por solicitante (jefatura)']))
            <div class="report-panel bg-white rounded-3xl border-2 border-amber-100 p-8 shadow-xl shadow-amber-500/5 transition-all">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-8 w-8 bg-amber-500 rounded-lg flex items-center justify-center text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-[0.1em]">Aprobación del Gestor</h4>
                </div>

                <div class="jefatura-actions">
                    <form action="{{ route('rendicion.reports.approve', $report) }}" method="POST">
                        @csrf
                        <button type="submit" class="report-action-btn report-action-btn-success">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            <span>ACEPTAR SOLICITUD</span>
                        </button>
                    </form>

                    <button type="button" onclick="openRejectModal()" class="report-action-btn report-action-btn-danger-soft">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        <span>RECHAZAR</span>
                    </button>

                    <button type="button" onclick="document.getElementById('observe-modal').classList.remove('hidden')" class="report-action-btn-subtle">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <span>SOLICITAR CORRECCIÓN</span>
                    </button>
                </div>
            </div>
            @endif

            <!-- Reembolso (Fase 3 - Aprobador) -->
            @if(auth()->user()->hasAnyRole(['Gestor', 'Admin', 'Superadmin', 'Super Admin']) && in_array($report->status, ['Aprobada por jefatura', 'Pendiente pago']))
            <div class="report-panel bg-white rounded-3xl border-2 border-teal-100 p-8 shadow-xl shadow-teal-500/5 transition-all">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-8 w-8 bg-teal-500 rounded-lg flex items-center justify-center text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0-16c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/></svg>
                    </div>
                    <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-[0.1em]">Reembolso</h4>
                </div>
                
                <form action="{{ route('rendicion.reports.pay', $report) }}" method="POST">
                    @csrf
                    <button type="submit" style="width:100%;background:#0d9488;color:#fff;font-weight:900;padding:1rem;border-radius:1rem;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;font-size:0.75rem;letter-spacing:0.1em;box-shadow:0 8px 20px -8px rgba(13,148,136,0.5);transition:background 0.15s;" onmouseover="this.style.background='#0f766e'" onmouseout="this.style.background='#0d9488'">
                        <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span>MARCAR DEVOLUCIÓN</span>
                    </button>
                </form>
            </div>
            @endif

            <!-- Datos de Pago -->
            <div class="report-transfer bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Información de Transferencia</h4>
                <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:1.5rem;text-align:center;">
                    <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#1a6fbd 0%,#0f4f8a 100%);display:flex;align-items:center;justify-content:center;margin-bottom:0.85rem;box-shadow:0 8px 24px -8px rgba(15,79,138,0.45);flex-shrink:0;">
                        <span style="font-size:1.75rem;font-weight:900;color:#fff;line-height:1;letter-spacing:-0.02em;">{{ strtoupper(substr($report->user->name, 0, 1)) }}</span>
                    </div>
                    <h5 style="font-size:1rem;font-weight:900;color:#1e293b;letter-spacing:-0.01em;line-height:1.2;margin:0;">{{ $report->user->name }}</h5>
                </div>
                <div class="space-y-4 border-t border-slate-100 pt-6">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">RUT</span>
                        <span class="text-sm font-black text-slate-700">12.345.678-k</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Banco</span>
                        <span class="text-sm font-black text-slate-700 text-right">Banco de Chile</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Cuenta</span>
                        <span class="text-sm font-black text-sofofa-blue tracking-widest">987 654 321 0</span>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Simple Layout) -->
    <!-- Modal de Rechazo -->
    <div id="reject-modal" class="reject-modal-overlay {{ $errors->has('comment') && old('_modal') === 'reject' ? 'is-open' : '' }}" onclick="if(event.target === this){ closeRejectModal(); }">
        <div class="reject-modal-panel" role="dialog" aria-modal="true" aria-labelledby="reject-modal-title">
            <button type="button" onclick="closeRejectModal()" class="absolute top-3 right-3 text-slate-300 hover:text-slate-600 transition-colors p-2 hover:bg-slate-50 rounded-xl">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <form action="{{ route('rendicion.reports.reject', $report) }}" method="POST">
                @csrf
                <input type="hidden" name="_modal" value="reject">
                <div class="reject-modal-body">
                    <div class="reject-modal-icon">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>

                    <h3 id="reject-modal-title" class="reject-modal-title">Confirmar Rechazo</h3>
                    <p class="reject-modal-subtitle">Esta acción notificará al solicitante y detendrá el proceso de rendición.</p>

                    <textarea name="comment" required class="reject-modal-textarea @error('comment') border-red-500 @enderror" placeholder="Escribe el motivo detallado aquí...">{{ old('comment') }}</textarea>

                    @error('comment')
                        <p class="mt-3 text-xs text-red-600 font-bold bg-red-50 py-2 px-3 rounded-lg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="reject-modal-footer">
                    <button type="button" onclick="closeRejectModal()" class="reject-modal-cancel">Volver</button>
                    <button type="submit" class="reject-modal-submit">Rechazar Rendición</button>
                </div>
            </form>
        </div>
    </div>
            <!-- Modal de Observación (Solicitar Corrección) -->
    <div id="observe-modal" class="{{ $errors->has('comment') && old('_modal') === 'observe' ? '' : 'hidden' }} fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full relative border border-slate-100 overflow-hidden transform transition-all">
            <!-- Header with Icon -->
            <div class="bg-slate-50/80 px-8 pt-10 pb-6 text-center border-b border-slate-100">
                <div class="h-16 w-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mx-auto mb-6 shadow-sm border border-blue-200/50">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Solicitar Corrección</h3>
                <p class="text-sm text-slate-500 font-medium">Indica los cambios que el solicitante debe realizar.</p>
                
                <button onclick="document.getElementById('observe-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-2 hover:bg-white rounded-xl transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('rendicion.reports.observe', $report) }}" method="POST">
                @csrf
                <input type="hidden" name="_modal" value="observe">
                
                <div class="p-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Comentario para el Solicitante</label>
                    <textarea name="comment" required rows="4" 
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl p-4 text-sm font-medium focus:bg-white focus:ring-0 focus:border-blue-500 transition-all outline-none resize-none @error('comment') border-red-500 @enderror" 
                        placeholder="Ej: La boleta es ilegible o falta el RUT del proveedor...">{{ old('comment') }}</textarea>
                    
                    @error('comment') 
                        <div class="mt-3 p-3 bg-red-50 rounded-xl border border-red-100">
                            <p class="text-xs text-red-600 font-bold flex items-center gap-2">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                        </div>
                    @enderror
                </div>
                
                <div class="px-8 pb-8 flex flex-col gap-3">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:scale-[1.01] active:scale-95 transition-all text-xs uppercase tracking-widest">
                        Enviar a Corregir
                    </button>
                    <button type="button" onclick="document.getElementById('observe-modal').classList.add('hidden')" class="w-full text-slate-500 hover:text-slate-700 font-bold py-2 text-[10px] uppercase tracking-widest transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal() {
            const modal = document.getElementById('reject-modal');
            if (!modal) return;
            modal.classList.add('is-open');
        }

        function closeRejectModal() {
            const modal = document.getElementById('reject-modal');
            if (!modal) return;
            modal.classList.remove('is-open');
        }

        function updateValidationButtonState(button, isActive) {
            const icon = button.querySelector('svg');
            button.dataset.active = isActive ? 'true' : 'false';
            button.classList.toggle('is-active', isActive);
            if (icon) {
                icon.classList.toggle('is-hidden', !isActive);
            }
        }

        async function toggleValidation(button, expenseId, field) {
            const currentValue = button.dataset.active === 'true';
            const nextValue = !currentValue;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                alert('No se pudo validar: falta token CSRF.');
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(`/gastos/${expenseId}/validate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ field: field, value: nextValue }),
                });

                if (!response.ok) {
                    throw new Error('No se pudo guardar la validación.');
                }

                updateValidationButtonState(button, nextValue);
            } catch (error) {
                alert('No se pudo actualizar la validación. Intenta nuevamente.');
            } finally {
                button.disabled = false;
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeRejectModal();
            }
        });
    </script>
</x-rendicion.layouts.app>
