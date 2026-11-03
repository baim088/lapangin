<footer class="w-full bg-arena-night text-surface-container-lowest border-t border-pitch-light/20">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            
            <!-- Col 1 & 2: Brand Information & Logo -->
            <div class="lg:col-span-2 flex flex-col gap-4">
                <a href="{{ route('beranda') }}" class="flex items-center">
                    <img 
                        src="{{ asset('images/logo.png') }}" 
                        alt="Lapangin" 
                        class="h-10 w-auto object-contain brightness-0 invert opacity-95"
                    >
                </a>
                <p class="text-xs text-surface-container-highest max-w-sm leading-relaxed">
                    Pusat reservasi gelanggang olahraga digital nomor satu. Booking slot instan, jaminan anti double-booking dengan sistem kunci waktu (pessimistic lock), dan konfirmasi pembayaran otomatis.
                </p>
                <div class="flex flex-col gap-2 pt-2 text-xs text-surface-container-highest">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-pitch-light text-[18px]">schedule</span>
                        <span>Operasional: <strong>08:00 - 23:00 WIB</strong> (Setiap Hari)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-pitch-light text-[18px]">verified</span>
                        <span>Instant Booking Lock Guarantee 10 Menit</span>
                    </div>
                </div>
            </div>

            <!-- Col 3: Fasilitas Arena -->
            <div class="flex flex-col gap-3">
                <h4 class="text-sm font-bold text-surface-white uppercase tracking-wider">Fasilitas Arena</h4>
                <ul class="flex flex-col gap-2 text-xs text-surface-container-highest">
                    <li class="flex items-center gap-2 hover:text-surface-white transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> 4 Lapangan Futsal Vinyl
                    </li>
                    <li class="flex items-center gap-2 hover:text-surface-white transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> 2 Lapangan Mini Soccer Sintetis
                    </li>
                    <li class="flex items-center gap-2 hover:text-surface-white transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> 6 Court Badminton BWF
                    </li>
                    <li class="flex items-center gap-2 hover:text-surface-white transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> 2 Court Padel Panoramic
                    </li>
                    <li class="flex items-center gap-2 hover:text-surface-white transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-pitch-light">check_circle</span> Shower Room & Loker Digital
                    </li>
                </ul>
            </div>

            <!-- Col 4: Menu Navigasi Footer -->
            <div class="flex flex-col gap-3">
                <h4 class="text-sm font-bold text-surface-white uppercase tracking-wider">Navigasi Utama</h4>
                <ul class="flex flex-col gap-2 text-xs text-surface-container-highest">
                    <li><a href="{{ route('beranda') }}" class="hover:text-surface-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('lapangan') }}" class="hover:text-surface-white transition-colors">Katalog Lapangan</a></li>
                    <li><a href="{{ route('tentang') }}" class="hover:text-surface-white transition-colors">Tentang Lapangin</a></li>
                    <li><a href="{{ route('kontak') }}" class="hover:text-surface-white transition-colors">Hubungi Pusat Layanan</a></li>
                    <li><a href="{{ route('beranda') }}#jadwal-interaktif" class="hover:text-surface-white transition-colors">Cek Ketersediaan Jam</a></li>
                </ul>
            </div>

            <!-- Col 5: Metode Pembayaran -->
            <div class="flex flex-col gap-3">
                <h4 class="text-sm font-bold text-surface-white uppercase tracking-wider">Pembayaran Resmi</h4>
                <p class="text-xs text-surface-container-highest leading-relaxed">
                    Konfirmasi instan otomatis via Payment Gateway Sandbox berlisensi Bank Indonesia.
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="bg-surface-white/10 px-2.5 py-1 rounded text-xs text-surface-white font-bold">QRIS</span>
                    <span class="bg-surface-white/10 px-2.5 py-1 rounded text-xs text-surface-white font-bold">Midtrans</span>
                    <span class="bg-surface-white/10 px-2.5 py-1 rounded text-xs text-surface-white font-bold">BCA VA</span>
                    <span class="bg-surface-white/10 px-2.5 py-1 rounded text-xs text-surface-white font-bold">Mandiri VA</span>
                    <span class="bg-surface-white/10 px-2.5 py-1 rounded text-xs text-surface-white font-bold">GoPay</span>
                    <span class="bg-surface-white/10 px-2.5 py-1 rounded text-xs text-surface-white font-bold">ShopeePay</span>
                </div>
            </div>

        </div>

        <div class="mt-12 pt-6 border-t border-surface-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-surface-container-highest text-xs">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-pitch-light text-[18px]">sports</span>
                <span>&copy; {{ date('Y') }} <strong>Lapangin</strong> - Sistem Booking Lapangan Olahraga.</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('tentang') }}" class="hover:text-surface-white transition-colors">Kebijakan Privasi</a>
                <a href="{{ route('tentang') }}" class="hover:text-surface-white transition-colors">Keamanan Transaksi</a>
                <a href="{{ route('kontak') }}" class="hover:text-surface-white transition-colors">SOP Gelanggang</a>
            </div>
        </div>
    </div>
</footer>
