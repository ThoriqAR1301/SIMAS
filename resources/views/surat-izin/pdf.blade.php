<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin</title>
    <style>
        @page { margin: 2.2cm 2.5cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; color: #000; line-height: 1.6; }
        .right { text-align: right; }
        table.data td { padding: 2px 0; vertical-align: top; }
        p { margin: 0 0 10px 0; text-align: justify; }
        .ttd { text-align: center; }
        .ttd-img { height: 80px; text-align: center; margin: 6px 0; }
        .ttd-img img { height: 80px; }
    </style>
</head>
<body>
    @php
        \Carbon\Carbon::setLocale('id');
        $tglSurat = $surat->tanggal_surat->translatedFormat('d F Y');
        $hari     = $surat->tanggal_izin->translatedFormat('l');
        $tglIzin  = $surat->tanggal_izin->translatedFormat('d F Y');
    @endphp

    <div class="right">Bogor, {{ $tglSurat }}</div>

    <br><br>
    <div>Kepada</div>
    <table style="border-collapse:collapse">
        <tr>
            <td style="width:45px">Yth.</td>
            <td>Kepala SMK Informatika Pesat<br>di tempat</td>
        </tr>
    </table>

    <br><br>
    <p>Assalamu'alaikum W. W.</p>
    <p>Dengan Hormat,</p>
    <p>Saya yang bertanda tangan dibawah ini:</p>

    <table class="data" style="margin-left:5px;margin-bottom:12px">
        <tr><td style="width:90px">Nama</td><td style="width:15px">:</td><td>{{ $surat->nama }}</td></tr>
        <tr><td>Jabatan</td><td>:</td><td>{{ $surat->jabatan }}</td></tr>
    </table>

    <p>
        Dengan ini mengajukan permohonan izin pada hari <b>{{ $hari }}</b>,
        tanggal <b>{{ $tglIzin }}</b>, dikarenakan {{ $surat->alasan }}.
    </p>

    <p>Demikian surat izin ini saya ajukan, atas perhatiannya saya ucapkan terima kasih.</p>

    <br>
    <p>Wassalamu'alaikum W.W.</p>

    <br>
    <table style="width:100%">
        <tr>
            <td style="width:55%"></td>
            <td class="ttd" style="width:45%">
                Hormat Saya
                <div class="ttd-img">
                    @if($surat->tanda_tangan)
                        <img src="{{ $surat->tanda_tangan }}">
                    @endif
                </div>
                ( {{ $surat->nama }} )
            </td>
        </tr>
    </table>
</body>
</html>