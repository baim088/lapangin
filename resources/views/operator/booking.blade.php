<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking - Operator | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, activeFilter: 'all', search: '' }">

    <div class="flex min-h-screen">
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-arena-night flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-pitch-deep/50">
                <img src="{{ asset('images/logo.png') }}" alt="Lapangin" class="h-8 w-auto brightness-0 invert opacity-90">
                <span class="text-xs font-bold text-pitch-light uppercase tracking-wider">Operator</span>
            </div>
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <div class="flex flex-col gap-1">
                    @foreach([
                        ['dashboard', 'Dashboard', route('operator.dashboard'), false],
                        ['calendar_month', 'Kelola Jadwal', route('operator.jadwal'), false],
                        ['receipt_long', 'Daftar Booking', route('operator.booking'), true],
                        ['stadium', 'Kelola Lapangan', route('operator.lapangan'), false],
                    ] as $item)
                    <a href="{{ $item[2] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-pitch-emerald/20 text-pitch-light' : 'text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                    </a>
                    @endforeach
                </div>
            </nav>
            <div class="px-4 py-4 border-t border-pitch-deep/50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-pitch-emerald/30 flex items-center justify-center">
                        <span class="font-bold text-pitch-light text-sm">BO</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-surface-white truncate">Budi Operator</p>
                        <p class="text-[10px] text-surface-container-highest truncate">budi@lapangin.id</p>
                    </div>
                </div>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/50 z-40" style="display:none;"></div>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-surface-white border-b border-surface-container-high px-6 py-4 flex items-center justify-between gap-4 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-surface-container-low text-on-surface-variant">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Daftar Booking</h1>
                        <p class="text-xs text-on-surface-variant">Kelola semua transaksi booking</p>
                    </div>
                </div>
                <button class="flex items-center gap-2 border border-surface-container-high text-on-surface font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Export CSV
                </button>
            </header>

            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                {{-- Filters row --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-48">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" x-model="search" placeholder="Cari nama, kode booking..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>

                    {{-- Status filter --}}
                    <div class="flex gap-1 bg-surface-container-low p-1 rounded-xl">
                        @foreach(['all' => 'Semua', 'confirmed' => 'Dikonfirmasi', 'pending' => 'Menunggu', 'done' => 'Selesai'] as $key => $label)
                        <button
                            @click="activeFilter = '{{ $key }}'"
                            :class="activeFilter === '{{ $key }}' ? 'bg-surface-white text-pitch-deep shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface'"
                            class="text-xs px-3 py-1.5 rounded-lg transition-all font-medium"
                        >{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Booking table --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-auto">
                    <table class="w-full text-sm min-w-[700px]">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-surface-container-high">
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Kode</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Penyewa</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Lapangan</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Tanggal & Jam</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Total</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Status</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-high">
                            @php
                                $allBookings = [
                                    ['LPN-4729', 'Ahmad Fauzi', '0812-3456', 'Futsal Arena A', '11 Nov 2026', '09:00–11:00', 'Rp 240.000', 'confirmed'],
                                    ['LPN-4580', 'Sari Dewi', '0878-1234', 'Badminton 2', '11 Nov 2026', '17:00–19:00', 'Rp 160.000', 'pending'],
                                    ['LPN-4421', 'Budi Santoso', '0856-9876', 'Mini Soccer', '11 Nov 2026', '15:00–17:00', 'Rp 300.000', 'confirmed'],
                                    ['LPN-4300', 'Dini Rahayu', '0896-5555', 'Futsal Arena B', '11 Nov 2026', '19:00–21:00', 'Rp 400.000', 'pending'],
                                    ['LPN-4120', 'Tim Garuda FC', '0812-7777', 'Mini Soccer', '10 Nov 2026', '15:00–17:00', 'Rp 300.000', 'done'],
                                    ['LPN-3890', 'Eko Purnama', '0877-3333', 'Futsal Arena B', '10 Nov 2026', '19:00–21:00', 'Rp 400.000', 'done'],
                                ];
                            @endphp
                            @foreach($allBookings as $b)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-5 py-4">
                                    <span class="font-mono text-xs font-bold text-pitch-deep">{{ $b[0] }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div>
                                        <p class="font-semibold text-on-surface">{{ $b[1] }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $b[2] }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-on-surface">{{ $b[3] }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-on-surface">{{ $b[4] }}</p>
                                    <p class="text-xs text-on-surface-variant">{{ $b[5] }}</p>
                                </td>
                                <td class="px-5 py-4 font-bold text-on-surface">{{ $b[6] }}</td>
                                <td class="px-5 py-4">
                                    @if($b[7] === 'confirmed')
                                    <span class="inline-flex items-center gap-1 bg-pitch-surface text-pitch-deep text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Terkonfirmasi
                                    </span>
                                    @elseif($b[7] === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-action-glow text-action-orange text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-action-amber animate-pulse"></span> Menunggu Bayar
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 bg-surface-container-low text-on-surface-variant text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="material-symbols-outlined text-[12px]">task_alt</span> Selesai
                                    </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($b[7] === 'pending')
                                        <button class="text-xs font-bold bg-pitch-deep text-surface-white px-2.5 py-1.5 rounded-lg hover:bg-pitch-emerald transition-colors">
                                            Konfirmasi
                                        </button>
                                        @endif
                                        <button class="text-xs font-semibold text-on-surface-variant border border-surface-container-high px-2.5 py-1.5 rounded-lg hover:bg-surface-container-low transition-colors">
                                            Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between">
                    <span class="text-xs text-on-surface-variant">Menampilkan 6 dari 48 booking</span>
                    <div class="flex items-center gap-1">
                        <button class="p-2 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container-low">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </button>
                        @foreach([1, 2, 3, '...', 8] as $page)
                        <button class="w-9 h-9 rounded-lg text-sm font-medium {{ $page === 1 ? 'bg-pitch-deep text-surface-white' : 'border border-surface-container-high text-on-surface hover:bg-surface-container-low' }}">{{ $page }}</button>
                        @endforeach
                        <button class="p-2 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container-low">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </button>
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>

