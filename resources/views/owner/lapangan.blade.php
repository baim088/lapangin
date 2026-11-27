<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan - Owner Portal | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-[#f8f9fa] font-body text-slate-800 antialiased"
    x-data="{
        sidebarOpen: false,
        showAddModal: false,
        showEditModal: false,
        editName: '',
        editCategory: 'futsal',
        editSurface: '',
        editPriceRegular: '',
        editPricePrime: '',
        editOpenTime: '{{ substr($currentVenue->open_time, 0, 5) }}',
        editCloseTime: '{{ substr($currentVenue->close_time, 0, 5) }}',
        editPrimeStart: '{{ substr($currentVenue->prime_start_time, 0, 5) }}',
        editPrimeEnd: '{{ substr($currentVenue->prime_end_time, 0, 5) }}',
        editActionUrl: ''
    }"
>

    <div class="flex min-h-screen">
        {{-- ============ SIDEBAR OWNER ============ --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f2444] flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto"
            style="background-color: #0f2444;"
        >
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
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
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-[#f8b710] text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                    >
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                    </a>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-white/10 flex flex-col gap-1">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-rose-400 hover:bg-rose-500/10 transition-all text-left">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>

            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-[#f8b710]/20 border border-[#f8b710]/40 flex items-center justify-center">
                        <span class="font-bold text-[#f8b710] text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
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
                <div class="flex items-center gap-3">
                    @if($venues->count() > 1)
                    <form method="GET" action="{{ route('owner.lapangan') }}">
                        <select name="venue_id" onchange="this.form.submit()" class="text-xs font-bold border border-surface-container-high rounded-xl px-3.5 py-2 bg-surface-white text-on-surface focus:ring-2 focus:ring-action-orange">
                            @foreach($venues as $v)
                            <option value="{{ $v->id }}" {{ $currentVenue->id === $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    @else
                    <div class="hidden sm:block px-3.5 py-1.5 rounded-full bg-pitch-surface border border-pitch-light/30 text-pitch-deep text-xs font-bold">
                        {{ $currentVenue->name }}
                    </div>
                    @endif
                    <button
                        type="button"
                        @click="showAddModal = true"
                        class="flex items-center gap-1.5 bg-[#0f2444] hover:bg-[#f37920] text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-colors shadow-xs"
                    >
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>Tambah Lapangan</span>
                    </button>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                @if(session('status'))
                <div class="p-4 bg-pitch-surface border border-pitch-light/40 rounded-xl text-sm font-semibold text-pitch-deep flex items-center gap-2">
                    <span class="material-symbols-outlined text-pitch-emerald text-[20px]">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 bg-error-container/20 border border-error/30 rounded-xl text-sm font-semibold text-error flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                {{-- Venue Info Banner --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-[11px] font-bold text-action-orange uppercase tracking-wider block">Venue Terpilih</span>
                        <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $currentVenue->name }}</h2>
                        <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-[15px]">location_on</span>
                            {{ $currentVenue->address }}, {{ $currentVenue->city }}
                        </p>
                        <p class="text-[11px] text-pitch-deep font-semibold mt-1.5">
                            Jam Operasional: {{ substr($currentVenue->open_time, 0, 5) }} - {{ substr($currentVenue->close_time, 0, 5) }} WIB
                            &nbsp;&bull;&nbsp;
                            Jam Prime: {{ substr($currentVenue->prime_start_time, 0, 5) }} - {{ substr($currentVenue->prime_end_time, 0, 5) }} WIB
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
                            <x-lazy-image
                                :src="asset('storage/' . $court->thumbnail)"
                                :alt="$court->name"
                                :eager="$loop->index < 3"
                                class="w-full h-full object-cover"
                            />
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
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="font-headline text-lg font-bold text-on-surface">{{ $court->name }}</h3>
                                    <span class="text-[11px] text-on-surface-variant font-medium shrink-0">{{ $court->surface_type }}</span>
                                </div>

                                <div class="mt-3 pt-3 border-t border-surface-container-high/80 grid grid-cols-2 gap-3">
                                    <div>
                                        <span class="text-[10px] text-on-surface-variant uppercase block font-semibold">Jam Normal</span>
                                        <div class="text-sm font-extrabold text-pitch-emerald">
                                            Rp {{ number_format($court->price_regular, 0, ',', '.') }} <span class="text-[10px] font-normal text-on-surface-variant">/jam</span>
                                        </div>
                                        <div class="text-[10px] text-on-surface-variant mt-0.5">
                                            {{ substr($currentVenue->open_time, 0, 5) }} - {{ substr($currentVenue->prime_start_time, 0, 5) }} WIB
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-action-orange uppercase block font-semibold">Jam Prime</span>
                                        <div class="text-sm font-extrabold text-on-surface">
                                            Rp {{ number_format($court->price_prime, 0, ',', '.') }} <span class="text-[10px] font-normal text-on-surface-variant">/jam</span>
                                        </div>
                                        <div class="text-[10px] text-on-surface-variant mt-0.5">
                                            {{ substr($currentVenue->prime_start_time, 0, 5) }} - {{ substr($currentVenue->prime_end_time, 0, 5) }} WIB
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-surface-container-high flex flex-col gap-3">
                                <div class="flex items-center justify-between text-xs text-on-surface-variant">
                                    <div class="flex items-center gap-1 font-semibold text-on-surface">
                                        <span class="material-symbols-outlined text-[16px] text-pitch-emerald">receipt_long</span>
                                        <span>{{ $court->booking_items_count }} kali dibooking</span>
                                    </div>
                                    <a href="{{ route('lapangan.detail', $court->slug) }}" target="_blank" class="text-action-orange font-bold hover:underline flex items-center gap-0.5">
                                        Lihat Publik <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    </a>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        @click="editName = '{{ addslashes($court->name) }}'; editCategory = '{{ $court->sport_category->value }}'; editSurface = '{{ addslashes($court->surface_type) }}'; editPriceRegular = '{{ $court->price_regular }}'; editPricePrime = '{{ $court->price_prime }}'; editOpenTime = '{{ substr($currentVenue->open_time, 0, 5) }}'; editCloseTime = '{{ substr($currentVenue->close_time, 0, 5) }}'; editPrimeStart = '{{ substr($currentVenue->prime_start_time, 0, 5) }}'; editPrimeEnd = '{{ substr($currentVenue->prime_end_time, 0, 5) }}'; editActionUrl = '{{ route('owner.lapangan.update', $court->id) }}'; showEditModal = true"
                                        class="flex-1 py-2 px-3 rounded-xl border border-slate-200 text-xs font-bold text-[#0f2444] hover:bg-slate-50 transition-colors flex items-center justify-center gap-1"
                                    >
                                        <span class="material-symbols-outlined text-[15px]">edit</span>
                                        Edit
                                    </button>
                                    <form action="{{ route('owner.lapangan.toggle-status', $court->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Ubah status operasional lapangan ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full py-2 px-3 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1 {{ $court->status->value === 'active' ? 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">
                                            <span class="material-symbols-outlined text-[15px]">{{ $court->status->value === 'active' ? 'build' : 'check_circle' }}</span>
                                            {{ $court->status->value === 'active' ? 'Maintenance' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
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

    {{-- Modal Tambah Lapangan + Jam Operasional & Prime --}}
    <div x-show="showAddModal" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="showAddModal = false" class="bg-white rounded-2xl w-full max-w-xl p-6 shadow-2xl border border-slate-200 flex flex-col gap-5 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="font-teko text-2xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">Tambah Lapangan Baru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Venue: <strong>{{ $currentVenue->name }}</strong></p>
                </div>
                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-700 p-1">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('owner.lapangan.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="venue_id" value="{{ $currentVenue->id }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Nama Lapangan</label>
                        <input type="text" name="name" required placeholder="Contoh: Futsal Arena C" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Jenis Olahraga</label>
                        <select name="sport_category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                            <option value="futsal">Futsal</option>
                            <option value="badminton">Badminton</option>
                            <option value="mini_soccer">Mini Soccer</option>
                            <option value="padel">Padel</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Jenis Permukaan</label>
                        <input type="text" name="surface_type" required placeholder="Karpet Vinyl / Rumput Sintetis" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Harga Jam Normal (Rp/jam)</label>
                        <input type="number" name="price_regular" required min="1000" placeholder="120000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Harga Jam Prime (Rp/jam)</label>
                        <input type="number" name="price_prime" required min="1000" placeholder="200000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 flex flex-col gap-3">
                    <div>
                        <h3 class="text-xs font-bold text-[#0f2444] uppercase tracking-wider">Pengaturan Jam Operasional &amp; Prime Time Venue</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Jam di luar rentang Prime Time otomatis memakai tarif <strong>Jam Normal</strong>.</p>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-slate-700">Jam Buka</label>
                            <input type="time" name="open_time" value="{{ substr($currentVenue->open_time, 0, 5) }}" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-slate-700">Jam Tutup</label>
                            <input type="time" name="close_time" value="{{ substr($currentVenue->close_time, 0, 5) }}" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-[#f37920]">Mulai Jam Prime</label>
                            <input type="time" name="prime_start_time" value="{{ substr($currentVenue->prime_start_time, 0, 5) }}" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-[#f37920]">Selesai Jam Prime</label>
                            <input type="time" name="prime_end_time" value="{{ substr($currentVenue->prime_end_time, 0, 5) }}" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" @click="showAddModal = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#0f2444] hover:bg-[#f37920] text-white font-bold text-xs transition-colors">Simpan Lapangan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Lapangan + Jam Operasional & Prime --}}
    <div x-show="showEditModal" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="showEditModal = false" class="bg-white rounded-2xl w-full max-w-xl p-6 shadow-2xl border border-slate-200 flex flex-col gap-5 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="font-teko text-2xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">Edit Lapangan &amp; Jam Operasional</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Venue: <strong>{{ $currentVenue->name }}</strong></p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-700 p-1">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form :action="editActionUrl" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Nama Lapangan</label>
                        <input type="text" name="name" x-model="editName" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Jenis Olahraga</label>
                        <select name="sport_category" x-model="editCategory" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                            <option value="futsal">Futsal</option>
                            <option value="badminton">Badminton</option>
                            <option value="mini_soccer">Mini Soccer</option>
                            <option value="padel">Padel</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Jenis Permukaan</label>
                        <input type="text" name="surface_type" x-model="editSurface" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Harga Jam Normal (Rp/jam)</label>
                        <input type="number" name="price_regular" x-model="editPriceRegular" required min="1000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-slate-700">Harga Jam Prime (Rp/jam)</label>
                        <input type="number" name="price_prime" x-model="editPricePrime" required min="1000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 flex flex-col gap-3">
                    <div>
                        <h3 class="text-xs font-bold text-[#0f2444] uppercase tracking-wider">Pengaturan Jam Operasional &amp; Prime Time Venue</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Jam di luar rentang Prime Time otomatis memakai tarif <strong>Jam Normal</strong>.</p>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-slate-700">Jam Buka</label>
                            <input type="time" name="open_time" x-model="editOpenTime" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-slate-700">Jam Tutup</label>
                            <input type="time" name="close_time" x-model="editCloseTime" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-[#f37920]">Mulai Jam Prime</label>
                            <input type="time" name="prime_start_time" x-model="editPrimeStart" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-[#f37920]">Selesai Jam Prime</label>
                            <input type="time" name="prime_end_time" x-model="editPrimeEnd" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#f8b710]">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" @click="showEditModal = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#0f2444] hover:bg-[#f37920] text-white font-bold text-xs transition-colors">Perbarui Lapangan</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
