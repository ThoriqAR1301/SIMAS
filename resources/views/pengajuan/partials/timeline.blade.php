<div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-6 lg:p-8">
    <h3 class="text-lg font-extrabold text-gray-900 mb-8 flex items-center gap-2">
        <svg class="w-5 h-5 text-[#FF5A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                <div class="text-[11px] font-semibold text-gray-500 mt-1.5">
                    {{ $pengajuan->created_at->format('d M Y, H:i') }} 
                    <span class="mx-1">•</span> 
                    {{ $pengajuan->user->name }}
                </div>
            </div>
        </div>

        @foreach($pengajuan->approvalLogs as $log)
            @php
                $warna = match($log->aksi) {
                    'approve' => 'emerald',
                    'reject' => 'red',
                    'revisi' => 'gold',
                    default => 'gray',
                };
            @endphp
            <div class="relative z-10 flex gap-5 mb-8 group">
                <div class="w-8 h-8 rounded-full bg-{{ $warna }}-100 text-{{ $warna }}-600 flex items-center justify-center flex-shrink-0 ring-4 ring-white mt-1 group-hover:scale-110 transition-transform">
                    @if($log->aksi === 'approve')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @elseif($log->aksi === 'reject')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    @endif
                </div>
                
                <div class="min-w-0">
                    <div class="text-sm font-bold text-gray-900 leading-tight">
                        {{ $log->label_aksi }} 
                        <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider ml-1">({{ $log->label_step }})</span>
                    </div>
                    <div class="text-[11px] font-semibold text-gray-500 mt-1.5">
                        {{ $log->acted_at->format('d M Y, H:i') }} 
                        <span class="mx-1">•</span> 
                        {{ $log->approver->name }}
                    </div>
                    
                    @if($log->catatan)
                        <div class="mt-3 text-xs bg-gray-50 p-3 rounded-xl border border-gray-100 italic text-gray-600 relative">
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1 w-2 h-2 bg-gray-50 border-l border-b border-gray-100 rotate-45"></div>
                            "{{ $log->catatan }}"
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if($pengajuan->status === \App\Enums\StatusPengajuan::DISETUJUI || $pengajuan->status === \App\Enums\StatusPengajuan::SELESAI)
            <div class="relative z-10 flex gap-5 group">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 ring-4 ring-white mt-1 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <div class="text-sm font-bold text-emerald-700 leading-tight">Surat Diterbitkan</div>
                    <div class="text-[11px] font-semibold text-gray-500 mt-1.5">
                        Nomor : 
                        <span class="font-mono text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">
                            {{ $pengajuan->nomor_surat }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>