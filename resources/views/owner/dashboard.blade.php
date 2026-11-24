<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - {{ config('app.name', 'Lapangin') }}</title>
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
            {{-- Logo & Role Badge --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-pitch-deep/50">
                <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
                <div>
                    <span class="text-xs font-bold text-action-amber uppercase tracking-wider block">Owner</span>
                    <span class="text-[10px] text-surface-container-highest block">Multi-Venue Portal</span>
                </div>
            </div>

            {{-- Navigation Menu --}}
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

            {{-- User info bottom bar --}}
            <div class="px-4 py-4 border-t border-pitch-deep/50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-action-amber/30 flex items-center justify-center">
                        <span class="font-bold text-action-amber text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-surface-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-surface-container-highest truncate">Owner ({{ $totalVenuesCount }} Venue)</p>
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
                        <h1 class="font-headline text-lg font-bold text-on-surface">Dashboard Eksekutif Owner</h1>
                        <p class="text-xs text-on-surface-variant">Ikhtisar performa bisnis dan pendapatan lintas venue Anda</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5 bg-action-glow px-3 py-1.5 rounded-full text-xs text-action-orange font-semibold border border-action-amber/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-action-orange animate-pulse"></span>
                        {{ $totalVenuesCount }} Venue Aktif Terdaftar
                    </div>
                    <a href="{{ route('owner.laporan') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-pitch-deep hover:bg-pitch-emerald text-surface-white text-xs font-bold transition-all shadow-xs">
                        <span class="material-symbols-outlined text-[16px]">bar_chart</span>
                        <span class="hidden sm:inline">Lihat</span> Laporan Keuangan
                    </a>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                @if(session('status'))
                <div class="p-4 bg-pitch-surface border border-pitch-light/40 rounded-xl text-sm font-semibold text-pitch-deep flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                {{-- Executive KPI Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- 1. Omzet Bulan Ini --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Omzet Bulan Ini</span>
                            <div class="w-9 h-9 rounded-xl bg-pitch-surface flex items-center justify-center">
                                <span class="material-symbols-outlined text-pitch-emerald text-[20px]">payments</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="font-headline text-2xl font-extrabold text-pitch-emerald">
                                Rp {{ number_format($monthRevenue, 0, ',', '.') }}
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">Bulan {{ now()->translatedFormat('F Y') }}</p>
                        </div>
                    </div>

                    {{-- 2. Total Kas Riil Akumulatif --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total Kas Masuk Riil</span>
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 text-[20px]">account_balance_wallet</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="font-headline text-2xl font-extrabold text-blue-600">
                                Rp {{ number_format($totalRealCashIn, 0, ',', '.') }}
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">Akumulasi DP, Pelunasan, & Full</p>
                        </div>
                    </div>

                    {{-- 3. Sisa Tagihan (Piutang) --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Sisa Tagihan (Piutang)</span>
                            <div class="w-9 h-9 rounded-xl bg-action-glow flex items-center justify-center">
                                <span class="material-symbols-outlined text-action-orange text-[20px]">pending_actions</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="font-headline text-2xl font-extrabold text-action-orange">
                                Rp {{ number_format($totalReceivables, 0, ',', '.') }}
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">Menunggu pelunasan di lokasi</p>
                        </div>
                    </div>

                    {{-- 4. Jadwal Main Hari Ini --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Jadwal Main Hari Ini</span>
                            <div class="w-9 h-9 rounded-xl bg-court-booked-surface flex items-center justify-center">
                                <span class="material-symbols-outlined text-pitch-deep text-[20px]">calendar_today</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="font-headline text-2xl font-extrabold text-on-surface">
                                {{ $todayBookingsCount }} Booking
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">{{ $totalCourtsCount }} lapangan di {{ $totalVenuesCount }} venue</p>
                        </div>
                    </div>
                </div>

                {{-- Venue Breakdown Cards --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-headline text-base font-bold text-on-surface">Performa Lapangan per Venue</h2>
                            <p class="text-xs text-on-surface-variant">Rincian pendapatan dan status operasional masing-masing venue Anda</p>
                        </div>
                        <a href="{{ route('owner.lapangan') }}" class="text-xs font-bold text-action-orange hover:text-tertiary-container flex items-center gap-1">
                            Kelola Lapangan <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($venueCards as $card)
                        <div class="border border-surface-container-high rounded-2xl p-5 hover:border-pitch-emerald/50 hover:shadow-md transition-all bg-surface-container-low/40 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="font-headline font-bold text-base text-on-surface">{{ $card['venue']->name }}</h3>
                                        <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-1">
                                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                                            {{ $card['venue']->address ?? $card['venue']->city ?? 'Indonesia' }}
                                        </p>
                                    </div>
                                    <span class="bg-action-glow text-action-orange text-[11px] font-bold px-2.5 py-1 rounded-full border border-action-amber/30 whitespace-nowrap">
                                        {{ $card['courts_count'] }} Lapangan
                                    </span>
                                </div>

                                <div class="mt-4 pt-4 border-t border-surface-container-high/80 grid grid-cols-2 gap-3 text-xs">
                                    <div class="bg-surface-white p-3 rounded-xl border border-surface-container-high">
                                        <span class="text-on-surface-variant block text-[11px]">Total Booking:</span>
                                        <span class="font-bold text-on-surface text-sm mt-0.5 block">{{ $card['bookings_count'] }} Transaksi</span>
                                    </div>
                                    <div class="bg-surface-white p-3 rounded-xl border border-surface-container-high">
                                        <span class="text-on-surface-variant block text-[11px]">Kas Masuk:</span>
                                        <span class="font-bold text-pitch-emerald text-sm mt-0.5 block">Rp {{ number_format($card['revenue'], 0, ',', '.') }}</span>
                                    </div>
                                    @if($card['receivables'] > 0)
                                    <div class="col-span-2 bg-action-glow/70 p-2.5 rounded-xl border border-action-orange/20 text-action-orange text-xs flex items-center justify-between">
                                        <span class="font-medium">Piutang DP Aktif:</span>
                                        <span class="font-bold">Rp {{ number_format($card['receivables'], 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 pt-3 border-t border-surface-container-high/80 flex gap-2">
                                <a href="{{ route('owner.laporan', ['venue_id' => $card['venue']->id]) }}" class="flex-1 text-center py-2 rounded-xl bg-surface-white hover:bg-surface-container-high text-xs font-bold text-on-surface border border-surface-container-high transition-colors">
                                    Laporan
                                </a>
                                <a href="{{ route('owner.booking', ['venue_id' => $card['venue']->id]) }}" class="flex-1 text-center py-2 rounded-xl bg-pitch-deep hover:bg-pitch-emerald text-xs font-bold text-surface-white transition-colors">
                                    Daftar Booking
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Recent Bookings Table --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high shadow-xs overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-surface-container-high flex items-center justify-between">
                        <div>
                            <h2 class="font-headline text-base font-bold text-on-surface">10 Transaksi Booking Terbaru</h2>
                            <p class="text-xs text-on-surface-variant">Memantau arus reservasi di seluruh venue Anda</p>
                        </div>
                        <a href="{{ route('owner.booking') }}" class="text-xs font-bold text-action-orange hover:text-tertiary-container flex items-center gap-1">
                            Lihat Semua <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-surface-container-low text-on-surface-variant font-semibold border-b border-surface-container-high uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5">Kode Booking</th>
                                    <th class="px-5 py-3.5">Venue & Lapangan</th>
                                    <th class="px-5 py-3.5">Pemesan</th>
                                    <th class="px-5 py-3.5">Tanggal Main</th>
                                    <th class="px-5 py-3.5">Total & Skema</th>
                                    <th class="px-5 py-3.5">Status</th>
                                    <th class="px-5 py-3.5">Pelunasan di Lokasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-container-high">
                                @forelse($recentBookings as $b)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="px-5 py-4 font-mono font-bold text-on-surface">
                                        {{ $b->booking_code }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-on-surface">{{ $b->venue->name ?? '-' }}</div>
                                        <div class="text-[11px] text-on-surface-variant">
                                            {{ $b->items->first()?->court?->name ?? 'Lapangan' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-on-surface">{{ $b->customer_name }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->customer_phone }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $firstItem = $b->items->first();
                                        @endphp
                                        <div class="font-medium text-on-surface">
                                            {{ $firstItem ? \Carbon\Carbon::parse($firstItem->booking_date)->translatedFormat('d M Y') : '-' }}
                                        </div>
                                        <div class="text-[11px] text-on-surface-variant">
                                            {{ $firstItem ? substr($firstItem->start_time, 0, 5) . ' - ' . substr($firstItem->end_time, 0, 5) : '-' }} WIB
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-on-surface">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</div>
                                        <div class="text-[10px] uppercase font-bold text-action-orange">
                                            {{ $b->payment_scheme === 'dp' ? 'Skema DP' : 'Full Payment' }}
                                        </div>
                                        @if($b->status->value === 'dp_paid')
                                        <div class="text-[10px] text-court-blocked font-semibold">
                                            Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $statusMap = [
                                                'pending' => ['bg-action-glow text-action-orange', 'Menunggu Bayar'],
                                                'pending_payment' => ['bg-action-glow text-action-orange', 'Menunggu Bayar'],
                                                'dp_paid' => ['bg-blue-100 text-blue-800', 'DP Lunas (Belum Lunas)'],
                                                'paid_in_full' => ['bg-pitch-surface text-pitch-deep', 'Lunas Penuh'],
                                                'confirmed' => ['bg-pitch-surface text-pitch-deep', 'Terkonfirmasi'],
                                                'completed' => ['bg-surface-container-high text-on-surface', 'Selesai'],
                                                'done' => ['bg-surface-container-high text-on-surface', 'Selesai'],
                                                'cancelled' => ['bg-court-blocked-surface text-court-blocked', 'Dibatalkan'],
                                                'expired' => ['bg-surface-container-high text-on-surface-variant', 'Kedaluwarsa'],
                                            ];
                                            $curr = $statusMap[$b->status->value] ?? ['bg-surface-container-high text-on-surface-variant', $b->status->value];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $curr[0] }}">
                                            {{ $curr[1] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-on-surface-variant">
                                        @if($b->settled_by && $b->settledBy)
                                        <div class="flex items-center gap-1.5 text-pitch-emerald font-semibold">
                                            <span class="material-symbols-outlined text-[16px]">verified_user</span>
                                            <span>{{ $b->settledBy->name }}</span>
                                        </div>
                                        <div class="text-[10px] text-on-surface-variant">
                                            {{ $b->settled_at ? \Carbon\Carbon::parse($b->settled_at)->translatedFormat('d M H:i') : '' }}
                                        </div>
                                        @elseif($b->status->value === 'dp_paid')
                                        <span class="text-action-orange font-semibold italic text-[11px]">Belum Pelunasan</span>
                                        @else
                                        <span class="text-on-surface-variant/60">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-on-surface-variant">
                                        Belum ada data booking di venue Anda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>
