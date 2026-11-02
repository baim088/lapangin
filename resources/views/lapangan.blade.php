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
                <span class="inline-flex items-center gap-2 bg-pitch-surface/10 text-pitch-light border border-pitch-light/30 px-3.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">
                    Standar Turnamen Internasional
                </span>
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
                        Semua Lapangan (4)
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
                
                <!-- Court 1: Futsal -->
                <div 
                    x-show="selectedCategory === 'all' || selectedCategory === 'futsal'"
                    class="group bg-surface-white rounded-3xl overflow-hidden border border-slate-200 shadow-md grid grid-cols-1 lg:grid-cols-12 transition-all hover:shadow-xl"
                >
                    <div class="lg:col-span-5 relative min-h-[280px] sm:min-h-[340px] lg:min-h-full bg-slate-900 overflow-hidden">
                        <img 
                            src="{{ asset('images/courts/futsal.jpg') }}" 
                            alt="Lapangan Futsal A Lapangin"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 min-h-[280px] sm:min-h-[340px] lg:min-h-full"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                        <span class="absolute top-4 left-4 bg-pitch-deep text-surface-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg border border-pitch-light/30">
                            Futsal Arena
                        </span>
                        <span class="absolute bottom-4 right-4 bg-surface-white/95 text-on-surface text-xs font-bold px-3 py-1 rounded-md backdrop-blur-md shadow">
                            25 × 15 Meter
                        </span>
                    </div>

                    <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-bold text-pitch-emerald uppercase tracking-wider">Indoor Hall Utama</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span class="text-xs text-on-surface-variant font-medium">Kapasitas 10-14 Pemain</span>
                            </div>
                            <h2 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface mb-3">
                                Lapangan Futsal A (Vinyl & Synthetic Turf)
                            </h2>
                            <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-6">
                                Lapangan futsal indoor kelas kompetisi dengan pilihan lantai rumput sintetis monofilament 5cm dan interlock vinyl elastis peredam benturan lutut. Dilengkapi jaring pengaman keliling 6 meter dan papan skor digital nirkabel.
                            </p>

                            <!-- Spesifikasi Fasilitas -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Permukaan</span>
                                    <strong class="text-on-surface">Monofilament 5cm</strong>
                                </div>
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Pencahayaan</span>
                                    <strong class="text-on-surface">LED 800 Lux Floodlight</strong>
                                </div>
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Fasilitas Khusus</span>
                                    <strong class="text-on-surface">Rompi & Bola Match</strong>
                                </div>
                            </div>

                            <!-- Matriks Harga -->
                            <div class="bg-surface-container-low p-4 rounded-2xl flex flex-wrap items-center justify-between gap-4 border border-slate-100">
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Tarif Siang (08.00 - 16.00)</span>
                                    <span class="font-headline text-lg sm:text-xl font-bold text-pitch-deep">Rp 150.000 / Jam</span>
                                </div>
                                <div class="sm:border-l sm:border-slate-300 sm:pl-4">
                                    <span class="text-xs text-on-surface-variant block">Prime Time Malam (17.00 - 23.00)</span>
                                    <span class="font-headline text-lg sm:text-xl font-bold text-action-orange">Rp 220.000 / Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a 
                                href="{{ route('beranda') }}#jadwal-interaktif" 
                                class="inline-flex items-center justify-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm px-6 py-3 rounded-xl transition-all shadow-md"
                            >
                                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                Cek Slot & Booking Lapangan Ini
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Court 2: Badminton -->
                <div 
                    x-show="selectedCategory === 'all' || selectedCategory === 'badminton'"
                    class="group bg-surface-white rounded-3xl overflow-hidden border border-slate-200 shadow-md grid grid-cols-1 lg:grid-cols-12 transition-all hover:shadow-xl"
                >
                    <div class="lg:col-span-5 relative min-h-[280px] sm:min-h-[340px] lg:min-h-full bg-slate-900 overflow-hidden">
                        <img 
                            src="{{ asset('images/courts/badminton.jpg') }}" 
                            alt="Badminton Court 1-4 Lapangin"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 min-h-[280px] sm:min-h-[340px] lg:min-h-full"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                        <span class="absolute top-4 left-4 bg-pitch-deep text-surface-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg border border-pitch-light/30">
                            Badminton Hall
                        </span>
                        <span class="absolute bottom-4 right-4 bg-surface-white/95 text-on-surface text-xs font-bold px-3 py-1 rounded-md backdrop-blur-md shadow">
                            13.4 × 6.1 Meter
                        </span>
                    </div>

                    <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-bold text-pitch-emerald uppercase tracking-wider">Hall BWF Standard</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span class="text-xs text-on-surface-variant font-medium">Tersedia 4 Court Paralel</span>
                            </div>
                            <h2 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface mb-3">
                                Badminton Court 1–4 (Karakal BWF Vinyl Mat)
                            </h2>
                            <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-6">
                                Karpet vinyl badminton bersertifikasi BWF dengan ketebalan 4.5mm bertekstur pasir anti-slip. Plafon setinggi 9 meter dengan pendingin udara sejuk tanpa hembusan angin langsung yang mengganggu arah shuttlecock.
                            </p>

                            <!-- Spesifikasi Fasilitas -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Tipe Karpet</span>
                                    <strong class="text-on-surface">BWF Approved 4.5mm</strong>
                                </div>
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Tinggi Plafon</span>
                                    <strong class="text-on-surface">9 Meter (Bebas Halangan)</strong>
                                </div>
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Lampu Sorot</span>
                                    <strong class="text-on-surface">LED Indirect (Anti-Silau)</strong>
                                </div>
                            </div>

                            <!-- Matriks Harga -->
                            <div class="bg-surface-container-low p-4 rounded-2xl flex flex-wrap items-center justify-between gap-4 border border-slate-100">
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Tarif Siang (08.00 - 16.00)</span>
                                    <span class="font-headline text-lg sm:text-xl font-bold text-pitch-deep">Rp 45.000 / Jam</span>
                                </div>
                                <div class="sm:border-l sm:border-slate-300 sm:pl-4">
                                    <span class="text-xs text-on-surface-variant block">Prime Time Malam (17.00 - 23.00)</span>
                                    <span class="font-headline text-lg sm:text-xl font-bold text-action-orange">Rp 65.000 / Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a 
                                href="{{ route('beranda') }}#jadwal-interaktif" 
                                class="inline-flex items-center justify-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm px-6 py-3 rounded-xl transition-all shadow-md"
                            >
                                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                Cek Slot & Booking Lapangan Ini
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Court 3: Mini Soccer -->
                <div 
                    x-show="selectedCategory === 'all' || selectedCategory === 'minisoccer'"
                    class="group bg-surface-white rounded-3xl overflow-hidden border border-slate-200 shadow-md grid grid-cols-1 lg:grid-cols-12 transition-all hover:shadow-xl"
                >
                    <div class="lg:col-span-5 relative min-h-[280px] sm:min-h-[340px] lg:min-h-full bg-slate-900 overflow-hidden">
                        <img 
                            src="{{ asset('images/courts/minisoccer.jpg') }}" 
                            alt="Mini Soccer 7v7 Lapangin"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 min-h-[280px] sm:min-h-[340px] lg:min-h-full"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                        <span class="absolute top-4 left-4 bg-pitch-deep text-surface-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg border border-pitch-light/30">
                            Mini Soccer Outdoor
                        </span>
                        <span class="absolute bottom-4 right-4 bg-surface-white/95 text-on-surface text-xs font-bold px-3 py-1 rounded-md backdrop-blur-md shadow">
                            50 × 30 Meter
                        </span>
                    </div>

                    <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-bold text-pitch-emerald uppercase tracking-wider">Format 7 vs 7</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span class="text-xs text-on-surface-variant font-medium">Dilengkapi Reschedule Cuaca</span>
                            </div>
                            <h2 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface mb-3">
                                Mini Soccer Arena 7v7 (FIFA Standard Infill Turf)
                            </h2>
                            <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-6">
                                Arena mini soccer berukuran 50x30 meter dengan rumput sintetis infill pasir silika dan butiran karet elastis ramah sendi. Penerangan 4 tiang lampu LED 800 Lux memastikan visibilitas tanding malam hari setara siaran televisi.
                            </p>

                            <!-- Spesifikasi Fasilitas -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Jenis Rumput</span>
                                    <strong class="text-on-surface">Silica Infill 50mm</strong>
                                </div>
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Lampu Sorot</span>
                                    <strong class="text-on-surface">4 Tiang Tower LED 800 Lux</strong>
                                </div>
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Perlengkapan</span>
                                    <strong class="text-on-surface">Gawang Standar & Jaring</strong>
                                </div>
                            </div>

                            <!-- Matriks Harga -->
                            <div class="bg-surface-container-low p-4 rounded-2xl flex flex-wrap items-center justify-between gap-4 border border-slate-100">
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Tarif Siang (08.00 - 16.00)</span>
                                    <span class="font-headline text-lg sm:text-xl font-bold text-pitch-deep">Rp 450.000 / Jam</span>
                                </div>
                                <div class="sm:border-l sm:border-slate-300 sm:pl-4">
                                    <span class="text-xs text-on-surface-variant block">Prime Time Malam (17.00 - 23.00)</span>
                                    <span class="font-headline text-lg sm:text-xl font-bold text-action-orange">Rp 650.000 / Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a 
                                href="{{ route('beranda') }}#jadwal-interaktif" 
                                class="inline-flex items-center justify-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm px-6 py-3 rounded-xl transition-all shadow-md"
                            >
                                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                Cek Slot & Booking Lapangan Ini
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Court 4: Padel Court -->
                <div 
                    x-show="selectedCategory === 'all' || selectedCategory === 'padel'"
                    class="group bg-surface-white rounded-3xl overflow-hidden border border-slate-200 shadow-md grid grid-cols-1 lg:grid-cols-12 transition-all hover:shadow-xl"
                >
                    <div class="lg:col-span-5 relative min-h-[280px] sm:min-h-[340px] lg:min-h-full bg-slate-900 overflow-hidden">
                        <img 
                            src="{{ asset('images/courts/padel.jpg') }}" 
                            alt="Padel Court Glass Lapangin"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 min-h-[280px] sm:min-h-[340px] lg:min-h-full"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                        <span class="absolute top-4 left-4 bg-pitch-deep text-surface-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg border border-pitch-light/30">
                            Padel Tennis
                        </span>
                        <span class="absolute bottom-4 right-4 bg-surface-white/95 text-on-surface text-xs font-bold px-3 py-1 rounded-md backdrop-blur-md shadow">
                            20 × 10 Meter
                        </span>
                    </div>

                    <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-bold text-pitch-emerald uppercase tracking-wider">Panoramic Glass</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span class="text-xs text-on-surface-variant font-medium">Olahraga Paling Populer</span>
                            </div>
                            <h2 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface mb-3">
                                Padel Court Panoramic Glass (Blue Textured Turf)
                            </h2>
                            <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-6">
                                Court padel berstandar federasi dengan dinding kaca tempered 12mm tanpa tiang pembatas sudut (*full panoramic view*). Lantai rumput sintetis biru bertekstur rapat menjamin pantulan bola akurat dan dinamika tanding yang seru.
                            </p>

                            <!-- Spesifikasi Fasilitas -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Dinding Kaca</span>
                                    <strong class="text-on-surface">Tempered Glass 12mm</strong>
                                </div>
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Lantai Lapangan</span>
                                    <strong class="text-on-surface">Blue Textured Padel Turf</strong>
                                </div>
                                <div class="bg-surface-container-low p-3 rounded-xl text-xs">
                                    <span class="text-on-surface-variant block font-medium">Penyewaan Alat</span>
                                    <strong class="text-on-surface">Tersedia Raket & Bola</strong>
                                </div>
                            </div>

                            <!-- Matriks Harga -->
                            <div class="bg-surface-container-low p-4 rounded-2xl flex flex-wrap items-center justify-between gap-4 border border-slate-100">
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Tarif Siang (08.00 - 16.00)</span>
                                    <span class="font-headline text-lg sm:text-xl font-bold text-pitch-deep">Rp 250.000 / Jam</span>
                                </div>
                                <div class="sm:border-l sm:border-slate-300 sm:pl-4">
                                    <span class="text-xs text-on-surface-variant block">Prime Time Malam (17.00 - 23.00)</span>
                                    <span class="font-headline text-lg sm:text-xl font-bold text-action-orange">Rp 330.000 / Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a 
                                href="{{ route('beranda') }}#jadwal-interaktif" 
                                class="inline-flex items-center justify-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm px-6 py-3 rounded-xl transition-all shadow-md"
                            >
                                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                Cek Slot & Booking Lapangan Ini
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- FOOTER COMPONENT -->
    <x-footer />
</body>
</html>
