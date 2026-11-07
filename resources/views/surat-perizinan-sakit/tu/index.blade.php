<x-app-layout>
    <style>
        .sk-head-title { font-size: 20px; font-weight: 800; color: #1f2a5c; letter-spacing: -0.01em; }
        .sk-head-sub { margin-top: 4px; font-size: 14px; color: #6b7191; }

        .sk-page {
            min-height: 100vh;
            padding: 32px 16px 48px;
            background: linear-gradient(135deg, #fbf7ea 0%, #f8f7f3 45%, #eef0f8 100%);
            color: #1f2a5c;
        }
        @media (min-width: 640px) { .sk-page { padding: 32px 24px 48px; } }
        @media (min-width: 1024px) { .sk-page { padding: 32px 32px 48px; } }
        .sk-wrap { max-width: 1280px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px; }

        /* Hero */
        .sk-hero {
            position: relative; overflow: hidden; border-radius: 24px; padding: 32px;
            color: #fff;
            background: linear-gradient(135deg, #1b2452 0%, #222d5f 55%, #30407a 100%);
            box-shadow: 0 20px 40px -18px rgba(31, 42, 92, 0.6);
            display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 24px;
        }
        .sk-hero::before {
            content: ""; position: absolute; right: -60px; top: -90px; width: 260px; height: 260px;
            border-radius: 50%; background: rgba(255, 255, 255, 0.05);
        }
        .sk-hero::after {
            content: ""; position: absolute; right: 180px; bottom: -110px; width: 220px; height: 220px;
            border-radius: 50%; background: rgba(245, 179, 36, 0.10);
        }
        .sk-hero-main { position: relative; z-index: 1; max-width: 680px; }
        .sk-chip {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 999px;
            background: rgba(255, 255, 255, 0.10); border: 1px solid rgba(245, 179, 36, 0.35);
            color: #f5b324; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em;
        }
        .sk-dot { width: 8px; height: 8px; border-radius: 50%; background: #f5b324; }
        .sk-hero h1 { margin: 20px 0 0; font-size: 30px; line-height: 1.2; font-weight: 800; letter-spacing: -0.02em; color: #fff; }
        .sk-hero p { margin: 10px 0 0; font-size: 15px; line-height: 1.65; color: #c9d0ea; }
        .sk-hero-stat {
            position: relative; z-index: 1; min-width: 140px; text-align: center; padding: 16px 24px; border-radius: 18px;
            background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .sk-hero-stat strong { display: block; font-size: 36px; line-height: 1; font-weight: 800; color: #f5b324; }
        .sk-hero-stat span { display: block; margin-top: 8px; font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #c9d0ea; }

        /* Flash */
        .sk-flash {
            border-radius: 16px; border: 1px solid #b7e4c7; background: #e8f7ee; color: #15803d;
            padding: 16px 20px; font-size: 14px; font-weight: 600;
        }

        /* Summary */
        .sk-summary { display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        .sk-stat {
            position: relative; overflow: hidden; border-radius: 20px; padding: 20px 22px 20px 26px;
            background: #fffdf8; border: 1px solid #efe9d8;
            box-shadow: 0 1px 2px rgba(31, 42, 92, 0.04), 0 10px 26px -16px rgba(31, 42, 92, 0.22);
        }
        .sk-stat::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background: var(--accent); }
        .sk-stat-icon {
            position: absolute; right: 18px; top: 18px; width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; background: var(--soft); color: var(--accent);
        }
        .sk-stat-icon svg { width: 20px; height: 20px; }
        .sk-stat-label { font-size: 13px; font-weight: 600; color: #6b7191; }
        .sk-stat-value { margin-top: 8px; font-size: 36px; line-height: 1.1; font-weight: 800; color: var(--value); }
        .sk-stat-note { margin-top: 6px; font-size: 12px; color: #9a9fb8; }

        /* Panel */
        .sk-panel {
            overflow: hidden; border-radius: 24px; background: #fffdf8; border: 1px solid #efe9d8;
            box-shadow: 0 1px 2px rgba(31, 42, 92, 0.04), 0 14px 34px -20px rgba(31, 42, 92, 0.25);
        }
        .sk-panel-head {
            display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px;
            padding: 22px 24px; border-bottom: 1px solid #f1ecdc;
        }
        .sk-panel-head h3 { margin: 0; font-size: 18px; font-weight: 800; color: #1f2a5c; }
        .sk-panel-head p { margin: 4px 0 0; font-size: 13.5px; color: #6b7191; }
        .sk-count {
            padding: 8px 16px; border-radius: 999px; background: #1f2a5c; color: #fff;
            font-size: 12px; font-weight: 700; white-space: nowrap;
        }

        /* Table */
        .sk-table-wrap { overflow-x: auto; }
        .sk-table { width: 100%; min-width: 860px; border-collapse: collapse; text-align: left; }
        .sk-table thead { background: #faf6ea; }
        .sk-table th {
            padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #6b7191; text-align: left;
        }
        .sk-table th.sk-center, .sk-table td.sk-center { text-align: center; }
        .sk-table td { padding: 18px 24px; border-top: 1px solid #f1ecdc; font-size: 14px; color: #555b7a; vertical-align: middle; }
        .sk-table tbody tr { transition: background 0.15s ease; }
        .sk-table tbody tr:hover td { background: #fff9ec; }
        .sk-nowrap { white-space: nowrap; }
        .sk-reason { max-width: 280px; }

        .sk-person { display: flex; align-items: center; gap: 12px; }
        .sk-avatar {
            flex-shrink: 0; width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #f5b324, #e39a0c); color: #1f2a5c; font-size: 14px; font-weight: 800;
            box-shadow: 0 4px 10px -4px rgba(227, 154, 12, 0.7);
        }
        .sk-name { font-weight: 700; color: #1f2a5c; }

        /* Status badge */
        .sk-badge {
            display: inline-flex; align-items: center; gap: 7px; padding: 5px 12px; border-radius: 999px;
            border: 1px solid transparent; font-size: 12px; font-weight: 700; white-space: nowrap;
        }
        .sk-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .sk-badge--menunggu { background: #fff6dc; color: #b57a00; border-color: #f6dd9b; }
        .sk-badge--disetujui { background: #e8f7ee; color: #15803d; border-color: #b7e4c7; }
        .sk-badge--ditolak { background: #fdecec; color: #b91c1c; border-color: #f6c3c3; }
        .sk-badge--perlu_revisi { background: #fff0e1; color: #c2410c; border-color: #fbd3ae; }
        .sk-badge--default { background: #eef0f6; color: #5b6180; border-color: #d9dcea; }

        /* Button */
        .sk-btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 12px;
            background: #1f2a5c; color: #fff !important; font-size: 12.5px; font-weight: 700; text-decoration: none;
            box-shadow: 0 6px 14px -8px rgba(31, 42, 92, 0.7);
            transition: background 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
        }
        .sk-btn:hover { background: #2d3a6e; transform: translateY(-1px); box-shadow: 0 10px 18px -10px rgba(31, 42, 92, 0.8); }
        .sk-btn:focus-visible { outline: 2px solid #f5b324; outline-offset: 2px; }
        .sk-btn svg { width: 16px; height: 16px; color: #f5b324; }

        /* Empty */
        .sk-empty { padding: 64px 24px; text-align: center; }
        .sk-empty-icon {
            margin: 0 auto; width: 64px; height: 64px; border-radius: 18px; background: #fff3d6; color: #f5b324;
            display: flex; align-items: center; justify-content: center;
        }
        .sk-empty-icon svg { width: 32px; height: 32px; }
        .sk-empty h4 { margin: 16px 0 0; font-size: 16px; font-weight: 800; color: #1f2a5c; }
        .sk-empty p { margin: 4px 0 0; font-size: 14px; color: #6b7191; }

        .sk-footer { text-align: center; font-size: 12px; color: #9a9fb8; }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="sk-head-title">
                Pemeriksaan Pengajuan Surat Perizinan Sakit
            </h2>
            <p class="sk-head-sub">
                Kelola dan periksa pengajuan izin sakit yang dikirimkan oleh Guru.
            </p>
        </div>
    </x-slot>

    @php
        $statusLabels = [
            'menunggu' => ['Menunggu', 'sk-badge--menunggu'],
            'disetujui' => ['Disetujui', 'sk-badge--disetujui'],
            'ditolak' => ['Ditolak', 'sk-badge--ditolak'],
            'perlu_revisi' => ['Perlu Revisi', 'sk-badge--perlu_revisi'],
        ];
    @endphp

    <div class="sk-page">
        <div class="sk-wrap">

            {{-- Hero --}}
            <div class="sk-hero">
                <div class="sk-hero-main">
                    <span class="sk-chip">
                        <span class="sk-dot"></span>
                        Admin Tata Usaha
                    </span>

                    <h1>Pengajuan Sakit Masuk</h1>
                    <p>
                        Lihat informasi pengajuan, periksa surat keterangan dokter,
                        dan tentukan hasil pemeriksaan izin sakit Guru.
                    </p>
                </div>

                <div class="sk-hero-stat">
                    <strong>{{ $pengajuan->where('status', 'menunggu')->count() }}</strong>
                    <span>Menunggu</span>
                </div>
            </div>

            {{-- Flash message --}}
            @if (session('status'))
                <div class="sk-flash">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Summary --}}
            <div class="sk-summary">
                <div class="sk-stat" style="--accent:#1f2a5c; --soft:#eef1f8; --value:#1f2a5c;">
                    <div class="sk-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="sk-stat-label">Total Pengajuan</p>
                    <p class="sk-stat-value">{{ $pengajuan->count() }}</p>
                    <p class="sk-stat-note">Seluruh pengajuan izin sakit</p>
                </div>

                <div class="sk-stat" style="--accent:#f5b324; --soft:#fff3d6; --value:#d99a0b;">
                    <div class="sk-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="sk-stat-label">Menunggu Pemeriksaan</p>
                    <p class="sk-stat-value">{{ $pengajuan->where('status', 'menunggu')->count() }}</p>
                    <p class="sk-stat-note">Pengajuan yang belum diputuskan</p>
                </div>

                <div class="sk-stat" style="--accent:#f97316; --soft:#fff0e1; --value:#ea580c;">
                    <div class="sk-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.4-9.4a2 2 0 112.8 2.8L11.8 15H9v-2.8l8.6-8.6z"/>
                        </svg>
                    </div>
                    <p class="sk-stat-label">Perlu Revisi</p>
                    <p class="sk-stat-value">{{ $pengajuan->where('status', 'perlu_revisi')->count() }}</p>
                    <p class="sk-stat-note">Pengajuan yang perlu diperbaiki Guru</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="sk-panel">
                <div class="sk-panel-head">
                    <div>
                        <h3>Daftar Pengajuan Guru</h3>
                        <p>Pilih pengajuan untuk melihat dokumen dan melakukan pemeriksaan.</p>
                    </div>

                    <span class="sk-count">{{ $pengajuan->count() }} Pengajuan</span>
                </div>

                @if ($pengajuan->isEmpty())
                    <div class="sk-empty">
                        <div class="sk-empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4>Belum Ada Pengajuan</h4>
                        <p>Belum ada Guru yang mengirimkan pengajuan izin sakit.</p>
                    </div>
                @else
                    <div class="sk-table-wrap">
                        <table class="sk-table">
                            <thead>
                                <tr>
                                    <th>Guru</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th class="sk-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pengajuan as $item)
                                    @php
                                        [$statusLabel, $statusClass] = $statusLabels[$item->status]
                                            ?? ['Tidak Diketahui', 'sk-badge--default'];
                                    @endphp

                                    <tr>
                                        <td>
                                            <div class="sk-person">
                                                <div class="sk-avatar">
                                                    {{ strtoupper(substr($item->user?->name ?? 'G', 0, 1)) }}
                                                </div>
                                                <span class="sk-name">
                                                    {{ $item->user?->name ?? 'Pengguna tidak diketahui' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="sk-nowrap">
                                            {{ $item->tanggal_mulai?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td class="sk-nowrap">
                                            {{ $item->tanggal_selesai?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td class="sk-reason">
                                            {{ \Illuminate\Support\Str::limit($item->alasan, 55) }}
                                        </td>

                                        <td class="sk-nowrap">
                                            <span class="sk-badge {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <td class="sk-center">
                                            <a href="{{ route('surat-perizinan-sakit.show', $item) }}" class="sk-btn">
                                                Lihat Detail
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <p class="sk-footer">
                SIMAS — Sistem Informasi Manajemen Administrasi Sekolah
            </p>
        </div>
    </div>
</x-app-layout>