<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Booking - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-canvas-bg font-body text-on-surface antialiased"
    x-data="checkoutApp()"
>
    <x-navbar active="lapangan" />

    <main class="w-full pt-20 pb-24">

        {{-- Header: Countdown Lock Bar --}}
        <div class="bg-action-orange text-surface-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-2.5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[18px] animate-pulse">timer</span>
                    <span class="font-semibold">Slot Anda dikunci! Selesaikan pembayaran dalam:</span>
                </div>
                <div class="font-headline font-bold text-lg" x-text="formatTime(countdown)">09:59</div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div class="bg-surface-white border-b border-surface-container-high">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
                <a href="{{ route('beranda') }}" class="hover:text-pitch-deep">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('lapangan') }}" class="hover:text-pitch-deep">Lapangan</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('lapangan.detail', $court->slug) }}" class="hover:text-pitch-deep">{{ $court->name }}</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-medium">Checkout</span>
            </div>
        </div>

        {{-- Steps --}}
        <div class="bg-surface-white border-b border-surface-container-high">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-4">
                <div class="flex items-center gap-0">
                    @php $steps = ['Detail Pemesanan', 'Pembayaran', 'Konfirmasi']; @endphp
                    @foreach($steps as $i => $step)
                        <div class="flex items-center gap-2 {{ $i < count($steps)-1 ? 'flex-1' : '' }}">
                            <div class="flex items-center gap-2 shrink-0">
                                <div @class([
                                    'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0',
                                    'bg-pitch-deep text-surface-white' => $i === 0,
                                    'bg-surface-container-high text-on-surface-variant' => $i > 0,
                                ])>{{ $i + 1 }}</div>
                                <span @class([
                                    'text-sm hidden sm:block',
                                    'font-bold text-pitch-deep' => $i === 0,
                                    'text-on-surface-variant' => $i > 0,
                                ])>{{ $step }}</span>
                            </div>
                            @if($i < count($steps) - 1)
                                <div class="flex-1 h-px bg-surface-container-high mx-2"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-8">
            <form method="POST" action="{{ route('booking.checkout.store', $court->slug) }}">
                @csrf
                <input type="hidden" name="booking_date" value="{{ $bookingDate }}">
                @foreach($slotItems as $item)
                    <input type="hidden" name="slots[]" value="{{ $item['start_time'] }}">
                @endforeach

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- LEFT: Form --}}
                    <div class="lg:col-span-2 flex flex-col gap-6">

                        {{-- Section: Data Pemesan --}}
                        <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-5">
                            <div class="flex items-center justify-between">
                                <h2 class="font-headline text-lg font-bold text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-pitch-deep text-[22px]">person</span>
                                    Data Pemesan
                                </h2>
                                <span class="inline-flex items-center gap-1.5 bg-pitch-surface text-pitch-deep text-xs font-bold px-3 py-1 rounded-full border border-pitch-light/30">
                                    <span class="w-2 h-2 rounded-full bg-pitch-emerald"></span>
                                    Akun Terverifikasi
                                </span>
                            </div>

                            {{-- Logged in badge --}}
                            @auth
                            <div class="flex items-center justify-between p-3.5 bg-surface-container-low rounded-xl border border-surface-container-high">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-pitch-surface flex items-center justify-center font-bold text-pitch-deep text-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-on-surface">{{ auth()->user()->name }} (Penyewa)</p>
                                        <p class="text-[11px] text-on-surface-variant">Data reservasi & e-tiket akan otomatis tersimpan di Dashboard Anda</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-pitch-deep shrink-0 ml-2">Akun Aktif</span>
                            </div>
                            @endauth

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-sm font-semibold text-on-surface">Nama Lengkap <span class="text-error">*</span></label>
                                    <input type="text" name="customer_name" placeholder="Nama lengkap" value="{{ old('customer_name', auth()->user()?->name) }}"
                                        class="w-full px-4 py-3 rounded-lg border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald transition-all">
                                    @error('customer_name')
                                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-sm font-semibold text-on-surface">Nomor WhatsApp <span class="text-error">*</span></label>
                                    <input type="tel" name="customer_phone" placeholder="08xx-xxxx-xxxx" value="{{ old('customer_phone', auth()->user()?->phone) }}"
                                        class="w-full px-4 py-3 rounded-lg border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald transition-all">
                                    <p class="text-xs text-on-surface-variant">Konfirmasi booking akan dikirim via WhatsApp</p>
                                    @error('customer_phone')
                                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-1.5 sm:col-span-2">
                                    <label class="text-sm font-semibold text-on-surface">Email <span class="text-error">*</span></label>
                                    <input type="email" name="customer_email" placeholder="email@domain.com" value="{{ old('customer_email', auth()->user()?->email) }}"
                                        class="w-full px-4 py-3 rounded-lg border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald transition-all">
                                    @error('customer_email')
                                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-1.5 sm:col-span-2">
                                    <label class="text-sm font-semibold text-on-surface">Catatan untuk Operator (opsional)</label>
                                    <textarea name="notes" rows="2" placeholder="Contoh: butuh rompi, bawa peralatan sendiri..."
                                        class="w-full px-4 py-3 rounded-lg border border-surface-container-high bg-surface-white text-sm focus:outline-none focus:ring-2 focus:ring-pitch-emerald transition-all resize-none">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section: Metode Pembayaran --}}
                        <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-5">
                            <h2 class="font-headline text-lg font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-pitch-deep text-[22px]">payments</span>
                                Metode Pembayaran
                            </h2>

                            <div class="flex flex-col gap-3">
                                @php
                                    $methods = [
                                        ['qris', 'QRIS', 'Bayar dengan scan QR dari semua e-wallet & m-banking', true],
                                        ['ewallet', 'GoPay / OVO / Dana', 'Transfer e-wallet langsung', false],
                                        ['va', 'Virtual Account Bank', 'BCA, Mandiri, BNI, BRI', false],
                                        ['bank_transfer', 'Transfer Manual', 'Konfirmasi via upload bukti transfer', false],
                                    ];
                                @endphp
                                @foreach($methods as $m)
                                <label
                                    class="flex items-center gap-4 p-4 rounded-xl border cursor-pointer transition-all"
                                    :class="paymentMethod === '{{ $m[0] }}' ? 'border-pitch-emerald bg-pitch-surface' : 'border-surface-container-high bg-surface-white hover:border-pitch-light/40'"
                                >
                                    <input type="radio" name="payment_method" value="{{ $m[0] }}" x-model="paymentMethod" class="sr-only">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-low flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-pitch-deep text-[20px]">{{ match($m[0]) {
                                            'qris' => 'qr_code_scanner',
                                            'ewallet' => 'account_balance_wallet',
                                            'va' => 'account_balance',
                                            default => 'receipt_long'
                                        } }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-on-surface">{{ $m[1] }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $m[2] }}</p>
                                    </div>
                                    <div
                                        class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all"
                                        :class="paymentMethod === '{{ $m[0] }}' ? 'border-pitch-emerald' : 'border-surface-container-high'"
                                    >
                                        <div class="w-2.5 h-2.5 rounded-full bg-pitch-emerald transition-all" :class="paymentMethod === '{{ $m[0] }}' ? 'opacity-100' : 'opacity-0'"></div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('payment_method')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                            @error('slots')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                            @error('court')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SOP / Ketentuan --}}
                        <div class="bg-surface-container-low rounded-xl border border-surface-container-high p-5 flex flex-col gap-3">
                            <h3 class="text-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-pitch-deep text-[18px]">policy</span>
                                Syarat & Kebijakan
                            </h3>
                            <ul class="flex flex-col gap-2 text-xs text-on-surface-variant">
                                @foreach([
                                    'Pembatalan lebih dari 24 jam sebelum jadwal: refund 90%',
                                    'Pembatalan kurang dari 24 jam: refund 50%',
                                    'No-show tanpa konfirmasi: tidak ada refund',
                                    'Pemain diwajibkan hadir maksimal 10 menit sebelum jadwal',
                                    'Lapangan luar ruang: booking batal otomatis jika hujan lebat, refund penuh',
                                ] as $rule)
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[14px] text-pitch-emerald mt-0.5 shrink-0">info</span>
                                    {{ $rule }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>

                    {{-- RIGHT: Order Summary --}}
                    <div class="flex flex-col gap-4">
                        <div class="sticky top-28 flex flex-col gap-4">

                            {{-- Booking Detail Card --}}
                            <div class="bg-surface-white rounded-2xl border border-surface-container-high p-6 flex flex-col gap-5">
                                <h3 class="font-headline text-base font-bold text-on-surface">Ringkasan Pesanan</h3>

                                {{-- Court info --}}
                                <div class="flex items-start gap-3 pb-4 border-b border-surface-container-high">
                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-pitch-deep to-pitch-emerald flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-surface-white text-[28px]">
                                            {{ in_array($court->sport_category->value, ['badminton', 'padel']) ? 'sports_tennis' : 'sports_soccer' }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $court->name }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $court->venue->name ?? 'Lapangin Sports Center' }}</p>
                                        <span class="inline-block bg-pitch-surface text-pitch-deep text-[10px] font-bold px-2 py-0.5 rounded mt-1">{{ $court->surface_type }}</span>
                                    </div>
                                </div>

                                {{-- Booking time --}}
                                <div class="flex flex-col gap-2.5 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="flex items-center gap-1.5 text-on-surface-variant"><span class="material-symbols-outlined text-[16px]">calendar_today</span> Tanggal</span>
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($bookingDate)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="flex items-center gap-1.5 text-on-surface-variant"><span class="material-symbols-outlined text-[16px]">timer</span> Durasi</span>
                                        <span class="font-semibold">{{ count($slotItems) }} Jam</span>
                                    </div>
                                </div>

                                {{-- Price breakdown: list each hour individually for non-sequential support --}}
                                <div class="flex flex-col gap-2 border-t border-surface-container-high pt-4 text-sm">
                                    @foreach($slotItems as $item)
                                        <div class="flex items-center justify-between text-on-surface-variant text-xs sm:text-sm">
                                            <span>{{ $item['start_time'] }} – {{ $item['end_time'] }} {{ $item['is_prime'] ? '(Prime)' : '' }}</span>
                                            <span>Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="flex items-center justify-between text-on-surface-variant">
                                        <span>Biaya platform</span>
                                        <span class="text-pitch-emerald font-semibold">Gratis</span>
                                    </div>
                                    <div class="flex items-center justify-between font-bold text-base text-on-surface border-t border-surface-container-high pt-3 mt-1">
                                        <span>Total Pembayaran</span>
                                        <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                {{-- CTA --}}
                                <label class="flex items-start gap-2.5 cursor-pointer">
                                    <input type="checkbox" x-model="agreed" class="w-4 h-4 mt-0.5 rounded border-surface-container-high text-pitch-emerald focus:ring-pitch-emerald flex-shrink-0">
                                    <span class="text-xs text-on-surface-variant leading-relaxed">
                                        Saya menyetujui syarat, ketentuan, dan kebijakan pembatalan Lapangin
                                    </span>
                                </label>

                                <button
                                    type="submit"
                                    :disabled="!agreed"
                                    :class="agreed ? 'bg-action-orange hover:bg-tertiary-container shadow-action-orange/20 cursor-pointer' : 'bg-surface-container-high text-on-surface-variant cursor-not-allowed'"
                                    class="w-full flex items-center justify-center gap-2 text-surface-white font-bold text-sm py-3.5 rounded-xl shadow-sm transition-all"
                                >
                                    <span class="material-symbols-outlined text-[20px]">lock</span>
                                    Bayar Rp {{ number_format($totalPrice, 0, ',', '.') }}
                                </button>

                                <p class="text-center text-xs text-on-surface-variant flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[14px] text-pitch-emerald">verified_user</span>
                                    Transaksi aman & terenkripsi SSL
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <script>
        function checkoutApp() {
            return {
                mobileMenuOpen: false,
                paymentMethod: 'qris',
                agreed: false,
                countdown: 599,
                timer: null,
                init() {
                    this.timer = setInterval(() => {
                        if (this.countdown > 0) { this.countdown--; }
                        else { clearInterval(this.timer); }
                    }, 1000);
                },
                formatTime(seconds) {
                    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                    const s = (seconds % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                }
            };
        }
    </script>

</body>
</html>

