<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lapangan - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-canvas-bg font-body text-on-surface antialiased"
    x-data="courtDetailApp()"
>
    <x-navbar active="lapangan" />

    <main class="w-full pt-20">

        {{-- Breadcrumb --}}
        <div class="bg-surface-white border-b border-surface-container-high">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
                <a href="{{ route('beranda') }}" class="hover:text-pitch-deep transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('lapangan') }}" class="hover:text-pitch-deep transition-colors">Lapangan</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-medium">Futsal Arena A</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- ============ LEFT COLUMN: Court Info ============ --}}
                <div class="lg:col-span-2 flex flex-col gap-6">

                    {{-- Image Gallery --}}
                    <div class="relative rounded-2xl overflow-hidden bg-surface-container aspect-video">
                        <div class="absolute inset-0 bg-gradient-to-br from-pitch-deep/80 to-pitch-emerald/40 flex items-center justify-center">
                            <div class="text-center text-surface-white">
                                <span class="material-symbols-outlined text-[80px] text-pitch-light/60">sports_soccer</span>
                                <p class="text-sm text-surface-container-highest mt-2">Foto lapangan akan tersedia</p>
                            </div>
                        </div>
                        {{-- Sport pill --}}
                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1.5 bg-pitch-deep text-pitch-light text-xs font-bold px-3 py-1.5 rounded-full">
                                <span class="material-symbols-outlined text-[14px]">sports_soccer</span>
                                Futsal
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-surface-white/90 text-on-surface text-xs font-bold px-3 py-1.5 rounded-full">
                                Vinyl Carpet
                            </span>
                        </div>
                        {{-- Availability badge --}}
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1.5 bg-pitch-surface text-pitch-deep text-xs font-bold px-3 py-1.5 rounded-full border border-pitch-light/30">
                                <span class="w-2 h-2 rounded-full bg-pitch-emerald animate-pulse"></span>
                                Tersedia Hari Ini
                            </span>
                        </div>
                    </div>

                    {{-- Court Name & Rating --}}
                    <div class="flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h1 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface">Futsal Arena A</h1>
                                <p class="text-on-surface-variant text-sm mt-1">Lapangin Sports Center • Karpet Vinyl Berkualitas Tinggi</p>
                            </div>
                            <div class="flex items-center gap-1.5 bg-action-glow px-3 py-2 rounded-xl shrink-0">
                                <span class="material-symbols-outlined text-action-amber text-[18px]">star</span>
                                <span class="font-bold text-on-surface text-sm">4.9</span>
                                <span class="text-xs text-on-surface-variant">(128)</span>
                            </div>
                        </div>

                        {{-- Quick specs row --}}
                        <div class="flex flex-wrap gap-2">
                            @foreach([
                                ['straighten', 'Ukuran 25x15m'],
                                ['lightbulb', 'LED 800 Lux'],
                                ['groups', 'Max 10 Pemain'],
                                ['ac_unit', 'Indoor A/C'],
                            ] as $spec)
                            <span class="inline-flex items-center gap-1.5 bg-surface-container-low text-on-surface-variant text-xs font-medium px-3 py-1.5 rounded-full border border-surface-container-high">
                                <span class="material-symbols-outlined text-[14px]">{{ $spec[0] }}</span>
                                {{ $spec[1] }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tabs: Jadwal / Fasilitas / Ulasan --}}
                    <div x-data="{ tab: 'jadwal' }" class="flex flex-col gap-4">

                        {{-- Tab Headers --}}
                        <div class="flex gap-1 bg-surface-container-low p-1 rounded-xl w-fit">
                            @foreach(['jadwal' => 'Pilih Jadwal', 'fasilitas' => 'Fasilitas', 'ulasan' => 'Ulasan'] as $key => $label)
                            <button
                                @click="tab = '{{ $key }}'"
                                :class="tab === '{{ $key }}' ? 'bg-surface-white text-pitch-deep shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface'"
                                class="text-sm px-4 py-2 rounded-lg transition-all font-medium"
                            >{{ $label }}</button>
                            @endforeach
                        </div>

                        {{-- Tab: Jadwal Interaktif --}}
                        <div x-show="tab === 'jadwal'" class="flex flex-col gap-4">

                            {{-- Date Selector --}}
                            <div class="flex flex-col gap-2">
                                <h3 class="text-sm font-semibold text-on-surface">Pilih Tanggal Main</h3>
                                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                                    @php
                                        $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                                    @endphp
                                    @for ($i = 0; $i < 7; $i++)
                                        @php
                                            $date = now()->addDays($i);
                                            $dayName = $days[(int)$date->format('w')];
                                            $dayNum = $date->format('d');
                                            $monthName = $months[(int)$date->format('n') - 1];
                                        @endphp
                                        <button
                                            @click="selectedDate = '{{ $date->format('Y-m-d') }}'"
                                            :class="selectedDate === '{{ $date->format('Y-m-d') }}' ? 'bg-pitch-deep text-surface-white border-pitch-deep' : 'bg-surface-white text-on-surface border-surface-container-high hover:border-pitch-light hover:text-pitch-deep'"
                                            class="flex flex-col items-center gap-0.5 px-4 py-3 rounded-xl border text-center transition-all shrink-0"
                                        >
                                            <span class="text-xs font-medium opacity-70">{{ $dayName }}</span>
                                            <span class="font-headline font-bold text-lg leading-none">{{ $dayNum }}</span>
                                            <span class="text-xs opacity-70">{{ $monthName }}</span>
                                        </button>
                                    @endfor
                                </div>
                            </div>

                            {{-- Slot Grid --}}
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-on-surface">Pilih Jam Main</h3>
                                    {{-- Legend --}}
                                    <div class="hidden sm:flex items-center gap-3 text-xs text-on-surface-variant">
                                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-pitch-surface border border-pitch-light/50"></span> Tersedia</span>
                                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-action-glow border-2 border-action-orange"></span> Dipilih</span>
                                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-court-booked-surface border border-court-booked/30"></span> Terisi</span>
                                    </div>
                                </div>

                                {{-- Time slots grid --}}
                                @php
                                    $slots = [
                                        ['time' => '08:00', 'price' => 'Rp 120.000', 'status' => 'available'],
                                        ['time' => '09:00', 'price' => 'Rp 120.000', 'status' => 'available'],
                                        ['time' => '10:00', 'price' => 'Rp 120.000', 'status' => 'booked'],
                                        ['time' => '11:00', 'price' => 'Rp 120.000', 'status' => 'booked'],
                                        ['time' => '12:00', 'price' => 'Rp 150.000', 'status' => 'available'],
                                        ['time' => '13:00', 'price' => 'Rp 150.000', 'status' => 'available'],
                                        ['time' => '14:00', 'price' => 'Rp 150.000', 'status' => 'available'],
                                        ['time' => '15:00', 'price' => 'Rp 150.000', 'status' => 'available'],
                                        ['time' => '16:00', 'price' => 'Rp 180.000', 'status' => 'available'],
                                        ['time' => '17:00', 'price' => 'Rp 180.000', 'status' => 'available'],
                                        ['time' => '18:00', 'price' => 'Rp 200.000', 'status' => 'booked'],
                                        ['time' => '19:00', 'price' => 'Rp 200.000', 'status' => 'booked'],
                                        ['time' => '20:00', 'price' => 'Rp 200.000', 'status' => 'available'],
                                        ['time' => '21:00', 'price' => 'Rp 200.000', 'status' => 'available'],
                                        ['time' => '22:00', 'price' => 'Rp 180.000', 'status' => 'available'],
                                    ];
                                @endphp
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                                    @foreach ($slots as $i => $slot)
                                        @if ($slot['status'] === 'booked')
                                            <div class="relative flex flex-col items-center justify-center gap-0.5 py-3.5 px-2 rounded-lg border border-court-booked/20 bg-court-booked-surface cursor-not-allowed min-h-[64px]">
                                                <span class="text-sm font-bold text-court-booked line-through">{{ $slot['time'] }}</span>
                                                <span class="text-[10px] text-court-booked/70">Terisi</span>
                                            </div>
                                        @else
                                            <button
                                                type="button"
                                                @click="toggleSlot({{ $i }}, '{{ $slot['time'] }}', {{ (int)preg_replace('/[^0-9]/', '', $slot['price']) }})"
                                                :class="isSelected({{ $i }}) ? 'bg-action-glow border-2 border-action-orange shadow-[0_0_0_2px_rgba(234,88,12,0.15)] text-on-surface' : 'bg-pitch-surface border border-pitch-light/40 hover:border-pitch-emerald hover:shadow-[0_4px_12px_rgba(5,150,105,0.12)] text-pitch-deep'"
                                                class="relative flex flex-col items-center justify-center gap-0.5 py-3.5 px-2 rounded-lg transition-all min-h-[64px]"
                                            >
                                                <span class="text-sm font-bold">{{ $slot['time'] }}</span>
                                                <span class="text-[10px] font-medium opacity-80">{{ $slot['price'] }}</span>
                                                <span x-show="isSelected({{ $i }})" class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-action-orange text-surface-white rounded-full flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-[11px]">check</span>
                                                </span>
                                            </button>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Mobile sticky summary --}}
                                <div x-show="selectedSlots.length > 0" x-transition class="lg:hidden fixed bottom-0 left-0 right-0 bg-surface-white border-t border-surface-container-high p-4 z-40 shadow-[0_-4px_20px_rgba(6,78,59,0.12)]" style="display:none;">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-xs text-on-surface-variant"><span x-text="selectedSlots.length"></span> jam dipilih</p>
                                            <p class="font-bold text-on-surface">Rp <span x-text="totalPrice.toLocaleString('id-ID')"></span></p>
                                        </div>
                                        <a href="{{ route('booking.checkout', 'futsal-arena-a') }}" class="flex-1 flex items-center justify-center gap-2 bg-action-orange text-surface-white font-bold text-sm py-3 rounded-lg">
                                            <span class="material-symbols-outlined text-[18px]">shopping_cart_checkout</span>
                                            Lanjut Booking
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab: Fasilitas --}}
                        <div x-show="tab === 'fasilitas'" class="grid grid-cols-1 sm:grid-cols-2 gap-4" style="display:none;">
                            @php
                                $facilities = [
                                    ['bolt', 'Pencahayaan LED', '800 Lux, standar turnamen nasional'],
                                    ['ac_unit', 'Air Conditioning', 'Ruangan full AC berkapasitas besar'],
                                    ['straighten', 'Dimensi Lapangan', '25m x 15m — Full Court FIFA Standard'],
                                    ['shower', 'Shower Room', '4 kamar mandi bersih + loker digital'],
                                    ['local_parking', 'Area Parkir', 'Parkir gratis 50 kendaraan'],
                                    ['wifi', 'WiFi Gratis', 'High-speed WiFi seluruh area venue'],
                                    ['restaurant', 'Kantin & Café', 'Minuman, camilan, dan paket makan tim'],
                                    ['videocam', 'CCTV & Keamanan', 'Rekaman 24 jam, keamanan terjamin'],
                                ];
                            @endphp
                            @foreach ($facilities as $f)
                            <div class="flex items-start gap-3 p-4 bg-surface-white rounded-xl border border-surface-container-high">
                                <div class="w-10 h-10 rounded-lg bg-pitch-surface flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-pitch-deep text-[20px]">{{ $f[0] }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-on-surface">{{ $f[1] }}</p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $f[2] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Tab: Ulasan --}}
                        <div x-show="tab === 'ulasan'" class="flex flex-col gap-4" style="display:none;">
                            {{-- Rating summary --}}
                            <div class="flex items-center gap-6 p-5 bg-surface-white rounded-xl border border-surface-container-high">
                                <div class="text-center">
                                    <div class="font-headline text-5xl font-bold text-on-surface">4.9</div>
                                    <div class="flex items-center justify-center gap-0.5 mt-1">
                                        @for($s = 0; $s < 5; $s++)
                                        <span class="material-symbols-outlined text-action-amber text-[18px]" style="font-variation-settings:'FILL' 1">star</span>
                                        @endfor
                                    </div>
                                    <div class="text-xs text-on-surface-variant mt-1">128 ulasan</div>
                                </div>
                                <div class="flex-1 flex flex-col gap-1.5">
                                    @foreach([5 => 85, 4 => 10, 3 => 3, 2 => 1, 1 => 1] as $star => $pct)
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="text-on-surface-variant w-4">{{ $star }}</span>
                                        <div class="flex-1 bg-surface-container-high rounded-full h-1.5">
                                            <div class="bg-action-amber rounded-full h-1.5" :style="'width: {{ $pct }}%'"></div>
                                        </div>
                                        <span class="text-on-surface-variant w-6 text-right">{{ $pct }}%</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Review cards --}}
                            @foreach([
                                ['Budi Santoso', '2 hari lalu', 5, 'Lapangannya bersih banget, AC-nya dingin. Proses booking online juga gampang, langsung dapat konfirmasi!'],
                                ['Sari Dewi', '1 minggu lalu', 5, 'Karpet vinyl-nya kualitasnya oke, tidak licin. Penerangan LED-nya terang banget. Pasti balik lagi!'],
                                ['Ahmad Fauzi', '2 minggu lalu', 4, 'Overall bagus, cuma tempat parkir kadang penuh di weekend. Tapi pelayanan stafnya ramah.'],
                            ] as $rev)
                            <div class="p-4 bg-surface-white rounded-xl border border-surface-container-high flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-full bg-pitch-surface flex items-center justify-center">
                                            <span class="font-bold text-pitch-deep text-sm">{{ substr($rev[0], 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-on-surface">{{ $rev[0] }}</p>
                                            <p class="text-xs text-on-surface-variant">{{ $rev[1] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-0.5">
                                        @for($s = 0; $s < $rev[2]; $s++)
                                        <span class="material-symbols-outlined text-action-amber text-[14px]" style="font-variation-settings:'FILL' 1">star</span>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-sm text-on-surface-variant leading-relaxed">{{ $rev[3] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ============ RIGHT COLUMN: Booking Summary (Sticky) ============ --}}
                <div class="hidden lg:block">
                    <div class="sticky top-28 flex flex-col gap-4">

                        {{-- Price card --}}
                        <div class="bg-surface-white rounded-2xl border border-surface-container-high shadow-sm p-6 flex flex-col gap-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs text-on-surface-variant uppercase font-semibold tracking-wider">Mulai dari</span>
                                    <div class="font-headline text-2xl font-bold text-on-surface mt-0.5">Rp 120.000<span class="text-sm font-normal text-on-surface-variant">/jam</span></div>
                                </div>
                                <div class="flex items-center gap-1 bg-action-glow px-2.5 py-1.5 rounded-lg">
                                    <span class="material-symbols-outlined text-action-amber text-[16px]" style="font-variation-settings:'FILL' 1">star</span>
                                    <span class="font-bold text-sm text-on-surface">4.9</span>
                                </div>
                            </div>

                            {{-- Slot lock notice --}}
                            <div x-show="selectedSlots.length > 0" class="flex items-center gap-2 bg-action-glow/50 border border-action-amber/20 px-3 py-2.5 rounded-lg" style="display:none;">
                                <span class="material-symbols-outlined text-action-amber text-[18px]">timer</span>
                                <span class="text-xs text-on-surface font-medium">Slot dikunci selama <strong>10 menit</strong> saat proses booking</span>
                            </div>

                            {{-- Selected slots summary --}}
                            <div x-show="selectedSlots.length > 0" class="flex flex-col gap-3" style="display:none;">
                                <h4 class="text-sm font-semibold text-on-surface">Jam yang dipilih:</h4>
                                <div class="flex flex-col gap-1.5 max-h-40 overflow-y-auto">
                                    <template x-for="slot in selectedSlots" :key="slot.index">
                                        <div class="flex items-center justify-between text-sm">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-action-orange text-[16px]">schedule</span>
                                                <span x-text="slot.time + ' – ' + slot.endTime" class="font-medium text-on-surface"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span x-text="'Rp ' + slot.price.toLocaleString('id-ID')" class="font-semibold text-on-surface"></span>
                                                <button @click="toggleSlot(slot.index, slot.time, slot.price)" class="text-on-surface-variant hover:text-error">
                                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="border-t border-surface-container-high pt-3 flex flex-col gap-2">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-on-surface-variant"><span x-text="selectedSlots.length"></span> jam × harga</span>
                                        <span class="font-semibold" x-text="'Rp ' + totalPrice.toLocaleString('id-ID')"></span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm text-on-surface-variant">
                                        <span>Biaya platform</span>
                                        <span class="font-medium text-pitch-emerald">Gratis</span>
                                    </div>
                                    <div class="flex items-center justify-between text-base font-bold text-on-surface border-t border-surface-container-high pt-2 mt-1">
                                        <span>Total</span>
                                        <span x-text="'Rp ' + totalPrice.toLocaleString('id-ID')"></span>
                                    </div>
                                </div>

                                <a href="{{ route('booking.checkout', 'futsal-arena-a') }}"
                                    class="w-full flex items-center justify-center gap-2 bg-action-orange hover:bg-tertiary-container text-surface-white font-bold text-sm py-3.5 rounded-xl shadow-sm shadow-action-orange/20 transition-all">
                                    <span class="material-symbols-outlined text-[20px]">shopping_cart_checkout</span>
                                    Lanjut ke Pembayaran
                                </a>
                            </div>

                            {{-- Empty state --}}
                            <div x-show="selectedSlots.length === 0" class="flex flex-col items-center gap-3 py-4 text-center">
                                <span class="material-symbols-outlined text-[40px] text-surface-container-highest">calendar_month</span>
                                <div>
                                    <p class="text-sm font-semibold text-on-surface">Belum ada jam dipilih</p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">Pilih tanggal dan jam di sebelah kiri untuk mulai booking</p>
                                </div>
                            </div>
                        </div>

                        {{-- Guarantees --}}
                        <div class="bg-pitch-surface rounded-xl border border-pitch-light/20 p-4 flex flex-col gap-3">
                            <h4 class="text-xs font-bold text-pitch-deep uppercase tracking-wider">Jaminan Lapangin</h4>
                            @foreach([
                                ['lock_clock', 'Slot dikunci 10 menit saat proses'],
                                ['verified', 'Anti double-booking 100%'],
                                ['payments', 'Pembayaran aman & terverifikasi'],
                                ['support_agent', 'CS siap membantu < 5 menit'],
                            ] as $g)
                            <div class="flex items-center gap-2 text-xs text-pitch-deep">
                                <span class="material-symbols-outlined text-pitch-emerald text-[16px]">{{ $g[0] }}</span>
                                {{ $g[1] }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function courtDetailApp() {
            return {
                mobileMenuOpen: false,
                selectedDate: '{{ now()->toDateString() }}',
                selectedSlots: [],
                get totalPrice() {
                    return this.selectedSlots.reduce((sum, slot) => sum + slot.price, 0);
                },
                isSelected(index) {
                    return this.selectedSlots.some(s => s.index === index);
                },
                toggleSlot(index, time, price) {
                    const idx = this.selectedSlots.findIndex(s => s.index === index);
                    if (idx !== -1) {
                        this.selectedSlots.splice(idx, 1);
                    } else {
                        const hour = parseInt(time.split(':')[0]);
                        const endTime = (hour + 1).toString().padStart(2, '0') + ':00';
                        this.selectedSlots.push({ index, time, endTime, price });
                        this.selectedSlots.sort((a, b) => a.index - b.index);
                    }
                }
            };
        }
    </script>

</body>
</html>

