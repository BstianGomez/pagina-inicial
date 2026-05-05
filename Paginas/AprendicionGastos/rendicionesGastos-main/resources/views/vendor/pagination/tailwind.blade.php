@if ($paginator->hasPages())
    <nav id="pagination-nav" role="navigation" aria-label="Paginación" class="flex items-center justify-end">
        <div class="inline-flex items-center gap-2 rounded-[1.4rem] border border-slate-200 bg-white px-2 py-2 shadow-[0_10px_24px_-18px_rgba(15,23,42,0.45)]">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="Anterior" class="inline-flex h-11 min-w-11 items-center justify-center rounded-xl text-slate-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}#pagination-nav" rel="prev" aria-label="Anterior" class="inline-flex h-11 min-w-11 items-center justify-center rounded-xl text-slate-500 transition-all hover:-translate-y-0.5 hover:bg-slate-100 hover:text-slate-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true" class="inline-flex h-11 min-w-11 items-center justify-center rounded-xl px-2 text-sm font-bold text-slate-300">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-11 min-w-11 items-center justify-center rounded-xl border border-blue-200 bg-blue-500/15 px-3 text-sm font-extrabold text-blue-700 shadow-[0_0_0_1px_rgba(96,165,250,0.25),0_10px_20px_-14px_rgba(59,130,246,0.45)] backdrop-blur-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}#pagination-nav" class="inline-flex h-11 min-w-11 items-center justify-center rounded-xl px-3 text-sm font-bold text-slate-700 transition-all hover:-translate-y-0.5 hover:bg-slate-100 hover:text-slate-900">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}#pagination-nav" rel="next" aria-label="Siguiente" class="inline-flex h-11 min-w-11 items-center justify-center rounded-xl text-slate-500 transition-all hover:-translate-y-0.5 hover:bg-slate-100 hover:text-slate-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="Siguiente" class="inline-flex h-11 min-w-11 items-center justify-center rounded-xl text-slate-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
