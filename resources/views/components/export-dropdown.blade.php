@props([
    'csvUrl',
    'pdfUrl',
    'label' => 'Ekspor',
])

<div x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false" class="relative inline-block text-left">
    <button
        type="button"
        @click="open = !open"
        :aria-expanded="open.toString()"
        class="inline-flex items-center gap-1.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs px-3.5 py-2 rounded-xl transition-all shadow-2xs"
    >
        <span class="material-symbols-outlined text-[16px] text-[#0f2444]">download</span>
        <span>{{ $label }}</span>
        <span class="material-symbols-outlined text-[16px] text-slate-400 transition-transform duration-150" :class="open ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
        class="absolute right-0 mt-1.5 w-44 rounded-xl bg-white shadow-lg border border-slate-200 py-1.5 z-40"
    >
        <a
            href="{{ $csvUrl }}"
            @click="open = false"
            class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#0f2444] transition-colors"
        >
            <span class="material-symbols-outlined text-[16px] text-emerald-600">table_view</span>
            <span>Ekspor CSV (.csv)</span>
        </a>
        <a
            href="{{ $pdfUrl }}"
            @click="open = false"
            class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#0f2444] transition-colors"
        >
            <span class="material-symbols-outlined text-[16px] text-rose-600">picture_as_pdf</span>
            <span>Ekspor PDF (.pdf)</span>
        </a>
    </div>
</div>
