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
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, showEditModal: false, editVenueId: '', editName: '', editAddress: '', editPhone: '', editEmail: '', editOpenTime: '', editCloseTime: '', editPrimeStart: '', editPrimeEnd: '', editLockDuration: 15, editActionUrl: '' }">
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
                <div class="mt-6 pt-4 border-t border-surface-white/10 flex flex-col gap-1">
                    <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-container-highest hover:text-surface-white hover:bg-surface-white/5 transition-all">
                        <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                        Lihat Panel Operator
                    </a>
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
            <div class="px-4 py-4 border-t border-surface-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-action-orange/30 flex items-center justify-center">
                        <span class="font-bold text-action-amber text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
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
                    <h1 class="font-headline text-lg font-bold text-on-surface">Kelola Venue</h1>
                </div>
                <button class="flex items-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm px-4 py-2.5 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Tambah Venue
                </button>
            </header>
            <main class="flex-1 p-6 overflow-auto">

                @if(session('status'))
                <div class="mb-5 p-4 bg-pitch-surface border border-pitch-light/40 rounded-xl text-sm font-semibold text-pitch-deep">
                    {{ session('status') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-5 p-4 bg-error-container/20 border border-error/30 rounded-xl text-sm font-semibold text-error">
                    {{ $errors->first() }}
                </div>
                @endif

                <div class="grid grid-cols-1 gap-5">
                    @forelse($venues as $venue)
                    @php
                        $courtsCount = $venue->courts_count;
                        $usersCount = $venueStats[$venue->id]['users_count'] ?? 0;
                        $bookingsCount = $venueStats[$venue->id]['bookings_count'] ?? 0;
                        $rev = $venueStats[$venue->id]['revenue'] ?? 0;
                    @endphp
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-pitch-surface flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-pitch-deep text-[28px]">stadium</span>
                                </div>
                                <div>
                                    <h3 class="font-headline text-base font-bold text-on-surface">{{ $venue->name }}</h3>
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $venue->address }} • {{ $venue->phone }}</p>
                                    <p class="text-[11px] text-pitch-deep mt-1">
                                        Jam Buka: {{ substr($venue->open_time, 0, 5) }} - {{ substr($venue->close_time, 0, 5) }} WIB • Prime: {{ substr($venue->prime_start_time, 0, 5) }} - {{ substr($venue->prime_end_time, 0, 5) }} WIB • Kunci Slot: {{ $venue->slot_lock_duration_minutes }} menit
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold bg-pitch-surface text-pitch-deep px-3 py-1.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Aktif
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-surface-container-low rounded-xl p-4 text-center">
                                <div class="font-headline text-xl font-bold text-on-surface">{{ $courtsCount }}</div>
                                <div class="text-xs text-on-surface-variant mt-1">Lapangan</div>
                            </div>
                            <div class="bg-surface-container-low rounded-xl p-4 text-center">
                                <div class="font-headline text-xl font-bold text-on-surface">{{ $usersCount }}</div>
                                <div class="text-xs text-on-surface-variant mt-1">Operator Terdaftar</div>
                            </div>
                            <div class="bg-surface-container-low rounded-xl p-4 text-center">
                                <div class="font-headline text-xl font-bold text-on-surface">{{ $bookingsCount }}</div>
                                <div class="text-xs text-on-surface-variant mt-1">Total Booking</div>
                            </div>
                            <div class="bg-surface-container-low rounded-xl p-4 text-center">
                                <div class="font-headline text-xl font-bold text-on-surface">Rp {{ number_format($rev, 0, ',', '.') }}</div>
                                <div class="text-xs text-on-surface-variant mt-1">Total GMV</div>
                            </div>
                        </div>
                        <div class="flex gap-2 items-center flex-wrap">
                            <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-1.5 text-xs font-semibold text-pitch-deep border border-pitch-light/40 px-3 py-2 rounded-lg hover:bg-pitch-surface transition-colors">
                                <span class="material-symbols-outlined text-[14px]">manage_accounts</span>
                                Panel Operator
                            </a>
                            <button
                                type="button"
                                @click="editVenueId = '{{ $venue->id }}'; editName = '{{ addslashes($venue->name) }}'; editAddress = '{{ addslashes($venue->address) }}'; editPhone = '{{ addslashes($venue->phone) }}'; editEmail = '{{ addslashes($venue->email ?? '') }}'; editOpenTime = '{{ substr($venue->open_time, 0, 5) }}'; editCloseTime = '{{ substr($venue->close_time, 0, 5) }}'; editPrimeStart = '{{ substr($venue->prime_start_time, 0, 5) }}'; editPrimeEnd = '{{ substr($venue->prime_end_time, 0, 5) }}'; editLockDuration = {{ $venue->slot_lock_duration_minutes }}; editActionUrl = '{{ route('admin.venues.update', $venue->id) }}'; showEditModal = true"
                                class="flex items-center gap-1.5 text-xs font-semibold text-on-surface border border-surface-container-high px-3 py-2 rounded-lg hover:bg-surface-container-low transition-colors">
                                <span class="material-symbols-outlined text-[14px]">edit</span>
                                Edit Venue
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="p-8 text-center text-xs text-on-surface-variant">Belum ada venue terdaftar.</p>
                    @endforelse
                </div>
            </main>
        </div>
    </div>

    {{-- Edit Venue Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="showEditModal = false" class="bg-surface-white rounded-2xl w-full max-w-lg p-6 flex flex-col gap-5">
            <div class="flex items-center justify-between">
                <h2 class="font-headline text-lg font-bold text-on-surface">Edit Konfigurasi Venue</h2>
                <button @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form :action="editActionUrl" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Nama Venue <span class="text-error">*</span></label>
                    <input type="text" name="name" x-model="editName" required class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Alamat Lengkap <span class="text-error">*</span></label>
                    <textarea name="address" x-model="editAddress" required rows="2" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Nomor Telepon <span class="text-error">*</span></label>
                        <input type="text" name="phone" x-model="editPhone" required class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Email Kontak</label>
                        <input type="email" name="email" x-model="editEmail" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Jam Buka (H:i) <span class="text-error">*</span></label>
                        <input type="text" name="open_time" x-model="editOpenTime" required placeholder="08:00" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Jam Tutup (H:i) <span class="text-error">*</span></label>
                        <input type="text" name="close_time" x-model="editCloseTime" required placeholder="23:00" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Awal Jam Prime <span class="text-error">*</span></label>
                        <input type="text" name="prime_start_time" x-model="editPrimeStart" required placeholder="17:00" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Akhir Jam Prime <span class="text-error">*</span></label>
                        <input type="text" name="prime_end_time" x-model="editPrimeEnd" required placeholder="23:00" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Durasi Kunci Slot (Menit) <span class="text-error">*</span></label>
                    <input type="number" name="slot_lock_duration_minutes" x-model="editLockDuration" required min="5" max="60" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                </div>
                <div class="flex gap-3 pt-3">
                    <button type="button" @click="showEditModal = false" class="flex-1 py-2.5 rounded-xl border border-surface-container-high text-sm font-semibold hover:bg-surface-container-low">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-pitch-deep text-surface-white font-bold text-sm hover:bg-pitch-emerald transition-colors">Simpan Konfigurasi</button>
                </div>
            </form>
        </div>
    </div>
            </main>
        </div>
    </div>
</body>
</html>

