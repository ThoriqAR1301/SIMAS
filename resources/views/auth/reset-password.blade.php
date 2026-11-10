<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#0B1120] shadow-xl mb-6 ring-4 ring-[#F4B22E]/20">
            <svg class="w-8 h-8 text-[#F4B22E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Atur Ulang Kata Sandi</h1>
        <p class="mt-2 text-sm font-medium text-gray-500 max-w-sm mx-auto leading-relaxed">
            Silakan buat kata sandi baru yang kuat untuk mengamankan akun Anda
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="group">
            <label for="email" class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#F4B22E] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="nama@smkpesatpro.sch.id" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-transparent focus:border-[#F4B22E] focus:bg-white focus:ring-4 focus:ring-[#F4B22E]/20 rounded-xl text-sm font-semibold text-gray-900 transition-all outline-none">
            </div>
            @error('email') <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <label for="password" class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Password Baru</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#F4B22E] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-transparent focus:border-[#F4B22E] focus:bg-white focus:ring-4 focus:ring-[#F4B22E]/20 rounded-xl text-sm font-semibold text-gray-900 transition-all outline-none">
            </div>
            @error('password') <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <label for="password_confirmation" class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Konfirmasi Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#F4B22E] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru" class="w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-transparent focus:border-[#F4B22E] focus:bg-white focus:ring-4 focus:ring-[#F4B22E]/20 rounded-xl text-sm font-semibold text-gray-900 transition-all outline-none">
            </div>
        </div>

        <button type="submit" class="relative w-full py-4 mt-2 bg-[#0B1120] text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-[0_10px_20px_rgba(11,17,32,0.2)] hover:scale-[1.02] hover:shadow-[0_15px_30px_rgba(11,17,32,0.3)] transition-all duration-300 overflow-hidden group">
            <span class="relative z-10 flex items-center justify-center gap-2">
                Simpan Kata Sandi Baru
                <svg class="w-4 h-4 text-[#F4B22E] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </span>
            <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
        </button>
    </form>
</x-guest-layout>
