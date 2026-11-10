<x-app-layout>
    <x-slot name="title">Pengajuan Surat</x-slot>
    <x-slot name="subtitle">Kelola Semua Pengajuan Surat Anda</x-slot>

    <div class="max-w-7xl mx-auto space-y-6 pb-12">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-gray-200 shadow-sm mb-3">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF5A1F] opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FF5A1F]"></span>
                    </span>
                    <span class="text-[11px] font-black text-gray-600 uppercase tracking-widest">{{ $pengajuan->total() }} Data Ditemukan</span>
                </div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Daftar Pengajuan</h2>
            </div>
            
            @can('create', App\Models\PengajuanSurat::class)
                <a href="{{ route('pengajuan.create') }}" class="relative inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#0B1120] text-white font-bold text-sm rounded-xl shadow-[0_10px_20px_rgba(11,17,32,0.2)] hover:bg-[#FF5A1F] hover:shadow-[0_15px_30px_rgba(255,90,31,0.3)] hover:-translate-y-0.5 transition-all duration-300 group overflow-hidden">
                    <span class="relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Buat Pengajuan Baru
                    </span>
                </a>
            @endcan
        </div>

        <form method="GET" class="bg-white/70 backdrop-blur-xl border border-gray-200/60 shadow-[0_8px_30px_rgba(0,0,0,0.03)] rounded-2xl p-2 relative z-20">
            <div class="flex flex-col lg:flex-row gap-2">
                
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#FF5A1F] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nomor, kegiatan, atau jenis surat..." class="w-full pl-11 pr-4 py-3 bg-gray-50/50 hover:bg-gray-50 focus:bg-white border-transparent focus:border-[#FF5A1F] focus:ring-4 focus:ring-[#FF5A1F]/10 rounded-xl text-sm font-semibold text-gray-900 transition-all outline-none">
                </div>

                <div class="lg:w-56 relative group">
                    <select name="status" class="w-full pl-4 pr-10 py-3 bg-gray-50/50 hover:bg-gray-50 focus:bg-white border border-transparent focus:border-[#FF5A1F] focus:ring-4 focus:ring-[#FF5A1F]/10 rounded-xl text-sm font-bold text-gray-800 appearance-none bg-none transition-all outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach(\App\Enums\StatusPengajuan::cases() as $s)
                            <option value="{{ $s->value }}" @selected(($filters['status'] ?? '') === $s->value)>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 group-hover:text-[#FF5A1F] group-focus-within:text-[#FF5A1F] transition-colors duration-300">
                        <svg class="w-4 h-4 transform group-hover:-translate-y-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-2 bg-gray-50/50 hover:bg-gray-50 focus-within:bg-white border border-transparent focus-within:border-[#FF5A1F] focus-within:ring-4 focus-within:ring-[#FF5A1F]/10 rounded-xl px-4 py-1 transition-all">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <input type="date" name="dari" value="{{ $filters['dari'] ?? '' }}" class="w-full bg-transparent border-none p-0 text-sm font-semibold text-gray-700 focus:ring-0 cursor-pointer">
                    <span class="text-gray-300">-</span>
                    <input type="date" name="sampai" value="{{ $filters['sampai'] ?? '' }}" class="w-full bg-transparent border-none p-0 text-sm font-semibold text-gray-700 focus:ring-0 cursor-pointer">
                </div>

                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['q', 'status', 'dari', 'sampai']))
                        <a href="{{ route('pengajuan.index') }}" class="p-3 text-red-500 bg-red-50 hover:bg-red-100 rounded-xl transition-colors" title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    <button type="submit" class="px-6 py-3 bg-[#0B1120] text-white font-bold text-sm rounded-xl hover:bg-gray-900 transition-colors shadow-md">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] overflow-hidden">
            
            <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-5 bg-gray-50/50 border-b border-gray-100 text-[11px] font-black text-gray-400 uppercase tracking-widest">
                <div class="col-span-5">Informasi Pengajuan</div>
                <div class="col-span-3">Waktu Pelaksanaan</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2 text-right">Aksi</div>
            </div>

            <div class="divide-y divide-gray-100 relative">
                @forelse($pengajuan as $p)
                    <div class="group grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-6 md:px-8 py-5 hover:bg-[#F8FAFC] transition-all duration-300 relative">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#FF5A1F] opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="col-span-5 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0B1120] to-[#1E293B] flex items-center justify-center text-white font-black text-sm shadow-lg group-hover:scale-105 transition-transform flex-shrink-0 mt-0.5">
                                {{ strtoupper(substr($p->user->name, 0, 2)) }}
                            </div>
                            
                            <div class="min-w-0">
                                <h3 class="text-base font-bold text-gray-900 truncate group-hover:text-[#FF5A1F] transition-colors">
                                    {{ $p->nama_kegiatan }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                    <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md border border-gray-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                        {{ $p->nomor_pengajuan }}
                                    </span>
                                    
                                    @if($p->nomor_surat)
                                        <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold bg-[#00D084]/10 text-[#00D084] px-2 py-0.5 rounded-md border border-[#00D084]/20">
                                            {{ $p->nomor_surat }}
                                        </span>
                                    @endif

                                    <span class="text-[11px] font-semibold text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $p->user->name }}
                                    </span>
                                </div>
                                <div class="text-xs font-medium text-gray-400 mt-1">{{ $p->jenis_surat }}</div>
                            </div>
                        </div>

                        <div class="col-span-3 mt-2 md:mt-0">
                            <div class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $p->tanggal_pelaksanaan->translatedFormat('d M Y') }}
                            </div>
                            @if($p->waktu_mulai)
                                <div class="text-xs font-semibold text-gray-500 mt-1 flex items-center gap-2 pl-6">
                                    {{ $p->waktu_mulai }} - {{ $p->waktu_selesai }}
                                </div>
                            @endif
                        </div>

                        <div class="col-span-2 mt-2 md:mt-0">
                            <x-pengajuan.status-badge :status="$p->status" />
                        </div>

                        <div class="col-span-2 flex items-center justify-end gap-2 mt-4 md:mt-0">
                            
                            <a href="{{ route('pengajuan.show', $p) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gray-50 hover:bg-[#0B1120] text-gray-600 hover:text-white text-xs font-bold rounded-xl transition-all duration-300 shadow-sm" title="Lihat Detail">
                                Detail
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            @if($p->sudahDisetujui())
                                <a href="{{ route('pengajuan.pdf', $p) }}" target="_blank" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#FF5A1F]/10 text-[#FF5A1F] hover:bg-[#FF5A1F] hover:text-white transition-all duration-300 shadow-sm" title="Unduh PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12">
                        <x-empty-state
                            title="Belum Ada Pengajuan"
                            description="Mulai Dengan Membuat Pengajuan Surat Pertama Anda"
                            action-label="Buat Pengajuan"
                            :action-url="route('pengajuan.create')" />
                    </div>
                @endforelse
            </div>

            @if($pengajuan->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/30">
                    {{ $pengajuan->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>