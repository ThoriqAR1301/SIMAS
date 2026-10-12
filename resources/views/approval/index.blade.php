<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Approval — {{ $step === 1 ? 'Verifikasi Admin TU' : 'Persetujuan Pimpinan' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">Nomor</th>
                            <th class="px-4 py-3 text-left">Pengaju</th>
                            <th class="px-4 py-3 text-left">Kegiatan</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pengajuan as $p)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $p->nomor_pengajuan }}</td>
                                <td class="px-4 py-3">{{ $p->user->name }}</td>
                                <td class="px-4 py-3">{{ $p->nama_kegiatan }}</td>
                                <td class="px-4 py-3">{{ $p->tanggal_pelaksanaan->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('pengajuan.show', $p) }}" class="text-blue-600 hover:underline">
                                        Review →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Tidak Ada Pengajuan Yang Menunggu
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $pengajuan->links() }}</div>
        </div>
    </div>
</x-app-layout>