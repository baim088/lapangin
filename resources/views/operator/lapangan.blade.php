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
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, showAddModal: false, showEditModal: false, editCourtId: '', editName: '', editCategory: 'futsal', editSurface: '', editPriceRegular: 0, editPricePrime: 0, editActionUrl: '' }">

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
                <div class="mt-6 pt-4 border-t border-pitch-deep/50 flex flex-col gap-1">
                    <a href="{{ route('beranda') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                        Lihat Situs Publik
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5 transition-all text-left">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>
            <div class="px-4 py-4 border-t border-pitch-deep/50">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-pitch-emerald/30 flex items-center justify-center">
                        <span class="font-bold text-pitch-light text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-surface-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-surface-container-highest truncate">{{ auth()->user()->email }}</p>
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

                @if(session('status'))
                <div class="p-4 bg-pitch-surface border border-pitch-light/40 rounded-xl text-sm font-semibold text-pitch-deep">
                    {{ session('status') }}
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 bg-error-container/20 border border-error/30 rounded-xl text-sm font-semibold text-error">
                    {{ $errors->first() }}
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @forelse($courts as $court)
                    @php
                        $icon = match($court->sport_category->value) {
                            'badminton', 'padel' => 'sports_tennis',
                            default => 'sports_soccer',
                        };
                        $occRate = $courtStats[$court->id] ?? 0;
                    @endphp
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-hidden hover:border-pitch-light/40 transition-colors">
                        {{-- Court image placeholder --}}
                        <div class="relative h-32 bg-gradient-to-br {{ $court->status->value === 'maintenance' ? 'from-court-blocked-surface to-court-booked-surface' : 'from-pitch-deep/80 to-pitch-emerald/40' }} flex items-center justify-center">
                            <span class="material-symbols-outlined text-[48px] {{ $court->status->value === 'maintenance' ? 'text-court-blocked/50' : 'text-pitch-light/50' }}">{{ $icon }}</span>
                            <div class="absolute top-3 left-3">
                                <span class="text-[10px] font-bold bg-pitch-deep text-pitch-light px-2.5 py-1 rounded-full">{{ $court->sport_category->label() }}</span>
                            </div>
                            <div class="absolute top-3 right-3">
                                @if($court->status->value === 'active')
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
                                <h3 class="text-sm font-bold text-on-surface">{{ $court->name }}</h3>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $court->surface_type }}</p>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-on-surface-variant">Harga:</span>
                                <span class="font-semibold text-on-surface">Rp {{ number_format($court->price_regular, 0, ',', '.') }} - Rp {{ number_format($court->price_prime, 0, ',', '.') }}/jam</span>
                            </div>

                            @if($court->status->value === 'active')
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-on-surface-variant">Occupancy hari ini</span>
                                    <span class="font-bold text-on-surface">{{ $occRate }}%</span>
                                </div>
                                <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-pitch-light rounded-full" style="width:{{ $occRate }}%"></div>
                                </div>
                            </div>
                            @endif

                            <div class="flex gap-2 pt-1 border-t border-surface-container-high">
                                <button
                                    type="button"
                                    @click="editCourtId = '{{ $court->id }}'; editName = '{{ addslashes($court->name) }}'; editCategory = '{{ $court->sport_category->value }}'; editSurface = '{{ addslashes($court->surface_type) }}'; editPriceRegular = '{{ $court->price_regular }}'; editPricePrime = '{{ $court->price_prime }}'; editActionUrl = '{{ route('operator.lapangan.update', $court->id) }}'; showEditModal = true"
                                    class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold text-pitch-deep border border-pitch-light/40 py-2 rounded-lg hover:bg-pitch-surface transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                    Edit
                                </button>
                                <form action="{{ route('operator.lapangan.toggle-status', $court->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Ubah status lapangan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold text-on-surface-variant border border-surface-container-high py-2 rounded-lg hover:bg-surface-container-low transition-colors">
                                        <span class="material-symbols-outlined text-[14px]">{{ $court->status->value === 'active' ? 'block' : 'check_circle' }}</span>
                                        {{ $court->status->value === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full p-8 text-center text-xs text-on-surface-variant">
                        Belum ada lapangan yang terdaftar.
                    </div>
                    @endforelse
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
            <form action="{{ route('operator.lapangan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Nama Lapangan</label>
                        <input type="text" name="name" required placeholder="Futsal Arena C" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Jenis Olahraga</label>
                        <select name="sport_category" required class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                            <option value="futsal">Futsal</option>
                            <option value="badminton">Badminton</option>
                            <option value="mini_soccer">Mini Soccer</option>
                            <option value="padel">Padel</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Jenis Permukaan</label>
                        <input type="text" name="surface_type" required placeholder="Karpet Vinyl" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Harga Normal (per jam)</label>
                        <input type="number" name="price_regular" required min="1000" placeholder="120000" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Harga Prime Time</label>
                        <input type="number" name="price_prime" required min="1000" placeholder="200000" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="flex-1 py-3 rounded-xl border border-surface-container-high text-sm font-semibold hover:bg-surface-container-low">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-pitch-deep text-surface-white font-bold text-sm hover:bg-pitch-emerald transition-colors">Simpan Lapangan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Court Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="showEditModal = false" class="bg-surface-white rounded-2xl w-full max-w-lg p-6 flex flex-col gap-5">
            <div class="flex items-center justify-between">
                <h2 class="font-headline text-lg font-bold text-on-surface">Edit Lapangan</h2>
                <button @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form :action="editActionUrl" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Nama Lapangan</label>
                        <input type="text" name="name" x-model="editName" required class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Jenis Olahraga</label>
                        <select name="sport_category" x-model="editCategory" required class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                            <option value="futsal">Futsal</option>
                            <option value="badminton">Badminton</option>
                            <option value="mini_soccer">Mini Soccer</option>
                            <option value="padel">Padel</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Jenis Permukaan</label>
                        <input type="text" name="surface_type" x-model="editSurface" required class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Harga Normal (per jam)</label>
                        <input type="number" name="price_regular" x-model="editPriceRegular" required min="1000" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Harga Prime Time</label>
                        <input type="number" name="price_prime" x-model="editPricePrime" required min="1000" class="w-full px-4 py-3 rounded-lg border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 py-3 rounded-xl border border-surface-container-high text-sm font-semibold hover:bg-surface-container-low">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-pitch-deep text-surface-white font-bold text-sm hover:bg-pitch-emerald transition-colors">Perbarui Lapangan</button>
                </div>
            </form>
        </div>
    </div>
        </div>
    </div>

</body>
</html>

