<x-app-layout>
    @section('page-title', 'Pengajuan Surat')
    @section('page-subtitle', 'Kelola Semua Pengajuan Surat Anda')

    <div class="space-y-5">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-sm text-ink-500">
                <span class="inline-flex items-center gap-1.5">
                    <span class="glow-dot"></span>
                    {{ $pengajuan->total() }} Pengajuan Ditemukan
                </span>
            </div>
            @can('create', App\Models\PengajuanSurat::class)
                <a href="{{ route('pengajuan.create') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Pengajuan
                </a>
            @endcan
        </div>

        <form method="GET" class="card p-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="label">Cari</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nomor, Kegiatan, Atau Jenis Surat..." class="input pl-11">
                    </div>
                </div>

                <div>
                    <label class="label">Status</label>
                    <select name="status" class="input">
                        <option value="">Semua Status</option>
                        @foreach(\App\Enums\StatusPengajuan::cases() as $s)
                            <option value="{{ $s->value }}" @selected(($filters['status'] ?? '') === $s->value)>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label">Rentang Tanggal</label>
                    <div class="flex gap-2">
                        <input type="date" name="dari" value="{{ $filters['dari'] ?? '' }}" class="input">
                        <input type="date" name="sampai" value="{{ $filters['sampai'] ?? '' }}" class="input">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-4 pt-4 border-t border-ink-100 dark:border-brand-800">
                <a href="{{ route('pengajuan.index') }}" class="btn-ghost btn-sm">Reset</a>
                <button type="submit" class="btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Terapkan
                </button>
            </div>
        </form>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Pengaju</th>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $p)
                            <tr>
                                <td>
                                    <div class="font-mono text-xs font-semibold text-brand-700 dark:text-gold-400">
                                        {{ $p->nomor_pengajuan }}
                                    </div>
                                    @if($p->nomor_surat)
                                        <div class="font-mono text-[10px] text-ink-400 mt-0.5">
                                            {{ $p->nomor_surat }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-700 to-brand-900 flex items-center justify-center text-white text-[10px] font-bold">
                                            {{ strtoupper(substr($p->user->name, 0, 2)) }}
                                        </div>
                                        <span class="font-medium text-sm">{{ $p->user->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-semibold text-sm text-brand-900 dark:text-ink-50">
                                        {{ $p->nama_kegiatan }}
                                    </div>
                                    <div class="text-xs text-ink-500 mt-0.5">{{ $p->jenis_surat }}</div>
                                </td>
                                <td>
                                    <div class="text-sm">{{ $p->tanggal_pelaksanaan->translatedFormat('d M Y') }}</div>
                                    @if($p->waktu_mulai)
                                        <div class="text-xs text-ink-500 mt-0.5">
                                            {{ $p->waktu_mulai }}–{{ $p->waktu_selesai }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <x-pengajuan.status-badge :status="$p->status" />
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('pengajuan.show', $p) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-800 dark:text-gold-400 dark:hover:text-gold-300">
                                        Detail
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
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
                                        :action-url="route('pengajuan.create')" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengajuan->hasPages())
                <div class="px-6 py-4 border-t border-ink-100 dark:border-brand-800">
                    {{ $pengajuan->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>