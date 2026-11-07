
<x-app-layout>
    <x-slot name="title">Riwayat Pengajuan Surat Perizinan Sakit</x-slot>
    <x-slot name="subtitle">Pantau perkembangan pengajuan izin sakit Anda</x-slot>

    <div class="min-h-screen px-4 py-8 sm:px-6 lg:px-8"
         style="background-color: #F5F3EC;">

        <div class="mx-auto max-w-5xl space-y-6">

            {{-- HEADER --}}
            <section
                class="relative overflow-hidden rounded-2xl p-6 shadow-lg sm:p-8"
                style="background: linear-gradient(120deg, #1E2B55 0%, #293B70 100%);">

                <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full"
                     style="background: rgba(242,181,29,0.10);">
                </div>

                <div class="relative z-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

                    <div class="max-w-2xl">
                        <div class="mb-3 inline-flex items-center gap-2 rounded-full px-3 py-1.5"
                             style="background: rgba(242,181,29,0.15); border: 1px solid rgba(242,181,29,0.35);">
                            <span class="h-2 w-2 rounded-full"
                                  style="background-color: #F2B51D;"></span>
                            <span class="text-xs font-semibold tracking-wider"
                                  style="color: #F2B51D;">
                                LAYANAN ADMINISTRASI SEKOLAH
                            </span>
                        </div>

                        <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            Riwayat Pengajuan
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed sm:text-base"
                           style="color: #E0E7F5;">
                            Lihat seluruh pengajuan izin sakit yang telah Anda kirim
                            dan pantau status pemeriksaannya oleh pihak Tata Usaha.
                        </p>
                    </div>

                    <a href="{{ route('surat-perizinan-sakit.create') }}"
                       class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold transition hover:brightness-110"
                       style="background-color: #F2B51D; color: #1E2B55;">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2.5"
                                  d="M12 5v14m-7-7h14"/>
                        </svg>

                        Buat Pengajuan
                    </a>

                </div>
            </section>

            {{-- DAFTAR PENGAJUAN --}}
            <section class="space-y-5">

                <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-lg font-bold" style="color: #1E2B55;">
                            Daftar Pengajuan Anda
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Pengajuan terbaru ditampilkan terlebih dahulu.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center rounded-full px-3 py-1.5 text-xs font-semibold"
                          style="background-color: #E9EDF7; color: #293B70;">
                        {{ $pengajuan->count() }} Pengajuan
                    </span>
                </div>

                @php
                    $statusLabels = [
                        \App\Models\SuratPerizinanSakit::STATUS_MENUNGGU => [
                            'Menunggu',
                            '#FFF4D6',
                            '#946600',
                            '#E7BD58'
                        ],
                        \App\Models\SuratPerizinanSakit::STATUS_DISETUJUI => [
                            'Disetujui',
                            '#DCFCE7',
                            '#166534',
                            '#86D6A0'
                        ],
                        \App\Models\SuratPerizinanSakit::STATUS_DITOLAK => [
                            'Ditolak',
                            '#FEE2E2',
                            '#B91C1C',
                            '#F2A0A0'
                        ],
                        \App\Models\SuratPerizinanSakit::STATUS_PERLU_REVISI => [
                            'Perlu Revisi',
                            '#FDE68A',
                            '#854D0E',
                            '#FBBF24'
                        ],
                    ];
                @endphp

                @forelse ($pengajuan as $item)

                    @php
                        [$statusLabel, $statusBg, $statusColor, $statusBorder] =
                            $statusLabels[$item->status] ?? [
                                'Tidak Dikenal',
                                '#F3F4F6',
                                '#4B5563',
                                '#D1D5DB'
                            ];
                    @endphp

                    <article class="overflow-hidden rounded-2xl border bg-white shadow-sm transition hover:shadow-md"
                             style="border-color: #E5E7EB;">

                        {{-- CARD HEADER --}}
                        <div class="flex flex-col justify-between gap-4 border-b px-5 py-5 sm:flex-row sm:items-center sm:px-7"
                             style="border-color: #E5E7EB; background-color: #FFFFFF;">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mt-5">
                                    Periode Izin
                                </p>

                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <svg class="h-5 w-5" fill="none"
                                         stroke="#293B70" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/>
                                    </svg>

                                    <span class="text-base font-bold sm:text-lg"
                                          style="color: #1E2B55;">
                                        {{ $item->tanggal_mulai?->format('d M Y') }}
                                    </span>

                                    <span class="text-gray-400">&ndash;</span>

                                    <span class="text-base font-bold sm:text-lg"
                                          style="color: #1E2B55;">
                                        {{ $item->tanggal_selesai?->format('d M Y') }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <span class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-bold"
                                      style="background-color: {{ $statusBg }};
                                             color: {{ $statusColor }};
                                             border-color: {{ $statusBorder }};">

                                    <span class="h-2 w-2 rounded-full"
                                          style="background-color: {{ $statusColor }};"></span>

                                    {{ $statusLabel }}
                                </span>
                            </div>

                        </div>

                        {{-- CARD CONTENT --}}
                        <div class="space-y-5 p-5 sm:p-7">

                            {{-- ALASAN --}}
                            <div>
                                <p class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Keterangan Sakit
                                </p>

                                <div class="rounded-xl p-4"
                                     style="background-color: #F8F9FC; border: 1px solid #E9EDF7;">

                                    <p class="text-sm leading-relaxed text-gray-700">
                                        {{ $item->alasan }}
                                    </p>
                                </div>
                            </div>

                            {{-- DOKUMEN --}}
                            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                                         style="background-color: #FFF5D9; color: #946600;">

                                        <svg class="h-5 w-5" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M8 4h8l4 4v12H4V4h4zm4 5v6m-3-3h6"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold"
                                           style="color: #1E2B55;">
                                            Surat Keterangan Dokter
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $item->surat_dokter_path ? 'Dokumen telah dilampirkan' : 'Dokumen tidak tersedia' }}
                                        </p>
                                    </div>
                                </div>

                                <a href="{{ route('surat-perizinan-sakit.show', $item) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-xl border px-5 py-3 text-sm font-bold transition hover:brightness-95"
                                   style="border-color: #D5DCEB; background-color: #F8F9FC; color: #1E2B55;">

                                    Lihat Detail

                                    <svg class="h-4 w-4" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2.5"
                                              d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                            </div>

                        </div>
                    </article>

                @empty

                    {{-- EMPTY STATE --}}
                    <div class="rounded-2xl border bg-white px-6 py-14 text-center shadow-sm"
                         style="border-color: #E5E7EB;">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full"
                             style="background-color: #E9EDF7; color: #293B70;">

                            <svg class="h-10 w-10" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="1.6"
                                      d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold"
                            style="color: #1E2B55;">
                            Belum Ada Pengajuan
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-gray-500">
                            Anda belum memiliki riwayat pengajuan izin sakit.
                            Silakan buat pengajuan baru melalui tombol di bawah ini.
                        </p>

                        <a href="{{ route('surat-perizinan-sakit.create') }}"
                           class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-bold text-white transition hover:brightness-110"
                           style="background-color: #1E2B55;">

                            <svg class="h-4 w-4" fill="none"
                                 stroke="#F2B51D" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2.5"
                                      d="M12 5v14m-7-7h14"/>
                            </svg>

                            Ajukan Izin Sakit
                        </a>

                    </div>

                @endforelse

            </section>

            <p class="pb-4 text-center text-xs text-gray-400">
                SIMAS · Sistem Informasi Manajemen Administrasi Sekolah
            </p>

        </div>
    </div>
</x-app-layout>
