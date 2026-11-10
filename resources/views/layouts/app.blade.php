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
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F8FAFC !important; 
        }
        .dark body {
            background-color: #030712 !important;
        }
        
        .premium-canvas {
            background-image: radial-gradient(#CBD5E1 1px, transparent 1px);
            background-size: 24px 24px;
            position: relative;
        }
        .dark .premium-canvas {
            background-image: radial-gradient(#1E293B 1px, transparent 1px);
        }

        .archive-feature-ui .top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 26px; flex-wrap: wrap; }
        .archive-feature-ui h1 { margin: 0; font-size: 1.625rem; font-weight: 700; letter-spacing: -.025em; }
        .archive-feature-ui .sub { margin: 3px 0 0; color: #6b7280; font-size: .875rem; }
        .archive-feature-ui .card { padding: 1.5rem; }
        .archive-feature-ui .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.125rem; }
        .archive-feature-ui .full { grid-column: 1 / -1; }
        .archive-feature-ui label { display: block; margin-bottom: .375rem; font-size: .8125rem; font-weight: 600; }
        .archive-feature-ui input:not([type="checkbox"]):not([type="radio"]),
        .archive-feature-ui select,
        .archive-feature-ui textarea { width: 100%; padding: .6875rem .875rem; border: 1px solid #d1d5db; border-radius: .625rem; background: #fff; color: #111827; font: inherit; font-size: .875rem; }
        .archive-feature-ui .filters { display: flex; gap: .625rem; flex-wrap: wrap; margin-bottom: 1.125rem; }
        .archive-feature-ui .filters input,
        .archive-feature-ui .filters select { width: auto; min-width: 8.75rem; flex: 1; }
        .archive-feature-ui .err { margin-top: .25rem; color: #dc2626; font-size: .75rem; }
        .archive-feature-ui .table-wrap { overflow-x: auto; }
        .archive-feature-ui table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .archive-feature-ui th { padding: .625rem .75rem; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: .75rem; font-weight: 600; text-align: left; text-transform: uppercase; }
        .archive-feature-ui td { padding: .875rem .75rem; border-bottom: 1px solid #e5e7eb; }
        .archive-feature-ui .badge { display: inline-block; padding: .25rem .6875rem; border-radius: 9999px; font-size: .75rem; font-weight: 600; }
        .archive-feature-ui .b-pending { background: #fef3c7; color: #b45309; }
        .archive-feature-ui .b-disetujui { background: #d1fae5; color: #047857; }
        .archive-feature-ui .b-ditolak { background: #fee2e2; color: #b91c1c; }
        .archive-feature-ui .empty { padding: 3rem 0; color: #6b7280; text-align: center; }
        .archive-feature-ui .sig { display: block; width: 100%; height: 180px; border: 2px dashed #d1d5db; border-radius: .75rem; background: #fff; cursor: crosshair; touch-action: none; }
        .archive-feature-ui .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(12.5rem, 1fr)); gap: 1rem; margin-bottom: 1.375rem; }
        .archive-feature-ui .stat { padding: 1.25rem 1.375rem; border: 1px solid #e5e7eb; border-radius: .875rem; background: #fff; }
        .archive-feature-ui .stat b { display: block; font-size: 1.875rem; line-height: 1.2; }
        .archive-feature-ui .stat span { color: #6b7280; font-size: .8125rem; }
        .archive-feature-ui .detail { display: grid; grid-template-columns: 8.75rem 1fr; gap: .625rem 1rem; margin: 0; font-size: .875rem; }
        .archive-feature-ui .detail dt { color: #6b7280; }
        .archive-feature-ui .detail dd { margin: 0; }
        .archive-feature-ui .btn-green,
        .archive-feature-ui .btn-red { display: inline-flex; align-items: center; gap: .5rem; padding: .625rem 1.125rem; border: 0; border-radius: .625rem; color: #fff; font: inherit; font-size: .875rem; font-weight: 600; cursor: pointer; }
        .archive-feature-ui .btn-green { background: #059669; }
        .archive-feature-ui .btn-red { background: #dc2626; }
        .archive-feature-ui .archive-alert { margin-bottom: 1.25rem; padding: .75rem 1rem; border: 1px solid #a7f3d0; border-radius: .75rem; background: #ecfdf5; color: #047857; font-size: .875rem; }
        .archive-feature-ui .archive-alert-error { border-color: #fecaca; background: #fef2f2; color: #b91c1c; }
        @media (max-width: 860px) {
            .archive-feature-ui .grid,
            .archive-feature-ui .detail { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="antialiased min-h-screen">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
           class="fixed lg:static inset-y-0 left-0 z-50 w-[280px] flex flex-col bg-[#0B1120] border-r border-white/5 transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] shadow-2xl shadow-black/50">

        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-[#FF5A1F]/10 to-transparent pointer-events-none"></div>

        <div class="flex items-center gap-4 px-6 h-24 border-b border-white/5 relative z-10">
            <div class="w-12 h-12 rounded-md bg-white/5 border border-white/10 flex items-center justify-center p-2 shadow-inner backdrop-blur-sm">
                <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo" class="w-full h-full object-contain rounded-md">
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-white font-black text-xl tracking-widest leading-none">SIMAS</div>
                <div class="text-[9px] text-[#FF5A1F] font-bold uppercase tracking-[0.2em] mt-1.5 truncate">
                    SMK INFORMATIKA PESAT
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto no-scrollbar px-4 py-6 space-y-1 relative z-10">

            <div class="px-2 pb-2">
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Menu Utama</span>
            </div>

            @php
                $navClass = "group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 relative overflow-hidden";
                $activeClass = "text-white bg-white/5 shadow-[inset_0_1px_0_rgba(255,255,255,0.1)]";
                $iconClass = "w-5 h-5 flex-shrink-0 transition-colors duration-300 text-slate-500 group-hover:text-[#FF5A1F]";
            @endphp

            <a href="{{ route('dashboard') }}" class="{{ $navClass }} {{ request()->routeIs('dashboard') ? $activeClass : '' }}">
                @if(request()->routeIs('dashboard'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                @endif
                <svg class="{{ $iconClass }} {{ request()->routeIs('dashboard') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="px-2 pt-6 pb-2">
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Persuratan</span>
            </div>

            <a href="{{ route('pengajuan.index') }}" class="{{ $navClass }} {{ request()->routeIs('pengajuan.*') ? $activeClass : '' }}">
                @if(request()->routeIs('pengajuan.*'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                @endif
                <svg class="{{ $iconClass }} {{ request()->routeIs('pengajuan.*') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Pengajuan Surat</span>
            </a>

            @if(auth()->user()->isGuru())
                <a href="{{ route('surat-perizinan-sakit.create') }}" class="{{ $navClass }} {{ request()->routeIs('surat-perizinan-sakit.create') ? $activeClass : '' }}">
                    @if(request()->routeIs('surat-perizinan-sakit.create'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                    @endif
                    <svg class="{{ $iconClass }} {{ request()->routeIs('surat-perizinan-sakit.create') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3h6l5 5v13H5V6a3 3 0 013-3zm4 7v7m-3.5-3.5h7"/></svg>
                    <span>Pengajuan Izin Sakit</span>
                </a>

                <a href="{{ route('surat-perizinan-sakit.index') }}" class="{{ $navClass }} {{ request()->routeIs('surat-perizinan-sakit.index') ? $activeClass : '' }}">
                    @if(request()->routeIs('surat-perizinan-sakit.index'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                    @endif
                    <svg class="{{ $iconClass }} {{ request()->routeIs('surat-perizinan-sakit.index') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Riwayat Izin</span>
                </a>
            @elseif(auth()->user()->isAdminTu())
                <a href="{{ route('surat-perizinan-sakit.index') }}" class="{{ $navClass }} {{ request()->routeIs('surat-perizinan-sakit.index') ? $activeClass : '' }}">
                    @if(request()->routeIs('surat-perizinan-sakit.index'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                    @endif
                    <svg class="{{ $iconClass }} {{ request()->routeIs('surat-perizinan-sakit.index') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3h6l5 5v13H5V6a3 3 0 013-3zm4 7v7m-3.5-3.5h7"/></svg>
                    <span>Pengajuan Sakit Masuk</span>
                </a>
            @endif

            <a href="{{ route('surat-masuk.index') }}" class="{{ $navClass }} {{ request()->routeIs('surat-masuk.*') ? $activeClass : '' }}">
                @if(request()->routeIs('surat-masuk.*'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                @endif
                <svg class="{{ $iconClass }} {{ request()->routeIs('surat-masuk.*') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Surat Masuk</span>
            </a>

            <a href="{{ route('surat-izin.index') }}" class="{{ $navClass }} {{ request()->routeIs('surat-izin.*') ? $activeClass : '' }}">
                @if(request()->routeIs('surat-izin.*'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                @endif
                <svg class="{{ $iconClass }} {{ request()->routeIs('surat-izin.*') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2zm7 0v5h5m-9 5h5m-5 4h5"/></svg>
                <span>Surat Izin</span>
            </a>

            <a href="{{ route('izin-notif.index') }}" class="{{ $navClass }} {{ request()->routeIs('izin-notif.*') ? $activeClass : '' }}">
                @if(request()->routeIs('izin-notif.*'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                @endif
                <svg class="{{ $iconClass }} {{ request()->routeIs('izin-notif.*') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m2 0v1a2 2 0 104 0v-1"/></svg>
                <span>Notifikasi Izin</span>
                <span data-izin-notif-count aria-label="Jumlah notifikasi izin belum dibaca" class="ml-auto hidden rounded-full bg-[#FF5A1F] px-2 py-0.5 text-[10px] font-bold text-white shadow-[0_0_10px_#FF5A1F]"></span>
            </a>

            @if(auth()->user()->role?->name !== 'guru')
                <a href="{{ route('approval.index') }}" class="{{ $navClass }} {{ request()->routeIs('approval.*') ? $activeClass : '' }}">
                    @if(request()->routeIs('approval.*'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                    @endif
                    <svg class="{{ $iconClass }} {{ request()->routeIs('approval.*') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Approval</span>
                </a>
            @endif

            @if(auth()->user()->isAdminTu() || auth()->user()->isPimpinan())
                <a href="{{ route('admin.surat-izin.index') }}" class="{{ $navClass }} {{ request()->routeIs('admin.surat-izin.*') ? $activeClass : '' }}">
                    @if(request()->routeIs('admin.surat-izin.*'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                    @endif
                    <svg class="{{ $iconClass }} {{ request()->routeIs('admin.surat-izin.*') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4m-1 8v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                    <span>Kelola Surat Izin</span>
                </a>
            @endif

            @if(auth()->user()->isAdminTu())
                <a href="{{ route('admin.akun.index') }}" class="{{ $navClass }} {{ request()->routeIs('admin.akun.*') ? $activeClass : '' }}">
                    @if(request()->routeIs('admin.akun.*'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#FF5A1F] rounded-r-full shadow-[0_0_12px_#FF5A1F]"></div>
                    @endif
                    <svg class="{{ $iconClass }} {{ request()->routeIs('admin.akun.*') ? 'text-[#FF5A1F]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.9M9 20H2v-2a4 4 0 014-4h3m8-3a4 4 0 10-8 0 4 4 0 008 0zm5-2a3 3 0 11-6 0"/></svg>
                    <span>Kelola Akun</span>
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-white/5 relative z-10">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/[0.03] border border-white/[0.05] hover:bg-white/[0.06] transition-colors group cursor-default">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#FF5A1F] to-[#FF8A5C] flex items-center justify-center text-white font-black text-sm shadow-[0_0_15px_rgba(255,90,31,0.4)] flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-xs font-bold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[9px] text-[#FF5A1F] uppercase tracking-widest font-bold truncate mt-0.5">
                        {{ auth()->user()->role?->display_name }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-all" title="Log Out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden relative">

        <header class="absolute top-0 inset-x-0 z-40 p-4 lg:p-6 pointer-events-none">
            <div class="max-w-[1600px] mx-auto glass bg-white/80 dark:bg-[#0B1120]/80 backdrop-blur-xl border border-white/50 dark:border-white/5 shadow-[0_8px_30px_rgba(0,0,0,0.04)] rounded-2xl h-16 flex items-center justify-between px-5 pointer-events-auto transition-all duration-300">
                
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="flex-1 min-w-0 px-4">
                    @isset($header)
                        {{ $header }}
                    @else
                        <h1 class="text-lg font-extrabold text-gray-900 dark:text-white truncate tracking-tight">
                            {{ $title ?? 'Dashboard' }}
                        </h1>
                        @if($subtitle)
                            <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ $subtitle }}</p>
                        @endif
                    @endisset
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-[#151D3F] border border-gray-200 dark:border-white/10 flex items-center justify-center shadow-sm hover:shadow-md transition cursor-pointer">
                        <x-notification-bell />
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto premium-canvas">
            <div class="{{ request()->routeIs('surat-izin.*', 'admin.surat-izin.*', 'admin.akun.*', 'izin-notif.*') ? 'archive-feature-ui ' : '' }}p-4 lg:p-6 pt-24 lg:pt-28 max-w-[1600px] mx-auto min-h-full">
                
                @if(request()->routeIs('surat-izin.*', 'admin.surat-izin.*', 'admin.akun.*', 'izin-notif.*') && session('success'))
                    <div class="archive-alert">{{ session('success') }}</div>
                @endif
                @if(request()->routeIs('surat-izin.*', 'admin.surat-izin.*', 'admin.akun.*', 'izin-notif.*') && session('error'))
                    <div class="archive-alert archive-alert-error">{{ session('error') }}</div>
                @endif
                
                {{ $slot }}
                
            </div>
        </main>
    </div>

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-[#0B1120]/80 backdrop-blur-sm z-40 lg:hidden" x-transition.opacity></div>
</div>

@if((session('success') || session('info')) && !request()->routeIs('surat-izin.*', 'admin.surat-izin.*', 'admin.akun.*', 'izin-notif.*'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-8 right-8 z-[9999] max-w-sm w-full bg-white/90 dark:bg-[#0B1120]/90 backdrop-blur-xl border border-emerald-500/30 rounded-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.2)] overflow-hidden cursor-pointer" 
         @click="show = false">
        
        <div class="h-1 w-full bg-emerald-500"></div>
        
        <div class="p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center flex-shrink-0 border border-emerald-500/20 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Sistem Informasi</p>
                    <button class="text-gray-400 hover:text-gray-600 transition">✕</button>
                </div>
                <p class="text-sm font-bold text-gray-900 dark:text-white mt-1 leading-relaxed">
                    {{ session('success') ?? session('info') }}
                </p>
            </div>
        </div>
    </div>
@endif

@stack('scripts')
<script data-izin-notif-url="{{ route('izin-notif.jumlah') }}">
    fetch(document.currentScript.dataset.izinNotifUrl, { headers: { Accept: 'application/json' } })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Notifikasi izin gagal dimuat (${response.status}).`);
            }
            return response.json();
        })
        .then((data) => {
            if (!Number.isInteger(data.count) || data.count < 0) {
                throw new Error('Jumlah notifikasi izin tidak valid.');
            }
            const badge = document.querySelector('[data-izin-notif-count]');
            if (badge && data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : String(data.count);
                badge.classList.remove('hidden');
            }
        })
        .catch((error) => console.error('Unable to fetch leave notifications.', error));
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.Echo !== 'undefined') {
        window.Echo.private(`role.admin_tu`)
            .listen('.pengajuan.status.changed', (payload) => {
                showPremiumToast(payload);
            });
    }
});

function showPremiumToast(payload) {
    const { data, aksi, url } = payload;
    
    const config = {
        'submitted': { color: 'blue', icon: 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8', label: 'Pengajuan Baru' },
        'verified': { color: 'gold', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', label: 'Diverifikasi' },
        'approved': { color: 'emerald', icon: 'M5 13l4 4L19 7', label: 'Disetujui' },
        'rejected': { color: 'red', icon: 'M6 18L18 6M6 6l12 12', label: 'Ditolak' },
        'revisi': { color: 'orange', icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5', label: 'Perlu Revisi' }
    }[aksi] || { color: 'gray', icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', label: 'Notifikasi' };

    const toast = document.createElement('div');
    toast.className = `fixed bottom-8 right-8 z-[9999] max-w-sm w-full bg-white/90 dark:bg-[#0B1120]/90 backdrop-blur-xl border border-${config.color}-500/30 rounded-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.2)] overflow-hidden transform transition-all duration-500 translate-y-10 opacity-0 cursor-pointer`;
    
    toast.innerHTML = `
        <div class="h-1 w-full bg-${config.color}-500"></div>
        <div class="p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-${config.color}-500/10 text-${config.color}-500 flex items-center justify-center flex-shrink-0 shadow-sm border border-${config.color}-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="${config.icon}"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-black text-${config.color}-500 uppercase tracking-widest">${config.label}</p>
                    <button class="text-gray-400 hover:text-gray-600 transition" onclick="this.closest('.fixed').remove()">✕</button>
                </div>
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate mt-1">${data.kegiatan}</p>
                <p class="text-xs font-medium text-gray-500 mt-0.5">Oleh: ${data.pengaju} · ${data.nomor}</p>
            </div>
        </div>
    `;

    toast.onclick = () => { window.location.href = url; };

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-y-10', 'opacity-0');
    }, 10);

    setTimeout(() => {
        toast.classList.add('translate-y-10', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 6000);
}
</script>
</body>
</html>
