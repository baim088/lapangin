@props(['active' => 'beranda'])

<header class="fixed top-0 left-0 right-0 z-50 bg-[#0f2444]/95 backdrop-blur-xl shadow-lg border-b border-white/10 text-white">
    <div class="h-20 w-full px-4 sm:px-8 flex items-center justify-between gap-4 max-w-7xl mx-auto">
        
        <!-- Left: Logo & Brand -->
        <div class="flex items-center gap-4 sm:gap-6 shrink-0">
            <a href="{{ route('beranda') }}" class="flex items-center">
                <img 
                    src="{{ asset('images/logo-white.png') }}" 
                    alt="Lapangin" 
                    class="h-9 sm:h-10 w-auto object-contain hover:opacity-90 transition-opacity"
                >
            </a>
            <div class="hidden xl:flex items-center gap-2 pl-4 border-l border-white/15 text-xs text-white/70">
                <span class="w-2 h-2 rounded-full bg-[#f8b710] animate-pulse"></span>
                <span>Buka Setiap Hari: <strong>08.00 - 23.00 WIB</strong></span>
            </div>
        </div>

        <!-- Center: Menu Navigasi Sebaris -->
        <nav class="hidden md:flex items-center gap-1 lg:gap-2">
            <a 
                href="{{ route('beranda') }}" 
                class="text-xs uppercase tracking-wider px-4 py-2 rounded-full transition-all whitespace-nowrap {{ $active === 'beranda' ? 'bg-white/15 text-[#f8b710] font-bold shadow-xs' : 'text-white/80 hover:text-[#f8b710] hover:bg-white/5 font-semibold' }}"
            >
                Beranda
            </a>
            <a 
                href="{{ route('lapangan') }}" 
                class="text-xs uppercase tracking-wider px-4 py-2 rounded-full transition-all whitespace-nowrap {{ $active === 'lapangan' ? 'bg-white/15 text-[#f8b710] font-bold shadow-xs' : 'text-white/80 hover:text-[#f8b710] hover:bg-white/5 font-semibold' }}"
            >
                Lapangan
            </a>
            <a 
                href="{{ route('tentang') }}" 
                class="text-xs uppercase tracking-wider px-4 py-2 rounded-full transition-all whitespace-nowrap {{ $active === 'tentang' ? 'bg-white/15 text-[#f8b710] font-bold shadow-xs' : 'text-white/80 hover:text-[#f8b710] hover:bg-white/5 font-semibold' }}"
            >
                Tentang
            </a>
            <a 
                href="{{ route('kontak') }}" 
                class="text-xs uppercase tracking-wider px-4 py-2 rounded-full transition-all whitespace-nowrap {{ $active === 'kontak' ? 'bg-white/15 text-[#f8b710] font-bold shadow-xs' : 'text-white/80 hover:text-[#f8b710] hover:bg-white/5 font-semibold' }}"
            >
                Kontak
            </a>
        </nav>

        <!-- Right: Auth + Action CTA -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">

            @guest
                <!-- Auth Links (desktop) -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('login') }}" class="text-xs uppercase tracking-wider px-3 py-2 rounded-lg text-white/80 hover:text-[#f8b710] font-semibold transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="text-xs uppercase tracking-wider px-3.5 py-2 rounded-full text-white bg-white/10 hover:bg-white/20 font-semibold transition-colors border border-white/15">Daftar</a>
                </div>
            @endguest
            @auth
                @php
                    $dashboardRoute = auth()->user()->isSuperAdmin()
                        ? route('admin.dashboard')
                        : (auth()->user()->isOwner()
                            ? route('owner.dashboard')
                            : (auth()->user()->isOperator() ? route('operator.dashboard') : route('user.dashboard')));
                @endphp
                <div class="hidden md:flex items-center gap-2">
                    <a href="{{ $dashboardRoute }}" class="text-xs uppercase tracking-wider px-3 py-2 rounded-lg text-[#f8b710] font-bold hover:bg-white/5 transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">account_circle</span>
                        {{ auth()->user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs uppercase tracking-wider px-3 py-2 rounded-lg text-rose-300 hover:text-rose-200 hover:bg-rose-500/10 font-semibold transition-colors">
                            Keluar
                        </button>
                    </form>
                </div>
            @endauth

            <!-- Primary Booking CTA (Single Line) -->
            <a 
                href="{{ route('lapangan') }}" 
                class="inline-flex items-center justify-center bg-gradient-to-r from-[#f8b710] to-[#f37920] hover:from-[#f37920] hover:to-[#f8b710] text-slate-950 font-bold text-xs uppercase tracking-wider px-5 py-2.5 rounded-full shadow-lg shadow-amber-500/20 transition-all transform hover:-translate-y-0.5 whitespace-nowrap"
            >
                Booking Cepat
            </a>

            <!-- Mobile Menu Button -->
            <button 
                type="button" 
                @click="mobileMenuOpen = !mobileMenuOpen" 
                class="md:hidden p-2 rounded-lg text-white hover:bg-white/10 transition-colors"
                aria-label="Toggle Menu"
            >
                <span class="material-symbols-outlined text-[26px]" x-text="mobileMenuOpen ? 'close' : 'menu'"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown Navigation -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition
        @click.away="mobileMenuOpen = false" 
        class="md:hidden border-t border-white/10 bg-[#0b1a30] px-6 py-5 flex flex-col gap-2.5 shadow-2xl"
        style="display: none;"
    >
        <div class="flex items-center gap-2 pb-3 mb-1 border-b border-white/10 text-xs text-[#f8b710] font-semibold">
            <span class="w-2 h-2 rounded-full bg-[#f8b710] animate-pulse"></span>
            <span>Buka Setiap Hari: 08:00 - 23:00 WIB</span>
        </div>
        <a 
            href="{{ route('beranda') }}" 
            class="py-2 px-3 rounded-lg text-sm font-semibold {{ $active === 'beranda' ? 'bg-white/15 text-[#f8b710] font-bold' : 'text-white/80 hover:bg-white/5 hover:text-[#f8b710]' }}"
        >
            Beranda
        </a>
        <a 
            href="{{ route('lapangan') }}" 
            class="py-2 px-3 rounded-lg text-sm font-semibold {{ $active === 'lapangan' ? 'bg-white/15 text-[#f8b710] font-bold' : 'text-white/80 hover:bg-white/5 hover:text-[#f8b710]' }}"
        >
            Lapangan
        </a>
        <a 
            href="{{ route('tentang') }}" 
            class="py-2 px-3 rounded-lg text-sm font-semibold {{ $active === 'tentang' ? 'bg-white/15 text-[#f8b710] font-bold' : 'text-white/80 hover:bg-white/5 hover:text-[#f8b710]' }}"
        >
            Tentang
        </a>
        <a 
            href="{{ route('kontak') }}" 
            class="py-2 px-3 rounded-lg text-sm font-semibold {{ $active === 'kontak' ? 'bg-white/15 text-[#f8b710] font-bold' : 'text-white/80 hover:bg-white/5 hover:text-[#f8b710]' }}"
        >
            Kontak
        </a>

        @guest
            <div class="pt-3 mt-2 border-t border-white/10 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="py-2.5 px-3 rounded-lg text-sm font-semibold text-white/90 hover:bg-white/5">Masuk</a>
                <a href="{{ route('register') }}" class="py-2.5 px-3 rounded-xl text-center text-sm font-bold text-slate-950 bg-[#f8b710] hover:bg-amber-300">Daftar Akun</a>
            </div>
        @endguest
        @auth
            @php
                $mobileDashboardRoute = auth()->user()->isSuperAdmin()
                    ? route('admin.dashboard')
                    : (auth()->user()->isOwner()
                        ? route('owner.dashboard')
                        : (auth()->user()->isOperator() ? route('operator.dashboard') : route('user.dashboard')));
            @endphp
            <div class="pt-3 mt-2 border-t border-white/10 flex flex-col gap-2">
                <a href="{{ $mobileDashboardRoute }}" class="py-2 px-3 rounded-lg text-sm font-semibold text-[#f8b710] hover:bg-white/5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">account_circle</span>
                    {{ auth()->user()->name }} (Dashboard)
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 px-3 rounded-lg text-sm font-semibold text-rose-300 hover:bg-rose-500/10">
                        Keluar
                    </button>
                </form>
            </div>
        @endauth
    </div>
</header>
