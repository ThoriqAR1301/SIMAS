@props([
    'title' => null,
    'subtitle' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'SIMAS') }} — SIMAS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-ink-50 dark:bg-brand-950 min-h-screen">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed lg:static inset-y-0 left-0 z-40 w-72 flex flex-col bg-gradient-premium border-r border-white/5 transition-transform duration-300 ease-out shadow-2xl shadow-brand-950/30">

        <div class="flex items-center gap-3 px-6 py-6 border-b border-white/5">
            <div class="relative flex-shrink-0">
                <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="SMK Pesat IT XPro" class="w-12 h-12 object-contain rounded-xl bg-white/5 p-1.5 ring-1 ring-white/10">
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-white font-bold text-lg tracking-tight leading-none">SIMAS</div>
                <div class="text-[10px] text-gold-400 font-semibold uppercase tracking-[0.15em] mt-1 truncate">
                    SMK PESAT IT XPRO
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-4 space-y-1">

            <div class="px-3 pt-2 pb-1">
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-ink-500">Menu Utama</span>
            </div>

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <div class="px-3 pt-4 pb-1">
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-ink-500">Persuratan</span>
            </div>

            <a href="{{ route('pengajuan.index') }}" class="nav-link {{ request()->routeIs('pengajuan.*') ? 'nav-link-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Pengajuan Surat</span>
            </a>

            @if(auth()->user()->role?->name !== 'guru')
                <a href="{{ route('approval.index') }}" class="nav-link {{ request()->routeIs('approval.*') ? 'nav-link-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Approval</span>

                </a>
            @endif
        </nav>

        <div class="p-3 border-t border-white/5">
            <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-white/[0.04]">
                <div class="w-10 h-10 rounded-full bg-gradient-gold flex items-center justify-center text-brand-900 font-bold text-sm ring-2 ring-white/10 flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-gold-400 uppercase tracking-wider font-medium truncate">
                        {{ auth()->user()->role?->display_name }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-ink-400 hover:text-red-400 transition p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">

        <header class="glass sticky top-0 z-30 border-b border-ink-100 dark:border-brand-800/60">
            <div class="flex items-center justify-between gap-4 px-4 lg:px-8 h-20 lg:h-24">

                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden btn-ghost btn-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="flex-1 min-w-0">
                    @isset($header)
                        {{ $header }}
                    @else
                        <h1 class="text-lg font-bold text-brand-900 dark:text-ink-50 truncate">
                            {{ $title ?? 'Dashboard' }}
                        </h1>
                        @if($subtitle)
                            <p class="text-xs text-ink-500 dark:text-ink-400 truncate">{{ $subtitle }}</p>
                        @endif
                    @endisset
                </div>

                <div class="flex items-center gap-2">
                    <x-notification-bell />
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto bg-gradient-mesh">
            <div class="px-4 lg:px-8 py-6 lg:py-8 max-w-[1600px] mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-brand-950/60 backdrop-blur-sm z-30 lg:hidden" x-transition.opacity></div>
</div>

@stack('scripts')
</body>
</html>
