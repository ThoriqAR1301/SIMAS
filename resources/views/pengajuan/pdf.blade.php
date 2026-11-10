<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Tugas {{ $pengajuan->nomor_surat }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 20mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11.5pt;
            line-height: 1.6;
            color: #000000;
            background-color: #FFFFFF;
        }

        /* =========================================
           KOP SURAT (Standard Premium)
           ========================================= */
        .letterhead {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .letterhead td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 90px;
            text-align: center;
        }

        .logo-cell img {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .school-info {
            text-align: center;
        }

        .school-info .foundation {
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .school-info .name {
            font-family: Arial, sans-serif;
            font-size: 18pt;
            font-weight: 900;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .school-info .address {
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            margin-bottom: 2px;
        }

        /* =========================================
           TITLE & NOMOR
           ========================================= */
        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .title-section h1 {
            font-size: 16pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 1px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .title-section .nomor {
            font-size: 11.5pt;
            margin: 0;
        }

        /* =========================================
           CONTENT
           ========================================= */
        .opening {
            margin-bottom: 15px;
            text-align: justify;
        }

        /* Tabel Panitia (Persis PDF Referensi) */
        .table-panitia {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-panitia th, 
        .table-panitia td {
            border: 1px solid #000000;
            padding: 6px 10px;
            vertical-align: top;
        }

        .table-panitia th {
            font-weight: bold;
            text-align: center;
            background-color: #F3F4F6; /* Sentuhan premium: header abu-abu sangat muda */
        }

        .table-panitia .col-no {
            width: 7%;
            text-align: center;
        }

        .table-panitia .col-nama {
            width: 40%;
        }

        .table-panitia .col-ket {
            width: 53%;
        }

        /* Rincian Kegiatan (Format Paragraf Rata Kiri) */
        .activity-info {
            margin-bottom: 20px;
        }

        .activity-info table {
            border-collapse: collapse;
            margin-left: 10px;
        }

        .activity-info td {
            padding: 2px 5px;
            vertical-align: top;
        }

        .activity-info td:first-child {
            width: 80px;
        }

        .activity-info td:nth-child(2) {
            width: 10px;
        }

        /* =========================================
           CLOSING & SIGNATURE
           ========================================= */
        .closing {
            margin-bottom: 30px;
            text-align: justify;
        }

        .signature-section {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .signature-section td {
            width: 50%;
            vertical-align: top;
        }

        .signature-box {
            text-align: center;
            padding-right: 20px;
        }

        .signature-box .date {
            margin-bottom: 5px;
        }

        .signature-box .role {
            margin-bottom: 70px;
        }

        .signature-box .name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* =========================================
           FOOTER
           ========================================= */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #D1D5DB;
            padding-top: 5px;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 8pt;
            color: #6B7280;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <table class="letterhead">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo">
            </td>
            <td class="school-info">
                <div class="foundation">YAYASAN PESAT BIRRUL WALIDAIN</div>
                <div class="name">SMK INFORMATIKA PESAT</div>
                <div class="address">
                    Jalan Poras No. 7 Sindang Barang Loji, Kota Bogor · Telp. (0251) 8346223<br>
                    Email: smkit.pesat@gmail.com · Website: www.smkpesat.sch.id
                </div>
            </td>
        </tr>
    </table>

    {{-- TITLE --}}
    <div class="title-section">
        <h1>Surat Tugas</h1>
        <p class="nomor">Nomor : {{ $pengajuan->nomor_surat }}</p>
    </div>

    {{-- OPENING --}}
    <p class="opening">
        Kepala SMK Informatika Pesat Kota Bogor dengan ini menugaskan kepada :
    </p>

    {{-- TABEL PANITIA (3 Kolom) --}}
    <table class="table-panitia">
        <thead>
            <tr>
                <th class="col-no">NO</th>
                <th class="col-nama">NAMA</th>
                <th class="col-ket">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuan->panitia ?? [] as $index => $anggota)
                @php
                    $nama = is_array($anggota) ? ($anggota['nama'] ?? '—') : $anggota;
                    $jabatan = is_array($anggota) ? ($anggota['jabatan'] ?? '—') : '—';
                    $keterangan = is_array($anggota) ? ($anggota['keterangan'] ?? '—') : '—';
                @endphp
                <tr>
                    <td class="col-no">{{ $index + 1 }}.</td>
                    <td>
                        {{ $nama }}
                        @if($jabatan !== '—')
                            <br><span style="font-size: 9pt; color: #555;">({{ $jabatan }})</span>
                        @endif
                    </td>
                    <td>{{ $keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; font-style: italic;">Tidak ada data guru/panitia yang dicantumkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- KEGIATAN --}}
    <p class="opening" style="margin-bottom: 8px;">
        Sebagai Panitia dalam Kegiatan <strong>{{ $pengajuan->nama_kegiatan }}</strong> yang akan dilaksanakan pada:
    </p>

    <div class="activity-info">
        <table>
            <tr>
                <td>Hari</td>
                <td>:</td>
                <td>{{ $pengajuan->tanggal_pelaksanaan->locale('id')->translatedFormat('l') }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $pengajuan->tanggal_pelaksanaan->locale('id')->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ $pengajuan->waktu_mulai ?? '07.00' }} WIB s.d {{ $pengajuan->waktu_selesai ?? 'Selesai' }}</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $pengajuan->tempat ?: 'SMK Informatika Pesat' }}</td>
            </tr>
        </table>
    </div>

    {{-- CLOSING --}}
    <p class="closing">
        Demikian surat tugas ini diberikan untuk dilaksanakan dengan penuh tanggung jawab.
    </p>

    {{-- TANDA TANGAN --}}
    <table class="signature-section">
        <tr>
            <td></td>
            <td>
                <div class="signature-box">
                    <div class="date">Bogor, {{ now()->locale('id')->translatedFormat('d F Y') }}</div>
                    <div class="role">Kepala Sekolah,</div>
                    <div class="name">{{ $pengajuan->reviewer?->name ?? 'Adhie Rachmat Saputra, S.Kom.' }}</div>
                    <div style="font-size: 10pt; margin-top: 2px;">
                        {{ $pengajuan->reviewer?->nip ? 'NIP. '.$pengajuan->reviewer->nip : '' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Dokumen Resmi SMK Informatika Pesat · Diterbitkan melalui SIMAS
    </div>

</body>
</html>