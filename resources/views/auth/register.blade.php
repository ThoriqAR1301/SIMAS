<x-guest-layout>
    
    <style>
        .custom-input-group input {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 1rem 1rem 1rem 0 !important; 
            border-radius: 0 !important;
            outline: none !important;
        }
        .custom-input-group input:focus {
            background-color: transparent !important;
            box-shadow: none !important;
        }
    </style>

    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Buat Akun Baru</h1>
        <p class="text-sm font-medium text-gray-500">Bergabunglah dengan ekosistem SIMAS.</p>
    </div>

    @error('role')
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300">
            {{ $message }}
        </div>
    @enderror

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="group">
            <label for="name" class="text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 block transition-colors group-focus-within:text-[#FF5A1F]">Nama Lengkap</label>
            <div class="custom-input-group flex items-center w-full bg-gray-50 border border-gray-200 rounded-2xl transition-all duration-300 focus-within:bg-white focus-within:border-[#FF5A1F] focus-within:ring-4 focus-within:ring-[#FF5A1F]/10 hover:border-gray-300">
                <div class="pl-4 pr-3 text-gray-400 group-focus-within:text-[#FF5A1F] transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" class="w-full text-gray-900 font-semibold text-sm placeholder-gray-400 bg-transparent">
            </div>
            @error('name') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <label for="email" class="text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 block transition-colors group-focus-within:text-[#FF5A1F]">Email Address</label>
            <div class="custom-input-group flex items-center w-full bg-gray-50 border border-gray-200 rounded-2xl transition-all duration-300 focus-within:bg-white focus-within:border-[#FF5A1F] focus-within:ring-4 focus-within:ring-[#FF5A1F]/10 hover:border-gray-300">
                <div class="pl-4 pr-3 text-gray-400 group-focus-within:text-[#FF5A1F] transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@smkpesatpro.sch.id" class="w-full text-gray-900 font-semibold text-sm placeholder-gray-400 bg-transparent">
            </div>
            @error('email') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <label for="password" class="text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 block transition-colors group-focus-within:text-[#FF5A1F]">Password</label>
            <div class="custom-input-group flex items-center w-full bg-gray-50 border border-gray-200 rounded-2xl transition-all duration-300 focus-within:bg-white focus-within:border-[#FF5A1F] focus-within:ring-4 focus-within:ring-[#FF5A1F]/10 hover:border-gray-300">
                <div class="pl-4 pr-3 text-gray-400 group-focus-within:text-[#FF5A1F] transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full text-gray-900 font-semibold text-sm placeholder-gray-400 bg-transparent">
            </div>
            @error('password') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <label for="password_confirmation" class="text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 block transition-colors group-focus-within:text-[#FF5A1F]">Konfirmasi Password</label>
            <div class="custom-input-group flex items-center w-full bg-gray-50 border border-gray-200 rounded-2xl transition-all duration-300 focus-within:bg-white focus-within:border-[#FF5A1F] focus-within:ring-4 focus-within:ring-[#FF5A1F]/10 hover:border-gray-300">
                <div class="pl-4 pr-3 text-gray-400 group-focus-within:text-[#FF5A1F] transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" class="w-full text-gray-900 font-semibold text-sm placeholder-gray-400 bg-transparent">
            </div>
        </div>

        <button type="submit" class="relative w-full py-4 mt-6 bg-gradient-to-r from-[#FF5A1F] to-[#FF7A45] text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-[0_15px_30px_rgba(255,90,31,0.3)] hover:shadow-[0_20px_40px_rgba(255,90,31,0.4)] hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <span class="relative z-10 flex items-center justify-center gap-2">
                Daftar Sekarang
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
            <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
        </button>
    </form>

    <p class="text-center text-sm font-medium text-gray-500 mt-8">
        Sudah punya akun? 
        <a href="{{ route('login') }}" class="font-bold text-[#FF5A1F] hover:text-[#e64a13] transition underline decoration-2 underline-offset-4 decoration-[#FF5A1F]/30 hover:decoration-[#FF5A1F]">Masuk Di Sini</a>
    </p>

    <style>
        @keyframes shine {
            100% { left: 125%; }
        }
        .animate-shine {
            animation: shine 0.8s;
        }
    </style>

</x-guest-layout>
