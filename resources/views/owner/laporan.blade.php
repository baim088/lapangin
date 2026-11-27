<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Owner - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-body text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

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
                        <h1 class="font-headline text-lg font-bold text-on-surface">Laporan Keuangan Owner</h1>
                        <p class="text-xs text-on-surface-variant">Transparansi arus kas riil, DP masuk, pelunasan di lokasi, dan sisa piutang</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('owner.booking.export', ['venue_id' => $selectedVenueId]) }}" class="flex items-center gap-2 border border-surface-container-high text-on-surface font-semibold text-xs px-3.5 py-2 rounded-xl hover:bg-surface-container-low transition-all">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        Ekspor CSV
                    </a>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                {{-- ======================================================== --}}
                {{-- 1. BADGE & FINANCIAL SUMMARY CARDS (DI ATAS SESUAI PERMINTAAN) --}}
                {{-- ======================================================== --}}

                {{-- Cash In Real Highlight Box --}}
                <div class="bg-arena-night rounded-2xl p-6 text-surface-white flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-md border border-white/10" style="background-color: #0f2444;">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-action-amber/20 text-action-amber text-xs font-bold border border-action-amber/30">
                            <span class="material-symbols-outlined text-[16px]">account_balance_wallet</span>
                            Total Kas Masuk Riil (Net Cash Inflow)
                        </div>
                        <h3 class="font-headline text-3xl lg:text-4xl font-extrabold text-action-amber">
                            Rp {{ number_format($totalCashIn, 0, ',', '.') }}
                        </h3>
                        <p class="text-xs text-surface-container-highest">
                            Akumulasi uang riil yang sudah diterima: Uang DP (Rp {{ number_format($dpIncome, 0, ',', '.') }}) + Pelunasan Kasir (Rp {{ number_format($settlementIncome, 0, ',', '.') }}) + Full Payment (Rp {{ number_format($fullPaymentIncome, 0, ',', '.') }})
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 border-t md:border-t-0 md:border-l border-white/10 pt-4 md:pt-0 md:pl-6 shrink-0">
                        <div class="text-left md:text-right">
                            <span class="text-xs text-surface-container-highest block">Rasio Pelunasan:</span>
                            <div class="text-lg font-bold text-surface-white mt-0.5">
                                {{ $totalCashIn + $receivables > 0 ? round(($totalCashIn / ($totalCashIn + $receivables)) * 100, 1) : 0 }}% Terkumpul
                            </div>
                            <span class="text-[11px] text-action-amber block">
                                Piutang: Rp {{ number_format($receivables, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 5 Financial Metric Cards Required by Business Rules --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                    {{-- 1. Total Nilai Transaksi (Gross) --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Gross Nilai Sewa</span>
                            <div class="w-8 h-8 rounded-lg bg-surface-container-low text-on-surface flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">receipt</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="font-headline text-xl font-extrabold text-on-surface">Rp {{ number_format($grossRevenue, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Total nilai transaksi booking</p>
                        </div>
                    </div>

                    {{-- 2. Kas Masuk dari DP --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-blue-600 uppercase tracking-wider">Kas Masuk dari DP</span>
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">lock_clock</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="font-headline text-xl font-extrabold text-blue-600">Rp {{ number_format($dpIncome, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">DP 30%–50% saat booking</p>
                        </div>
                    </div>

                    {{-- 3. Pelunasan di Lokasi (Kasir) --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-pitch-emerald uppercase tracking-wider">Pelunasan di Lokasi</span>
                            <div class="w-8 h-8 rounded-lg bg-pitch-surface text-pitch-emerald flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">point_of_sale</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="font-headline text-xl font-extrabold text-pitch-emerald">Rp {{ number_format($settlementIncome, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Diverifikasi Admin/Kasir venue</p>
                        </div>
                    </div>

                    {{-- 4. Full Payment Awal --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-teal-700 uppercase tracking-wider">Full Payment Awal</span>
                            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="font-headline text-xl font-extrabold text-teal-700">Rp {{ number_format($fullPaymentIncome, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Lunas 100% di muka</p>
                        </div>
                    </div>

                    {{-- 5. Sisa Tagihan (Piutang) --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-action-orange uppercase tracking-wider">Sisa Tagihan (Piutang)</span>
                            <div class="w-8 h-8 rounded-lg bg-action-glow text-action-orange flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="font-headline text-xl font-extrabold text-action-orange">Rp {{ number_format($receivables, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Menunggu pelunasan check-in</p>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- 2. FILTER BAR (DI BAWAH KARTU METRIK) --}}
                {{-- ======================================================== --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 shadow-xs flex flex-col gap-4">
                    <form method="GET" action="{{ route('owner.laporan') }}" class="flex flex-wrap items-end justify-between gap-4">

                        {{-- Venue Selector --}}
                        <div class="flex flex-wrap items-end gap-4 w-full lg:w-auto">
                            <div>
                                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 uppercase tracking-wider">Pilih Venue:</label>
                                <select name="venue_id" onchange="this.form.submit()" class="text-xs font-bold border border-surface-container-high rounded-xl px-3.5 py-2.5 bg-surface-white text-on-surface focus:ring-2 focus:ring-action-orange w-full sm:w-60">
                                    <option value="all" {{ $selectedVenueId === 'all' ? 'selected' : '' }}>Semua Venue Milik Saya</option>
                                    @foreach($venues as $v)
                                    <option value="{{ $v->id }}" {{ (string)$selectedVenueId === (string)$v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Period Switcher Buttons --}}
                            <div>
                                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 uppercase tracking-wider">Periode Laporan:</label>
                                <div class="inline-flex rounded-xl border border-surface-container-high p-1 bg-surface-container-low gap-1">
                                    @foreach([
                                        'harian' => 'Harian (Hari Ini)',
                                        'mingguan' => 'Mingguan (Minggu Ini)',
                                        'bulanan' => 'Bulanan (Bulan Ini)',
                                        'custom' => 'Kustom Tanggal',
                                    ] as $pkey => $plabel)
                                    <a
                                        href="{{ route('owner.laporan', ['venue_id' => $selectedVenueId, 'period' => $pkey]) }}"
                                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $period === $pkey ? 'bg-pitch-deep text-surface-white shadow-xs' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-white/60' }}"
                                    >
                                        {{ $plabel }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Active Period Info Badge --}}
                        <div class="text-xs font-bold text-pitch-deep bg-pitch-surface px-3.5 py-2 rounded-xl border border-pitch-light/30">
                            {{ $periodLabel }}
                        </div>
                    </form>

                    {{-- Custom Date Form (Tampil jika period == custom) --}}
                    @if($period === 'custom')
                    <form method="GET" action="{{ route('owner.laporan') }}" class="pt-4 border-t border-surface-container-high/60 flex flex-wrap items-end gap-3">
                        <input type="hidden" name="period" value="custom">
                        <input type="hidden" name="venue_id" value="{{ $selectedVenueId }}">
                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Tanggal Mulai:</label>
                            <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}" class="text-xs px-3 py-2 rounded-xl border border-surface-container-high focus:ring-2 focus:ring-action-orange">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Tanggal Akhir:</label>
                            <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}" class="text-xs px-3 py-2 rounded-xl border border-surface-container-high focus:ring-2 focus:ring-action-orange">
                        </div>
                        <button type="submit" class="text-xs font-bold px-4 py-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white rounded-xl transition-colors">
                            Terapkan Rentang Tanggal
                        </button>
                    </form>
                    @endif
                </div>

                {{-- ======================================================== --}}
                {{-- 3. DETAILED TRANSACTIONS TABLE --}}
                {{-- ======================================================== --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high shadow-xs overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-surface-container-high flex items-center justify-between">
                        <div>
                            <h2 class="font-headline text-base font-bold text-on-surface">Rincian Transaksi Booking Periode Ini</h2>
                            <p class="text-xs text-on-surface-variant">Audit trail pembayaran, petugas kasir pemroses, dan status pelunasan</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[980px] text-left text-xs sm:text-sm border-collapse">
                            <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 text-[11px] uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Kode & Waktu</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Venue & Lapangan</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Penyewa</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Total Sewa</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Terbayar (DP / Full)</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Sisa Tagihan</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Status Booking</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Verifikasi Kasir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($reportBookings as $b)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-mono font-bold text-on-surface">{{ $b->booking_code }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->created_at->translatedFormat('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-bold text-on-surface">{{ $b->venue->name ?? '-' }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->items->first()?->court?->name ?? 'Lapangan' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-semibold text-on-surface">{{ $b->customer_name }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->customer_phone }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap font-bold text-on-surface">
                                        Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-bold text-pitch-emerald">Rp {{ number_format($b->paid_amount, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-on-surface-variant uppercase font-medium">
                                            {{ $b->payment_scheme === 'dp' ? 'Skema DP' : 'Full Payment' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        @if($b->remaining_amount > 0 && $b->status->value === 'dp_paid')
                                        <span class="font-bold text-action-orange">Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}</span>
                                        @else
                                        <span class="text-on-surface-variant text-[11px]">Rp 0 (Lunas)</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
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
                                    <td class="px-5 py-3.5 whitespace-nowrap text-on-surface-variant">
                                        @if($b->settled_by && $b->settledBy)
                                        <div class="flex items-center gap-1 text-pitch-emerald font-semibold">
                                            <span class="material-symbols-outlined text-[15px]">badge</span>
                                            {{ $b->settledBy->name }}
                                        </div>
                                        <div class="text-[10px]">
                                            {{ $b->settled_at ? \Carbon\Carbon::parse($b->settled_at)->translatedFormat('d M H:i') : '' }}
                                            ({{ ucfirst($b->settlement_method ?? 'Kasir') }})
                                        </div>
                                        @else
                                        <span class="text-on-surface-variant/50 italic">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-10 text-center text-on-surface-variant">
                                        Tidak ada transaksi booking pada periode ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($reportBookings->hasPages())
                    <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/70">
                        {{ $reportBookings->links() }}
                    </div>
                    @endif
                </div>

            </main>
        </div>
    </div>

</body>
</html>
