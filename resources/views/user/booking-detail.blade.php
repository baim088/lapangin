<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Booking #{{ $booking->booking_code }} - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950" x-data="{ sidebarOpen: false }">

    {{-- Layout with sidebar --}}
    <div class="flex min-h-screen">

        {{-- Mobile Backdrop --}}
        <div 
            x-show="sidebarOpen" 
            x-transition.opacity
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
            style="display: none;"
        ></div>

        {{-- ============ SIDEBAR CUSTOMER ============ --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f2444] flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:z-auto shrink-0"
        >
            {{-- Logo Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
                </a>
                <span class="text-[11px] font-bold text-[#f8b710] uppercase tracking-wider bg-[#f8b710]/15 border border-[#f8b710]/30 px-2 py-0.5 rounded-md">Member</span>
            </div>

            {{-- Main Navigation Links --}}
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <div class="flex flex-col gap-1">
                    <a
                        href="{{ route('user.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all bg-[#f8b710] text-slate-950 shadow-sm"
                    >
                        <span class="material-symbols-outlined text-[20px]">dashboard</span>
                        Dashboard &amp; Booking
                    </a>

                    <a
                        href="{{ route('user.cari-lapangan') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all text-slate-300 hover:text-white hover:bg-white/5"
                    >
                        <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                        Cari Lapangan
                    </a>
                </div>

                {{-- Divider --}}
                <div class="mt-6 pt-4 border-t border-white/10 flex flex-col gap-1">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-rose-400 hover:bg-rose-500/10 transition-all text-left cursor-pointer">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>

            {{-- Bottom User Info --}}
            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                    <div class="w-9 h-9 rounded-full bg-[#f8b710]/20 border border-[#f8b710]/40 flex items-center justify-center shrink-0">
                        <span class="font-bold text-[#f8b710] text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-300 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ============ MAIN CONTENT AREA ============ --}}
        <div class="flex-1 min-w-0 flex flex-col bg-[#f8f9fa] min-h-screen">

            {{-- Topbar Header --}}
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Toggle Sidebar">
                        <span class="material-symbols-outlined text-[24px]">menu</span>
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-[#0f2444] transition-colors bg-slate-100 px-3 py-1.5 rounded-lg">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        <span>Kembali</span>
                    </a>
                    <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>
                    <div>
                        <h1 class="font-teko text-2xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">Detail Booking #{{ $booking->booking_code }}</h1>
                    </div>
                </div>
            </header>

            {{-- Content Container --}}
            <main class="p-4 sm:p-8 flex-1 flex flex-col gap-6 max-w-5xl w-full mx-auto">

                {{-- Status banner --}}
                <div class="bg-pitch-surface border border-pitch-light/30 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-pitch-deep flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-pitch-light text-[24px]" style="font-variation-settings:'FILL' 1">
                                @if($booking->status->value === 'confirmed')
                                    verified
                                @elseif($booking->status->value === 'pending')
                                    pending
                                @elseif($booking->status->value === 'done')
                                    task_alt
                                @else
                                    cancel
                                @endif
                            </span>
                        </div>
                        <div>
                            <h2 class="font-headline text-base font-bold text-pitch-deep">
                                @if($booking->status->value === 'confirmed')
                                    Booking Terkonfirmasi
                                @elseif($booking->status->value === 'pending')
                                    Menunggu Pembayaran / Verifikasi
                                @elseif($booking->status->value === 'done')
                                    Booking Selesai
                                @else
                                    Booking {{ $booking->status->label() }}
                                @endif
                            </h2>
                            <p class="text-xs text-pitch-deep/70 mt-0.5">
                                @if($booking->status->value === 'confirmed')
                                    Pembayaran berhasil. Silakan hadir tepat waktu sebelum sesi dimulai.
                                @elseif($booking->status->value === 'pending')
                                    @if($booking->payment?->status?->value === 'pending_verification')
                                        Bukti transfer sedang diverifikasi oleh operator kami.
                                    @else
                                        Selesaikan pembayaran sebelum batas waktu reservasi berakhir.
                                    @endif
                                @elseif($booking->status->value === 'done')
                                    Terima kasih telah berolahraga di Lapangin.
                                @else
                                    {{ $booking->cancelled_reason ?? 'Pemesanan telah dibatalkan.' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <span class="font-mono text-sm font-bold text-pitch-deep bg-surface-white px-3.5 py-1.5 rounded-xl border border-pitch-light/30 self-start sm:self-auto shadow-xs">{{ $booking->booking_code }}</span>
                </div>

                {{-- Grid Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Booking detail card --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-5 shadow-xs">
                        <h3 class="font-headline text-xs font-bold text-on-surface uppercase tracking-wider text-pitch-deep">Detail Pemesanan</h3>
                        <div class="flex flex-col gap-3.5 text-sm">
                            @php
                                $court = $booking->items->first()?->court;
                                $bookingDate = \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y');
                                $slots = $booking->items->map(fn($item) => substr($item->start_time, 0, 5) . ' - ' . substr($item->end_time, 0, 5) . ' WIB')->join(', ');
                            @endphp
                            <div class="flex justify-between py-2 border-b border-surface-container-high/60">
                                <span class="text-xs text-on-surface-variant">Lapangan</span>
                                <span class="text-xs font-bold text-on-surface text-right">{{ $court?->name ?? 'Lapangan Futsal' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-surface-container-high/60">
                                <span class="text-xs text-on-surface-variant">Tipe Olahraga</span>
                                <span class="text-xs font-semibold text-on-surface uppercase">{{ $court?->type ?? 'Futsal' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-surface-container-high/60">
                                <span class="text-xs text-on-surface-variant">Tanggal Bermain</span>
                                <span class="text-xs font-semibold text-on-surface text-right">{{ $bookingDate }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-surface-container-high/60">
                                <span class="text-xs text-on-surface-variant">Jam / Slot</span>
                                <span class="text-xs font-semibold text-on-surface text-right">{{ $slots }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-surface-container-high/60">
                                <span class="text-xs text-on-surface-variant">Jumlah Durasi</span>
                                <span class="text-xs font-semibold text-on-surface">{{ $booking->items->count() }} Jam</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-surface-container-high/60">
                                <span class="text-xs text-on-surface-variant">Skema Pembayaran</span>
                                <span class="text-xs font-bold text-pitch-deep">{{ $booking->isDp() ? 'Down Payment (DP ' . ($booking->dp_percentage ?? 50) . '%)' : 'Lunas (100%)' }}</span>
                            </div>
                            @if($booking->isDp())
                            <div class="flex justify-between py-1 text-xs">
                                <span class="text-on-surface-variant">DP Masuk</span>
                                <span class="font-semibold text-indigo-700">Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-1 text-xs">
                                <span class="text-on-surface-variant">Sisa Tagihan (di Kasir)</span>
                                <span class="font-bold {{ $booking->remaining_amount > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                                    {{ $booking->remaining_amount > 0 ? 'Rp ' . number_format($booking->remaining_amount, 0, ',', '.') : 'LUNAS DI LOKASI' }}
                                </span>
                            </div>
                            @endif
                            <div class="flex justify-between py-2">
                                <span class="text-xs text-on-surface-variant">Total Biaya Sewa</span>
                                <span class="font-headline text-base font-bold text-pitch-deep">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Payment Info --}}
                        <div class="bg-surface-container-lowest p-4 rounded-xl flex flex-col gap-2 border border-surface-container-high/60 text-xs">
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Status Pembayaran</span>
                                <span class="font-bold text-on-surface">{{ $booking->payment?->status?->label() ?? 'Belum Dibayar' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Metode</span>
                                <span class="font-semibold text-on-surface">{{ $booking->payment?->method?->label() ?? 'Transfer Bank BCA' }}</span>
                            </div>
                            @if($booking->payment?->paid_at)
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Waktu Bayar</span>
                                <span class="text-on-surface">{{ $booking->payment->paid_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- E-Ticket card --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-5 shadow-xs">
                        <div class="flex items-center justify-between">
                            <h3 class="font-headline text-xs font-bold text-on-surface uppercase tracking-wider text-pitch-deep">E-Tiket Masuk</h3>
                            <span class="text-[10px] font-bold text-pitch-deep bg-pitch-surface px-2.5 py-1 rounded-full uppercase">Check-In</span>
                        </div>

                        {{-- Real Scannable QR Code & Check-In Token --}}
                        <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center gap-3">
                            <div
                                class="bg-white rounded-2xl border border-slate-200 p-3 flex items-center justify-center shadow-xs"
                                x-data="{ qrSvg: '' }"
                                x-init="qrSvg = window.renderQrSvg ? window.renderQrSvg('{{ $booking->checkin_token }}', 164) : ''"
                                x-html="qrSvg"
                            ></div>
                            @if($booking->checked_in_at)
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full">
                                    <span class="material-symbols-outlined text-[14px]">verified</span>
                                    Sudah Check-In ({{ $booking->checked_in_at->format('d M Y H:i') }})
                                </span>
                            @endif
                            <p class="text-xs text-slate-600 text-center">Tunjukkan kode QR atau token ini ke petugas lapangan saat check-in</p>
                            <p class="font-mono text-xs font-bold text-[#0f2444] bg-white px-3 py-1.5 rounded-lg break-all text-center select-all border border-slate-200">{{ $booking->checkin_token }}</p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col gap-2.5">
                            <button onclick="window.print()" class="flex items-center justify-center gap-2 border border-surface-container-high text-on-surface font-semibold text-xs py-2.5 rounded-xl hover:bg-surface-container-low transition-all">
                                <span class="material-symbols-outlined text-[18px]">print</span>
                                Cetak / Simpan E-Tiket
                            </button>
                            <a href="https://wa.me/628001234567?text={{ urlencode('Halo CS Lapangin, saya ingin bertanya tentang booking ' . $booking->booking_code) }}" target="_blank"
                                class="flex items-center justify-center gap-2 border border-pitch-light/40 text-pitch-deep font-semibold text-xs py-2.5 rounded-xl hover:bg-pitch-surface transition-all">
                                <span class="material-symbols-outlined text-[18px]">chat</span>
                                Hubungi CS via WhatsApp
                            </a>
                            @if($booking->status->value === 'pending')
                                @if($booking->payment?->status?->value === 'unpaid')
                                <a href="{{ route('booking.konfirmasi', ['code' => $booking->booking_code]) }}"
                                    class="flex items-center justify-center gap-2 bg-action-orange text-surface-white font-semibold text-xs py-2.5 rounded-xl hover:bg-tertiary-container transition-all shadow-xs">
                                    <span class="material-symbols-outlined text-[18px]">payments</span>
                                    Bayar Sekarang
                                </a>
                                @endif
                                <form action="{{ route('user.booking.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 border border-error/20 text-error font-semibold text-xs py-2.5 rounded-xl hover:bg-error-container/10 transition-all">
                                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                                        Batalkan Booking
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Cancellation Policy Card --}}
                <div class="bg-surface-container-low rounded-2xl border border-surface-container-high p-5 shadow-xs">
                    <h4 class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-2 mb-3 text-pitch-deep">
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant">policy</span>
                        Kebijakan Pembatalan & Reservasi
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        @foreach([
                            ['check_circle', 'pitch-emerald', '> 24 jam sebelum jadwal', 'Refund 90%'],
                            ['warning', 'action-amber', '< 24 jam sebelum jadwal', 'Refund 50%'],
                            ['cancel', 'error', 'No-show / tidak hadir', 'Tidak ada refund'],
                        ] as $pol)
                        <div class="flex items-start gap-2 p-3 bg-surface-white rounded-xl border border-surface-container-high">
                            <span class="material-symbols-outlined text-{{ $pol[1] }} text-[18px] shrink-0">{{ $pol[0] }}</span>
                            <div>
                                <p class="font-semibold text-on-surface">{{ $pol[2] }}</p>
                                <p class="text-on-surface-variant mt-0.5">{{ $pol[3] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>
