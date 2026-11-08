<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan - Operator | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, showAddModal: false }">

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
                        ['receipt_long', 'Daftar Booking', route('operator.booking'), false],
                        ['stadium', 'Kelola Lapangan', route('operator.lapangan'), true],
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
                    <h1 class="font-headline text-lg font-bold text-on-surface">Kelola Lapangan</h1>
                </div>
                <button @click="showAddModal = true" class="flex items-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm px-4 py-2.5 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Tambah Lapangan
                </button>
            </header>

            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @php
                        $courts = [
                            ['Futsal Arena A', 'Futsal', 'Karpet Vinyl', 'Rp 120.000 - Rp 200.000', 'active', 85, 'sports_soccer'],
                            ['Futsal Arena B', 'Futsal', 'Karpet Vinyl', 'Rp 120.000 - Rp 200.000', 'active', 60, 'sports_soccer'],
                            ['Badminton Court 1', 'Badminton', 'Kayu Sintesis', 'Rp 70.000 - Rp 110.000', 'active', 90, 'sports_tennis'],
                            ['Badminton Court 2', 'Badminton', 'Kayu Sintesis', 'Rp 70.000 - Rp 110.000', 'active', 75, 'sports_tennis'],
                            ['Badminton Court 3', 'Badminton', 'Kayu Sintesis', 'Rp 70.000 - Rp 110.000', 'maintenance', 0, 'sports_tennis'],
                            ['Mini Soccer Field', 'Mini Soccer', 'Rumput Sintetis', 'Rp 150.000 - Rp 300.000', 'active', 55, 'sports_soccer'],
                            ['Padel Court 1', 'Padel', 'Artificial Grass', 'Rp 150.000 - Rp 250.000', 'active', 40, 'sports_tennis'],
                        ];
                    @endphp

                    @foreach($courts as $court)
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-hidden hover:border-pitch-light/40 transition-colors">
                        {{-- Court image placeholder --}}
                        <div class="relative h-32 bg-gradient-to-br {{ $court[4] === 'maintenance' ? 'from-court-blocked-surface to-court-booked-surface' : 'from-pitch-deep/80 to-pitch-emerald/40' }} flex items-center justify-center">
                            <span class="material-symbols-outlined text-[48px] {{ $court[4] === 'maintenance' ? 'text-court-blocked/50' : 'text-pitch-light/50' }}">{{ $court[6] }}</span>
                            <div class="absolute top-3 left-3">
                                <span class="text-[10px] font-bold bg-pitch-deep text-pitch-light px-2.5 py-1 rounded-full">{{ $court[1] }}</span>
                            </div>
                            <div class="absolute top-3 right-3">
                                @if($court[4] === 'active')
                                <span class="text-[10px] font-bold bg-pitch-surface text-pitch-deep px-2.5 py-1 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Aktif
                                </span>
                                @else
                                <span class="text-[10px] font-bold bg-court-blocked-surface text-court-blocked px-2.5 py-1 rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[10px]">build</span> Maintenance
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 flex flex-col gap-3">
                            <div>
                                <h3 class="text-sm font-bold text-on-surface">{{ $court[0] }}</h3>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $court[2] }}</p>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-on-surface-variant">Harga:</span>
                                <span class="font-semibold text-on-surface">{{ $court[3] }}/jam</span>
                            </div>

                            @if($court[4] === 'active')
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-on-surface-variant">Occupancy hari ini</span>
                                    <span class="font-bold text-on-surface">{{ $court[5] }}%</span>
                                </div>
                                <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-pitch-light rounded-full" style="width:{{ $court[5] }}%"></div>
                                </div>
                            </div>
                            @endif

                            <div class="flex gap-2 pt-1 border-t border-surface-container-high">
                                <button class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold text-pitch-deep border border-pitch-light/40 py-2 rounded-lg hover:bg-pitch-surface transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                    Edit
                                </button>
                                <button class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold text-on-surface-variant border border-surface-container-high py-2 rounded-lg hover:bg-surface-container-low transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">{{ $court[4] === 'active' ? 'block' : 'check_circle' }}</span>
                                    {{ $court[4] === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </main>
        </div>
    </div>

    {{-- Add Court Modal --}}
    <div x-show="showAddModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="showAddModal = false" class="bg-surface-white rounded-2xl w-full max-w-lg p-6 flex flex-col gap-5">
            <div class="flex items-center justify-between">
                <h2 class="font-headline text-lg font-bold text-on-surface">Tambah Lapangan Baru</h2>
                <button @click="showAddModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Nama Lapangan</label>
                    <input type="text" placeholder="Futsal Arena C" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Jenis Olahraga</label>
                    <select class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <option>Futsal</option>
                        <option>Badminton</option>
                        <option>Mini Soccer</option>
                        <option>Padel</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Jenis Permukaan</label>
                    <input type="text" placeholder="Karpet Vinyl" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Harga Normal (per jam)</label>
                    <input type="number" placeholder="120000" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Harga Prime Time</label>
                    <input type="number" placeholder="200000" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button @click="showAddModal = false" class="flex-1 py-3 rounded-xl border border-surface-container-high text-sm font-semibold hover:bg-surface-container-low">Batal</button>
                <button class="flex-1 py-3 rounded-xl bg-pitch-deep text-surface-white font-bold text-sm hover:bg-pitch-emerald transition-colors">Simpan Lapangan</button>
            </div>
        </div>
    </div>

</body>
</html>

