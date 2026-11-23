<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kontak & Pusat Layanan Pelanggan Lapangin. Hubungi tim kami untuk reservasi turnamen, kemitraan, dan bantuan booking lapangan.">
    <title>Kontak Kami - {{ config('app.name', 'Lapangin') }}</title>

    <!-- Google Fonts: Teko & Nunito Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ mobileMenuOpen: false, formSent: false }"
    class="bg-canvas-bg font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="kontak" />

    <main class="w-full pt-20 bg-canvas-bg min-h-[calc(100vh-80px)]">
        
        <!-- HEADER BANNER -->
        <section class="w-full bg-[#0f2444] text-white py-16 px-4 sm:px-8 border-b border-white/10 relative overflow-hidden">
            <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-[#f8b710]/15 blur-3xl pointer-events-none"></div>
            <div class="w-full max-w-7xl mx-auto text-center relative z-10">
                <span class="text-xs font-bold text-[#f8b710] uppercase tracking-widest block mb-1">LAYANAN BANTUAN PELANGGAN</span>
                <h1 class="font-teko text-5xl sm:text-6xl lg:text-7xl font-bold uppercase tracking-tight text-white mb-2 leading-none">
                    HUBUNGI TIM LAPANGIN
                </h1>
                <div class="flex items-center justify-center gap-1.5 mt-2 mb-4">
                    <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                    <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                </div>
                <p class="font-body text-sm sm:text-base text-slate-200/90 max-w-2xl mx-auto leading-relaxed">
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
                        <div class="w-12 h-12 rounded-2xl bg-[#0f2444] text-[#f8b710] flex items-center justify-center shrink-0 shadow-md">
                            <span class="material-symbols-outlined text-[26px]">location_on</span>
                        </div>
                        <div>
                            <h3 class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900 leading-none">Lokasi Gelanggang</h3>
                            <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">
                                LapangIn Sports Hub, Jl. Gelora Pemuda No. 7, Kawasan Olahraga Terpadu, Jakarta Selatan, 12190
                            </p>
                            <span class="inline-block mt-2 text-xs font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                Akses Strategis & Bebas Ganjil Genap
                            </span>
                        </div>
                    </div>

                    <!-- Box WhatsApp CS -->
                    <div class="bg-surface-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md">
                            <span class="material-symbols-outlined text-[26px]">chat</span>
                        </div>
                        <div>
                            <h3 class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900 leading-none">WhatsApp Resmi</h3>
                            <p class="text-xs text-slate-500 mt-1">Customer Service (Respon cepat &lt; 5 menit)</p>
                            <a 
                                href="https://wa.me/6280012345678?text=Halo%20Admin%20Lapangin%2C%20saya%20ingin%20bertanya" 
                                target="_blank"
                                class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-700 hover:underline mt-2"
                            >
                                +62 800-1234-5678 <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
                    </div>

                    <!-- Box Email & Telepon -->
                    <div class="bg-surface-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#0f2444] text-[#f8b710] flex items-center justify-center shrink-0 shadow-md">
                            <span class="material-symbols-outlined text-[26px]">contact_support</span>
                        </div>
                        <div>
                            <h3 class="font-teko text-2xl font-bold uppercase tracking-wide text-slate-900 leading-none">Email & Telepon Kantor</h3>
                            <p class="text-xs text-slate-500 mt-1">Inquiry Turnamen & Kemitraan Sponsorship</p>
                            <div class="flex flex-col gap-1 mt-2 text-xs sm:text-sm font-semibold text-slate-800">
                                <a href="mailto:info@lapangin.id" class="hover:text-[#f37920]">info@lapangin.id</a>
                                <a href="tel:+6280012345678" class="hover:text-[#f37920]">(021) 7890-1234</a>
                            </div>
                        </div>
                    </div>

                    <!-- Jam Operasional Gelanggang -->
                    <div class="bg-[#0f2444] text-white p-6 rounded-3xl shadow-md border border-white/10 flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center shrink-0 text-[#f8b710]">
                            <span class="material-symbols-outlined text-[26px]">schedule</span>
                        </div>
                        <div>
                            <h3 class="font-teko text-2xl font-bold uppercase tracking-wide text-white leading-none">Jam Operasional</h3>
                            <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                                Buka setiap hari (Senin s/d Minggu): <strong>08.00 &ndash; 23.00 WIB</strong>
                            </p>
                            <p class="text-xs text-[#f8b710] mt-1 font-medium">
                                Termasuk hari libur nasional dan tanggal merah.
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Form Kirim Pesan Cepat -->
                <div class="lg:col-span-7 bg-surface-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-lg">
                    <div class="mb-6">
                        <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">FORMULIR PESAN</span>
                        <h2 class="font-teko text-3xl sm:text-4xl font-bold text-slate-900 uppercase leading-none">
                            KIRIMKAN PESAN / PERTANYAAN
                        </h2>
                        <div class="w-10 h-1 bg-[#f8b710] rounded-full my-2"></div>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1">
                            Isi formulir di bawah ini dan perwakilan tim kami akan merespons dalam waktu 1x24 jam kerja.
                        </p>
                    </div>

                    <!-- Alert Sukses (Alpine.js State) -->
                    <div 
                        x-show="formSent" 
                        x-transition 
                        class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs sm:text-sm"
                        style="display: none;"
                    >
                        <span class="material-symbols-outlined text-[24px] text-emerald-600 shrink-0">check_circle</span>
                        <div>
                            <strong>Pesan Anda berhasil terkirim!</strong> Tim operasional kami akan segera menghubungi Anda.
                        </div>
                    </div>

                    <form 
                        @submit.prevent="formSent = true; setTimeout(() => formSent = false, 6000)" 
                        class="flex flex-col gap-4"
                    >
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-1.5">Nama Lengkap *</label>
                                <input 
                                    type="text" 
                                    required 
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm border border-slate-200 focus:border-[#f8b710] focus:bg-white focus:outline-none transition-all"
                                >
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-1.5">Nomor WhatsApp *</label>
                                <input 
                                    type="tel" 
                                    required 
                                    placeholder="0812-3456-7890"
                                    class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm border border-slate-200 focus:border-[#f8b710] focus:bg-white focus:outline-none transition-all"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-1.5">Alamat Email *</label>
                                <input 
                                    type="email" 
                                    required 
                                    placeholder="budi@example.com"
                                    class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm border border-slate-200 focus:border-[#f8b710] focus:bg-white focus:outline-none transition-all"
                                >
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-1.5">Kategori Kepentingan</label>
                                <select class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm border border-slate-200 focus:border-[#f8b710] focus:bg-white focus:outline-none transition-all cursor-pointer">
                                    <option>Pertanyaan Ketersediaan Jam</option>
                                    <option>Sewa Turnamen / Event Komunitas</option>
                                    <option>Paket Langganan Mingguan / Rutin</option>
                                    <option>Bantuan Pembayaran / Reschedule</option>
                                    <option>Kemitraan Fasilitas Venue</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-1.5">Rincian Pesan atau Permintaan</label>
                            <textarea 
                                rows="5" 
                                required 
                                placeholder="Jelaskan kebutuhan Anda, rencana tanggal, atau pertanyaan terkait fasilitas..."
                                class="w-full bg-slate-50 px-4 py-3 rounded-xl text-sm border border-slate-200 focus:border-[#f8b710] focus:bg-white focus:outline-none transition-all"
                            ></textarea>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-xs uppercase tracking-wider py-4 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 transform active:scale-95 cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>

            </div>
        </section>

        <!-- PANDUAN KEDATANGAN & PARKIR -->
        <section class="w-full bg-[#f8f9fa] py-16 border-t border-slate-200">
            <div class="w-full max-w-5xl mx-auto px-4 sm:px-8">
                <div class="text-center max-w-xl mx-auto mb-10">
                    <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">PANDUAN PENGUNJUNG</span>
                    <h2 class="font-teko text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight uppercase leading-none">AKSES & PETUNJUK ARAH</h2>
                    <div class="flex items-center justify-center gap-1 mt-1 mb-2">
                        <span class="w-10 h-0.5 bg-[#f8b710] rounded-full inline-block"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-surface-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-[#0f2444] text-[#f8b710] flex items-center justify-center mb-3 font-bold shadow">
                            <span class="material-symbols-outlined text-[22px]">directions_car</span>
                        </div>
                        <h4 class="font-teko text-xl font-bold uppercase text-slate-900 mb-1 leading-none">KENDARAAN PRIBADI</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Tersedia area parkir luas gratis dengan pengawasan CCTV 24 jam untuk 60 mobil dan 200 motor.
                        </p>
                    </div>

                    <div class="bg-surface-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-[#f8b710] text-slate-950 flex items-center justify-center mb-3 font-bold shadow">
                            <span class="material-symbols-outlined text-[22px]">directions_transit</span>
                        </div>
                        <h4 class="font-teko text-xl font-bold uppercase text-slate-900 mb-1 leading-none">TRANSPORTASI UMUM</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Hanya 5 menit jalan kaki dari Halte TransJakarta Gelora dan 10 menit dari Stasiun MRT terdekat.
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
