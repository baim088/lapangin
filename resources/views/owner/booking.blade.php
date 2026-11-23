<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Booking - Owner Portal | {{ config('app.name', 'Lapangin') }}</title>
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
                        <h1 class="font-headline text-lg font-bold text-on-surface">Monitoring Reservasi Booking</h1>
                        <p class="text-xs text-on-surface-variant">Lacak status booking dari seluruh venue milik Anda</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('owner.booking.export', ['venue_id' => $selectedVenueId]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        Unduh CSV
                    </a>
                </div>
            </header>

            <main class="flex-1 p-6 space-y-6 overflow-auto">

                {{-- Filter & Search Form --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 shadow-xs">
                    <form method="GET" action="{{ route('owner.booking') }}" class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                            {{-- Venue Filter --}}
                            <div>
                                <select name="venue_id" onchange="this.form.submit()" class="text-xs font-bold border border-surface-container-high rounded-xl px-3.5 py-2.5 bg-surface-white text-on-surface focus:ring-2 focus:ring-amber-500">
                                    <option value="all" {{ $selectedVenueId === 'all' ? 'selected' : '' }}>Semua Venue Saya</option>
                                    @foreach($venues as $v)
                                    <option value="{{ $v->id }}" {{ (string)$selectedVenueId === (string)$v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Pills --}}
                            <div class="flex flex-wrap gap-1 bg-surface-container-lowest p-1 rounded-xl border border-surface-container-high">
                                @php
                                    $filters = [
                                        'all' => 'Semua',
                                        'dp_paid' => 'DP Paid',
                                        'confirmed' => 'Lunas / Terkonfirmasi',
                                        'pending' => 'Pending',
                                        'done' => 'Selesai',
                                    ];
                                @endphp
                                @foreach($filters as $val => $lbl)
                                <a href="{{ route('owner.booking', array_merge(request()->query(), ['status' => $val])) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $activeFilter === $val ? 'bg-amber-400 text-slate-950 shadow-xs' : 'text-on-surface-variant hover:text-on-surface' }}">
                                    {{ $lbl }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Search Input --}}
                        <div class="w-full lg:w-72 relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Cari Kode, Nama, No WA..."
                                class="w-full text-xs border border-surface-container-high rounded-xl pl-9 pr-4 py-2.5 bg-surface-white text-on-surface focus:ring-2 focus:ring-amber-500"
                            >
                            <span class="material-symbols-outlined text-surface-container-highest text-[18px] absolute left-3 top-3">search</span>
                        </div>
                    </form>
                </div>

                {{-- Booking List Table --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-surface-container-low text-on-surface-variant font-semibold border-b border-surface-container-high uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5">Kode Booking</th>
                                    <th class="px-5 py-3.5">Venue & Lapangan</th>
                                    <th class="px-5 py-3.5">Penyewa</th>
                                    <th class="px-5 py-3.5">Jadwal Main</th>
                                    <th class="px-5 py-3.5">Total & Skema</th>
                                    <th class="px-5 py-3.5">Status Booking</th>
                                    <th class="px-5 py-3.5">Pelunasan Kasir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-container-high">
                                @forelse($allBookings as $b)
                                <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="font-mono font-bold text-on-surface text-sm">{{ $b->booking_code }}</div>
                                        <div class="text-[10px] text-on-surface-variant">{{ $b->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-on-surface">{{ $b->venue->name ?? '-' }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->items->first()?->court?->name ?? 'Lapangan' }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-on-surface">{{ $b->customer_name }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $b->customer_phone }}</div>
                                        <div class="text-[10px] text-on-surface-variant/70">{{ $b->customer_email }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        @php $first = $b->items->first(); @endphp
                                        <div class="font-bold text-on-surface">
                                            {{ $first ? \Carbon\Carbon::parse($first->booking_date)->translatedFormat('d M Y') : '-' }}
                                        </div>
                                        <div class="text-[11px] text-on-surface-variant">
                                            {{ $first ? substr($first->start_time, 0, 5) . ' - ' . substr($first->end_time, 0, 5) : '-' }} WIB
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-extrabold text-on-surface">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</div>
                                        <div class="text-[10px] font-bold text-amber-600 uppercase">
                                            {{ $b->payment_scheme === 'dp' ? 'Skema DP' : 'Full Payment' }}
                                        </div>
                                        @if($b->status->value === 'dp_paid')
                                        <div class="text-[10px] text-rose-500 font-semibold mt-0.5">
                                            Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $statusMap = [
                                                'pending' => ['bg-amber-100 text-amber-800', 'Menunggu Bayar'],
                                                'pending_payment' => ['bg-amber-100 text-amber-800', 'Menunggu Bayar'],
                                                'dp_paid' => ['bg-blue-100 text-blue-800', 'DP Paid (Sisa Tagihan)'],
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
                                        <div class="flex items-center gap-1.5 text-emerald-700 font-semibold">
                                            <span class="material-symbols-outlined text-[16px]">verified</span>
                                            {{ $b->settledBy->name }}
                                        </div>
                                        <div class="text-[10px]">
                                            {{ $b->settled_at ? \Carbon\Carbon::parse($b->settled_at)->translatedFormat('d M H:i') : '' }}
                                            ({{ ucfirst($b->settlement_method ?? 'Kasir') }})
                                        </div>
                                        @elseif($b->status->value === 'dp_paid')
                                        <span class="text-amber-600 font-semibold italic text-[11px]">Belum Pelunasan</span>
                                        @else
                                        <span class="text-on-surface-variant/40">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-on-surface-variant">
                                        Tidak ditemukan data booking yang sesuai kriteria pencarian.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($allBookings->hasPages())
                    <div class="p-4 border-t border-surface-container-high">
                        {{ $allBookings->links() }}
                    </div>
                    @endif
                </div>

            </main>
        </div>
    </div>

</body>
</html>
