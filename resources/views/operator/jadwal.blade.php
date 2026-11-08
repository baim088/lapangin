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
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, selectedDate: '{{ now()->toDateString() }}', selectedCourt: 'all' }">

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
                        <h1 class="font-headline text-lg font-bold text-on-surface">Kelola Jadwal</h1>
                        <p class="text-xs text-on-surface-variant">Pantau & atur ketersediaan slot per lapangan</p>
                    </div>
                </div>
                <button class="flex items-center gap-2 bg-action-orange hover:bg-tertiary-container text-surface-white font-bold text-sm px-4 py-2.5 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[18px]">block</span>
                    Blokir Slot
                </button>
            </header>

            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                {{-- Filters --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Date picker --}}
                    <div class="flex items-center gap-2 bg-surface-white border border-surface-container-high rounded-xl px-4 py-2.5">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px]">calendar_today</span>
                        <input type="date" x-model="selectedDate" class="text-sm font-medium bg-transparent focus:outline-none text-on-surface">
                    </div>

                    {{-- Court filter --}}
                    <select x-model="selectedCourt" class="bg-surface-white border border-surface-container-high rounded-xl px-4 py-2.5 text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <option value="all">Semua Lapangan</option>
                        <option value="futsal-a">Futsal Arena A</option>
                        <option value="futsal-b">Futsal Arena B</option>
                        <option value="badminton">Badminton Court</option>
                        <option value="mini-soccer">Mini Soccer Field</option>
                        <option value="padel">Padel Court</option>
                    </select>

                    {{-- Legend --}}
                    <div class="flex items-center gap-4 ml-auto text-xs text-on-surface-variant">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-pitch-surface border border-pitch-light/50"></span> Tersedia</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-court-booked-surface border border-court-booked/30"></span> Terisi</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-court-blocked-surface border border-court-blocked/30"></span> Diblokir</span>
                    </div>
                </div>

                {{-- Schedule Grid --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-auto">
                    @php
                        $hours = range(8, 22);
                        $courts = ['Futsal A', 'Futsal B', 'Badminton 1', 'Badminton 2', 'Badminton 3', 'Mini Soccer', 'Padel'];
                        $bookings = [
                            ['Futsal A', 9, 11, 'confirmed', 'Ahmad Fauzi'],
                            ['Futsal B', 19, 21, 'confirmed', 'Budi S.'],
                            ['Badminton 1', 8, 10, 'confirmed', 'Sari D.'],
                            ['Badminton 2', 17, 19, 'pending', 'Dini R.'],
                            ['Mini Soccer', 15, 17, 'confirmed', 'Tim Garuda'],
                            ['Padel', 10, 12, 'blocked', '—'],
                        ];
                    @endphp

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
                                <td class="px-4 py-2 font-semibold text-on-surface border-b border-surface-container-high sticky left-0 bg-surface-white z-10">{{ $court }}</td>
                                @foreach($hours as $h)
                                    @php
                                        $booking = null;
                                        foreach($bookings as $b) {
                                            if ($b[0] === $court && $h >= $b[1] && $h < $b[2]) {
                                                $booking = $b;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <td class="p-0.5 border-b border-surface-container-high">
                                        @if($booking)
                                            @if($booking[3] === 'blocked')
                                                <div class="w-full h-9 rounded bg-court-blocked-surface border border-court-blocked/20 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-court-blocked text-[14px]">block</span>
                                                </div>
                                            @elseif($booking[3] === 'pending')
                                                <div class="w-full h-9 rounded bg-action-glow border border-action-amber/30 flex items-center justify-center overflow-hidden px-1" title="{{ $booking[4] }}">
                                                    <span class="truncate text-[9px] font-bold text-action-orange">{{ $booking[4] }}</span>
                                                </div>
                                            @else
                                                <div class="w-full h-9 rounded bg-pitch-surface border border-pitch-light/40 flex items-center justify-center overflow-hidden px-1" title="{{ $booking[4] }}">
                                                    <span class="truncate text-[9px] font-bold text-pitch-deep">{{ $booking[4] }}</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-full h-9 rounded bg-surface-container-low/50 border border-dashed border-surface-container-high hover:bg-pitch-surface/50 hover:border-pitch-light/40 cursor-pointer transition-colors"></div>
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

</body>
</html>

