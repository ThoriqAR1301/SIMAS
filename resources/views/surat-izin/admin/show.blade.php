@extends('surat-izin.layout')
@section('title', 'Detail Surat Izin (Admin)')

@section('content')
<div class="w-full min-h-screen bg-slate-50 pb-12 font-sans selection:bg-[#F4B22E] selection:text-white">
    
    <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 space-y-6">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-white/70 backdrop-blur-xl p-6 rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgba(0,0,0,0.03)]">
            <div class="flex items-center gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Detail Surat Izin</h1>
                        @php
                            $status = strtolower($surat->status);
                            $badgeClass = match($status) {
                                'disetujui', 'approved' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                'ditolak', 'rejected' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
                                default => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                            };
                            $dotClass = match($status) {
                                'disetujui', 'approved' => 'bg-emerald-500',
                                'ditolak', 'rejected' => 'bg-rose-500',
                                default => 'bg-amber-500',
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
                <a href="{{ route('admin.surat-izin.pdf', $surat) }}" target="_blank" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka PDF
                </a>

                <a href="{{ route('admin.surat-izin.pdf', [$surat, 'download' => 1]) }}" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#F4B22E] to-[#e0a020] hover:from-[#e0a020] hover:to-[#c98a15] text-white font-bold text-sm rounded-xl shadow-[0_8px_20px_rgba(244,178,46,0.3)] hover:shadow-[0_8px_25px_rgba(244,178,46,0.4)] transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download PDF
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgba(0,0,0,0.03)] p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Nama Pemohon</span>
                    <p class="font-bold text-slate-900 text-sm md:text-base">{{ $surat->nama }}</p>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Jabatan</span>
                    <p class="font-bold text-slate-900 text-sm md:text-base">{{ $surat->jabatan }}</p>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Tanggal Surat</span>
                    <p class="font-bold text-slate-900 text-sm md:text-base">{{ $surat->tanggal_surat->locale('id')->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Tanggal Izin</span>
                    <p class="font-bold text-slate-900 text-sm md:text-base">{{ $surat->tanggal_izin->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="md:col-span-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Alasan / Keperluan</span>
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl mt-1">
                        <p class="text-sm font-semibold text-slate-700 leading-relaxed">{{ $surat->alasan }}</p>
                    </div>
                </div>

                @if($surat->status !== 'pending')
                    <div class="md:col-span-2 pt-4 border-t border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Diproses Oleh</span>
                                <p class="font-bold text-slate-900 text-sm">{{ $surat->pemroses->name ?? '-' }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-1">{{ optional($surat->diproses_pada)?->locale("id")?->translatedFormat('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Catatan Admin</span>
                                <p class="text-sm font-semibold text-slate-700">{{ $surat->catatan_admin ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($surat->status === 'pending')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-emerald-50/50 rounded-[2rem] border border-emerald-100 p-6 flex flex-col justify-between">
                    <form method="POST" action="{{ route('admin.surat-izin.setujui', $surat) }}">
                        @csrf
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h3 class="text-lg font-black text-emerald-900">Setujui Izin</h3>
                        </div>
                        
                        <label class="block text-xs font-bold text-emerald-800 uppercase tracking-widest mb-2">Catatan (Opsional)</label>
                        <div class="flex items-center bg-white rounded-xl shadow-sm border border-emerald-200 focus-within:ring-4 focus-within:ring-emerald-500/20 transition-all overflow-hidden mb-4">
                            <input type="text" name="catatan_admin" placeholder="Contoh: Disetujui, jangan lupa serahkan tugas..." 
                                   class="w-full px-4 py-3 bg-transparent border-0 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none">
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                            Setujui Pengajuan
                        </button>
                    </form>
                </div>

                <div class="bg-rose-50/50 rounded-[2rem] border border-rose-100 p-6 flex flex-col justify-between">
                    <form method="POST" action="{{ route('admin.surat-izin.tolak', $surat) }}">
                        @csrf
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <h3 class="text-lg font-black text-rose-900">Tolak Izin</h3>
                        </div>
                        
                        <label class="block text-xs font-bold text-rose-800 uppercase tracking-widest mb-2">Alasan Penolakan (Wajib)</label>
                        <div class="flex items-start bg-white rounded-xl shadow-sm border border-rose-200 focus-within:ring-4 focus-within:ring-rose-500/20 transition-all overflow-hidden mb-2">
                            <textarea name="catatan_admin" rows="2" placeholder="Berikan alasan penolakan yang jelas..." required
                                      class="w-full px-4 py-3 bg-transparent border-0 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none resize-none"></textarea>
                        </div>
                        
                        @error('catatan_admin')
                            <div class="text-xs font-bold text-rose-600 mb-4">{{ $message }}</div>
                        @enderror
                        
                        <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm rounded-xl shadow-md transition-all mt-2">
                            Tolak Pengajuan
                        </button>
                    </form>
                </div>

            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-200/80 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] overflow-hidden relative">
            
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 bg-white px-4 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Pratinjau Dokumen PDF
                </div>
                <div class="w-16"></div>
            </div>

            <div class="bg-slate-100 p-2 sm:p-4">
                <iframe src="{{ route('admin.surat-izin.pdf', $surat) }}" class="w-full h-[75vh] rounded-xl border-0 shadow-inner bg-white"></iframe>
            </div>
            
        </div>
        
    </div>
</div>
@endsection