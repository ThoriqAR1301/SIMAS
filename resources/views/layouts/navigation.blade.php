<nav x-data="{ open: false }" class="sticky top-4 z-50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pointer-events-none">
    
    <div class="bg-white/80 dark:bg-[#0B1120]/80 backdrop-blur-xl border border-gray-200/60 dark:border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.06)] rounded-2xl pointer-events-auto">
        
        <div class="px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center">
                
                <div class="flex items-center gap-8">
                    <div class="shrink-0 flex items-center group">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 transition-transform group-hover:scale-105">
                            <x-application-logo class="block h-8 w-auto fill-current text-[#0B1120] dark:text-white" />
                            <span class="font-black tracking-widest text-gray-900 dark:text-white hidden sm:block">SIMAS</span>
                        </a>
                    </div>

                    <div class="hidden space-x-2 sm:flex">
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300
                                  {{ request()->routeIs('dashboard') 
                                      ? 'bg-[#0B1120] text-white shadow-md' 
                                      : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            Dashboard
                        </a>
                        
                        {{-- <a href="#" class="px-4 py-2 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-all">Menu Lain</a> --}}
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    
                    <div class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 hover:bg-gray-100 dark:bg-white/5 dark:hover:bg-white/10 transition-colors border border-gray-200/50 dark:border-white/5 cursor-pointer">
                        <x-notification-bell />
                    </div>

                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-3 pl-2 pr-4 py-1.5 bg-gray-50 hover:bg-white border border-gray-200/60 hover:border-[#FF5A1F]/30 rounded-full transition-all duration-300 focus:outline-none group shadow-sm">
                                    <div class="w-7 h-7 rounded-full bg-[#FF5A1F] text-white flex items-center justify-center font-black text-xs shadow-sm group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    
                                    <div class="text-sm font-bold text-gray-700 group-hover:text-[#FF5A1F] transition-colors hidden md:block">
                                        {{ Auth::user()->name }}
                                    </div>

                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#FF5A1F] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <div class="p-2">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-[#FF5A1F] rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Pengaturan Profil
                                    </a>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors mt-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar Sistem
                                        </button>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="flex items-center sm:hidden">
                        <button @click="open = ! open" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200/60 transition-colors focus:outline-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="open" x-transition.opacity.duration.200ms x-cloak class="sm:hidden absolute top-20 inset-x-4 bg-white dark:bg-[#0B1120] rounded-2xl shadow-xl border border-gray-100 dark:border-white/10 overflow-hidden">
            <div class="p-3 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="block px-4 py-3 rounded-xl text-sm font-bold transition-all
                          {{ request()->routeIs('dashboard') ? 'bg-[#0B1120] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                    Dashboard
                </a>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-white/10 bg-gray-50/50 dark:bg-white/5">
                <div class="flex items-center gap-3 mb-4 px-1">
                    <div class="w-10 h-10 rounded-full bg-[#FF5A1F] text-white flex items-center justify-center font-black shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs font-medium text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 rounded-lg text-sm font-bold text-gray-700 hover:bg-white transition-colors">
                        Pengaturan Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                            Keluar Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
