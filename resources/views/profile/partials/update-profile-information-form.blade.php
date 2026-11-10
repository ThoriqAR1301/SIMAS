<section class="space-y-6">
    
    <div class="mb-6">
        <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#F4B22E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm font-medium text-gray-500">
            Perbarui informasi akun dan alamat email Anda
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="group">
            <label for="name" class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2 transition-colors group-focus-within:text-[#F4B22E]">Nama Lengkap</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#F4B22E] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="Nama Lengkap Anda" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-transparent focus:border-[#F4B22E] focus:bg-white focus:ring-4 focus:ring-[#F4B22E]/20 rounded-xl text-sm font-semibold text-gray-900 transition-all outline-none">
            </div>
            @error('name') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="group">
            <label for="email" class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2 transition-colors group-focus-within:text-[#F4B22E]">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#F4B22E] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="nama@smkpesatpro.sch.id" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-transparent focus:border-[#F4B22E] focus:bg-white focus:ring-4 focus:ring-[#F4B22E]/20 rounded-xl text-sm font-semibold text-gray-900 transition-all outline-none">
            </div>
            @error('email') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-amber-800">Email Anda belum diverifikasi</p>
                            <p class="text-xs font-medium text-amber-700 mt-1">Silakan verifikasi untuk mengamankan akun Anda</p>
                        </div>
                    </div>

                    <button form="send-verification" class="flex-shrink-0 px-4 py-2 bg-white text-amber-700 text-xs font-bold rounded-lg border border-amber-300 hover:bg-amber-100 hover:border-amber-400 transition-colors shadow-sm">
                        Kirim Ulang Tautan
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-3 text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tautan verifikasi baru telah dikirim ke email Anda
                    </p>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="relative inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#0B1120] text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-[0_10px_20px_rgba(11,17,32,0.2)] hover:scale-[1.02] hover:shadow-[0_15px_30px_rgba(11,17,32,0.3)] transition-all duration-300 overflow-hidden group">
                <span class="relative z-10 flex items-center gap-2">
                    Simpan Perubahan
                    <svg class="w-4 h-4 text-[#F4B22E] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-emerald-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Profil berhasil diperbarui
                </p>
            @endif
        </div>
    </form>
</section>
