<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased" x-data="{ mobileMenuOpen: false, countdown: 599, init() { setInterval(() => { if(this.countdown > 0) this.countdown--; }, 1000); }, formatTime(s) { return String(Math.floor(s/60)).padStart(2,'0')+':'+String(s%60).padStart(2,'0'); } }">
    <x-navbar active="lapangan" />

    <main class="w-full pt-20 pb-24">

        {{-- Lock countdown bar --}}
        <div class="bg-action-orange text-surface-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-2.5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[18px] animate-pulse">timer</span>
                    <span class="font-semibold">Selesaikan pembayaran sebelum waktu habis!</span>
                </div>
                <div class="font-headline font-bold text-lg" x-text="formatTime(countdown)">09:59</div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-8 py-10 flex flex-col gap-6">

            {{-- Header --}}
            <div class="text-center flex flex-col gap-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-action-glow border-2 border-action-amber mx-auto">
                    <span class="material-symbols-outlined text-action-amber text-[32px]">qr_code_scanner</span>
                </div>
                <h1 class="font-headline text-2xl font-bold text-on-surface">Scan & Bayar QRIS</h1>
                <p class="text-sm text-on-surface-variant">Gunakan aplikasi e-wallet atau m-banking untuk scan QR</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- QR Code mockup --}}
                <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col items-center gap-4">
                    <div class="w-48 h-48 bg-on-surface rounded-2xl flex items-center justify-center">
                        <div class="w-40 h-40 bg-surface-white rounded-xl grid grid-cols-7 gap-0.5 p-2">
                            @for($r = 0; $r < 7; $r++)
                                @for($c = 0; $c < 7; $c++)
                                    @php
                                        $isCorner = ($r < 2 && $c < 2) || ($r < 2 && $c >= 5) || ($r >= 5 && $c < 2);
                                        $isDark = $isCorner || rand(0,1);
                                    @endphp
                                    <div class="rounded-sm {{ $isDark ? 'bg-on-surface' : 'bg-surface-white' }}"></div>
                                @endfor
                            @endfor
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-on-surface-variant">Total yang harus dibayar</p>
                        <p class="font-headline text-2xl font-bold text-on-surface">Rp 240.000</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-[14px]">verified_user</span>
                        Didukung semua e-wallet & m-banking
                    </div>
                </div>

                {{-- Booking summary --}}
                <div class="flex flex-col gap-4">
                    <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 flex flex-col gap-3">
                        <h3 class="text-sm font-bold text-on-surface">Detail Booking</h3>
                        <div class="flex flex-col gap-2.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Lapangan</span>
                                <span class="font-semibold">Futsal Arena A</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Tanggal</span>
                                <span class="font-semibold">Senin, 11 Nov 2026</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Jam</span>
                                <span class="font-semibold">09:00 – 11:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Durasi</span>
                                <span class="font-semibold">2 Jam</span>
                            </div>
                            <div class="border-t border-surface-container-high pt-2 flex justify-between font-bold">
                                <span>Total</span>
                                <span class="text-action-orange">Rp 240.000</span>
                            </div>
                        </div>
                    </div>

                    {{-- Instructions --}}
                    <div class="bg-pitch-surface rounded-xl border border-pitch-light/20 p-4 flex flex-col gap-2.5">
                        <h4 class="text-xs font-bold text-pitch-deep uppercase tracking-wider">Cara Bayar QRIS</h4>
                        @foreach([
                            'Buka aplikasi GoPay, OVO, Dana, m-banking, atau e-wallet lainnya',
                            'Pilih menu Bayar / Scan QR',
                            'Arahkan kamera ke kode QR di sebelah kiri',
                            'Konfirmasi jumlah Rp 240.000',
                            'Pembayaran dikonfirmasi otomatis dalam 1-2 menit',
                        ] as $i => $step)
                        <div class="flex items-start gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-pitch-deep text-surface-white text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $i + 1 }}</div>
                            <span class="text-xs text-pitch-deep">{{ $step }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Upload bukti (manual transfer fallback) --}}
            <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-headline text-base font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">upload_file</span>
                        Upload Bukti Transfer (Opsional)
                    </h3>
                    <span class="text-xs text-on-surface-variant bg-surface-container-low px-2.5 py-1 rounded-full">Khusus transfer manual</span>
                </div>

                <div
                    class="border-2 border-dashed border-surface-container-high rounded-xl p-8 flex flex-col items-center gap-3 text-center hover:border-pitch-light transition-colors cursor-pointer"
                    @dragover.prevent
                    @drop.prevent
                >
                    <span class="material-symbols-outlined text-[40px] text-surface-container-highest">add_photo_alternate</span>
                    <div>
                        <p class="text-sm font-semibold text-on-surface">Drag & drop bukti transfer</p>
                        <p class="text-xs text-on-surface-variant mt-1">atau klik untuk pilih file (JPG, PNG, PDF maks 5MB)</p>
                    </div>
                    <input type="file" class="hidden" accept="image/*,.pdf">
                    <button type="button" class="text-xs font-semibold text-pitch-deep border border-pitch-light/40 px-4 py-2 rounded-lg hover:bg-pitch-surface transition-colors">
                        Pilih File
                    </button>
                </div>
            </div>

            {{-- Check status button --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('booking.sukses') }}" class="flex-1 flex items-center justify-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm py-3.5 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px]">task_alt</span>
                    Saya Sudah Bayar
                </a>
                <a href="{{ route('kontak') }}" class="flex items-center justify-center gap-2 border border-surface-container-high text-on-surface font-semibold text-sm px-6 py-3.5 rounded-xl hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[18px]">support_agent</span>
                    Butuh Bantuan?
                </a>
            </div>

        </div>
    </main>

</body>
</html>

