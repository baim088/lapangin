<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Booking - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false }">
    <x-navbar active="beranda" />

    <main class="w-full pt-20 pb-12">
        {{-- Breadcrumb --}}
        <div class="bg-surface-white border-b border-surface-container-high">
            <div class="max-w-4xl mx-auto px-4 sm:px-8 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
                <a href="{{ route('user.dashboard') }}" class="hover:text-pitch-deep">Dashboard</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-medium">Detail Booking #{{ $id }}</span>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-8 py-8 flex flex-col gap-6">

            {{-- Status banner --}}
            <div class="bg-pitch-surface border border-pitch-light/30 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-pitch-deep flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-pitch-light text-[24px]" style="font-variation-settings:'FILL' 1">verified</span>
                </div>
                <div class="flex-1">
                    <h2 class="font-headline text-base font-bold text-pitch-deep">Booking Terkonfirmasi</h2>
                    <p class="text-xs text-pitch-deep/70 mt-0.5">Pembayaran berhasil. Silakan hadir tepat waktu.</p>
                </div>
                <span class="font-mono text-sm font-bold text-pitch-deep bg-pitch-light/20 px-3 py-1.5 rounded-lg">LPN-2026-4729</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Booking detail --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-5">
                    <h3 class="font-headline text-sm font-bold text-on-surface uppercase tracking-wider">Detail Pemesanan</h3>
                    <div class="flex flex-col gap-3 text-sm">
                        @foreach([
                            ['stadium', 'Lapangan', 'Futsal Arena A'],
                            ['calendar_today', 'Tanggal Main', 'Senin, 11 November 2026'],
                            ['schedule', 'Jam Main', '09:00 – 11:00 WIB (2 Jam)'],
                            ['person', 'Nama Pemesan', 'Ahmad Fauzi'],
                            ['phone', 'WhatsApp', '0812-3456-7890'],
                            ['payments', 'Total Bayar', 'Rp 240.000'],
                            ['receipt', 'Metode Pembayaran', 'QRIS'],
                        ] as $detail)
                        <div class="flex items-start gap-3 py-2 border-b border-surface-container-high last:border-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-[16px] mt-0.5 shrink-0">{{ $detail[0] }}</span>
                            <div class="flex-1">
                                <span class="text-on-surface-variant text-xs">{{ $detail[1] }}</span>
                                <p class="font-semibold text-on-surface">{{ $detail[2] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- QR check-in + actions --}}
                <div class="flex flex-col gap-4">
                    {{-- QR Code untuk check-in --}}
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col items-center gap-3">
                        <h3 class="font-headline text-sm font-bold text-on-surface self-start">QR Check-In</h3>
                        <div class="w-36 h-36 bg-on-surface rounded-xl flex items-center justify-center">
                            <div class="w-28 h-28 bg-surface-white rounded-lg grid grid-cols-7 gap-0 p-1.5">
                                @for($r = 0; $r < 7; $r++)
                                    @for($c = 0; $c < 7; $c++)
                                        @php $isDark = (($r < 2 && $c < 2) || ($r < 2 && $c >= 5) || ($r >= 5 && $c < 2) || rand(0,1)); @endphp
                                        <div class="{{ $isDark ? 'bg-on-surface' : 'bg-surface-white' }}"></div>
                                    @endfor
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-on-surface-variant text-center">Tunjukkan QR ini ke petugas saat check-in</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2">
                        <button class="flex items-center justify-center gap-2 border border-surface-container-high text-on-surface font-semibold text-sm py-3 rounded-xl hover:bg-surface-container-low transition-all">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            Unduh Tiket PDF
                        </button>
                        <a href="https://wa.me/628001234567?text=Halo CS Lapangin, saya ingin bertanya tentang booking LPN-2026-4729" target="_blank"
                            class="flex items-center justify-center gap-2 border border-pitch-light/40 text-pitch-deep font-semibold text-sm py-3 rounded-xl hover:bg-pitch-surface transition-all">
                            <span class="material-symbols-outlined text-[18px]">chat</span>
                            Hubungi CS via WhatsApp
                        </button>
                        <button class="flex items-center justify-center gap-2 border border-error/20 text-error font-semibold text-sm py-3 rounded-xl hover:bg-error-container/10 transition-all">
                            <span class="material-symbols-outlined text-[18px]">cancel</span>
                            Batalkan Booking
                        </button>
                    </div>
                </div>
            </div>

            {{-- Cancellation policy --}}
            <div class="bg-surface-container-low rounded-xl border border-surface-container-high p-5">
                <h4 class="text-sm font-bold text-on-surface flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">policy</span>
                    Kebijakan Pembatalan
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    @foreach([
                        ['check_circle', 'pitch-emerald', '> 24 jam sebelum jadwal', 'Refund 90%'],
                        ['warning', 'action-amber', '< 24 jam sebelum jadwal', 'Refund 50%'],
                        ['cancel', 'error', 'No-show / tidak hadir', 'Tidak ada refund'],
                    ] as $pol)
                    <div class="flex items-start gap-2 p-3 bg-surface-white rounded-lg border border-surface-container-high">
                        <span class="material-symbols-outlined text-{{ $pol[1] }} text-[16px] shrink-0">{{ $pol[0] }}</span>
                        <div>
                            <p class="font-semibold text-on-surface">{{ $pol[2] }}</p>
                            <p class="text-on-surface-variant mt-0.5">{{ $pol[3] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-on-surface-variant hover:text-pitch-deep">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Dashboard
            </a>

        </div>
    </main>

    <x-footer />

</body>
</html>

