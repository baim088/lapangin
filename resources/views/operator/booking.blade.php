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
        detailModalOpen: false,
        detailData: {},
        checkInModalOpen: false,
        activeMenu: null,
        menuStyle: '',
        openSettleModal(booking) {
            this.activeMenu = null;
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
        openDetailModal(booking) {
            this.activeMenu = null;
            this.detailData = booking;
            this.detailModalOpen = true;
        },
        toggleActionMenu(event, data) {
            if (this.activeMenu && this.activeMenu.id === data.id) {
                this.activeMenu = null;
                return;
            }
            const rect = event.currentTarget.getBoundingClientRect();
            const top = Math.min(rect.bottom + 6, window.innerHeight - 180);
            const right = Math.max(16, window.innerWidth - rect.right);
            this.menuStyle = `top: ${top}px; right: ${right}px;`;
            this.activeMenu = data;
        },
        rejectBooking(id) {
            this.activeMenu = null;
            const reason = prompt('Masukkan alasan penolakan booking:');
            if (!reason) return;
            const form = document.getElementById('reject-form-' + id);
            if (form) {
                form.querySelector('input[name=reason]').value = reason;
                form.submit();
            }
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
                    <a href="{{ $item[2] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $item[3] ? 'bg-[#f8b710] text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
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

                    <button
                        type="button"
                        @click="checkInModalOpen = true"
                        class="flex items-center gap-1.5 bg-[#0f2444] hover:bg-[#f37920] text-white font-bold text-xs px-3.5 py-2 rounded-xl transition-colors shadow-xs cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                        <span class="hidden sm:inline">Validasi Check-In</span>
                    </button>
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

                    {{-- Status filter & Export --}}
                    <div class="flex flex-wrap items-center gap-2.5">
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

                        <x-export-dropdown
                            :csv-url="route('operator.booking.export', ['search' => $search, 'status' => $activeFilter, 'venue_id' => $venue->id, 'format' => 'csv'])"
                            :pdf-url="route('operator.booking.export', ['search' => $search, 'status' => $activeFilter, 'venue_id' => $venue->id, 'format' => 'pdf'])"
                        />
                    </div>
                </div>

                {{-- Booking table --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[1040px] text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5 whitespace-nowrap">Kode</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Penyewa</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Lapangan</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Jadwal Main</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Tagihan &amp; DP</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Status</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap text-right">Aksi Operasional</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($allBookings as $b)
                                @php
                                    $courtName = $b->items->first()?->court?->name ?? 'Lapangan';
                                    $bookingDate = $b->items->first()?->booking_date ? \Carbon\Carbon::parse($b->items->first()->booking_date)->translatedFormat('d M Y') : '-';
                                    $times = $b->items->pluck('start_time')->map(fn($t) => substr($t, 0, 5))->join(', ');
                                    $isDpPaid = ($b->status->value === 'dp_paid');
                                    $initialPayment = $b->payments->firstWhere('payment_type', '!=', 'settlement') ?? $b->payment;
                                    $settlementPayment = $b->payments->firstWhere('payment_type', 'settlement');
                                    $initialMethodLabel = $initialPayment?->payment_method?->label() ?? '-';
                                    $settlementMethodLabel = $settlementPayment?->payment_method?->label()
                                        ?? ($b->settlement_method ? (\App\Enums\PaymentMethod::tryFrom($b->settlement_method)?->label() ?? strtoupper($b->settlement_method)) : null);
                                    $detailPayload = [
                                        'booking_code' => $b->booking_code,
                                        'created_at' => $b->created_at->format('d M Y H:i'),
                                        'customer_name' => $b->customer_name,
                                        'customer_phone' => $b->customer_phone,
                                        'customer_email' => $b->customer_email,
                                        'court_name' => $courtName,
                                        'venue_name' => $b->venue->name ?? '-',
                                        'booking_date' => $bookingDate,
                                        'times' => $times . ' WIB (' . $b->total_hours . ' jam)',
                                        'total_amount' => (int) $b->total_amount,
                                        'paid_amount' => (int) $b->paid_amount,
                                        'remaining_amount' => (int) $b->remaining_amount,
                                        'payment_type_label' => $b->isDp() ? 'Down Payment (DP ' . $b->dp_percentage . '%)' : 'Bayar Lunas (100%)',
                                        'payment_method' => $initialMethodLabel,
                                        'settlement_method' => $settlementMethodLabel,
                                        'status_label' => $b->status->label(),
                                        'checked_in_at' => $b->checked_in_at ? $b->checked_in_at->format('d M Y H:i') : null,
                                        'checkin_token' => $b->checkin_token,
                                        'can_checkin' => in_array($b->status->value, ['paid_in_full', 'confirmed']) && $b->checked_in_at === null,
                                        'notes' => $b->notes ?? '-',
                                        'proof_url' => $initialPayment?->proof_path ? route('booking.proof', $b->id) : null,
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="font-mono text-xs font-bold text-[#0f2444]">{{ $b->booking_code }}</span>
                                        <span class="block text-[11px] text-slate-400 mt-0.5">{{ $b->created_at->format('d/m/y H:i') }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <p class="font-semibold text-slate-900">{{ $b->customer_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $b->customer_phone }}</p>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="font-semibold text-slate-900">{{ $courtName }}</span>
                                        <span class="block text-[11px] text-slate-500">{{ $b->venue->name }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <p class="font-medium text-slate-900">{{ $bookingDate }}</p>
                                        <p class="text-xs text-slate-500">{{ $times }} WIB ({{ $b->total_hours }} jam)</p>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <p class="font-bold text-slate-900">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</p>
                                        @if($b->isDp() && $b->remaining_amount > 0 && !in_array($b->status->value, ['paid_in_full', 'confirmed', 'done', 'completed']))
                                            <span class="inline-block text-[10px] bg-indigo-50 text-indigo-700 font-semibold px-1.5 py-0.5 rounded mt-0.5">
                                                DP Masuk: Rp {{ number_format($b->paid_amount, 0, ',', '.') }}
                                            </span>
                                            <p class="text-[11px] font-bold text-amber-700 mt-0.5">Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        @if($isDpPaid)
                                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-300 text-xs font-bold px-2.5 py-1 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                DP Terbayar
                                            </span>
                                            <p class="text-[10px] text-amber-900 font-semibold mt-1">Harap Lunasi di Lokasi</p>
                                        @elseif(in_array($b->status->value, ['confirmed', 'paid_in_full']))
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                            </span>
                                            @if($b->checked_in_at)
                                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-200 ml-1">
                                                    Check-In
                                                </span>
                                            @endif
                                            @if($b->settledBy)
                                                <p class="text-[10px] text-slate-500 mt-0.5">Kasir: {{ $b->settledBy->name }} ({{ strtoupper($b->settlement_method ?? 'CASH') }})</p>
                                            @endif
                                        @elseif(in_array($b->status->value, ['pending', 'pending_payment']))
                                            <span class="inline-flex items-center gap-1 bg-amber-50 text-[#f37920] text-xs font-bold px-2.5 py-1 rounded-full border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#f8b710] animate-pulse"></span>
                                                {{ $b->payment?->status?->value === 'pending_verification' ? 'Cek Bukti Bayar' : 'Menunggu Bayar' }}
                                            </span>
                                        @elseif(in_array($b->status->value, ['done', 'completed']))
                                            <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                                <span class="material-symbols-outlined text-[12px]">task_alt</span> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                                {{ $b->status->label() }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                        <div class="inline-flex items-center justify-end gap-1">
                                            {{-- 1 Tombol Aksi Utama (Compact) --}}
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
                                                    class="inline-flex items-center gap-1 text-[11px] font-bold bg-amber-600 text-white px-2.5 py-1 rounded-md hover:bg-amber-700 transition-colors cursor-pointer"
                                                >
                                                    Pelunasan
                                                </button>
                                            @elseif(in_array($b->status->value, ['pending', 'pending_payment']))
                                                <form action="{{ route('operator.booking.confirm', $b->id) }}" method="POST" onsubmit="return confirm('Konfirmasi dan kunci booking ini?')">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-1 text-[11px] font-bold bg-[#0f2444] text-white px-2.5 py-1 rounded-md hover:bg-[#f37920] transition-colors cursor-pointer">
                                                        Konfirmasi
                                                    </button>
                                                </form>
                                                <form id="reject-form-{{ $b->id }}" action="{{ route('operator.booking.reject', $b->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    <input type="hidden" name="reason" value="">
                                                </form>
                                            @else
                                                <button
                                                    type="button"
                                                    @click="openDetailModal({{ \Illuminate\Support\Js::from($detailPayload) }})"
                                                    class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#0f2444] hover:text-[#f37920] px-2 py-1 rounded-md hover:bg-slate-100 transition-colors cursor-pointer"
                                                >
                                                    Detail
                                                </button>
                                            @endif

                                            {{-- Titik Tiga (⋮) tanpa container box --}}
                                            <button
                                                type="button"
                                                @click.stop="toggleActionMenu($event, {
                                                    id: {{ $b->id }},
                                                    is_pending: {{ in_array($b->status->value, ['pending', 'pending_payment']) ? 'true' : 'false' }},
                                                    can_checkin: {{ $detailPayload['can_checkin'] ? 'true' : 'false' }},
                                                    checkin_token: {{ \Illuminate\Support\Js::from($b->checkin_token) }},
                                                    proof_url: {{ \Illuminate\Support\Js::from($detailPayload['proof_url']) }},
                                                    detail: {{ \Illuminate\Support\Js::from($detailPayload) }}
                                                })"
                                                class="p-1 text-slate-500 hover:text-[#0f2444] rounded-md hover:bg-slate-100 transition-colors cursor-pointer"
                                                title="Aksi lainnya"
                                                aria-label="Menu Aksi"
                                            >
                                                <span class="material-symbols-outlined text-[18px]">more_vert</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-xs text-slate-500">
                                        Tidak ada data booking yang sesuai filter.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($allBookings->hasPages())
                    <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/70">
                        {{ $allBookings->links() }}
                    </div>
                    @endif
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

    {{-- ============ MODAL DETAIL BOOKING OPERATOR ============ --}}
    <div
        x-show="detailModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
        style="display:none;"
    >
        <div @click.away="detailModalOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 flex flex-col gap-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-[#0f2444] text-[#f8b710] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                    </div>
                    <div>
                        <h3 class="font-teko text-2xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">Detail Booking</h3>
                        <p class="text-xs font-mono font-bold text-[#f37920]" x-text="detailData.booking_code"></p>
                    </div>
                </div>
                <button type="button" @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1 cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <span class="text-slate-500 block">Penyewa</span>
                    <span class="font-bold text-slate-900" x-text="detailData.customer_name"></span>
                    <span class="block text-slate-500" x-text="detailData.customer_phone"></span>
                </div>
                <div>
                    <span class="text-slate-500 block">Status</span>
                    <span class="font-bold text-[#0f2444]" x-text="detailData.status_label"></span>
                    <span class="block text-slate-400" x-text="detailData.created_at"></span>
                </div>
                <div>
                    <span class="text-slate-500 block">Lapangan &amp; Venue</span>
                    <span class="font-bold text-slate-900" x-text="detailData.court_name"></span>
                    <span class="block text-slate-500" x-text="detailData.venue_name"></span>
                </div>
                <div>
                    <span class="text-slate-500 block">Jadwal Main</span>
                    <span class="font-bold text-slate-900" x-text="detailData.booking_date"></span>
                    <span class="block text-slate-500" x-text="detailData.times"></span>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 text-xs flex flex-col gap-2">
                <div class="flex justify-between">
                    <span class="text-slate-500">Skema Pembayaran</span>
                    <span class="font-bold text-slate-900" x-text="detailData.payment_type_label"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Metode Bayar User (Booking)</span>
                    <span class="font-bold text-[#0f2444]" x-text="detailData.payment_method"></span>
                </div>
                <template x-if="detailData.settlement_method">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Metode Pelunasan Kasir</span>
                        <span class="font-bold text-emerald-700" x-text="detailData.settlement_method"></span>
                    </div>
                </template>
                <div class="flex justify-between">
                    <span class="text-slate-500">Total Tagihan</span>
                    <span class="font-bold text-slate-900" x-text="'Rp ' + formatRupiah(detailData.total_amount || 0)"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Sudah Dibayar</span>
                    <span class="font-bold text-emerald-700" x-text="'Rp ' + formatRupiah(detailData.paid_amount || 0)"></span>
                </div>
                <div class="flex justify-between border-t border-slate-100 pt-2">
                    <span class="text-slate-500">Sisa Tagihan</span>
                    <span class="font-bold text-[#f37920]" x-text="'Rp ' + formatRupiah(detailData.remaining_amount || 0)"></span>
                </div>
                <div class="border-t border-slate-100 pt-2">
                    <span class="text-slate-500 block">Catatan Penyewa</span>
                    <span class="text-slate-700" x-text="detailData.notes || '-'"></span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <template x-if="detailData.proof_url">
                    <a :href="detailData.proof_url" target="_blank" class="px-4 py-2 text-xs font-bold bg-[#0f2444] text-white rounded-xl hover:bg-[#f37920] transition-colors">
                        Lihat Bukti Transfer
                    </a>
                </template>
                <button type="button" @click="detailModalOpen = false" class="px-4 py-2 text-xs font-bold bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ============ FLOATING ACTION DROPDOWN (⋮) ============ --}}
    <div
        x-show="activeMenu !== null"
        @click.away="activeMenu = null"
        @keydown.escape.window="activeMenu = null"
        :style="menuStyle"
        class="fixed z-50 w-48 bg-white rounded-xl shadow-xl border border-slate-200 py-1.5 text-xs"
        style="display:none;"
    >
        <button
            type="button"
            @click="openDetailModal(activeMenu.detail)"
            class="w-full px-3.5 py-2 text-left text-slate-700 hover:bg-slate-50 hover:text-[#0f2444] font-semibold flex items-center gap-2 cursor-pointer"
        >
            <span class="material-symbols-outlined text-[16px] text-slate-400">visibility</span>
            Detail Booking
        </button>

        <template x-if="activeMenu && activeMenu.proof_url">
            <a
                :href="activeMenu.proof_url"
                target="_blank"
                @click="activeMenu = null"
                class="w-full px-3.5 py-2 text-left text-[#0f2444] hover:bg-slate-50 font-semibold flex items-center gap-2"
            >
                <span class="material-symbols-outlined text-[16px] text-emerald-600">receipt</span>
                Lihat Bukti Transfer
            </a>
        </template>

        <template x-if="activeMenu && activeMenu.can_checkin">
            <form action="{{ route('operator.booking.checkin') }}" method="POST">
                @csrf
                <input type="hidden" name="token" :value="activeMenu.checkin_token">
                <button
                    type="submit"
                    class="w-full px-3.5 py-2 text-left text-emerald-700 hover:bg-emerald-50 font-semibold flex items-center gap-2 cursor-pointer"
                >
                    <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                    Validasi Check-In
                </button>
            </form>
        </template>

        <template x-if="activeMenu && activeMenu.is_pending">
            <button
                type="button"
                @click="rejectBooking(activeMenu.id)"
                class="w-full px-3.5 py-2 text-left text-rose-600 hover:bg-rose-50 font-semibold flex items-center gap-2 cursor-pointer border-t border-slate-100 mt-1 pt-2"
            >
                <span class="material-symbols-outlined text-[16px]">cancel</span>
                Tolak Booking
            </button>
        </template>
    </div>

    {{-- ============ MODAL VALIDASI CHECK-IN QR / TOKEN ============ --}}
    <div
        x-show="checkInModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
        style="display:none;"
    >
        <div @click.away="checkInModalOpen = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 flex flex-col gap-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-[#0f2444] text-[#f8b710] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">qr_code_scanner</span>
                    </div>
                    <div>
                        <h3 class="font-teko text-2xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">Validasi Check-In</h3>
                        <p class="text-xs text-slate-500">Scan QR E-Tiket atau masukkan Kode Booking / Token</p>
                    </div>
                </div>
                <button type="button" @click="checkInModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1 cursor-pointer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('operator.booking.checkin') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label for="checkin_token_input" class="text-xs font-bold text-slate-700">Token Check-In / Kode Booking</label>
                    <input
                        type="text"
                        id="checkin_token_input"
                        name="token"
                        required
                        autofocus
                        placeholder="Scan QR atau ketik LPN-2026-XXXX..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-mono text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#f8b710] focus:bg-white"
                    >
                    <p class="text-[11px] text-slate-500">Check-in dibuka mulai <strong>1 jam sebelum jadwal bermain</strong> untuk booking yang sudah lunas di venue Anda.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" @click="checkInModalOpen = false" class="px-4 py-2 text-xs font-bold bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#0f2444] hover:bg-[#f37920] text-white rounded-xl transition-colors cursor-pointer inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        Validasi &amp; Check-In
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
