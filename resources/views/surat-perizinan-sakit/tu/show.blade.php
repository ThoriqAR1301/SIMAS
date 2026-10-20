<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pemeriksaan Detail Pengajuan — {{ config('app.name', 'SIMAS') }}</title>

        <style>
            :root {
                color-scheme: light;
                --bg: #fdfdfc;
                --surface: #ffffff;
                --text: #1b1b18;
                --text-muted: #706f6c;
                --border: #e3e3e0;
                --border-strong: rgba(26, 26, 0, 0.16);
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

            .link.is-disabled {
                color: var(--text-muted);
                text-decoration: none;
                cursor: not-allowed;
            }

            .note {
                margin-bottom: 1.25rem;
                padding: 0.7rem 0.85rem;
                border: 1px solid #fde68a;
                border-radius: 0.5rem;
                background: #fef9c3;
                color: #854d0e;
                font-size: 0.82rem;
            }

            .detail {
                margin: 0 0 1.5rem;
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

            fieldset {
                margin: 0;
                padding: 1.25rem 0 0;
                border: none;
                border-top: 1px solid var(--border);
            }

            legend {
                padding: 0;
                font-size: 0.95rem;
                font-weight: 600;
            }

            .field {
                margin-top: 1rem;
            }

            label {
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.85rem;
                font-weight: 600;
            }

            textarea {
                width: 100%;
                min-height: 6rem;
                padding: 0.55rem 0.7rem;
                border: 1px solid var(--border);
                border-radius: 0.5rem;
                background: var(--surface);
                color: var(--text);
                font: inherit;
                resize: vertical;
            }

            .options {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                margin-top: 0.35rem;
            }

            .option {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                font-size: 0.9rem;
                font-weight: 400;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                margin-top: 1.5rem;
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

            .limitation {
                color: var(--text-muted);
                font-size: 0.78rem;
            }

            p.limitation {
                margin: 1rem 0 0;
            }

            fieldset[disabled] {
                opacity: 0.65;
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
                @if (Route::has('tu.surat-perizinan-sakit.index'))
                    <a class="link" href="{{ route('tu.surat-perizinan-sakit.index') }}">
                        &larr; Daftar Pemeriksaan
                    </a>
                @else
                    <span class="link is-disabled" aria-disabled="true" title="Tersedia setelah otorisasi role Tata Usaha">
                        &larr; Daftar Pemeriksaan
                    </span>
                @endif
            </div>

            <header class="page-header">
                <h1>Pemeriksaan Detail Pengajuan</h1>
                <p>Pratinjau antarmuka pemeriksaan oleh Tata Usaha.</p>
            </header>

            <main class="card">
                <div class="note">
                    <strong>Antarmuka belum aktif.</strong> Otorisasi role Tata Usaha belum tersedia;
                    area keputusan di bawah hanya tampilan dan tidak dapat mengirim data.
                </div>

                <dl class="detail">
                    <dt>Nama Guru</dt>
                    <dd>{{ $pengajuan->user?->name ?? 'Pengguna tidak diketahui' }}</dd>

                    <dt>Tanggal Mulai Izin</dt>
                    <dd>{{ $pengajuan->tanggal_mulai?->format('d/m/Y') }}</dd>

                    <dt>Tanggal Selesai Izin</dt>
                    <dd>{{ $pengajuan->tanggal_selesai?->format('d/m/Y') }}</dd>

                    <dt>Alasan Sakit</dt>
                    <dd>{{ $pengajuan->alasan }}</dd>

                    <dt>Status</dt>
                    <dd><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></dd>

                    <dt>Surat Dokter</dt>
                    <dd>
                        {{ $pengajuan->surat_dokter_path ? 'Tersedia' : 'Tidak tersedia' }}
                        @if ($pengajuan->surat_dokter_path)
                            <span class="limitation">(penyajian berkas secara aman belum tersedia)</span>
                        @endif
                    </dd>

                    @if (filled($pengajuan->catatan_tu))
                        <dt>Catatan Tata Usaha Saat Ini</dt>
                        <dd>{{ $pengajuan->catatan_tu }}</dd>
                    @endif
                </dl>

                <fieldset disabled aria-disabled="true">
                    <legend>Keputusan Pemeriksaan</legend>

                    <div class="field">
                        <label for="catatan_tu">Catatan Tata Usaha</label>
                        <textarea id="catatan_tu" name="catatan_tu" placeholder="Catatan untuk guru (opsional)">{{ $pengajuan->catatan_tu }}</textarea>
                    </div>

                    <div class="field">
                        <label>Keputusan</label>
                        <div class="options">
                            <label class="option">
                                <input type="radio" name="keputusan" value="disetujui">
                                Setujui
                            </label>
                            <label class="option">
                                <input type="radio" name="keputusan" value="ditolak">
                                Tolak
                            </label>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn">Simpan Keputusan</button>
                    </div>
                </fieldset>
            </main>

            <p class="limitation">
                Catatan keterbatasan: tidak ada endpoint POST keputusan dan tidak ada route pemeriksaan
                yang dipasang, agar status tidak dapat diubah sebelum otorisasi role Tata Usaha tersedia.
                Dokumen medis juga tidak ditautkan secara publik.
            </p>
        </div>
    </body>
</html>
