<x-app-layout>
    <x-slot name="title">Detail Pengajuan</x-slot>
    <x-slot name="subtitle">{{ $pengajuan->nomor_pengajuan }}</x-slot>

    @php
        $isOwner = auth()->id() === $pengajuan->user_id;
        $isAdmin = auth()->user()->isAdminTu();
        $isPimpinan = auth()->user()->isPimpinan();
        $bisaAksi = ($pengajuan->current_step === 1 && $isAdmin)
                 || ($pengajuan->current_step === 2 && $isPimpinan);
    @endphp

    <div class="max-w-7xl mx-auto space-y-6" x-data="{ showReject: false, showRevisi: false, action: null }">

        <div class="relative rounded-3xl overflow-hidden noise bg-gradient-premium shadow-lift ring-1 ring-white/5">

            <div class="absolute -top-24 -right-16 w-80 h-80 rounded-full bg-gold-400/15 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-16 w-72 h-72 rounded-full bg-brand-400/10 blur-3xl"></div>

            <div class="relative p-6 lg:p-8">
                <nav class="flex items-center gap-2 text-xs text-ink-400 mb-5">
                    <a href="{{ route('pengajuan.index') }}" class="hover:text-gold-400 transition">
                        Pengajuan
                    </a>
                    <svg class="w-3 h-3 opacity-40" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-ink-300">{{ $pengajuan->nomor_pengajuan }}</span>
                </nav>

                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-4 flex-wrap">
                            <x-pengajuan.status-badge :status="$pengajuan->status" size="lg" />
                            @if($pengajuan->current_step > 0 && $pengajuan->current_step < 3)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/10 text-gold-300 border border-white/10">
                                    <span class="glow-dot"></span>
                                    Menunggu {{ $pengajuan->sedangMenungguSiapa() }}
                                </span>
                            @endif
                        </div>

                        <h1 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight text-balance">
                            {{ $pengajuan->nama_kegiatan }}
                        </h1>

                        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
                            <div class="flex items-center gap-2 text-ink-300">
                                <div class="w-7 h-7 rounded-lg bg-gradient-gold flex items-center justify-center text-brand-900 font-bold text-[10px]">
                                    {{ strtoupper(substr($pengajuan->user->name, 0, 2)) }}
                                </div>
                                <span class="text-white font-semibold">{{ $pengajuan->user->name }}</span>
                            </div>
                            <span class="text-ink-500">·</span>
                            <span class="text-ink-300">{{ $pengajuan->jenis_surat }}</span>
                            <span class="text-ink-500">·</span>
                            <span class="text-ink-300 font-mono text-xs">
                                {{ $pengajuan->tanggal_pelaksanaan->translatedFormat('d M Y') }}
                            </span>
                        </div>

                        @if($pengajuan->nomor_surat)
                            <div class="mt-6 inline-flex items-center gap-3 px-4 py-3 rounded-2xl bg-white/[0.06] backdrop-blur ring-1 ring-gold-400/20">
                                <div class="w-9 h-9 rounded-xl bg-gradient-gold flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-gold-400">
                                        Nomor Surat
                                    </div>
                                    <div class="font-mono font-bold text-white text-lg tracking-tight">
                                        {{ $pengajuan->nomor_surat }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($pengajuan->qr_token && $pengajuan->sudahDisetujui())
                        <div class="flex-shrink-0 self-start lg:self-end">
                            <div class="qr-frame w-28 h-28 flex items-center justify-center">
                                <img src="{{ Storage::disk('public')->url('qr/' . $pengajuan->qr_token . '.png') }}" alt="QR Code" class="w-full h-full object-contain rounded-xl">
                            </div>
                            <p class="text-[10px] text-ink-400 text-center mt-2 font-medium">Scan Verifikasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="paper corner-accent rounded-3xl p-6 lg:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-700 to-brand-900 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-900 dark:text-ink-50 tracking-tight">Preview Surat</h3>
                                <p class="text-xs text-ink-500">Data Yang Akan Tercetak Di Surat</p>
                            </div>
                        </div>
                        @if($pengajuan->sudahDisetujui())
                            <a href="{{ route('pengajuan.pdf', $pengajuan) }}" class="btn-gold btn-sm btn-shine">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh PDF
                            </a>
                        @endif
                    </div>

                    <div class="font-serif space-y-5 text-[15px] leading-relaxed">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            @php
                                $fields = [
                                    ['Kepada', $pengajuan->user->name],
                                    ['NIP', $pengajuan->user->nip ?? '—'],
                                    ['Jabatan', $pengajuan->user->jabatan ?? '—'],
                                    ['Jenis Surat', $pengajuan->jenis_surat],
                                    ['Tanggal', $pengajuan->tanggal_pelaksanaan->translatedFormat('d F Y')],
                                    ['Waktu', $pengajuan->waktu_mulai
                                        ? $pengajuan->waktu_mulai . ' – ' . $pengajuan->waktu_selesai
                                        : '—'],
                                    ['Tempat', $pengajuan->tempat ?? '—'],
                                    ['Blanko', $pengajuan->blanko->nama],
                                ];
                            @endphp

                            @foreach($fields as [$label, $val])
                                <div class="pb-3 border-b border-dashed border-brand-900/10 dark:border-white/10">
                                    <dt class="text-[10px] font-bold uppercase tracking-widest text-ink-400 mb-1">
                                        {{ $label }}
                                    </dt>
                                    <dd class="font-medium text-brand-900 dark:text-ink-100">{{ $val }}</dd>
                                </div>
                            @endforeach

                            @if($pengajuan->keterangan)
                                <div class="md:col-span-2 pt-2">
                                    <dt class="text-[10px] font-bold uppercase tracking-widest text-ink-400 mb-1">
                                        Keterangan
                                    </dt>
                                    <dd class="font-medium text-brand-900 dark:text-ink-100 leading-relaxed">
                                        {{ $pengajuan->keterangan }}
                                    </dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($pengajuan->lampiran->count())
                    <div class="card">
                        <div class="card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center shadow-glow-gold">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-brand-900 dark:text-ink-50">Lampiran</h3>
                                    <p class="text-xs text-ink-500">{{ $pengajuan->lampiran->count() }} File Terunggah</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($pengajuan->lampiran as $lap)
                                <a href="{{ $lap->url() }}" target="_blank" class="group flex items-center gap-3 p-3 rounded-2xl bg-ink-50 dark:bg-brand-900/40 hover:bg-gold-50 dark:hover:bg-gold-900/20 border border-transparent hover:border-gold-200 dark:hover:border-gold-800/50 transition-all duration-200">
                                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-brand-900 flex items-center justify-center shadow-sm flex-shrink-0 group-hover:scale-105 transition">
                                        <svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-brand-900 dark:text-ink-100 truncate">
                                            {{ $lap->nama_file }}
                                        </p>
                                        <p class="text-xs text-ink-500">{{ $lap->ukuranFormat() }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-ink-400 group-hover:text-gold-600 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-premium flex items-center justify-center shadow-soft">
                            <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-brand-900 dark:text-ink-50 tracking-tight">Riwayat Approval</h3>
                            <p class="text-xs text-ink-500">Jejak Audit Lengkap Pengajuan Ini</p>
                        </div>
                    </div>

                    <div class="timeline-vertical space-y-6 pl-2">

                        <div class="timeline-step timeline-step-active">
                            <div class="timeline-step-icon bg-gradient-gold">
                                <svg class="w-5 h-5 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div class="flex-1 pt-2 pb-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-bold text-brand-900 dark:text-ink-50">Pengajuan Dibuat</h4>
                                    <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full bg-brand-100 text-brand-700 dark:bg-brand-900/60 dark:text-brand-300">
                                        Draft
                                    </span>
                                </div>
                                <p class="text-sm text-ink-500 mt-1">
                                    Oleh <span class="font-semibold text-ink-700 dark:text-ink-200">{{ $pengajuan->user->name }}</span>
                                </p>
                                <p class="text-xs text-ink-400 mt-1 font-mono">
                                    {{ $pengajuan->created_at->translatedFormat('d M Y · H:i') }}
                                </p>
                            </div>
                        </div>

                        @foreach($pengajuan->approvalLogs as $log)
                            @php
                                $variant = match($log->aksi) {
                                    'approve' => ['from-emerald-400', 'to-emerald-600', 'M5 13l4 4L19 7', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
                                    'reject' => ['from-red-400', 'to-red-600', 'M6 18L18 6M6 6l12 12', 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'],
                                    'revisi' => ['from-gold-400', 'to-gold-600', 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'bg-gold-100 text-gold-800 dark:bg-gold-900/40 dark:text-gold-300'],
                                    default => ['from-ink-400', 'to-ink-600', 'M12 8v4l3 3', 'bg-ink-100 text-ink-700'],
                                };
                            @endphp
                            <div class="timeline-step">
                                <div class="timeline-step-icon bg-gradient-to-br {{ $variant[0] }} {{ $variant[1] }}">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $variant[2] }}"/>
                                    </svg>
                                </div>
                                <div class="flex-1 pt-2 pb-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-bold text-brand-900 dark:text-ink-50">
                                            {{ $log->labelAksi() }} — {{ $log->labelStep() }}
                                        </h4>
                                        <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full {{ $variant[3] }}">
                                            {{ ucfirst($log->aksi) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-ink-500 mt-1">
                                        Oleh <span class="font-semibold text-ink-700 dark:text-ink-200">{{ $log->approver->name }}</span>
                                    </p>
                                    <p class="text-xs text-ink-400 mt-1 font-mono">
                                        {{ $log->acted_at->translatedFormat('d M Y · H:i') }}
                                    </p>
                                    @if($log->catatan)
                                        <div class="mt-3 p-4 rounded-2xl bg-gradient-to-br from-ink-50 to-white dark:from-brand-900/60 dark:to-brand-900/30 border-l-4 border-gold-400">
                                            <p class="text-sm text-ink-700 dark:text-ink-200 italic leading-relaxed">
                                                "{{ $log->catatan }}"
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($pengajuan->sudahDisetujui())
                            <div class="timeline-step">
                                <div class="timeline-step-icon bg-gradient-to-br from-gold-400 to-gold-600" style="box-shadow: 0 0 0 4px rgba(255,255,255,1), 0 0 0 5px rgba(244,178,46,0.4), 0 0 24px rgba(244,178,46,0.5);">
                                    <svg class="w-5 h-5 text-brand-900" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1 pt-2 pb-2">
                                    <h4 class="font-bold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                                        Surat Disetujui & Diterbitkan
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </h4>
                                    <p class="text-sm text-ink-500 mt-1">
                                        Nomor : <span class="font-mono font-semibold text-brand-700 dark:text-gold-400">{{ $pengajuan->nomor_surat }}</span>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 space-y-5">

                    @if($isOwner)
                        <div class="card p-6 border-gradient">
                            <h3 class="font-bold text-brand-900 dark:text-ink-50 mb-4 flex items-center gap-2">
                                <span class="glow-dot"></span>
                                Tindakan Anda
                            </h3>
                            <div class="space-y-2">
                                @if($pengajuan->bisaDiedit())
                                    <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="btn-outline w-full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Draft
                                    </a>
                                @endif
                                @if($pengajuan->bisaDiajukan())
                                    <form action="{{ route('pengajuan.submit', $pengajuan) }}" method="POST" onsubmit="return confirm('Ajukan Pengajuan Ini Sekarang?')">
                                        @csrf
                                        <button class="btn-gold w-full btn-shine">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Ajukan Sekarang
                                        </button>
                                    </form>
                                @endif
                                @if($pengajuan->bisaDihapus())
                                    <form action="{{ route('pengajuan.destroy', $pengajuan) }}" method="POST" onsubmit="return confirm('Hapus Pengajuan Ini? Tindakan Tidak Bisa Dibatalkan')">
                                        @csrf @method('DELETE')
                                        <button class="btn-ghost w-full text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus Draft
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($bisaAksi)
                        <div class="relative rounded-3xl overflow-hidden bg-gradient-premium noise shadow-lift ring-1 ring-gold-400/20">
                            <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-gold-400/20 blur-3xl"></div>
                            <div class="relative p-6">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="glow-dot"></span>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gold-400">
                                        Tahap Anda
                                    </p>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-1">
                                    {{ $pengajuan->current_step === 1 ? 'Verifikasi Pengajuan' : 'Persetujuan Final' }}
                                </h3>
                                <p class="text-xs text-ink-300 mb-5">
                                    {{ $pengajuan->current_step === 1
                                        ? 'Periksa Kelengkapan Data Sebelum Diteruskan Ke Pimpinan'
                                        : 'Setujui Untuk Generate Nomor Surat & QR Code' }}
                                </p>

                                <div class="space-y-2">
                                    <form method="POST" action="{{ $pengajuan->current_step === 1 ? route('approval.verify', $pengajuan) : route('approval.approve', $pengajuan) }}">
                                        @csrf
                                        <button class="btn-gold w-full btn-shine">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ $pengajuan->current_step === 1 ? 'Verifikasi & Teruskan' : 'Setujui Final' }}
                                        </button>
                                    </form>

                                    <button type="button" @click="showRevisi = true" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-white/[0.06] text-gold-300 hover:bg-white/[0.12] transition ring-1 ring-inset ring-white/10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Minta Revisi
                                    </button>

                                    <button type="button" @click="showReject = true" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-red-500/20 text-red-300 hover:bg-red-500/30 transition ring-1 ring-inset ring-red-500/30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card p-6 space-y-5">
                        <h3 class="font-bold text-brand-900 dark:text-ink-50">Informasi</h3>

                        @php
                            $metas = [
                                ['Pembuat', $pengajuan->user->name],
                                ['Dibuat', $pengajuan->created_at->translatedFormat('d M Y · H:i')],
                                ['Diperbarui', $pengajuan->updated_at->diffForHumans()],
                            ];
                            if ($pengajuan->reviewer) {
                                $metas[] = ['Reviewer', $pengajuan->reviewer->name];
                                $metas[] = ['Direview', $pengajuan->reviewed_at?->translatedFormat('d M Y · H:i')];
                            }
                            if ($pengajuan->qr_generated_at) {
                                $metas[] = ['QR Dibuat', $pengajuan->qr_generated_at->translatedFormat('d M Y · H:i')];
                            }
                        @endphp

                        <dl class="space-y-3">
                            @foreach($metas as [$label, $val])
                                <div>
                                    <dt class="text-[10px] font-bold uppercase tracking-widest text-ink-400 mb-0.5">
                                        {{ $label }}
                                    </dt>
                                    <dd class="text-sm font-medium text-ink-700 dark:text-ink-200">{{ $val }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showReject" x-cloak class="fixed inset-0 bg-brand-950/70 backdrop-blur-md flex items-center justify-center z-50 p-4"
             x-transition.opacity>
            <div @click.outside="showReject = false" class="bg-white dark:bg-brand-900 rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate-in ring-1 ring-red-500/20">
                <div class="bg-gradient-to-br from-red-500 to-red-700 p-6 text-white">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold">Tolak Pengajuan</h3>
                    <p class="text-sm text-red-100 mt-1">Pengajuan Akan Dikembalikan Ke Guru</p>
                </div>
                <form method="POST" action="{{ route('approval.reject', $pengajuan) }}" class="p-6">
                    @csrf
                    <label class="label label-required">Alasan Penolakan</label>
                    <textarea name="catatan" rows="4" required placeholder="Jelaskan Alasan Penolakan Dengan Jelas..." class="input"></textarea>
                    <div class="flex justify-end gap-2 mt-5">
                        <button type="button" @click="showReject = false" class="btn-ghost">Batal</button>
                        <button class="btn-danger">Tolak Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showRevisi" x-cloak class="fixed inset-0 bg-brand-950/70 backdrop-blur-md flex items-center justify-center z-50 p-4"
             x-transition.opacity>
            <div @click.outside="showRevisi = false" class="bg-white dark:bg-brand-900 rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate-in ring-1 ring-gold-400/20">
                <div class="bg-gradient-gold p-6 text-brand-900">
                    <div class="w-12 h-12 rounded-2xl bg-white/30 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold">Minta Revisi</h3>
                    <p class="text-sm text-brand-800 mt-1">Pengajuan Dikembalikan Ke Status Draft</p>
                </div>
                <form method="POST" action="{{ route('approval.revisi', $pengajuan) }}" class="p-6">
                    @csrf
                    <label class="label label-required">Catatan Revisi</label>
                    <textarea name="catatan" rows="4" required placeholder="Bagian Mana Yang Perlu Diperbaiki?" class="input"></textarea>
                    <div class="flex justify-end gap-2 mt-5">
                        <button type="button" @click="showRevisi = false" class="btn-ghost">Batal</button>
                        <button class="btn-gold">Kirim Permintaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>