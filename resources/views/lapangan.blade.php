<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Katalog Lengkap Lapangan Olahraga di Lapangin. Futsal, Badminton, Mini Soccer, dan Padel Court standar turnamen internasional.">
    <title>Koleksi Lapangan - {{ config('app.name', 'Lapangin') }}</title>

    <!-- Google Fonts: Teko & Nunito Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ mobileMenuOpen: false }"
    class="bg-canvas-bg font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="lapangan" />

    @php
        $currentCategory = $category ?? 'all';
        $currentRegion = $region ?? 'all';
        $currentSearch = $search ?? '';

        $buildFilterUrl = function (?string $newCategory = null, ?string $newRegion = null) use ($currentCategory, $currentRegion, $currentSearch) {
            $params = [];
            $cat = $newCategory ?? $currentCategory;
            $reg = $newRegion ?? $currentRegion;

            if ($cat !== 'all') {
                $params['category'] = $cat;
            }
            if ($reg !== 'all') {
                $params['region'] = $reg;
            }
            if ($currentSearch !== '') {
                $params['search'] = $currentSearch;
            }

            return route('lapangan', $params);
        };
    @endphp

    <main class="w-full pt-20 bg-canvas-bg min-h-[calc(100vh-80px)]">
        
        <!-- HEADER BANNER -->
        <section class="w-full bg-[#0f2444] text-white py-14 px-4 sm:px-8 border-b border-white/10 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-[#f8b710]/15 blur-3xl pointer-events-none"></div>
            <div class="w-full max-w-7xl mx-auto text-center relative z-10">
                <span class="text-xs font-bold text-[#f8b710] uppercase tracking-widest block mb-1">KATALOG ARENA RESMI</span>
                <h1 class="font-teko text-5xl sm:text-6xl font-bold uppercase tracking-tight text-white mb-2 leading-none">
                    KOLEKSI ARENA OLAHRAGA
                </h1>
                <div class="flex items-center justify-center gap-1.5 mt-2 mb-4">
                    <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                    <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                </div>
                <p class="font-body text-sm text-slate-200/90 max-w-2xl mx-auto leading-relaxed">
                    Pilih venue dan lapangan favorit Anda di seluruh wilayah DKI Jakarta. Klik tombol <strong>Booking</strong> untuk melihat spesifikasi lengkap dan jadwal tersedia.
                </p>

                <!-- Search Bar -->
                <form action="{{ route('lapangan') }}" method="GET" class="max-w-lg mx-auto mt-6">
                    @if ($currentCategory !== 'all')
                        <input type="hidden" name="category" value="{{ $currentCategory }}">
                    @endif
                    @if ($currentRegion !== 'all')
                        <input type="hidden" name="region" value="{{ $currentRegion }}">
                    @endif
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-4 text-slate-400 text-[20px]">search</span>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $currentSearch }}"
                            placeholder="Cari nama lapangan, venue, atau wilayah (mis. Kemang, Padel)..."
                            class="w-full pl-11 pr-24 py-3 rounded-full bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710] shadow-md"
                        >
                        <button type="submit" class="absolute right-1.5 bg-[#f8b710] hover:bg-[#f37920] text-slate-950 font-bold text-xs px-4 py-2 rounded-full transition-colors cursor-pointer">
                            Cari
                        </button>
                    </div>
                </form>

                <!-- Category Filter Tabs -->
                <div class="flex flex-wrap items-center justify-center gap-2 mt-6">
                    <a 
                        href="{{ $buildFilterUrl('all', null) }}"
                        class="px-5 py-2 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap {{ $currentCategory === 'all' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}"
                    >
                        Semua Olahraga
                    </a>
                    <a 
                        href="{{ $buildFilterUrl('futsal', null) }}"
                        class="px-5 py-2 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap {{ $currentCategory === 'futsal' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}"
                    >
                        Futsal
                    </a>
                    <a 
                        href="{{ $buildFilterUrl('badminton', null) }}"
                        class="px-5 py-2 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap {{ $currentCategory === 'badminton' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}"
                    >
                        Badminton
                    </a>
                    <a 
                        href="{{ $buildFilterUrl('minisoccer', null) }}"
                        class="px-5 py-2 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap {{ $currentCategory === 'minisoccer' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}"
                    >
                        Mini Soccer
                    </a>
                    <a 
                        href="{{ $buildFilterUrl('padel', null) }}"
                        class="px-5 py-2 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap {{ $currentCategory === 'padel' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}"
                    >
                        Padel Court
                    </a>
                </div>

                <!-- Region Filter Tabs -->
                <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 mt-4 pt-4 border-t border-white/10 text-xs">
                    <span class="text-white/70 mr-1 hidden sm:inline font-semibold">Wilayah:</span>
                    @foreach ([
                        'all' => 'Semua Wilayah',
                        'Jakarta Selatan' => 'Jakarta Selatan',
                        'Jakarta Timur' => 'Jakarta Timur',
                        'Jakarta Pusat' => 'Jakarta Pusat',
                        'Jakarta Barat' => 'Jakarta Barat',
                        'Jakarta Utara' => 'Jakarta Utara',
                    ] as $regKey => $regLabel)
                        <a 
                            href="{{ $buildFilterUrl(null, $regKey) }}"
                            class="px-3.5 py-1.5 rounded-full font-semibold transition-all {{ $currentRegion === $regKey ? ($regKey === 'all' ? 'bg-white text-slate-950 font-bold shadow-xs' : 'bg-[#f8b710] text-slate-950 font-bold shadow-xs') : 'bg-white/10 text-white hover:bg-white/20' }}"
                        >
                            {{ $regLabel }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- COURTS COMPACT GRID (5 Cards Per Row on XL) -->
        <section class="w-full max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Result Summary & Active Filters -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <p class="text-xs sm:text-sm text-slate-600 font-medium">
                    Menampilkan <span class="font-bold text-slate-900">{{ $courts->firstItem() ?? 0 }}–{{ $courts->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-900">{{ $courts->total() }}</span> lapangan tersedia
                </p>
                @if ($currentCategory !== 'all' || $currentRegion !== 'all' || $currentSearch !== '')
                    <a href="{{ route('lapangan') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#f37920] hover:underline">
                        <span class="material-symbols-outlined text-[15px]">restart_alt</span>
                        Reset Semua Filter
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @forelse ($courts as $court)
                    @php
                        $sportCategoryValue = $court->sport_category instanceof \App\Enums\SportCategory ? $court->sport_category->value : (string) $court->sport_category;
                        $imagePath = match($sportCategoryValue) {
                            'futsal' => 'images/courts/futsal.jpg',
                            'badminton' => 'images/courts/badminton.jpg',
                            'mini_soccer' => 'images/courts/minisoccer.jpg',
                            'padel' => 'images/courts/padel.jpg',
                            default => 'images/courts/futsal.jpg',
                        };
                        $sportLabel = match($sportCategoryValue) {
                            'futsal' => 'Futsal',
                            'badminton' => 'Badminton',
                            'mini_soccer' => 'Mini Soccer',
                            'padel' => 'Padel',
                            default => ucfirst($sportCategoryValue),
                        };
                        $courtAddress = $court->venue->address ?? 'DKI Jakarta';
                    @endphp
                    <div class="group bg-surface-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-lg transition-all flex flex-col justify-between">
                        <div>
                            <!-- Compact Thumbnail -->
                            <div class="relative aspect-[4/3] bg-slate-900 overflow-hidden">
                                <x-lazy-image
                                    :src="asset($imagePath)"
                                    :alt="$court->name"
                                    :eager="$loop->index < 3"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/15 pointer-events-none"></div>
                                <span class="absolute top-2.5 left-2.5 bg-[#f8b710] text-slate-950 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow">
                                    {{ $sportLabel }}
                                </span>
                                <span class="absolute top-2.5 right-2.5 inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full backdrop-blur-md {{ $court->status === \App\Enums\CourtStatus::Active ? 'bg-emerald-500/90 text-white' : 'bg-amber-500/90 text-white' }}">
                                    {{ $court->status === \App\Enums\CourtStatus::Active ? 'Tersedia' : 'Maintenance' }}
                                </span>
                            </div>

                            <!-- Compact Info Body -->
                            <div class="p-3.5 flex flex-col gap-1.5">
                                <div class="flex items-center gap-1 text-[11px] font-bold text-blue-700 truncate" title="{{ $court->venue->name ?? 'LapangIn Arena' }}">
                                    <span class="material-symbols-outlined text-[13px] shrink-0">stadium</span>
                                    <span class="truncate">{{ $court->venue->name ?? 'LapangIn Arena' }}</span>
                                </div>

                                <h2 class="font-teko text-xl font-bold uppercase tracking-wide text-slate-900 leading-tight line-clamp-2 min-h-[2.6rem] group-hover:text-[#f37920] transition-colors" title="{{ $court->name }}">
                                    {{ $court->name }}
                                </h2>

                                <div class="flex items-center gap-1 text-[11px] text-slate-500 truncate" title="{{ $courtAddress }}">
                                    <span class="material-symbols-outlined text-[#f37920] text-[14px] shrink-0">location_on</span>
                                    <span class="truncate">{{ $courtAddress }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Compact Footer: Price & Booking Action -->
                        <div class="p-3.5 pt-2 border-t border-slate-100 flex flex-col gap-2.5 bg-slate-50/60">
                            <div class="flex items-baseline justify-between">
                                <span class="text-[10px] text-slate-500 font-semibold uppercase">Mulai dari</span>
                                <span class="font-teko text-xl font-bold text-slate-900 leading-none">
                                    Rp {{ number_format($court->price_regular, 0, ',', '.') }}<span class="font-body text-[10px] font-normal text-slate-500">/jam</span>
                                </span>
                            </div>

                            <a 
                                href="{{ route('lapangan.detail', $court->slug) }}" 
                                class="w-full inline-flex items-center justify-center gap-1.5 bg-[#0f2444] hover:bg-[#f37920] text-white font-bold text-[11px] uppercase tracking-wider py-2.5 px-3 rounded-xl shadow-xs transition-colors"
                            >
                                <span class="material-symbols-outlined text-[15px]">calendar_month</span>
                                Booking
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-12 text-center text-slate-500 bg-surface-white rounded-3xl border border-slate-200">
                        <span class="material-symbols-outlined text-[40px] text-slate-300 mb-2">search_off</span>
                        <p class="font-semibold text-slate-700">Tidak ada lapangan yang sesuai dengan filter Anda.</p>
                        <a href="{{ route('lapangan') }}" class="inline-block mt-3 text-xs font-bold text-[#f37920] hover:underline">Tampilkan semua lapangan</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($courts->hasPages())
                <div class="mt-10">
                    {{ $courts->links() }}
                </div>
            @endif
        </section>

    </main>

    <!-- FOOTER COMPONENT -->
    <x-footer />

</body>
</html>
