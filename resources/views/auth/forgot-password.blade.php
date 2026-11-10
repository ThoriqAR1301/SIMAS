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

    <div class="mb-8 text-center">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Lupa Sandi?</h1>
        <p class="text-sm font-medium text-gray-500">Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div class="group">
            <label for="email" class="text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 block transition-colors group-focus-within:text-[#FF5A1F]">Email Address</label>
            
            <div class="custom-input-group flex items-center w-full bg-gray-50 border border-gray-200 rounded-2xl transition-all duration-300 focus-within:bg-white focus-within:border-[#FF5A1F] focus-within:ring-4 focus-within:ring-[#FF5A1F]/10 hover:border-gray-300">
                <div class="pl-4 pr-3 text-gray-400 group-focus-within:text-[#FF5A1F] transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@smkpesatpro.sch.id" class="w-full text-gray-900 font-semibold text-sm placeholder-gray-400 bg-transparent">
            </div>
            @error('email') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="relative w-full py-4 mt-2 bg-gradient-to-r from-[#FF5A1F] to-[#FF7A45] text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-[0_15px_30px_rgba(255,90,31,0.3)] hover:shadow-[0_20px_40px_rgba(255,90,31,0.4)] hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <span class="relative z-10 flex items-center justify-center gap-2">
                Kirim Tautan Reset
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
            <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
        </button>
    </form>

    <div class="mt-8 text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#FF5A1F] transition group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali Ke Halaman Masuk
        </a>
    </div>

    <style>
        @keyframes shine {
            100% { left: 125%; }
        }
        .animate-shine {
            animation: shine 0.8s;
        }
    </style>

</x-guest-layout>
