<x-app-layout>
    <x-slot name="title">Detail Pengajuan</x-slot>
    <x-slot name="subtitle">{{ $pengajuan->nomor_pengajuan }}</x-slot>

    @php
        $isOwner = auth()->id() === $pengajuan->user_id;
        $bisaAksi = ($pengajuan->current_step === 1 && auth()->user()->isAdminTu()) || ($pengajuan->current_step === 2 && auth()->user()->isPimpinan());
    @endphp

    <div class="max-w-9xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 pb-12">

        <div class="lg:col-span-8 space-y-8">

            <div class="relative overflow-hidden rounded-[2rem] bg-[#0B1120] p-8 lg:p-12 shadow-2xl shadow-black/20 group">
                <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-[#FF5A1F] rounded-full blur-[100px] opacity-30 group-hover:opacity-40 transition-opacity duration-700 pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <span class="px-4 py-1.5 bg-[#CCFF00] text-[#0B1120] rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-[0_0_15px_rgba(204,255,0,0.4)]">
                            {{ $pengajuan->status->label() }}
                        </span>
                        <span class="text-xs font-mono font-bold text-slate-400 bg-white/5 border border-white/10 px-3 py-1.5 rounded-lg">
                            {{ $pengajuan->nomor_pengajuan }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl lg:text-5xl font-black text-white leading-tight mb-8 tracking-tight">
                        {{ $pengajuan->nama_kegiatan }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4 text-sm font-medium text-slate-300">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#FF5A1F] to-[#FF8A5C] flex items-center justify-center text-white font-bold shadow-lg">
                                {{ strtoupper(substr($pengajuan->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-widest">Pengaju</div>
                                <div class="text-white font-bold">{{ $pengajuan->user->name }}</div>
                            </div>

                        </div>
                        <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                        <div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">Tanggal Pelaksanaan</div>
                            <div class="text-white font-bold flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#FF5A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $pengajuan->tanggal_pelaksanaan->translatedFormat('d F Y') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100">
                
                <h3 class="text-xl font-extrabold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#FF5A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Data Pengaju
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</div>
                        <div class="font-bold text-gray-900">{{ $pengajuan->user->name }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NIP / NUPTK</div>
                        <div class="font-bold text-gray-900">{{ $pengajuan->user->nip ?? '—' }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jabatan</div>
                        <div class="font-bold text-gray-900">{{ $pengajuan->user->jabatan ?? '—' }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email</div>
                        <div class="font-bold text-gray-900 truncate">{{ $pengajuan->user->email }}</div>
                    </div>
                </div>

                <h3 class="text-xl font-extrabold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#FF5A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Kegiatan
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jenis Surat</div>
                        <div class="font-bold text-gray-900">{{ $pengajuan->jenis_surat }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Waktu</div>
                        <div class="font-bold text-gray-900">{{ $pengajuan->waktu_mulai ?? '—' }} - {{ $pengajuan->waktu_selesai ?? '—' }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100/50">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tempat</div>
                        <div class="font-bold text-gray-900">{{ $pengajuan->tempat ?? '—' }}</div>
                    </div>
                </div>

                @if($pengajuan->keterangan)
                    <div class="mb-8 p-5 bg-orange-50 rounded-2xl border border-orange-100 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#FF5A1F]"></div>
                        <div class="text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-1">Keterangan Tambahan</div>
                        <div class="text-sm font-medium text-gray-800 italic">"{{ $pengajuan->keterangan }}"</div>
                    </div>
                @endif

                <h3 class="text-xl font-extrabold text-gray-900 mb-6 mt-10 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#FF5A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.9M9 20H2v-2a4 4 0 014-4h3m8-3a4 4 0 10-8 0 4 4 0 008 0zm5-2a3 3 0 11-6 0"/></svg>
                    Susunan Panitia
                </h3>

                @if($pengajuan->panitia && count($pengajuan->panitia) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($pengajuan->panitia as $p)
                            <div class="flex items-start gap-4 p-5 rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow group">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 font-black flex-shrink-0 group-hover:bg-[#FF5A1F] group-hover:text-white transition-colors">
                                    {{ is_array($p) ? strtoupper(substr($p['nama'] ?? 'A', 0, 2)) : 'A' }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-gray-900 text-sm truncate">{{ is_array($p) ? ($p['nama'] ?? '—') : $p }}</div>
                                    <div class="text-xs font-semibold text-[#FF5A1F] mt-0.5">{{ is_array($p) ? ($p['jabatan'] ?? '—') : '—' }}</div>
                                    <div class="text-[11px] text-gray-500 mt-1 truncate">{{ is_array($p) ? ($p['keterangan'] ?? 'Tidak ada keterangan') : '—' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 bg-gray-50 rounded-2xl text-center border border-dashed border-gray-200">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <div class="text-sm font-bold text-gray-500">Tidak Ada Data Panitia</div>
                        <div class="text-xs text-gray-400 mt-1">Pengaju tidak melampirkan susunan panitia.</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="lg:sticky lg:top-28 space-y-6">

                @if($isOwner || $bisaAksi)
                <div class="bg-[#0B1120] p-6 rounded-[2rem] shadow-xl relative overflow-hidden text-white">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#FF5A1F] rounded-full blur-[50px] opacity-40"></div>
                    <h3 class="text-lg font-extrabold mb-5 flex items-center gap-2 relative z-10">
                        <svg class="w-5 h-5 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Aksi
                    </h3>
                    
                    <div class="space-y-3 relative z-10">
                        @if($pengajuan->bisaDiedit() && $isOwner)
                            <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="flex items-center justify-center w-full py-3 bg-white/10 text-white font-bold text-sm rounded-xl border border-white/10 hover:bg-white/20 transition-all">
                                Edit Draft
                            </a>
                        @endif

                        @if($pengajuan->bisaDiajukan() && $isOwner)
                            <form action="{{ route('pengajuan.submit', $pengajuan) }}" method="POST">
                                @csrf
                                <button class="w-full py-3 bg-[#FF5A1F] text-white font-bold text-sm rounded-xl shadow-[0_10px_20px_rgba(255,90,31,0.3)] hover:scale-[1.02] transition-transform">
                                    Ajukan Sekarang
                                </button>
                            </form>
                        @endif

                        @if($bisaAksi)
                            <form method="POST" action="{{ $pengajuan->current_step === 1 ? route('approval.verify', $pengajuan) : route('approval.approve', $pengajuan) }}">
                                @csrf
                                <button class="w-full py-3 bg-[#CCFF00] text-[#0B1120] font-black text-sm rounded-xl shadow-[0_10px_20px_rgba(204,255,0,0.2)] hover:scale-[1.02] transition-transform mb-3">
                                    {{ $pengajuan->current_step === 1 ? 'Verifikasi & Teruskan' : 'Setujui Final' }}
                                </button>
                            </form>
                            
                            <button onclick="document.getElementById('form-reject').classList.toggle('hidden')" class="w-full py-3 bg-red-500/10 text-red-400 font-bold text-sm rounded-xl border border-red-500/20 hover:bg-red-500/20 transition-all">
                                Tolak Pengajuan
                            </button>
                            
                            <form id="form-reject" action="{{ route('approval.reject', $pengajuan) }}" method="POST" class="hidden mt-3 bg-black/20 p-4 rounded-xl border border-white/10">
                                @csrf
                                <input type="text" name="catatan" required placeholder="Alasan penolakan..." class="w-full text-sm p-3 bg-white/5 border border-white/10 rounded-lg mb-3 text-white placeholder-gray-400 focus:outline-none focus:border-[#FF5A1F]">
                                <button class="w-full py-2.5 bg-red-600 text-white font-bold rounded-lg text-sm hover:bg-red-700 transition">Kirim Penolakan</button>
                            </form>
                        @endif
                    </div>
                </div>
                @endif

                <div class="bg-white p-6 lg:p-8 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <h3 class="text-lg font-extrabold mb-8 text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Aktivitas
                    </h3>

                    <div class="relative pl-3">
                        <div class="absolute left-[27px] top-4 bottom-4 w-0.5 bg-gray-100 z-0"></div>

                        <div class="relative z-10 flex gap-5 mb-8 group">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 ring-4 ring-white mt-1 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 leading-tight">Pengajuan Dibuat</div>
                                <div class="text-[11px] font-semibold text-gray-500 mt-1">{{ $pengajuan->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>

                        @foreach($pengajuan->approvalLogs as $log)
                        <div class="relative z-10 flex gap-5 mb-8 group">
                            <div class="w-8 h-8 rounded-full {{ $log->aksi === 'approve' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center flex-shrink-0 ring-4 ring-white mt-1 group-hover:scale-110 transition-transform">
                                @if($log->aksi === 'approve') 
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> 
                                @else 
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg> 
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-gray-900 leading-tight">{{ $log->label_aksi }} <span class="text-[10px] text-gray-400 font-medium ml-1">({{ $log->label_step }})</span></div>
                                <div class="text-[11px] font-semibold text-gray-500 mt-1">{{ $log->approver->name }} • {{ $log->acted_at->format('d M Y, H:i') }}</div>
                                
                                @if($log->catatan)
                                    <div class="mt-3 text-xs bg-gray-50 p-3 rounded-xl border border-gray-100 italic text-gray-600 relative">
                                        <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1 w-2 h-2 bg-gray-50 border-l border-b border-gray-100 rotate-45"></div>
                                        "{{ $log->catatan }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        @if($pengajuan->sudahDisetujui())
                        <a href="{{ route('pengajuan.pdf', $pengajuan) }}" target="_blank" class="relative inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#0B1120] text-white font-black text-sm uppercase tracking-wider rounded-2xl shadow-[0_15px_30px_rgba(11,17,32,0.3)] hover:bg-[#F2B51D] hover:text-[#0B1120] hover:scale-105 transition-all duration-300 group overflow-hidden w-full md:w-auto">
                            <span class="relative z-10 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Unduh Surat Tugas (PDF)
                            </span>
                            <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
                        </a>
                        @endif
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>