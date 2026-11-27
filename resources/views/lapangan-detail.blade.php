<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lapangan - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-[#f8f9fa] font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950"
    x-data="courtDetailApp()"
>
    @auth
        <x-user-shell active="cari-lapangan" :title="$court->name" :subtitle="($court->venue->name ?? 'LapangIn Venue') . ' • ' . ($court->venue->address ?? 'DKI Jakarta')">
            <main class="w-full">
                {{-- Breadcrumb inside User Dashboard --}}
                <div class="bg-white border-b border-slate-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3 flex items-center gap-2 text-xs text-slate-500">
                        <a href="{{ route('user.dashboard') }}" class="hover:text-[#0f2444] font-medium transition-colors">Dashboard</a>
                        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        <a href="{{ route('user.cari-lapangan') }}" class="hover:text-[#0f2444] font-medium transition-colors">Cari Lapangan</a>
                        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        <span class="text-slate-900 font-bold">{{ $court->name }}</span>
                    </div>
                </div>
                @include('partials.lapangan-detail-main')
            </main>
        </x-user-shell>
    @else
        <x-navbar active="lapangan" />
        <main class="w-full pt-20">
            {{-- Breadcrumb Public --}}
            <div class="bg-surface-white border-b border-surface-container-high">
                <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
                    <a href="{{ route('beranda') }}" class="hover:text-pitch-deep transition-colors">Beranda</a>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <a href="{{ route('lapangan') }}" class="hover:text-pitch-deep transition-colors">Lapangan</a>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-on-surface font-medium">{{ $court->name }}</span>
                </div>
            </div>
            @include('partials.lapangan-detail-main')
        </main>
    @endauth

    {{-- AUTH INTERCEPT MODAL (Untuk Tamu/Guest yang ingin booking) --}}
    <div 
        x-show="showAuthModal" 
        x-transition.opacity
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            @click.away="showAuthModal = false"
            class="bg-surface-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-surface-container-high relative flex flex-col gap-5"
        >
            <button 
                @click="showAuthModal = false"
                class="absolute top-5 right-5 text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container-low transition-colors"
                aria-label="Tutup"
            >
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>

            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-pitch-surface border border-pitch-light/30 flex items-center justify-center text-pitch-deep">
                    <span class="material-symbols-outlined text-[26px]">lock_person</span>
                </div>
                <div>
                    <h3 class="font-headline text-lg font-bold text-on-surface">Masuk untuk Booking</h3>
                    <p class="text-xs text-on-surface-variant">Slot aman & anti double-booking</p>
                </div>
            </div>

            <div class="bg-surface-container-low rounded-2xl p-4 border border-surface-container-high text-xs flex flex-col gap-2">
                <div class="flex justify-between items-center text-on-surface font-semibold">
                    <span>Slot Dipilih (<span x-text="selectedSlots.length"></span> jam):</span>
                    <span class="text-pitch-deep font-bold" x-text="'Rp ' + totalPrice.toLocaleString('id-ID')"></span>
                </div>
                <p class="text-on-surface-variant leading-relaxed">
                    Untuk mengunci slot selama <strong>10 menit</strong> dan menerima e-tiket kode QR serta konfirmasi WhatsApp resmi, silakan masuk ke akun Anda atau buat akun baru.
                </p>
            </div>

            <div class="flex flex-col gap-2.5">
                <a 
                    href="{{ route('login') }}" 
                    class="w-full bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm py-3.5 rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all"
                >
                    <span class="material-symbols-outlined text-[18px]">login</span>
                    Masuk ke Akun Saya
                </a>

                <a 
                    href="{{ route('register') }}" 
                    class="w-full bg-action-orange hover:bg-tertiary-container text-surface-white font-bold text-sm py-3.5 rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all"
                >
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Daftar Akun Baru (Gratis)
                </a>
            </div>

            <div class="pt-2 border-t border-surface-container-high text-center">
                <a 
                    href="{{ route('booking.checkout', 'futsal-arena-a') }}" 
                    class="text-xs text-on-surface-variant hover:text-pitch-deep font-medium inline-flex items-center gap-1 hover:underline"
                >
                    <span>(Uji Coba: Lanjut ke Halaman Checkout &rarr;)</span>
                </a>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        function courtDetailApp() {
            return {
                mobileMenuOpen: false,
                showAuthModal: false,
                isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
                selectedDate: '{{ $date }}',
                selectedSlots: [],
                slug: '{{ $court->slug }}',
                slots: @json($availability['slots']),
                get totalPrice() {
                    return this.selectedSlots.reduce((sum, slot) => sum + slot.price, 0);
                },
                isSelected(slot) {
                    return this.selectedSlots.some(s => s.id === slot.id);
                },
                selectDate(newDate) {
                    this.selectedDate = newDate;
                    this.selectedSlots = [];
                    fetch('{{ url('/api/lapangan') }}/' + this.slug + '/slots?date=' + newDate)
                        .then(res => res.json())
                        .then(data => {
                            this.slots = data.slots;
                        });
                },
                toggleSlot(slot) {
                    if (!slot.is_available) return;
                    const idx = this.selectedSlots.findIndex(s => s.id === slot.id);
                    if (idx !== -1) {
                        this.selectedSlots.splice(idx, 1);
                    } else {
                        this.selectedSlots.push(slot);
                        this.selectedSlots.sort((a, b) => a.start_time.localeCompare(b.start_time));
                    }
                },
                triggerCheckout() {
                    if (this.selectedSlots.length === 0) return;
                    if (!this.isLoggedIn) {
                        this.showAuthModal = true;
                    } else {
                        const slotParams = this.selectedSlots.map(s => encodeURIComponent(s.start_time)).join(',');
                        window.location.href = "{{ route('booking.checkout', $court->slug) }}?date=" + this.selectedDate + "&slots=" + slotParams;
                    }
                }
            };
        }
    </script>

</body>
</html>

