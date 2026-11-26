<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking - Operator | {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-[#f8f9fa] font-body text-slate-800 antialiased"
    x-data="{
        mobileMenuOpen: false,
        sidebarOpen: true,
        settleModalOpen: false,
        settleBookingId: null,
        settleBookingCode: '',
        settleCustomer: '',
        settleCourt: '',
        settleTotal: 0,
        settlePaid: 0,
        settleRemaining: 0,
        settleMethod: 'cash',
        openSettleModal(booking) {
            this.settleBookingId = booking.id;
            this.settleBookingCode = booking.booking_code;
            this.settleCustomer = booking.customer_name;
            this.settleCourt = booking.court_name;
            this.settleTotal = booking.total_amount;
            this.settlePaid = booking.paid_amount;
            this.settleRemaining = booking.remaining_amount;
            this.settleMethod = 'cash';
            this.settleModalOpen = true;
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }"
>

    <div class="flex min-h-screen">
        {{-- ============ SIDEBAR ============ --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f2444] flex flex-col transition-transform lg:translate-x-0 lg:static lg:z-auto">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
                <span class="text-xs font-bold text-[#f8b710] uppercase tracking-wider">
                    {{ auth()->user()->isOwner() ? 'Owner Venue' : (auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Kasir / Operator') }}
                </span>
            </div>
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <div class="flex flex-col gap-1">
                    @php
                        $navItems = [
                            ['dashboard', 'Dashboard', route('operator.dashboard'), false],
                            ['calendar_month', 'Kelola Jadwal', route('operator.jadwal'), false],
                            ['receipt_long', 'Daftar Booking', route('operator.booking'), true],
                            ['bar_chart', 'Laporan Keuangan', route('operator.laporan'), false],
                            ['stadium', 'Kelola Lapangan', route('operator.lapangan'), false],
                        ];
                    @endphp
                    @foreach($navItems as $item)
                    <a href="{{ $item[2] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-pitch-emerald/20 text-pitch-light font-bold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $item[0] }}</span>
                        {{ $item[1] }}
                    </a>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t border-white/10 flex flex-col gap-1">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-white/5 transition-all text-left">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>
            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-white/5">
                    <div class="w-9 h-9 rounded-full bg-[#f8b710]/20 border border-[#f8b710]/40 flex items-center justify-center">
                        <span class="font-bold text-[#f8b710] text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-surface-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-surface-container-highest truncate">{{ auth()->user()->role->label() }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/50 z-40" style="display:none;"></div>

        {{-- ============ MAIN CONTENT ============ --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-surface-white border-b border-surface-container-high px-6 py-4 flex items-center justify-between gap-4 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-surface-container-low text-on-surface-variant">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <div>
                        <h1 class="font-headline text-lg font-bold text-on-surface">Daftar Transaksi Booking</h1>
                        <p class="text-xs text-on-surface-variant">Kelola booking, verifikasi DP, dan proses pelunasan di tempat</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Venue Selector for multi-tenant owner / admin --}}
                    @if(isset($accessibleVenues) && $accessibleVenues->count() > 1)
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-on-surface-variant font-medium hidden sm:inline">Venue:</span>
                        <select onchange="window.location.href = '?venue_id=' + this.value" class="text-xs font-bold border border-surface-container-high rounded-xl px-3 py-2 bg-surface-white text-on-surface focus:ring-2 focus:ring-pitch-emerald">
                            @foreach($accessibleVenues as $v)
                                <option value="{{ $v->id }}" {{ $venue->id === $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-pitch-deep bg-pitch-surface px-3 py-2 rounded-xl border border-pitch-light/30">
                        <span class="material-symbols-outlined text-[16px]">location_on</span>
                        {{ $venue->name }}
                    </div>
                    @endif

                    <a href="{{ route('operator.booking.export', ['search' => $search, 'status' => $activeFilter, 'venue_id' => $venue->id]) }}" class="flex items-center gap-2 border border-surface-container-high text-on-surface font-semibold text-xs px-3.5 py-2 rounded-xl hover:bg-surface-container-low transition-all">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        Export CSV
                    </a>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-auto flex flex-col gap-6">

                @if(session('status'))
                <div class="p-4 bg-pitch-surface border border-pitch-light/40 rounded-xl text-sm font-semibold text-pitch-deep flex items-center gap-2">
                    <span class="material-symbols-outlined text-pitch-emerald text-[20px]">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 bg-error-container/20 border border-error/30 rounded-xl text-sm font-semibold text-error flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                {{-- Filters row --}}
                <div class="flex flex-wrap items-center justify-between gap-3">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('operator.booking') }}" class="relative flex-1 min-w-48 max-w-md">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode booking, nama, HP..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald">
                        <input type="hidden" name="status" value="{{ $activeFilter }}">
                        @if(request('venue_id'))
                            <input type="hidden" name="venue_id" value="{{ request('venue_id') }}">
                        @endif
                    </form>

                    {{-- Status filter --}}
                    <div class="flex flex-wrap gap-1 bg-surface-container-low p-1 rounded-xl">
                        @foreach([
                            'all' => 'Semua',
                            'dp_paid' => 'DP Terbayar',
                            'confirmed' => 'Lunas',
                            'pending' => 'Menunggu',
                            'done' => 'Selesai',
                            'cancelled' => 'Dibatalkan'
                        ] as $key => $label)
                        <a
                            href="{{ route('operator.booking', ['status' => $key, 'search' => $search, 'venue_id' => $venue->id]) }}"
                            class="text-xs px-3 py-1.5 rounded-lg transition-all font-medium {{ $activeFilter === $key ? 'bg-surface-white text-pitch-deep shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface' }}"
                        >{{ $label }}</a>
                        @endforeach
                    </div>
                </div>

                {{-- Booking table --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high overflow-auto shadow-sm">
                    <table class="w-full text-sm min-w-[760px]">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-surface-container-high">
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Kode</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Penyewa</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Lapangan</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Jadwal Main</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Tagihan & DP</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Status</th>
                                <th class="text-left px-5 py-3 text-on-surface-variant font-semibold text-xs uppercase tracking-wider">Aksi Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-high">
                            @forelse($allBookings as $b)
                            @php
                                $courtName = $b->items->first()?->court?->name ?? 'Lapangan';
                                $bookingDate = $b->items->first()?->booking_date ? \Carbon\Carbon::parse($b->items->first()->booking_date)->translatedFormat('d M Y') : '-';
                                $times = $b->items->pluck('start_time')->map(fn($t) => substr($t, 0, 5))->join(', ');
                                $isDpPaid = ($b->status->value === 'dp_paid');
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-5 py-4">
                                    <span class="font-mono text-xs font-bold text-pitch-deep">{{ $b->booking_code }}</span>
                                    <span class="block text-[10px] text-on-surface-variant mt-0.5">{{ $b->created_at->format('d/m/y H:i') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div>
                                        <p class="font-semibold text-on-surface">{{ $b->customer_name }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $b->customer_phone }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-on-surface">
                                    <span class="font-semibold">{{ $courtName }}</span>
                                    <span class="block text-[10px] text-on-surface-variant">{{ $b->venue->name }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-on-surface">{{ $bookingDate }}</p>
                                    <p class="text-xs text-on-surface-variant">{{ $times }} WIB ({{ $b->total_hours }} jam)</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-on-surface">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</p>
                                    @if($b->isDp())
                                        <span class="inline-block text-[10px] bg-indigo-50 text-indigo-700 font-semibold px-1.5 py-0.5 rounded mt-0.5">
                                            DP Masuk: Rp {{ number_format($b->paid_amount, 0, ',', '.') }}
                                        </span>
                                        @if($b->remaining_amount > 0)
                                            <p class="text-[11px] font-bold text-amber-700 mt-0.5">Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($isDpPaid)
                                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-300 text-xs font-bold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            DP Terbayar
                                        </span>
                                        <p class="text-[10px] text-amber-900 font-semibold mt-1">Harap Lunasi di Lokasi</p>
                                    @elseif(in_array($b->status->value, ['confirmed', 'paid_in_full']))
                                        <span class="inline-flex items-center gap-1 bg-pitch-surface text-pitch-deep text-xs font-bold px-2.5 py-1 rounded-full border border-pitch-light/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-pitch-emerald"></span> Lunas
                                        </span>
                                        @if($b->settledBy)
                                            <p class="text-[10px] text-on-surface-variant mt-0.5">Kasir: {{ $b->settledBy->name }} ({{ strtoupper($b->settlement_method ?? 'CASH') }})</p>
                                        @endif
                                    @elseif(in_array($b->status->value, ['pending', 'pending_payment']))
                                        <span class="inline-flex items-center gap-1 bg-action-glow text-action-orange text-xs font-bold px-2.5 py-1 rounded-full border border-action-orange/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-action-amber animate-pulse"></span>
                                            {{ $b->payment?->status?->value === 'pending_verification' ? 'Cek Bukti Bayar' : 'Menunggu Bayar' }}
                                        </span>
                                    @elseif(in_array($b->status->value, ['done', 'completed']))
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
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        {{-- Tombol Pelunasan On-site untuk Kasir --}}
                                        @if($b->canBeSettled() || $isDpPaid)
                                        <button
                                            type="button"
                                            @click="openSettleModal({
                                                id: {{ $b->id }},
                                                booking_code: '{{ $b->booking_code }}',
                                                customer_name: '{{ addslashes($b->customer_name) }}',
                                                court_name: '{{ addslashes($courtName) }}',
                                                total_amount: {{ (int)$b->total_amount }},
                                                paid_amount: {{ (int)$b->paid_amount }},
                                                remaining_amount: {{ (int)$b->remaining_amount }}
                                            })"
                                            class="inline-flex items-center gap-1 text-xs font-bold bg-amber-600 text-white px-2.5 py-1.5 rounded-lg hover:bg-amber-700 shadow-sm transition-colors cursor-pointer"
                                        >
                                            <span class="material-symbols-outlined text-[14px]">point_of_sale</span>
                                            Pelunasan Kasir
                                        </button>
                                        @endif

                                        {{-- Konfirmasi / Tolak Verifikasi Bukti --}}
                                        @if(in_array($b->status->value, ['pending', 'pending_payment']))
                                        <form action="{{ route('operator.booking.confirm', $b->id) }}" method="POST" onsubmit="return confirm('Konfirmasi dan kunci booking ini?')">
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

    {{-- ============ MODAL PELUNASAN KASIR ON-SITE ============ --}}
    <div
        x-show="settleModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
        style="display:none;"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
    >
        <div @click.away="settleModalOpen = false" class="bg-surface-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-surface-container-high flex flex-col gap-5">
            <div class="flex items-center justify-between border-b border-surface-container-high pb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">point_of_sale</span>
                    </div>
                    <div>
                        <h3 class="font-headline text-base font-bold text-on-surface">Pelunasan On-Site di Venue</h3>
                        <p class="text-xs text-on-surface-variant">Penyewa hadir sebelum bermain</p>
                    </div>
                </div>
                <button @click="settleModalOpen = false" class="text-on-surface-variant hover:text-on-surface p-1">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form :action="'/operator/booking/' + settleBookingId + '/settle'" method="POST" class="flex flex-col gap-4">
                @csrf

                {{-- Summary info --}}
                <div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Kode Booking:</span>
                        <span class="font-mono font-bold text-pitch-deep" x-text="settleBookingCode"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Nama Penyewa:</span>
                        <span class="font-semibold text-on-surface" x-text="settleCustomer"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Lapangan:</span>
                        <span class="font-semibold text-on-surface" x-text="settleCourt"></span>
                    </div>
                    <div class="flex justify-between border-t border-surface-container-high pt-2">
                        <span class="text-on-surface-variant">Total Nilai Sewa:</span>
                        <span class="font-bold text-on-surface" x-text="'Rp ' + formatRupiah(settleTotal)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">DP Sudah Dibayar:</span>
                        <span class="font-semibold text-pitch-deep" x-text="'Rp ' + formatRupiah(settlePaid)"></span>
                    </div>
                </div>

                {{-- Sisa Tagihan (Locked by System) --}}
                <div class="bg-amber-50 border border-amber-300 p-4 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-amber-900">Sisa Tagihan (Wajib Dilunasi)</p>
                        <p class="text-[10px] text-amber-700 mt-0.5">Nominal dihitung otomatis oleh sistem</p>
                    </div>
                    <span class="font-headline text-lg font-extrabold text-amber-900" x-text="'Rp ' + formatRupiah(settleRemaining)"></span>
                </div>

                {{-- Metode Pelunasan --}}
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-2">Pilih Metode Pelunasan di Lokasi:</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex flex-col items-center gap-1.5 p-3 rounded-xl border cursor-pointer text-center transition-all"
                            :class="settleMethod === 'cash' ? 'border-pitch-emerald bg-pitch-surface text-pitch-deep font-bold' : 'border-surface-container-high bg-surface-white text-on-surface-variant'">
                            <input type="radio" name="settlement_method" value="cash" x-model="settleMethod" class="sr-only">
                            <span class="material-symbols-outlined text-[20px]">payments</span>
                            <span class="text-xs">Tunai / Cash</span>
                        </label>
                        <label class="flex flex-col items-center gap-1.5 p-3 rounded-xl border cursor-pointer text-center transition-all"
                            :class="settleMethod === 'qris' ? 'border-pitch-emerald bg-pitch-surface text-pitch-deep font-bold' : 'border-surface-container-high bg-surface-white text-on-surface-variant'">
                            <input type="radio" name="settlement_method" value="qris" x-model="settleMethod" class="sr-only">
                            <span class="material-symbols-outlined text-[20px]">qr_code_scanner</span>
                            <span class="text-xs">QRIS Kasir</span>
                        </label>
                        <label class="flex flex-col items-center gap-1.5 p-3 rounded-xl border cursor-pointer text-center transition-all"
                            :class="settleMethod === 'manual_transfer' ? 'border-pitch-emerald bg-pitch-surface text-pitch-deep font-bold' : 'border-surface-container-high bg-surface-white text-on-surface-variant'">
                            <input type="radio" name="settlement_method" value="manual_transfer" x-model="settleMethod" class="sr-only">
                            <span class="material-symbols-outlined text-[20px]">account_balance</span>
                            <span class="text-xs">Transfer Bank</span>
                        </label>
                    </div>
                </div>

                {{-- Catatan Kasir --}}
                <div>
                    <label class="block text-xs font-medium text-on-surface mb-1">Catatan Kasir (Opsional):</label>
                    <input type="text" name="notes" placeholder="Contoh: Diterima uang pas Rp 100.000"
                        class="w-full text-xs px-3 py-2 rounded-xl border border-surface-container-high focus:ring-2 focus:ring-pitch-emerald focus:outline-none">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="settleModalOpen = false" class="flex-1 py-2.5 text-xs font-semibold text-on-surface-variant bg-surface-container-low rounded-xl hover:bg-surface-container-high transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 text-xs font-bold text-white bg-pitch-deep hover:bg-pitch-emerald rounded-xl shadow-sm transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                        Proses Pelunasan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
