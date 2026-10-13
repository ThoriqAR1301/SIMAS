<x-app-layout>
    @section('page-title', 'Dashboard')
    @section('page-subtitle', 'Ringkasan aktivitas administrasi surat')

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

    <div class="space-y-6">

        <div class="card p-6 lg:p-8 bg-gradient-premium border-0 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-gold-400/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-32 w-48 h-48 rounded-full bg-brand-400/10 blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div>
                    <p class="text-gold-400 text-xs font-bold uppercase tracking-[0.15em] mb-2">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h2 class="text-2xl lg:text-3xl font-bold text-white tracking-tight">
                        Halo, {{ explode(' ', $user->name)[0] }} 👋
                    </h2>
                    <p class="text-ink-300 mt-2 max-w-xl">
                        @if($isGuru)
                            Anda Punya <span class="text-gold-400 font-semibold">{{ $draft }} Draft</span>
                            Dan <span class="text-gold-400 font-semibold">{{ $diproses }} Pengajuan</span> Yang Sedang Diproses
                        @else
                            Ada <span class="text-gold-400 font-semibold">{{ $menunggu }} Pengajuan</span>
                            Yang Menunggu Tindakan Anda
                        @endif
                    </p>
                </div>

                @if($isGuru)
                    <a href="{{ route('pengajuan.create') }}"
                       class="btn-gold btn-lg whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Pengajuan
                    </a>
                @else
                    <a href="{{ route('approval.index') }}"
                       class="btn-gold btn-lg whitespace-nowrap">
                        Review Approval
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
            @if($isGuru)
                <x-stat-card label="Total Pengajuan" :value="$totalPengajuan" color="brand" />
                <x-stat-card label="Draft" :value="$draft" color="gold" />
                <x-stat-card label="Sedang Diproses" :value="$diproses" color="blue" />
                <x-stat-card label="Disetujui" :value="$disetujui" color="emerald" />
            @else
                <x-stat-card label="Total Pengajuan" :value="$totalPengajuan" color="brand" />
                <x-stat-card label="Menunggu Anda" :value="$menunggu" color="gold" />
                <x-stat-card label="Disetujui Bulan Ini" :value="$disetujuiBulanIni" color="emerald" />
                <x-stat-card label="Ditolak" :value="$ditolak" color="red" />
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <div class="lg:col-span-2 card overflow-hidden">
                <div class="card-header">
                    <div>
                        <h3 class="font-bold text-brand-900 dark:text-ink-50">Pengajuan Terbaru</h3>
                        <p class="text-xs text-ink-500 mt-0.5">5 Aktivitas Terakhir</p>
                    </div>
                    <a href="{{ route('pengajuan.index') }}"
                       class="text-xs font-semibold text-brand-600 hover:text-brand-800 dark:text-gold-400">
                        Lihat Semua →
                    </a>
                </div>

                <div class="divide-y divide-ink-100 dark:divide-brand-800/50">
                    @forelse($pengajuanTerbaru as $p)
                        <a href="{{ route('pengajuan.show', $p) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gold-50/30 dark:hover:bg-brand-900/40 transition group">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-700 to-brand-900 flex items-center justify-center text-white text-xs font-bold group-hover:scale-105 transition">
                                {{ strtoupper(substr($p->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-sm text-brand-900 dark:text-ink-50 truncate">
                                    {{ $p->nama_kegiatan }}
                                </div>
                                <div class="text-xs text-ink-500 mt-0.5">
                                    {{ $p->nomor_pengajuan }} · {{ $p->user->name }}
                                </div>
                            </div>
                            <x-pengajuan.status-badge :status="$p->status" />
                        </a>
                    @empty
                        <x-empty-state
                            title="Belum Ada Pengajuan"
                            description="Pengajuan Akan Muncul Di Sini"
                            :action-label="$isGuru ? 'Buat Pengajuan' : null"
                            :action-url="$isGuru ? route('pengajuan.create') : null" />
                    @endforelse
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-bold text-brand-900 dark:text-ink-50 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    @if($isGuru)
                        <a href="{{ route('pengajuan.create') }}" class="nav-quick">
                            <span class="w-9 h-9 rounded-xl bg-brand-100 dark:bg-brand-900/60 flex items-center justify-center text-brand-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </span>
                            <span class="flex-1 text-sm font-semibold text-ink-700 dark:text-ink-200">Buat Pengajuan</span>
                        </a>
                        <a href="{{ route('pengajuan.index', ['status' => 'draft']) }}" class="nav-quick">
                            <span class="w-9 h-9 rounded-xl bg-gold-100 dark:bg-gold-900/30 flex items-center justify-center text-gold-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </span>
                            <span class="flex-1 text-sm font-semibold text-ink-700 dark:text-ink-200">Lanjutkan Draft</span>
                        </a>
                    @else
                        <a href="{{ route('approval.index') }}" class="nav-quick">
                            <span class="w-9 h-9 rounded-xl bg-gold-100 dark:bg-gold-900/30 flex items-center justify-center text-gold-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <span class="flex-1 text-sm font-semibold text-ink-700 dark:text-ink-200">Review Approval</span>
                            @if($menunggu > 0)
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gold-400 text-brand-900">
                                    {{ $menunggu }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('pengajuan.index') }}" class="nav-quick">
                            <span class="w-9 h-9 rounded-xl bg-brand-100 dark:bg-brand-900/60 flex items-center justify-center text-brand-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            <span class="flex-1 text-sm font-semibold text-ink-700 dark:text-ink-200">Semua Pengajuan</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        .nav-quick {
            @apply flex items-center gap-3 px-3 py-2.5 rounded-xl transition hover:bg-ink-50 dark:hover:bg-brand-900/60;
        }
    </style>
    @endpush
</x-app-layout>
