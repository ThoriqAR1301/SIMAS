@extends('surat-izin.layout')
@section('title', 'Kelola Surat Izin')

@section('content')
<div class="w-full min-h-screen bg-slate-50 pb-20 font-sans">
    
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 pt-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 shadow-sm mb-4">
                    <span class="w-2 h-2 rounded-full bg-[#F4B22E] animate-pulse"></span>
                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Live Dashboard</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Kelola <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F4B22E] to-[#d99a1a]">Surat Izin</span>
                </h1>
                <p class="text-base font-medium text-slate-500 mt-3 max-w-2xl">Pantau, filter, dan kelola seluruh pengajuan izin staf dan guru dengan antarmuka generasi baru</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="relative overflow-hidden bg-white rounded-[2rem] p-8 border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-amber-100 rounded-full blur-2xl opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-black text-amber-600 uppercase tracking-widest mb-2">Menunggu Persetujuan</p>
                        <h3 class="text-5xl font-black text-slate-900">{{ $hitung['pending'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white rounded-[2rem] p-8 border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-100 rounded-full blur-2xl opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-2">Disetujui</p>
                        <h3 class="text-5xl font-black text-slate-900">{{ $hitung['disetujui'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white rounded-[2rem] p-8 border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-rose-100 rounded-full blur-2xl opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-black text-rose-600 uppercase tracking-widest mb-2">Ditolak</p>
                        <h3 class="text-5xl font-black text-slate-900">{{ $hitung['ditolak'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 bg-slate-900 rounded-[2rem] p-4 sm:p-5 shadow-2xl border border-slate-800">
            <form method="GET" class="flex flex-col xl:flex-row gap-3 w-full">
                
                <div class="relative w-full group">
                    <div class="flex items-center w-full bg-white rounded-xl shadow-sm border-0 focus-within:ring-4 focus-within:ring-[#F4B22E]/30 transition-all overflow-hidden">
                        
                        <div class="pl-4 pr-2 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#F4B22E] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau alasan..." 
                               class="w-full py-3.5 pr-4 bg-transparent border-0 text-sm font-semibold text-slate-900 placeholder:text-slate-400 outline-none focus:ring-0">
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                    <div class="relative w-full sm:w-48">
                        <select name="jabatan" class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700/50 rounded-xl text-sm font-medium text-white appearance-none focus:ring-2 focus:ring-[#F4B22E] focus:border-[#F4B22E] outline-none cursor-pointer">
                            <option value="">Semua Jabatan</option>
                            <option value="Guru" @selected(request('jabatan')=='Guru')>Guru</option>
                            <option value="Pegawai" @selected(request('jabatan')=='Pegawai')>Pegawai</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-48">
                        <select name="status" class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700/50 rounded-xl text-sm font-medium text-white appearance-none focus:ring-2 focus:ring-[#F4B22E] focus:border-[#F4B22E] outline-none cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="pending" @selected(request('status')=='pending')>Pending</option>
                            <option value="disetujui" @selected(request('status')=='disetujui')>Disetujui</option>
                            <option value="ditolak" @selected(request('status')=='ditolak')>Ditolak</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div class="flex gap-3 w-full sm:w-auto">
                        <button class="flex-1 sm:flex-none px-8 py-3.5 bg-[#F4B22E] hover:bg-[#d99a1a] text-slate-900 font-black text-sm rounded-xl transition-all shadow-[0_0_20px_rgba(244,178,46,0.2)] whitespace-nowrap" type="submit">
                            Filter
                        </button>
                        <a href="{{ route('admin.surat-izin.index') }}" class="flex-1 sm:flex-none px-6 py-3.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-sm rounded-xl border border-slate-700 transition-colors text-center whitespace-nowrap">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @if($surats->count())
                    @foreach($surats as $s)
                        @php
                            $status = strtolower($s->status);
                            $theme = match($status) {
                                'disetujui', 'approved' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                'ditolak', 'rejected' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'dot' => 'bg-rose-500'],
                                default => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
                            };
                        @endphp
                        
                        <div class="group relative bg-white rounded-[2rem] p-1 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] transition-all duration-300">
                            <div class="h-full bg-white rounded-[1.8rem] p-6 flex flex-col justify-between gap-6 border border-slate-100 relative overflow-hidden">
                                
                                <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#F4B22E]/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                                <div class="flex justify-between items-start relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-[#F4B22E] flex items-center justify-center font-black text-2xl shadow-inner">
                                            {{ strtoupper(substr($s->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-black text-slate-900">{{ $s->nama }}</h3>
                                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-0.5">{{ $s->jabatan }}</p>
                                        </div>
                                    </div>
                                    
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-[11px] font-black uppercase tracking-widest border {{ $theme['bg'] }} {{ $theme['text'] }} {{ $theme['border'] }}">
                                        <span class="w-2 h-2 rounded-full {{ $theme['dot'] }} animate-pulse"></span>
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </div>

                                <div class="bg-slate-50 rounded-2xl p-4 relative z-10 border border-slate-100">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Izin</p>
                                            <p class="text-sm font-bold text-slate-800">{{ $s->tanggal_izin->locale('id')->translatedFormat('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alasan</p>
                                            <p class="text-sm font-medium text-slate-600 truncate" title="{{ $s->alasan }}">{{ \Illuminate\Support\Str::limit($s->alasan, 30) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center border-t border-slate-100 pt-4 relative z-10">
                                    <span class="text-xs font-semibold text-slate-400">ID: #{{ str_pad($s->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.surat-izin.pdf', $s) }}" target="_blank" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Print
                                        </a>
                                        <a href="{{ route('admin.surat-izin.show', $s) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all shadow-md group/btn">
                                            Detail
                                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach

                    <div class="col-span-1 lg:col-span-2 xl:col-span-3 mt-10 flex justify-center">
                        {{ $surats->links() }}
                    </div>
                @else
                    <div class="col-span-1 lg:col-span-2 xl:col-span-3 bg-white rounded-[2rem] border border-slate-100 p-20 flex flex-col items-center justify-center text-center shadow-sm">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 border border-slate-100">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">Pencarian Tidak Ditemukan</h3>
                        <p class="text-sm font-medium text-slate-500 mt-2 mb-8 max-w-md">Kami tidak dapat menemukan data surat izin yang cocok dengan filter Anda saat ini</p>
                        <a href="{{ route('admin.surat-izin.index') }}" class="px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl shadow-lg transition-all">Bersihkan Filter</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
</div>
@endsection