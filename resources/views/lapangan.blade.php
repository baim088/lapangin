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
    x-data="{ 
        mobileMenuOpen: false, 
        selectedCategory: 'all',
        selectedRegion: 'all'
    }"
    class="bg-canvas-bg font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="lapangan" />

    <main class="w-full pt-20 bg-canvas-bg min-h-[calc(100vh-80px)]">
        
        <!-- HEADER BANNER -->
        <section class="w-full bg-[#0f2444] text-white py-16 px-4 sm:px-8 border-b border-white/10 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-[#f8b710]/15 blur-3xl pointer-events-none"></div>
            <div class="w-full max-w-7xl mx-auto text-center relative z-10">
                <span class="text-xs font-bold text-[#f8b710] uppercase tracking-widest block mb-1">KATALOG ARENA RESMI</span>
                <h1 class="font-teko text-5xl sm:text-6xl lg:text-7xl font-bold uppercase tracking-tight text-white mb-2 leading-none">
                    KOLEKSI ARENA OLAHRAGA
                </h1>
                <div class="flex items-center justify-center gap-1.5 mt-2 mb-4">
                    <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                    <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                </div>
                <p class="font-body text-sm sm:text-base text-slate-200/90 max-w-2xl mx-auto leading-relaxed">
                    Setiap gelanggang di LapangIn dirawat harian, dilengkapi karpet & rumput sintetis bersertifikasi, sistem pendingin udara, dan pencahayaan LED 800 Lux anti-silau.
                </p>

                <!-- Category Filter Tabs -->
                <div class="flex flex-wrap items-center justify-center gap-2 mt-8">
                    <button 
                        type="button"
                        @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap cursor-pointer"
                    >
                        Semua Olahraga ({{ isset($courts) ? $courts->count() : 50 }})
                    </button>
                    <button 
                        type="button"
                        @click="selectedCategory = 'futsal'"
                        :class="selectedCategory === 'futsal' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap cursor-pointer"
                    >
                        Futsal
                    </button>
                    <button 
                        type="button"
                        @click="selectedCategory = 'badminton'"
                        :class="selectedCategory === 'badminton' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap cursor-pointer"
                    >
                        Badminton
                    </button>
                    <button 
                        type="button"
                        @click="selectedCategory = 'minisoccer'"
                        :class="selectedCategory === 'minisoccer' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap cursor-pointer"
                    >
                        Mini Soccer
                    </button>
                    <button 
                        type="button"
                        @click="selectedCategory = 'padel'"
                        :class="selectedCategory === 'padel' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold transition-all whitespace-nowrap cursor-pointer"
                    >
                        Padel Court
                    </button>
                </div>

                <!-- Region Filter Tabs -->
                <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 mt-4 pt-4 border-t border-white/10 text-xs">
                    <span class="text-white/70 mr-1 hidden sm:inline font-semibold">Wilayah:</span>
                    <button 
                        type="button"
                        @click="selectedRegion = 'all'"
                        :class="selectedRegion === 'all' ? 'bg-white text-slate-950 font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-3.5 py-1.5 rounded-full font-semibold transition-all cursor-pointer"
                    >
                        Semua Wilayah
                    </button>
                    <button 
                        type="button"
                        @click="selectedRegion = 'selatan'"
                        :class="selectedRegion === 'selatan' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-3.5 py-1.5 rounded-full font-semibold transition-all cursor-pointer"
                    >
                        Jakarta Selatan
                    </button>
                    <button 
                        type="button"
                        @click="selectedRegion = 'timur'"
                        :class="selectedRegion === 'timur' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-3.5 py-1.5 rounded-full font-semibold transition-all cursor-pointer"
                    >
                        Jakarta Timur
                    </button>
                    <button 
                        type="button"
                        @click="selectedRegion = 'pusat'"
                        :class="selectedRegion === 'pusat' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-3.5 py-1.5 rounded-full font-semibold transition-all cursor-pointer"
                    >
                        Jakarta Pusat
                    </button>
                    <button 
                        type="button"
                        @click="selectedRegion = 'barat'"
                        :class="selectedRegion === 'barat' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-3.5 py-1.5 rounded-full font-semibold transition-all cursor-pointer"
                    >
                        Jakarta Barat
                    </button>
                    <button 
                        type="button"
                        @click="selectedRegion = 'utara'"
                        :class="selectedRegion === 'utara' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-3.5 py-1.5 rounded-full font-semibold transition-all cursor-pointer"
                    >
                        Jakarta Utara
                    </button>
                </div>
            </div>
        </section>

        <!-- COURTS DETAIL LIST -->
        <section class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-16">
            <div class="space-y-12">
                @forelse ($courts as $court)
                    @php
                        $sportCategoryValue = $court->sport_category instanceof \App\Enums\SportCategory ? $court->sport_category->value : (string) $court->sport_category;
                        $sportCategoryFilter = match($sportCategoryValue) {
                            'mini_soccer' => 'minisoccer',
                            default => $sportCategoryValue,
                        };
                        $imagePath = match($sportCategoryValue) {
                            'futsal' => 'images/courts/futsal.jpg',
                            'badminton' => 'images/courts/badminton.jpg',
                            'mini_soccer' => 'images/courts/minisoccer.jpg',
                            'padel' => 'images/courts/padel.jpg',
                            default => 'images/courts/futsal.jpg',
                        };
                        $sportLabel = match($sportCategoryValue) {
                            'futsal' => 'Futsal Arena',
                            'badminton' => 'Badminton Hall',
                            'mini_soccer' => 'Mini Soccer Outdoor',
                            'padel' => 'Padel Court',
                            default => ucfirst($sportCategoryValue),
                        };
                        $courtAddress = $court->venue->address ?? 'DKI Jakarta';
                    @endphp
                    <div 
                        x-show="(selectedCategory === 'all' || selectedCategory === '{{ $sportCategoryFilter }}') && (selectedRegion === 'all' || '{{ strtolower($courtAddress) }}'.includes(selectedRegion))"
                        class="group bg-surface-white rounded-3xl overflow-hidden border border-slate-200 shadow-md grid grid-cols-1 lg:grid-cols-12 transition-all hover:shadow-xl"
                    >
                        <div class="lg:col-span-5 relative min-h-[280px] sm:min-h-[340px] lg:min-h-full bg-slate-900 overflow-hidden">
                            <img 
                                src="{{ asset($imagePath) }}" 
                                alt="{{ $court->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 min-h-[280px] sm:min-h-[340px] lg:min-h-full"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                            <span class="absolute top-4 left-4 bg-[#f8b710] text-slate-950 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider shadow-lg">
                                {{ $sportLabel }}
                            </span>
                            @if ($court->status === \App\Enums\CourtStatus::Maintenance)
                                <span class="absolute top-4 right-4 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-md shadow">
                                    Maintenance
                                </span>
                            @endif
                            <span class="absolute bottom-4 right-4 bg-slate-950/80 text-white text-xs font-bold px-3 py-1 rounded-md backdrop-blur-md shadow">
                                {{ $court->surface_type }}
                            </span>
                        </div>

                        <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between gap-6">
                            <div>
                                <!-- Header Badge: Venue & Status -->
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 font-bold text-xs px-3 py-1 rounded-full border border-blue-100">
                                            <span class="material-symbols-outlined text-[15px]">stadium</span>
                                            Venue: {{ $court->venue->name ?? 'LapangIn Venue Hub' }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $court->status === \App\Enums\CourtStatus::Active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $court->status === \App\Enums\CourtStatus::Active ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        {{ $court->status === \App\Enums\CourtStatus::Active ? 'Tersedia untuk Booking' : 'Maintenance' }}
                                    </span>
                                </div>

                                <!-- Nama Court / Lapangan -->
                                <div class="mb-2">
                                    <span class="text-[11px] font-extrabold text-[#f37920] uppercase tracking-wider block mb-0.5">Nama Court / Lapangan:</span>
                                    <h2 class="font-teko text-3xl sm:text-4xl font-bold uppercase tracking-wide text-slate-900 leading-none group-hover:text-[#f37920] transition-colors">
                                        {{ $court->name }}
                                    </h2>
                                </div>

                                <!-- Lokasi Lapangan & Venue -->
                                <div class="flex items-center gap-2 bg-slate-50 p-2.5 px-3.5 rounded-xl border border-slate-100 mb-4 text-xs text-slate-700">
                                    <span class="material-symbols-outlined text-[#f37920] text-[18px] shrink-0">location_on</span>
                                    <span class="font-semibold text-slate-900">Lokasi:</span>
                                    <span class="text-slate-600 font-medium">{{ $courtAddress }}</span>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                                    Gelanggang {{ strtolower($sportLabel) }} dengan permukaan <strong>{{ $court->surface_type }}</strong>. Dikelola langsung oleh operator venue terverifikasi dengan jaminan slot instan dan bebas double-booking.
                                </p>

                                <!-- Spesifikasi Fasilitas -->
                                @if (!empty($court->specs) && is_array($court->specs))
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 mb-6">
                                        @foreach (array_slice($court->specs, 0, 4) as $spec)
                                            <div class="bg-slate-50 p-2.5 rounded-xl text-xs border border-slate-100 flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-[14px] text-emerald-600 shrink-0">check_circle</span>
                                                <span class="text-slate-700 font-medium truncate" title="{{ $spec }}">{{ $spec }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Matriks Harga -->
                                <div class="bg-slate-50 p-4 rounded-2xl flex flex-wrap items-center justify-between gap-4 border border-slate-100">
                                    <div>
                                        <span class="text-xs text-slate-500 block">Tarif Reguler (Siang)</span>
                                        <span class="font-teko text-2xl font-bold text-slate-900 leading-none">Rp {{ number_format($court->price_regular, 0, ',', '.') }} / Jam</span>
                                    </div>
                                    <div class="sm:border-l sm:border-slate-300 sm:pl-4">
                                        <span class="text-xs text-slate-500 block">Prime Time (Malam)</span>
                                        <span class="font-teko text-2xl font-bold text-[#f37920] leading-none">Rp {{ number_format($court->price_prime, 0, ',', '.') }} / Jam</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <a 
                                    href="{{ route('lapangan.detail', $court->slug) }}" 
                                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-xs uppercase tracking-wider px-7 py-3.5 rounded-full shadow-md transition-all transform hover:-translate-y-0.5"
                                >
                                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                                    Cek Slot & Booking Lapangan Ini
                                </a>
                            </div>
                        </div>
                    </div> 
                                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-xs uppercase tracking-wider px-7 py-3.5 rounded-full shadow-md transition-all transform hover:-translate-y-0.5"
                                >
                                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                                    Cek Slot & Booking Lapangan Ini
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-500 bg-surface-white rounded-3xl border border-slate-200">
                        <p>Belum ada lapangan yang terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    <!-- FOOTER COMPONENT -->
    <x-footer />
</body>
</html>
