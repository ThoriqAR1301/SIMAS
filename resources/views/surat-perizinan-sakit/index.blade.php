<x-app-layout>
    <x-slot name="title">Riwayat Pengajuan Sakit</x-slot>
    <x-slot name="subtitle">Pantau status persetujuan dan riwayat izin Anda</x-slot>

    <div class="max-w-7xl mx-auto space-y-6 pb-12">

        <div class="relative overflow-hidden rounded-[2rem] bg-[#0B1120] p-8 lg:p-10 shadow-2xl shadow-black/20 group">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#F2B51D] rounded-full blur-[100px] opacity-20 group-hover:opacity-30 transition-opacity duration-700 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 mb-4 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-[#F2B51D] animate-pulse"></span>
                        <span class="text-[10px] font-black text-[#F2B51D] uppercase tracking-widest">Layanan Mandiri</span>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight mb-3">
                        Riwayat Pengajuan Sakit
                    </h1>
                    <p class="text-sm font-medium text-gray-400 leading-relaxed">
                        Lihat seluruh pengajuan izin sakit yang telah Anda kirim dan pantau status pemeriksaannya oleh pihak Tata Usaha.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                <div>
                    <h2 class="text-xl font-black text-gray-900 tracking-tight">Daftar Pengajuan Anda</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Pengajuan terbaru ditampilkan terlebih dahulu.</p>
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#FF5A1F]"></span>
                    <span class="text-sm font-bold text-gray-700">{{ $pengajuan->count() }} Total Pengajuan</span>
                </div>
            </div>

            @php
                $statusLabels = [
                    \App\Models\SuratPerizinanSakit::STATUS_MENUNGGU => [
                        'Menunggu',
                        'bg-[#F2B51D]/10 text-[#946600] border-[#F2B51D]/30',
                        'bg-[#F2B51D]'
                    ],
                    \App\Models\SuratPerizinanSakit::STATUS_DISETUJUI => [
                        'Disetujui',
                        'bg-emerald-500/10 text-emerald-700 border-emerald-500/30',
                        'bg-emerald-500'
                    ],
                    \App\Models\SuratPerizinanSakit::STATUS_DITOLAK => [
                        'Ditolak',
                        'bg-red-500/10 text-red-700 border-red-500/30',
                        'bg-red-500'
                    ],
                    \App\Models\SuratPerizinanSakit::STATUS_PERLU_REVISI => [
                        'Perlu Revisi',
                        'bg-orange-500/10 text-orange-700 border-orange-500/30',
                        'bg-orange-500'
                    ],
                ];
            @endphp

            @forelse ($pengajuan as $item)
                @php
                    [$statusLabel, $statusClass, $statusColorClass] = $statusLabels[$item->status] ?? [
                        'Tidak Dikenal',
                        'bg-gray-100 text-gray-600 border-gray-200',
                        'bg-gray-500'
                    ];
                @endphp

                <div class="group relative bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_40px_-10px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $statusColorClass }} opacity-80 transition-opacity duration-300"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-6 lg:p-8">
                        
                        <div class="lg:col-span-4 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-gray-100 pb-6 lg:pb-0 lg:pr-6">
                            <div class="mb-4">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Periode Izin</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $item->tanggal_mulai?->format('d M Y') }} 
                                        <span class="text-gray-400 mx-1">s.d</span> 
                                        {{ $item->tanggal_selesai?->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $statusClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusColorClass }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>

                        <div class="lg:col-span-5 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-gray-100 pb-6 lg:pb-0 lg:pr-6">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Keterangan Sakit</span>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 relative">
                                <svg class="absolute top-3 right-3 w-8 h-8 text-gray-200 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                <p class="text-sm font-medium text-gray-700 leading-relaxed relative z-10">
                                    {{ $item->alasan }}
                                </p>
                            </div>
                        </div>

                        <div class="lg:col-span-3 flex flex-col justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Dokumen Pendukung</span>
                            
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-orange-50 border border-orange-100 mb-4">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-[#F2B51D] shadow-sm flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-gray-900 truncate">Surat Dokter</p>
                                    <p class="text-[10px] font-semibold text-gray-500 mt-0.5">{{ $item->surat_dokter_path ? 'Tersedia' : 'Tidak Ada' }}</p>
                                </div>
                            </div>

                            <a href="{{ route('surat-perizinan-sakit.show', $item) }}" class="w-full py-3 bg-[#0B1120] text-white font-bold text-xs rounded-xl hover:bg-[#FF5A1F] transition-colors duration-300 flex items-center justify-center gap-2 shadow-md">
                                Lihat Detail
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                    </div>
                </div>

            @empty
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-16 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Belum Ada Riwayat</h3>
                    <p class="text-sm font-medium text-gray-500 max-w-md mb-8 leading-relaxed">
                        Anda belum pernah mengajukan izin sakit. Silakan buat pengajuan baru jika Anda membutuhkan izin.
                    </p>
                    <a href="{{ route('surat-perizinan-sakit.create') }}" class="px-8 py-3.5 bg-[#0B1120] text-white font-bold text-sm rounded-xl hover:scale-105 transition-transform shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#F2B51D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m-7-7h14"/></svg>
                        Buat Pengajuan Sekarang
                    </a>
                </div>
            @endforelse

        </div>

        <p class="pt-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            SIMAS · Sistem Informasi Manajemen Administrasi Sekolah
        </p>

    </div>
</x-app-layout>