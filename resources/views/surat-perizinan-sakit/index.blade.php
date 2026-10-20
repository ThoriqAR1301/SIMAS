<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Riwayat Pengajuan Surat Perizinan Sakit — {{ config('app.name', 'SIMAS') }}</title>

        <style>
            :root {
                color-scheme: light;
                --bg: #fdfdfc;
                --surface: #ffffff;
                --text: #1b1b18;
                --text-muted: #706f6c;
                --border: #e3e3e0;
                --border-strong: rgba(26, 26, 0, 0.16);
                --accent: #1b1b18;
                --accent-text: #ffffff;
            }

            *,
            *::before,
            *::after {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 2rem 1rem;
                background: var(--bg);
                color: var(--text);
                font-family: "Instrument Sans", ui-sans-serif, system-ui, -apple-system,
                    "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                font-size: 15px;
                line-height: 1.5;
            }

            .page {
                width: 100%;
                max-width: 48rem;
            }

            .link-row {
                margin-bottom: 1rem;
            }

            .page-header {
                margin-bottom: 1.25rem;
            }

            .page-header h1 {
                margin: 0 0 0.25rem;
                font-size: 1.35rem;
                font-weight: 600;
            }

            .page-header p {
                margin: 0;
                color: var(--text-muted);
                font-size: 0.9rem;
            }

            .card {
                background: var(--surface);
                border: 1px solid var(--border);
                box-shadow: inset 0 0 0 1px var(--border-strong);
                border-radius: 0.75rem;
                padding: 1.5rem;
            }

            .link {
                color: var(--text);
                font-size: 0.85rem;
                font-weight: 600;
                text-decoration: underline;
                text-underline-offset: 4px;
            }

            .link:hover {
                color: var(--text-muted);
            }

            .item {
                padding: 1rem 0;
                border-bottom: 1px solid var(--border);
            }

            .item:first-child {
                padding-top: 0;
            }

            .item:last-child {
                padding-bottom: 0;
                border-bottom: none;
            }

            .item-head {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 0.5rem;
                margin-bottom: 0.5rem;
            }

            .item-dates {
                font-weight: 600;
                font-size: 0.95rem;
            }

            .item-alasan {
                margin: 0 0 0.4rem;
                white-space: pre-line;
            }

            .item-meta {
                margin: 0;
                color: var(--text-muted);
                font-size: 0.78rem;
            }

            .item-action {
                margin: 0.5rem 0 0;
                font-size: 0.85rem;
            }

            .badge {
                display: inline-block;
                padding: 0.2rem 0.6rem;
                border: 1px solid transparent;
                border-radius: 999px;
                font-size: 0.75rem;
                font-weight: 600;
                white-space: nowrap;
            }

            .badge-menunggu {
                background: #fef9c3;
                border-color: #fde68a;
                color: #854d0e;
            }

            .badge-disetujui {
                background: #f0fdf4;
                border-color: #bbf7d0;
                color: #166534;
            }

            .badge-ditolak {
                background: #fef2f2;
                border-color: #fecaca;
                color: #b91c1c;
            }

            .badge-unknown {
                background: #f4f4f5;
                border-color: #e4e4e7;
                color: #52525b;
            }

            .empty {
                text-align: center;
                padding: 2rem 1rem;
            }

            .empty h2 {
                margin: 0 0 0.35rem;
                font-size: 1rem;
                font-weight: 600;
            }

            .empty p {
                margin: 0 0 1rem;
                color: var(--text-muted);
                font-size: 0.85rem;
            }

            .btn {
                display: inline-block;
                padding: 0.55rem 1.25rem;
                border: 1px solid var(--accent);
                border-radius: 0.5rem;
                background: var(--accent);
                color: var(--accent-text);
                font-size: 0.9rem;
                text-decoration: none;
            }

            .btn:hover {
                opacity: 0.88;
            }
        </style>
    </head>
    <body>
        @php
            $statusLabels = [
                \App\Models\SuratPerizinanSakit::STATUS_MENUNGGU => ['Menunggu', 'badge-menunggu'],
                \App\Models\SuratPerizinanSakit::STATUS_DISETUJUI => ['Disetujui', 'badge-disetujui'],
                \App\Models\SuratPerizinanSakit::STATUS_DITOLAK => ['Ditolak', 'badge-ditolak'],
            ];
        @endphp

        <div class="page">
            <div class="link-row">
                <a class="link" href="{{ route('surat-perizinan-sakit.create') }}">
                    &larr; Ajukan Izin Sakit
                </a>
            </div>

            <header class="page-header">
                <h1>Riwayat Pengajuan Surat Perizinan Sakit</h1>
                <p>Daftar pengajuan izin sakit yang Anda kirim, terbaru di atas.</p>
            </header>

            <main class="card">
                @forelse ($pengajuan as $item)
                    @php
                        [$statusLabel, $statusClass] = $statusLabels[$item->status] ?? ['Status tidak dikenal', 'badge-unknown'];
                    @endphp

                    <article class="item">
                        <div class="item-head">
                            <span class="item-dates">
                                {{ $item->tanggal_mulai?->format('d/m/Y') }}
                                &ndash;
                                {{ $item->tanggal_selesai?->format('d/m/Y') }}
                            </span>
                            <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>

                        <p class="item-alasan">{{ $item->alasan }}</p>

                        <p class="item-meta">
                            Surat dokter:
                            {{ $item->surat_dokter_path ? 'Tersedia' : 'Tidak tersedia' }}
                        </p>

                        <p class="item-action">
                            <a class="link" href="{{ route('surat-perizinan-sakit.show', $item) }}">
                                Detail &rarr;
                            </a>
                        </p>
                    </article>
                @empty
                    <div class="empty">
                        <h2>Belum ada pengajuan</h2>
                        <p>Anda belum pernah mengirim pengajuan surat perizinan sakit.</p>
                        <a class="btn" href="{{ route('surat-perizinan-sakit.create') }}">
                            Ajukan Izin Sakit
                        </a>
                    </div>
                @endforelse
            </main>
        </div>
    </body>
</html>
