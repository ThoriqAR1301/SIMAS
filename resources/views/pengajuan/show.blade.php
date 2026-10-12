<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Detail Pengajuan
            </h2>
            <a href="{{ route('pengajuan.index') }}" class="text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <div class="text-xs text-gray-500 uppercase">Nomor Pengajuan</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $pengajuan->nomor_pengajuan }}
                        </div>
                        @if($pengajuan->nomor_surat)
                            <div class="mt-2 text-xs text-gray-500 uppercase">Nomor Surat</div>
                            <div class="font-mono text-gray-800 dark:text-gray-200">
                                {{ $pengajuan->nomor_surat }}
                            </div>
                        @endif
                    </div>
                    <x-pengajuan.status-badge :status="$pengajuan->status" />
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Pengajuan</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Pengaju</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $pengajuan->user->name }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Jenis Surat</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $pengajuan->jenis_surat }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Nama Kegiatan</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $pengajuan->nama_kegiatan }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Tanggal Pelaksanaan</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            {{ $pengajuan->tanggal_pelaksanaan->translatedFormat('d F Y') }}
                            @if($pengajuan->waktu_mulai)
                                , {{ $pengajuan->waktu_mulai }}–{{ $pengajuan->waktu_selesai }}
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Tempat</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $pengajuan->tempat ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Blanko</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            {{ $pengajuan->blanko->nama }}
                        </dd>
                    </div>
                    @if($pengajuan->keterangan)
                        <div class="md:col-span-2">
                            <dt class="text-gray-500">Keterangan</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $pengajuan->keterangan }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            @if($pengajuan->lampiran->count())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Lampiran</h3>
                    <ul class="space-y-2">
                        @foreach($pengajuan->lampiran as $lap)
                            <li class="flex items-center gap-3 text-sm">
                                <x-heroicon-o-paper-clip class="w-4 h-4 text-gray-400" />
                                <a href="{{ $lap->url() }}" target="_blank" class="text-blue-600 hover:underline flex-1">
                                    {{ $lap->nama_file }}
                                </a>
                                <span class="text-gray-400 text-xs">{{ $lap->ukuranFormat() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @include('pengajuan.partials.timeline', ['pengajuan' => $pengajuan])

            @if(auth()->id() === $pengajuan->user_id)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-wrap gap-3">
                    @if($pengajuan->bisaDiedit())
                        <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="px-4 py-2 text-sm rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white">
                            Edit Draft
                        </a>
                    @endif

                    @if($pengajuan->bisaDiajukan())
                        <form action="{{ route('pengajuan.submit', $pengajuan) }}" method="POST" onsubmit="return confirm('Ajukan Pengajuan Ini?')">
                            @csrf
                            <button class="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                                Ajukan Sekarang
                            </button>
                        </form>
                    @endif

                    @if($pengajuan->bisaDihapus())
                        <form action="{{ route('pengajuan.destroy', $pengajuan) }}" method="POST" onsubmit="return confirm('Hapus Pengajuan Ini?')">
                            @csrf @method('DELETE')
                            <button class="px-4 py-2 text-sm rounded-lg bg-red-600 hover:bg-red-700 text-white">
                                Hapus
                            </button>
                        </form>
                    @endif

                    @if($pengajuan->sudahDisetujui())
                        <a href="{{ route('pengajuan.pdf', $pengajuan) }}" class="px-4 py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white">
                            Download PDF
                        </a>
                    @endif
                </div>
            @endif

            @if(in_array(auth()->user()->role?->name, ['admin_tu', 'pimpinan']))
                @php
                    $bisaAksi = ($pengajuan->current_step === 1 && auth()->user()->isAdminTu()) || ($pengajuan->current_step === 2 && auth()->user()->isPimpinan());
                @endphp

                @if($bisaAksi)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-data="{ modal: null }">
                        <h3 class="font-medium mb-3">Aksi Anda</h3>
                        <div class="flex flex-wrap gap-2">
                            <button @click="modal = 'approve'" class="px-4 py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white">
                                {{ $pengajuan->current_step === 1 ? 'Verifikasi & Teruskan' : 'Setujui Final' }}
                            </button>
                            <button @click="modal = 'revisi'" class="px-4 py-2 text-sm rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white">
                                Minta Revisi
                            </button>
                            <button @click="modal = 'reject'" class="px-4 py-2 text-sm rounded-lg bg-red-600 hover:bg-red-700 text-white">
                                Tolak
                            </button>
                        </div>

                        <div x-show="modal === 'approve'" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                            <div @click.outside="modal = null" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                                <h4 class="font-medium mb-3">Konfirmasi</h4>
                                <form method="POST" action="{{ $pengajuan->current_step === 1 ? route('approval.verify', $pengajuan) : route('approval.approve', $pengajuan) }}">
                                    @csrf
                                    <textarea name="catatan" rows="3" placeholder="Catatan (Opsional)" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 mb-3"></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="modal = null" class="px-3 py-2 text-sm rounded-lg border">Batal</button>
                                        <button class="px-3 py-2 text-sm rounded-lg bg-green-600 text-white">
                                            Konfirmasi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div x-show="modal === 'revisi'" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                            <div @click.outside="modal = null" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                                <h4 class="font-medium mb-3">Minta Revisi</h4>
                                <form method="POST" action="{{ route('approval.revisi', $pengajuan) }}">
                                    @csrf
                                    <textarea name="catatan" rows="3" required placeholder="Catatan Revisi" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 mb-3"></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="modal = null" class="px-3 py-2 text-sm rounded-lg border">Batal</button>
                                        <button class="px-3 py-2 text-sm rounded-lg bg-yellow-500 text-white">
                                            Kirim
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div x-show="modal === 'reject'" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                            <div @click.outside="modal = null" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                                <h4 class="font-medium mb-3">Tolak Pengajuan</h4>
                                <form method="POST" action="{{ route('approval.reject', $pengajuan) }}">
                                    @csrf
                                    <textarea name="catatan" rows="3" required placeholder="Alasan Penolakan" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 mb-3"></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="modal = null" class="px-3 py-2 text-sm rounded-lg border">Batal</button>
                                        <button class="px-3 py-2 text-sm rounded-lg bg-red-600 text-white">
                                            Tolak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>