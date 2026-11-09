<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking - Operator | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, sidebarOpen: true, activeFilter: 'all', search: '' }">

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
                        ['receipt_long', 'Daftar Booking', route('operator.booking'), true],
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
                        <h1 class="font-headline text-lg font-bold text-on-surface">Daftar Booking</h1>
                        <p class="text-xs text-on-surface-variant">Kelola semua transaksi booking</p>
                    </div>
                </div>
                <a href="{{ route('operator.booking.export', ['search' => $search, 'status' => $activeFilter]) }}" class="flex items-center gap-2 border border-surface-container-high text-on-surface font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Export CSV
                </a>
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

                {{-- Filters row --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('operator.booking') }}" class="relative flex-1 min-w-48">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, kode booking, no HP..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <input type="hidden" name="status" value="{{ $activeFilter }}">
                    </form>

                    {{-- Status filter --}}
                    <div class="flex gap-1 bg-surface-container-low p-1 rounded-xl">
                        @foreach(['all' => 'Semua', 'confirmed' => 'Dikonfirmasi', 'pending' => 'Menunggu', 'done' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $key => $label)
                        <a
                            href="{{ route('operator.booking', ['status' => $key, 'search' => $search]) }}"
                            class="text-xs px-3 py-1.5 rounded-lg transition-all font-medium {{ $activeFilter === $key ? 'bg-surface-white text-pitch-deep shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface' }}"
                        >{{ $label }}</a>
                        @endforeach
                    </div>
                </div>

                {{-- Booking table --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-auto">
                    <table class="w-full text-sm min-w-[700px]">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-surface-container-high">
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Kode</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Penyewa</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Lapangan</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Tanggal & Jam</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Total</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Status</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-high">
                            @forelse($allBookings as $b)
                            @php
                                $courtName = $b->items->first()?->court?->name ?? 'Lapangan';
                                $bookingDate = $b->items->first()?->booking_date ? \Carbon\Carbon::parse($b->items->first()->booking_date)->translatedFormat('d M Y') : '-';
                                $times = $b->items->pluck('start_time')->map(fn($t) => substr($t, 0, 5))->join(', ');
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-5 py-4">
                                    <span class="font-mono text-xs font-bold text-pitch-deep">{{ $b->booking_code }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div>
                                        <p class="font-semibold text-on-surface">{{ $b->customer_name }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $b->customer_phone }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-on-surface">{{ $courtName }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-on-surface">{{ $bookingDate }}</p>
                                    <p class="text-xs text-on-surface-variant">{{ $times }} WIB</p>
                                </td>
                                <td class="px-5 py-4 font-bold text-on-surface">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">
                                    @if($b->status->value === 'confirmed')
                                    <span class="inline-flex items-center gap-1 bg-pitch-surface text-pitch-deep text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Terkonfirmasi
                                    </span>
                                    @elseif($b->status->value === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-action-glow text-action-orange text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-action-amber animate-pulse"></span>
                                        {{ $b->payment?->status?->value === 'pending_verification' ? 'Verifikasi Bukti' : 'Menunggu Bayar' }}
                                    </span>
                                    @elseif($b->status->value === 'done')
                                    <span class="inline-flex items-center gap-1 bg-surface-container-low text-on-surface-variant text-xs font-bold px-2.5 py-1 rounded-full">
                                        <span class="material-symbols-outlined text-[12px]">task_alt</span> Selesai
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 bg-error-container/20 text-error text-xs font-bold px-2.5 py-1 rounded-full">
                                        {{ $b->status->label() }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($b->status->value === 'pending')
                                        <form action="{{ route('operator.booking.confirm', $b->id) }}" method="POST" onsubmit="return confirm('Konfirmasi booking ini?')">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold bg-pitch-deep text-surface-white px-2.5 py-1.5 rounded-lg hover:bg-pitch-emerald transition-colors">
                                                Konfirmasi
                                            </button>
                                        </form>
                                        <form action="{{ route('operator.booking.reject', $b->id) }}" method="POST" onsubmit="var reason = prompt('Masukkan alasan penolakan booking:'); if (!reason) return false; this.querySelector('input[name=reason]').value = reason; return true;">
                                            @csrf
                                            <input type="hidden" name="reason" value="">
                                            <button type="submit" class="text-xs font-semibold text-error border border-error/30 px-2 py-1.5 rounded-lg hover:bg-error-container/10 transition-colors">
                                                Tolak
                                            </button>
                                        </form>
                                        @elseif($b->status->value === 'confirmed')
                                        <form action="{{ route('operator.booking.cancel', $b->id) }}" method="POST" onsubmit="var reason = prompt('Masukkan alasan pembatalan booking:'); if (!reason) return false; this.querySelector('input[name=reason]').value = reason; return true;">
                                            @csrf
                                            <input type="hidden" name="reason" value="">
                                            <button type="submit" class="text-xs font-semibold text-error border border-error/30 px-2 py-1.5 rounded-lg hover:bg-error-container/10 transition-colors">
                                                Batalkan
                                            </button>
                                        </form>
                                        @endif

                                        @if($b->payment?->proof_path)
                                        <a href="{{ route('booking.proof', $b->id) }}" target="_blank" class="text-xs font-semibold text-pitch-deep border border-pitch-light/40 px-2 py-1.5 rounded-lg hover:bg-pitch-surface transition-colors">
                                            Bukti
                                        </a>
                                        @endif
                                        <a href="{{ route('user.booking.detail', $b->id) }}" class="text-xs font-semibold text-on-surface-variant border border-surface-container-high px-2 py-1.5 rounded-lg hover:bg-surface-container-low transition-colors">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-xs text-on-surface-variant">
                                    Tidak ada data booking yang sesuai filter.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <span class="text-xs text-on-surface-variant">Menampilkan {{ $allBookings->firstItem() ?? 0 }} - {{ $allBookings->lastItem() ?? 0 }} dari {{ $allBookings->total() }} booking</span>
                    <div>
                        {{ $allBookings->links() }}
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>

