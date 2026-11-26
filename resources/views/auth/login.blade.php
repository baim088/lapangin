<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8f9fa] font-body text-slate-800 antialiased min-h-screen selection:bg-[#f8b710] selection:text-slate-950">

    <div class="min-h-screen grid lg:grid-cols-2">

        {{-- Left Panel: Branding (Aligned with Landing Page Royal Navy & Gold) --}}
        <div class="hidden lg:flex flex-col bg-[#0f2444] p-12 relative overflow-hidden">
            {{-- Ambient Stadium Glows --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-[#f8b710]/15 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-10 right-0 w-96 h-96 rounded-full bg-[#f37920]/15 blur-3xl pointer-events-none"></div>

            {{-- Logo --}}
            <a href="{{ route('beranda') }}" class="flex items-center relative z-10 mb-12">
                <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-10 w-auto opacity-95">
            </a>

            {{-- Center Content --}}
            <div class="relative z-10 flex flex-col gap-6">
                <div class="inline-flex items-center gap-2 bg-[#f8b710]/15 border border-[#f8b710]/30 px-3.5 py-1.5 rounded-full w-fit">
                    <span class="w-2 h-2 rounded-full bg-[#f8b710] animate-pulse"></span>
                    <span class="text-xs font-bold text-[#f8b710] uppercase tracking-wider">Real-Time Booking System</span>
                </div>
                <h1 class="font-teko text-5xl xl:text-6xl text-white font-bold uppercase leading-[0.95] tracking-tight">
                    KELOLA BOOKING<br>
                    <span class="text-[#f8b710]">LAPANGAN OLAHRAGA</span><br>
                    DENGAN MUDAH.
                </h1>
                <div class="w-14 h-1 bg-[#f8b710] rounded-full -mt-2"></div>
                <p class="text-sm text-slate-300 max-w-sm leading-relaxed">
                    Akses 50+ lapangan olahraga terverifikasi di 5 wilayah DKI Jakarta. Kunci slot 10 menit anti double-booking dengan konfirmasi instan.
                </p>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4 pt-4">
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="font-teko text-3xl text-[#f8b710] font-bold leading-none">50+</div>
                        <div class="text-xs text-slate-300 mt-1">Arena Jakarta</div>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="font-teko text-3xl text-[#f37920] font-bold leading-none">10'</div>
                        <div class="text-xs text-slate-300 mt-1">Slot Lock</div>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="font-teko text-3xl text-white font-bold leading-none">0</div>
                        <div class="text-xs text-slate-300 mt-1">Double Booking</div>
                    </div>
                </div>
            </div>

            {{-- Footer quote --}}
            <div class="relative z-10 text-xs text-slate-400 mt-auto pt-8">
                "Ajak Tim, Pilih Jam, Langsung Main!"
            </div>
        </div>

        {{-- Right Panel: Login Form --}}
        <div class="flex flex-col justify-center items-center px-6 py-12 sm:px-10" x-data="{ showPass: false }">

            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8">
                <a href="{{ route('beranda') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Lapangin" class="h-9 w-auto">
                </a>
            </div>

            <div class="w-full max-w-sm">
                {{-- Reservation notice banner --}}
                <div class="mb-6 p-3.5 bg-amber-50 border border-[#f8b710]/40 rounded-xl flex items-center gap-3 text-xs text-[#0f2444]">
                    <span class="material-symbols-outlined text-[#f37920] text-[20px] shrink-0">lock</span>
                    <span>Masuk ke akun Anda untuk melanjutkan proses booking dan mengunci slot lapangan.</span>
                </div>

                <div class="mb-6">
                    <h2 class="font-teko text-4xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">Masuk ke Akun</h2>
                    <p class="text-sm text-slate-600 mt-1.5">Belum punya akun?
                        <a href="{{ route('register') }}" class="text-[#f37920] font-bold hover:underline">Daftar sekarang</a>
                    </p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                    @csrf

                    {{-- Email --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="email" class="text-sm font-bold text-slate-800">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#f8b710] focus:border-[#f8b710] transition-all"
                            >
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-sm font-bold text-slate-800">Password</label>
                            <a href="#" class="text-xs text-[#f37920] font-bold hover:underline">Lupa password?</a>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                            <input
                                :type="showPass ? 'text' : 'password'"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                class="w-full pl-10 pr-11 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#f8b710] focus:border-[#f8b710] transition-all"
                            >
                            <button
                                type="button"
                                @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-[20px]" x-text="showPass ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#f37920] focus:ring-[#f8b710]">
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-sm uppercase tracking-wider py-3.5 rounded-xl shadow-md transition-all mt-2 cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[20px]">login</span>
                        Masuk
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs text-slate-500">
                        <span class="bg-[#f8f9fa] px-3">atau</span>
                    </div>
                </div>

                {{-- Social / Quick Login --}}
                <button type="button" class="w-full flex items-center justify-center gap-3 border border-slate-200 bg-white hover:bg-slate-50 text-slate-800 text-sm font-semibold py-3 px-4 rounded-xl transition-colors mb-6 cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Lanjutkan dengan Google
                </button>
            </div>
        </div>
    </div>

</body>
</html>
