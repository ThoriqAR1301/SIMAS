<x-app-layout title="Akun Guru & Pegawai">
    <div class="max-w-7xl mx-auto space-y-8 pb-12">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#FF5A1F]/10 border border-[#FF5A1F]/20 shadow-sm mb-4">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF5A1F] opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FF5A1F]"></span>
                    </span>
                    <span class="text-[10px] font-black text-[#FF5A1F] uppercase tracking-widest">Pusat Kendali Pengguna</span>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tighter leading-none mb-5">
                    Manajemen Akun
                </h1>
                <p class="text-sm font-semibold text-gray-500 max-w-lg mt-2">
                    Kelola hak akses, identitas, dan kredensial seluruh staf pengajar dan pegawai dalam satu tempat.
                </p>
            </div>
            
            <a href="{{ route('admin.akun.create') }}" class="relative inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#0B1120] text-white font-black text-sm rounded-2xl shadow-[0_15px_30px_rgba(11,17,32,0.3)] hover:bg-[#FF5A1F] hover:shadow-[0_20px_40px_rgba(255,90,31,0.4)] hover:-translate-y-1 transition-all duration-300 group overflow-hidden flex-shrink-0">
                <span class="relative z-10 flex items-center gap-2">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Tambah Akun
                </span>
            </a>
        </div>

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                {{ session('error') }}
            </div>
        @endif

        <style>
            .force-transparent-input input,
            .force-transparent-input select {
                background-color: transparent !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                color: #ffffff !important;
                outline: none !important;
                width: 100% !important;
                font-weight: 700 !important;
                font-size: 0.875rem !important;
            }
            .force-transparent-input select {
                appearance: none !important;
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
            }
            .force-transparent-input input::placeholder {
                color: #6B7280 !important;
            }
            .force-transparent-input input:focus,
            .force-transparent-input select:focus {
                background-color: transparent !important;
                box-shadow: none !important;
                border: none !important;
            }
        </style>

        <form method="GET" class="bg-[#0B1120] rounded-[1.5rem] p-2.5 shadow-2xl flex flex-col md:flex-row items-center gap-2 relative z-20">
            
            <div class="force-transparent-input flex items-center w-full flex-1 bg-white/5 hover:bg-white/10 border border-white/10 focus-within:border-[#CCFF00]/50 rounded-xl px-4 py-3 transition-all">
                <div class="text-gray-400 flex-shrink-0 mr-3 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau email...">
            </div>

            <div class="force-transparent-input flex items-center w-full md:w-64 bg-white/5 hover:bg-white/10 border border-white/10 focus-within:border-[#CCFF00]/50 rounded-xl px-4 py-3 transition-all relative">
                <div class="text-gray-400 flex-shrink-0 mr-3 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <select name="role" class="cursor-pointer pr-6">
                    <option value="" class="bg-[#0B1120] text-white">Semua Role</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" @selected(request('role') === $r->name) class="bg-[#0B1120] text-white">{{ $r->display_name ?? $r->name }}</option>
                    @endforeach
                </select>
                <div class="absolute right-4 text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                @if(request()->hasAny(['cari','role']))
                    <a href="{{ route('admin.akun.index') }}" class="p-3.5 text-red-400 bg-red-400/10 hover:bg-red-400/20 rounded-xl transition-colors border border-red-400/20 flex-shrink-0" title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
                <button type="submit" class="flex-1 md:flex-none px-8 py-3.5 bg-[#CCFF00] text-[#0B1120] font-black text-sm rounded-xl hover:scale-105 transition-transform shadow-[0_0_20px_rgba(204,255,0,0.3)] flex-shrink-0">
                    Cari
                </button>
            </div>
        </form>

        <div class="space-y-4">
            @if($users->count())
                @foreach($users as $u)
                    <div class="group relative bg-white rounded-[1.5rem] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_15px_40px_-10px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col md:flex-row md:items-center p-5 lg:p-6 gap-6">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#FF5A1F] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <div class="flex items-center gap-5 flex-1 min-w-0">
                            <div class="relative flex-shrink-0">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0B1120] to-[#1E293B] flex items-center justify-center text-white font-black text-xl shadow-lg ring-4 ring-white group-hover:scale-105 group-hover:ring-[#FF5A1F]/20 transition-all duration-300">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-[#CCFF00] border-2 border-white flex items-center justify-center shadow-sm">
                                    <svg class="w-3 h-3 text-[#0B1120]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xl font-black text-gray-900 truncate group-hover:text-[#FF5A1F] transition-colors">
                                    {{ $u->name }}
                                </h3>
                                <div class="text-sm font-bold text-gray-500 truncate mt-1 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-50 border border-gray-100 text-[10px] uppercase tracking-wider">
                                        <svg class="w-3 h-3 text-[#0B1120]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $u->jabatan ?? 'Jabatan belum diisi' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6">
                            <div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Email</span>
                                <div class="text-sm font-bold text-gray-700 truncate">{{ $u->email }}</div>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">NIP / NUPTK</span>
                                <div class="text-sm font-bold text-gray-700 font-mono tracking-tight">{{ $u->nip ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-6 md:w-64 border-t md:border-t-0 border-gray-100 pt-4 md:pt-0">
                            <div class="text-left md:text-right">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Hak Akses</span>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-[#0B1120] text-white shadow-sm">
                                    {{ $u->role->display_name ?? $u->role->name ?? '-' }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.akun.edit', $u) }}" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-500 hover:bg-[#0B1120] hover:text-[#CCFF00] flex items-center justify-center transition-all duration-300" title="Edit Akun">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.akun.destroy', $u) }}" class="inline" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-600 flex items-center justify-center transition-all duration-300" title="Hapus Akun">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
                
                <div class="mt-8 pt-6">
                    {{ $users->links() }}
                </div>
            @else
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-16 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-5-3.9M9 20H2v-2a4 4 0 014-4h3m8-3a4 4 0 10-8 0 4 4 0 008 0zm5-2a3 3 0 11-6 0"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Tidak Ada Akun Ditemukan</h3>
                    <p class="text-sm font-medium text-gray-500 max-w-sm mb-8 leading-relaxed">
                        Belum ada data akun guru/pegawai atau hasil filter tidak cocok dengan pencarian Anda.
                    </p>
                    <a href="{{ route('admin.akun.create') }}" class="px-8 py-3.5 bg-[#0B1120] text-white font-bold text-sm rounded-xl hover:scale-105 transition-transform shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m-7-7h14"/></svg>
                        Tambah Akun Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>