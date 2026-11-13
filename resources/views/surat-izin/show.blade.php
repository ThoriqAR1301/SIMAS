<x-app-layout title="Detail Surat Izin">
    <div class="max-w-9xl mx-auto space-y-6 pb-12">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-white/60 backdrop-blur-xl p-6 rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgba(0,0,0,0.03)]">
            <div class="flex items-center gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Detail Surat Izin</h1>
                        @php
                            $status = strtolower($surat->status);
                            $badgeClass = match($status) {
                                'disetujui', 'approved' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                'ditolak', 'rejected' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
                                default => 'bg-[#F4B22E]/10 text-[#d99a1a] border-[#F4B22E]/20',
                            };
                            $dotClass = match($status) {
                                'disetujui', 'approved' => 'bg-emerald-500',
                                'ditolak', 'rejected' => 'bg-rose-500',
                                default => 'bg-[#F4B22E]',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} animate-pulse"></span>
                            {{ ucfirst($surat->status) }}
                        </span>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Pratinjau dokumen dan kelola status persetujuan</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                @if($surat->status === 'pending' && $surat->user_id === auth()->id())
                    <a href="{{ route('surat-izin.edit', $surat) }}" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                @endif
                
                <a href="{{ route('surat-izin.pdf', $surat) }}" target="_blank" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka PDF
                </a>

                <a href="{{ route('surat-izin.pdf', [$surat, 'download' => 1]) }}" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#F4B22E] to-[#e0a020] hover:from-[#e0a020] hover:to-[#c98a15] text-white font-bold text-sm rounded-xl shadow-[0_8px_20px_rgba(244,178,46,0.3)] hover:shadow-[0_8px_25px_rgba(244,178,46,0.4)] transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download PDF
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200/80 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] overflow-hidden relative">
            
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 bg-white px-4 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Pratinjau Dokumen
                </div>
                <div class="w-16"></div> 
            </div>

            <div class="bg-slate-100 p-2 sm:p-4">
                <iframe src="{{ route('surat-izin.pdf', $surat) }}" class="w-full h-[75vh] rounded-xl border-0 shadow-inner bg-white"></iframe>
            </div>
            
        </div>
    </div>
</x-app-layout>