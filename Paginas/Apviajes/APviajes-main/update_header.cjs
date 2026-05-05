const fs = require('fs');

let layout = fs.readFileSync('resources/views/components/layouts/app.blade.php', 'utf8');

const additionalStyles = `
        /* New Hero Header for Pages */
        .page-hero {
            background: linear-gradient(135deg, #0f6bb6 0%, #1b7dc8 100%);
            border-radius: 18px;
            color: #fff;
            padding: 20px 24px;
            box-shadow: 0 12px 24px rgba(15, 107, 182, 0.18);
            position: relative;
            overflow: hidden;
            margin: 20px 22px 14px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-hero::before {
            content: '';
            position: absolute;
            right: -60px;
            top: -80px;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            pointer-events: none;
        }
        .page-hero-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 18px;
        }
        .page-hero-copy {
            min-width: 0;
            flex: 1;
        }
        .page-hero h1 {
            margin: 0;
            font-size: clamp(19px, 2.2vw, 26px);
            line-height: 1.08;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #fff;
        }
        .page-hero p {
            margin: 6px 0 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.92);
            max-width: 62ch;
        }
        .page-hero-btn {
            position: relative;
            z-index: 2;
        }
        @media (max-width: 768px) {
            .page-hero {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
`;

if (!layout.includes('page-hero')) {
    layout = layout.replace('</style>', additionalStyles + '\n    </style>');
}

const oldHeader = `@if (trim((string) $page_title))
                        <div class="content-header flex items-center justify-between">
                            <div>
                                <h1>{{ $page_title }}</h1>
                                @if (trim((string) $page_subtitle))
                                    <p>{{ $page_subtitle }}</p>
                                @endif
                            </div>
                            @if(request()->route() && !request()->routeIs('dashboard'))
                                <a href="javascript:history.back();" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-[13px] font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-sofofa-blue hover:border-slate-300 transition-all shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                    Volver
                                </a>
                            @endif
                        </div>
                    @endif`;

const newHeader = `@if (trim((string) $page_title))
                        <div class="page-hero">
                            <div class="page-hero-inner">
                                <div class="page-hero-copy">
                                    <h1>{{ $page_title }}</h1>
                                    @if (trim((string) $page_subtitle))
                                        <p>{{ $page_subtitle }}</p>
                                    @endif
                                </div>
                            </div>
                            @if(request()->route() && !request()->routeIs('dashboard'))
                                <div class="page-hero-btn">
                                    <a href="javascript:history.back();" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-[13px] font-bold text-sofofa-blue bg-white border border-white/20 rounded-xl hover:bg-slate-50 transition-all shadow-sm" style="color: #0f6bb6;">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                        Volver
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif`;

layout = layout.replace(oldHeader, newHeader);

// also remove content-header class usages if empty
layout = layout.replace(/\.content-header\s*\{[^}]+\}/g, '');
layout = layout.replace(/\.content-header h1\s*\{[^}]+\}/g, '');
layout = layout.replace(/\.content-header p\s*\{[^}]+\}/g, '');

fs.writeFileSync('resources/views/components/layouts/app.blade.php', layout);
