<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lapangin - Sistem booking lapangan olahraga online real-time. Futsal, Badminton, Mini Soccer, Padel. Anti double-booking dengan slot lock 10 menit.">
    <meta name="keywords" content="booking lapangan, sewa lapangan futsal, badminton, mini soccer, padel, lapangin, reservasi olahraga">
    <title>{{ config('app.name', 'Lapangin') }} - Sistem Booking Lapangan Olahraga Real-Time</title>

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
    x-data="bookingApp()"
    class="bg-surface font-body text-on-surface antialiased selection:bg-secondary-container selection:text-on-secondary-container"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="beranda" />

    <main class="w-full pt-20 bg-surface min-h-[calc(100vh-80px)]">
        
        <!-- SECTION 1: HERO SECTION -->
        <section id="beranda" class="relative w-full bg-arena-night text-surface-container-lowest overflow-hidden pb-16 pt-8">
            <!-- Ambient Glow Background Graphics -->
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-pitch-emerald/20 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 -left-20 w-80 h-80 rounded-full bg-action-orange/15 blur-3xl pointer-events-none"></div>

            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8 pt-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    
                    <!-- Hero Left Column: Headline, Subhead & Proof Metrics -->
                    <div class="lg:col-span-7 flex flex-col gap-6 z-10">
                        <div class="inline-flex items-center gap-2 bg-surface-white/10 px-3.5 py-1 rounded-full w-fit backdrop-blur-sm border border-surface-white/10">
                            <span class="w-2.5 h-2.5 rounded-full bg-pitch-light animate-ping"></span>
                            <span class="text-xs font-semibold text-pitch-light uppercase tracking-wider">
                                Sistem Reservasi Real-Time • Anti Double-Booking
                            </span>
                        </div>

                        <h1 class="font-headline text-4xl sm:text-5xl lg:text-6xl text-surface-white tracking-tight uppercase font-bold leading-tight">
                            Ajak Tim, <span class="text-pitch-light">Pilih Jam,</span><br>
                            <span class="text-action-orange">Langsung Main!</span>
                        </h1>

                        <p class="font-body text-base sm:text-lg text-surface-container-highest max-w-xl leading-relaxed">
                            Booking 4 kategori arena berstandar turnamen nasional & internasional dalam hitungan detik. Kunci slot 10 menit tanpa takut bentrok jadwal, didukung pencahayaan LED 800 Lux dan sistem pembayaran otomatis terverifikasi.
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="#jadwal-interaktif" class="inline-flex items-center justify-center gap-2 bg-action-orange hover:bg-tertiary-container text-surface-white font-bold text-sm px-6 py-3.5 rounded-lg shadow-lg shadow-action-orange/20 transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                Pilih Jam Main Sekarang
                            </a>
                            <a href="{{ route('lapangan') }}" class="inline-flex items-center justify-center gap-2 bg-surface-white/10 hover:bg-surface-white/20 text-surface-white font-semibold text-sm px-5 py-3.5 rounded-lg transition-colors border border-surface-white/15 whitespace-nowrap">
                                <span class="material-symbols-outlined text-[20px]">sports</span>
                                Jelajahi Lapangan
                            </a>
                        </div>

                        <!-- Proof Metrics Bar (Aligned with PRD KPIs) -->
                        <div class="grid grid-cols-3 gap-3 pt-4">
                            <div class="bg-surface-white/5 p-4 rounded-xl backdrop-blur-sm border border-surface-white/10">
                                <span class="text-xs text-surface-container-highest block uppercase font-medium">Jam Operasional</span>
                                <span class="font-headline text-lg sm:text-xl text-surface-white font-bold">08.00–23.00</span>
                            </div>
                            <div class="bg-surface-white/5 p-4 rounded-xl backdrop-blur-sm border border-surface-white/10">
                                <span class="text-xs text-surface-container-highest block uppercase font-medium">Total Lapangan</span>
                                <span class="font-headline text-lg sm:text-xl text-pitch-light font-bold">4 Kategori</span>
                            </div>
                            <div class="bg-surface-white/5 p-4 rounded-xl backdrop-blur-sm border border-surface-white/10">
                                <span class="text-xs text-surface-container-highest block uppercase font-medium">Lock Guarantee</span>
                                <span class="font-headline text-lg sm:text-xl text-action-amber font-bold">10 Menit</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Right Column: Graphic Showcase with Active Match Badge -->
                    <div class="lg:col-span-5 relative">
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-pitch-deep border border-pitch-light/20">
                            <img 
                                src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80" 
                                alt="Pertandingan Futsal Lapangin Arena" 
                                class="w-full h-[400px] object-cover"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-arena-night via-arena-night/30 to-transparent"></div>

                            <!-- Live Active Match Badge Floating -->
                            <div class="absolute bottom-4 left-4 right-4 bg-arena-night/90 backdrop-blur-md p-3.5 rounded-xl flex items-center justify-between border border-surface-white/15 shadow-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-pitch-emerald flex items-center justify-center text-surface-white">
                                        <span class="material-symbols-outlined text-[22px]">sports_soccer</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-surface-white leading-tight">Mini Soccer A • Court 1</h4>
                                        <p class="text-xs text-pitch-light flex items-center gap-1.5 font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-pitch-light animate-pulse"></span>
                                            Live: Sparring Sore Sedang Berlangsung
                                        </p>
                                    </div>
                                </div>
                                <span class="bg-surface-white/15 text-surface-white text-xs font-semibold px-2.5 py-1 rounded">
                                    16:00 - 18:00
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION 2: QUICK SEARCH FILTER TRAY -->
        <section class="w-full max-w-7xl mx-auto px-4 sm:px-8 -mt-8 relative z-20">
            <div class="bg-surface-white rounded-2xl shadow-xl p-5 lg:p-6 border border-slate-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px] text-pitch-emerald">sports</span> 
                            Pilih Cabang
                        </label>
                        <select 
                            x-model="currentSport"
                            @change="selectSport(currentSport)"
                            class="w-full bg-surface-container-low text-on-surface py-2.5 px-3 rounded-lg text-sm font-medium border border-transparent focus:border-pitch-emerald focus:outline-none focus:bg-surface-white transition-all cursor-pointer"
                        >
                            <option value="futsal">Futsal (Interlock & Vinyl)</option>
                            <option value="badminton">Badminton (BWF Vinyl)</option>
                            <option value="minisoccer">Mini Soccer (Sintetis 7v7)</option>
                            <option value="padel">Padel Court (Panoramic Glass)</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px] text-pitch-emerald">event</span> 
                            Tanggal Main
                        </label>
                        <input 
                            type="date" 
                            x-model="selectedDate"
                            class="w-full bg-surface-container-low text-on-surface py-2 px-3 rounded-lg text-sm font-medium border border-transparent focus:border-pitch-emerald focus:outline-none focus:bg-surface-white transition-all"
                        >
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px] text-pitch-emerald">schedule</span> 
                            Kategori Jam
                        </label>
                        <select 
                            x-model="filterTimeType"
                            class="w-full bg-surface-container-low text-on-surface py-2.5 px-3 rounded-lg text-sm font-medium border border-transparent focus:border-pitch-emerald focus:outline-none focus:bg-surface-white transition-all cursor-pointer"
                        >
                            <option value="all">Semua Jam (08.00 - 23.00)</option>
                            <option value="regular">Siang / Reguler (08.00 - 16.00)</option>
                            <option value="prime">Prime Time Malam (17.00 - 23.00)</option>
                        </select>
                    </div>

                    <div>
                        <a 
                            href="#jadwal-interaktif" 
                            class="w-full bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 transition-all shadow-sm whitespace-nowrap"
                        >
                            <span class="material-symbols-outlined text-[20px]">filter_alt</span>
                            Tampilkan Jadwal
                        </a>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION 3: VENUE COURTS CATALOG -->
        <section id="katalog" class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs text-action-orange uppercase tracking-wider font-bold block mb-1">Katalog Lapangan</span>
                    <h2 class="font-headline text-3xl text-on-surface tracking-tight uppercase font-bold">Pilih Lapanganmu</h2>
                </div>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-on-surface-variant max-w-md hidden sm:block">
                        Spesifikasi standar turnamen nasional & internasional. Lengkap dengan pencahayaan LED anti-glare.
                    </p>
                    <a href="{{ route('lapangan') }}" class="text-xs font-bold text-pitch-deep hover:text-pitch-emerald flex items-center gap-1 underline underline-offset-4 whitespace-nowrap">
                        Lihat Detail Semua Lapangan &rarr;
                    </a>
                </div>
            </div>

            <!-- Court Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Futsal -->
                <div class="group bg-surface-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between">
                    <div>
                        <div class="relative aspect-video overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1529900240041-22f114d18e40?auto=format&fit=crop&w=600&q=80" 
                                alt="Lapangan Futsal A"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                            <span class="absolute top-3 left-3 bg-pitch-deep text-surface-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow">
                                Futsal
                            </span>
                            <span class="absolute bottom-3 right-3 bg-surface-white/95 text-on-surface text-xs font-semibold px-2 py-0.5 rounded backdrop-blur-sm">
                                25 × 15 m
                            </span>
                        </div>
                        <div class="p-5 flex flex-col gap-2">
                            <h3 class="font-headline text-lg font-bold text-on-surface group-hover:text-primary transition-colors">
                                Lapangan Futsal A
                            </h3>
                            <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> 
                                Rumput Sintetis Monofilament 5cm
                            </p>
                            <div class="grid grid-cols-2 gap-2 mt-2 pt-2 bg-surface-container-low p-2.5 rounded-xl border border-slate-100">
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Siang (08-16)</span>
                                    <span class="text-sm text-pitch-deep font-bold">Rp 150.000</span>
                                </div>
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Malam (17-23)</span>
                                    <span class="text-sm text-action-orange font-bold">Rp 220.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 pt-0">
                        <a 
                            href="#jadwal-interaktif" 
                            @click="selectSport('futsal')"
                            class="w-full bg-pitch-surface hover:bg-secondary-container text-pitch-deep font-bold text-sm py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors whitespace-nowrap"
                        >
                            Pilih Jam / Slot <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Badminton -->
                <div class="group bg-surface-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between">
                    <div>
                        <div class="relative aspect-video overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=600&q=80" 
                                alt="Badminton Court 1-4"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                            <span class="absolute top-3 left-3 bg-pitch-deep text-surface-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow">
                                Badminton
                            </span>
                            <span class="absolute bottom-3 right-3 bg-surface-white/95 text-on-surface text-xs font-semibold px-2 py-0.5 rounded backdrop-blur-sm">
                                13.4 × 6.1 m
                            </span>
                        </div>
                        <div class="p-5 flex flex-col gap-2">
                            <h3 class="font-headline text-lg font-bold text-on-surface group-hover:text-primary transition-colors">
                                Badminton Court 1–4
                            </h3>
                            <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> 
                                Karpet Karakal BWF Approved
                            </p>
                            <div class="grid grid-cols-2 gap-2 mt-2 pt-2 bg-surface-container-low p-2.5 rounded-xl border border-slate-100">
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Siang (08-16)</span>
                                    <span class="text-sm text-pitch-deep font-bold">Rp 45.000</span>
                                </div>
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Malam (17-23)</span>
                                    <span class="text-sm text-action-orange font-bold">Rp 65.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 pt-0">
                        <a 
                            href="#jadwal-interaktif" 
                            @click="selectSport('badminton')"
                            class="w-full bg-pitch-surface hover:bg-secondary-container text-pitch-deep font-bold text-sm py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors whitespace-nowrap"
                        >
                            Pilih Jam / Slot <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Mini Soccer -->
                <div class="group bg-surface-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between">
                    <div>
                        <div class="relative aspect-video overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1551958219-acbc608c6377?auto=format&fit=crop&w=600&q=80" 
                                alt="Mini Soccer 7v7"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                            <span class="absolute top-3 left-3 bg-pitch-deep text-surface-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow">
                                Mini Soccer
                            </span>
                            <span class="absolute bottom-3 right-3 bg-surface-white/95 text-on-surface text-xs font-semibold px-2 py-0.5 rounded backdrop-blur-sm">
                                50 × 30 m
                            </span>
                        </div>
                        <div class="p-5 flex flex-col gap-2">
                            <h3 class="font-headline text-lg font-bold text-on-surface group-hover:text-primary transition-colors">
                                Mini Soccer 7v7
                            </h3>
                            <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> 
                                FIFA Standard Turf + 800 Lux
                            </p>
                            <div class="grid grid-cols-2 gap-2 mt-2 pt-2 bg-surface-container-low p-2.5 rounded-xl border border-slate-100">
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Siang (08-16)</span>
                                    <span class="text-sm text-pitch-deep font-bold">Rp 450.000</span>
                                </div>
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Malam (17-23)</span>
                                    <span class="text-sm text-action-orange font-bold">Rp 650.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 pt-0">
                        <a 
                            href="#jadwal-interaktif" 
                            @click="selectSport('minisoccer')"
                            class="w-full bg-pitch-surface hover:bg-secondary-container text-pitch-deep font-bold text-sm py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors whitespace-nowrap"
                        >
                            Pilih Jam / Slot <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- Card 4: Padel Court -->
                <div class="group bg-surface-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between">
                    <div>
                        <div class="relative aspect-video overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1554068865-24cecd4e34b8?auto=format&fit=crop&w=600&q=80" 
                                alt="Padel Court Glass"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                            <span class="absolute top-3 left-3 bg-pitch-deep text-surface-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow">
                                Padel
                            </span>
                            <span class="absolute bottom-3 right-3 bg-surface-white/95 text-on-surface text-xs font-semibold px-2 py-0.5 rounded backdrop-blur-sm">
                                20 × 10 m
                            </span>
                        </div>
                        <div class="p-5 flex flex-col gap-2">
                            <h3 class="font-headline text-lg font-bold text-on-surface group-hover:text-primary transition-colors">
                                Padel Court Glass
                            </h3>
                            <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> 
                                Kaca Tempered 12mm & Blue Turf
                            </p>
                            <div class="grid grid-cols-2 gap-2 mt-2 pt-2 bg-surface-container-low p-2.5 rounded-xl border border-slate-100">
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Siang (08-16)</span>
                                    <span class="text-sm text-pitch-deep font-bold">Rp 250.000</span>
                                </div>
                                <div>
                                    <span class="text-xs text-on-surface-variant block">Malam (17-23)</span>
                                    <span class="text-sm text-action-orange font-bold">Rp 330.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 pt-0">
                        <a 
                            href="#jadwal-interaktif" 
                            @click="selectSport('padel')"
                            class="w-full bg-pitch-surface hover:bg-secondary-container text-pitch-deep font-bold text-sm py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors whitespace-nowrap"
                        >
                            Pilih Jam / Slot <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <!-- SECTION 4: INTERACTIVE BOOKING SCHEDULE ENGINE (#jadwal-interaktif) -->
        <section id="jadwal-interaktif" class="w-full bg-arena-night text-surface-container-lowest py-16 px-4 sm:px-8 relative">
            <div class="w-full max-w-7xl mx-auto">
                
                <div class="flex flex-col gap-2 mb-8">
                    <div class="flex items-center gap-2">
                        <span class="bg-action-orange text-surface-white text-xs px-2.5 py-0.5 rounded uppercase font-bold tracking-wider">
                            Jadwal Real-Time
                        </span>
                        <span class="text-pitch-light text-xs font-semibold flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-pitch-light animate-pulse"></span> Diperbarui otomatis
                        </span>
                    </div>
                    <h2 class="font-headline text-3xl sm:text-4xl text-surface-white tracking-tight uppercase font-bold">
                        PILIH JAM MAIN
                    </h2>
                    <p class="font-body text-sm sm:text-base text-surface-container-highest max-w-2xl leading-relaxed">
                        Ketuk slot jam yang diinginkan (bisa pilih lebih dari satu jam berurutan). Jam malam dan weekend bertarif prime time. Slot terkunci selama 10 menit saat Anda melanjutkan ke checkout.
                    </p>
                </div>

                <!-- Booking Interface Shell -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Left Schedule Timetable Matrix (Col 8) -->
                    <div class="lg:col-span-8 bg-surface-white text-on-surface rounded-2xl p-5 md:p-6 shadow-xl border border-slate-200">
                        
                        <!-- Arena Selector Pills (Sebaris & Responsif) -->
                        <div class="flex flex-wrap gap-2 mb-5">
                            <button 
                                type="button"
                                @click="selectSport('futsal')"
                                :class="currentSport === 'futsal' ? 'bg-pitch-deep text-surface-white shadow' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                class="text-sm font-bold px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                            >
                                Lapangan Futsal A
                            </button>
                            <button 
                                type="button"
                                @click="selectSport('badminton')"
                                :class="currentSport === 'badminton' ? 'bg-pitch-deep text-surface-white shadow' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                class="text-sm font-bold px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                            >
                                Badminton Court 1–4
                            </button>
                            <button 
                                type="button"
                                @click="selectSport('minisoccer')"
                                :class="currentSport === 'minisoccer' ? 'bg-pitch-deep text-surface-white shadow' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                class="text-sm font-bold px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                            >
                                Mini Soccer 7v7
                            </button>
                            <button 
                                type="button"
                                @click="selectSport('padel')"
                                :class="currentSport === 'padel' ? 'bg-pitch-deep text-surface-white shadow' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                class="text-sm font-bold px-4 py-2 rounded-lg transition-all whitespace-nowrap"
                            >
                                Padel Court
                            </button>
                        </div>

                        <!-- Date Selector Horizontal Scroll -->
                        <div class="mb-5 overflow-x-auto pb-2">
                            <div class="flex gap-2 min-w-max">
                                <template x-for="(day, index) in dateOptions" :key="index">
                                    <button 
                                        type="button"
                                        @click="selectDate(day.date)"
                                        :class="selectedDate === day.date ? 'bg-pitch-deep text-surface-white shadow' : 'bg-surface-container-low hover:bg-surface-container text-on-surface'"
                                        class="px-4 py-2 rounded-xl text-center flex flex-col items-center min-w-[72px] transition-all whitespace-nowrap"
                                    >
                                        <span :class="selectedDate === day.date ? 'text-pitch-light' : (day.isWeekend ? 'text-action-orange' : 'text-on-surface-variant')" class="text-xs font-bold block" x-text="day.dayLabel"></span>
                                        <span class="text-sm font-bold" x-text="day.dateLabel"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Slot Status Legend (PRD Aligned) -->
                        <div class="flex flex-wrap items-center gap-4 py-2.5 mb-5 bg-surface-container-low px-4 rounded-xl border border-slate-100 text-xs font-medium">
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-3 h-3 rounded bg-pitch-surface border border-pitch-light"></span>
                                <span>Tersedia (Siang)</span>
                            </div>
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-3 h-3 rounded bg-action-glow border border-action-amber"></span>
                                <span>Jam Ramai / Prime</span>
                            </div>
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-3 h-3 rounded bg-action-orange border border-action-orange"></span>
                                <span class="font-bold text-action-orange">Dipilih Anda</span>
                            </div>
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-3 h-3 rounded bg-court-booked-surface border border-slate-200"></span>
                                <span class="text-court-booked line-through">Terisi / Booked</span>
                            </div>
                        </div>

                        <!-- Slot Matrix Time Grid (Multi-slot selection with alpine.js) -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-2.5">
                            <template x-for="slot in activeCourtSlots" :key="slot.id">
                                <!-- Booked Slot -->
                                <div 
                                    x-show="slot.status === 'booked'"
                                    class="p-2.5 rounded-xl text-center bg-court-booked-surface text-court-booked cursor-not-allowed border border-slate-200 select-none"
                                >
                                    <span class="text-sm font-bold block line-through whitespace-nowrap" x-text="slot.time"></span>
                                    <span class="text-xs">Terisi</span>
                                </div>

                                <!-- Available or Selected Slot -->
                                <button 
                                    x-show="slot.status !== 'booked'"
                                    type="button"
                                    @click="toggleSlot(slot)"
                                    :class="{
                                        'bg-action-orange text-surface-white ring-2 ring-action-orange shadow-md transform scale-[1.02]': isSlotSelected(slot),
                                        'bg-action-glow text-on-surface hover:bg-action-amber/30 border border-action-amber/40': !isSlotSelected(slot) && slot.isPrime,
                                        'bg-pitch-surface text-pitch-deep hover:bg-secondary-container border border-pitch-light/30': !isSlotSelected(slot) && !slot.isPrime
                                    }"
                                    class="p-2.5 rounded-xl text-center transition-all cursor-pointer font-body flex flex-col items-center justify-center"
                                >
                                    <span class="text-sm font-bold block whitespace-nowrap" x-text="slot.time"></span>
                                    <span 
                                        :class="isSlotSelected(slot) ? 'text-surface-white' : (slot.isPrime ? 'text-action-orange font-bold' : 'text-pitch-deep font-medium')"
                                        class="text-xs mt-0.5 whitespace-nowrap"
                                        x-text="formatCurrency(slot.price)"
                                    ></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Right Column: Sticky Real-Time Booking Tray (Col 4) -->
                    <div class="lg:col-span-4 bg-surface-white text-on-surface rounded-2xl p-6 shadow-2xl sticky top-24 border border-slate-200">
                        
                        <!-- Pessimistic Lock Banner (Aligned with PRD FR-12) -->
                        <div class="flex items-center justify-between pb-2 mb-4 bg-surface-container-low p-3 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-pitch-emerald">lock_clock</span>
                                <span class="text-xs font-bold text-on-surface">Slot Lock Guarantee</span>
                            </div>
                            <span class="text-xs text-action-orange font-bold bg-action-glow px-2 py-0.5 rounded-md">
                                10 Menit
                            </span>
                        </div>

                        <h3 class="font-headline text-lg font-bold text-on-surface mb-1">Ringkasan Reservasi</h3>
                        <p class="text-xs text-on-surface-variant mb-4" x-text="summaryTitle"></p>

                        <!-- Selected Slots List -->
                        <div class="min-h-[110px] flex flex-col gap-2 mb-4">
                            <!-- Empty State -->
                            <div 
                                x-show="selectedSlots.length === 0" 
                                class="p-4 bg-surface-container-low rounded-xl text-center text-on-surface-variant text-xs leading-relaxed border border-dashed border-slate-200"
                            >
                                <span class="material-symbols-outlined text-[28px] text-outline mb-1 block">touch_app</span>
                                Belum ada jam yang dipilih.<br>
                                Ketuk satu atau beberapa slot jam di tabel sebelah kiri.
                            </div>

                            <!-- List of chosen slots -->
                            <template x-for="(item, idx) in selectedSlots" :key="idx">
                                <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-xl text-xs font-medium border border-slate-100">
                                    <div class="flex items-center gap-1.5 text-on-surface">
                                        <span class="w-2 h-2 rounded-full bg-pitch-emerald"></span>
                                        <span class="font-bold whitespace-nowrap" x-text="item.time"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-action-orange font-bold whitespace-nowrap" x-text="formatCurrency(item.price)"></span>
                                        <button 
                                            type="button" 
                                            @click="toggleSlot(item)" 
                                            class="text-outline hover:text-red-500 transition-colors"
                                            title="Hapus slot"
                                        >
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Total Price Block -->
                        <div class="bg-arena-night text-surface-white p-4 rounded-xl mb-4 shadow-inner">
                            <div class="flex justify-between items-center text-surface-container-highest text-xs mb-1">
                                <span>Durasi Main</span>
                                <span class="font-bold" x-text="selectedSlots.length + ' Jam'"></span>
                            </div>
                            <div class="flex justify-between items-center text-surface-white pt-1 border-t border-surface-white/10">
                                <span class="text-sm font-semibold">Total Tagihan</span>
                                <span class="font-headline text-xl text-pitch-light font-bold whitespace-nowrap" x-text="formatCurrency(totalPrice)"></span>
                            </div>
                        </div>

                        <!-- Dual Action CTA Buttons (PRD Compliant) -->
                        <div class="flex flex-col gap-2.5">
                            <button 
                                type="button"
                                :disabled="selectedSlots.length === 0"
                                @click="proceedBooking()"
                                class="w-full bg-action-orange disabled:opacity-40 disabled:cursor-not-allowed hover:bg-tertiary-container text-surface-white font-bold text-sm py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md transform active:scale-95 whitespace-nowrap"
                            >
                                <span class="material-symbols-outlined text-[20px]">flash_on</span>
                                Kunci Slot & Bayar Otomatis
                            </button>

                            <a 
                                :href="whatsAppLink"
                                target="_blank"
                                class="w-full bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm py-3 rounded-xl flex items-center justify-center gap-2 transition-colors whitespace-nowrap"
                            >
                                <span class="material-symbols-outlined text-[20px]">chat</span>
                                Booking Cepat via WhatsApp
                            </a>
                        </div>

                        <p class="text-[11px] text-on-surface-variant text-center mt-3 leading-relaxed">
                            Sistem secara otomatis mengamankan slot Anda selama 10 menit agar tidak diambil pengguna lain saat proses checkout.
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 5: FASILITAS PREMIUM SECTION (PRD ALIGNED) -->
        <section id="fasilitas" class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-16">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-xs text-pitch-emerald uppercase tracking-wider font-bold block mb-1">Kenyamanan Maksimal</span>
                <h2 class="font-headline text-3xl text-on-surface tracking-tight uppercase font-bold">Bukan Cuma Lapangan</h2>
                <p class="text-sm text-on-surface-variant mt-2">
                    Fasilitas penunjang lengkap kelas profesional disiapkan agar tim Anda bermain nyaman, aman, dan siap tempur.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Fac 1 -->
                <div class="bg-surface-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-200 flex flex-col gap-2">
                    <div class="w-12 h-12 rounded-xl bg-pitch-surface text-pitch-deep flex items-center justify-center mb-2">
                        <span class="material-symbols-outlined text-[28px]">local_parking</span>
                    </div>
                    <h3 class="font-headline text-lg font-bold text-on-surface">Parkir Luas & Aman</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Kapasitas hingga 60 mobil dan 200 motor dengan pantauan CCTV 24 jam serta penjaga resmi bebas pungli liar.
                    </p>
                </div>

                <!-- Fac 2 -->
                <div class="bg-surface-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-200 flex flex-col gap-2">
                    <div class="w-12 h-12 rounded-xl bg-pitch-surface text-pitch-deep flex items-center justify-center mb-2">
                        <span class="material-symbols-outlined text-[28px]">shower</span>
                    </div>
                    <h3 class="font-headline text-lg font-bold text-on-surface">Shower & Loker Digital</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Kamar bilas air hangat higienis, pengering rambut, dan fasilitas loker digital gratis untuk seluruh pemain.
                    </p>
                </div>

                <!-- Fac 3 -->
                <div class="bg-surface-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-200 flex flex-col gap-2">
                    <div class="w-12 h-12 rounded-xl bg-pitch-surface text-pitch-deep flex items-center justify-center mb-2">
                        <span class="material-symbols-outlined text-[28px]">restaurant</span>
                    </div>
                    <h3 class="font-headline text-lg font-bold text-on-surface">Kantin & Musala Bersih</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Penyedia hidrasi isotonik, kopi artisan, area santai pasca-tanding, serta musala ber-AC yang nyaman.
                    </p>
                </div>

                <!-- Fac 4 -->
                <div class="bg-surface-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-200 flex flex-col gap-2">
                    <div class="w-12 h-12 rounded-xl bg-pitch-surface text-pitch-deep flex items-center justify-center mb-2">
                        <span class="material-symbols-outlined text-[28px]">lightbulb</span>
                    </div>
                    <h3 class="font-headline text-lg font-bold text-on-surface">Lampu LED 800 Lux</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Pencahayaan broadcast-grade anti-silau di seluruh sudut. Pengalaman tanding malam hari terasa seperti siang hari.
                    </p>
                </div>
            </div>
        </section>

        <!-- SECTION 6: ALUR BOOKING 4 LANGKAH (SESUAI PRD WORKFLOW) -->
        <section id="cara-booking" class="w-full bg-surface-white py-16 border-y border-slate-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-xl mx-auto mb-12">
                    <span class="text-xs text-action-orange uppercase tracking-wider font-bold block mb-1">Proses Mudah</span>
                    <h2 class="font-headline text-3xl text-on-surface tracking-tight uppercase font-bold">4 Langkah Reservasi</h2>
                    <p class="text-sm text-on-surface-variant mt-2">
                        Booking lapangan olahraga di Lapangin selesai dalam waktu kurang dari 2 menit.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="bg-surface-container-low p-6 rounded-2xl relative border border-slate-100">
                        <span class="font-headline text-4xl text-pitch-deep/10 absolute top-3 right-4 font-bold select-none">01</span>
                        <div class="w-10 h-10 rounded-xl bg-pitch-deep text-surface-white flex items-center justify-center mb-4 font-bold shadow">
                            1
                        </div>
                        <h3 class="text-base font-bold text-on-surface mb-1">Pilih Arena & Jam</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Pilih cabang olahraga, tentukan tanggal pertandingan, dan pilih satu atau beberapa jam yang kosong di kalender.
                        </p>
                    </div>

                    <div class="bg-surface-container-low p-6 rounded-2xl relative border border-slate-100">
                        <span class="font-headline text-4xl text-pitch-deep/10 absolute top-3 right-4 font-bold select-none">02</span>
                        <div class="w-10 h-10 rounded-xl bg-action-orange text-surface-white flex items-center justify-center mb-4 font-bold shadow">
                            2
                        </div>
                        <h3 class="text-base font-bold text-on-surface mb-1">Slot Lock 10 Menit</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Sistem secara instan mengunci slot pilihan Anda selama 10 menit agar tidak dapat dipesan atau diserobot orang lain.
                        </p>
                    </div>

                    <div class="bg-surface-container-low p-6 rounded-2xl relative border border-slate-100">
                        <span class="font-headline text-4xl text-pitch-deep/10 absolute top-3 right-4 font-bold select-none">03</span>
                        <div class="w-10 h-10 rounded-xl bg-pitch-deep text-surface-white flex items-center justify-center mb-4 font-bold shadow">
                            3
                        </div>
                        <h3 class="text-base font-bold text-on-surface mb-1">Bayar Otomatis</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Bayar instan via QRIS, Virtual Account, E-Wallet otomatis, atau gunakan opsi upload bukti transfer manual.
                        </p>
                    </div>

                    <div class="bg-surface-container-low p-6 rounded-2xl relative border border-slate-100">
                        <span class="font-headline text-4xl text-pitch-deep/10 absolute top-3 right-4 font-bold select-none">04</span>
                        <div class="w-10 h-10 rounded-xl bg-pitch-emerald text-surface-white flex items-center justify-center mb-4 font-bold shadow">
                            4
                        </div>
                        <h3 class="text-base font-bold text-on-surface mb-1">Tiket QR & Main!</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Invoice PDF dan kode reservasi unik langsung terkirim ke email Anda. Tunjukkan ke staf kasir untuk cek-in!
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 7: SUASANA ARENA (GALERI FOTO) -->
        <section class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs text-pitch-emerald uppercase tracking-wider font-bold block mb-1">Galeri Foto</span>
                    <h2 class="font-headline text-3xl text-on-surface tracking-tight uppercase font-bold">Suasana Arena</h2>
                </div>
                <p class="text-sm text-on-surface-variant">
                    Kualitas arena terawat dan standar fasilitas bersertifikasi resmi untuk kepuasan bermain Anda.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="relative rounded-2xl overflow-hidden aspect-square group shadow-sm">
                    <img 
                        src="https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=600&q=80" 
                        alt="Hall Indoor Utama Lapangin"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-arena-night/80 via-transparent to-transparent flex items-end p-4">
                        <span class="text-surface-white text-xs font-bold">Hall Indoor Utama</span>
                    </div>
                </div>

                <div class="relative rounded-2xl overflow-hidden aspect-square group shadow-sm">
                    <img 
                        src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=600&q=80" 
                        alt="Court Badminton BWF"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-arena-night/80 via-transparent to-transparent flex items-end p-4">
                        <span class="text-surface-white text-xs font-bold">Court Badminton BWF</span>
                    </div>
                </div>

                <div class="relative rounded-2xl overflow-hidden aspect-square group shadow-sm">
                    <img 
                        src="https://images.unsplash.com/photo-1518604666860-9ed391f76460?auto=format&fit=crop&w=600&q=80" 
                        alt="Mini Soccer Malam"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-arena-night/80 via-transparent to-transparent flex items-end p-4">
                        <span class="text-surface-white text-xs font-bold">Mini Soccer Malam</span>
                    </div>
                </div>

                <div class="relative rounded-2xl overflow-hidden aspect-square group shadow-sm">
                    <img 
                        src="https://images.unsplash.com/photo-1505666287802-931dc83948e9?auto=format&fit=crop&w=600&q=80" 
                        alt="Court Padel & Basket"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-arena-night/80 via-transparent to-transparent flex items-end p-4">
                        <span class="text-surface-white text-xs font-bold">Court Padel & Basket</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 8: TESTIMONIALS (ULASAN RESMI SESUAI PRD) -->
        <section class="w-full bg-surface-white py-16 border-y border-slate-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-xl mx-auto mb-12">
                    <span class="text-xs text-pitch-emerald uppercase tracking-wider font-bold block mb-1">Ulasan Pemain</span>
                    <h2 class="font-headline text-3xl text-on-surface tracking-tight uppercase font-bold">Kata Tim Langganan</h2>
                    <p class="text-sm text-on-surface-variant mt-2">
                        Ulasan terverifikasi langsung dari pelanggan yang telah menyelesaikan pertandingan di Lapangin Hub.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Testi 1 -->
                    <div class="bg-surface-container-low p-6 rounded-2xl flex flex-col justify-between border border-slate-100">
                        <div>
                            <div class="flex items-center text-action-amber mb-3">
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            </div>
                            <p class="text-sm text-on-surface italic mb-4 leading-relaxed">
                                “Booking gampang banget, tinggal pilih jam langsung kelihatan total harganya. Slot terkunci aman, gak khawatir diserobot tim lain waktu mau transfer.”
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-200/60">
                            <div class="w-10 h-10 rounded-full bg-pitch-deep text-surface-white flex items-center justify-center font-bold">
                                T
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-on-surface">Tim FC Kantor Pajak</h4>
                                <span class="text-xs text-on-surface-variant">Member Futsal Jumat Malam</span>
                            </div>
                        </div>
                    </div>

                    <!-- Testi 2 -->
                    <div class="bg-surface-container-low p-6 rounded-2xl flex flex-col justify-between border border-slate-100">
                        <div>
                            <div class="flex items-center text-action-amber mb-3">
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            </div>
                            <p class="text-sm text-on-surface italic mb-4 leading-relaxed">
                                “Tarif pagi jauh lebih hemat buat main santai. Lampu LED-nya beneran ramah di mata, gak silau waktu ambil smash tinggi. Invoice juga langsung masuk email.”
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-200/60">
                            <div class="w-10 h-10 rounded-full bg-pitch-emerald text-surface-white flex items-center justify-center font-bold">
                                K
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-on-surface">Komunitas Badminton Pagi</h4>
                                <span class="text-xs text-on-surface-variant">Rutin 4 Court Tiap Minggu</span>
                            </div>
                        </div>
                    </div>

                    <!-- Testi 3 -->
                    <div class="bg-surface-container-low p-6 rounded-2xl flex flex-col justify-between border border-slate-100">
                        <div>
                            <div class="flex items-center text-action-amber mb-3">
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            </div>
                            <p class="text-sm text-on-surface italic mb-4 leading-relaxed">
                                “Court padel panoramic glass terbaik di kota ini! Kaca pantulnya tebal dan presisi. Cek-in di meja resepsionis tinggal scan kode booking, cepat sekali!”
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-200/60">
                            <div class="w-10 h-10 rounded-full bg-action-orange text-surface-white flex items-center justify-center font-bold">
                                D
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-on-surface">Dimas Setiawan</h4>
                                <span class="text-xs text-on-surface-variant">Pemain Padel Antar-Klub</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 9: INTERACTIVE FAQ ACCORDION (SESUAI PRD ATURAN BISNIS) -->
        <section id="faq" class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-16">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-xs text-pitch-emerald uppercase tracking-wider font-bold block mb-1">Bantuan & Aturan Main</span>
                <h2 class="font-headline text-3xl text-on-surface tracking-tight uppercase font-bold">Pertanyaan Sering Diajukan</h2>
                <p class="text-sm text-on-surface-variant mt-2">
                    Transparansi kebijakan pembatalan, refund, dan keamanan booking di Lapangin.
                </p>
            </div>

            <div class="max-w-3xl mx-auto flex flex-col gap-3">
                
                <!-- FAQ 1 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <button 
                        type="button"
                        class="w-full p-5 text-left flex items-center justify-between font-bold text-sm text-on-surface"
                        @click="activeFaq = (activeFaq === 1 ? null : 1)"
                    >
                        <span>Apakah jam yang dipilih di website pasti kosong dan anti double-booking?</span>
                        <span 
                            class="material-symbols-outlined text-pitch-deep transition-transform duration-200"
                            :class="activeFaq === 1 ? 'rotate-180' : 'rotate-0'"
                        >
                            expand_more
                        </span>
                    </button>
                    <div 
                        x-show="activeFaq === 1" 
                        class="px-5 pb-5 text-on-surface-variant text-xs leading-relaxed border-t border-slate-100 pt-3"
                    >
                        Pasti kosong! Sistem Lapangin menerapkan mekanisme <strong>Pessimistic Time-Lock 10 Menit</strong> dan database transaction. Saat Anda menekan tombol booking, slot tersebut langsung dikunci di tingkat basis data sehingga pengguna lain tidak bisa memesan jam yang sama.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <button 
                        type="button"
                        class="w-full p-5 text-left flex items-center justify-between font-bold text-sm text-on-surface"
                        @click="activeFaq = (activeFaq === 2 ? null : 2)"
                    >
                        <span>Bagaimana metode pembayaran dan proses konfirmasinya?</span>
                        <span 
                            class="material-symbols-outlined text-pitch-deep transition-transform duration-200"
                            :class="activeFaq === 2 ? 'rotate-180' : 'rotate-0'"
                        >
                            expand_more
                        </span>
                    </button>
                    <div 
                        x-show="activeFaq === 2" 
                        class="px-5 pb-5 text-on-surface-variant text-xs leading-relaxed border-t border-slate-100 pt-3"
                    >
                        Kami mendukung pembayaran instan via Payment Gateway (QRIS, BCA & Mandiri Virtual Account, GoPay, ShopeePay) dengan verifikasi otomatis via webhook. Kami juga menyediakan alternatif transfer bank manual dengan upload bukti bayar yang diverifikasi cepat oleh staf kasir.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <button 
                        type="button"
                        class="w-full p-5 text-left flex items-center justify-between font-bold text-sm text-on-surface"
                        @click="activeFaq = (activeFaq === 3 ? null : 3)"
                    >
                        <span>Bagaimana kebijakan pembatalan (cancellation) dan pengembalian dana (refund)?</span>
                        <span 
                            class="material-symbols-outlined text-pitch-deep transition-transform duration-200"
                            :class="activeFaq === 3 ? 'rotate-180' : 'rotate-0'"
                        >
                            expand_more
                        </span>
                    </button>
                    <div 
                        x-show="activeFaq === 3" 
                        class="px-5 pb-5 text-on-surface-variant text-xs leading-relaxed border-t border-slate-100 pt-3"
                    >
                        Sesuai aturan bisnis PRD Lapangin:
                        <ul class="list-disc pl-5 mt-1.5 space-y-1">
                            <li>Pembatalan <strong>&ge; 24 jam</strong> sebelum jadwal main berhak atas <strong>refund 100%</strong>.</li>
                            <li>Pembatalan <strong>6 &ndash; 24 jam</strong> sebelum jadwal main berhak atas <strong>refund 50%</strong>.</li>
                            <li>Pembatalan <strong>&lt; 6 jam</strong> sebelum jadwal main tidak mendapatkan pengembalian dana.</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <button 
                        type="button"
                        class="w-full p-5 text-left flex items-center justify-between font-bold text-sm text-on-surface"
                        @click="activeFaq = (activeFaq === 4 ? null : 4)"
                    >
                        <span>Apakah saya bisa mengajukan reschedule jadwal main?</span>
                        <span 
                            class="material-symbols-outlined text-pitch-deep transition-transform duration-200"
                            :class="activeFaq === 4 ? 'rotate-180' : 'rotate-0'"
                        >
                            expand_more
                        </span>
                    </button>
                    <div 
                        x-show="activeFaq === 4" 
                        class="px-5 pb-5 text-on-surface-variant text-xs leading-relaxed border-t border-slate-100 pt-3"
                    >
                        Bisa! Pelanggan dapat melakukan reschedule maksimal 1 kali dengan catatan slot jadwal pengganti masih tersedia dan diajukan selambat-lambatnya 12 jam sebelum waktu mulai awal.
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <button 
                        type="button"
                        class="w-full p-5 text-left flex items-center justify-between font-bold text-sm text-on-surface"
                        @click="activeFaq = (activeFaq === 5 ? null : 5)"
                    >
                        <span>Bagaimana jika terjadi cuaca ekstrem atau hujan lebat di lapangan outdoor?</span>
                        <span 
                            class="material-symbols-outlined text-pitch-deep transition-transform duration-200"
                            :class="activeFaq === 5 ? 'rotate-180' : 'rotate-0'"
                        >
                            expand_more
                        </span>
                    </button>
                    <div 
                        x-show="activeFaq === 5" 
                        class="px-5 pb-5 text-on-surface-variant text-xs leading-relaxed border-t border-slate-100 pt-3"
                    >
                        Khusus lapangan outdoor seperti Mini Soccer, jika terjadi cuaca ekstrem atau hujan deras yang menyebabkan lapangan tidak layak pakai sebelum atau saat bermain di bawah 30 menit, Anda berhak mendapatkan <strong>Reschedule Gratis</strong> ke jadwal lain tanpa penalti.
                    </div>
                </div>

            </div>
        </section>

        <!-- FLOATING QUICK WHATSAPP BUTTON -->
        <div class="fixed bottom-6 right-6 z-40">
            <a 
                :href="whatsAppLink" 
                target="_blank"
                class="bg-pitch-emerald hover:bg-pitch-deep text-surface-white p-4 rounded-full shadow-2xl flex items-center justify-center transition-all transform hover:scale-110 border-2 border-surface-white"
                title="Hubungi Admin Lapangan"
            >
                <span class="material-symbols-outlined text-[26px]">chat</span>
            </a>
        </div>

    </main>

    <!-- FOOTER COMPONENT -->
    <x-footer />

    <!-- ALPINE.JS COMPONENT LOGIC -->
    <script>
        function bookingApp() {
            return {
                mobileMenuOpen: false,
                currentSport: 'futsal',
                selectedDate: '2026-11-03',
                filterTimeType: 'all',
                selectedSlots: [],
                activeFaq: 1,

                dateOptions: [
                    { date: '2026-11-03', dayLabel: 'HARI INI', dateLabel: '3 Nov', isWeekend: false },
                    { date: '2026-11-04', dayLabel: 'RAB', dateLabel: '4 Nov', isWeekend: false },
                    { date: '2026-11-05', dayLabel: 'KAM', dateLabel: '5 Nov', isWeekend: false },
                    { date: '2026-11-06', dayLabel: 'JUM', dateLabel: '6 Nov', isWeekend: false },
                    { date: '2026-11-07', dayLabel: 'SAB', dateLabel: '7 Nov', isWeekend: true },
                    { date: '2026-11-08', dayLabel: 'MIN', dateLabel: '8 Nov', isWeekend: true },
                    { date: '2026-11-09', dayLabel: 'SEN', dateLabel: '9 Nov', isWeekend: false },
                ],

                sportCatalog: {
                    'futsal': {
                        name: 'Lapangan Futsal A',
                        regularPrice: 150000,
                        primePrice: 220000,
                        bookedSlots: ['12.00 - 13.00', '15.00 - 16.00', '19.00 - 20.00']
                    },
                    'badminton': {
                        name: 'Badminton Court 1–4',
                        regularPrice: 45000,
                        primePrice: 65000,
                        bookedSlots: ['09.00 - 10.00', '14.00 - 15.00', '18.00 - 19.00', '20.00 - 21.00']
                    },
                    'minisoccer': {
                        name: 'Mini Soccer 7v7',
                        regularPrice: 450000,
                        primePrice: 650000,
                        bookedSlots: ['16.00 - 17.00', '17.00 - 18.00', '21.00 - 22.00']
                    },
                    'padel': {
                        name: 'Padel Court Glass',
                        regularPrice: 250000,
                        primePrice: 330000,
                        bookedSlots: ['10.00 - 11.00', '16.00 - 17.00', '19.00 - 20.00']
                    }
                },

                timeSlotsConfig: [
                    { id: 1, time: '08.00 - 09.00', isPrime: false },
                    { id: 2, time: '09.00 - 10.00', isPrime: false },
                    { id: 3, time: '10.00 - 11.00', isPrime: false },
                    { id: 4, time: '11.00 - 12.00', isPrime: false },
                    { id: 5, time: '12.00 - 13.00', isPrime: false },
                    { id: 6, time: '13.00 - 14.00', isPrime: false },
                    { id: 7, time: '14.00 - 15.00', isPrime: false },
                    { id: 8, time: '15.00 - 16.00', isPrime: false },
                    { id: 9, time: '16.00 - 17.00', isPrime: false },
                    { id: 10, time: '17.00 - 18.00', isPrime: true },
                    { id: 11, time: '18.00 - 19.00', isPrime: true },
                    { id: 12, time: '19.00 - 20.00', isPrime: true },
                    { id: 13, time: '20.00 - 21.00', isPrime: true },
                    { id: 14, time: '21.00 - 22.00', isPrime: true },
                    { id: 15, time: '22.00 - 23.00', isPrime: true }
                ],

                get activeCourtSlots() {
                    const sport = this.sportCatalog[this.currentSport] || this.sportCatalog['futsal'];
                    return this.timeSlotsConfig
                        .filter(slot => {
                            if (this.filterTimeType === 'regular') return !slot.isPrime;
                            if (this.filterTimeType === 'prime') return slot.isPrime;
                            return true;
                        })
                        .map(slot => {
                            const isBooked = sport.bookedSlots.includes(slot.time);
                            const price = slot.isPrime ? sport.primePrice : sport.regularPrice;
                            return {
                                id: `${this.currentSport}_${slot.id}`,
                                time: slot.time,
                                isPrime: slot.isPrime,
                                price: price,
                                status: isBooked ? 'booked' : 'available'
                            };
                        });
                },

                get summaryTitle() {
                    const sport = this.sportCatalog[this.currentSport] || this.sportCatalog['futsal'];
                    const foundDate = this.dateOptions.find(d => d.date === this.selectedDate);
                    const dateText = foundDate ? `${foundDate.dayLabel} (${foundDate.dateLabel})` : this.selectedDate;
                    return `${sport.name} • ${dateText}`;
                },

                get totalPrice() {
                    return this.selectedSlots.reduce((sum, item) => sum + item.price, 0);
                },

                get whatsAppLink() {
                    const sport = this.sportCatalog[this.currentSport] || this.sportCatalog['futsal'];
                    if (this.selectedSlots.length === 0) {
                        return `https://wa.me/6280012345678?text=Halo%20Admin%20Lapangin%2C%20saya%20ingin%20tanya%20jadwal%20tersedia%20untuk%20${encodeURIComponent(sport.name)}`;
                    }
                    const slotTimes = this.selectedSlots.map(s => s.time).join(', ');
                    const text = `Halo Admin Lapangin, saya ingin booking:\n- Arena: ${sport.name}\n- Tanggal: ${this.selectedDate}\n- Jam: ${slotTimes}\n- Durasi: ${this.selectedSlots.length} Jam\n- Total: ${this.formatCurrency(this.totalPrice)}\n\nMohon bantu amankan slot ini. Terima kasih!`;
                    return `https://wa.me/6280012345678?text=${encodeURIComponent(text)}`;
                },

                selectSport(sportKey) {
                    this.currentSport = sportKey;
                    this.selectedSlots = [];
                },

                selectDate(dateKey) {
                    this.selectedDate = dateKey;
                    this.selectedSlots = [];
                },

                isSlotSelected(slot) {
                    return this.selectedSlots.some(s => s.id === slot.id);
                },

                toggleSlot(slot) {
                    const index = this.selectedSlots.findIndex(s => s.id === slot.id);
                    if (index > -1) {
                        this.selectedSlots.splice(index, 1);
                    } else {
                        this.selectedSlots.push({
                            id: slot.id,
                            time: slot.time,
                            price: slot.price
                        });
                    }
                },

                formatCurrency(amount) {
                    return 'Rp ' + Number(amount).toLocaleString('id-ID');
                },

                proceedBooking() {
                    if (this.selectedSlots.length === 0) return;
                    const sport = this.sportCatalog[this.currentSport] || this.sportCatalog['futsal'];
                    alert(`Slot berhasil dikunci selama 10 menit!\n\nDetail:\n- Arena: ${sport.name}\n- Tanggal: ${this.selectedDate}\n- Durasi: ${this.selectedSlots.length} Jam\n- Total Bayar: ${this.formatCurrency(this.totalPrice)}\n\nLanjut ke alur pembayaran otomatis (Midtrans/Xendit Sandbox)...`);
                }
            };
        }
    </script>
</body>
</html>
