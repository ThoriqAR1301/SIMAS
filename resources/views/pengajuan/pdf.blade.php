<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat {{ $pengajuan->nomor_surat }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 18mm 20mm 18mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Georgia, serif;
            font-size: 11.5pt;
            line-height: 1.55;
            color: #1E2A5A;
            position: relative;
        }

        .side-accent {
            position: fixed;
            top: 0;
            left: 0;
            width: 6mm;
            height: 100%;
            background: linear-gradient(180deg,
                #F4B22E 0%, #F4B22E 40%,
                #1E2A5A 40%, #1E2A5A 100%);
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 120pt;
            font-weight: 900;
            color: #1E2A5A;
            opacity: 0.03;
            letter-spacing: 0.1em;
            z-index: -1;
        }

        .header {
            padding-bottom: 12px;
            border-bottom: 4px double #1E2A5A;
            margin-bottom: 24px;
            position: relative;
        }

        .header-inner {
            display: table;
            width: 100%;
        }

        .logo-cell {
            display: table-cell;
            width: 22mm;
            vertical-align: middle;
            padding-right: 6mm;
        }

        .logo-cell img {
            width: 20mm;
            height: 20mm;
            object-fit: contain;
        }

        .text-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .kop-1 {
            font-size: 11pt;
            letter-spacing: 0.05em;
            color: #1E2A5A;
            font-weight: 600;
        }

        .kop-2 {
            font-size: 15pt;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #1E2A5A;
            margin: 2px 0;
        }

        .kop-3 {
            font-size: 9pt;
            color: #4A579F;
            letter-spacing: 0.02em;
        }

        .kop-address {
            font-size: 8.5pt;
            color: #5C6B85;
            margin-top: 3px;
            font-style: italic;
        }

        .title-block {
            text-align: center;
            margin: 22px 0 8px;
        }

        .title-main {
            font-size: 14pt;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-decoration: underline;
            text-underline-offset: 4px;
            color: #1E2A5A;
        }

        .title-nomor {
            font-size: 11pt;
            margin-top: 4px;
            color: #2B3567;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            letter-spacing: 0.05em;
        }

        .body-content {
            margin-top: 20px;
            text-align: justify;
        }

        .body-content p {
            margin-bottom: 10px;
        }

        .info-table {
            margin: 16px 0 16px 24px;
            width: calc(100% - 24px);
            border-collapse: collapse;
        }

        .info-table tr { vertical-align: top; }

        .info-table td {
            padding: 4px 0;
            font-size: 11pt;
        }

        .info-table td:first-child {
            width: 45mm;
            color: #5C6B85;
        }

        .info-table td:nth-child(2) {
            width: 5mm;
            color: #1E2A5A;
        }

        .info-table td:last-child {
            font-weight: 600;
            color: #1E2A5A;
        }

        .highlight-box {
            margin: 18px 0;
            padding: 12px 16px;
            background: linear-gradient(135deg, #FEF9EC 0%, #FDF0C8 100%);
            border-left: 4px solid #F4B22E;
            border-radius: 2px;
        }

        .highlight-box strong {
            color: #1E2A5A;
            font-size: 11.5pt;
        }

        .signature-block {
            margin-top: 40px;
            width: 100%;
            display: table;
        }

        .signature-left {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            font-size: 10pt;
            color: #5C6B85;
        }

        .signature-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
            text-align: center;
        }

        .signature-date {
            font-size: 11pt;
            color: #1E2A5A;
            margin-bottom: 4px;
        }

        .signature-role {
            font-size: 11pt;
            color: #1E2A5A;
            margin-bottom: 50px;
        }

        .signature-name {
            font-size: 11.5pt;
            font-weight: 800;
            color: #1E2A5A;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .signature-nip {
            font-size: 10pt;
            color: #5C6B85;
            margin-top: 3px;
        }

        .qr-block {
            position: fixed;
            bottom: 12mm;
            right: 18mm;
            text-align: center;
            width: 32mm;
        }

        .qr-frame {
            display: inline-block;
            padding: 3px;
            background: linear-gradient(135deg, #F4B22E, #D99A1A);
            border-radius: 6px;
        }

        .qr-frame img {
            display: block;
            width: 26mm;
            height: 26mm;
            background: white;
            padding: 2px;
            border-radius: 4px;
        }

        .qr-caption {
            font-size: 6.5pt;
            color: #5C6B85;
            margin-top: 3px;
            line-height: 1.3;
            letter-spacing: 0.02em;
        }

        .qr-caption strong {
            color: #1E2A5A;
            display: block;
            font-size: 7pt;
            letter-spacing: 0.05em;
        }

        .footer {
            position: fixed;
            bottom: 6mm;
            left: 18mm;
            right: 18mm;
            padding-top: 4px;
            border-top: 1px solid #C2C9E5;
            font-size: 7pt;
            color: #808EA6;
            display: table;
            width: calc(100% - 36mm);
        }

        .footer-left { display: table-cell; }
        .footer-right { display: table-cell; text-align: right; }
    </style>
</head>
<body>

    <div class="side-accent"></div>

    <div class="watermark">SMK PESAT IT XPRO</div>

    <div class="header">
        <div class="header-inner">
            <div class="logo-cell">
                <img src="{{ public_path('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo">
            </div>
            <div class="text-cell">
                <div class="kop-1">YAYASAN PENDIDIKAN PESAT</div>
                <div class="kop-2">SMK PESAT IT XPRO</div>
                <div class="kop-3">IT EXPERT &amp; PROFESSIONAL</div>
                <div class="kop-address">
                    Jl. Poras No.07, RT.01/RW.04, Loji, Kec. Bogor Bar., Kota Bogor, Jawa Barat 16117 · Telp 0877-1117-7442 · https://smkpesat.sch.id
                </div>
            </div>
        </div>
    </div>

    <div class="title-block">
        <div class="title-main">{{ strtoupper($pengajuan->jenis_surat) }}</div>
        <div class="title-nomor">Nomor: {{ $pengajuan->nomor_surat }}</div>
    </div>

    <div class="body-content">

        <p>Yang Bertanda Tangan Di Bawah Ini, Kepala SMK Pesat IT XPro, Dengan Ini Menerangkan Bahwa :</p>

        <table class="info-table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $pengajuan->user->name }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $pengajuan->user->nip ?? '—' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $pengajuan->user->jabatan ?? '—' }}</td>
            </tr>
        </table>

        <p>
            Benar Yang Bersangkutan Mengajukan <strong>{{ $pengajuan->jenis_surat }}</strong>
            Untuk Kegiatan Dengan Rincian Sebagai Berikut :
        </p>

        <div class="highlight-box">
            <table class="info-table" style="margin: 0;">
                <tr>
                    <td style="width: 40mm; color: #764E13;">Nama Kegiatan</td>
                    <td style="width: 5mm; color: #764E13;">:</td>
                    <td style="color: #1E2A5A;">{{ $pengajuan->nama_kegiatan }}</td>
                </tr>
                <tr>
                    <td style="color: #764E13;">Tanggal</td>
                    <td style="color: #764E13;">:</td>
                    <td style="color: #1E2A5A;">
                        {{ $pengajuan->tanggal_pelaksanaan->translatedFormat('d F Y') }}
                    </td>
                </tr>
                @if($pengajuan->waktu_mulai)
                <tr>
                    <td style="color: #764E13;">Waktu</td>
                    <td style="color: #764E13;">:</td>
                    <td style="color: #1E2A5A;">
                        {{ $pengajuan->waktu_mulai }} — {{ $pengajuan->waktu_selesai }} WIB
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="color: #764E13;">Tempat</td>
                    <td style="color: #764E13;">:</td>
                    <td style="color: #1E2A5A;">{{ $pengajuan->tempat ?? '—' }}</td>
                </tr>
                @if($pengajuan->keterangan)
                <tr>
                    <td style="color: #764E13;">Keterangan</td>
                    <td style="color: #764E13;">:</td>
                    <td style="color: #1E2A5A;">{{ $pengajuan->keterangan }}</td>
                </tr>
                @endif
            </table>
        </div>

        <p>
            Demikian Surat {{ strtolower($pengajuan->jenis_surat) }} Ini Dibuat Dengan Sebenarnya
            Untuk Dapat Dipergunakan Sebagaimana Mestinya.
        </p>

    </div>

    <div class="signature-block">
        <div class="signature-left">
            <div style="font-size: 9pt; line-height: 1.6;">
                Surat Ini Diterbitkan Secara Elektronik Melalui Sistem Informasi Manajemen
                Administrasi Surat (SIMAS) SMK Pesat IT XPro. Keaslian Dokumen Dapat Diverifikasi
                Dengan Memindai QR Code Di Sebelah Kanan.
            </div>
        </div>
        <div class="signature-right">
            <div class="signature-date">
                Diterbitkan Pada {{ $pengajuan->qr_generated_at?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}
            </div>
            <div class="signature-role">Kepala Sekolah,</div>

            <div style="height: 45px;"></div>

            <div class="signature-name">{{ $pengajuan->reviewer->name ?? 'Kepala Sekolah' }}</div>
            <div class="signature-nip">NIP. {{ $pengajuan->reviewer->nip ?? '—' }}</div>
        </div>
    </div>

    @php
        $qrPath = storage_path('app/public/qr/' . $pengajuan->qr_token . '.png');
    @endphp
    @if($pengajuan->qr_token && file_exists($qrPath))
        <div class="qr-block">
            <div class="qr-frame">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($qrPath)) }}" alt="QR">
            </div>
            <div class="qr-caption">
                <strong>✓ TERVERIFIKASI</strong>
                Scan Untuk Verifikasi Keaslian
            </div>
        </div>
    @endif

    <div class="footer">
        <div class="footer-left">
            SIMAS · SMK Pesat IT XPro · Dokumen Ini Sah Tanpa Memerlukan Cap Basah
        </div>
        <div class="footer-right">
            {{ $pengajuan->nomor_surat }} · Cetak {{ now()->translatedFormat('d/m/Y H:i') }}
        </div>
    </div>

</body>
</html>