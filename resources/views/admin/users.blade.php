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
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, search: '{{ $search }}', roleFilter: '{{ $roleFilter }}', showRoleModal: false, targetUserId: '', targetUserName: '', targetUserRole: 'customer', targetVenueId: '', roleActionUrl: '' }">
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
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Kelola User</h1>
                        <p class="text-xs text-on-surface-variant">{{ $users->total() }} user terdaftar</p>
                    </div>
                </div>
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

                {{-- Search & Filters --}}
                <form method="GET" action="{{ route('admin.users') }}" class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-48 max-w-sm">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, no HP..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                    </div>
                    <select name="role" onchange="this.form.submit()" class="bg-surface-white border border-surface-container-high rounded-xl px-4 py-2.5 text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>Semua Role</option>
                        <option value="customer" {{ $roleFilter === 'customer' ? 'selected' : '' }}>Penyewa (Customer)</option>
                        <option value="operator" {{ $roleFilter === 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="super_admin" {{ $roleFilter === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </form>

                <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-auto">
                    <table class="w-full text-sm min-w-[600px]">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-surface-container-high">
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">User</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">WhatsApp</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Role & Venue</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Total Booking</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Bergabung</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-high">
                            @forelse($users as $user)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-pitch-surface flex items-center justify-center shrink-0">
                                            <span class="font-bold text-pitch-deep text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-on-surface">{{ $user->name }}</p>
                                            <p class="text-xs text-on-surface-variant">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-on-surface-variant text-xs">{{ $user->phone }}</td>
                                <td class="px-5 py-4">
                                    <span @class([
                                        'text-[10px] font-bold px-2.5 py-1 rounded-full',
                                        'bg-pitch-surface text-pitch-deep' => $user->role->value === 'customer',
                                        'bg-action-glow text-action-orange' => $user->role->value === 'operator',
                                        'bg-action-orange text-surface-white' => $user->role->value === 'super_admin',
                                    ])>{{ $user->role->label() }}</span>
                                    @if($user->role->value === 'operator')
                                    <p class="text-[10px] text-on-surface-variant mt-0.5 font-medium truncate max-w-[150px]">{{ $user->venue?->name ?? 'Belum ada venue' }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-semibold text-on-surface">{{ $user->bookings_count }}</td>
                                <td class="px-5 py-4 text-xs text-on-surface-variant">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <button
                                            type="button"
                                            @click="targetUserId = '{{ $user->id }}'; targetUserName = '{{ addslashes($user->name) }}'; targetUserRole = '{{ $user->role->value }}'; targetVenueId = '{{ $user->venue_id ?? ($venues->first()?->id ?? '') }}'; roleActionUrl = '{{ route('admin.users.update-role', $user->id) }}'; showRoleModal = true"
                                            class="text-xs font-semibold text-pitch-deep border border-pitch-light/40 px-2.5 py-1.5 rounded-lg hover:bg-pitch-surface transition-colors">
                                            Ubah Peran
                                        </button>
                                        @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" onsubmit="return confirm('{{ $user->status->value === 'suspended' ? 'Aktifkan akun ini?' : 'Suspend akun ini?' }}')">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->status->value === 'suspended')
                                            <button type="submit" class="text-xs font-bold text-pitch-deep border border-pitch-light/40 px-2.5 py-1.5 rounded-lg hover:bg-pitch-surface transition-colors">Aktifkan</button>
                                            @else
                                            <button type="submit" class="text-xs font-bold text-error border border-error/20 px-2.5 py-1.5 rounded-lg hover:bg-error-container/10 transition-colors">Suspend</button>
                                            @endif
                                        </form>
                                        @else
                                        <span class="text-[10px] text-on-surface-variant italic">(Akun Anda)</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-xs text-on-surface-variant">
                                    Tidak ada pengguna yang sesuai pencarian/filter.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <span class="text-xs text-on-surface-variant">Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user</span>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>

            </main>
        </div>
    </div>

    {{-- Update Role Modal --}}
    <div x-show="showRoleModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="showRoleModal = false" class="bg-surface-white rounded-2xl w-full max-w-md p-6 flex flex-col gap-5">
            <div class="flex items-center justify-between">
                <h2 class="font-headline text-lg font-bold text-on-surface">Ubah Peran Pengguna</h2>
                <button @click="showRoleModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <p class="text-xs text-on-surface-variant">Mengubah peran untuk pengguna: <span class="font-bold text-on-surface" x-text="targetUserName"></span></p>
            <form :action="roleActionUrl" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Peran (Role) <span class="text-error">*</span></label>
                    <select name="role" x-model="targetUserRole" required class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <option value="customer">Penyewa (Customer)</option>
                        <option value="operator">Operator Lapangan</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div x-show="targetUserRole === 'operator'" class="flex flex-col gap-1.5">
                    <label class="text-sm font-semibold">Tugaskan ke Venue <span class="text-error">*</span></label>
                    <select name="venue_id" x-model="targetVenueId" class="w-full px-4 py-2.5 rounded-xl border border-surface-container-high text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <option value="">-- Pilih Venue --</option>
                        @foreach($venues as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-on-surface-variant">Operator wajib memiliki venue yang dikelola.</p>
                </div>
                <div class="flex gap-3 pt-3">
                    <button type="button" @click="showRoleModal = false" class="flex-1 py-2.5 rounded-xl border border-surface-container-high text-sm font-semibold hover:bg-surface-container-low">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-pitch-deep text-surface-white font-bold text-sm hover:bg-pitch-emerald transition-colors">Simpan Peran</button>
                </div>
            </form>
        </div>
    </div>
            </main>
        </div>
    </div>
</body>
</html>

