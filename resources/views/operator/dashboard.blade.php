<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operator - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true }">

    {{-- Layout with sidebar --}}
    <div class="flex min-h-screen">

        {{-- ============ SIDEBAR ============ --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-arena-night flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto"
        >
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-pitch-deep/50">
                <img src="{{ asset('images/logo.png') }}" alt="Lapangin" class="h-8 w-auto brightness-0 invert opacity-90">
                <div>
                    <span class="text-xs font-bold text-pitch-light uppercase tracking-wider block">Operator</span>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <div class="flex flex-col gap-1">
                    @php
                        $navItems = [
                            ['dashboard', 'Dashboard', route('operator.dashboard'), true],
                            ['calendar_month', 'Kelola Jadwal', route('operator.jadwal'), false],
                            ['receipt_long', 'Daftar Booking', route('operator.booking'), false],
                            ['stadium', 'Kelola Lapangan', route('operator.lapangan'), false],
                        ];
                    @endphp
                    @foreach($navItems as $item)
                    <a
                        href="{{ $item[2] }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-pitch-emerald/20 text-pitch-light' : 'text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5' }}"
                    >
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                        @if($item[1] === 'Daftar Booking')
                        <span class="ml-auto bg-action-orange text-surface-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">3</span>
                        @endif
                    </a>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-pitch-deep/50 flex flex-col gap-1">
                    <a href="{{ route('beranda') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                        Lihat Situs Publik
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        Keluar
                    </a>
                </div>
            </nav>

            {{-- User info --}}
            <div class="px-4 py-4 border-t border-pitch-deep/50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-pitch-emerald/30 flex items-center justify-center">
                        <span class="font-bold text-pitch-light text-sm">BO</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-surface-white truncate">Budi Operator</p>
                        <p class="text-[10px] text-surface-container-highest truncate">budi@lapangin.id</p>
                    </div>
                    <span class="material-symbols-outlined text-surface-container-highest text-[18px]">settings</span>
                </div>
            </div>
        </aside>

        {{-- Overlay for mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/50 z-40" style="display:none;"></div>

        {{-- ============ MAIN CONTENT ============ --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Top bar --}}
            <header class="bg-surface-white border-b border-surface-container-high px-6 py-4 flex items-center justify-between gap-4 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-surface-container-low text-on-surface-variant">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Dashboard Operator</h1>
                        <p class="text-xs text-on-surface-variant">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5 bg-pitch-surface px-3 py-1.5 rounded-full text-xs text-pitch-deep font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald animate-pulse"></span>
                        Venue Aktif
                    </div>
                    <button class="relative p-2 rounded-lg hover:bg-surface-container-low text-on-surface-variant">
                        <span class="material-symbols-outlined text-[22px]">notifications</span>
                        <span class="absolute top-1 right-1 w-4 h-4 bg-action-orange rounded-full text-[9px] text-surface-white font-bold flex items-center justify-center">3</span>
                    </button>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-6 overflow-auto">

                {{-- KPI Cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    @php
                        $kpis = [
                            ['today_booking', 'Booking Hari Ini', '8', '+2 dari kemarin', 'pitch-deep', 'pitch-surface'],
                            ['payments', 'Pendapatan Hari Ini', 'Rp 1,6 Jt', '+15% dari kemarin', 'pitch-emerald', 'pitch-surface'],
                            ['pending_actions', 'Menunggu Konfirmasi', '3', 'Perlu tindakan', 'action-orange', 'action-glow'],
                            ['cancel', 'Pembatalan Hari Ini', '0', 'Tidak ada pembatalan', 'court-booked', 'court-booked-surface'],
                        ];
                    @endphp
                    @foreach($kpis as $kpi)
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-on-surface-variant font-medium">{{ $kpi[1] }}</span>
                            <div class="w-9 h-9 rounded-xl bg-{{ $kpi[5] }} flex items-center justify-center">
                                <span class="material-symbols-outlined text-{{ $kpi[4] }} text-[18px]">{{ $kpi[0] }}</span>
                            </div>
                        </div>
                        <div class="font-headline text-2xl font-bold text-on-surface">{{ $kpi[2] }}</div>
                        <div class="text-xs text-on-surface-variant">{{ $kpi[3] }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Incoming bookings --}}
                    <div class="lg:col-span-2 bg-surface-white rounded-2xl border border-surface-container-high flex flex-col">
                        <div class="flex items-center justify-between p-5 border-b border-surface-container-high">
                            <h2 class="font-headline text-sm font-bold text-on-surface">Booking Masuk Hari Ini</h2>
                            <a href="{{ route('operator.booking') }}" class="text-xs text-pitch-deep font-semibold hover:underline flex items-center gap-1">
                                Lihat Semua <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                        <div class="flex flex-col divide-y divide-surface-container-high">
                            @php
                                $bookings = [
                                    ['LPN-4729', 'Ahmad Fauzi', 'Futsal Arena A', '09:00–11:00', 'Rp 240.000', 'confirmed', '0812-3456-7890'],
                                    ['LPN-4580', 'Sari Dewi', 'Badminton Court 2', '17:00–19:00', 'Rp 160.000', 'pending', '0878-1234-5678'],
                                    ['LPN-4421', 'Budi Santoso', 'Mini Soccer Field', '15:00–17:00', 'Rp 300.000', 'confirmed', '0856-9876-5432'],
                                    ['LPN-4300', 'Dini Rahayu', 'Futsal Arena B', '19:00–21:00', 'Rp 400.000', 'pending', '0896-5555-4444'],
                                ];
                            @endphp
                            @foreach($bookings as $b)
                            <div class="flex items-center gap-4 px-5 py-4 hover:bg-surface-container-low transition-colors">
                                <div class="w-9 h-9 rounded-full bg-pitch-surface flex items-center justify-center shrink-0">
                                    <span class="font-bold text-pitch-deep text-sm">{{ substr($b[1], 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-on-surface">{{ $b[1] }}</p>
                                    <p class="text-xs text-on-surface-variant">{{ $b[2] }} • {{ $b[3] }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm font-bold text-on-surface">{{ $b[4] }}</p>
                                    <span @class([
                                        'inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full',
                                        'bg-pitch-surface text-pitch-deep' => $b[5] === 'confirmed',
                                        'bg-action-glow text-action-orange' => $b[5] === 'pending',
                                    ])>
                                        {{ $b[5] === 'confirmed' ? 'Terkonfirmasi' : 'Menunggu Bayar' }}
                                    </span>
                                </div>
                                <div class="flex flex-col gap-1 shrink-0">
                                    @if($b[5] === 'pending')
                                    <button class="text-[10px] font-bold bg-pitch-deep text-surface-white px-2.5 py-1 rounded-lg hover:bg-pitch-emerald transition-colors">
                                        Konfirmasi
                                    </button>
                                    @endif
                                    <a href="https://wa.me/{{ $b[6] }}" target="_blank" class="text-[10px] font-semibold text-pitch-deep border border-pitch-light/40 px-2.5 py-1 rounded-lg hover:bg-pitch-surface transition-colors text-center">
                                        WhatsApp
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right sidebar: Schedule overview + Stats --}}
                    <div class="flex flex-col gap-4">

                        {{-- Today's occupancy --}}
                        <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4">
                            <h3 class="font-headline text-sm font-bold text-on-surface">Occupancy Hari Ini</h3>
                            @php
                                $courts = [
                                    ['Futsal Arena A', 75, 'pitch-emerald'],
                                    ['Futsal Arena B', 50, 'pitch-emerald'],
                                    ['Badminton Court 1-3', 90, 'action-amber'],
                                    ['Mini Soccer Field', 30, 'pitch-light'],
                                    ['Padel Court', 60, 'pitch-emerald'],
                                ];
                            @endphp
                            @foreach($courts as $court)
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-on-surface font-medium">{{ $court[0] }}</span>
                                    <span class="font-bold text-on-surface">{{ $court[1] }}%</span>
                                </div>
                                <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-{{ $court[2] }} rounded-full transition-all" style="width:{{ $court[1] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Revenue chart placeholder --}}
                        <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4">
                            <h3 class="font-headline text-sm font-bold text-on-surface">Pendapatan 7 Hari</h3>
                            <div class="flex items-end gap-1.5 h-24">
                                @foreach([40, 65, 50, 80, 55, 90, 75] as $i => $h)
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full rounded-t-sm bg-pitch-light/80 hover:bg-pitch-emerald transition-colors" style="height: {{ $h }}%"></div>
                                    <span class="text-[9px] text-on-surface-variant">{{ ['S','S','R','K','J','S','M'][$i] }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between text-xs text-on-surface-variant border-t border-surface-container-high pt-3">
                                <span>Total minggu ini</span>
                                <span class="font-bold text-on-surface">Rp 9,8 Jt</span>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>

