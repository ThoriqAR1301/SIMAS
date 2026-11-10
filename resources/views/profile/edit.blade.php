<x-app-layout>
    <x-slot name="title">Pengaturan Profil</x-slot>
    <x-slot name="subtitle">Kelola informasi akun dan keamanan Anda</x-slot>

    <style>
        
        input[type="text"], 
        input[type="email"], 
        input[type="password"] {
            width: 100% !important;
            background-color: #F8FAFC !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 0.75rem !important;
            padding: 0.85rem 1rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            color: #0F172A !important;
            transition: all 0.3s ease !important;
            box-shadow: none !important;
        }
        
        input:focus {
            background-color: #ffffff !important;
            border-color: #FF5A1F !important;
            outline: none !important;
            box-shadow: 0 0 0 4px rgba(255, 90, 31, 0.1) !important;
        }

        label {
            font-size: 0.7rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #475569 !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        button[type="submit"]:not(.btn-danger) {
            background-color: #0B1120 !important;
            color: white !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.75rem !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(11, 17, 32, 0.2) !important;
            border: none !important;
        }
        
        button[type="submit"]:not(.btn-danger):hover {
            background-color: #FF5A1F !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(255, 90, 31, 0.3) !important;
        }

        button.btn-danger {
            background-color: #FEF2F2 !important;
            color: #DC2626 !important;
            border: 1px solid #FECACA !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
            padding: 0.75rem 1.5rem !important;
            transition: all 0.3s ease !important;
        }
        
        button.btn-danger:hover {
            background-color: #DC2626 !important;
            color: white !important;
        }
        
        .sm\:rounded-lg, .dark\:bg-gray-800 {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-8 pb-12">

        <div class="relative overflow-hidden rounded-[2rem] bg-[#0B1120] p-8 lg:p-10 shadow-2xl shadow-black/20 group">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#FF5A1F] rounded-full blur-[100px] opacity-20 group-hover:opacity-30 transition-opacity duration-700 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#FF5A1F] to-[#FF8A5C] flex items-center justify-center text-white font-black text-4xl shadow-[0_0_30px_rgba(255,90,31,0.4)] ring-4 ring-white/10 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight">{{ Auth::user()->name }}</h1>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-3">
                        <span class="px-3 py-1 bg-white/10 text-white rounded-lg text-xs font-bold border border-white/10 backdrop-blur-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ Auth::user()->email }}
                        </span>
                        <span class="px-3 py-1 bg-[#FF5A1F]/20 text-[#FF5A1F] rounded-lg text-xs font-bold border border-[#FF5A1F]/30 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ Auth::user()->role?->display_name ?? 'User' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-bl-full pointer-events-none"></div>
                
                <div class="flex items-center gap-3 mb-6 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-gray-900">Informasi Profil</h2>
                        <p class="text-xs font-medium text-gray-500">Perbarui nama dan alamat email Anda.</p>
                    </div>
                </div>
                
                <div class="relative z-10">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-bl-full pointer-events-none"></div>
                
                <div class="flex items-center gap-3 mb-6 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-gray-900">Ubah Kata Sandi</h2>
                        <p class="text-xs font-medium text-gray-500">Pastikan akun Anda menggunakan sandi yang aman.</p>
                    </div>
                </div>
                
                <div class="relative z-10">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="bg-[#FEF2F2] rounded-[2rem] border border-red-100 shadow-[0_8px_30px_rgba(220,38,38,0.05)] p-8 relative overflow-hidden">
            <div class="flex items-center gap-3 mb-4 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white text-red-600 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-black text-red-900">Hapus Akun</h2>
                    <p class="text-xs font-medium text-red-600/80">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                </div>
            </div>
            
            <div class="relative z-10 pl-13">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>
