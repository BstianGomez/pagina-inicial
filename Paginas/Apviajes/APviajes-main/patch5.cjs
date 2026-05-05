const fs = require('fs');

let layout = fs.readFileSync('resources/views/components/layouts/app.blade.php', 'utf8');

const oldHeader = /@if\s*\(\s*trim\(\(string\)\s*\$page_title\)\s*\)[\s\S]*?<div class="content-header">[\s\S]*?<h1>\{\{\s*\$page_title\s*\}\}<\/h1>[\s\S]*?@if\s*\(\s*trim\(\(string\)\s*\$page_subtitle\)\s*\)[\s\S]*?<p>\{\{\s*\$page_subtitle\s*\}\}<\/p>[\s\S]*?@endif[\s\S]*?<\/div>[\s\S]*?@endif/;

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

if(oldHeader.test(layout)) {
   layout = layout.replace(oldHeader, newHeader);
   fs.writeFileSync('resources/views/components/layouts/app.blade.php', layout);
   console.log("Success");
} else {
   console.log("Regex did not match");
}
