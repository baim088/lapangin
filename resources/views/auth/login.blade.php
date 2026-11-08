<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Lapangin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas-bg font-body text-on-surface antialiased min-h-screen">

    <div class="min-h-screen grid lg:grid-cols-2">

        {{-- Left Panel: Branding --}}
        <div class="hidden lg:flex flex-col justify-between bg-arena-night p-12 relative overflow-hidden">
            {{-- Ambient glows --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-pitch-emerald/20 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-10 right-0 w-96 h-96 rounded-full bg-action-orange/10 blur-3xl pointer-events-none"></div>

            {{-- Logo --}}
            <a href="{{ route('beranda') }}" class="flex items-center relative z-10">
                <img src="{{ asset('images/logo.png') }}" alt="Lapangin" class="h-10 w-auto brightness-0 invert opacity-95">
            </a>

            {{-- Center Content --}}
            <div class="relative z-10 flex flex-col gap-6">
                <div class="inline-flex items-center gap-2 bg-pitch-light/20 px-3.5 py-1.5 rounded-full w-fit">
                    <span class="w-2 h-2 rounded-full bg-pitch-light animate-pulse"></span>
                    <span class="text-xs font-semibold text-pitch-light uppercase tracking-wider">Real-Time Booking System</span>
                </div>
                <h1 class="font-headline text-4xl text-surface-white font-bold leading-tight">
                    Kelola booking<br>
                    <span class="text-pitch-light">lapangan olahraga</span><br>
                    dengan mudah.
                </h1>
                <p class="text-sm text-surface-container-highest max-w-sm leading-relaxed">
                    Sistem anti double-booking dengan slot lock 10 menit. Futsal, Badminton, Mini Soccer, dan Padel tersedia 08:00 – 23:00 WIB.
                </p>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4 pt-4">
                    <div class="bg-surface-white/5 rounded-xl p-4 border border-surface-white/10">
                        <div class="font-headline text-2xl text-pitch-light font-bold">4+</div>
                        <div class="text-xs text-surface-container-highest mt-1">Kategori Arena</div>
                    </div>
                    <div class="bg-surface-white/5 rounded-xl p-4 border border-surface-white/10">
                        <div class="font-headline text-2xl text-action-amber font-bold">10'</div>
                        <div class="text-xs text-surface-container-highest mt-1">Slot Lock</div>
                    </div>
                    <div class="bg-surface-white/5 rounded-xl p-4 border border-surface-white/10">
                        <div class="font-headline text-2xl text-surface-white font-bold">0</div>
                        <div class="text-xs text-surface-container-highest mt-1">Double Booking</div>
                    </div>
                </div>
            </div>

            {{-- Footer quote --}}
            <div class="relative z-10 text-xs text-surface-container-high">
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
                <div class="mb-6 p-3.5 bg-pitch-surface border border-pitch-light/30 rounded-xl flex items-center gap-3 text-xs text-pitch-deep">
                    <span class="material-symbols-outlined text-pitch-emerald text-[20px] shrink-0">lock</span>
                    <span>Masuk ke akun Anda untuk melanjutkan proses booking dan mengunci slot lapangan.</span>
                </div>

                <div class="mb-6">
                    <h2 class="font-headline text-2xl font-bold text-on-surface">Masuk ke Akun</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Belum punya akun?
                        <a href="{{ route('register') }}" class="text-pitch-deep font-semibold hover:underline">Daftar gratis</a>
                    </p>
                </div>

                {{-- Social / Quick Login --}}
                <button type="button" class="w-full flex items-center justify-center gap-3 border border-surface-container-high bg-surface-white hover:bg-surface-container-low text-on-surface text-sm font-semibold py-3 px-4 rounded-lg transition-colors mb-6">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Lanjutkan dengan Google
                </button>

                <div class="relative mb-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-surface-container-high"></div>
                    </div>
                    <div class="relative flex justify-center text-xs text-on-surface-variant">
                        <span class="bg-canvas-bg px-3">atau masuk dengan email</span>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="#" class="flex flex-col gap-4">
                    @csrf

                    {{-- Email --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="email" class="text-sm font-semibold text-on-surface">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">mail</span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-surface-container-high bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 focus:ring-pitch-emerald focus:border-pitch-emerald transition-all"
                            >
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-sm font-semibold text-on-surface">Password</label>
                            <a href="#" class="text-xs text-pitch-deep font-semibold hover:underline">Lupa password?</a>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock</span>
                            <input
                                :type="showPass ? 'text' : 'password'"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                class="w-full pl-10 pr-11 py-3 rounded-lg border border-surface-container-high bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 focus:ring-pitch-emerald focus:border-pitch-emerald transition-all"
                            >
                            <button
                                type="button"
                                @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface"
                            >
                                <span class="material-symbols-outlined text-[20px]" x-text="showPass ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Remember --}}
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-surface-container-high text-pitch-emerald focus:ring-pitch-emerald">
                        <span class="text-sm text-on-surface-variant">Ingat saya di perangkat ini</span>
                    </label>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-pitch-deep hover:bg-pitch-emerald text-surface-white font-bold text-sm py-3.5 rounded-lg shadow-sm transition-all mt-2"
                    >
                        <span class="material-symbols-outlined text-[20px]">login</span>
                        Masuk ke Akun
                    </button>
                </form>

                {{-- Register link --}}
                <p class="text-center text-xs text-on-surface-variant mt-6">
                    Dengan masuk, Anda menyetujui
                    <a href="{{ route('tentang') }}" class="text-pitch-deep hover:underline font-medium">Syarat & Ketentuan</a>
                    serta
                    <a href="{{ route('tentang') }}" class="text-pitch-deep hover:underline font-medium">Kebijakan Privasi</a> Lapangin.
                </p>
            </div>
        </div>
    </div>

</body>
</html>

