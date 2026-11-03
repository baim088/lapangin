<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tentang Lapangin - Profil platform sistem booking lapangan olahraga online modern. Menghilangkan double-booking dengan sistem kunci waktu 10 menit.">
    <title>Tentang Kami - {{ config('app.name', 'Lapangin') }}</title>

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
    x-data="{ mobileMenuOpen: false }"
    class="bg-surface font-body text-on-surface antialiased selection:bg-secondary-container selection:text-on-secondary-container"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="tentang" />

    <main class="w-full pt-20 bg-surface min-h-[calc(100vh-80px)]">
        
        <!-- HEADER BANNER -->
        <section class="w-full bg-arena-night text-surface-white py-20 px-4 sm:px-8 border-b border-pitch-light/20 relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-pitch-emerald/20 blur-3xl pointer-events-none"></div>
            <div class="w-full max-w-7xl mx-auto text-center relative z-10">
                <span class="inline-flex items-center gap-2 bg-pitch-surface/10 text-pitch-light border border-pitch-light/30 px-3.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">
                    Profil Platform Olahraga
                </span>
                <h1 class="font-headline text-3xl sm:text-5xl font-bold uppercase tracking-tight text-surface-white mb-4">
                    Mengenal Lapangin
                </h1>
                <p class="font-body text-sm sm:text-base text-surface-container-highest max-w-2xl mx-auto leading-relaxed">
                    Menghubungkan pecinta olahraga dengan fasilitas gelanggang terstandarisasi melalui teknologi reservasi real-time tanpa risiko jadwal bentrok.
                </p>
            </div>
        </section>

        <!-- CERITA & MISI -->
        <section class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 flex flex-col gap-6">
                    <div>
                        <span class="text-xs text-pitch-emerald uppercase tracking-wider font-bold block mb-1">Cerita Di Balik Layar</span>
                        <h2 class="font-headline text-3xl sm:text-4xl font-bold text-on-surface tracking-tight uppercase">
                            Revolusi Pemesanan Lapangan Konvensional
                        </h2>
                    </div>

                    <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Sebelum <strong>Lapangin</strong> hadir, memesan gelanggang olahraga adalah proses yang melelahkan: harus mengirim chat WhatsApp berkali-kali ke pengelola, menunggu balasan yang lambat, menebak-nebak jam berapa yang masih kosong, hingga risiko paling fatal: <em>jadwal tumpang tindih (double booking)</em> saat tiba di lokasi.
                    </p>

                    <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Bagi pengelola venue, pembukuan kertas manual dan transfer bukti rekening juga kerap menimbulkan kekacauan rekapitulasi omzet dan jadwal maintenance lapangan.
                    </p>

                    <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        <strong>Lapangin</strong> hadir sebagai solusi terpadu satu atap: sebuah sistem kalender real-time yang memadukan algoritma <em>Pessimistic Time-Lock 10 Menit</em>, gateway pembayaran instan terverifikasi otomatis, dan kontrol operasional kasir walk-in di lokasi.
                    </p>
                </div>

                <div class="lg:col-span-6 relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-200 bg-pitch-deep">
                        <img 
                            src="https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=800&q=80" 
                            alt="Hall Arena Lapangin"
                            class="w-full h-[420px] object-cover"
                        >
                    </div>
                </div>

            </div>
        </section>

        <!-- 4 PILAR KEUNGGULAN (PRD ALIGNED) -->
        <section class="w-full bg-surface-white py-20 border-y border-slate-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-xl mx-auto mb-14">
                    <span class="text-xs text-action-orange uppercase tracking-wider font-bold block mb-1">Standar Layanan</span>
                    <h2 class="font-headline text-3xl font-bold text-on-surface tracking-tight uppercase">4 Pilar Keunggulan Lapangin</h2>
                    <p class="text-sm text-on-surface-variant mt-2">
                        Dibangun dengan arsitektur modern berstandar enterprise untuk kenyamanan maksimal Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <div class="bg-surface-container-low p-6 rounded-2xl border border-slate-100 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-pitch-deep text-surface-white flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-[26px]">lock_clock</span>
                            </div>
                            <h3 class="font-headline text-lg font-bold text-on-surface mb-2">Slot Lock 10 Menit</h3>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Saat Anda memilih jam dan menuju proses bayar, slot tersebut terkunci di tingkat database sehingga tidak bisa diserobot pengguna lain.
                            </p>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-200/60 text-xs font-bold text-pitch-deep">
                            Zero Double Booking
                        </div>
                    </div>

                    <div class="bg-surface-container-low p-6 rounded-2xl border border-slate-100 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-action-orange text-surface-white flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-[26px]">speed</span>
                            </div>
                            <h3 class="font-headline text-lg font-bold text-on-surface mb-2">Proses &lt; 2 Menit</h3>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Tanpa proses berbelit-belit. Cukup pilih jam, bayar lewat QRIS atau Virtual Account, dan tiket elektronik langsung terbit seketika.
                            </p>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-200/60 text-xs font-bold text-action-orange">
                            Efisiensi Maksimal
                        </div>
                    </div>



                    <div class="bg-surface-container-low p-6 rounded-2xl border border-slate-100 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-pitch-deep text-surface-white flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-[26px]">policy</span>
                            </div>
                            <h3 class="font-headline text-lg font-bold text-on-surface mb-2">Kebijakan Transparan</h3>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Aturan pengembalian dana (refund 100% pada H-24 jam) serta opsi reschedule cuaca buruk untuk arena outdoor yang jelas dan berkeadilan.
                            </p>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-200/60 text-xs font-bold text-pitch-deep">
                            Perlindungan Pemain
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- CTA KE BERANDA / BOOKING -->
        <section class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-20 text-center">
            <div class="bg-arena-night text-surface-white rounded-3xl p-10 sm:p-14 border border-pitch-light/20 relative overflow-hidden shadow-2xl">
                <div class="absolute -left-10 -bottom-10 w-64 h-64 rounded-full bg-action-orange/20 blur-3xl"></div>
                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="font-headline text-3xl sm:text-4xl font-bold uppercase tracking-tight mb-4">
                        Siap Bertanding Bersama Tim Anda?
                    </h2>
                    <p class="text-sm sm:text-base text-surface-container-highest mb-8 leading-relaxed">
                        Cek jadwal ketersediaan arena sekarang dan amankan slot terbaik Anda sebelum kehabisan.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <a 
                            href="{{ route('beranda') }}#jadwal-interaktif" 
                            class="bg-action-orange hover:bg-tertiary-container text-surface-white font-bold text-sm px-8 py-3.5 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5"
                        >
                            Pilih Jam Main Sekarang
                        </a>
                        <a 
                            href="{{ route('lapangan') }}" 
                            class="bg-surface-white/10 hover:bg-surface-white/20 text-surface-white font-bold text-sm px-6 py-3.5 rounded-xl transition-colors border border-surface-white/20"
                        >
                            Lihat Katalog Lapangan
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER COMPONENT -->
    <x-footer />
</body>
</html>
