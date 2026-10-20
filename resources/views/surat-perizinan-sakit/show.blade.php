<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Detail Pengajuan Surat Perizinan Sakit — {{ config('app.name', 'SIMAS') }}</title>

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
                max-width: 40rem;
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

            .detail {
                margin: 0;
            }

            .detail dt {
                margin-top: 1.1rem;
                font-size: 0.78rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                color: var(--text-muted);
            }

            .detail dt:first-child {
                margin-top: 0;
            }

            .detail dd {
                margin: 0.25rem 0 0;
                white-space: pre-line;
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

            .actions {
                margin-top: 1.5rem;
                padding-top: 1.25rem;
                border-top: 1px solid var(--border);
            }

            .btn {
                display: inline-block;
                padding: 0.55rem 1.25rem;
                border: 1px solid var(--border);
                border-radius: 0.5rem;
                background: transparent;
                color: var(--text);
                font-size: 0.9rem;
                text-decoration: none;
            }

            .btn:hover {
                border-color: var(--text-muted);
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
            [$statusLabel, $statusClass] = $statusLabels[$pengajuan->status] ?? ['Status tidak dikenal', 'badge-unknown'];
        @endphp

        <div class="page">
            <div class="link-row">
                <a class="link" href="{{ route('surat-perizinan-sakit.index') }}">
                    &larr; Riwayat Pengajuan
                </a>
            </div>

            <header class="page-header">
                <h1>Detail Pengajuan Surat Perizinan Sakit</h1>
                <p>Rincian pengajuan izin sakit yang Anda kirim.</p>
            </header>

            <main class="card">
                <dl class="detail">
                    <dt>Tanggal Mulai Izin</dt>
                    <dd>{{ $pengajuan->tanggal_mulai?->format('d/m/Y') }}</dd>

                    <dt>Tanggal Selesai Izin</dt>
                    <dd>{{ $pengajuan->tanggal_selesai?->format('d/m/Y') }}</dd>

                    <dt>Alasan Sakit</dt>
                    <dd>{{ $pengajuan->alasan }}</dd>

                    <dt>Status</dt>
                    <dd><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></dd>

                    <dt>Surat Dokter</dt>
                    <dd>{{ $pengajuan->surat_dokter_path ? 'Tersedia' : 'Tidak tersedia' }}</dd>

                    @if (filled($pengajuan->catatan_tu))
                        <dt>Catatan Tata Usaha</dt>
                        <dd>{{ $pengajuan->catatan_tu }}</dd>
                    @endif
                </dl>

                <div class="actions">
                    <a class="btn" href="{{ route('surat-perizinan-sakit.index') }}">
                        Kembali ke Riwayat
                    </a>
                </div>
            </main>
        </div>
    </body>
</html>
