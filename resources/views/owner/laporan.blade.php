<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Owner - {{ config('app.name', 'Lapangin') }}</title>
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
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
                <div>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest block">Owner Portal</span>
                    <span class="text-[9px] text-white/50 block">Multi-Venue Control</span>
                </div>
            </div>

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

            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5">
                    <div class="w-9 h-9 rounded-full bg-amber-400/20 text-amber-300 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-amber-300/80 truncate">Venue Owner</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/60 z-40 backdrop-blur-xs" style="display:none;"></div>

        {{-- ============ MAIN AREA ============ --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-surface-white border-b border-surface-container-high px-6 py-4 flex items-center justify-between gap-4 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-surface-container-low text-on-surface-variant">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Laporan Keuangan Owner</h1>
                        <p class="text-xs text-on-surface-variant">Transparansi arus kas riil, DP, pelunasan di tempat, dan sisa piutang</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('owner.booking.export', ['venue_id' => $selectedVenueId]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-surface-container-high text-xs font-bold text-on-surface hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        Ekspor CSV
                    </a>
                </div>
            </header>

            <main class="flex-1 p-6 space-y-6 overflow-auto">

                {{-- Filter Bar --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 shadow-xs">
                    <form method="GET" action="{{ route('owner.laporan') }}" class="flex flex-wrap items-end gap-4">
                        {{-- Venue Selector --}}
                        <div class="w-full sm:w-auto">
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Pilih Venue:</label>
                            <select name="venue_id" class="text-xs font-bold border border-surface-container-high rounded-xl px-3.5 py-2.5 bg-surface-white text-on-surface focus:ring-2 focus:ring-amber-500 w-full sm:w-56">
                                <option value="all" {{ $selectedVenueId === 'all' ? 'selected' : '' }}>Semua Venue Milik Saya</option>
                                @foreach($venues as $v)
                                <option value="{{ $v->id }}" {{ (string)$selectedVenueId === (string)$v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Period Buttons --}}
                        <div class="w-full sm:w-auto">
                            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Periode Laporan:</label>
                            <div class="inline-flex rounded-xl border border-surface-container-high p-1 bg-surface-container-lowest gap-1">
                                <a href="{{ route('owner.laporan', ['venue_id' => $selectedVenueId, 'period' => 'harian']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $period === 'harian' ? 'bg-amber-400 text-slate-950 shadow-xs' : 'text-on-surface-variant hover:text-on-surface' }}">
                                    Harian
                                </a>
                                <a href="{{ route('owner.laporan', ['venue_id' => $selectedVenueId, 'period' => 'mingguan']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $period === 'mingguan' ? 'bg-amber-400 text-slate-950 shadow-xs' : 'text-on-surface-variant hover:text-on-surface' }}">
                                    Mingguan
                                </a>
                                <a href="{{ route('owner.laporan', ['venue_id' => $selectedVenueId, 'period' => 'bulanan']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $period === 'bulanan' ? 'bg-amber-400 text-slate-950 shadow-xs' : 'text-on-surface-variant hover:text-on-surface' }}">
                                    Bulanan
                                </a>
                            </div>
                        </div>

                        {{-- Custom Date Range --}}
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Dari Tanggal:</label>
                                <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}" class="text-xs border border-surface-container-high rounded-xl px-3 py-2 bg-surface-white text-on-surface focus:ring-2 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Sampai Tanggal:</label>
                                <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}" class="text-xs border border-surface-container-high rounded-xl px-3 py-2 bg-surface-white text-on-surface focus:ring-2 focus:ring-amber-500">
                            </div>
                            <input type="hidden" name="period" value="custom">
                            <button type="submit" class="mt-5 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors">
                                Terapkan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Periode Active Badge --}}
                <div class="flex items-center justify-between text-xs text-on-surface-variant">
                    <span class="font-medium">Menampilkan data untuk: <strong class="text-on-surface">{{ $periodLabel }}</strong></span>
                    <span class="text-xs">Cakupan Venue: <strong class="text-on-surface">{{ $selectedVenueId === 'all' ? 'Semua Venue' : ($venues->firstWhere('id', $selectedVenueId)?->name ?? '-') }}</strong></span>
                </div>

                {{-- Financial Summary Breakdown Cards (Wajib Sesuai Business Rules) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- 1. Total Nilai Transaksi (Gross) --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-on-surface-variant uppercase">Total Gross (Nilai Sewa)</span>
                            <span class="material-symbols-outlined text-slate-500 text-[18px]">receipt</span>
                        </div>
                        <div class="mt-2 text-xl font-extrabold text-on-surface">
                            Rp {{ number_format($grossRevenue, 0, ',', '.') }}
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-1">Nilai total kontrak booking</p>
                    </div>

                    {{-- 2. Uang Masuk dari DP --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-blue-600 uppercase">Kas Masuk dari DP</span>
                            <span class="material-symbols-outlined text-blue-600 text-[18px]">lock_clock</span>
                        </div>
                        <div class="mt-2 text-xl font-extrabold text-blue-600">
                            Rp {{ number_format($dpIncome, 0, ',', '.') }}
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-1">DP 30%-50% saat booking</p>
                    </div>

                    {{-- 3. Uang Masuk dari Pelunasan di Tempat --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-emerald-600 uppercase">Pelunasan di Lokasi</span>
                            <span class="material-symbols-outlined text-emerald-600 text-[18px]">point_of_sale</span>
                        </div>
                        <div class="mt-2 text-xl font-extrabold text-emerald-600">
                            Rp {{ number_format($settlementIncome, 0, ',', '.') }}
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-1">Diproses Admin/Kasir di venue</p>
                    </div>

                    {{-- 4. Uang Masuk dari Full Payment --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-teal-600 uppercase">Full Payment Awal</span>
                            <span class="material-symbols-outlined text-teal-600 text-[18px]">check_circle</span>
                        </div>
                        <div class="mt-2 text-xl font-extrabold text-teal-600">
                            Rp {{ number_format($fullPaymentIncome, 0, ',', '.') }}
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-1">Lunas 100% di awal booking</p>
                    </div>

                    {{-- 5. Sisa Tagihan (Piutang) --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-amber-600 uppercase">Sisa Tagihan (Piutang)</span>
                            <span class="material-symbols-outlined text-amber-600 text-[18px]">schedule</span>
                        </div>
                        <div class="mt-2 text-xl font-extrabold text-amber-600">
                            Rp {{ number_format($receivables, 0, ',', '.') }}
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-1">DP paid belum pelunasan</p>
                    </div>
                </div>

                {{-- Cash In Real Highlight Box --}}
                <div class="bg-gradient-to-r from-[#0a192f] to-[#172a45] rounded-2xl p-6 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-lg">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-bold">
                            <span class="material-symbols-outlined text-[16px]">account_balance_wallet</span>
                            Total Kas Masuk Riil (Net Cash Inflow)
                        </div>
                        <h3 class="font-headline text-3xl font-extrabold text-amber-400">
                            Rp {{ number_format($totalCashIn, 0, ',', '.') }}
                        </h3>
                        <p class="text-xs text-white/70">
                            Formula Riil: Uang DP (Rp {{ number_format($dpIncome, 0, ',', '.') }}) + Pelunasan Kasir (Rp {{ number_format($settlementIncome, 0, ',', '.') }}) + Full Payment (Rp {{ number_format($fullPaymentIncome, 0, ',', '.') }})
                        </p>
                    </div>
                    <div class="text-right border-t md:border-t-0 md:border-l border-white/10 pt-3 md:pt-0 md:pl-6">
                        <span class="text-xs text-white/60 block">Status Pelunasan</span>
                        <div class="text-sm font-bold text-white mt-1">
                            Piutang Tertagih: {{ $totalCashIn + $receivables > 0 ? round(($totalCashIn / ($totalCashIn + $receivables)) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>

                {{-- Detailed Transactions Table --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-surface-container-high flex items-center justify-between">
                        <div>
                            <h2 class="font-headline text-base font-bold text-on-surface">Rincian Transaksi Booking Periode Ini</h2>
                            <p class="text-xs text-on-surface-variant">Audit trail pembayaran, petugas verifikator kasir, dan status pelunasan</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-surface-container-low text-on-surface-variant font-semibold border-b border-surface-container-high uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5">Kode & Tanggal</th>
                                    <th class="px-5 py-3.5">Venue & Lapangan</th>
                                    <th class="px-5 py-3.5">Penyewa</th>
                                    <th class="px-5 py-3.5">Total Sewa</th>
                                    <th class="px-5 py-3.5">Terbayar (DP / Full)</th>
                                    <th class="px-5 py-3.5">Sisa Tagihan</th>
                                    <th class="px-5 py-3.5">Status</th>
                                    <th class="px-5 py-3.5">Verifikasi Kasir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-container-high">
                                @forelse($reportBookings as $b)
                                <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="font-mono font-bold text-on-surface">{{ $b->booking_code }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->created_at->translatedFormat('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-on-surface">{{ $b->venue->name ?? '-' }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->items->first()?->court?->name ?? 'Lapangan' }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-on-surface">{{ $b->customer_name }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->customer_phone }}</div>
                                    </td>
                                    <td class="px-5 py-4 font-bold text-on-surface">
                                        Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-emerald-600">Rp {{ number_format($b->paid_amount, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-on-surface-variant uppercase font-medium">
                                            {{ $b->payment_scheme === 'dp' ? 'Skema DP' : 'Full Payment' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($b->remaining_amount > 0 && $b->status->value === 'dp_paid')
                                        <span class="font-bold text-amber-600">Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}</span>
                                        @else
                                        <span class="text-on-surface-variant text-[11px]">Rp 0 (Lunas)</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $statusMap = [
                                                'pending' => ['bg-amber-100 text-amber-800', 'Menunggu Bayar'],
                                                'pending_payment' => ['bg-amber-100 text-amber-800', 'Menunggu Bayar'],
                                                'dp_paid' => ['bg-blue-100 text-blue-800', 'DP Lunas (Belum Pelunasan)'],
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
                                        <div class="flex items-center gap-1 text-emerald-700 font-semibold">
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
                    <div class="p-4 border-t border-surface-container-high">
                        {{ $reportBookings->links() }}
                    </div>
                    @endif
                </div>

            </main>
        </div>
    </div>

</body>
</html>
