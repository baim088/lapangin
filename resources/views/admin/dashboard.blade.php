<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true }">

    <div class="flex min-h-screen">
        {{-- Sidebar Admin --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-on-surface flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-surface-white/10">
                <img src="{{ asset('images/logo.png') }}" alt="Lapangin" class="h-8 w-auto brightness-0 invert opacity-90">
                <div>
                    <span class="text-[10px] font-bold text-action-orange uppercase tracking-widest block">Super Admin</span>
                </div>
            </div>
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <p class="text-[10px] text-surface-container-highest uppercase tracking-wider px-3 mb-2">Menu Utama</p>
                <div class="flex flex-col gap-1">
                    @foreach([
                        ['dashboard', 'Dashboard', route('admin.dashboard'), true],
                        ['domain', 'Kelola Venue', route('admin.venues'), false],
                        ['people', 'Kelola User', route('admin.users'), false],
                    ] as $item)
                    <a href="{{ $item[2] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-action-orange/20 text-action-amber' : 'text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                    </a>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t border-surface-white/10 flex flex-col gap-1">
                    <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                        Lihat Panel Operator
                    </a>
                    <a href="{{ route('beranda') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                        Lihat Situs Publik
                    </a>
                </div>
            </nav>
            <div class="px-4 py-4 border-t border-surface-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-action-orange/30 flex items-center justify-center">
                        <span class="font-bold text-action-amber text-sm">SA</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-surface-white truncate">Super Admin</p>
                        <p class="text-[10px] text-surface-container-highest truncate">admin@lapangin.id</p>
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
                        <h1 class="font-headline text-lg font-bold text-on-surface">Admin Dashboard</h1>
                        <p class="text-xs text-on-surface-variant">Overview platform Lapangin</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-action-orange bg-action-glow px-3 py-1.5 rounded-full">Super Admin</span>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                {{-- Global KPIs --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach([
                        ['domain', 'Total Venue', '1', 'venue aktif', 'pitch-deep', 'pitch-surface'],
                        ['people', 'Total User', '247', '+12 minggu ini', 'pitch-emerald', 'pitch-surface'],
                        ['receipt_long', 'Total Booking', '1.429', '+89 bulan ini', 'action-orange', 'action-glow'],
                        ['payments', 'Total Revenue', 'Rp 94,2 Jt', 'Bulan November', 'pitch-deep', 'pitch-surface'],
                    ] as $kpi)
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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Recent Users --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high flex flex-col">
                        <div class="flex items-center justify-between p-5 border-b border-surface-container-high">
                            <h2 class="font-headline text-sm font-bold text-on-surface">User Terbaru</h2>
                            <a href="{{ route('admin.users') }}" class="text-xs text-pitch-deep font-semibold hover:underline flex items-center gap-1">
                                Kelola User <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                        <div class="flex flex-col divide-y divide-surface-container-high">
                            @foreach([
                                ['Ahmad Fauzi', 'ahmad@email.com', 'Penyewa', '2 jam lalu'],
                                ['Sari Dewi', 'sari@email.com', 'Penyewa', '5 jam lalu'],
                                ['Budi Santoso', 'budi@email.com', 'Penyewa', 'Kemarin'],
                                ['Tim Garuda', 'tim@email.com', 'Penyewa', '2 hari lalu'],
                            ] as $user)
                            <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-surface-container-low transition-colors">
                                <div class="w-9 h-9 rounded-full bg-pitch-surface flex items-center justify-center shrink-0">
                                    <span class="font-bold text-pitch-deep text-sm">{{ substr($user[0], 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-on-surface">{{ $user[0] }}</p>
                                    <p class="text-xs text-on-surface-variant truncate">{{ $user[1] }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-[10px] font-bold bg-pitch-surface text-pitch-deep px-2 py-0.5 rounded-full">{{ $user[2] }}</span>
                                    <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $user[3] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Venue Overview --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high flex flex-col">
                        <div class="flex items-center justify-between p-5 border-b border-surface-container-high">
                            <h2 class="font-headline text-sm font-bold text-on-surface">Venue Overview</h2>
                            <a href="{{ route('admin.venues') }}" class="text-xs text-pitch-deep font-semibold hover:underline flex items-center gap-1">
                                Kelola <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                        <div class="p-5 flex flex-col gap-4">
                            {{-- Venue card --}}
                            <div class="p-4 bg-surface-container-low rounded-xl border border-surface-container-high flex flex-col gap-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-bold text-on-surface">Lapangin Sports Center</h3>
                                        <p class="text-xs text-on-surface-variant">Jakarta Selatan</p>
                                    </div>
                                    <span class="text-[10px] font-bold bg-pitch-surface text-pitch-deep px-2.5 py-1 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Aktif
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-3 text-center">
                                    @foreach([['7', 'Lapangan'], ['247', 'User'], ['1.429', 'Booking']] as $v)
                                    <div class="bg-surface-white rounded-lg p-3">
                                        <div class="font-headline text-lg font-bold text-on-surface">{{ $v[0] }}</div>
                                        <div class="text-[10px] text-on-surface-variant mt-0.5">{{ $v[1] }}</div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-on-surface-variant">Revenue Bulan Ini</span>
                                        <span class="font-bold text-on-surface">Rp 94,2 Jt</span>
                                    </div>
                                    <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                                        <div class="h-full bg-pitch-emerald rounded-full" style="width:78%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-on-surface-variant">
                                        <span>Target: Rp 120 Jt</span>
                                        <span class="font-semibold">78% tercapai</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('admin.venues') }}" class="flex items-center justify-center gap-2 border-2 border-dashed border-surface-container-high rounded-xl p-4 text-sm text-on-surface-variant hover:border-pitch-light/40 hover:text-pitch-deep hover:bg-pitch-surface transition-all">
                                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                                Tambah Venue Baru
                            </a>
                        </div>
                    </div>
                </div>

                {{-- System health --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-4">
                    <h2 class="font-headline text-sm font-bold text-on-surface">Status Sistem</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach([
                            ['Server', '99.9%', 'pitch-emerald', 'check_circle'],
                            ['Database', '99.7%', 'pitch-emerald', 'storage'],
                            ['Payment Gateway', '100%', 'pitch-emerald', 'payments'],
                            ['WhatsApp API', '98.2%', 'action-amber', 'warning'],
                        ] as $sys)
                        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
                            <span class="material-symbols-outlined text-{{ $sys[2] }} text-[20px]">{{ $sys[3] }}</span>
                            <div>
                                <p class="text-xs font-semibold text-on-surface">{{ $sys[0] }}</p>
                                <p class="text-xs font-bold text-{{ $sys[2] }}">{{ $sys[1] }} uptime</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>

