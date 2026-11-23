<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lapangin - Sistem booking lapangan olahraga online real-time. Futsal, Badminton, Mini Soccer, Padel. Anti double-booking dengan slot lock 10 menit.">
    <meta name="keywords" content="booking lapangan, sewa lapangan futsal, badminton, mini soccer, padel, lapangin, reservasi olahraga">
    <title>LapangIn - Sistem Booking Lapangan Olahraga Real-Time</title>

    <!-- Google Fonts: Teko (Headings), Nunito Sans & Plus Jakarta Sans (Body & UI) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Symbols Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="bookingApp()"
    class="bg-canvas-bg font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="beranda" />

    <main class="w-full pt-20 bg-canvas-bg min-h-[calc(100vh-80px)]">
        
        <!-- SECTION 1: HERO SECTION -->
        <section id="beranda" class="relative w-full bg-[#0f2444] text-white overflow-hidden pt-12 pb-24 border-b border-white/10">
            <!-- Ambient Glow Stadium Lighting -->
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-[#f8b710]/15 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 -left-20 w-80 h-80 rounded-full bg-orange-500/15 blur-3xl pointer-events-none"></div>

            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8 pt-4">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Hero Left Column: Headline, Subhead & Action Buttons -->
                    <div class="lg:col-span-7 flex flex-col gap-6 z-10">
                        
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 px-4 py-1.5 rounded-full text-xs font-bold text-[#f8b710] tracking-wider uppercase w-fit">
                            <span class="w-2 h-2 rounded-full bg-[#f8b710] animate-pulse"></span>
                            <span>Sistem Reservasi Olahraga Real-Time</span>
                        </div>

                        <!-- Main Big Display Headline in Teko -->
                        <h1 class="font-teko text-6xl sm:text-7xl lg:text-8xl text-white font-bold tracking-tight uppercase leading-[0.92]">
                            AJAK TIM, <span class="text-[#f8b710]">PILIH JAM,</span><br>
                            LANGSUNG MAIN!
                        </h1>

                        <!-- Accent bar -->
                        <div class="w-16 h-1 bg-[#f8b710] rounded-full -mt-2"></div>

                        <!-- Value Proposition Subtitle -->
                        <p class="font-body text-base sm:text-lg text-slate-200/90 max-w-xl leading-relaxed">
                            Booking 4 kategori arena berstandar turnamen nasional & internasional dalam hitungan detik. Kunci slot 10 menit tanpa takut bentrok jadwal, didukung pencahayaan LED 800 Lux dan sistem pembayaran otomatis terverifikasi.
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a 
                                href="#jadwal-interaktif" 
                                class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-sm uppercase tracking-wider px-7 py-4 rounded-full shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5 whitespace-nowrap"
                            >
                                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                Pilih Jam Main Sekarang
                            </a>
                            <a 
                                href="{{ route('lapangan') }}" 
                                class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold text-sm uppercase tracking-wider px-6 py-4 rounded-full transition-colors border border-white/20 whitespace-nowrap"
                            >
                                <span class="material-symbols-outlined text-[20px]">sports</span>
                                Jelajahi Lapangan
                            </a>
                        </div>

                        <!-- Quick Feature Chips -->
                        <div class="flex flex-wrap items-center gap-6 pt-2 text-xs text-slate-300 font-semibold">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[#f8b710] text-[18px]">verified</span>
                                <span>Anti Double-Booking Guarantee</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[#f8b710] text-[18px]">timer</span>
                                <span>10 Menit Slot Lock</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[#f8b710] text-[18px]">bolt</span>
                                <span>Konfirmasi Instan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Right Column: Graphic Showcase with Active Match Badge -->
                    <div class="lg:col-span-5 relative">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-[#0b1a30] border border-white/15 group">
                            <img 
                                src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80" 
                                alt="Pertandingan Futsal Lapangin Arena" 
                                class="w-full h-[420px] object-cover group-hover:scale-105 transition-transform duration-700"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0f2444] via-[#0f2444]/40 to-transparent"></div>

                            <!-- Live Active Match Badge Floating -->
                            <div class="absolute bottom-5 left-5 right-5 bg-[#0f2444]/95 backdrop-blur-md p-4 rounded-2xl flex items-center justify-between border border-white/20 shadow-2xl">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#f8b710] to-[#f37920] flex items-center justify-center text-slate-950 font-bold shadow-md">
                                        <span class="material-symbols-outlined text-[24px]">sports_soccer</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-white leading-tight">Mini Soccer A &bull; Court 1</h4>
                                        <p class="text-xs text-[#f8b710] flex items-center gap-1.5 font-semibold mt-0.5">
                                            <span class="w-2 h-2 rounded-full bg-[#f8b710] animate-pulse"></span>
                                            Sedang Bertanding (19.00 - 21.00)
                                        </p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-950 bg-[#f8b710] px-2.5 py-1 rounded-full uppercase tracking-wider">
                                    LIVE
                                </span>
                            </div>

                            <!-- Floating Lock Guarantee Pill Badge (Top Right) -->
                            <div class="absolute top-4 right-4 bg-[#0b1a30]/90 backdrop-blur-md border border-white/20 text-white text-xs px-3.5 py-1.5 rounded-full flex items-center gap-1.5 font-bold shadow-lg">
                                <span class="material-symbols-outlined text-[#f8b710] text-[16px]">lock_clock</span>
                                <span>Pessimistic Lock Active</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION 2: STATS & NUMBERS COUNTER (Inspired by Skydiving Template Counter Grid) -->
        <section class="w-full bg-surface-white py-14 border-b border-slate-200/80 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center sm:text-left">
                    
                    <!-- Stat 1 -->
                    <div class="flex flex-col items-center sm:items-start p-2">
                        <span class="font-teko text-5xl sm:text-6xl font-bold text-[#f8b710] leading-none">100%</span>
                        <div class="w-10 h-1 bg-[#f8b710] rounded-full my-2.5"></div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">JAMINAN BEBAS DOUBLE-BOOKING</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Sistem kunci waktu otomatis saat checkout</p>
                    </div>

                    <!-- Stat 2 -->
                    <div class="flex flex-col items-center sm:items-start p-2">
                        <span class="font-teko text-5xl sm:text-6xl font-bold text-[#f8b710] leading-none">4+</span>
                        <div class="w-10 h-1 bg-[#f8b710] rounded-full my-2.5"></div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">KATEGORI LAPANGAN STANDAR</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Futsal, Badminton, Mini Soccer, Padel</p>
                    </div>

                    <!-- Stat 3 -->
                    <div class="flex flex-col items-center sm:items-start p-2">
                        <span class="font-teko text-5xl sm:text-6xl font-bold text-[#f8b710] leading-none">1.200+</span>
                        <div class="w-10 h-1 bg-[#f8b710] rounded-full my-2.5"></div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">PERTANDINGAN SUKSES</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Komunitas & instansi terdaftar aktif</p>
                    </div>

                    <!-- Stat 4 -->
                    <div class="flex flex-col items-center sm:items-start p-2">
                        <span class="font-teko text-5xl sm:text-6xl font-bold text-[#f8b710] leading-none">10 MENIT</span>
                        <div class="w-10 h-1 bg-[#f8b710] rounded-full my-2.5"></div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">INSTANT SLOT LOCK</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Waktu aman untuk proses pembayaran</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION 3: TOP EXPERIENCES / LAPANGAN UNGGULAN (Anatomy like Weblium Services Grid) -->
        <section id="katalog" class="w-full bg-[#f8f9fa] py-20 border-b border-slate-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8">
                
                <!-- Section Header with Signature Bar + Dot Accent -->
                <div class="text-center max-w-2xl mx-auto mb-14">
                    <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">KATALOG ARENA RESMI</span>
                    <h2 class="font-teko text-4xl sm:text-5xl lg:text-6xl uppercase font-bold text-slate-900 tracking-tight leading-none">
                        PILIHAN LAPANGAN OLAHRAGA TERBAIK KAMI
                    </h2>
                    <div class="flex items-center justify-center gap-1.5 mt-3 mb-3">
                        <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                        <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        Arena berstandar federasi nasional & internasional dengan fasilitas lengkap, lantai terawat, dan pencahayaan LED 800 Lux.
                    </p>
                </div>

                <!-- 4-Column Courts Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Card 1: Futsal Arena A -->
                    <div class="group bg-surface-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between">
                        <div>
                            <div class="relative aspect-video overflow-hidden">
                                <img 
                                    src="{{ asset('images/courts/futsal.jpg') }}" 
                                    alt="Lapangan Futsal A"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                                <span class="absolute top-3 left-3 bg-[#f8b710] text-slate-950 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow">
                                    Futsal
                                </span>
                                <span class="absolute bottom-3 right-3 bg-slate-950/80 text-white text-[11px] font-semibold px-2 py-0.5 rounded backdrop-blur-sm">
                                    Vinyl 8mm &bull; 26 &times; 16 m
                                </span>
                            </div>
                            <div class="p-5 flex flex-col gap-2">
                                <h3 class="font-teko text-2xl sm:text-3xl font-bold text-slate-900 uppercase tracking-wide group-hover:text-[#f37920] transition-colors leading-none">
                                    Lapangan Futsal A
                                </h3>
                                <div class="w-8 h-0.5 bg-[#f8b710] rounded-full my-0.5"></div>
                                <p class="text-xs text-slate-600 flex items-center gap-1.5 font-medium">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span> 
                                    Lantai Vinyl Interlock & LED 800 Lux
                                </p>
                                <div class="grid grid-cols-2 gap-2 mt-3 pt-3 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs">
                                    <div>
                                        <span class="text-slate-500 block">Siang (08-16)</span>
                                        <span class="text-sm font-bold text-slate-900">Rp 150.000</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block">Malam (17-23)</span>
                                        <span class="text-sm font-bold text-[#f37920]">Rp 220.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            <a 
                                href="#jadwal-interaktif" 
                                @click="selectSport('futsal')"
                                class="w-full bg-[#0f2444] hover:bg-[#0b1a30] text-white font-bold text-xs uppercase tracking-wider py-3 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm"
                            >
                                Pilih Jam / Slot <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Badminton Court -->
                    <div class="group bg-surface-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between">
                        <div>
                            <div class="relative aspect-video overflow-hidden">
                                <img 
                                    src="{{ asset('images/courts/badminton.jpg') }}" 
                                    alt="Badminton Court 1-4"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                                <span class="absolute top-3 left-3 bg-[#f8b710] text-slate-950 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow">
                                    Badminton
                                </span>
                                <span class="absolute bottom-3 right-3 bg-slate-950/80 text-white text-[11px] font-semibold px-2 py-0.5 rounded backdrop-blur-sm">
                                    BWF Mat &bull; 13.4 &times; 6.1 m
                                </span>
                            </div>
                            <div class="p-5 flex flex-col gap-2">
                                <h3 class="font-teko text-2xl sm:text-3xl font-bold text-slate-900 uppercase tracking-wide group-hover:text-[#f37920] transition-colors leading-none">
                                    Badminton Court 1-4
                                </h3>
                                <div class="w-8 h-0.5 bg-[#f8b710] rounded-full my-0.5"></div>
                                <p class="text-xs text-slate-600 flex items-center gap-1.5 font-medium">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span> 
                                    Karpet Li-Ning BWF Standard
                                </p>
                                <div class="grid grid-cols-2 gap-2 mt-3 pt-3 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs">
                                    <div>
                                        <span class="text-slate-500 block">Siang (08-16)</span>
                                        <span class="text-sm font-bold text-slate-900">Rp 45.000</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block">Malam (17-23)</span>
                                        <span class="text-sm font-bold text-[#f37920]">Rp 65.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            <a 
                                href="#jadwal-interaktif" 
                                @click="selectSport('badminton')"
                                class="w-full bg-[#0f2444] hover:bg-[#0b1a30] text-white font-bold text-xs uppercase tracking-wider py-3 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm"
                            >
                                Pilih Jam / Slot <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 3: Mini Soccer 7v7 -->
                    <div class="group bg-surface-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between">
                        <div>
                            <div class="relative aspect-video overflow-hidden">
                                <img 
                                    src="{{ asset('images/courts/minisoccer.jpg') }}" 
                                    alt="Mini Soccer 7v7"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                                <span class="absolute top-3 left-3 bg-[#f8b710] text-slate-950 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow">
                                    Mini Soccer
                                </span>
                                <span class="absolute bottom-3 right-3 bg-slate-950/80 text-white text-[11px] font-semibold px-2 py-0.5 rounded backdrop-blur-sm">
                                    Rumput FIFA &bull; 45 &times; 25 m
                                </span>
                            </div>
                            <div class="p-5 flex flex-col gap-2">
                                <h3 class="font-teko text-2xl sm:text-3xl font-bold text-slate-900 uppercase tracking-wide group-hover:text-[#f37920] transition-colors leading-none">
                                    Mini Soccer 7v7
                                </h3>
                                <div class="w-8 h-0.5 bg-[#f8b710] rounded-full my-0.5"></div>
                                <p class="text-xs text-slate-600 flex items-center gap-1.5 font-medium">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span> 
                                    Sintetis Monofilament 50mm
                                </p>
                                <div class="grid grid-cols-2 gap-2 mt-3 pt-3 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs">
                                    <div>
                                        <span class="text-slate-500 block">Siang (08-16)</span>
                                        <span class="text-sm font-bold text-slate-900">Rp 450.000</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block">Malam (17-23)</span>
                                        <span class="text-sm font-bold text-[#f37920]">Rp 650.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            <a 
                                href="#jadwal-interaktif" 
                                @click="selectSport('minisoccer')"
                                class="w-full bg-[#0f2444] hover:bg-[#0b1a30] text-white font-bold text-xs uppercase tracking-wider py-3 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm"
                            >
                                Pilih Jam / Slot <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 4: Padel Court -->
                    <div class="group bg-surface-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between">
                        <div>
                            <div class="relative aspect-video overflow-hidden">
                                <img 
                                    src="{{ asset('images/courts/padel.jpg') }}" 
                                    alt="Padel Court Glass"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                                <span class="absolute top-3 left-3 bg-[#f8b710] text-slate-950 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow">
                                    Padel
                                </span>
                                <span class="absolute bottom-3 right-3 bg-slate-950/80 text-white text-[11px] font-semibold px-2 py-0.5 rounded backdrop-blur-sm">
                                    Panoramic &bull; 20 &times; 10 m
                                </span>
                            </div>
                            <div class="p-5 flex flex-col gap-2">
                                <h3 class="font-teko text-2xl sm:text-3xl font-bold text-slate-900 uppercase tracking-wide group-hover:text-[#f37920] transition-colors leading-none">
                                    Padel Court Glass
                                </h3>
                                <div class="w-8 h-0.5 bg-[#f8b710] rounded-full my-0.5"></div>
                                <p class="text-xs text-slate-600 flex items-center gap-1.5 font-medium">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span> 
                                    Kaca Tempered 12mm & Blue Turf
                                </p>
                                <div class="grid grid-cols-2 gap-2 mt-3 pt-3 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs">
                                    <div>
                                        <span class="text-slate-500 block">Siang (08-16)</span>
                                        <span class="text-sm font-bold text-slate-900">Rp 250.000</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block">Malam (17-23)</span>
                                        <span class="text-sm font-bold text-[#f37920]">Rp 330.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            <a 
                                href="#jadwal-interaktif" 
                                @click="selectSport('padel')"
                                class="w-full bg-[#0f2444] hover:bg-[#0b1a30] text-white font-bold text-xs uppercase tracking-wider py-3 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm"
                            >
                                Pilih Jam / Slot <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 4: ABOUT US / MENGAPA MEMILIH LAPANGIN (Deep Royal Navy High-Contrast Section) -->
        <section class="w-full bg-[#0f2444] text-white py-20 border-y border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-xs font-bold text-[#f8b710] uppercase tracking-widest block mb-1">MENGAPA KAMI BERBEDA</span>
                    <h2 class="font-teko text-4xl sm:text-5xl lg:text-6xl uppercase font-bold text-white tracking-tight leading-none">
                        STANDAR TERTINGGI UNTUK SETIAP PERTANDINGAN
                    </h2>
                    <div class="flex items-center justify-center gap-1.5 mt-3 mb-3">
                        <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                        <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                        Teknologi reservasi canggih dipadukan dengan kualitas infrastruktur gelanggang olahraga bertaraf internasional.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Feature 1 -->
                    <div class="flex flex-col items-center sm:items-start text-center sm:text-left bg-white/5 p-6 rounded-2xl border border-white/10 hover:border-[#f8b710]/50 transition-colors">
                        <div class="w-14 h-14 rounded-full border border-white/20 bg-white/10 flex items-center justify-center text-[#f8b710] mb-4 shadow-inner">
                            <span class="material-symbols-outlined text-[28px]">lock_clock</span>
                        </div>
                        <h3 class="font-teko text-2xl font-bold uppercase tracking-wider text-white mb-1">PESSIMISTIC LOCK SYSTEM</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Jaminan slot terkunci 10 menit saat Anda melakukan pemesanan. Tidak ada risiko jadwal bentrok atau diserobot orang lain.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex flex-col items-center sm:items-start text-center sm:text-left bg-white/5 p-6 rounded-2xl border border-white/10 hover:border-[#f8b710]/50 transition-colors">
                        <div class="w-14 h-14 rounded-full border border-white/20 bg-white/10 flex items-center justify-center text-[#f8b710] mb-4 shadow-inner">
                            <span class="material-symbols-outlined text-[28px]">military_tech</span>
                        </div>
                        <h3 class="font-teko text-2xl font-bold uppercase tracking-wider text-white mb-1">STANDAR TURNAMEN RESMI</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Lantai vinyl Teraflex empuk, rumput sintetis bersertifikasi FIFA, dan pencahayaan LED 800 Lux anti silau untuk performa maksimal.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex flex-col items-center sm:items-start text-center sm:text-left bg-white/5 p-6 rounded-2xl border border-white/10 hover:border-[#f8b710]/50 transition-colors">
                        <div class="w-14 h-14 rounded-full border border-white/20 bg-white/10 flex items-center justify-center text-[#f8b710] mb-4 shadow-inner">
                            <span class="material-symbols-outlined text-[28px]">verified</span>
                        </div>
                        <h3 class="font-teko text-2xl font-bold uppercase tracking-wider text-white mb-1">E-TIKET & VERIFIKASI REAL-TIME</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Konfirmasi otomatis dan e-tiket QR Code instan langsung ke dashboard Anda. Cukup tunjukkan ke resepsionis untuk check-in.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="flex flex-col items-center sm:items-start text-center sm:text-left bg-white/5 p-6 rounded-2xl border border-white/10 hover:border-[#f8b710]/50 transition-colors">
                        <div class="w-14 h-14 rounded-full border border-white/20 bg-white/10 flex items-center justify-center text-[#f8b710] mb-4 shadow-inner">
                            <span class="material-symbols-outlined text-[28px]">support_agent</span>
                        </div>
                        <h3 class="font-teko text-2xl font-bold uppercase tracking-wider text-white mb-1">FASILITAS & CS STANDBY</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Shower air hangat, ruang ganti ber-AC, mushola, loker aman, tribun penonton, serta tim CS responsif siap membantu Anda.
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 5: INTERACTIVE BOOKING SCHEDULE ENGINE (#jadwal-interaktif) -->
        <section id="jadwal-interaktif" class="w-full bg-[#edf0f5] py-20 px-4 sm:px-8 border-b border-slate-200">
            <div class="w-full max-w-7xl mx-auto">
                
                <!-- Section Title with Bar + Dot Accent -->
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">CEK KETERSEDIAAN HARI INI</span>
                    <h2 class="font-teko text-4xl sm:text-5xl lg:text-6xl uppercase font-bold text-slate-900 tracking-tight leading-none">
                        PILIH JAM & KUNCI JADWAL PERTANDINGAN
                    </h2>
                    <div class="flex items-center justify-center gap-1.5 mt-3 mb-3">
                        <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                        <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        Ketuk slot jam yang diinginkan (bisa pilih lebih dari satu jam berurutan). Jam malam dan weekend bertarif prime time. Slot terkunci selama 10 menit saat Anda melanjutkan ke checkout.
                    </p>
                </div>

                <!-- Booking Interface Shell -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Left Schedule Timetable Matrix (Col 8) -->
                    <div class="lg:col-span-8 bg-surface-white text-slate-800 rounded-2xl p-5 md:p-6 shadow-xl border border-slate-200">
                        
                        <!-- Arena Selector Pills -->
                        <div class="flex flex-wrap gap-2 mb-5">
                            <button 
                                type="button"
                                @click="selectSport('futsal')"
                                :class="currentSport === 'futsal' ? 'bg-[#0f2444] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="text-xs uppercase font-bold tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap"
                            >
                                Lapangan Futsal A
                            </button>
                            <button 
                                type="button"
                                @click="selectSport('badminton')"
                                :class="currentSport === 'badminton' ? 'bg-[#0f2444] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="text-xs uppercase font-bold tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap"
                            >
                                Badminton Court 1-4
                            </button>
                            <button 
                                type="button"
                                @click="selectSport('minisoccer')"
                                :class="currentSport === 'minisoccer' ? 'bg-[#0f2444] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="text-xs uppercase font-bold tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap"
                            >
                                Mini Soccer 7v7
                            </button>
                            <button 
                                type="button"
                                @click="selectSport('padel')"
                                :class="currentSport === 'padel' ? 'bg-[#0f2444] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="text-xs uppercase font-bold tracking-wider px-4 py-2.5 rounded-xl transition-all whitespace-nowrap"
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
                                        :class="selectedDate === day.date ? 'bg-[#0f2444] text-white shadow-md' : 'bg-slate-100 hover:bg-slate-200 text-slate-800'"
                                        class="px-4 py-2.5 rounded-xl text-center flex flex-col items-center min-w-[76px] transition-all whitespace-nowrap"
                                    >
                                        <span :class="selectedDate === day.date ? 'text-[#f8b710]' : (day.isWeekend ? 'text-[#f37920]' : 'text-slate-500')" class="text-xs font-bold block uppercase" x-text="day.dayLabel"></span>
                                        <span class="text-sm font-bold" x-text="day.dateLabel"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Slot Status Legend -->
                        <div class="flex flex-wrap items-center gap-4 py-2.5 mb-5 bg-slate-50 px-4 rounded-xl border border-slate-100 text-xs font-medium">
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-3 h-3 rounded bg-emerald-50 border border-emerald-400"></span>
                                <span>Tersedia (Siang)</span>
                            </div>
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-3 h-3 rounded bg-amber-50 border border-[#f8b710]"></span>
                                <span>Jam Ramai / Prime</span>
                            </div>
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-3 h-3 rounded bg-[#f37920] border border-[#f37920]"></span>
                                <span class="font-bold text-[#f37920]">Dipilih Anda</span>
                            </div>
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-3 h-3 rounded bg-slate-100 border border-slate-200"></span>
                                <span class="text-slate-400 line-through">Terisi / Booked</span>
                            </div>
                        </div>

                        <!-- Slot Matrix Time Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-2.5">
                            <template x-for="slot in activeCourtSlots" :key="slot.id">
                                <div>
                                    <!-- Booked Slot -->
                                    <div 
                                        x-show="slot.status === 'booked'"
                                        class="p-2.5 rounded-xl text-center bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200 select-none"
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
                                            'bg-gradient-to-r from-[#f8b710] to-[#f37920] text-slate-950 font-bold ring-2 ring-[#f8b710] shadow-md transform scale-[1.02]': isSlotSelected(slot),
                                            'bg-amber-50/80 text-slate-900 hover:bg-amber-100/80 border border-[#f8b710]/40': !isSlotSelected(slot) && slot.isPrime,
                                            'bg-emerald-50 text-slate-900 hover:bg-emerald-100 border border-emerald-300': !isSlotSelected(slot) && !slot.isPrime
                                        }"
                                        class="w-full p-2.5 rounded-xl text-center transition-all cursor-pointer font-body flex flex-col items-center justify-center"
                                    >
                                        <span class="text-sm font-bold block whitespace-nowrap" x-text="slot.time"></span>
                                        <span 
                                            :class="isSlotSelected(slot) ? 'text-slate-950 font-bold' : (slot.isPrime ? 'text-[#f37920] font-bold' : 'text-emerald-800 font-medium')"
                                            class="text-xs mt-0.5 whitespace-nowrap"
                                            x-text="formatCurrency(slot.price)"
                                        ></span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Right Column: Sticky Real-Time Booking Tray (Col 4) -->
                    <div class="lg:col-span-4 bg-surface-white text-slate-800 rounded-2xl p-6 shadow-2xl sticky top-24 border border-slate-200">
                        
                        <!-- Pessimistic Lock Banner -->
                        <div class="flex items-center justify-between pb-2 mb-4 bg-amber-50/80 p-3 rounded-xl border border-[#f8b710]/30">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-[#f37920]">lock_clock</span>
                                <span class="text-xs font-bold text-slate-800">Slot Lock Guarantee</span>
                            </div>
                            <span class="text-xs text-slate-950 font-bold bg-[#f8b710] px-2 py-0.5 rounded-md">
                                10 Menit
                            </span>
                        </div>

                        <h3 class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900 leading-none">Ringkasan Reservasi</h3>
                        <p class="text-xs text-slate-500 mb-4 font-medium" x-text="summaryTitle"></p>

                        <!-- Selected Slots List -->
                        <div class="min-h-[110px] flex flex-col gap-2 mb-4">
                            <!-- Empty State -->
                            <div 
                                x-show="selectedSlots.length === 0" 
                                class="p-4 bg-slate-50 rounded-xl text-center text-slate-500 text-xs leading-relaxed border border-dashed border-slate-200"
                            >
                                <span class="material-symbols-outlined text-[28px] text-slate-400 mb-1 block">touch_app</span>
                                Belum ada jam yang dipilih.<br>
                                Ketuk satu atau beberapa slot jam di tabel sebelah kiri.
                            </div>

                            <!-- List of chosen slots -->
                            <template x-for="(item, idx) in selectedSlots" :key="idx">
                                <div class="flex justify-between items-center bg-slate-50 px-3 py-2 rounded-xl text-xs font-medium border border-slate-100">
                                    <div class="flex items-center gap-1.5 text-slate-800">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span class="font-bold whitespace-nowrap" x-text="item.time"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[#f37920] font-bold whitespace-nowrap" x-text="formatCurrency(item.price)"></span>
                                        <button 
                                            type="button" 
                                            @click="toggleSlot(item)" 
                                            class="text-slate-400 hover:text-red-500 transition-colors"
                                            title="Hapus slot"
                                        >
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Total Price Block -->
                        <div class="bg-[#0f2444] text-white p-4 rounded-xl mb-4 shadow-inner">
                            <div class="flex justify-between items-center text-slate-300 text-xs mb-1">
                                <span>Durasi Main</span>
                                <span class="font-bold" x-text="selectedSlots.length + ' Jam'"></span>
                            </div>
                            <div class="flex justify-between items-center text-white pt-1 border-t border-white/10">
                                <span class="text-sm font-semibold">Total Tagihan</span>
                                <span class="font-teko text-2xl sm:text-3xl text-[#f8b710] font-bold whitespace-nowrap leading-none" x-text="formatCurrency(totalPrice)"></span>
                            </div>
                        </div>

                        <!-- Dual Action CTA Buttons -->
                        <div class="flex flex-col gap-2.5">
                            <button 
                                type="button"
                                :disabled="selectedSlots.length === 0"
                                @click="proceedBooking()"
                                class="w-full bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] disabled:opacity-40 disabled:cursor-not-allowed text-slate-950 font-bold text-xs uppercase tracking-wider py-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md transform active:scale-95 whitespace-nowrap cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                                Kunci Slot & Booking Sekarang
                            </button>

                            <a 
                                :href="whatsAppLink"
                                target="_blank"
                                class="w-full bg-[#0f2444] hover:bg-[#0b1a30] text-white font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl flex items-center justify-center gap-2 transition-colors whitespace-nowrap"
                            >
                                <span class="material-symbols-outlined text-[20px]">chat</span>
                                Booking Cepat via WhatsApp
                            </a>
                        </div>

                        <p class="text-[11px] text-slate-500 text-center mt-3 leading-relaxed flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-emerald-600">verified_user</span>
                            Slot dikunci 10 menit anti double-booking setelah masuk/daftar.
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 6: PILIHAN PAKET SEWA ARENA (Pricing Cards anatomy like Weblium) -->
        <section class="w-full bg-surface-white py-20 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">PAKET & HARGA SEWA</span>
                    <h2 class="font-teko text-4xl sm:text-5xl lg:text-6xl uppercase font-bold text-slate-900 tracking-tight leading-none">
                        PILIHAN PAKET SEWA GELANGGANG
                    </h2>
                    <div class="flex items-center justify-center gap-1.5 mt-3 mb-3">
                        <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                        <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        Fleksibilitas reservasi lengkap, mulai dari sparring santai per jam, paket komunitas mingguan, hingga turnamen akbar.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                    
                    <!-- Pricing Card 1: Reguler -->
                    <div class="bg-surface-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-xl transition-all flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">LATIHAN & SPARRING</span>
                            <h3 class="font-teko text-3xl font-bold uppercase text-slate-900 leading-none">PAKET REGULER</h3>
                            <div class="w-8 h-1 bg-[#f8b710] rounded-full my-3"></div>
                            
                            <div class="my-5">
                                <span class="text-xs text-slate-500 block">Mulai dari</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="font-teko text-5xl font-bold text-[#f8b710] leading-none">Rp 45.000</span>
                                    <span class="text-xs text-slate-500">/ jam</span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                Pilihan praktis untuk tim yang ingin bermain fleksibel per sesi tanpa komitmen jangka panjang.
                            </p>

                            <ul class="flex flex-col gap-3 text-xs text-slate-700">
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Bebas pilih jam siang atau prime time malam</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Slot terkunci aman 10 menit saat checkout</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Akses shower & ruang ganti ber-AC</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>E-Tiket kode QR instan</span>
                                </li>
                            </ul>
                        </div>

                        <div class="pt-8">
                            <a 
                                href="#jadwal-interaktif" 
                                class="w-full border-2 border-[#0f2444] text-[#0f2444] hover:bg-[#0f2444] hover:text-white font-bold text-xs uppercase tracking-wider py-3.5 rounded-full flex items-center justify-center gap-2 transition-all"
                            >
                                Booking Sekarang
                            </a>
                        </div>
                    </div>

                    <!-- Pricing Card 2: Member Komunitas (Featured / Highlight) -->
                    <div class="bg-surface-white rounded-3xl p-8 border-2 border-[#f8b710] shadow-xl relative flex flex-col justify-between transform md:-translate-y-2">
                        <!-- Popular Badge -->
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-[#f8b710] to-[#f37920] text-slate-950 font-bold text-xs uppercase tracking-wider px-4 py-1 rounded-full shadow-md">
                            REKOMENDASI KOMUNITAS
                        </div>

                        <div>
                            <span class="text-xs font-bold text-[#f37920] uppercase tracking-wider block mb-1">LANGGANAN RUTIN</span>
                            <h3 class="font-teko text-3xl font-bold uppercase text-slate-900 leading-none">PAKET MEMBER KOMUNITAS</h3>
                            <div class="w-8 h-1 bg-[#f8b710] rounded-full my-3"></div>
                            
                            <div class="my-5">
                                <span class="text-xs text-slate-500 block">Mulai dari</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="font-teko text-5xl font-bold text-[#f37920] leading-none">DISKON 15%</span>
                                    <span class="text-xs text-slate-500">/ bulan</span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                Amankan jadwal favorit Anda secara rutin setiap minggu tanpa takut kehabisan slot oleh tim lain.
                            </p>

                            <ul class="flex flex-col gap-3 text-xs text-slate-700">
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span><strong>Prioritas jadwal tetap</strong> mingguan terjaga</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Diskon 15% setiap total transaksi bulanan</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Gratis 1 galon air mineral setiap sesi main</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Fleksibilitas reschedule 1x per bulan</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Prioritas loker tim & ruang simpan perlengkapan</span>
                                </li>
                            </ul>
                        </div>

                        <div class="pt-8">
                            <a 
                                href="{{ route('kontak') }}" 
                                class="w-full bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-xs uppercase tracking-wider py-4 rounded-full flex items-center justify-center gap-2 shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5"
                            >
                                Daftar Member Komunitas
                            </a>
                        </div>
                    </div>

                    <!-- Pricing Card 3: Turnamen / Event Organizer -->
                    <div class="bg-surface-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-xl transition-all flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">EVENT & TURNAMEN</span>
                            <h3 class="font-teko text-3xl font-bold uppercase text-slate-900 leading-none">PAKET TURNAMEN & EVENT</h3>
                            <div class="w-8 h-1 bg-[#f8b710] rounded-full my-3"></div>
                            
                            <div class="my-5">
                                <span class="text-xs text-slate-500 block">Sewa Venue Penuh</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="font-teko text-5xl font-bold text-slate-900 leading-none">CUSTOM</span>
                                    <span class="text-xs text-slate-500">/ seharian</span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                Solusi sewa gelanggang menyeluruh untuk event perusahaan, liga amatir, atau kejuaraan antarsekolah.
                            </p>

                            <ul class="flex flex-col gap-3 text-xs text-slate-700">
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Sewa arena penuh seharian (08.00 - 23.00)</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Akses sound system, mic wireless, & tribun</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Ruang panitia ber-AC khusus</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Papan skor digital & meja wasit resmi</span>
                                </li>
                            </ul>
                        </div>

                        <div class="pt-8">
                            <a 
                                href="{{ route('kontak') }}" 
                                class="w-full border-2 border-[#0f2444] text-[#0f2444] hover:bg-[#0f2444] hover:text-white font-bold text-xs uppercase tracking-wider py-3.5 rounded-full flex items-center justify-center gap-2 transition-all"
                            >
                                Konsultasi Event CS
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 7: ALUR BOOKING 4 LANGKAH (Step Cards) -->
        <section id="cara-booking" class="w-full bg-[#f8f9fa] py-20 border-b border-slate-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">PROSES MUDAH & CEPAT</span>
                    <h2 class="font-teko text-4xl sm:text-5xl lg:text-6xl uppercase font-bold text-slate-900 tracking-tight leading-none">
                        4 LANGKAH RESERVASI PRAKTIS
                    </h2>
                    <div class="flex items-center justify-center gap-1.5 mt-3 mb-3">
                        <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                        <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600">
                        Booking lapangan olahraga di LapangIn selesai dalam waktu kurang dari 2 menit.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="bg-surface-white p-6 rounded-2xl relative border border-slate-200 shadow-sm">
                        <span class="font-teko text-5xl text-slate-200 absolute top-3 right-4 font-bold select-none leading-none">01</span>
                        <div class="w-12 h-12 rounded-xl bg-[#0f2444] text-white flex items-center justify-center mb-4 font-bold shadow-md">
                            <span class="material-symbols-outlined text-[24px]">sports_soccer</span>
                        </div>
                        <h3 class="font-teko text-2xl font-bold uppercase text-slate-900 mb-1 leading-none">PILIH ARENA & JAM</h3>
                        <div class="w-6 h-0.5 bg-[#f8b710] rounded-full my-2"></div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pilih cabang olahraga, tentukan tanggal pertandingan, dan pilih satu atau beberapa jam yang kosong di kalender interaktif.
                        </p>
                    </div>

                    <div class="bg-surface-white p-6 rounded-2xl relative border border-slate-200 shadow-sm">
                        <span class="font-teko text-5xl text-slate-200 absolute top-3 right-4 font-bold select-none leading-none">02</span>
                        <div class="w-12 h-12 rounded-xl bg-[#f8b710] text-slate-950 flex items-center justify-center mb-4 font-bold shadow-md">
                            <span class="material-symbols-outlined text-[24px]">lock_clock</span>
                        </div>
                        <h3 class="font-teko text-2xl font-bold uppercase text-slate-900 mb-1 leading-none">SLOT LOCK 10 MENIT</h3>
                        <div class="w-6 h-0.5 bg-[#f8b710] rounded-full my-2"></div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Sistem secara instan mengunci slot pilihan Anda selama 10 menit agar tidak dapat dipesan atau diserobot orang lain.
                        </p>
                    </div>

                    <div class="bg-surface-white p-6 rounded-2xl relative border border-slate-200 shadow-sm">
                        <span class="font-teko text-5xl text-slate-200 absolute top-3 right-4 font-bold select-none leading-none">03</span>
                        <div class="w-12 h-12 rounded-xl bg-[#0f2444] text-white flex items-center justify-center mb-4 font-bold shadow-md">
                            <span class="material-symbols-outlined text-[24px]">payments</span>
                        </div>
                        <h3 class="font-teko text-2xl font-bold uppercase text-slate-900 mb-1 leading-none">BAYAR INSTAN / MANUAL</h3>
                        <div class="w-6 h-0.5 bg-[#f8b710] rounded-full my-2"></div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Selesaikan pembayaran melalui transfer bank BCA resmi dan upload bukti transfer untuk diverifikasi operator kami.
                        </p>
                    </div>

                    <div class="bg-surface-white p-6 rounded-2xl relative border border-slate-200 shadow-sm">
                        <span class="font-teko text-5xl text-slate-200 absolute top-3 right-4 font-bold select-none leading-none">04</span>
                        <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center mb-4 font-bold shadow-md">
                            <span class="material-symbols-outlined text-[24px]">qr_code_scanner</span>
                        </div>
                        <h3 class="font-teko text-2xl font-bold uppercase text-slate-900 mb-1 leading-none">E-TIKET QR & MAIN!</h3>
                        <div class="w-6 h-0.5 bg-[#f8b710] rounded-full my-2"></div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Tiket QR Code unik dan kode booking langsung tersedia di dashboard Anda. Tunjukkan ke kasir untuk mulai bertanding!
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 8: HIGH-ENERGY FULL-WIDTH ACTION BANNER (Like Skydiving "Sky is Not the Limit" Banner) -->
        <section class="w-full bg-[#0f2444] text-white py-20 relative overflow-hidden border-b border-white/10 text-center">
            <div class="absolute inset-0 bg-radial from-white/5 to-transparent pointer-events-none"></div>
            <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-[#f8b710]/15 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full bg-orange-500/15 blur-3xl pointer-events-none"></div>

            <div class="max-w-4xl mx-auto px-4 sm:px-8 relative z-10">
                <span class="text-xs font-bold text-[#f8b710] uppercase tracking-widest block mb-2">SIAPKAN SEPATU & ATUR JADWAL</span>
                <h2 class="font-teko text-5xl sm:text-6xl lg:text-7xl font-bold uppercase text-white tracking-tight leading-none mb-4">
                    JADWALKAN PERTANDINGAN TIM ANDA SEKARANG!
                </h2>
                <div class="flex items-center justify-center gap-1.5 my-3">
                    <span class="w-16 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                    <span class="w-2 h-2 bg-[#f8b710] rounded-xs inline-block"></span>
                </div>
                <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto mb-8 leading-relaxed">
                    Slot lapangan favorit terutama di akhir pekan dan jam malam cepat terisi. Jangan biarkan rencana pertandingan tim Anda tertunda!
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a 
                        href="#jadwal-interaktif" 
                        class="bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-xs uppercase tracking-wider px-8 py-4 rounded-full shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5"
                    >
                        Booking Lapangan Sekarang
                    </a>
                    <a 
                        href="https://wa.me/6280012345678" 
                        target="_blank"
                        class="border-2 border-white/30 hover:border-white text-white font-bold text-xs uppercase tracking-wider px-7 py-4 rounded-full hover:bg-white/10 transition-all inline-flex items-center gap-2"
                    >
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                        Chat CS WhatsApp
                    </a>
                </div>
            </div>
        </section>

        <!-- SECTION 9: SUASANA ARENA (GALERI FOTO) -->
        <section class="w-full bg-[#f8f9fa] py-20 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-14">
                    <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">DOKUMENTASI VENUE</span>
                    <h2 class="font-teko text-4xl sm:text-5xl lg:text-6xl uppercase font-bold text-slate-900 tracking-tight leading-none">
                        SUASANA GELANGGANG KAMI
                    </h2>
                    <div class="flex items-center justify-center gap-1.5 mt-3 mb-3">
                        <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                        <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                    </div>
                    <p class="text-sm text-slate-600">
                        Kualitas gelanggang terawat, pencahayaan optimal, dan standar fasilitas bersertifikasi resmi.
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="relative rounded-2xl overflow-hidden aspect-square group shadow-sm">
                        <img 
                            src="https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=600&q=80" 
                            alt="Hall Indoor Utama Lapangin" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-4">
                            <span class="text-xs font-bold text-white uppercase tracking-wider">Hall Indoor Futsal</span>
                        </div>
                    </div>
                    <div class="relative rounded-2xl overflow-hidden aspect-square group shadow-sm">
                        <img 
                            src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=600&q=80" 
                            alt="Badminton Court Area" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-4">
                            <span class="text-xs font-bold text-white uppercase tracking-wider">Karpet Badminton BWF</span>
                        </div>
                    </div>
                    <div class="relative rounded-2xl overflow-hidden aspect-square group shadow-sm">
                        <img 
                            src="https://images.unsplash.com/photo-1529900245534-47fbf46b403d?auto=format&fit=crop&w=600&q=80" 
                            alt="Pencahayaan LED Lapangan" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-4">
                            <span class="text-xs font-bold text-white uppercase tracking-wider">Pencahayaan LED 800 Lux</span>
                        </div>
                    </div>
                    <div class="relative rounded-2xl overflow-hidden aspect-square group shadow-sm">
                        <img 
                            src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=600&q=80" 
                            alt="Pertandingan Mini Soccer" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-4">
                            <span class="text-xs font-bold text-white uppercase tracking-wider">Mini Soccer Sintetis</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- SECTION 10: TESTIMONIALS (ULASAN KOMUNITAS) -->
        <section class="w-full bg-surface-white py-20 border-b border-slate-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">SUARA KOMUNITAS</span>
                    <h2 class="font-teko text-4xl sm:text-5xl lg:text-6xl uppercase font-bold text-slate-900 tracking-tight leading-none">
                        APA KATA MEREKA TENTANG LAPANGIN
                    </h2>
                    <div class="flex items-center justify-center gap-1.5 mt-3 mb-3">
                        <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                        <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                    </div>
                    <p class="text-sm text-slate-600">
                        Ulasan terverifikasi langsung dari pelanggan yang telah menyelesaikan pertandingan di LapangIn Hub.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Testi 1 -->
                    <div class="bg-[#f8f9fa] p-7 rounded-3xl flex flex-col justify-between border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center text-[#f8b710]">
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-950 bg-[#f8b710] px-2.5 py-0.5 rounded-full uppercase">FUTSAL LEAGUE</span>
                            </div>
                            <p class="text-sm text-slate-700 italic mb-6 leading-relaxed">
                                &ldquo;Booking gampang banget, tinggal pilih jam langsung kelihatan total harganya. Slot terkunci aman, gak khawatir diserobot tim lain waktu mau transfer.&rdquo;
                            </p>
                        </div>
                        <div class="flex items-center gap-3.5 pt-4 border-t border-slate-200">
                            <div class="w-11 h-11 rounded-full bg-[#0f2444] text-[#f8b710] flex items-center justify-center font-bold text-base shadow-sm">
                                T
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 leading-tight">Tim FC Kantor Pajak</h4>
                                <span class="text-xs text-slate-500">Member Futsal Jumat Malam</span>
                            </div>
                        </div>
                    </div>

                    <!-- Testi 2 -->
                    <div class="bg-[#f8f9fa] p-7 rounded-3xl flex flex-col justify-between border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center text-[#f8b710]">
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-950 bg-[#f8b710] px-2.5 py-0.5 rounded-full uppercase">BADMINTON CLUB</span>
                            </div>
                            <p class="text-sm text-slate-700 italic mb-6 leading-relaxed">
                                &ldquo;Tarif pagi jauh lebih hemat buat main santai. Lampu LED-nya beneran ramah di mata, gak silau waktu ambil smash tinggi. Invoice juga langsung masuk email.&rdquo;
                            </p>
                        </div>
                        <div class="flex items-center gap-3.5 pt-4 border-t border-slate-200">
                            <div class="w-11 h-11 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-base shadow-sm">
                                K
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 leading-tight">Komunitas Badminton Pagi</h4>
                                <span class="text-xs text-slate-500">Rutin 4 Court Tiap Minggu</span>
                            </div>
                        </div>
                    </div>

                    <!-- Testi 3 -->
                    <div class="bg-[#f8f9fa] p-7 rounded-3xl flex flex-col justify-between border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center text-[#f8b710]">
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-950 bg-[#f8b710] px-2.5 py-0.5 rounded-full uppercase">PADEL SQUAD</span>
                            </div>
                            <p class="text-sm text-slate-700 italic mb-6 leading-relaxed">
                                &ldquo;Court padel panoramic glass terbaik di kota ini! Kaca pantulnya tebal dan presisi. Cek-in di meja resepsionis tinggal scan kode booking, cepat sekali!&rdquo;
                            </p>
                        </div>
                        <div class="flex items-center gap-3.5 pt-4 border-t border-slate-200">
                            <div class="w-11 h-11 rounded-full bg-[#f37920] text-white flex items-center justify-center font-bold text-base shadow-sm">
                                D
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 leading-tight">Dimas Setiawan</h4>
                                <span class="text-xs text-slate-500">Pemain Padel Antar-Klub</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECTION 11: INTERACTIVE FAQ ACCORDION -->
        <section id="faq" class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-20 bg-canvas-bg">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">PUSAT BANTUAN</span>
                <h2 class="font-teko text-4xl sm:text-5xl lg:text-6xl uppercase font-bold text-slate-900 tracking-tight leading-none">
                    PERTANYAAN YANG SERING DIAJUKAN
                </h2>
                <div class="flex items-center justify-center gap-1.5 mt-3 mb-3">
                    <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                    <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                </div>
                <p class="text-sm text-slate-600">
                    Informasi lengkap mengenai tata cara pemesanan, kebijakan pembatalan, dan peraturan bermain di LapangIn.
                </p>
            </div>

            <div class="max-w-3xl mx-auto flex flex-col gap-3">
                
                <!-- FAQ 1 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button 
                        type="button" 
                        @click="activeFaq = (activeFaq === 1 ? null : 1)" 
                        class="w-full p-5 text-left flex justify-between items-center transition-colors hover:bg-slate-50"
                    >
                        <span class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900">
                            Bagaimana cara kerja kunci slot 10 menit?
                        </span>
                        <span 
                            class="material-symbols-outlined text-[22px] text-[#f37920] transition-transform duration-200"
                            :class="activeFaq === 1 ? 'rotate-180' : ''"
                        >
                            expand_more
                        </span>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Ketika Anda menekan tombol "Kunci Slot & Booking" dan masuk ke halaman checkout, sistem backend kami mengaktifkan <em>pessimistic lock</em>. Slot jam tersebut tidak akan dapat dipilih atau dipesan oleh pengguna lain di seluruh dunia selama 10 menit. Jika dalam waktu tersebut pembayaran tidak diselesaikan, slot akan otomatis dibuka kembali.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button 
                        type="button" 
                        @click="activeFaq = (activeFaq === 2 ? null : 2)" 
                        class="w-full p-5 text-left flex justify-between items-center transition-colors hover:bg-slate-50"
                    >
                        <span class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900">
                            Apakah saya bisa membatalkan atau reschedule jadwal?
                        </span>
                        <span 
                            class="material-symbols-outlined text-[22px] text-[#f37920] transition-transform duration-200"
                            :class="activeFaq === 2 ? 'rotate-180' : ''"
                        >
                            expand_more
                        </span>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Bisa. Pembatalan lebih dari 24 jam sebelum jadwal bermain berhak mendapatkan refund 90% atau bebas reschedule 1 kali. Pembatalan dalam rentang kurang dari 24 jam sebelum bermain dikenakan potongan 50%. Pembatalan saat hari H tidak dapat direfund.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button 
                        type="button" 
                        @click="activeFaq = (activeFaq === 3 ? null : 3)" 
                        class="w-full p-5 text-left flex justify-between items-center transition-colors hover:bg-slate-50"
                    >
                        <span class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900">
                            Apa saja metode pembayaran yang diterima?
                        </span>
                        <span 
                            class="material-symbols-outlined text-[22px] text-[#f37920] transition-transform duration-200"
                            :class="activeFaq === 3 ? 'rotate-180' : ''"
                        >
                            expand_more
                        </span>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Kami menerima transfer antar bank ke rekening resmi BCA PT Lapangin Olahraga Indonesia. Anda cukup mengunggah foto struk transfer atau tangkapan layar m-banking pada halaman konfirmasi pembayaran.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-surface-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button 
                        type="button" 
                        @click="activeFaq = (activeFaq === 4 ? null : 4)" 
                        class="w-full p-5 text-left flex justify-between items-center transition-colors hover:bg-slate-50"
                    >
                        <span class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900">
                            Apakah sepatu dan raket bisa disewa di lokasi?
                        </span>
                        <span 
                            class="material-symbols-outlined text-[22px] text-[#f37920] transition-transform duration-200"
                            :class="activeFaq === 4 ? 'rotate-180' : ''"
                        >
                            expand_more
                        </span>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Ya! Pro Shop kami menyediakan penyewaan raket badminton, raket padel, bola futsal FIFA, dan rompi tanding dengan biaya terjangkau di meja resepsionis gelanggang.
                    </div>
                </div>

            </div>
        </section>

        <!-- FLOATING WHATSAPP SPEED DIAL -->
        <div class="fixed bottom-6 right-6 z-40">
            <a 
                href="https://wa.me/6280012345678" 
                target="_blank" 
                class="w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl flex items-center justify-center transition-transform hover:scale-110 border-2 border-white"
                title="Bantuan WhatsApp 24 Jam"
            >
                <span class="material-symbols-outlined text-[28px]">chat</span>
            </a>
        </div>

    </main>

    <!-- AUTH INTERCEPT MODAL (Untuk Tamu/Guest yang ingin booking) -->
    <div 
        x-show="showAuthModal" 
        x-transition.opacity
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            @click.away="showAuthModal = false"
            class="bg-surface-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative flex flex-col gap-5 text-left"
        >
            <button 
                @click="showAuthModal = false"
                class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-1 rounded-full hover:bg-slate-100 transition-colors"
                aria-label="Tutup"
            >
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>

            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-[#f8b710]/40 flex items-center justify-center text-[#f37920] shrink-0">
                    <span class="material-symbols-outlined text-[26px]">lock_person</span>
                </div>
                <div>
                    <h3 class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900 leading-none">MASUK UNTUK BOOKING</h3>
                    <p class="text-xs text-slate-500 mt-1">Slot aman & anti double-booking</p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-xs flex flex-col gap-2">
                <div class="flex justify-between items-center text-slate-800 font-semibold">
                    <span>Slot Dipilih (<span x-text="selectedSlots.length"></span> jam):</span>
                    <span class="text-[#f37920] font-bold text-sm" x-text="formatCurrency(totalPrice)"></span>
                </div>
                <p class="text-slate-600 leading-relaxed">
                    Untuk mengunci slot selama <strong>10 menit</strong> dan menerima e-tiket kode QR serta konfirmasi resmi, silakan masuk ke akun Anda atau daftar gratis.
                </p>
            </div>

            <div class="flex flex-col gap-2.5">
                <a 
                    href="{{ route('login') }}" 
                    class="w-full bg-[#0f2444] hover:bg-[#0b1a30] text-white font-bold text-xs uppercase tracking-wider py-4 rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all"
                >
                    <span class="material-symbols-outlined text-[18px]">login</span>
                    Masuk ke Akun Saya
                </a>

                <a 
                    href="{{ route('register') }}" 
                    class="w-full bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-xs uppercase tracking-wider py-4 rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all"
                >
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Daftar Akun Baru (Gratis)
                </a>
            </div>

            <div class="pt-2 border-t border-slate-100 text-center">
                <a 
                    href="{{ route('booking.checkout', 'futsal-arena-a') }}" 
                    class="text-xs text-slate-400 hover:text-slate-700 font-medium inline-flex items-center gap-1 hover:underline"
                >
                    <span>(Uji Coba: Lanjut ke Halaman Checkout &rarr;)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- FOOTER COMPONENT -->
    <x-footer />

    <!-- ALPINE.JS COMPONENT LOGIC -->
    <script>
        function bookingApp() {
            return {
                mobileMenuOpen: false,
                showAuthModal: false,
                isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
                currentSport: 'futsal',
                selectedDate: '{{ now()->toDateString() }}',
                filterTimeType: 'all',
                selectedSlots: [],
                activeFaq: 1,

                dateOptions: [
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $d = now()->addDays($i);
                            $dayLbl = $i === 0 ? 'HARI INI' : match($d->dayOfWeek) {
                                1 => 'SEN', 2 => 'SEL', 3 => 'RAB', 4 => 'KAM', 5 => 'JUM', 6 => 'SAB', 0 => 'MIN'
                            };
                            $dateLbl = $d->format('j M');
                            $isWknd = in_array($d->dayOfWeek, [0, 6]);
                        @endphp
                        { date: '{{ $d->toDateString() }}', dayLabel: '{{ $dayLbl }}', dateLabel: '{{ $dateLbl }}', isWeekend: {{ $isWknd ? 'true' : 'false' }} },
                    @endfor
                ],

                sportCatalog: {
                    'futsal': {
                        name: '{{ $futsalCourt->name ?? "Lapangan Futsal A" }}',
                        slug: '{{ $futsalCourt->slug ?? "futsal-arena-a" }}',
                        regularPrice: {{ $futsalCourt->price_regular ?? 150000 }},
                        primePrice: {{ $futsalCourt->price_prime ?? 220000 }},
                        bookedSlots: []
                    },
                    'badminton': {
                        name: '{{ $badmintonCourt->name ?? "Badminton Court 1" }}',
                        slug: '{{ $badmintonCourt->slug ?? "badminton-court-1" }}',
                        regularPrice: {{ $badmintonCourt->price_regular ?? 45000 }},
                        primePrice: {{ $badmintonCourt->price_prime ?? 65000 }},
                        bookedSlots: []
                    },
                    'minisoccer': {
                        name: '{{ $miniSoccerCourt->name ?? "Mini Soccer 7v7" }}',
                        slug: '{{ $miniSoccerCourt->slug ?? "mini-soccer-7v7" }}',
                        regularPrice: {{ $miniSoccerCourt->price_regular ?? 450000 }},
                        primePrice: {{ $miniSoccerCourt->price_prime ?? 650000 }},
                        bookedSlots: []
                    },
                    'padel': {
                        name: '{{ $padelCourt->name ?? "Padel Court Glass" }}',
                        slug: '{{ $padelCourt->slug ?? "padel-court-glass" }}',
                        regularPrice: {{ $padelCourt->price_regular ?? 250000 }},
                        primePrice: {{ $padelCourt->price_prime ?? 330000 }},
                        bookedSlots: []
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
                    return `${sport.name} &bull; ${dateText}`;
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
                    if (!this.isLoggedIn) {
                        this.showAuthModal = true;
                    } else {
                        window.location.href = "{{ url('/booking') }}/" + sport.slug;
                    }
                }
            };
        }
    </script>
</body>
</html>
