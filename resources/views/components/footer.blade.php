<footer class="w-full bg-[#0f2444] text-white border-t border-white/10">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            
            <!-- Col 1 & 2: Brand Information & Logo -->
            <div class="lg:col-span-2 flex flex-col gap-4">
                <a href="{{ route('beranda') }}" class="flex items-center">
                    <img 
                        src="{{ asset('images/logo-white.png') }}" 
                        alt="Lapangin" 
                        class="h-10 w-auto object-contain opacity-95"
                    >
                </a>
                <div class="flex items-center gap-1.5 mt-1 mb-1">
                    <span class="w-10 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                    <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                </div>
                <p class="text-xs text-slate-300 max-w-sm leading-relaxed">
                    Sistem reservasi gelanggang olahraga modern terintegrasi. Booking slot instan, jaminan anti double-booking dengan sistem kunci waktu (pessimistic lock), dan konfirmasi pembayaran otomatis terpercaya.
                </p>
                <div class="flex flex-col gap-2 pt-2 text-xs text-slate-300">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#f8b710] text-[18px]">schedule</span>
                        <span>Operasional: <strong>08:00 - 23:00 WIB</strong> (Setiap Hari)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#f8b710] text-[18px]">verified</span>
                        <span>Instant Booking Lock Guarantee 10 Menit</span>
                    </div>
                </div>
            </div>

            <!-- Col 3: Fasilitas Arena -->
            <div class="flex flex-col gap-3">
                <h4 class="font-teko text-xl tracking-wider text-white uppercase font-bold">Fasilitas Arena</h4>
                <div class="w-6 h-0.5 bg-[#f8b710] rounded-full mb-1"></div>
                <ul class="flex flex-col gap-2.5 text-xs text-slate-300">
                    <li class="flex items-center gap-2 hover:text-[#f8b710] transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-[#f8b710]">check_circle</span> 4 Lapangan Futsal Vinyl
                    </li>
                    <li class="flex items-center gap-2 hover:text-[#f8b710] transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-[#f8b710]">check_circle</span> 6 Karpet Badminton BWF
                    </li>
                    <li class="flex items-center gap-2 hover:text-[#f8b710] transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-[#f8b710]">check_circle</span> 2 Lapangan Mini Soccer Rumput FIFA
                    </li>
                    <li class="flex items-center gap-2 hover:text-[#f8b710] transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-[#f8b710]">check_circle</span> 2 Padel Panoramic Glass Court
                    </li>
                    <li class="flex items-center gap-2 hover:text-[#f8b710] transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-[#f8b710]">check_circle</span> Lighting 800 Lux Standar Siaran
                    </li>
                </ul>
            </div>

            <!-- Col 4: Navigasi Cepat -->
            <div class="flex flex-col gap-3">
                <h4 class="font-teko text-xl tracking-wider text-white uppercase font-bold">Menu Navigasi</h4>
                <div class="w-6 h-0.5 bg-[#f8b710] rounded-full mb-1"></div>
                <ul class="flex flex-col gap-2.5 text-xs text-slate-300">
                    <li><a href="{{ route('beranda') }}" class="hover:text-[#f8b710] transition-colors">Beranda</a></li>
                    <li><a href="{{ route('lapangan') }}" class="hover:text-[#f8b710] transition-colors">Katalog Lapangan</a></li>
                    <li><a href="{{ route('beranda') }}#jadwal-interaktif" class="hover:text-[#f8b710] transition-colors">Cek Jadwal Slot Hari Ini</a></li>
                    <li><a href="{{ route('tentang') }}" class="hover:text-[#f8b710] transition-colors">Tentang LapangIn</a></li>
                    <li><a href="{{ route('kontak') }}" class="hover:text-[#f8b710] transition-colors">Hubungi Bantuan / CS</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-[#f8b710] transition-colors">Masuk Akun Penyewa</a></li>
                </ul>
            </div>

            <!-- Col 5: Kontak & Lokasi -->
            <div class="flex flex-col gap-3">
                <h4 class="font-teko text-xl tracking-wider text-white uppercase font-bold">Hubungi Kami</h4>
                <div class="w-6 h-0.5 bg-[#f8b710] rounded-full mb-1"></div>
                <div class="flex flex-col gap-3 text-xs text-slate-300">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#f8b710] text-[18px] shrink-0 mt-0.5">location_on</span>
                        <span>Jl. Gelanggang Olahraga Utama No. 88, Jakarta Selatan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#f8b710] text-[18px] shrink-0">call</span>
                        <a href="tel:+6280012345678" class="hover:text-[#f8b710]">(021) 7890-1234</a>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#f8b710] text-[18px] shrink-0">chat</span>
                        <a href="https://wa.me/6280012345678" target="_blank" class="hover:text-[#f8b710]">+62 800-1234-5678 (WhatsApp)</a>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#f8b710] text-[18px] shrink-0">mail</span>
                        <a href="mailto:info@lapangin.id" class="hover:text-[#f8b710]">info@lapangin.id</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Copyright & Disclaimer Bar -->
    <div class="w-full bg-[#0b1a30] border-t border-white/10 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} LapangIn &bull; Sistem Reservasi Lapangan Olahraga Real-Time. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('tentang') }}" class="hover:text-[#f8b710] transition-colors">Syarat & Ketentuan</a>
                <a href="{{ route('kontak') }}" class="hover:text-[#f8b710] transition-colors">Kebijakan Privasi</a>
                <span class="text-[#f8b710] font-semibold">100% Anti Double-Booking Guarantee</span>
            </div>
        </div>
    </div>
</footer>
