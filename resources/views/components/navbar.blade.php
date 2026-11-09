@props(['active' => 'beranda'])

<header class="fixed top-0 left-0 right-0 z-50 bg-surface-white/95 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] border-b border-surface-container-high/60">
    <div class="h-20 w-full px-4 sm:px-8 flex items-center justify-between gap-4 max-w-7xl mx-auto">
        
        <!-- Left: Logo & Operational Badge -->
        <div class="flex items-center gap-4 sm:gap-6 shrink-0">
            <a href="{{ route('beranda') }}" class="flex items-center">
                <img 
                    src="{{ asset('images/logo.png') }}" 
                    alt="Lapangin" 
                    class="h-9 sm:h-10 w-auto object-contain hover:opacity-95 transition-opacity"
                >
            </a>
            
        </div>

        <!-- Center: Menu Navigasi Sebaris (Beranda, Lapangan, Tentang, Kontak) -->
        <nav class="hidden md:flex items-center gap-1 lg:gap-2">
            <a 
                href="{{ route('beranda') }}" 
                class="text-sm px-4 py-2 rounded-full transition-colors whitespace-nowrap {{ $active === 'beranda' ? 'bg-pitch-surface text-pitch-deep font-bold' : 'text-on-surface-variant hover:text-pitch-deep hover:bg-surface-container-low font-medium' }}"
            >
                Beranda
            </a>
            <a 
                href="{{ route('lapangan') }}" 
                class="text-sm px-4 py-2 rounded-full transition-colors whitespace-nowrap {{ $active === 'lapangan' ? 'bg-pitch-surface text-pitch-deep font-bold' : 'text-on-surface-variant hover:text-pitch-deep hover:bg-surface-container-low font-medium' }}"
            >
                Lapangan
            </a>
            <a 
                href="{{ route('tentang') }}" 
                class="text-sm px-4 py-2 rounded-full transition-colors whitespace-nowrap {{ $active === 'tentang' ? 'bg-pitch-surface text-pitch-deep font-bold' : 'text-on-surface-variant hover:text-pitch-deep hover:bg-surface-container-low font-medium' }}"
            >
                Tentang
            </a>
            <a 
                href="{{ route('kontak') }}" 
                class="text-sm px-4 py-2 rounded-full transition-colors whitespace-nowrap {{ $active === 'kontak' ? 'bg-pitch-surface text-pitch-deep font-bold' : 'text-on-surface-variant hover:text-pitch-deep hover:bg-surface-container-low font-medium' }}"
            >
                Kontak
            </a>
        </nav>

        <!-- Right: Auth + Action CTA -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">

            @guest
                <!-- Auth Links (desktop) -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('login') }}" class="text-sm px-3 py-2 rounded-lg text-on-surface-variant hover:text-pitch-deep font-medium transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm px-3 py-2 rounded-lg text-pitch-deep font-semibold hover:bg-pitch-surface transition-colors">Daftar</a>
                </div>
            @endguest
            @auth
                @php
                    $dashboardRoute = auth()->user()->isSuperAdmin()
                        ? route('admin.dashboard')
                        : (auth()->user()->isOperator() ? route('operator.dashboard') : route('user.dashboard'));
                @endphp
                <div class="hidden md:flex items-center gap-2">
                    <a href="{{ $dashboardRoute }}" class="text-sm px-3 py-2 rounded-lg text-on-surface-variant hover:text-pitch-deep font-medium transition-colors">
                        {{ auth()->user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm px-3 py-2 rounded-lg text-rose-600 hover:bg-rose-50 font-medium transition-colors">
                            Keluar
                        </button>
                    </form>
                </div>
            @endauth

            <!-- Primary Booking CTA (Single Line) -->
            <a 
                href="{{ route('lapangan') }}" 
                class="inline-flex items-center justify-center bg-action-orange hover:bg-tertiary-container text-surface-white font-bold text-sm px-4 py-2.5 rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5 whitespace-nowrap"
            >
                Booking Cepat
            </a>

            <!-- Mobile Menu Button -->
            <button 
                type="button" 
                @click="mobileMenuOpen = !mobileMenuOpen" 
                class="md:hidden p-2 rounded-lg text-on-surface hover:bg-surface-container-low"
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
        class="md:hidden border-t border-slate-200 bg-surface-white px-6 py-4 flex flex-col gap-2 shadow-lg"
        style="display: none;"
    >
        <div class="flex items-center gap-2 pb-2 mb-1 border-b border-slate-100 text-xs text-pitch-deep font-semibold">
            <span class="w-2 h-2 rounded-full bg-pitch-emerald animate-pulse"></span>
            <span>Buka 08:00 - 23:00 WIB</span>
        </div>
        <a 
            href="{{ route('beranda') }}" 
            class="py-2 px-3 rounded-lg text-sm font-semibold {{ $active === 'beranda' ? 'bg-pitch-surface text-pitch-deep font-bold' : 'hover:bg-pitch-surface hover:text-pitch-deep' }}"
        >
            Beranda
        </a>
        <a 
            href="{{ route('lapangan') }}" 
            class="py-2 px-3 rounded-lg text-sm font-semibold {{ $active === 'lapangan' ? 'bg-pitch-surface text-pitch-deep font-bold' : 'hover:bg-pitch-surface hover:text-pitch-deep' }}"
        >
            Lapangan
        </a>
        <a 
            href="{{ route('tentang') }}" 
            class="py-2 px-3 rounded-lg text-sm font-semibold {{ $active === 'tentang' ? 'bg-pitch-surface text-pitch-deep font-bold' : 'hover:bg-pitch-surface hover:text-pitch-deep' }}"
        >
            Tentang
        </a>
        <a 
            href="{{ route('kontak') }}" 
            class="py-2 px-3 rounded-lg text-sm font-semibold {{ $active === 'kontak' ? 'bg-pitch-surface text-pitch-deep font-bold' : 'hover:bg-pitch-surface hover:text-pitch-deep' }}"
        >
            Kontak
        </a>

        @guest
            <div class="pt-2 mt-2 border-t border-slate-100 flex flex-col gap-1">
                <a href="{{ route('login') }}" class="py-2 px-3 rounded-lg text-sm font-semibold text-on-surface-variant hover:bg-pitch-surface hover:text-pitch-deep">Masuk</a>
                <a href="{{ route('register') }}" class="py-2 px-3 rounded-lg text-sm font-semibold text-pitch-deep hover:bg-pitch-surface">Daftar</a>
            </div>
        @endguest
        @auth
            @php
                $mobileDashboardRoute = auth()->user()->isSuperAdmin()
                    ? route('admin.dashboard')
                    : (auth()->user()->isOperator() ? route('operator.dashboard') : route('user.dashboard'));
            @endphp
            <div class="pt-2 mt-2 border-t border-slate-100 flex flex-col gap-1">
                <a href="{{ $mobileDashboardRoute }}" class="py-2 px-3 rounded-lg text-sm font-semibold text-pitch-deep hover:bg-pitch-surface">
                    {{ auth()->user()->name }} (Dashboard)
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 px-3 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50">
                        Keluar
                    </button>
                </form>
            </div>
        @endauth
    </div>
</header>
