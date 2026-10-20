<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pemeriksaan Pengajuan Surat Perizinan Sakit — {{ config('app.name', 'SIMAS') }}</title>

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
                max-width: 60rem;
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

            .note {
                margin-bottom: 1.25rem;
                padding: 0.7rem 0.85rem;
                border: 1px solid #fde68a;
                border-radius: 0.5rem;
                background: #fef9c3;
                color: #854d0e;
                font-size: 0.82rem;
            }

            .table-wrap {
                overflow-x: auto;
            }

            table {
                width: 100%;
                min-width: 46rem;
                border-collapse: collapse;
                font-size: 0.88rem;
            }

            th,
            td {
                padding: 0.65rem 0.75rem;
                text-align: left;
                vertical-align: top;
                border-bottom: 1px solid var(--border);
            }

            th {
                font-size: 0.72rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                color: var(--text-muted);
                white-space: nowrap;
            }

            tbody tr:last-child td {
                border-bottom: none;
            }

            td.alasan {
                color: var(--text-muted);
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

            .badge {
                display: inline-block;
                padding: 0.2rem 0.6rem;
                border: 1px solid transparent;
                border-radius: 999px;
                font-size: 0.72rem;
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
                margin: 0;
                color: var(--text-muted);
                font-size: 0.85rem;
            }

            .limitation {
                margin: 1rem 0 0;
                color: var(--text-muted);
                font-size: 0.78rem;
            }

            code {
                font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
                font-size: 0.85em;
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
            <header class="page-header">
                <h1>Pemeriksaan Pengajuan Surat Perizinan Sakit</h1>
                <p>Daftar pengajuan izin sakit untuk diperiksa Tata Usaha (pratinjau antarmuka).</p>
            </header>

            <main class="card">
                <div class="note">
                    <strong>Antarmuka belum aktif.</strong> Otorisasi role Tata Usaha belum tersedia,
                    sehingga halaman ini belum memiliki route dan tidak dapat diakses pengguna biasa.
                </div>

                @if ($pengajuan->isEmpty())
                    <div class="empty">
                        <h2>Belum ada pengajuan</h2>
                        <p>Tidak ada pengajuan surat perizinan sakit yang perlu diperiksa.</p>
                    </div>
                @else
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Guru</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengajuan as $item)
                                    @php
                                        [$statusLabel, $statusClass] = $statusLabels[$item->status] ?? ['Status tidak dikenal', 'badge-unknown'];
                                    @endphp

                                    <tr>
                                        <td>{{ $item->user?->name ?? 'Pengguna tidak diketahui' }}</td>
                                        <td>{{ $item->tanggal_mulai?->format('d/m/Y') }}</td>
                                        <td>{{ $item->tanggal_selesai?->format('d/m/Y') }}</td>
                                        <td class="alasan">{{ \Illuminate\Support\Str::limit($item->alasan, 60) }}</td>
                                        <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                        <td>
                                            @if (Route::has('tu.surat-perizinan-sakit.show'))
                                                <a class="link" href="{{ route('tu.surat-perizinan-sakit.show', $item) }}">Detail</a>
                                            @else
                                                <span class="link is-disabled" aria-disabled="true" title="Tersedia setelah otorisasi role Tata Usaha">Detail</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </main>

            <p class="limitation">
                Catatan keterbatasan: nama guru diambil dari relasi <code>user</code> (kolom
                <code>users.name</code>). Model belum memiliki role guru/TU, sehingga pemisahan
                guru dan Tata Usaha belum dapat diverifikasi. Daftar ini kelak perlu memuat relasi
                <code>user</code> (eager load) untuk menghindari kueri berulang.
            </p>
        </div>
    </body>
</html>
