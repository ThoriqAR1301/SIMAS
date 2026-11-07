<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-4">Riwayat Approval</h3>

    <ol class="relative border-l-2 border-gray-200 dark:border-gray-700 ml-3 space-y-6">
        <li class="ml-6">
            <span class="absolute -left-[9px] w-4 h-4 rounded-full bg-blue-500 ring-4 ring-white dark:ring-gray-800"></span>
            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                Pengajuan Dibuat
            </div>
            <div class="text-xs text-gray-500">
                {{ $pengajuan->created_at->format('d M Y H:i') }}
                • Oleh {{ $pengajuan->user->name }}
            </div>
        </li>

        @foreach($pengajuan->approvalLogs as $log)
            @php
                $warna = match($log->aksi) {
                    'approve' => 'green',
                    'reject' => 'red',
                    'revisi' => 'yellow',
                    default => 'gray',
                };
            @endphp
            <li class="ml-6">
                <span class="absolute -left-[9px] w-4 h-4 rounded-full bg-{{ $warna }}-500 ring-4 ring-white dark:ring-gray-800"></span>
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $log->labelAksi() }} — {{ $log->labelStep() }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ $log->acted_at->format('d M Y H:i') }}
                    • Oleh {{ $log->approver->name }}
                </div>
                @if($log->catatan)
                    <div class="mt-2 text-xs bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-gray-700 dark:text-gray-300">
                        {{ $log->catatan }}
                    </div>
                @endif
            </li>
        @endforeach

        @if($pengajuan->status === \App\Enums\StatusPengajuan::DISETUJUI
            || $pengajuan->status === \App\Enums\StatusPengajuan::SELESAI)
            <li class="ml-6">
                <span class="absolute -left-[9px] w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-white dark:ring-gray-800"></span>
                <div class="text-sm font-medium text-emerald-700 dark:text-emerald-400">
                    Surat Disetujui
                </div>
                <div class="text-xs text-gray-500">
                    Nomor Surat : {{ $pengajuan->nomor_surat }}
                </div>
            </li>
        @endif
    </ol>
</div>