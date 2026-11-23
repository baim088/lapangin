<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tentang Lapangin - Profil platform sistem booking lapangan olahraga online modern. Menghilangkan double-booking dengan sistem kunci waktu 10 menit.">
    <title>Tentang Kami - {{ config('app.name', 'Lapangin') }}</title>

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
    x-data="{ mobileMenuOpen: false }"
    class="bg-canvas-bg font-body text-slate-800 antialiased selection:bg-[#f8b710] selection:text-slate-950"
>
    <!-- TOP NAVBAR COMPONENT -->
    <x-navbar active="tentang" />

    <main class="w-full pt-20 bg-canvas-bg min-h-[calc(100vh-80px)]">
        
        <!-- HEADER BANNER -->
        <section class="w-full bg-[#0f2444] text-white py-20 px-4 sm:px-8 border-b border-white/10 relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-[#f8b710]/15 blur-3xl pointer-events-none"></div>
            <div class="w-full max-w-7xl mx-auto text-center relative z-10">
                <span class="text-xs font-bold text-[#f8b710] uppercase tracking-widest block mb-1">PROFIL & NILAI PERUSAHAAN</span>
                <h1 class="font-teko text-5xl sm:text-6xl lg:text-7xl font-bold uppercase tracking-tight text-white mb-2 leading-none">
                    MENGENAL LAPANGIN
                </h1>
                <div class="flex items-center justify-center gap-1.5 mt-2 mb-4">
                    <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                    <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                </div>
                <p class="font-body text-sm sm:text-base text-slate-200/90 max-w-2xl mx-auto leading-relaxed">
                    Menghubungkan pecinta olahraga dengan fasilitas gelanggang terstandarisasi melalui teknologi reservasi real-time tanpa risiko jadwal bentrok.
                </p>
            </div>
        </section>

        <!-- CERITA & MISI -->
        <section class="w-full max-w-7xl mx-auto px-4 sm:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 flex flex-col gap-6">
                    <div>
                        <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">CERITA DI BALIK LAYAR</span>
                        <h2 class="font-teko text-4xl sm:text-5xl font-bold text-slate-900 tracking-tight uppercase leading-none">
                            REVOLUSI PEMESANAN GELANGGANG KONVENSIONAL
                        </h2>
                        <div class="w-12 h-1 bg-[#f8b710] rounded-full my-3"></div>
                    </div>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        Sebelum <strong>LapangIn</strong> hadir, memesan gelanggang olahraga adalah proses yang melelahkan: harus mengirim chat WhatsApp berkali-kali ke pengelola, menunggu balasan yang lambat, menebak-nebak jam berapa yang masih kosong, hingga risiko paling fatal: <em>jadwal tumpang tindih (double booking)</em> saat tiba di lokasi.
                    </p>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        Bagi pengelola venue, pembukuan kertas manual dan transfer bukti rekening juga kerap menimbulkan kekacauan rekapitulasi omzet dan jadwal maintenance lapangan.
                    </p>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                        <strong>LapangIn</strong> hadir sebagai solusi terpadu satu atap: sebuah sistem kalender real-time yang memadukan algoritma <em>Pessimistic Time-Lock 10 Menit</em>, konfirmasi pembayaran otomatis, dan kontrol operasional kasir walk-in di lokasi.
                    </p>
                </div>

                <div class="lg:col-span-6 relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-200 bg-[#0f2444] group">
                        <img 
                            src="https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=800&q=80" 
                            alt="Hall Arena Lapangin"
                            class="w-full h-[420px] object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                    </div>
                </div>

            </div>
        </section>

        <!-- 3 PILAR KEUNGGULAN (Cards) -->
        <section class="w-full bg-surface-white py-20 border-y border-slate-200">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-8">
                
                <div class="text-center max-w-xl mx-auto mb-16">
                    <span class="text-xs font-bold text-[#f37920] uppercase tracking-widest block mb-1">STANDAR KUALITAS</span>
                    <h2 class="font-teko text-4xl sm:text-5xl font-bold text-slate-900 tracking-tight uppercase leading-none">
                        3 PILAR KEUNGGULAN UTAMA
                    </h2>
                    <div class="flex items-center justify-center gap-1.5 mt-2 mb-3">
                        <span class="w-14 h-1 bg-[#f8b710] rounded-full inline-block"></span>
                        <span class="w-1.5 h-1.5 bg-[#f8b710] rounded-xs inline-block"></span>
                    </div>
                    <p class="text-sm text-slate-600">
                        Dibangun dengan arsitektur modern berstandar enterprise untuk kenyamanan maksimal Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <div class="bg-[#f8f9fa] p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 rounded-2xl bg-[#0f2444] text-[#f8b710] flex items-center justify-center mb-5 shadow-md">
                                <span class="material-symbols-outlined text-[28px]">lock_clock</span>
                            </div>
                            <h3 class="font-teko text-2xl sm:text-3xl font-bold uppercase text-slate-900 mb-2 leading-none">SLOT LOCK 10 MENIT</h3>
                            <div class="w-8 h-1 bg-[#f8b710] rounded-full my-2"></div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Saat Anda memilih jam dan menuju proses bayar, slot tersebut terkunci di tingkat database sehingga tidak bisa diserobot pengguna lain.
                            </p>
                        </div>
                        <div class="pt-5 mt-5 border-t border-slate-200 text-xs font-bold text-[#f37920] uppercase tracking-wider">
                            Zero Double Booking
                        </div>
                    </div>

                    <div class="bg-[#f8f9fa] p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 rounded-2xl bg-[#f8b710] text-slate-950 flex items-center justify-center mb-5 shadow-md">
                                <span class="material-symbols-outlined text-[28px]">speed</span>
                            </div>
                            <h3 class="font-teko text-2xl sm:text-3xl font-bold uppercase text-slate-900 mb-2 leading-none">PROSES KILAT &lt; 2 MENIT</h3>
                            <div class="w-8 h-1 bg-[#f8b710] rounded-full my-2"></div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Tanpa proses berbelit-belit. Cukup pilih jam, bayar lewat transfer, dan tiket elektronik langsung terbit seketika di akun Anda.
                            </p>
                        </div>
                        <div class="pt-5 mt-5 border-t border-slate-200 text-xs font-bold text-slate-900 uppercase tracking-wider">
                            Efisiensi Maksimal
                        </div>
                    </div>

                    <div class="bg-[#f8f9fa] p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 rounded-2xl bg-[#0f2444] text-[#f8b710] flex items-center justify-center mb-5 shadow-md">
                                <span class="material-symbols-outlined text-[28px]">policy</span>
                            </div>
                            <h3 class="font-teko text-2xl sm:text-3xl font-bold uppercase text-slate-900 mb-2 leading-none">KEBIJAKAN TRANSPARAN</h3>
                            <div class="w-8 h-1 bg-[#f8b710] rounded-full my-2"></div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Aturan pengembalian dana (refund 90% pada H-24 jam) serta opsi reschedule cuaca buruk untuk arena outdoor yang jelas dan berkeadilan.
                            </p>
                        </div>
                        <div class="pt-5 mt-5 border-t border-slate-200 text-xs font-bold text-[#f37920] uppercase tracking-wider">
                            Perlindungan Pemain
                        </div>
                    </div>

                </div>

            </div>
        </section>

    </main>

    <!-- FOOTER COMPONENT -->
    <x-footer />
</body>
</html>
