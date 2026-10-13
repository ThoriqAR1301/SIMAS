<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Daftar Pengajuan Surat
            </h2>
            @can('create', App\Models\PengajuanSurat::class)
                <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Buat Pengajuan
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari Nomor / Kegiatan..." class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">

                    <select name="status"
                        class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">Semua Status</option>
                        @foreach(\App\Enums\StatusPengajuan::cases() as $s)
                            <option value="{{ $s->value }}" @selected(($filters['status'] ?? '') === $s->value)>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="dari" value="{{ $filters['dari'] ?? '' }}" class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">

                    <div class="flex gap-2">
                        <input type="date" name="sampai" value="{{ $filters['sampai'] ?? '' }}" class="flex-1 rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm rounded-lg transition">
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">Nomor</th>
                            <th class="px-4 py-3 text-left">Pengaju</th>
                            <th class="px-4 py-3 text-left">Kegiatan</th>
                            <th class="px-4 py-3 text-left">Tgl Pelaksanaan</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pengajuan as $p)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $p->nomor_pengajuan }}
                                    </div>
                                    @if($p->nomor_surat)
                                        <div class="text-xs text-gray-500">
                                            Surat: {{ $p->nomor_surat }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $p->user->name }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-gray-900 dark:text-gray-100">{{ $p->nama_kegiatan }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->jenis_surat }}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $p->tanggal_pelaksanaan->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <x-pengajuan.status-badge :status="$p->status" />
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('pengajuan.show', $p) }}"
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-empty-state
                                        title="Belum Ada Pengajuan"
                                        description="Mulai Dengan Membuat Pengajuan Surat Pertama Anda"
                                        action-label="Buat Pengajuan"
                                        action-url="{{ route('pengajuan.create') }}" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pengajuan->links() }}
            </div>
        </div>
    </div>
</x-app-layout>