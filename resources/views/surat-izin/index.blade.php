<x-app-layout title="Surat Izin">
    <div class="min-h-screen bg-slate-50 relative overflow-hidden pb-5">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#F4B22E]/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 relative z-10 space-y-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                        Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F4B22E] to-[#d99a1a]">Surat Izin</span>
                    </h1>
                    <p class="text-sm md:text-base font-medium text-slate-500 max-w-lg">
                        Pantau dan kelola pengajuan izin guru & pegawai dengan mudah dalam satu dasbor terpusat
                    </p>
                </div>
                
                <a href="{{ route('surat-izin.create') }}" 
                   class="group relative inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.2)] hover:shadow-[0_10px_40px_rgba(0,0,0,0.3)] transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5 text-[#F4B22E] transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Buat Surat Izin
                </a>
            </div>

            <div class="space-y-4">
                @if($surats->count())
                    @foreach($surats as $s)
                        @php
                            $status = strtolower($s->status);
                            $statusConfig = match($status) {
                                'disetujui', 'approved' => [
                                    'bg' => 'bg-emerald-50/50', 'border' => 'border-emerald-100', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-500', 'shadow' => 'shadow-emerald-500/20'
                                ],
                                'ditolak', 'rejected' => [
                                    'bg' => 'bg-rose-50/50', 'border' => 'border-rose-100', 'text' => 'text-rose-600', 'dot' => 'bg-rose-500', 'shadow' => 'shadow-rose-500/20'
                                ],
                                default => [
                                    'bg' => 'bg-amber-50/50', 'border' => 'border-amber-100', 'text' => 'text-amber-600', 'dot' => 'bg-amber-500', 'shadow' => 'shadow-amber-500/20'
                                ],
                            };
                        @endphp

                        <div class="group bg-white/80 backdrop-blur-xl rounded-[1.5rem] border border-slate-200/60 p-5 md:p-6 shadow-[0_8px_30px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:border-[#F4B22E]/30 transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-6 transform hover:-translate-y-1">
                            
                            <div class="flex items-center gap-4 min-w-[280px]">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center text-[#F4B22E] font-black text-xl shadow-lg border border-slate-700 group-hover:scale-105 transition-transform duration-300">
                                    {{ strtoupper(substr($s->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-[#d99a1a] transition-colors">{{ $s->nama }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $s->jabatan }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1 flex flex-col md:flex-row md:items-center gap-4 md:gap-12">
                                <div class="flex flex-col min-w-[120px]">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Tanggal Izin</span>
                                    <span class="text-sm font-bold text-slate-700">{{ $s->tanggal_izin->locale('id')->translatedFormat('d M Y') }}</span>
                                </div>
                                
                                <div class="flex flex-col flex-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Alasan</span>
                                    <p class="text-sm font-medium text-slate-600 truncate md:whitespace-normal md:line-clamp-1 group-hover:line-clamp-none transition-all duration-300">
                                        {{ $s->alasan }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-4 min-w-[200px] border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                                <div class="flex flex-col items-start md:items-end">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Status</span>
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-black tracking-wide border {{ $statusConfig['bg'] }} {{ $statusConfig['border'] }} {{ $statusConfig['text'] }} shadow-sm">
                                        <span class="w-2 h-2 rounded-full {{ $statusConfig['dot'] }} shadow-[0_0_8px_currentColor] animate-pulse"></span>
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </div>

                                <a href="{{ route('surat-izin.show', $s) }}" 
                                   class="w-11 h-11 flex items-center justify-center rounded-xl bg-slate-50 border border-slate-200 text-slate-400 hover:text-white hover:bg-[#F4B22E] hover:border-[#F4B22E] shadow-sm hover:shadow-[0_8px_20px_rgba(244,178,46,0.3)] transition-all duration-300 group/btn">
                                    <svg class="w-5 h-5 transition-transform group-hover/btn:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="mt-8 flex justify-center">
                        {{ $surats->links() }}
                    </div>
                @else
                    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] border border-slate-200/60 p-12 md:p-24 flex flex-col items-center justify-center text-center shadow-[0_8px_30px_rgba(0,0,0,0.03)] relative overflow-hidden">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1/2 bg-gradient-to-b from-[#F4B22E]/5 to-transparent"></div>
                        
                        <div class="relative w-24 h-24 bg-white border border-slate-100 rounded-[2rem] shadow-xl flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="relative text-2xl font-black text-slate-900 tracking-tight">Belum Ada Riwayat</h3>
                        <p class="relative text-sm font-medium text-slate-500 mt-2 mb-8 max-w-md mx-auto">Sistem tidak menemukan data pengajuan izin. Mulai dengan membuat pengajuan baru untuk guru atau pegawai</p>
                        <a href="{{ route('surat-izin.create') }}" 
                           class="relative px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                            + Buat Pengajuan Baru
                        </a>
                    </div>
                @endif
            </div>
            
        </div>

    </div>
</x-app-layout>