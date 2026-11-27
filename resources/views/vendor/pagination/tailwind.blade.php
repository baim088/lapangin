@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3">
        {{-- Summary Text --}}
        <div class="text-xs text-slate-500 font-medium">
            Menampilkan
            <span class="font-bold text-slate-800">{{ $paginator->firstItem() ?? 0 }}</span>
            –
            <span class="font-bold text-slate-800">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-bold text-slate-800">{{ $paginator->total() }}</span>
            data
        </div>

        {{-- Page Controls --}}
        <div class="flex items-center gap-1.5 flex-wrap justify-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center h-9 px-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 text-xs font-semibold cursor-not-allowed select-none">
                    &laquo; Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center h-9 px-3 rounded-xl border border-slate-200 bg-white hover:bg-[#0f2444] hover:text-white hover:border-[#0f2444] text-slate-700 text-xs font-semibold transition-colors shadow-2xs">
                    &laquo; Prev
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-400 select-none">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2.5 rounded-xl bg-[#0f2444] text-[#f8b710] text-xs font-bold shadow-xs border border-[#0f2444]">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[2.25rem] h-9 px-2.5 rounded-xl border border-slate-200 bg-white hover:bg-[#f8b710] hover:text-slate-950 hover:border-[#f8b710] text-slate-700 text-xs font-semibold transition-colors shadow-2xs">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center h-9 px-3 rounded-xl border border-slate-200 bg-white hover:bg-[#0f2444] hover:text-white hover:border-[#0f2444] text-slate-700 text-xs font-semibold transition-colors shadow-2xs">
                    Next &raquo;
                </a>
            @else
                <span class="inline-flex items-center justify-center h-9 px-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 text-xs font-semibold cursor-not-allowed select-none">
                    Next &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif
