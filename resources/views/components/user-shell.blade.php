@props([
    'active' => 'cari-lapangan',
    'title' => 'Dashboard Penyewa',
    'subtitle' => 'Kelola booking & reservasi lapangan Anda',
])

@php
    $authUser = auth()->user();
@endphp

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Mobile Backdrop --}}
    <div 
        x-show="sidebarOpen" 
        x-transition.opacity
        @click="sidebarOpen = false" 
        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        style="display: none;"
    ></div>

    {{-- ============ SIDEBAR CUSTOMER ============ --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f2444] flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:z-auto shrink-0"
    >
        {{-- Logo Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo-white.png') }}" alt="Lapangin" class="h-8 w-auto opacity-95">
            </a>
            <span class="text-[11px] font-bold text-[#f8b710] uppercase tracking-wider bg-[#f8b710]/15 border border-[#f8b710]/30 px-2 py-0.5 rounded-md">Member</span>
        </div>

        {{-- Main Navigation Links --}}
        <nav class="flex-1 px-4 py-4 overflow-y-auto">
            <div class="flex flex-col gap-1">
                <a
                    href="{{ route('user.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all {{ $active === 'dashboard' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-sm' : 'font-medium text-slate-300 hover:text-white hover:bg-white/5' }}"
                >
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    Dashboard &amp; Booking
                </a>

                <a
                    href="{{ route('user.cari-lapangan') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all {{ $active === 'cari-lapangan' ? 'bg-[#f8b710] text-slate-950 font-bold shadow-sm' : 'font-medium text-slate-300 hover:text-white hover:bg-white/5' }}"
                >
                    <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                    Cari Lapangan
                </a>
            </div>

            {{-- Divider --}}
            <div class="mt-6 pt-4 border-t border-white/10 flex flex-col gap-1">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-rose-400 hover:bg-rose-500/10 transition-all text-left cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        Keluar
                    </button>
                </form>
            </div>
        </nav>

        {{-- Bottom User Info --}}
        @if($authUser)
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                <div class="w-9 h-9 rounded-full bg-[#f8b710]/20 border border-[#f8b710]/40 flex items-center justify-center shrink-0">
                    <span class="font-bold text-[#f8b710] text-sm">{{ strtoupper(substr($authUser->name, 0, 2)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ $authUser->name }}</p>
                    <p class="text-[10px] text-slate-300 truncate">{{ $authUser->email }}</p>
                </div>
            </div>
        </div>
        @endif
    </aside>

    {{-- ============ MAIN CONTENT AREA ============ --}}
    <div class="flex-1 min-w-0 flex flex-col bg-[#f8f9fa] min-h-screen">

        {{-- Topbar Header --}}
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Toggle Sidebar">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <div>
                    <h1 class="font-teko text-2xl sm:text-3xl font-bold uppercase tracking-wide text-[#0f2444] leading-none">{{ $title }}</h1>
                    <p class="text-xs text-slate-500 hidden sm:block">{{ $subtitle }}</p>
                </div>
            </div>
            <a href="{{ route('user.dashboard') }}" class="text-xs font-bold text-[#0f2444] hover:text-[#f37920] flex items-center gap-1 transition-colors shrink-0">
                <span class="material-symbols-outlined text-[16px]">dashboard</span>
                <span class="hidden sm:inline">Booking Saya</span>
            </a>
        </header>

        {{-- Content --}}
        <div class="flex-1">
            {{ $slot }}
        </div>
    </div>
</div>
