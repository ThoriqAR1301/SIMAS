<x-app-layout>
    <x-slot name="title">Approval</x-slot>
    <x-slot name="subtitle">{{ $step === 1 ? 'Tahap Verifikasi — Admin TU' : 'Tahap Persetujuan Final — Pimpinan' }}</x-slot>

    @php
        $user = auth()->user();
        $tabs = [
            ['key' => null, 'label' => 'Semua', 'count' => $pengajuan->total()],
            ['key' => 'segera', 'label' => 'Urgent', 'count' => 0],
        ];
    @endphp

    <div class="max-w-7xl mx-auto space-y-6"
         x-data="{ selected: null, bulk: [] }">

        <div class="relative rounded-3xl overflow-hidden noise bg-gradient-premium shadow-lift ring-1 ring-white/5">
            <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-gold-400/15 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-brand-400/10 blur-3xl"></div>

            <div class="relative p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-400/15 text-gold-300 text-[10px] font-bold uppercase tracking-widest mb-4 ring-1 ring-gold-400/20">
                            <span class="glow-dot"></span>
                            {{ $step === 1 ? 'Step 1 dari 2' : 'Step 2 dari 2 · Final' }}
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                            {{ $step === 1 ? 'Verifikasi Pengajuan' : 'Persetujuan Pimpinan' }}
                        </h1>
                        <p class="text-ink-300 mt-2 max-w-lg text-sm">
                            {{ $step === 1
                                ? 'Periksa Kelengkapan & Validitas Pengajuan Sebelum Diteruskan Ke Pimpinan'
                                : 'Beri Persetujuan Final. Nomor Surat & QR Code Akan Di-Generate Otomatis' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="metric relative px-6 py-4 rounded-2xl bg-white/[0.06] backdrop-blur ring-1 ring-white/10 text-gold-400">
                            <div class="text-4xl font-black tracking-tight text-white">
                                {{ number_format($pengajuan->total()) }}
                            </div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gold-400 mt-1">
                                Menunggu
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
            <a href="{{ route('approval.index') }}" class="pill {{ !request('prioritas') ? 'pill-active' : 'pill-inactive bg-white dark:bg-brand-900' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Semua
                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-white/20">{{ $pengajuan->total() }}</span>
            </a>
            <a href="{{ route('approval.index', ['prioritas' => 'segera']) }}" class="pill {{ request('prioritas') === 'segera' ? 'pill-active' : 'pill-inactive bg-white dark:bg-brand-900' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Urgent
            </a>
            <a href="{{ route('approval.index', ['dari' => now()->subDays(7)->format('Y-m-d')]) }}" class="pill pill-inactive bg-white dark:bg-brand-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                7 Hari Terakhir
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <div class="lg:col-span-3 space-y-3">
                @forelse($pengajuan as $p)
                    <div class="group relative">
                        <a href="{{ route('pengajuan.show', $p) }}" @mouseenter="selected = {{ $p->id }}" :class="selected === {{ $p->id }} ? 'ring-2 ring-gold-400/50 border-gold-300 dark:border-gold-700' : ''" class="block card-hover p-5 overflow-hidden transition-all duration-300">

                            @if($p->prioritas === 'segera' ?? false)
                                <div class="ribbon bg-gradient-to-r from-red-500 to-red-700">URGENT</div>
                            @endif

                            <div class="flex items-start gap-4">
                                <div class="relative flex-shrink-0">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-700 to-brand-900 flex items-center justify-center text-white font-bold text-lg ring-2 ring-white dark:ring-brand-800 group-hover:scale-105 group-hover:ring-gold-400/50 transition-all duration-300">
                                        {{ strtoupper(substr($p->user->name, 0, 2)) }}
                                    </div>
                                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-gradient-gold ring-2 ring-white dark:ring-brand-800 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-brand-900" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-bold text-brand-900 dark:text-ink-50 text-base truncate group-hover:text-gold-600 dark:group-hover:text-gold-400 transition">
                                                {{ $p->nama_kegiatan }}
                                            </h3>
                                            <p class="text-xs text-ink-500 mt-0.5">
                                                {{ $p->user->name }} · {{ $p->user->jabatan }}
                                            </p>
                                        </div>
                                        <svg class="w-5 h-5 text-ink-300 group-hover:text-gold-500 group-hover:translate-x-1 transition-all flex-shrink-0"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>

                                    <div class="grid grid-cols-3 gap-3 mt-4 pt-4 border-t border-ink-100 dark:border-brand-800">
                                        <div>
                                            <div class="text-[10px] font-bold uppercase tracking-widest text-ink-400 mb-0.5">
                                                Nomor
                                            </div>
                                            <div class="font-mono text-xs font-semibold text-brand-700 dark:text-gold-400 truncate">
                                                {{ $p->nomor_pengajuan }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-bold uppercase tracking-widest text-ink-400 mb-0.5">
                                                Tanggal
                                            </div>
                                            <div class="text-xs font-semibold text-ink-700 dark:text-ink-200">
                                                {{ $p->tanggal_pelaksanaan->translatedFormat('d M') }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-bold uppercase tracking-widest text-ink-400 mb-0.5">
                                                Jenis
                                            </div>
                                            <div class="text-xs font-semibold text-ink-700 dark:text-ink-200 truncate">
                                                {{ $p->jenis_surat }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="card">
                        <x-empty-state title="Semua Sudah Diproses 🎉" description="Tidak Ada Pengajuan Yang Menunggu Approval Anda Saat Ini" action-label="Lihat Semua Pengajuan" :action-url="route('pengajuan.index')" />
                    </div>
                @endforelse

                @if($pengajuan->hasPages())
                    <div class="mt-4">{{ $pengajuan->links() }}</div>
                @endif
            </div>

            <div class="lg:col-span-2 hidden lg:block">
                <div class="lg:sticky lg:top-24">
                    <template x-if="!selected">
                        <div class="card p-8 text-center">
                            <div class="w-16 h-16 mx-auto rounded-3xl bg-gradient-gold/20 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-brand-900 dark:text-ink-50 mb-1">Preview</h3>
                            <p class="text-sm text-ink-500">
                                Klik Salah Satu Pengajuan Untuk Melihat Preview Detail Di Sini
                            </p>
                        </div>
                    </template>

                    @foreach($pengajuan as $p)
                        <div x-show="selected === {{ $p->id }}" x-cloak x-transition.opacity>
                            <div class="card overflow-hidden">
                                <div class="bg-gradient-premium p-6 text-white relative noise">
                                    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-gold-400/20 blur-2xl"></div>
                                    <div class="relative">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-gold-400 mb-1">
                                            Preview Pengajuan
                                        </p>
                                        <h3 class="text-lg font-bold leading-tight text-balance">
                                            {{ $p->nama_kegiatan }}
                                        </h3>
                                        <div class="mt-3">
                                            <x-pengajuan.status-badge :status="$p->status" />
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 space-y-4">
                                    <dl class="space-y-3 text-sm">
                                        <div class="flex justify-between gap-3">
                                            <dt class="text-ink-500">Pengaju</dt>
                                            <dd class="font-semibold text-right">{{ $p->user->name }}</dd>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <dt class="text-ink-500">Nomor</dt>
                                            <dd class="font-mono text-xs font-semibold text-right text-brand-700 dark:text-gold-400">
                                                {{ $p->nomor_pengajuan }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <dt class="text-ink-500">Jenis</dt>
                                            <dd class="font-semibold text-right">{{ $p->jenis_surat }}</dd>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <dt class="text-ink-500">Tanggal</dt>
                                            <dd class="font-semibold text-right">
                                                {{ $p->tanggal_pelaksanaan->translatedFormat('d F Y') }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <dt class="text-ink-500">Tempat</dt>
                                            <dd class="font-semibold text-right">{{ $p->tempat ?? '—' }}</dd>
                                        </div>
                                    </dl>

                                    <a href="{{ route('pengajuan.show', $p) }}" class="btn-primary w-full btn-shine">
                                        Buka Detail Lengkap
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>