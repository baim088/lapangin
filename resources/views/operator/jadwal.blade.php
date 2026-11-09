<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal - Operator | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, selectedDate: '{{ $selectedDate }}', selectedCourt: '{{ $selectedCourt }}', showBlockModal: false, blockCourtId: '{{ $courts->first()?->id }}', blockTime: '{{ sprintf('%02d:00', $hours[0] ?? 8) }}' }">

    <div class="flex min-h-screen">

        {{-- Sidebar (sama dengan dashboard) --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-arena-night flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-pitch-deep/50">
                <img src="{{ asset('images/logo.png') }}" alt="Lapangin" class="h-8 w-auto brightness-0 invert opacity-90">
                <span class="text-xs font-bold text-pitch-light uppercase tracking-wider">Operator</span>
            </div>
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <div class="flex flex-col gap-1">
                    @foreach([
                        ['dashboard', 'Dashboard', route('operator.dashboard'), false],
                        ['calendar_month', 'Kelola Jadwal', route('operator.jadwal'), true],
                        ['receipt_long', 'Daftar Booking', route('operator.booking'), false],
                        ['stadium', 'Kelola Lapangan', route('operator.lapangan'), false],
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
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Kelola Jadwal</h1>
                        <p class="text-xs text-on-surface-variant">Pantau & atur ketersediaan slot per lapangan</p>
                    </div>
                </div>
                <button @click="showBlockModal = true" class="flex items-center gap-2 bg-action-orange hover:bg-tertiary-container text-surface-white font-bold text-sm px-4 py-2.5 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[18px]">block</span>
                    Blokir Slot
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

                {{-- Filters --}}
                <form method="GET" action="{{ route('operator.jadwal') }}" class="flex flex-wrap items-center gap-3">
                    {{-- Date picker --}}
                    <div class="flex items-center gap-2 bg-surface-white border border-surface-container-high rounded-xl px-4 py-2.5">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px]">calendar_today</span>
                        <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" class="text-sm font-medium bg-transparent focus:outline-none text-on-surface">
                    </div>

                    {{-- Court filter --}}
                    <select name="court" onchange="this.form.submit()" class="bg-surface-white border border-surface-container-high rounded-xl px-4 py-2.5 text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <option value="all" {{ $selectedCourt === 'all' ? 'selected' : '' }}>Semua Lapangan</option>
                        @foreach($venue->courts as $c)
                        <option value="{{ $c->id }}" {{ $selectedCourt == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>

                    {{-- Legend --}}
                    <div class="flex items-center gap-4 ml-auto text-xs text-on-surface-variant">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-pitch-surface border border-pitch-light/50"></span> Tersedia</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-court-booked-surface border border-court-booked/30"></span> Terisi</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-court-blocked-surface border border-court-blocked/30"></span> Diblokir</span>
                    </div>
                </form>

                {{-- Schedule Grid --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-auto">
                    <table class="w-full text-xs border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-surface-container-low">
                                <th class="text-left px-4 py-3 text-on-surface-variant font-semibold w-28 border-b border-surface-container-high sticky left-0 bg-surface-container-low z-10">Lapangan</th>
                                @foreach($hours as $h)
                                <th class="text-center px-1 py-3 text-on-surface-variant font-medium border-b border-surface-container-high min-w-[52px]">{{ sprintf('%02d:00', $h) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courts as $court)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-2 font-semibold text-on-surface border-b border-surface-container-high sticky left-0 bg-surface-white z-10">{{ $court->name }}</td>
                                @foreach($hours as $h)
                                    @php
                                        $hourStr = sprintf('%02d:00:00', $h);
                                        $slot = $slots->first(fn($s) => $s->court_id === $court->id && $s->start_time === $hourStr);
                                    @endphp
                                    <td class="p-0.5 border-b border-surface-container-high">
                                        @if($slot)
                                            @if($slot->block_type->value !== 'booking')
                                                <div class="relative group w-full h-9 rounded bg-court-blocked-surface border border-court-blocked/20 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-court-blocked text-[14px]">block</span>
                                                    <form action="{{ route('operator.jadwal.unblock', $slot->id) }}" method="POST" onsubmit="return confirm('Buka blokir slot {{ $court->name }} jam {{ sprintf('%02d:00', $h) }}?')" class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-black/60 rounded flex items-center justify-center transition-opacity">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Buka blokir ({{ $slot->note }})" class="text-surface-white text-[10px] font-bold">
                                                            Buka
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($slot->booking?->status?->value === 'pending')
                                                <div class="w-full h-9 rounded bg-action-glow border border-action-amber/30 flex items-center justify-center overflow-hidden px-1" title="{{ $slot->booking?->customer_name }} (Pending)">
                                                    <span class="truncate text-[9px] font-bold text-action-orange">{{ $slot->booking?->customer_name }}</span>
                                                </div>
                                            @else
                                                <div class="w-full h-9 rounded bg-pitch-surface border border-pitch-light/40 flex items-center justify-center overflow-hidden px-1" title="{{ $slot->booking?->customer_name }} (Confirmed)">
                                                    <span class="truncate text-[9px] font-bold text-pitch-deep">{{ $slot->booking?->customer_name }}</span>
                                                </div>
                                            @endif
                                        @else
                                            <div @click="blockCourtId = '{{ $court->id }}'; blockTime = '{{ sprintf('%02d:00', $h) }}'; showBlockModal = true"
                                                class="w-full h-9 rounded bg-surface-container-low/50 border border-dashed border-surface-container-high hover:bg-pitch-surface/50 hover:border-pitch-light/40 cursor-pointer transition-colors"
                                                title="Klik untuk memblokir slot jam {{ sprintf('%02d:00', $h) }}"></div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    {{-- Block Slot Modal --}}
    <div x-show="showBlockModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="showBlockModal = false" class="bg-surface-white rounded-2xl w-full max-w-md p-6 flex flex-col gap-5">
            <div class="flex items-center justify-between">
                <h2 class="font-headline text-lg font-bold text-on-surface">Blokir Slot Lapangan</h2>
                <button @click="showBlockModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('operator.jadwal.block') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Lapangan</label>
                    <select name="court_id" x-model="blockCourtId" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        @foreach($venue->courts as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Tanggal</label>
                        <input type="date" name="booking_date" value="{{ $selectedDate }}" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-semibold">Jam Mulai</label>
                        <select name="start_time" x-model="blockTime" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                            @foreach($hours as $h)
                            <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Tipe Pemblokiran</label>
                    <select name="block_type" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <option value="maintenance">Maintenance Lapangan</option>
                        <option value="event">Event Khusus / Turnamen</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Catatan / Alasan <span class="text-error">*</span></label>
                    <input type="text" name="note" required placeholder="Contoh: Perbaikan lampu lapangan" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showBlockModal = false" class="flex-1 py-2.5 rounded-xl border border-surface-container-high text-sm font-semibold hover:bg-surface-container-low">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-action-orange text-surface-white font-bold text-sm hover:bg-tertiary-container transition-colors">Blokir Sekarang</button>
                </div>
            </form>
        </div>
    </div>
        </div>
    </div>

</body>
</html>

