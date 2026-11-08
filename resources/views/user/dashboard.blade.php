<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Saya - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, activeTab: 'upcoming' }">
    <x-navbar active="beranda" />

    <main class="w-full pt-20">

        {{-- User header banner --}}
        <div class="bg-arena-night border-b border-pitch-deep/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-8">
                <div class="flex items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-pitch-emerald/20 border border-pitch-light/30 flex items-center justify-center">
                            <span class="font-headline font-bold text-xl text-pitch-light">AF</span>
                        </div>
                        <div>
                            <p class="text-xs text-surface-container-highest uppercase tracking-wider">Selamat datang kembali,</p>
                            <h1 class="font-headline text-xl font-bold text-surface-white">Ahmad Fauzi</h1>
                            <p class="text-xs text-pitch-light mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">verified</span>
                                Penyewa Aktif • Member sejak Nov 2026
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('lapangan') }}" class="hidden sm:flex items-center gap-2 bg-action-orange hover:bg-tertiary-container text-surface-white font-bold text-sm px-5 py-2.5 rounded-xl transition-all">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Booking Lapangan
                    </a>
                </div>

                {{-- Stats row --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                    @foreach([
                        ['history', '12', 'Total Booking'],
                        ['task_alt', '11', 'Berhasil'],
                        ['cancel', '1', 'Dibatalkan'],
                        ['payments', 'Rp 1,4 Jt', 'Total Transaksi'],
                    ] as $stat)
                    <div class="bg-surface-white/5 rounded-xl p-4 border border-surface-white/10">
                        <span class="material-symbols-outlined text-pitch-light text-[18px]">{{ $stat[0] }}</span>
                        <div class="font-headline text-xl font-bold text-surface-white mt-1">{{ $stat[1] }}</div>
                        <div class="text-xs text-surface-container-highest mt-0.5">{{ $stat[2] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Main: Booking List --}}
                <div class="lg:col-span-2 flex flex-col gap-6">

                    {{-- Tab selector --}}
                    <div class="flex gap-1 bg-surface-container-low p-1 rounded-xl w-fit">
                        @foreach(['upcoming' => 'Upcoming', 'history' => 'Riwayat', 'cancelled' => 'Dibatalkan'] as $key => $label)
                        <button
                            @click="activeTab = '{{ $key }}'"
                            :class="activeTab === '{{ $key }}' ? 'bg-surface-white text-pitch-deep shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface'"
                            class="text-sm px-4 py-2 rounded-lg transition-all font-medium"
                        >{{ $label }}</button>
                        @endforeach
                    </div>

                    {{-- Upcoming bookings --}}
                    <div x-show="activeTab === 'upcoming'" class="flex flex-col gap-4">
                        {{-- Booking card: upcoming --}}
                        @php
                            $upcomingBookings = [
                                ['LPN-2026-4729', 'Futsal Arena A', 'Senin, 11 Nov 2026', '09:00 – 11:00', 'Rp 240.000', 'confirmed', '2 hari lagi'],
                                ['LPN-2026-4580', 'Badminton Court 2', 'Rabu, 13 Nov 2026', '17:00 – 19:00', 'Rp 160.000', 'pending', '4 hari lagi'],
                            ];
                        @endphp
                        @foreach($upcomingBookings as $booking)
                        <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4 hover:border-pitch-light/40 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-pitch-surface flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-pitch-deep text-[22px]">sports_soccer</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $booking[1] }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $booking[0] }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    @if($booking[5] === 'confirmed')
                                    <span class="inline-flex items-center gap-1 bg-pitch-surface text-pitch-deep text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Terkonfirmasi
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 bg-action-glow text-action-orange text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-action-amber animate-pulse"></span> Menunggu Bayar
                                    </span>
                                    @endif
                                    <span class="text-xs text-on-surface-variant">{{ $booking[6] }}</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 text-xs">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-on-surface-variant">Tanggal</span>
                                    <span class="font-semibold text-on-surface">{{ $booking[2] }}</span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-on-surface-variant">Waktu</span>
                                    <span class="font-semibold text-on-surface">{{ $booking[3] }}</span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-on-surface-variant">Total</span>
                                    <span class="font-bold text-on-surface">{{ $booking[4] }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2 pt-1 border-t border-surface-container-high">
                                <a href="{{ route('user.booking.detail', $loop->index + 1) }}" class="flex items-center gap-1.5 text-xs font-semibold text-pitch-deep hover:underline">
                                    <span class="material-symbols-outlined text-[14px]">visibility</span>
                                    Lihat Detail
                                </a>
                                @if($booking[5] === 'pending')
                                <a href="{{ route('booking.konfirmasi') }}" class="flex items-center gap-1.5 text-xs font-semibold text-action-orange hover:underline ml-auto">
                                    <span class="material-symbols-outlined text-[14px]">payments</span>
                                    Bayar Sekarang
                                </a>
                                @else
                                <button class="flex items-center gap-1.5 text-xs font-semibold text-error hover:underline ml-auto">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                                    Batalkan
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        {{-- Empty state --}}
                        @if(count($upcomingBookings) === 0)
                        <div class="flex flex-col items-center gap-3 py-16 text-center">
                            <span class="material-symbols-outlined text-[60px] text-surface-container-highest">event_busy</span>
                            <div>
                                <p class="text-sm font-semibold text-on-surface">Belum ada booking yang akan datang</p>
                                <p class="text-xs text-on-surface-variant mt-1">Booking lapangan sekarang dan nikmati main!</p>
                            </div>
                            <a href="{{ route('lapangan') }}" class="inline-flex items-center gap-2 bg-action-orange text-surface-white font-bold text-sm px-5 py-2.5 rounded-xl mt-2">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                Booking Sekarang
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- History bookings --}}
                    <div x-show="activeTab === 'history'" class="flex flex-col gap-4" style="display:none;">
                        @php
                            $historyBookings = [
                                ['LPN-2026-4120', 'Mini Soccer Field', 'Jumat, 1 Nov 2026', '15:00 – 17:00', 'Rp 300.000', 'done'],
                                ['LPN-2026-3890', 'Futsal Arena B', 'Rabu, 30 Okt 2026', '19:00 – 21:00', 'Rp 400.000', 'done'],
                                ['LPN-2026-3650', 'Badminton Court 1', 'Sabtu, 25 Okt 2026', '08:00 – 10:00', 'Rp 160.000', 'done'],
                            ];
                        @endphp
                        @foreach($historyBookings as $booking)
                        <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4 opacity-80 hover:opacity-100 transition-opacity">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-surface-container-low flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-on-surface-variant text-[22px]">sports_soccer</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $booking[1] }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $booking[0] }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 bg-surface-container-low text-on-surface-variant text-xs font-bold px-2.5 py-1 rounded-full">
                                    <span class="material-symbols-outlined text-[14px]">task_alt</span> Selesai
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-3 text-xs">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-on-surface-variant">Tanggal</span>
                                    <span class="font-semibold text-on-surface">{{ $booking[2] }}</span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-on-surface-variant">Waktu</span>
                                    <span class="font-semibold text-on-surface">{{ $booking[3] }}</span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-on-surface-variant">Total</span>
                                    <span class="font-bold text-on-surface">{{ $booking[4] }}</span>
                                </div>
                            </div>
                            <div class="flex gap-3 pt-1 border-t border-surface-container-high">
                                <button class="flex items-center gap-1.5 text-xs font-semibold text-pitch-deep hover:underline">
                                    <span class="material-symbols-outlined text-[14px]">star_rate</span>
                                    Beri Ulasan
                                </button>
                                <button class="flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant hover:text-on-surface hover:underline ml-auto">
                                    <span class="material-symbols-outlined text-[14px]">refresh</span>
                                    Booking Lagi
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Cancelled bookings --}}
                    <div x-show="activeTab === 'cancelled'" class="flex flex-col items-center gap-3 py-16 text-center" style="display:none;">
                        <span class="material-symbols-outlined text-[60px] text-surface-container-highest">cancel</span>
                        <p class="text-sm font-semibold text-on-surface">Tidak ada booking yang dibatalkan</p>
                        <p class="text-xs text-on-surface-variant">Booking Anda semua berjalan lancar!</p>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="flex flex-col gap-4">

                    {{-- Quick actions --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4">
                        <h3 class="text-sm font-bold text-on-surface uppercase tracking-wider">Aksi Cepat</h3>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('lapangan') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pitch-surface hover:text-pitch-deep transition-colors group">
                                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-pitch-emerald text-[20px]">add_circle</span>
                                <span class="text-sm font-medium">Booking Lapangan Baru</span>
                            </a>
                            <a href="{{ route('kontak') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-surface-container-low transition-colors group">
                                <span class="material-symbols-outlined text-on-surface-variant text-[20px]">support_agent</span>
                                <span class="text-sm font-medium">Hubungi CS</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-surface-container-low transition-colors group">
                                <span class="material-symbols-outlined text-on-surface-variant text-[20px]">manage_accounts</span>
                                <span class="text-sm font-medium">Edit Profil</span>
                            </a>
                        </div>
                    </div>

                    {{-- Upcoming reminder --}}
                    <div class="bg-action-glow rounded-2xl border border-action-amber/20 p-5 flex flex-col gap-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-action-amber text-[20px]">notifications_active</span>
                            <h3 class="text-sm font-bold text-on-surface">Reminder Upcoming</h3>
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <div class="flex items-center gap-3 p-3 bg-surface-white/70 rounded-xl">
                                <div class="w-9 h-9 rounded-lg bg-pitch-deep flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-surface-white text-[16px]">sports_soccer</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-on-surface truncate">Futsal Arena A</p>
                                    <p class="text-xs text-on-surface-variant">Senin, 11 Nov • 09:00</p>
                                </div>
                                <span class="text-xs font-bold text-action-orange shrink-0">2 hari</span>
                            </div>
                        </div>
                    </div>

                    {{-- Promo Banner --}}
                    <div class="bg-pitch-deep rounded-2xl p-5 flex flex-col gap-3 relative overflow-hidden">
                        <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-pitch-emerald/20"></div>
                        <span class="text-xs font-bold text-pitch-light uppercase tracking-wider">Spesial Member</span>
                        <p class="text-sm font-bold text-surface-white leading-snug">Booking 3x berturut-turut, dapatkan diskon 15%!</p>
                        <a href="{{ route('lapangan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-action-amber hover:underline">
                            Klaim Sekarang
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <x-footer />

</body>
</html>

