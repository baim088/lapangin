<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kontak & Pusat Layanan Pelanggan Lapangin. Hubungi tim kami untuk reservasi turnamen, kemitraan, dan bantuan booking lapangan.">
    <title>Kontak Kami - {{ config('app.name', 'Lapangin') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ mobileMenuOpen: false, formSent: false }"
    class="bg-surface font-body text-on-surface antialiased selection:bg-secondary-container selection:text-on-secondary-container"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="kontak" />

    <main class="w-full pt-20 bg-surface min-h-[calc(100vh-80px)]">
        
        <!-- HEADER BANNER -->
        <section class="w-full bg-arena-night text-surface-white py-16 px-4 sm:px-8 border-b border-pitch-light/20 relative overflow-hidden">
            <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-action-orange/20 blur-3xl pointer-events-none"></div>
            <div class="w-full max-w-7xl mx-auto text-center relative z-10">
                <span class="inline-flex items-center gap-2 bg-pitch-surface/10 text-pitch-light border border-pitch-light/30 px-3.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">
                    Pelayanan Ramah & Cepat
                </span>
                <h1 class="font-headline text-3xl sm:text-5xl font-bold uppercase tracking-tight text-surface-white mb-4">
                    Hubungi Lapangin
                </h1>
                <p class="font-body text-sm sm:text-base text-surface-container-highest max-w-2xl mx-auto leading-relaxed">
                    Punya pertanyaan seputar sewa turnamen, membership mingguan, atau kendala pembayaran? Tim kami siap membantu Anda setiap hari.
                </p>
            </div>
        </section>

        <!-- KONTEN UTAMA KONTAK -->
        <section class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Left Column: Informasi Kontak & Lokasi -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    <!-- Box Lokasi Gelanggang -->
                    <div class="bg-surface-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-pitch-deep text-surface-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[26px]">location_on</span>
                        </div>
                        <div>
                            <h3 class="font-headline text-base font-bold text-on-surface">Lokasi Gelanggang</h3>
                            <p class="text-xs sm:text-sm text-on-surface-variant mt-1 leading-relaxed">
                                Lapangin Sports Hub, Jl. Gelora Pemuda No. 7, Kawasan Olahraga Terpadu, Jakarta Selatan, 12190
                            </p>
                            <span class="inline-block mt-2 text-xs font-semibold text-pitch-deep bg-pitch-surface px-2.5 py-1 rounded-md">
                                Akses Strategis & Bebas Ganjil Genap
                            </span>
                        </div>
                    </div>

                    <!-- Box WhatsApp CS -->
                    <div class="bg-surface-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-pitch-emerald text-surface-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[26px]">chat</span>
                        </div>
                        <div>
                            <h3 class="font-headline text-base font-bold text-on-surface">WhatsApp Resmi</h3>
                            <p class="text-xs text-on-surface-variant mt-1">Customer Service (Respon cepat &lt; 5 menit)</p>
                            <a 
                                href="https://wa.me/6280012345678?text=Halo%20Admin%20Lapangin%2C%20saya%20ingin%20bertanya" 
                                target="_blank"
                                class="inline-flex items-center gap-1.5 mt-2 font-bold text-sm text-pitch-deep hover:text-pitch-emerald transition-colors"
                            >
                                +62 800-1234-5678 <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
                    </div>

                    <!-- Box Email -->
                    <div class="bg-surface-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-action-orange text-surface-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[26px]">mail</span>
                        </div>
                        <div>
                            <h3 class="font-headline text-base font-bold text-on-surface">Email Dukungan & Kerjasama</h3>
                            <p class="text-xs text-on-surface-variant mt-1">Untuk sponsorship, turnamen liga, dan kendala akun</p>
                            <span class="inline-block mt-2 font-bold text-sm text-on-surface">
                                support@lapangin.id
                            </span>
                        </div>
                    </div>

                    <!-- Box Jam Buka -->
                    <div class="bg-pitch-surface p-5 rounded-2xl border border-pitch-light/30 flex items-center gap-3 text-pitch-deep text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-[24px]">schedule</span>
                        <div>
                            <strong>Jam Operasional Resepsionis:</strong><br>
                            Buka Setiap Hari, 08.00 - 23.00 WIB (Termasuk Hari Libur Nasional)
                        </div>
                    </div>

                </div>

                <!-- Right Column: Formulir Pesan Cepat -->
                <div class="lg:col-span-7 bg-surface-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-lg">
                    <h2 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface mb-2">
                        Kirim Pesan Cepat
                    </h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant mb-6 leading-relaxed">
                        Silakan tinggalkan pesan atau permohonan sewa khusus di bawah. Staf Lapangin akan segera membalas ke kontak WhatsApp atau Email Anda.
                    </p>

                    <!-- Alert Success jika form dikirim -->
                    <div 
                        x-show="formSent" 
                        x-transition
                        class="mb-6 p-4 rounded-xl bg-pitch-surface border border-pitch-light text-pitch-deep text-xs sm:text-sm flex items-center gap-2"
                        style="display: none;"
                    >
                        <span class="material-symbols-outlined text-pitch-emerald text-[20px]">check_circle</span>
                        <span>Terima kasih! Pesan Anda telah kami terima. Kami akan segera menghubungi Anda kembali.</span>
                    </div>

                    <form @submit.prevent="formSent = true" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-on-surface block mb-1.5">Nama Lengkap</label>
                                <input 
                                    type="text" 
                                    required 
                                    placeholder="Contoh: Budi Pratama"
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl text-sm border border-transparent focus:border-pitch-emerald focus:bg-white focus:outline-none transition-all"
                                >
                            </div>
                            <div>
                                <label class="text-xs font-bold text-on-surface block mb-1.5">Nomor WhatsApp</label>
                                <input 
                                    type="tel" 
                                    required 
                                    placeholder="081234567890"
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl text-sm border border-transparent focus:border-pitch-emerald focus:bg-white focus:outline-none transition-all"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-on-surface block mb-1.5">Alamat Email</label>
                                <input 
                                    type="email" 
                                    required 
                                    placeholder="budi@example.com"
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl text-sm border border-transparent focus:border-pitch-emerald focus:bg-white focus:outline-none transition-all"
                                >
                            </div>
                            <div>
                                <label class="text-xs font-bold text-on-surface block mb-1.5">Kategori Kepentingan</label>
                                <select class="w-full bg-surface-container-low px-4 py-3 rounded-xl text-sm border border-transparent focus:border-pitch-emerald focus:bg-white focus:outline-none transition-all cursor-pointer">
                                    <option>Pertanyaan Ketersediaan Jam</option>
                                    <option>Sewa Turnamen / Event Komunitas</option>
                                    <option>Paket Langganan Mingguan / Rutin</option>
                                    <option>Bantuan Pembayaran / Reschedule</option>
                                    <option>Kemitraan Fasilitas Venue</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-on-surface block mb-1.5">Rincian Pesan atau Permintaan</label>
                            <textarea 
                                rows="5" 
                                required 
                                placeholder="Jelaskan kebutuhan Anda, rencana tanggal, atau pertanyaan terkait fasilitas..."
                                class="w-full bg-surface-container-low px-4 py-3 rounded-xl text-sm border border-transparent focus:border-pitch-emerald focus:bg-white focus:outline-none transition-all"
                            ></textarea>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm py-4 rounded-xl transition-all shadow-md flex items-center justify-center gap-2"
                        >
                            <span class="material-symbols-outlined text-[20px]">send</span>
                            Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>

            </div>
        </section>

        <!-- PANDUAN KEDATANGAN & PARKIR -->
        <section class="w-full bg-surface-white py-16 border-t border-slate-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8">
                <div class="text-center max-w-xl mx-auto mb-10">
                    <span class="text-xs text-pitch-emerald uppercase tracking-wider font-bold block mb-1">Panduan Pengunjung</span>
                    <h2 class="font-headline text-2xl sm:text-3xl font-bold text-on-surface tracking-tight uppercase">Akses & Petunjuk Arah</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-surface-container-low p-6 rounded-2xl border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-pitch-deep text-surface-white flex items-center justify-center mb-3 font-bold">
                            <span class="material-symbols-outlined text-[22px]">directions_car</span>
                        </div>
                        <h4 class="font-bold text-sm text-on-surface mb-1">Kendaraan Pribadi</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Tersedia area parkir luas gratis dengan pengawasan CCTV 24 jam untuk 60 mobil dan 200 motor.
                        </p>
                    </div>

                    <div class="bg-surface-container-low p-6 rounded-2xl border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-action-orange text-surface-white flex items-center justify-center mb-3 font-bold">
                            <span class="material-symbols-outlined text-[22px]">directions_transit</span>
                        </div>
                        <h4 class="font-bold text-sm text-on-surface mb-1">Transportasi Umum</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Hanya 5 menit jalan kaki dari Halte TransJakarta Gelora dan 10 menit dari Stasiun MRT terdekat.
                        </p>
                    </div>

                    <div class="bg-surface-container-low p-6 rounded-2xl border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-pitch-emerald text-surface-white flex items-center justify-center mb-3 font-bold">
                            <span class="material-symbols-outlined text-[22px]">qr_code_scanner</span>
                        </div>
                        <h4 class="font-bold text-sm text-on-surface mb-1">Check-in di Resepsionis</h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Cukup tunjukkan kode booking unik (mis. BK-20261103-0001) dari email ke kasir untuk masuk ke lapangan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER COMPONENT -->
    <x-footer />
</body>
</html>
