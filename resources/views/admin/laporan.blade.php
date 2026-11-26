<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Platform - Super Admin | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-body text-slate-800 antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex min-h-screen">
        {{-- Sidebar Admin --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f2444] flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-surface-white/10">
                <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
                <div>
                    <span class="text-[10px] font-bold text-action-orange uppercase tracking-widest block">Super Admin</span>
                </div>
            </div>
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <p class="text-[10px] text-surface-container-highest uppercase tracking-wider px-3 mb-2">Menu Utama</p>
                <div class="flex flex-col gap-1">
                    @foreach([
                        ['dashboard', 'Dashboard', route('admin.dashboard'), false],
                        ['bar_chart', 'Laporan Keuangan', route('admin.laporan'), true],
                        ['domain', 'Kelola Venue', route('admin.venues'), false],
                        ['people', 'Kelola User', route('admin.users'), false],
                    ] as $item)
                    <a href="{{ $item[2] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-action-orange/20 text-action-amber font-bold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                    </a>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t border-surface-white/10 flex flex-col gap-1">
                    <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                        Lihat Panel Operator
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5 transition-all text-left">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>
            <div class="px-4 py-4 border-t border-surface-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-[#f8b710]/20 border border-[#f8b710]/40 flex items-center justify-center">
                        <span class="font-bold text-[#f8b710] text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-surface-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-surface-container-highest truncate">Super Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/50 z-40" style="display:none;"></div>

        {{-- ============ MAIN CONTENT ============ --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-surface-white border-b border-surface-container-high px-6 py-4 flex items-center justify-between gap-4 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-surface-container-low text-on-surface-variant">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Rekapitulasi Keuangan Global Platform</h1>
                        <p class="text-xs text-on-surface-variant">Monitoring transaksi multi-venue SaaS di seluruh Indonesia</p>
                    </div>
                </div>

                {{-- Filter Venue Global --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-on-surface-variant font-medium hidden sm:inline">Venue:</span>
                    <select onchange="window.location.href = '?venue_id=' + this.value + '&period={{ $period }}'" class="text-xs font-bold border border-surface-container-high rounded-xl px-3 py-2 bg-surface-white text-on-surface focus:ring-2 focus:ring-action-orange">
                        <option value="all" {{ $selectedVenueId === 'all' ? 'selected' : '' }}>Semua Venue (Global)</option>
                        @foreach($venues as $v)
                            <option value="{{ $v->id }}" {{ (string)$selectedVenueId === (string)$v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                {{-- Periode Filter Bar --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-4 flex flex-wrap items-center justify-between gap-4 shadow-xs">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mr-1">Periode:</span>
                        @foreach([
                            'harian' => 'Harian (Hari Ini)',
                            'mingguan' => 'Mingguan (Minggu Ini)',
                            'bulanan' => 'Bulanan (Bulan Ini)',
                            'custom' => 'Kustom Tanggal',
                        ] as $pkey => $plabel)
                        <a
                            href="{{ route('admin.laporan', ['period' => $pkey, 'venue_id' => $selectedVenueId]) }}"
                            class="text-xs px-3.5 py-2 rounded-xl font-semibold transition-all {{ $period === $pkey ? 'bg-on-surface text-surface-white shadow-sm' : 'bg-surface-container-low text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}"
                        >
                            {{ $plabel }}
                        </a>
                        @endforeach
                    </div>

                    <div class="text-xs font-bold text-action-orange bg-action-glow px-3 py-1.5 rounded-lg border border-action-orange/20">
                        {{ $periodLabel }}
                    </div>
                </div>

                {{-- Custom Date Picker Form --}}
                @if($period === 'custom')
                <form method="GET" action="{{ route('admin.laporan') }}" class="bg-surface-white rounded-2xl border border-surface-container-high p-4 flex flex-wrap items-end gap-3 shadow-xs">
                    <input type="hidden" name="period" value="custom">
                    <input type="hidden" name="venue_id" value="{{ $selectedVenueId }}">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1">Tanggal Mulai:</label>
                        <input type="date" name="start_date" value="{{ $startDate->toDateString() }}" class="text-xs px-3 py-2 rounded-xl border border-surface-container-high focus:ring-2 focus:ring-action-orange">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1">Tanggal Akhir:</label>
                        <input type="date" name="end_date" value="{{ $endDate->toDateString() }}" class="text-xs px-3 py-2 rounded-xl border border-surface-container-high focus:ring-2 focus:ring-action-orange">
                    </div>
                    <button type="submit" class="text-xs font-bold px-4 py-2 bg-on-surface text-white rounded-xl hover:bg-on-surface/80 transition-colors">
                        Terapkan
                    </button>
                </form>
                @endif

                {{-- 4 Metric Cards Required by Business Rules --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- 1. Gross Revenue --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Gross Transaction</span>
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">analytics</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="font-headline text-xl lg:text-2xl font-extrabold text-on-surface">Rp {{ number_format($grossRevenue, 0, ',', '.') }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-1">Total nilai transaksi platform</p>
                        </div>
                    </div>

                    {{-- 2. Uang Masuk dari DP --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">DP Masuk</span>
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="font-headline text-xl lg:text-2xl font-extrabold text-indigo-900">Rp {{ number_format($dpIncome, 0, ',', '.') }}</p>
                            <p class="text-[11px] text-indigo-600 mt-1">Uang muka sewa terkunci</p>
                        </div>
                    </div>

                    {{-- 3. Uang Masuk dari Pelunasan --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Pelunasan Kasir</span>
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">point_of_sale</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="font-headline text-xl lg:text-2xl font-extrabold text-emerald-900">Rp {{ number_format($settlementIncome, 0, ',', '.') }}</p>
                            <p class="text-[11px] text-emerald-600 mt-1">Pelunasan on-site kasir venue</p>
                        </div>
                    </div>

                    {{-- 4. Sisa Tagihan (Piutang) --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col justify-between shadow-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Sisa Tagihan (Piutang)</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="font-headline text-xl lg:text-2xl font-extrabold text-amber-900">Rp {{ number_format($receivables, 0, ',', '.') }}</p>
                            <p class="text-[11px] text-amber-700 mt-1">Belum dilunasi di lokasi</p>
                        </div>
                    </div>

                </div>

                {{-- Banner Total Kas Riil Masuk --}}
                <div class="bg-on-surface text-white rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4 shadow-sm border border-surface-white/10">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-action-amber">Total Kas Bersih Masuk Platform (Real Cash In)</span>
                        <h2 class="font-headline text-2xl lg:text-3xl font-extrabold mt-1">Rp {{ number_format($totalCashIn, 0, ',', '.') }}</h2>
                        <p class="text-xs text-surface-container-highest mt-1">Total uang riil yang telah diterima dari seluruh penyewa (DP + Pelunasan + Full Payment)</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-medium bg-white/5 px-4 py-3 rounded-xl border border-white/10">
                        <div>
                            <span class="block opacity-70 text-[10px]">DP Masuk</span>
                            <span class="font-bold">Rp {{ number_format($dpIncome, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-px h-8 bg-white/20"></div>
                        <div>
                            <span class="block opacity-70 text-[10px]">Pelunasan Kasir</span>
                            <span class="font-bold">Rp {{ number_format($settlementIncome, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-px h-8 bg-white/20"></div>
                        <div>
                            <span class="block opacity-70 text-[10px]">Lunas di Awal</span>
                            <span class="font-bold">Rp {{ number_format($fullPaymentIncome, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Table Rincian Transaksi --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-hidden shadow-xs">
                    <div class="p-5 border-b border-surface-container-high flex items-center justify-between">
                        <div>
                            <h3 class="font-headline text-base font-bold text-on-surface">Data Transaksi Global</h3>
                            <p class="text-xs text-on-surface-variant">Daftar booking pada {{ $periodLabel }}</p>
                        </div>
                        <span class="text-xs font-semibold text-action-orange bg-action-glow px-3 py-1 rounded-full border border-action-orange/20">
                            {{ $reportBookings->total() }} Transaksi
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[900px]">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-surface-container-high">
                                    <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Kode & Waktu</th>
                                    <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Venue</th>
                                    <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Penyewa</th>
                                    <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Lapangan</th>
                                    <th class="text-right px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Gross Total</th>
                                    <th class="text-right px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">DP Masuk</th>
                                    <th class="text-right px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Pelunasan</th>
                                    <th class="text-right px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Sisa Piutang</th>
                                    <th class="text-center px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Status & Kasir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-container-high">
                                @forelse($reportBookings as $b)
                                @php
                                    $courtName = $b->items->first()?->court?->name ?? 'Lapangan';
                                    $settlementPay = $b->payments->firstWhere('payment_type', 'settlement');
                                @endphp
                                <tr class="hover:bg-surface-container-low/50 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="font-mono text-xs font-bold text-pitch-deep">{{ $b->booking_code }}</span>
                                        <span class="block text-[10px] text-on-surface-variant">{{ $b->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-on-surface">{{ $b->venue->name }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-on-surface">{{ $b->customer_name }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $b->customer_phone }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="text-on-surface">{{ $courtName }}</p>
                                        <p class="text-[10px] text-on-surface-variant">{{ $b->booking_date }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-right font-bold text-on-surface">
                                        Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold text-indigo-700">
                                        {{ $b->isDp() ? 'Rp ' . number_format($b->dp_amount, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold text-emerald-700">
                                        {{ $settlementPay ? 'Rp ' . number_format($settlementPay->amount, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-right font-bold {{ $b->remaining_amount > 0 ? 'text-amber-700' : 'text-on-surface-variant' }}">
                                        {{ $b->remaining_amount > 0 ? 'Rp ' . number_format($b->remaining_amount, 0, ',', '.') : 'Lunas' }}
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if($b->status->value === 'dp_paid')
                                            <span class="inline-block bg-amber-50 text-amber-800 text-[11px] font-bold px-2 py-0.5 rounded-full border border-amber-200">
                                                DP Terbayar
                                            </span>
                                        @elseif(in_array($b->status->value, ['confirmed', 'paid_in_full']))
                                            <span class="inline-block bg-pitch-surface text-pitch-deep text-[11px] font-bold px-2 py-0.5 rounded-full border border-pitch-light/30">
                                                Lunas
                                            </span>
                                            @if($b->settledBy)
                                                <p class="text-[10px] text-on-surface-variant mt-0.5">Kasir: {{ $b->settledBy->name }}</p>
                                            @endif
                                        @else
                                            <span class="inline-block bg-surface-container-low text-on-surface-variant text-[11px] font-semibold px-2 py-0.5 rounded-full">
                                                {{ $b->status->label() }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-8 text-center text-xs text-on-surface-variant">
                                        Belum ada transaksi tercatat pada periode ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="p-4 border-t border-surface-container-high flex items-center justify-between flex-wrap gap-4">
                        <span class="text-xs text-on-surface-variant">Menampilkan {{ $reportBookings->firstItem() ?? 0 }} - {{ $reportBookings->lastItem() ?? 0 }} dari {{ $reportBookings->total() }} transaksi</span>
                        <div>
                            {{ $reportBookings->links() }}
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>
