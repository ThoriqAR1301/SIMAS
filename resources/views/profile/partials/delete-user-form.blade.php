<section class="space-y-6">
    
    <div class="relative overflow-hidden rounded-2xl bg-red-50 border border-red-100 p-6 lg:p-8">
        <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 rounded-bl-full pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="max-w-xl">
                <h2 class="text-lg font-black text-red-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Hapus Akun
                </h2>
                <p class="mt-2 text-sm font-medium text-red-700/80 leading-relaxed">
                    Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun, silakan unduh data atau informasi apa pun yang ingin Anda simpan
                </p>
            </div>

            <x-danger-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="relative flex-shrink-0 inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white font-bold text-sm rounded-xl shadow-[0_10px_20px_rgba(220,38,38,0.2)] hover:bg-red-700 hover:scale-105 transition-all duration-300"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Akun
            </x-danger-button>
        </div>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 lg:p-8 bg-white rounded-3xl">
            @csrf
            @method('delete')

            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-gray-900 tracking-tight">
                        Apakah Anda yakin ingin menghapus akun?
                    </h2>
                    <p class="mt-1 text-sm font-medium text-gray-500 leading-relaxed">
                        Tindakan ini tidak dapat dibatalkan. Semua data pengajuan, profil, dan arsip Anda akan dihapus permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi
                    </p>
                </div>
            </div>

            <div class="group mt-6">
                <label for="password" class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2 transition-colors group-focus-within:text-red-500">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input 
                        id="password"
                        name="password"
                        type="password"
                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-transparent focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/20 rounded-xl text-sm font-semibold text-gray-900 transition-all outline-none"
                        placeholder="Masukkan kata sandi Anda"
                        required
                    />
                </div>
                @if($errors->userDeletion->get('password'))
                    <p class="mt-2 text-xs font-bold text-red-500">{{ $errors->userDeletion->get('password')[0] }}</p>
                @endif
            </div>

            <div class="mt-8 flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 border-t border-gray-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 bg-gray-50 text-gray-600 font-bold text-sm rounded-xl hover:bg-gray-100 transition-colors w-full sm:w-auto">
                    Batal
                </button>

                <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold text-sm rounded-xl shadow-[0_10px_20px_rgba(220,38,38,0.3)] hover:bg-red-700 hover:scale-105 transition-all duration-300 w-full sm:w-auto flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
