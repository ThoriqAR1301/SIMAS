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
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Selamat Datang</h1>
        <p class="text-sm font-medium text-gray-500">Masukkan kredensial Anda untuk mengakses sistem.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="group">
            <label for="email" class="text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 block transition-colors group-focus-within:text-[#FF5A1F]">Email Address</label>
            
            <div class="custom-input-group flex items-center w-full bg-gray-50 border border-gray-200 rounded-2xl transition-all duration-300 focus-within:bg-white focus-within:border-[#FF5A1F] focus-within:ring-4 focus-within:ring-[#FF5A1F]/10 hover:border-gray-300">
                <div class="pl-4 pr-3 text-gray-400 group-focus-within:text-[#FF5A1F] transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@simas.test" class="w-full text-gray-900 font-semibold text-sm placeholder-gray-400 bg-transparent">
            </div>
            @error('email') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="text-[11px] font-black uppercase tracking-widest text-gray-500 block transition-colors group-focus-within:text-[#FF5A1F]">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#FF5A1F] hover:text-[#e64a13] transition">Lupa Sandi?</a>
                @endif
            </div>
            
            <div class="custom-input-group flex items-center w-full bg-gray-50 border border-gray-200 rounded-2xl transition-all duration-300 focus-within:bg-white focus-within:border-[#FF5A1F] focus-within:ring-4 focus-within:ring-[#FF5A1F]/10 hover:border-gray-300">
                <div class="pl-4 pr-3 text-gray-400 group-focus-within:text-[#FF5A1F] transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full text-gray-900 font-semibold text-sm placeholder-gray-400 bg-transparent">
            </div>
            @error('password') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center">
            <label class="inline-flex items-center gap-3 cursor-pointer group" style="text-transform: none; font-size: 0.875rem; letter-spacing: normal; margin-bottom: 0;">
                <input type="checkbox" name="remember" class="w-5 h-5 rounded border-gray-300 text-[#FF5A1F] focus:ring-[#FF5A1F] transition cursor-pointer">
                <span class="text-gray-500 group-hover:text-gray-900 transition font-semibold"> Ingat perangkat ini</span>
            </label>
        </div>

        <button type="submit" class="relative w-full py-4 bg-gradient-to-r from-[#FF5A1F] to-[#FF7A45] text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-[0_15px_30px_rgba(255,90,31,0.3)] hover:shadow-[0_20px_40px_rgba(255,90,31,0.4)] hover:-translate-y-1 transition-all duration-300 overflow-hidden group mt-2">
            <span class="relative z-10 flex items-center justify-center gap-2">
                Masuk ke Sistem
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
            <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
        </button>
    </form>

    @if(config('app.env') === 'local')
        <div class="mt-10">
            <div class="relative flex items-center justify-center mb-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative px-4 bg-white text-[10px] font-black text-gray-400 uppercase tracking-widest">Akses Cepat Demo</div>
            </div>
            
            <div class="grid grid-cols-3 gap-3">
                <button type="button" onclick="quickLogin('aesa@simas.test')" class="flex flex-col items-center justify-center gap-2 p-3 bg-white border border-gray-200 rounded-2xl hover:border-[#FF5A1F] hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">👩‍💼</div>
                    <span class="text-[11px] font-bold text-gray-700 group-hover:text-[#FF5A1F]">Admin TU</span>
                </button>
                <button type="button" onclick="quickLogin('kepsek@simas.test')" class="flex flex-col items-center justify-center gap-2 p-3 bg-white border border-gray-200 rounded-2xl hover:border-blue-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">👨‍💼</div>
                    <span class="text-[11px] font-bold text-gray-700 group-hover:text-blue-500">Pimpinan</span>
                </button>
                <button type="button" onclick="quickLogin('ahmad@simas.test')" class="flex flex-col items-center justify-center gap-2 p-3 bg-white border border-gray-200 rounded-2xl hover:border-green-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center group-hover:scale-110 transition-transform">👨‍🏫</div>
                    <span class="text-[11px] font-bold text-gray-700 group-hover:text-green-500">Guru</span>
                </button>
            </div>
        </div>
        <script>
        function quickLogin(email) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = 'password';
            document.querySelector('form').submit();
        }
        </script>
    @endif

    <p class="text-center text-sm font-medium text-gray-500 mt-10">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="font-bold text-[#FF5A1F] hover:text-[#e64a13] transition underline decoration-2 underline-offset-4 decoration-[#FF5A1F]/30 hover:decoration-[#FF5A1F]">Daftar Di Sini</a>
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
