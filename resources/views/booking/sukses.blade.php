<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil! - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950">
    <x-user-shell active="dashboard" title="Status Booking" :subtitle="'Kode Booking #' . $booking->booking_code">

    <main class="w-full min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-lg flex flex-col items-center gap-6 text-center">

            {{-- Success animation --}}
            <div class="relative">
                <div class="w-28 h-28 rounded-full bg-pitch-surface flex items-center justify-center">
                    <div class="w-20 h-20 rounded-full bg-pitch-emerald/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[52px] text-pitch-emerald" style="font-variation-settings:'FILL' 1">check_circle</span>
                    </div>
                </div>
                <div class="absolute -top-1 -right-1 w-8 h-8 rounded-full bg-action-amber flex items-center justify-center">
                    <span class="material-symbols-outlined text-surface-white text-[18px]" style="font-variation-settings:'FILL' 1">celebration</span>
                </div>
            </div>

            {{-- Title --}}
            <div class="flex flex-col gap-2">
                @if (in_array($booking->status->value, ['pending', 'pending_payment']))
                    <h1 class="font-headline text-3xl font-bold text-on-surface">Bukti Diterima!</h1>
                    <p class="text-on-surface-variant text-sm max-w-sm">
                        Bukti pembayaran Anda sedang diverifikasi oleh operator. Anda akan mendapat konfirmasi segera.
                    </p>
                @else
                    <h1 class="font-headline text-3xl font-bold text-on-surface">Booking Berhasil!</h1>
                    <p class="text-on-surface-variant text-sm max-w-sm">
                        Pembayaran Anda telah dikonfirmasi. Konfirmasi booking telah dikirim ke WhatsApp dan email Anda.
                    </p>
                @endif
            </div>

            {{-- Booking code & Real QR Code --}}
            <div class="bg-white border border-slate-200 rounded-2xl px-6 py-6 flex flex-col items-center gap-3 w-full shadow-xs">
                <span class="text-xs font-bold text-[#0f2444] uppercase tracking-wider">Kode Booking</span>
                <span class="font-teko text-4xl font-bold text-[#0f2444] tracking-wider leading-none">{{ $booking->booking_code }}</span>
                <div
                    class="p-2.5 bg-white rounded-xl border border-slate-200 shadow-xs mt-1"
                    x-data="{ qrSvg: '' }"
                    x-init="window.renderQrSvg && window.renderQrSvg('{{ $booking->checkin_token }}', 148).then(svg => qrSvg = svg)"
                    x-html="qrSvg"
                ></div>
                <div class="text-center w-full">
                    <span class="text-[11px] text-slate-500 block">Token Check-In (Tunjukkan ke Petugas):</span>
                    <span class="font-mono text-xs font-bold text-[#0f2444] bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 break-all select-all inline-block mt-1">{{ $booking->checkin_token }}</span>
                </div>
                <span class="text-xs text-emerald-700 font-semibold flex items-center gap-1 mt-1">
                    <span class="material-symbols-outlined text-[14px]">verified</span>
                    Tersimpan otomatis di akun Anda
                </span>
            </div>

            {{-- Detail card --}}
            <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 w-full flex flex-col gap-4 text-left">
                <h3 class="font-headline text-sm font-bold text-on-surface uppercase tracking-wider">Detail Booking</h3>
                <div class="flex flex-col gap-3 text-sm">
                    <div class="flex items-center justify-between py-2 border-b border-surface-container-high">
                        <span class="flex items-center gap-2 text-on-surface-variant"><span class="material-symbols-outlined text-[16px]">stadium</span> Lapangan</span>
                        <span class="font-semibold">{{ $booking->items->first()?->court->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-surface-container-high">
                        <span class="flex items-center gap-2 text-on-surface-variant"><span class="material-symbols-outlined text-[16px]">calendar_today</span> Tanggal</span>
                        <span class="font-semibold">{{ $booking->items->first()?->booking_date ? \Carbon\Carbon::parse($booking->items->first()->booking_date)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-surface-container-high">
                        <span class="flex items-center gap-2 text-on-surface-variant"><span class="material-symbols-outlined text-[16px]">schedule</span> Waktu</span>
                        <span class="font-semibold">{{ $booking->items->map(fn($it) => \Carbon\Carbon::parse($it->start_time)->format('H:i') . '–' . \Carbon\Carbon::parse($it->end_time)->format('H:i'))->implode(', ') }} WIB</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-surface-container-high">
                        <span class="flex items-center gap-2 text-on-surface-variant"><span class="material-symbols-outlined text-[16px]">person</span> Nama</span>
                        <span class="font-semibold">{{ $booking->customer_name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="flex items-center gap-2 text-on-surface-variant"><span class="material-symbols-outlined text-[16px]">payments</span> Total Bayar</span>
                        <span class="font-bold text-action-orange">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- What's next --}}
            <div class="bg-surface-white rounded-2xl border border-surface-container-high p-5 w-full text-left flex flex-col gap-3">
                <h3 class="text-sm font-bold text-on-surface">Apa yang perlu Anda lakukan?</h3>
                <div class="flex flex-col gap-3">
                    @php
                        $firstItem = $booking->items->first();
                        $courtName = $firstItem?->court?->name ?? '-';
                        $bookingDate = $firstItem?->booking_date ? \Carbon\Carbon::parse($firstItem->booking_date)->translatedFormat('l, d M') : '-';
                        $startTime = $firstItem?->start_time ? \Carbon\Carbon::parse($firstItem->start_time)->format('H:i') : '-';
                        $nextSteps = [
                            ['notifications_active', 'Cek WhatsApp Anda', 'Konfirmasi booking + reminder otomatis H-1 & H-0 sudah terkirim', 'Sudah terkirim'],
                            ['person_pin_circle', 'Datang tepat waktu', 'Hadir paling lambat 10 menit sebelum jadwal untuk check-in', $bookingDate . ' ' . $startTime],
                            ['qr_code_2', 'Tunjukkan kode booking', 'Scan QR atau tunjukkan kode ' . $booking->booking_code . ' ke petugas lapangan', $booking->booking_code],
                        ];
                    @endphp
                    @foreach($nextSteps as $step)
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-pitch-surface flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-pitch-deep text-[18px]">{{ $step[0] }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-on-surface">{{ $step[1] }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $step[2] }}</p>
                        </div>
                        <span class="text-xs font-semibold text-pitch-emerald bg-pitch-surface px-2 py-1 rounded-full shrink-0">{{ $step[3] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full">
                <a href="{{ route('user.dashboard') }}" class="flex-1 flex items-center justify-center gap-2 bg-[#0f2444] hover:bg-[#f37920] text-white font-bold text-sm py-3.5 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    Lihat Riwayat Booking
                </a>
                <a href="{{ route('user.cari-lapangan') }}" class="flex-1 flex items-center justify-center gap-2 border border-slate-200 bg-white text-slate-700 font-semibold text-sm py-3.5 rounded-xl hover:bg-slate-50 transition-all">
                    <span class="material-symbols-outlined text-[18px]">sports_soccer</span>
                    Cari Lapangan Lain
                </a>
            </div>

            {{-- Share CTA --}}
            <div class="flex items-center gap-3 text-sm">
                <span class="text-on-surface-variant">Ajak teman main bareng?</span>
                @php
                    $firstItem = $firstItem ?? $booking->items->first();
                    $shareCourtName = $firstItem?->court?->name ?? 'lapangan';
                    $shareDate = $firstItem?->booking_date ? \Carbon\Carbon::parse($firstItem->booking_date)->format('d M Y') : '';
                    $shareStart = $firstItem?->start_time ? \Carbon\Carbon::parse($firstItem->start_time)->format('H:i') : '';
                    $shareEnd = $firstItem?->end_time ? \Carbon\Carbon::parse($firstItem->end_time)->format('H:i') : '';
                    $shareText = "Yuk main bareng! Saya sudah booking {$shareCourtName}, {$shareDate} {$shareStart} - {$shareEnd} di Lapangin.";
                @endphp
                <a href="https://wa.me/?text={{ urlencode($shareText) }}" target="_blank"
                    class="flex items-center gap-1.5 text-pitch-deep font-semibold hover:underline">
                    <span class="material-symbols-outlined text-[16px]">share</span>
                    Bagikan via WhatsApp
                </a>
            </div>

        </div>
    </main>
    </x-user-shell>

</body>
</html>

