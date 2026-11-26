<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950" x-data="{ sidebarOpen: false, activeTab: 'upcoming' }">

    {{-- Layout with sidebar --}}
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
                        @click="activeTab = 'upcoming'"
                        class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-bold transition-all bg-[#f8b710] text-slate-950 shadow-sm"
                    >
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px]">dashboard</span>
                            Dashboard &amp; Booking
                        </div>
                        @if($upcomingBookings->count() > 0)
                        <span class="bg-[#0f2444] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $upcomingBookings->count() }}</span>
                        @endif
                    </a>

                    <a
                        href="{{ route('user.cari-lapangan') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all text-slate-300 hover:text-white hover:bg-white/5"
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
                        <h1 class="font-teko text-2xl sm:text-3xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">Dashboard Penyewa</h1>
                        <p class="text-xs text-slate-500 hidden sm:block">Kelola riwayat booking &amp; reservasi lapangan Anda</p>
                    </div>
                </div>
                <div class="text-xs text-slate-500 font-medium hidden sm:block">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </header>

            {{-- Content Container --}}
            <main class="p-4 sm:p-8 flex-1 flex flex-col gap-6 max-w-7xl w-full mx-auto">

                {{-- User Welcome & Stats Banner --}}
                <div class="bg-[#0f2444] rounded-2xl p-6 sm:p-8 border border-white/10 relative overflow-hidden text-white shadow-lg">
                    <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-[#f8b710]/10 blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#f8b710]/20 border border-[#f8b710]/40 flex items-center justify-center shrink-0">
                                <span class="font-teko font-bold text-2xl text-[#f8b710]">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-300 uppercase tracking-wider">Selamat datang kembali,</p>
                                <h2 class="font-teko text-2xl sm:text-3xl font-bold uppercase tracking-wide text-white leading-none mt-0.5">{{ $user->name }}</h2>
                                <p class="text-xs text-[#f8b710] mt-1 flex items-center gap-1 font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">verified</span>
                                    {{ $user->role->label() ?? 'Penyewa' }} &bull; Member sejak {{ $user->created_at ? $user->created_at->translatedFormat('M Y') : '2026' }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('user.cari-lapangan') }}" class="self-start md:self-center inline-flex items-center gap-2 bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-xs sm:text-sm uppercase tracking-wider px-6 py-3 rounded-xl transition-all shadow-md">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            + Booking Lapangan Baru
                        </a>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-white/10">
                        @foreach([
                            ['history', $totalBookings, 'Total Booking'],
                            ['task_alt', $successfulBookings, 'Berhasil'],
                            ['cancel', $cancelledBookings, 'Dibatalkan'],
                            ['payments', 'Rp ' . number_format($totalSpent, 0, ',', '.'), 'Total Transaksi'],
                        ] as $stat)
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <span class="material-symbols-outlined text-[#f8b710] text-[18px]">{{ $stat[0] }}</span>
                            <div class="font-teko text-2xl sm:text-3xl font-bold text-white mt-1 leading-none">{{ $stat[1] }}</div>
                            <div class="text-xs text-slate-300 mt-1">{{ $stat[2] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Dashboard Grid Content --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Main: Booking List --}}
                    <div class="lg:col-span-2 flex flex-col gap-6">

                        {{-- Tab selector --}}
                        <div class="flex gap-1 bg-surface-container-low p-1 rounded-xl w-fit border border-surface-container-high/60">
                            <button
                                @click="activeTab = 'upcoming'"
                                :class="activeTab === 'upcoming' ? 'bg-surface-white text-pitch-deep shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                                class="text-sm px-4 py-2 rounded-lg transition-all"
                            >
                                Upcoming ({{ $upcomingBookings->count() }})
                            </button>
                            <button
                                @click="activeTab = 'history'"
                                :class="activeTab === 'history' ? 'bg-surface-white text-pitch-deep shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                                class="text-sm px-4 py-2 rounded-lg transition-all"
                            >
                                Riwayat ({{ $historyBookings->count() }})
                            </button>
                            <button
                                @click="activeTab = 'cancelled'"
                                :class="activeTab === 'cancelled' ? 'bg-surface-white text-pitch-deep shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface font-medium'"
                                class="text-sm px-4 py-2 rounded-lg transition-all"
                            >
                                Dibatalkan ({{ $cancelledList->count() }})
                            </button>
                        </div>

                        {{-- Upcoming bookings --}}
                        <div x-show="activeTab === 'upcoming'" class="flex flex-col gap-4">
                            @forelse($upcomingBookings as $booking)
                            @php
                                $courtName = $booking->items->first()?->court?->name ?? 'Lapangan';
                                $bookingDateFormatted = \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d M Y');
                                $timesFormatted = $booking->items->pluck('start_time')->map(fn($t) => substr($t, 0, 5))->join(', ') . ' WIB';
                                $diffDays = \Carbon\Carbon::parse($booking->booking_date)->diffForHumans(now(), ['parts' => 1]);
                            @endphp
                            <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4 hover:border-pitch-light/40 transition-colors shadow-xs">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-pitch-surface flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-pitch-deep text-[22px]">sports_soccer</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">{{ $courtName }}</p>
                                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $booking->booking_code }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1">
                                        @if($booking->status->value === 'confirmed')
                                        <span class="inline-flex items-center gap-1 bg-pitch-surface text-pitch-deep text-xs font-bold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Terkonfirmasi
                                        </span>
                                        @elseif($booking->status->value === 'pending')
                                        <span class="inline-flex items-center gap-1 bg-action-amber/20 text-action-amber text-xs font-bold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-action-amber"></span> Menunggu Pembayaran
                                        </span>
                                        @endif
                                        <span class="text-[11px] text-on-surface-variant">{{ $diffDays }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3 bg-surface-container-lowest rounded-xl text-xs">
                                    <div>
                                        <span class="text-on-surface-variant block">Tanggal</span>
                                        <span class="font-semibold text-on-surface">{{ $bookingDateFormatted }}</span>
                                    </div>
                                    <div>
                                        <span class="text-on-surface-variant block">Jam Main</span>
                                        <span class="font-semibold text-on-surface">{{ $timesFormatted }}</span>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <span class="text-on-surface-variant block">Total</span>
                                        <span class="font-bold text-pitch-deep">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1 border-t border-surface-container-high/60 gap-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('user.booking.detail', $booking->id) }}" class="text-xs font-bold text-pitch-deep hover:underline inline-flex items-center gap-1">
                                            Detail & E-Tiket
                                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                        </a>
                                        @if($booking->status->value === 'pending' && $booking->payment?->status?->value === 'unpaid')
                                        <a href="{{ route('booking.konfirmasi', ['code' => $booking->booking_code]) }}" class="text-xs font-bold text-action-orange hover:underline">
                                            Bayar Sekarang
                                        </a>
                                        @endif
                                    </div>

                                    @if($booking->status->value === 'pending')
                                    <form action="{{ route('user.booking.cancel', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-1.5 text-xs font-semibold text-error hover:underline">
                                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                                            Batalkan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center gap-3 py-16 text-center bg-surface-white rounded-2xl border border-dashed border-surface-container-high">
                                <span class="material-symbols-outlined text-[60px] text-surface-container-highest">event_available</span>
                                <p class="text-sm font-semibold text-on-surface">Belum ada booking aktif</p>
                                <p class="text-xs text-on-surface-variant">Ayo booking lapangan favorit Anda sekarang!</p>
                                <a href="{{ route('user.cari-lapangan') }}" class="mt-2 bg-[#0f2444] text-white font-bold text-xs px-5 py-2.5 rounded-xl hover:bg-[#f37920] transition-all">
                                    Cari Lapangan
                                </a>
                            </div>
                            @endforelse
                        </div>

                        {{-- History bookings --}}
                        <div x-show="activeTab === 'history'" class="flex flex-col gap-4" style="display:none;">
                            @forelse($historyBookings as $booking)
                            @php
                                $courtName = $booking->items->first()?->court?->name ?? 'Lapangan';
                                $bookingDateFormatted = \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d M Y');
                                $timesFormatted = $booking->items->pluck('start_time')->map(fn($t) => substr($t, 0, 5))->join(', ') . ' WIB';
                            @endphp
                            <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4 shadow-xs">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-surface-container-low flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-on-surface-variant text-[22px]">sports_soccer</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">{{ $courtName }}</p>
                                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $booking->booking_code }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1 bg-surface-container-high text-on-surface-variant text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="material-symbols-outlined text-[14px]">check</span> Selesai
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3 bg-surface-container-lowest rounded-xl text-xs">
                                    <div>
                                        <span class="text-on-surface-variant block">Tanggal</span>
                                        <span class="font-semibold text-on-surface">{{ $bookingDateFormatted }}</span>
                                    </div>
                                    <div>
                                        <span class="text-on-surface-variant block">Jam</span>
                                        <span class="font-semibold text-on-surface">{{ $timesFormatted }}</span>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <span class="text-on-surface-variant block">Total</span>
                                        <span class="font-bold text-on-surface">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1 border-t border-surface-container-high/60">
                                    <a href="{{ route('user.booking.detail', $booking->id) }}" class="text-xs font-bold text-pitch-deep hover:underline inline-flex items-center gap-1">
                                        Lihat Tiket
                                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </a>
                                    <a href="{{ route('user.cari-lapangan') }}" class="text-xs font-semibold text-slate-500 hover:text-[#f37920]">
                                        Booking Lagi
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center gap-3 py-16 text-center bg-surface-white rounded-2xl border border-dashed border-surface-container-high">
                                <span class="material-symbols-outlined text-[60px] text-surface-container-highest">history</span>
                                <p class="text-sm font-semibold text-on-surface">Belum ada riwayat booking selesai</p>
                                <p class="text-xs text-on-surface-variant">Booking yang telah selesai akan muncul di sini.</p>
                            </div>
                            @endforelse
                        </div>

                        {{-- Cancelled bookings --}}
                        <div x-show="activeTab === 'cancelled'" class="flex flex-col gap-4" style="display:none;">
                            @forelse($cancelledList as $booking)
                            @php
                                $courtName = $booking->items->first()?->court?->name ?? 'Lapangan';
                                $bookingDateFormatted = \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d M Y');
                                $timesFormatted = $booking->items->pluck('start_time')->map(fn($t) => substr($t, 0, 5))->join(', ') . ' WIB';
                            @endphp
                            <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4 shadow-xs opacity-80">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-surface-container-low flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-error text-[22px]">cancel</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">{{ $courtName }}</p>
                                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $booking->booking_code }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1 bg-error-container/20 text-error text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="material-symbols-outlined text-[14px]">cancel</span>
                                        {{ $booking->status->label() }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3 bg-surface-container-lowest rounded-xl text-xs">
                                    <div>
                                        <span class="text-on-surface-variant block">Tanggal</span>
                                        <span class="font-semibold text-on-surface">{{ $bookingDateFormatted }}</span>
                                    </div>
                                    <div>
                                        <span class="text-on-surface-variant block">Jam</span>
                                        <span class="font-semibold text-on-surface">{{ $timesFormatted }}</span>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <span class="text-on-surface-variant block">Total</span>
                                        <span class="font-bold text-on-surface">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                @if($booking->cancelled_reason)
                                <div class="text-xs text-error bg-error-container/10 p-2 rounded-lg">
                                    Alasan: {{ $booking->cancelled_reason }}
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="flex flex-col items-center gap-3 py-16 text-center bg-surface-white rounded-2xl border border-dashed border-surface-container-high">
                                <span class="material-symbols-outlined text-[60px] text-surface-container-highest">cancel</span>
                                <p class="text-sm font-semibold text-on-surface">Tidak ada booking yang dibatalkan</p>
                                <p class="text-xs text-on-surface-variant">Booking Anda semua berjalan lancar!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Sidebar Widgets Column --}}
                    <div class="flex flex-col gap-5">

                        {{-- Quick actions card --}}
                        <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4 shadow-xs">
                            <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider text-pitch-deep">Aksi Cepat</h3>
                            <div class="flex flex-col gap-1.5">
                                <a href="{{ route('user.cari-lapangan') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 hover:text-[#0f2444] transition-colors group">
                                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-pitch-emerald text-[20px]">add_circle</span>
                                    <span class="text-sm font-medium">Booking Lapangan Baru</span>
                                </a>
                                <a href="{{ route('kontak') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-surface-container-low transition-colors group">
                                    <span class="material-symbols-outlined text-on-surface-variant text-[20px]">support_agent</span>
                                    <span class="text-sm font-medium">Hubungi Layanan CS</span>
                                </a>
                            </div>
                        </div>

                        {{-- Upcoming reminder card --}}
                        @php $firstUpcoming = $upcomingBookings->first(); @endphp
                        @if($firstUpcoming)
                        <div class="bg-action-glow rounded-2xl border border-action-amber/20 p-5 flex flex-col gap-3 shadow-xs">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-action-amber text-[20px]">notifications_active</span>
                                <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider">Jadwal Terdekat</h3>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-surface-white/80 rounded-xl">
                                <div class="w-10 h-10 rounded-lg bg-pitch-deep flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-surface-white text-[18px]">sports_soccer</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-on-surface truncate">{{ $firstUpcoming->items->first()?->court?->name ?? 'Lapangan' }}</p>
                                    <p class="text-[11px] text-on-surface-variant">{{ \Carbon\Carbon::parse($firstUpcoming->booking_date)->translatedFormat('D, d M') }} • {{ substr($firstUpcoming->items->first()?->start_time ?? '', 0, 5) }}</p>
                                </div>
                                <span class="text-xs font-bold text-action-orange shrink-0">{{ \Carbon\Carbon::parse($firstUpcoming->booking_date)->diffForHumans(now(), ['parts' => 1]) }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- Member Benefit Box --}}
                        <div class="bg-pitch-deep rounded-2xl p-5 flex flex-col gap-3 relative overflow-hidden text-surface-white shadow-sm">
                            <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-pitch-emerald/20"></div>
                            <span class="text-xs font-bold text-pitch-light uppercase tracking-wider">Jaminan Booking</span>
                            <p class="text-sm font-bold text-surface-white leading-snug">Jadwal Anda terkunci otomatis & aman dari double-booking.</p>
                            <a href="{{ route('user.cari-lapangan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#f8b710] hover:underline mt-1">
                                Booking Jadwal Berikutnya
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

</body>
</html>
