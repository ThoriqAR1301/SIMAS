<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16pt; }
        .header p { margin: 2px 0; font-size: 10pt; }
        .nomor { text-align: center; margin: 20px 0; font-weight: bold; text-decoration: underline; }
        .content { text-align: justify; }
        .qr { position: fixed; bottom: 40px; right: 40px; text-align: center; }
        .qr img { width: 100px; }
        .qr small { font-size: 8pt; }
        .footer { margin-top: 60px; text-align: right; }
        table.info { margin: 20px 0 20px 40px; }
        table.info td { padding: 3px 8px; vertical-align: top; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PEMERINTAH KABUPATEN/KOTA</h2>
        <h2>SEKOLAH MENENGAH KEJURUAN (SMK) INFORMATIKA PESAT</h2>
        <p>Jl. Poras No.07, RT.01/RW.04, Loji, Kec. Bogor Bar., Kota Bogor, Jawa Barat 16117, Telp. 0877-1117-7442</p>
        <p>Email : https://smkpesat.sch.id/</p>
    </div>

    <div class="nomor">
        Nomor: {{ $pengajuan->nomor_surat }}
    </div>

    <div class="content">
        <p>Yang Bertanda Tangan Di Bawah Ini, Kepala SMK Informatika Pesat, Dengan Ini Menerangkan Bahwa :</p>

        <table class="info">
            <tr><td>Nama</td><td>:</td><td><strong>{{ $pengajuan->user->name }}</strong></td></tr>
            <tr><td>NIP</td><td>:</td><td>{{ $pengajuan->user->nip ?? '-' }}</td></tr>
            <tr><td>Jabatan</td><td>:</td><td>{{ $pengajuan->user->jabatan ?? '-' }}</td></tr>
        </table>

        <p>Benar Yang Bersangkutan Mengajukan <strong>{{ $pengajuan->jenis_surat }}</strong> Dengan Detail :</p>

        <table class="info">
            <tr><td>Nama Kegiatan</td><td>:</td><td>{{ $pengajuan->nama_kegiatan }}</td></tr>
            <tr><td>Tanggal</td><td>:</td><td>{{ $pengajuan->tanggal_pelaksanaan->translatedFormat('d F Y') }}</td></tr>
            @if($pengajuan->waktu_mulai)
                <tr><td>Waktu</td><td>:</td><td>{{ $pengajuan->waktu_mulai }} – {{ $pengajuan->waktu_selesai }}</td></tr>
            @endif
            <tr><td>Tempat</td><td>:</td><td>{{ $pengajuan->tempat ?? '-' }}</td></tr>
        </table>

        <p>Demikian Surat Ini Dibuat Untuk Dipergunakan Sebagaimana Mestinya</p>
    </div>

    <div class="footer">
        <p>{{ now()->translatedFormat('d F Y') }}</p>
        <p>Kepala Sekolah,</p>
        <br><br><br>
        <p><strong><u>{{ $pengajuan->reviewer->name ?? 'Kepala Sekolah' }}</u></strong></p>
        <p>NIP. {{ $pengajuan->reviewer->nip ?? '-' }}</p>
    </div>

    @if($qrPath)
        <div class="qr">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($qrPath)) }}" alt="QR">
            <br>
            <small>Scan Untuk Verifikasi</small>
        </div>
    @endif
</body>
</html>