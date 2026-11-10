<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Ringkasan Aktivitas Administrasi Surat</x-slot>

    @php
        $user = auth()->user();
        $isGuru = $user->isGuru();

        if ($isGuru) {
            $totalPengajuan = \App\Models\PengajuanSurat::milik($user->id)->count();
            $draft = \App\Models\PengajuanSurat::milik($user->id)->status('draft')->count();
            $diproses = \App\Models\PengajuanSurat::milik($user->id)->whereIn('status', ['diajukan','diproses'])->count();
            $disetujui = \App\Models\PengajuanSurat::milik($user->id)->status('disetujui')->count();
        } else {
            $totalPengajuan = \App\Models\PengajuanSurat::count();
            $menunggu = \App\Models\PengajuanSurat::menungguApproval($user->isAdminTu() ? 1 : 2)->count();
            $disetujuiBulanIni = \App\Models\PengajuanSurat::status('disetujui')->whereMonth('created_at', now()->month)->count();
            $ditolak = \App\Models\PengajuanSurat::status('ditolak')->count();
        }

        $pengajuanTerbaru = \App\Models\PengajuanSurat::with(['user', 'blanko'])
            ->when($isGuru, fn($q) => $q->milik($user->id))
            ->latest()
            ->take(5)
            ->get();
    @endphp

    <div class="space-y-6 pb-12">

        <div class="relative overflow-hidden rounded-[2rem] bg-[#0B1120] p-8 lg:p-12 shadow-2xl shadow-black/20 group">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#FF5A1F] rounded-full blur-[100px] opacity-30 group-hover:opacity-40 transition-opacity duration-700 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 mb-6 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-[#CCFF00] animate-pulse"></span>
                        <p class="text-[10px] font-bold text-white uppercase tracking-[0.2em]">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight mb-4">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF5A1F] to-[#FF8A5C]">{{ explode(' ', $user->name)[0] }}</span> 👋
                    </h2>
                    <p class="text-slate-400 text-lg font-medium leading-relaxed">
                        @if($isGuru)
                            Anda memiliki <span class="text-white font-bold">{{ $draft }} Draft</span> dan <span class="text-white font-bold">{{ $diproses }} Pengajuan</span> yang sedang diproses oleh sistem.
                        @else
                            Terdapat <span class="text-white font-bold">{{ $menunggu }} Pengajuan</span> baru yang membutuhkan persetujuan dan tindakan Anda hari ini.
                        @endif
                    </p>
                </div>

                <div class="flex flex-shrink-0 flex-col gap-3">
                    @if($isGuru)
                        <a href="{{ route('pengajuan.create') }}" class="relative inline-flex items-center justify-center gap-3 px-8 py-4 bg-[#FF5A1F] text-white font-bold rounded-2xl shadow-[0_15px_30px_rgba(255,90,31,0.3)] hover:shadow-[0_20px_40px_rgba(255,90,31,0.5)] hover:-translate-y-1 transition-all duration-300 group/btn overflow-hidden">
                            <span class="relative z-10 flex items-center gap-2">
                                Buat Pengajuan Baru
                                <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                            <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover/btn:animate-shine"></div>
                        </a>
                        <a href="{{ route('surat-perizinan-sakit.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/5 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                            <svg class="h-5 w-5 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3h6l5 5v13H5V6a3 3 0 013-3zm4 7v7m-3.5-3.5h7"/></svg>
                            Ajukan Izin Sakit
                        </a>
                    @else
                        <a href="{{ route('approval.index') }}" class="relative inline-flex items-center justify-center gap-3 px-8 py-4 bg-[#CCFF00] text-[#0B1120] font-black rounded-2xl shadow-[0_15px_30px_rgba(204,255,0,0.2)] hover:shadow-[0_20px_40px_rgba(204,255,0,0.4)] hover:-translate-y-1 transition-all duration-300 group/btn overflow-hidden">
                            <span class="relative z-10 flex items-center gap-2">
                                Review Approval
                                <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                            <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-40 group-hover/btn:animate-shine"></div>
                        </a>
                        @if($user->isAdminTu())
                            <a href="{{ route('surat-perizinan-sakit.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/5 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                                <svg class="h-5 w-5 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3h6l5 5v13H5V6a3 3 0 013-3zm4 7v7m-3.5-3.5h7"/></svg>
                                Periksa Izin Sakit
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @if($isGuru)
                <div class="relative overflow-hidden bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-bl-full group-hover:scale-110 transition-transform"></div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="text-4xl font-black text-gray-900 tracking-tighter">{{ $totalPengajuan }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Total Pengajuan</div>
                </div>

                <div class="relative overflow-hidden bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gray-500/5 rounded-bl-full group-hover:scale-110 transition-transform"></div>
                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div class="text-4xl font-black text-gray-900 tracking-tighter">{{ $draft }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Draft Tersimpan</div>
                </div>

                <div class="relative overflow-hidden bg-[#FF5A1F] p-6 rounded-[1.5rem] shadow-[0_15px_30px_rgba(255,90,31,0.2)] hover:shadow-[0_20px_40px_rgba(255,90,31,0.3)] hover:-translate-y-1 transition-all duration-300 group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-full group-hover:scale-110 transition-transform"></div>
                    <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center mb-4 backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-4xl font-black text-white tracking-tighter">{{ $diproses }}</div>
                    <div class="text-xs font-bold text-white/80 uppercase tracking-widest mt-1">Sedang Diproses</div>
                </div>

                <div class="relative overflow-hidden bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-[#00D084]/5 rounded-bl-full group-hover:scale-110 transition-transform"></div>
                    <div class="w-10 h-10 rounded-xl bg-[#00D084]/10 text-[#00D084] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-4xl font-black text-gray-900 tracking-tighter">{{ $disetujui }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Disetujui</div>
                </div>
            @else
                <div class="relative overflow-hidden bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                    <div class="text-4xl font-black text-gray-900 tracking-tighter">{{ $totalPengajuan }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Total Pengajuan</div>
                </div>
                <div class="relative overflow-hidden bg-[#FF5A1F] p-6 rounded-[1.5rem] shadow-[0_15px_30px_rgba(255,90,31,0.2)] hover:-translate-y-1 transition-all duration-300 group text-white">
                    <div class="text-4xl font-black tracking-tighter">{{ $menunggu }}</div>
                    <div class="text-xs font-bold text-white/80 uppercase tracking-widest mt-1">Menunggu Anda</div>
                </div>
                <div class="relative overflow-hidden bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                    <div class="text-4xl font-black text-[#00D084] tracking-tighter">{{ $disetujuiBulanIni }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Disetujui Bln Ini</div>
                </div>
                <div class="relative overflow-hidden bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                    <div class="text-4xl font-black text-red-500 tracking-tighter">{{ $ditolak }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Ditolak</div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">Pengajuan Terbaru</h3>
                        <p class="text-sm font-medium text-gray-500 mt-1">5 aktivitas terakhir</p>
                    </div>
                    <a href="{{ route('pengajuan.index') }}" class="px-4 py-2 rounded-xl bg-gray-50 text-xs font-bold text-gray-700 hover:bg-gray-100 transition">Lihat Semua</a>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($pengajuanTerbaru as $p)
                        <a href="{{ route('pengajuan.show', $p) }}" class="group flex items-center gap-5 p-6 hover:bg-[#F8FAFC] transition-colors relative">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#FF5A1F] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0B1120] to-[#1E293B] flex items-center justify-center text-white font-black text-sm shadow-md group-hover:scale-105 transition-transform flex-shrink-0">
                                {{ strtoupper(substr($p->user->name, 0, 2)) }}
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-base text-gray-900 truncate group-hover:text-[#FF5A1F] transition-colors">
                                    {{ $p->nama_kegiatan }}
                                </div>
                                <div class="flex items-center gap-2 text-xs font-medium text-gray-500 mt-1">
                                    <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $p->nomor_pengajuan }}</span>
                                    <span>•</span>
                                    <span>{{ $p->user->name }}</span>
                                </div>
                            </div>
                            
                            <div class="flex-shrink-0 hidden sm:block">
                                <x-pengajuan.status-badge :status="$p->status" />
                            </div>
                        </a>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-20 h-20 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg">Belum Ada Pengajuan</h4>
                            <p class="text-sm text-gray-500 mt-1 mb-4">Aktivitas pengajuan akan muncul di sini.</p>
                            @if($isGuru)
                                <a href="{{ route('pengajuan.create') }}" class="inline-block px-6 py-2.5 bg-[#FF5A1F] text-white font-bold text-sm rounded-xl shadow-lg hover:scale-105 transition">Buat Pengajuan</a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-6 lg:p-8">
                    <h3 class="text-xl font-extrabold text-gray-900 mb-6">Aksi Cepat</h3>
                    
                    <div class="space-y-3">
                        @if($isGuru)
                            <a href="{{ route('pengajuan.create') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-[#FF5A1F] group transition-colors duration-300">
                                <div class="w-10 h-10 rounded-xl bg-white text-[#FF5A1F] flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 group-hover:text-white transition-colors">Buat Pengajuan</span>
                            </a>
                            <a href="{{ route('pengajuan.index', ['status' => 'draft']) }}" class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-gray-900 group transition-colors duration-300">
                                <div class="w-10 h-10 rounded-xl bg-white text-gray-900 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 group-hover:text-white transition-colors">Lanjutkan Draft</span>
                            </a>
                        @else
                            <a href="{{ route('approval.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-[#FF5A1F] group transition-colors duration-300">
                                <div class="w-10 h-10 rounded-xl bg-white text-[#FF5A1F] flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 group-hover:text-white transition-colors">Review Approval</span>
                            </a>
                            <a href="{{ route('pengajuan.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-gray-900 group transition-colors duration-300">
                                <div class="w-10 h-10 rounded-xl bg-white text-gray-900 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 group-hover:text-white transition-colors">Semua Pengajuan</span>
                            </a>
                        @endif
                        
                        <a href="{{ $user->isAdminTu() || $user->isPimpinan() ? route('admin.surat-izin.index') : route('surat-izin.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-[#CCFF00]/20 hover:bg-[#CCFF00] group transition-colors duration-300">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#0B1120] flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2zm7 0v5h5m-9 5h5m-5 4h5"/></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-900 transition-colors">
                                {{ $user->isAdminTu() || $user->isPimpinan() ? 'Kelola Surat Izin' : 'Surat Izin Saya' }}
                            </span>
                        </a>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#0B1120] to-[#1E293B] rounded-[2rem] p-8 text-white relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#FF5A1F] rounded-full blur-3xl opacity-30 group-hover:opacity-50 transition-opacity"></div>
                    <h4 class="text-lg font-bold mb-2 relative z-10">Butuh Bantuan?</h4>
                    <p class="text-sm text-slate-400 mb-6 relative z-10">Hubungi Admin TU jika Anda mengalami kendala pada sistem.</p>
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center relative z-10 backdrop-blur-sm">
                        <svg class="w-5 h-5 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

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
