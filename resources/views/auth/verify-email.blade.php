<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="relative inline-flex items-center justify-center w-20 h-20 mb-6">
            <div class="absolute inset-0 bg-[#F4B22E]/20 rounded-full animate-ping"></div>
            
            <div class="relative w-16 h-16 bg-white border border-gray-100 shadow-xl rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-[#F4B22E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Verifikasi Email Anda</h1>
        <p class="mt-3 text-sm font-medium text-gray-500 max-w-sm mx-auto leading-relaxed">
            Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 shadow-sm animate-in fade-in slide-in-from-top-4">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-emerald-700 leading-relaxed">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan
            </p>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100 mt-8">
        
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="relative w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#0B1120] text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-[0_10px_20px_rgba(11,17,32,0.2)] hover:scale-[1.02] hover:shadow-[0_15px_30px_rgba(11,17,32,0.3)] transition-all duration-300 overflow-hidden group">
                <span class="relative z-10 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#F4B22E] group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Kirim Ulang Email
                </span>
                <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto text-sm font-bold text-gray-500 hover:text-red-500 transition-colors text-center px-4 py-2">
                Keluar Akun
            </button>
        </form>
    </div>
</x-guest-layout>
