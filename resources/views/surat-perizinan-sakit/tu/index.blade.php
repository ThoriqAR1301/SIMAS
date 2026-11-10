<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">
                Pemeriksaan Pengajuan Sakit
            </h2>
            <p class="text-[11px] font-medium text-gray-500 mt-0.5">
                Kelola dan periksa pengajuan izin sakit dari Guru.
            </p>
        </div>
    </x-slot>

    @php
        $statusLabels = [
            'menunggu' => ['Menunggu', 'bg-amber-500/10 text-amber-600 border-amber-500/20', 'bg-amber-500'],
            'disetujui' => ['Disetujui', 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20', 'bg-emerald-500'],
            'ditolak' => ['Ditolak', 'bg-red-500/10 text-red-600 border-red-500/20', 'bg-red-500'],
            'perlu_revisi' => ['Perlu Revisi', 'bg-orange-500/10 text-orange-600 border-orange-500/20', 'bg-orange-500'],
        ];
    @endphp

    <div class="max-w-9xl mx-auto space-y-6 pb-12">

        <div class="relative rounded-[2rem] overflow-hidden bg-[#0B1120] shadow-2xl shadow-black/20 group">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#F5B324] rounded-full blur-[100px] opacity-20 group-hover:opacity-30 transition-opacity duration-700 pointer-events-none"></div>

            <div class="relative p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-[#F5B324] animate-pulse"></span>
                        <span class="text-[10px] font-black text-[#F5B324] uppercase tracking-widest">Admin Tata Usaha</span>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight mb-4">
                        Pengajuan Sakit Masuk
                    </h1>
                    <p class="text-sm font-medium text-gray-400 leading-relaxed">
                        Lihat informasi pengajuan, periksa surat keterangan dokter, dan tentukan hasil pemeriksaan izin sakit Guru.
                    </p>
                </div>

                <div class="relative px-8 py-6 rounded-3xl bg-white/[0.03] backdrop-blur-xl border border-white/10 shadow-lg flex-shrink-0 min-w-[200px]">
                    <div class="text-6xl font-black tracking-tighter text-white mb-1">
                        {{ $pengajuan->where('status', 'menunggu')->count() }}
                    </div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#F5B324]">
                        Menunggu Tindakan
                    </div>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="relative overflow-hidden bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-500/5 rounded-bl-full group-hover:scale-110 transition-transform pointer-events-none"></div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-800 flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Pengajuan</span>
                        <p class="text-3xl font-black text-gray-900 tracking-tighter mt-1">{{ $pengajuan->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-[#F5B324] p-6 rounded-[2rem] shadow-[0_15px_30px_rgba(245,179,36,0.2)] hover:shadow-[0_20px_40px_rgba(245,179,36,0.3)] hover:-translate-y-1 transition-all duration-300 group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-bl-full group-hover:scale-110 transition-transform pointer-events-none"></div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-white/30 text-[#0B1120] flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-[#0B1120]/70">Menunggu</span>
                        <p class="text-3xl font-black text-[#0B1120] tracking-tighter mt-1">{{ $pengajuan->where('status', 'menunggu')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/5 rounded-bl-full group-hover:scale-110 transition-transform pointer-events-none"></div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.4-9.4a2 2 0 112.8 2.8L11.8 15H9v-2.8l8.6-8.6z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Perlu Revisi</span>
                        <p class="text-3xl font-black text-gray-900 tracking-tighter mt-1">{{ $pengajuan->where('status', 'perlu_revisi')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-6 lg:p-8 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Daftar Pengajuan Guru</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Pilih pengajuan untuk melihat dokumen dan melakukan pemeriksaan.</p>
                </div>
                <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold">{{ $pengajuan->count() }} Data</span>
            </div>

            @if ($pengajuan->isEmpty())
                <div class="p-16 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">Belum Ada Pengajuan</h3>
                    <p class="text-sm font-medium text-gray-500 max-w-sm">
                        Belum ada Guru yang mengirimkan pengajuan izin sakit saat ini.
                    </p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($pengajuan as $item)
                        @php
                            $statusInfo = $statusLabels[$item->status] ?? ['Tidak Diketahui', 'bg-gray-100 text-gray-600 border-gray-200', 'bg-gray-400'];
                        @endphp
                        
                        <div class="group flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 lg:p-8 hover:bg-[#F8FAFC] transition-colors duration-300 relative">
                            <div class="absolute left-0 top-0 bottom-0 w-1 opacity-0 group-hover:opacity-100 transition-opacity" style="background-color: var(--accent)"></div>

                            <div class="flex items-center gap-5 flex-1 min-w-0">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0B1120] to-[#1E293B] flex items-center justify-center text-[#F5B324] font-black text-lg shadow-md group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                                    {{ strtoupper(substr($item->user?->name ?? 'G', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-base font-bold text-gray-900 truncate group-hover:text-[#F5B324] transition-colors">
                                        {{ $item->user?->name ?? 'Pengguna tidak diketahui' }}
                                    </h3>
                                    <div class="text-xs font-semibold text-gray-500 truncate mt-1 flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $item->tanggal_mulai?->format('d M Y') ?? '-' }} 
                                        <span class="text-gray-300 mx-0.5">s.d</span> 
                                        {{ $item->tanggal_selesai?->format('d M Y') ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="hidden md:block flex-1 min-w-0 px-4">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Alasan</span>
                                <p class="text-sm font-medium text-gray-600 line-clamp-1 italic">
                                    "{{ \Illuminate\Support\Str::limit($item->alasan, 60) }}"
                                </p>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-6 flex-shrink-0 mt-4 md:mt-0">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $statusInfo[1] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusInfo[2] }}"></span>
                                    {{ $statusInfo[0] }}
                                </span>

                                <a href="{{ route('surat-perizinan-sakit.show', $item) }}" class="flex items-center gap-2 px-5 py-2.5 bg-[#0B1120] text-white text-xs font-bold rounded-xl hover:bg-[#F5B324] hover:text-[#0B1120] hover:scale-105 transition-all duration-300 shadow-md">
                                    Detail
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <p class="pt-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            SIMAS — Sistem Informasi Manajemen Administrasi Sekolah
        </p>
    </div>
</x-app-layout>