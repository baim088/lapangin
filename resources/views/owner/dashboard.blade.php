<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Portal - Dashboard Bisnis | {{ config('app.name', 'Lapangin') }}</title>
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
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a192f] text-white flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto shadow-2xl"
        >
            {{-- Logo & Role Badge --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
                <div>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest block">Owner Portal</span>
                    <span class="text-[9px] text-white/50 block">Multi-Venue Control</span>
                </div>
            </div>

            {{-- Nav Items --}}
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <div class="flex flex-col gap-1.5">
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
                        class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all {{ $item[3] ? 'bg-amber-400 text-slate-950 shadow-md shadow-amber-400/20' : 'text-white/70 hover:text-white hover:bg-white/5' }}"
                    >
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                    </a>
                    @endforeach
                </div>

                <div class="mt-8 pt-4 border-t border-white/10 flex flex-col gap-1">
                    <a href="{{ route('beranda') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-white/70 hover:text-white hover:bg-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                        Lihat Situs Publik
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-rose-300 hover:text-rose-100 hover:bg-rose-500/10 transition-all text-left">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>

            {{-- Profile Card --}}
            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5">
                    <div class="w-9 h-9 rounded-full bg-amber-400/20 text-amber-300 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-amber-300/80 truncate">Venue Owner ({{ $totalVenuesCount }} Venue)</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/60 z-40 backdrop-blur-xs" style="display:none;"></div>

        {{-- ============ MAIN AREA ============ --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top Navbar --}}
            <header class="bg-surface-white border-b border-surface-container-high px-6 py-4 flex items-center justify-between gap-4 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-surface-container-low text-on-surface-variant">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Dashboard Eksekutif Owner</h1>
                        <p class="text-xs text-on-surface-variant">Ikhtisar performa bisnis dan pendapatan lintas venue Anda</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-800 px-3.5 py-1.5 rounded-full text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        {{ $totalVenuesCount }} Venue Aktif Terdaftar
                    </div>
                    <a href="{{ route('owner.laporan') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">finance</span>
                        Lihat Laporan
                    </a>
                </div>
            </header>

            {{-- Main Dashboard Body --}}
            <main class="flex-1 p-6 space-y-6 overflow-auto">

                {{-- Status Alert if any --}}
                @if(session('status'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                {{-- Executive KPI Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- KPI 1: Omzet Bulan Ini --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-on-surface-variant">Omzet Bulan Ini</span>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">payments</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="font-headline text-2xl font-extrabold text-emerald-600">
                                Rp {{ number_format($monthRevenue, 0, ',', '.') }}
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">Total pendapatan di bulan {{ now()->translatedFormat('F Y') }}</p>
                        </div>
                    </div>

                    {{-- KPI 2: Total Kas Riil Akumulatif --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-on-surface-variant">Total Uang Masuk</span>
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="font-headline text-2xl font-extrabold text-blue-600">
                                Rp {{ number_format($totalRealCashIn, 0, ',', '.') }}
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">Akumulasi riil DP, Full, & Pelunasan</p>
                        </div>
                    </div>

                    {{-- KPI 3: Piutang Sisa Tagihan (DP Belum Lunas) --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-on-surface-variant">Sisa Tagihan (Piutang)</span>
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">pending_actions</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="font-headline text-2xl font-extrabold text-amber-600">
                                Rp {{ number_format($totalReceivables, 0, ',', '.') }}
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">Menunggu pelunasan di lokasi</p>
                        </div>
                    </div>

                    {{-- KPI 4: Booking Hari Ini --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-on-surface-variant">Jadwal Main Hari Ini</span>
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">calendar_today</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="font-headline text-2xl font-extrabold text-purple-700">
                                {{ $todayBookingsCount }} Booking
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">{{ $totalCourtsCount }} lapangan di {{ $totalVenuesCount }} venue</p>
                        </div>
                    </div>
                </div>

                {{-- Venue Comparison Cards --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="font-headline text-base font-bold text-on-surface">Performa Lapangan per Venue</h2>
                            <p class="text-xs text-on-surface-variant">Rincian pendapatan dan status operasional masing-masing venue Anda</p>
                        </div>
                        <a href="{{ route('owner.lapangan') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1">
                            Kelola Lapangan <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($venueCards as $card)
                        <div class="border border-surface-container-high rounded-xl p-5 hover:border-amber-400/50 hover:shadow-md transition-all bg-surface-container-lowest/30">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-headline font-bold text-base text-on-surface">{{ $card['venue']->name }}</h3>
                                    <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-[14px]">location_on</span>
                                        {{ $card['venue']->city ?? 'Indonesia' }}
                                    </p>
                                </div>
                                <span class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-1 rounded-full">
                                    {{ $card['courts_count'] }} Lapangan
                                </span>
                            </div>

                            <div class="mt-4 pt-4 border-t border-surface-container-high/60 grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-on-surface-variant block">Total Booking:</span>
                                    <span class="font-bold text-on-surface text-sm">{{ $card['bookings_count'] }} Transaksi</span>
                                </div>
                                <div>
                                    <span class="text-on-surface-variant block">Omzet Masuk:</span>
                                    <span class="font-bold text-emerald-600 text-sm">Rp {{ number_format($card['revenue'], 0, ',', '.') }}</span>
                                </div>
                                @if($card['receivables'] > 0)
                                <div class="col-span-2 bg-amber-50/70 p-2 rounded-lg text-amber-800 text-[11px] flex items-center justify-between">
                                    <span>Piutang DP Aktif:</span>
                                    <span class="font-bold">Rp {{ number_format($card['receivables'], 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 flex gap-2">
                                <a href="{{ route('owner.laporan', ['venue_id' => $card['venue']->id]) }}" class="flex-1 text-center py-2 rounded-lg bg-surface-container-high hover:bg-surface-container-highest text-xs font-semibold text-on-surface transition-colors">
                                    Lihat Laporan
                                </a>
                                <a href="{{ route('owner.booking', ['venue_id' => $card['venue']->id]) }}" class="flex-1 text-center py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-xs font-semibold text-white transition-colors">
                                    Daftar Booking
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Recent Bookings Table --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-surface-container-high flex items-center justify-between">
                        <div>
                            <h2 class="font-headline text-base font-bold text-on-surface">10 Transaksi Booking Terbaru</h2>
                            <p class="text-xs text-on-surface-variant">Memantau arus reservasi di seluruh venue Anda</p>
                        </div>
                        <a href="{{ route('owner.booking') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1">
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
                                <tr class="hover:bg-surface-container-lowest/50 transition-colors">
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
                                            {{ $firstItem ? substr($firstItem->start_time, 0, 5) . ' - ' . substr($firstItem->end_time, 0, 5) : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-on-surface">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</div>
                                        <div class="text-[10px] uppercase font-bold text-amber-600">
                                            {{ $b->payment_scheme === 'dp' ? 'Skema DP' : 'Full Payment' }}
                                        </div>
                                        @if($b->status->value === 'dp_paid')
                                        <div class="text-[10px] text-rose-500 font-semibold">
                                            Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $statusMap = [
                                                'pending' => ['bg-amber-100 text-amber-800', 'Menunggu Bayar'],
                                                'pending_payment' => ['bg-amber-100 text-amber-800', 'Menunggu Bayar'],
                                                'dp_paid' => ['bg-blue-100 text-blue-800', 'DP Lunas (Belum Lunas)'],
                                                'paid_in_full' => ['bg-emerald-100 text-emerald-800', 'Lunas Penuh'],
                                                'confirmed' => ['bg-emerald-100 text-emerald-800', 'Terkonfirmasi'],
                                                'completed' => ['bg-slate-100 text-slate-800', 'Selesai'],
                                                'done' => ['bg-slate-100 text-slate-800', 'Selesai'],
                                                'cancelled' => ['bg-rose-100 text-rose-800', 'Dibatalkan'],
                                                'expired' => ['bg-gray-100 text-gray-800', 'Kedaluwarsa'],
                                            ];
                                            $curr = $statusMap[$b->status->value] ?? ['bg-gray-100 text-gray-800', $b->status->value];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $curr[0] }}">
                                            {{ $curr[1] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-on-surface-variant">
                                        @if($b->settled_by && $b->settledBy)
                                        <div class="flex items-center gap-1.5 text-emerald-700 font-medium">
                                            <span class="material-symbols-outlined text-[16px]">verified_user</span>
                                            <span>{{ $b->settledBy->name }}</span>
                                        </div>
                                        <div class="text-[10px] text-on-surface-variant">
                                            {{ $b->settled_at ? \Carbon\Carbon::parse($b->settled_at)->translatedFormat('d M H:i') : '' }}
                                        </div>
                                        @elseif($b->status->value === 'dp_paid')
                                        <span class="text-amber-600 font-semibold italic text-[11px]">Belum Pelunasan</span>
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
