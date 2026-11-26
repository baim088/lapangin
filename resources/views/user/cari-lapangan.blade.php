<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Lapangan - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950" x-data="{ sidebarOpen: false }">

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

            return route('user.cari-lapangan', $params);
        };
    @endphp

    <div class="flex min-h-screen">

        {{-- Mobile Backdrop --}}
        <div 
            x-show="sidebarOpen" 
            x-transition.opacity
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
            style="display: none;"
        ></div>

        {{-- ============ SIDEBAR CUSTOMER ============ --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f2444] flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:z-auto shrink-0"
        >
            {{-- Logo Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
                </a>
                <span class="text-[11px] font-bold text-[#f8b710] uppercase tracking-wider bg-[#f8b710]/15 border border-[#f8b710]/30 px-2 py-0.5 rounded-md">Member</span>
            </div>

            {{-- Main Navigation Links --}}
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <div class="flex flex-col gap-1">
                    <a
                        href="{{ route('user.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all text-slate-300 hover:text-white hover:bg-white/5"
                    >
                        <span class="material-symbols-outlined text-[20px]">dashboard</span>
                        Dashboard &amp; Booking
                    </a>

                    <a
                        href="{{ route('user.cari-lapangan') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all bg-[#f8b710] text-slate-950 shadow-sm"
                    >
                        <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                        Cari Lapangan
                    </a>
                </div>

                {{-- Divider --}}
                <div class="mt-6 pt-4 border-t border-white/10 flex flex-col gap-1">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-rose-400 hover:bg-rose-500/10 transition-all text-left cursor-pointer">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>

            {{-- Bottom User Info --}}
            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                    <div class="w-9 h-9 rounded-full bg-[#f8b710]/20 border border-[#f8b710]/40 flex items-center justify-center shrink-0">
                        <span class="font-bold text-[#f8b710] text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ $user->name }}</p>
                        <p class="text-[10px] text-slate-300 truncate">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ============ MAIN CONTENT AREA ============ --}}
        <div class="flex-1 min-w-0 flex flex-col bg-[#f8f9fa] min-h-screen">

            {{-- Topbar Header --}}
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Toggle Sidebar">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <div>
                        <h1 class="font-teko text-2xl sm:text-3xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">Cari &amp; Booking Lapangan</h1>
                        <p class="text-xs text-slate-500 hidden sm:block">Pilih arena resmi di 5 wilayah DKI Jakarta dan amankan jadwal Anda</p>
                    </div>
                </div>
                <a href="{{ route('user.dashboard') }}" class="text-xs font-bold text-[#0f2444] hover:text-[#f37920] flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Kembali ke Booking Saya
                </a>
            </header>

            {{-- Content Container --}}
            <main class="p-4 sm:p-8 flex-1 flex flex-col gap-6 max-w-[1440px] w-full mx-auto">

                {{-- Filter & Search Panel --}}
                <div class="bg-[#0f2444] text-white rounded-2xl p-5 sm:p-6 shadow-md border border-white/10">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <form action="{{ route('user.cari-lapangan') }}" method="GET" class="w-full lg:max-w-md">
                            @if ($currentCategory !== 'all')
                                <input type="hidden" name="category" value="{{ $currentCategory }}">
                            @endif
                            @if ($currentRegion !== 'all')
                                <input type="hidden" name="region" value="{{ $currentRegion }}">
                            @endif
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 text-[18px]">search</span>
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ $currentSearch }}"
                                    placeholder="Cari lapangan, venue, atau lokasi..."
                                    class="w-full pl-10 pr-20 py-2.5 rounded-xl bg-white text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]"
                                >
                                <button type="submit" class="absolute right-1.5 bg-[#f8b710] hover:bg-[#f37920] text-slate-950 font-bold text-xs px-3.5 py-1.5 rounded-lg transition-colors cursor-pointer">
                                    Cari
                                </button>
                            </div>
                        </form>

                        <!-- Category Pills -->
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ([
                                'all' => 'Semua Olahraga',
                                'futsal' => 'Futsal',
                                'badminton' => 'Badminton',
                                'minisoccer' => 'Mini Soccer',
                                'padel' => 'Padel',
                            ] as $catKey => $catLabel)
                                <a 
                                    href="{{ $buildFilterUrl($catKey, null) }}"
                                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $currentCategory === $catKey ? 'bg-[#f8b710] text-slate-950 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }}"
                                >
                                    {{ $catLabel }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Region Filter Pills -->
                    <div class="flex flex-wrap items-center gap-1.5 mt-4 pt-4 border-t border-white/10 text-xs">
                        <span class="text-slate-300 font-semibold mr-1">Wilayah:</span>
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
                                class="px-3 py-1 rounded-full font-semibold transition-all {{ $currentRegion === $regKey ? ($regKey === 'all' ? 'bg-white text-slate-950 font-bold' : 'bg-[#f8b710] text-slate-950 font-bold') : 'bg-white/10 text-slate-200 hover:bg-white/20' }}"
                            >
                                {{ $regLabel }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Result Summary -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <p class="text-xs sm:text-sm text-slate-600 font-medium">
                        Menampilkan <span class="font-bold text-slate-900">{{ $courts->firstItem() ?? 0 }}–{{ $courts->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-900">{{ $courts->total() }}</span> lapangan
                    </p>
                    @if ($currentCategory !== 'all' || $currentRegion !== 'all' || $currentSearch !== '')
                        <a href="{{ route('user.cari-lapangan') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#f37920] hover:underline">
                            <span class="material-symbols-outlined text-[15px]">restart_alt</span>
                            Reset Filter
                        </a>
                    @endif
                </div>

                <!-- 5-Column Compact Court Grid -->
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
                        <div class="group bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-lg transition-all flex flex-col justify-between">
                            <div>
                                <div class="relative aspect-[4/3] bg-slate-900 overflow-hidden">
                                    <img 
                                        src="{{ asset($imagePath) }}" 
                                        alt="{{ $court->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/15 pointer-events-none"></div>
                                    <span class="absolute top-2.5 left-2.5 bg-[#f8b710] text-slate-950 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow">
                                        {{ $sportLabel }}
                                    </span>
                                    <span class="absolute top-2.5 right-2.5 inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full backdrop-blur-md {{ $court->status === \App\Enums\CourtStatus::Active ? 'bg-emerald-500/90 text-white' : 'bg-amber-500/90 text-white' }}">
                                        {{ $court->status === \App\Enums\CourtStatus::Active ? 'Tersedia' : 'Maintenance' }}
                                    </span>
                                </div>

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
                        <div class="col-span-full p-12 text-center text-slate-500 bg-white rounded-3xl border border-slate-200">
                            <span class="material-symbols-outlined text-[40px] text-slate-300 mb-2">search_off</span>
                            <p class="font-semibold text-slate-700">Tidak ada lapangan yang sesuai dengan filter Anda.</p>
                            <a href="{{ route('user.cari-lapangan') }}" class="inline-block mt-3 text-xs font-bold text-[#f37920] hover:underline">Tampilkan semua lapangan</a>
                        </div>
                    @endforelse
                </div>

                @if ($courts->hasPages())
                    <div class="mt-4">
                        {{ $courts->links() }}
                    </div>
                @endif

            </main>
        </div>
    </div>

</body>
</html>
