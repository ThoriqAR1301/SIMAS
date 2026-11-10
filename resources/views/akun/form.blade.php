<x-app-layout title="{{ $akun ? 'Edit Akun' : 'Tambah Akun' }}">
    
    <style>
        .bg-dots {
            background-color: #F8FAFC;
            background-image: radial-gradient(#CBD5E1 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .premium-input-wrapper { 
            position: relative; 
            display: flex; 
            align-items: center; 
            background: #FFFFFF;
            border: 2px solid #E2E8F0; 
            border-radius: 1rem; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        
        .premium-input-wrapper:focus-within {
            border-color: #FF5A1F;
            box-shadow: 0 0 0 4px rgba(255, 90, 31, 0.15);
            background: #FFFFFF;
        }
        
        .premium-input-wrapper input, 
        .premium-input-wrapper select {
            width: 100% !important;
            background: transparent !important;
            border: none !important;
            padding: 1rem 1rem 1rem 0.5rem !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            color: #0F172A !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .premium-input-wrapper select {
            appearance: none !important;
            -webkit-appearance: none !important;
            cursor: pointer;
        }

        .premium-icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 100%;
            flex-shrink: 0;
            color: #94A3B8;
            transition: color 0.3s ease;
        }
        
        .premium-input-wrapper:focus-within .premium-icon-box {
            color: #FF5A1F;
        }
    </style>

    <div class="max-w-9xl mx-auto pb-12 pt-2 px-4 sm:px-6 lg:px-8 bg-dots rounded-3xl min-h-[80vh]">
        
        <div class="mb-8 text-center animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#0B1120] shadow-[0_10px_20px_rgba(11,17,32,0.2)] mb-4">
                <svg class="w-6 h-6 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tighter mb-2">
                {{ $akun ? 'Perbarui Akun' : 'Registrasi Akun Baru' }}
            </h1>
            <p class="text-sm font-medium text-slate-500">
                Pastikan data yang dimasukkan valid dan sesuai dengan data kepegawaian.
            </p>
        </div>

        <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] p-8 md:p-10 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-white/60 animate-in fade-in zoom-in-95 duration-500 delay-100 relative overflow-hidden" x-data="{ showPassword: false }">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#FF5A1F]/5 rounded-full blur-[60px] pointer-events-none"></div>

            <form method="POST" action="{{ $akun ? route('admin.akun.update', $akun) : route('admin.akun.store') }}" class="relative z-10 space-y-6">
                @csrf
                @if($akun) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <div class="premium-input-wrapper">
                            <div class="premium-icon-box border-r border-slate-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="name" value="{{ old('name', $akun->name ?? '') }}" required placeholder="Nama beserta gelar">
                        </div>
                        @error('name') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Email Address <span class="text-red-500">*</span></label>
                        <div class="premium-input-wrapper">
                            <div class="premium-icon-box border-r border-slate-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $akun->email ?? '') }}" placeholder="nama@gmail.com" required>
                        </div>
                        @error('email') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">NIP / NUPTK</label>
                        <div class="premium-input-wrapper">
                            <div class="premium-icon-box border-r border-slate-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                            </div>
                            <input type="text" name="nip" value="{{ old('nip', $akun->nip ?? '') }}" maxlength="50" placeholder="Nomor Induk Pegawai">
                        </div>
                        @error('nip') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Jabatan</label>
                        <div class="premium-input-wrapper">
                            <div class="premium-icon-box border-r border-slate-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $akun->jabatan ?? '') }}" maxlength="100" placeholder="Contoh: Guru Matematika">
                        </div>
                        @error('jabatan') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Hak Akses Sistem <span class="text-red-500">*</span></label>
                        <div class="premium-input-wrapper">
                            <div class="premium-icon-box border-r border-slate-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <select name="role_id" required>
                                <option value="" disabled selected>Pilih Role Akses</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}" @selected(old('role_id', $roleId) == $r->id)>{{ $r->display_name ?? $r->name }}</option>
                                @endforeach
                            </select>
                            <div class="pr-4 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('role_id') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">
                            {{ $akun ? 'Password Baru (Opsional)' : 'Password Awal' }}
                            @if(!$akun) <span class="text-red-500">*</span> @endif
                        </label>
                        <div class="premium-input-wrapper">
                            <div class="premium-icon-box border-r border-slate-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="pw" autocomplete="new-password" {{ $akun ? '' : 'required' }} minlength="8" placeholder="Minimal 8 karakter" class="pr-32">
                            
                            <div class="absolute right-2 flex items-center gap-1.5">
                                <button type="button" @click="showPassword = !showPassword" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-[#FF5A1F] hover:bg-orange-50 rounded-lg transition-colors" title="Lihat Sandi">
                                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                                <button type="button" class="px-3 h-8 rounded-lg bg-[#0B1120] text-[#CCFF00] text-[10px] font-bold hover:bg-slate-800 transition-colors shadow-sm" onclick="document.getElementById('pw').value=Array.from(crypto.getRandomValues(new Uint8Array(10))).map(b=>'abcdefghjkmnpqrstuvwxyz23456789'[b%31]).join('')">Acak</button>
                            </div>
                        </div>
                        @error('password') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('admin.akun.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors w-full sm:w-auto text-center py-2">
                        Batal & Kembali
                    </a>
                    <button type="submit" class="relative w-full sm:w-auto px-8 py-3.5 bg-[#FF5A1F] text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-[0_10px_20px_rgba(255,90,31,0.3)] hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 group overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            {{ $akun ? 'Simpan Perubahan' : 'Daftarkan Akun' }}
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                        <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes shine {
            100% { left: 125%; }
        }
        .animate-shine {
            animation: shine 0.8s;
        }
    </style>
</x-app-layout>