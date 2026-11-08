<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Venue - Admin | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true }">
    <div class="flex min-h-screen">
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-on-surface flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-surface-white/10">
                <img src="{{ asset('images/logo.png') }}" alt="Lapangin" class="h-8 w-auto brightness-0 invert opacity-90">
                <span class="text-[10px] font-bold text-action-orange uppercase tracking-widest">Super Admin</span>
            </div>
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <div class="flex flex-col gap-1">
                    @foreach([
                        ['dashboard', 'Dashboard', route('admin.dashboard'), false],
                        ['domain', 'Kelola Venue', route('admin.venues'), true],
                        ['people', 'Kelola User', route('admin.users'), false],
                    ] as $item)
                    <a href="{{ $item[2] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-action-orange/20 text-action-amber' : 'text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                    </a>
                    @endforeach
                </div>
            </nav>
            <div class="px-4 py-4 border-t border-surface-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-action-orange/30 flex items-center justify-center">
                        <span class="font-bold text-action-amber text-sm">SA</span>
                    </div>
                    <p class="text-xs font-bold text-surface-white truncate">Super Admin</p>
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
                    <h1 class="font-headline text-lg font-bold text-on-surface">Kelola Venue</h1>
                </div>
                <button class="flex items-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm px-4 py-2.5 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Tambah Venue
                </button>
            </header>
            <main class="flex-1 p-6 overflow-auto">
                <div class="grid grid-cols-1 gap-5">
                    @foreach([
                        ['Lapangin Sports Center', 'Jl. Olahraga No. 1, Jakarta Selatan', '7', '247', '1.429', 'Rp 94,2 Jt', 'active'],
                    ] as $venue)
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-pitch-surface flex items-center justify-center">
                                    <span class="material-symbols-outlined text-pitch-deep text-[28px]">stadium</span>
                                </div>
                                <div>
                                    <h3 class="font-headline text-base font-bold text-on-surface">{{ $venue[0] }}</h3>
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $venue[1] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold bg-pitch-surface text-pitch-deep px-3 py-1.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Aktif
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach([[$venue[2], 'Lapangan'], [$venue[3], 'User Terdaftar'], [$venue[4], 'Total Booking'], [$venue[5], 'Revenue Bulan Ini']] as $stat)
                            <div class="bg-surface-container-low rounded-xl p-4 text-center">
                                <div class="font-headline text-xl font-bold text-on-surface">{{ $stat[0] }}</div>
                                <div class="text-xs text-on-surface-variant mt-1">{{ $stat[1] }}</div>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-1.5 text-xs font-semibold text-pitch-deep border border-pitch-light/40 px-3 py-2 rounded-lg hover:bg-pitch-surface transition-colors">
                                <span class="material-symbols-outlined text-[14px]">manage_accounts</span>
                                Panel Operator
                            </a>
                            <button class="flex items-center gap-1.5 text-xs font-semibold text-on-surface border border-surface-container-high px-3 py-2 rounded-lg hover:bg-surface-container-low transition-colors">
                                <span class="material-symbols-outlined text-[14px]">edit</span>
                                Edit Venue
                            </button>
                            <button class="flex items-center gap-1.5 text-xs font-semibold text-error border border-error/20 px-3 py-2 rounded-lg hover:bg-error-container/20 transition-colors ml-auto">
                                <span class="material-symbols-outlined text-[14px]">block</span>
                                Nonaktifkan
                            </button>
                        </div>
                    </div>
                    @endforeach

                    {{-- Add venue placeholder --}}
                    <div class="border-2 border-dashed border-surface-container-high rounded-2xl p-12 flex flex-col items-center gap-3 text-center hover:border-pitch-light/40 hover:bg-pitch-surface/30 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-[48px] text-surface-container-highest">add_business</span>
                        <div>
                            <p class="text-sm font-bold text-on-surface">Tambah Venue Baru</p>
                            <p class="text-xs text-on-surface-variant mt-1">Daftarkan venue olahraga baru ke platform Lapangin</p>
                        </div>
                        <button class="bg-pitch-deep text-surface-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-pitch-emerald transition-colors mt-2">
                            Tambah Sekarang
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

