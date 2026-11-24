<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan - Owner Portal | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        {{-- ============ SIDEBAR OWNER ============ --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-arena-night flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto"
            style="background-color: #0f2444;"
        >
            <div class="flex items-center gap-3 px-6 py-5 border-b border-pitch-deep/50">
                <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
                <div>
                    <span class="text-xs font-bold text-action-amber uppercase tracking-wider block">Owner</span>
                    <span class="text-[10px] text-surface-container-highest block">Multi-Venue Portal</span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <p class="text-[10px] text-surface-container-highest uppercase tracking-wider px-3 mb-2 font-bold">Menu Pemilik</p>
                <div class="flex flex-col gap-1">
                    @php
                        $navItems = [
                            ['dashboard', 'Dashboard Bisnis', route('owner.dashboard'), request()->routeIs('owner.dashboard')],
                            ['bar_chart', 'Laporan Keuangan', route('owner.laporan'), request()->routeIs('owner.laporan')],
                            ['receipt_long', 'Monitoring Booking', route('owner.booking'), request()->routeIs('owner.booking')],
                            ['stadium', 'Kelola Lapangan', route('owner.lapangan'), request()->routeIs('owner.lapangan')],
                        ];
                    @endphp
                    @foreach($navItems as $item)
                    <a
                        href="{{ $item[2] }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-action-amber/20 text-action-amber font-bold' : 'text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5' }}"
                    >
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                    </a>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-pitch-deep/50 flex flex-col gap-1">
                    <a href="{{ route('beranda') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                        Lihat Situs Publik
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-rose-400 hover:bg-rose-500/10 transition-all text-left">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>

            <div class="px-4 py-4 border-t border-pitch-deep/50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-action-amber/30 flex items-center justify-center">
                        <span class="font-bold text-action-amber text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-surface-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-surface-container-highest truncate">Owner Venue</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/50 z-40" style="display:none;"></div>

        {{-- ============ MAIN CONTENT ============ --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Top Header --}}
            <header class="bg-surface-white border-b border-surface-container-high px-6 py-4 flex items-center justify-between gap-4 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-surface-container-low text-on-surface-variant">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Kelola Lapangan & Fasilitas</h1>
                        <p class="text-xs text-on-surface-variant">Daftar lapangan dan tarif per jam untuk setiap venue milik Anda</p>
                    </div>
                </div>
                <div>
                    @if($venues->count() > 1)
                    <form method="GET" action="{{ route('owner.lapangan') }}">
                        <select name="venue_id" onchange="this.form.submit()" class="text-xs font-bold border border-surface-container-high rounded-xl px-3.5 py-2 bg-surface-white text-on-surface focus:ring-2 focus:ring-action-orange">
                            @foreach($venues as $v)
                            <option value="{{ $v->id }}" {{ $currentVenue->id === $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    @else
                    <div class="px-3.5 py-1.5 rounded-full bg-pitch-surface border border-pitch-light/30 text-pitch-deep text-xs font-bold">
                        {{ $currentVenue->name }}
                    </div>
                    @endif
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                {{-- Venue Info Banner --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-[11px] font-bold text-action-orange uppercase tracking-wider block">Venue Terpilih</span>
                        <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $currentVenue->name }}</h2>
                        <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-[15px]">location_on</span>
                            {{ $currentVenue->address }}, {{ $currentVenue->city }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right sm:border-r border-surface-container-high sm:pr-4">
                            <span class="text-xs text-on-surface-variant block">Total Lapangan:</span>
                            <span class="font-headline text-lg font-bold text-on-surface">{{ $courts->total() }} Unit</span>
                        </div>
                    </div>
                </div>

                {{-- Courts Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($courts as $court)
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high shadow-xs overflow-hidden flex flex-col justify-between hover:border-pitch-emerald/50 hover:shadow-md transition-all">
                        {{-- Thumbnail --}}
                        <div class="relative h-44 bg-surface-container-low flex items-center justify-center overflow-hidden">
                            @if($court->thumbnail)
                            <img src="{{ asset('storage/' . $court->thumbnail) }}" alt="{{ $court->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-on-surface-variant/40 flex-col gap-2">
                                <span class="material-symbols-outlined text-4xl">stadium</span>
                                <span class="text-xs font-medium">Foto Lapangan</span>
                            </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span class="bg-arena-night/80 backdrop-blur-xs text-white text-[11px] font-bold px-2.5 py-1 rounded-full uppercase" style="background-color: rgba(15, 36, 68, 0.85);">
                                    {{ $court->sport_category }}
                                </span>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $court->status->value === 'active' ? 'bg-pitch-surface text-pitch-deep border border-pitch-light/30' : 'bg-court-blocked-surface text-court-blocked border border-court-blocked/30' }}">
                                    {{ $court->status->value === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>

                        {{-- Court Details --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-headline text-lg font-bold text-on-surface">{{ $court->name }}</h3>
                                <p class="text-xs text-on-surface-variant mt-1 line-clamp-2">{{ $court->description ?? 'Lapangan standar kompetisi terbaik dengan fasilitas lengkap.' }}</p>

                                <div class="mt-4 pt-3 border-t border-surface-container-high/80 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-on-surface-variant uppercase block font-semibold">Tarif Sewa</span>
                                        <div class="text-sm font-extrabold text-pitch-emerald">
                                            Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} <span class="text-[10px] font-normal text-on-surface-variant">/ jam</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-on-surface-variant uppercase block font-semibold">Jam Operasional</span>
                                        <div class="text-xs font-bold text-on-surface">
                                            {{ substr($court->open_time, 0, 5) }} - {{ substr($court->close_time, 0, 5) }} WIB
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-t border-surface-container-high flex items-center justify-between text-xs text-on-surface-variant">
                                <div class="flex items-center gap-1 font-semibold text-on-surface">
                                    <span class="material-symbols-outlined text-[16px] text-pitch-emerald">receipt_long</span>
                                    <span>{{ $court->booking_items_count }} kali dibooking</span>
                                </div>
                                <a href="{{ route('lapangan.detail', $court->slug) }}" target="_blank" class="text-action-orange font-bold hover:underline flex items-center gap-0.5">
                                    Lihat Publik <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 bg-surface-white rounded-2xl border border-surface-container-high p-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-5xl text-surface-container-highest mb-2">sports_soccer</span>
                        <p class="font-bold text-sm text-on-surface">Belum ada lapangan yang terdaftar pada venue ini.</p>
                    </div>
                    @endforelse
                </div>

                @if($courts->hasPages())
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 shadow-xs">
                    {{ $courts->links() }}
                </div>
                @endif

            </main>
        </div>
    </div>

</body>
</html>
