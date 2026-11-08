<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Admin | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, search: '' }">
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
                        ['domain', 'Kelola Venue', route('admin.venues'), false],
                        ['people', 'Kelola User', route('admin.users'), true],
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
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Kelola User</h1>
                        <p class="text-xs text-on-surface-variant">247 user terdaftar</p>
                    </div>
                </div>
                <button class="flex items-center gap-2 border border-surface-container-high text-on-surface font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Export
                </button>
            </header>
            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">
                {{-- Search --}}
                <div class="relative max-w-sm">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" x-model="search" placeholder="Cari nama atau email..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                </div>

                <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-auto">
                    <table class="w-full text-sm min-w-[600px]">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-surface-container-high">
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">User</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">WhatsApp</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Role</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Total Booking</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Bergabung</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-high">
                            @foreach([
                                ['Ahmad Fauzi', 'ahmad@email.com', '0812-3456', 'Penyewa', '12', '1 Nov 2026', 'active'],
                                ['Sari Dewi', 'sari@email.com', '0878-1234', 'Penyewa', '8', '2 Nov 2026', 'active'],
                                ['Budi Santoso', 'budi@email.com', '0856-9876', 'Penyewa', '5', '3 Nov 2026', 'active'],
                                ['Budi Operator', 'budi@lapangin.id', '0898-7777', 'Operator', '—', '1 Nov 2026', 'active'],
                                ['Dini Rahayu', 'dini@email.com', '0896-5555', 'Penyewa', '3', '5 Nov 2026', 'suspended'],
                            ] as $user)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-pitch-surface flex items-center justify-center shrink-0">
                                            <span class="font-bold text-pitch-deep text-sm">{{ substr($user[0], 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-on-surface">{{ $user[0] }}</p>
                                            <p class="text-xs text-on-surface-variant">{{ $user[1] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-on-surface-variant text-xs">{{ $user[2] }}</td>
                                <td class="px-5 py-4">
                                    <span @class([
                                        'text-[10px] font-bold px-2.5 py-1 rounded-full',
                                        'bg-pitch-surface text-pitch-deep' => $user[3] === 'Penyewa',
                                        'bg-action-glow text-action-orange' => $user[3] === 'Operator',
                                    ])>{{ $user[3] }}</span>
                                </td>
                                <td class="px-5 py-4 font-semibold text-on-surface">{{ $user[4] }}</td>
                                <td class="px-5 py-4 text-xs text-on-surface-variant">{{ $user[5] }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <button class="text-xs font-semibold text-on-surface-variant border border-surface-container-high px-2.5 py-1.5 rounded-lg hover:bg-surface-container-low transition-colors">Detail</button>
                                        @if($user[6] === 'suspended')
                                        <button class="text-xs font-bold text-pitch-deep border border-pitch-light/40 px-2.5 py-1.5 rounded-lg hover:bg-pitch-surface transition-colors">Aktifkan</button>
                                        @else
                                        <button class="text-xs font-bold text-error border border-error/20 px-2.5 py-1.5 rounded-lg hover:bg-error-container/10 transition-colors">Suspend</button>
                                        @endif
                                    </div>
                                </td>
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

