<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Katalog Lengkap Lapangan Olahraga di Lapangin. Futsal, Badminton, Mini Soccer, dan Padel Court standar turnamen internasional.">
    <title>Koleksi Lapangan - {{ config('app.name', 'Lapangin') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ 
        mobileMenuOpen: false, 
        selectedCategory: 'all' 
    }"
    class="bg-surface font-body text-on-surface antialiased selection:bg-secondary-container selection:text-on-secondary-container"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="lapangan" />

    <main class="w-full pt-20 bg-surface min-h-[calc(100vh-80px)]">
        
        <!-- HEADER BANNER -->
        <section class="w-full bg-arena-night text-surface-white py-16 px-4 sm:px-8 border-b border-pitch-light/20 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-pitch-emerald/20 blur-3xl pointer-events-none"></div>
            <div class="w-full max-w-7xl mx-auto text-center relative z-10">
                <h1 class="font-headline text-3xl sm:text-5xl font-bold uppercase tracking-tight text-surface-white mb-4">
                    Koleksi Arena Olahraga
                </h1>
                <p class="font-body text-sm sm:text-base text-surface-container-highest max-w-2xl mx-auto leading-relaxed">
                    Setiap gelanggang di Lapangin dirawat harian, dilengkapi karpet & rumput sintetis bersertifikasi, sistem pendingin udara, dan pencahayaan LED 800 Lux anti-silau.
                </p>

                <!-- Category Filter Tabs -->
                <div class="flex flex-wrap items-center justify-center gap-2 mt-8">
                    <button 
                        type="button"
                        @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-action-orange text-surface-white font-bold shadow' : 'bg-surface-white/10 text-surface-white hover:bg-surface-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all whitespace-nowrap"
                    >
                        Semua Lapangan ({{ isset($courts) ? $courts->count() : 4 }})
                    </button>
                    <button 
                        type="button"
                        @click="selectedCategory = 'futsal'"
                        :class="selectedCategory === 'futsal' ? 'bg-action-orange text-surface-white font-bold shadow' : 'bg-surface-white/10 text-surface-white hover:bg-surface-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all whitespace-nowrap"
                    >
                        Futsal
                    </button>
                    <button 
                        type="button"
                        @click="selectedCategory = 'badminton'"
                        :class="selectedCategory === 'badminton' ? 'bg-action-orange text-surface-white font-bold shadow' : 'bg-surface-white/10 text-surface-white hover:bg-surface-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all whitespace-nowrap"
                    >
                        Badminton
                    </button>
                    <button 
                        type="button"
                        @click="selectedCategory = 'minisoccer'"
                        :class="selectedCategory === 'minisoccer' ? 'bg-action-orange text-surface-white font-bold shadow' : 'bg-surface-white/10 text-surface-white hover:bg-surface-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all whitespace-nowrap"
                    >
                        Mini Soccer
                    </button>
                    <button 
                        type="button"
                        @click="selectedCategory = 'padel'"
                        :class="selectedCategory === 'padel' ? 'bg-action-orange text-surface-white font-bold shadow' : 'bg-surface-white/10 text-surface-white hover:bg-surface-white/20'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all whitespace-nowrap"
                    >
                        Padel Court
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
                            'padel' => 'Padel Tennis',
                            default => ucfirst($sportCategoryValue),
                        };
                    @endphp
                    <div 
                        x-show="selectedCategory === 'all' || selectedCategory === '{{ $sportCategoryFilter }}'"
                        class="group bg-surface-white rounded-3xl overflow-hidden border border-slate-200 shadow-md grid grid-cols-1 lg:grid-cols-12 transition-all hover:shadow-xl"
                    >
                        <div class="lg:col-span-5 relative min-h-[280px] sm:min-h-[340px] lg:min-h-full bg-slate-900 overflow-hidden">
                            <img 
                                src="{{ asset($imagePath) }}" 
                                alt="{{ $court->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 min-h-[280px] sm:min-h-[340px] lg:min-h-full"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                            <span class="absolute top-4 left-4 bg-pitch-deep text-surface-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg border border-pitch-light/30">
                                {{ $sportLabel }}
                            </span>
                            @if ($court->status === \App\Enums\CourtStatus::Maintenance)
                                <span class="absolute top-4 right-4 bg-action-amber text-surface-white text-xs font-bold px-3 py-1 rounded-md shadow">
                                    Maintenance
                                </span>
                            @endif
                            <span class="absolute bottom-4 right-4 bg-surface-white/95 text-on-surface text-xs font-bold px-3 py-1 rounded-md backdrop-blur-md shadow">
                                {{ $court->surface_type }}
                            </span>
                        </div>

                        <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between gap-6">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-bold text-pitch-emerald uppercase tracking-wider">{{ $court->venue->name ?? 'Lapangin Sports Center' }}</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                    <span class="text-xs text-on-surface-variant font-medium">Status: {{ ucfirst($court->status->value) }}</span>
                                </div>
                                <h2 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface mb-3">
                                    {{ $court->name }}
                                </h2>
                                <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-6">
                                    Gelanggang {{ strtolower($sportLabel) }} berkualitas dengan permukaan {{ $court->surface_type }}. Dilengkapi pencahayaan LED optimal dan fasilitas terstandarisasi.
                                </p>

                                <!-- Spesifikasi Fasilitas -->
                                @if (!empty($court->specs) && is_array($court->specs))
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                                        @foreach (array_slice($court->specs, 0, 3) as $spec)
                                            <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                                <span class="text-on-surface-variant block font-medium">Spesifikasi</span>
                                                <strong class="text-on-surface">{{ $spec }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Matriks Harga -->
                                <div class="bg-surface-container-low p-4 rounded-2xl flex flex-wrap items-center justify-between gap-4 border border-slate-100">
                                    <div>
                                        <span class="text-xs text-on-surface-variant block">Tarif Reguler (Siang)</span>
                                        <span class="font-headline text-lg sm:text-xl font-bold text-pitch-deep">Rp {{ number_format($court->price_regular, 0, ',', '.') }} / Jam</span>
                                    </div>
                                    <div class="sm:border-l sm:border-slate-300 sm:pl-4">
                                        <span class="text-xs text-on-surface-variant block">Prime Time (Malam)</span>
                                        <span class="font-headline text-lg sm:text-xl font-bold text-action-orange">Rp {{ number_format($court->price_prime, 0, ',', '.') }} / Jam</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <a 
                                    href="{{ route('lapangan.detail', $court->slug) }}" 
                                    class="inline-flex items-center justify-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm px-6 py-3 rounded-xl transition-all shadow-md"
                                >
                                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                    Cek Slot & Booking Lapangan Ini
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-on-surface-variant bg-surface-white rounded-3xl border border-slate-200">
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
