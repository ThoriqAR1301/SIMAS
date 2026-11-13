<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin - {{ $surat->nama }}</title>
    <style>
        @page { margin: 2cm 2.5cm 2cm 2.5cm; }
        
        body { 
            font-family: "Georgia", "Times New Roman", serif; 
            font-size: 11.5pt; 
            color: #1a1a1a; 
            line-height: 1.6; 
            margin: 0;
            padding: 0;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #1a1a1a;
            padding-bottom: 10px;
            margin-bottom: 5px;
        }
        .kop-surat h1 {
            font-family: Arial, sans-serif;
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .kop-surat h2 {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            font-weight: normal;
            margin: 5px 0 0 0;
            letter-spacing: 0.5px;
            color: #444;
        }
        .kop-surat p {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            margin: 5px 0 0 0;
            color: #666;
        }
        .garis-ganda {
            border-top: 1px solid #1a1a1a;
            margin-bottom: 25px;
        }

        .tanggal { text-align: right; margin-bottom: 20px; }
        .tujuan { margin-bottom: 20px; line-height: 1.4; }
        
        p { margin: 0 0 12px 0; text-align: justify; }
        
        table.data { 
            width: 100%; 
            margin-left: 15px; 
            margin-bottom: 20px; 
            border-collapse: collapse;
        }
        table.data td { 
            padding: 4px 0; 
            vertical-align: top; 
        }
        .label-col { width: 100px; font-weight: bold; }
        .separator-col { width: 15px; }

        .ttd-wrapper {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .ttd-table { width: 100%; }
        .ttd-kiri { width: 50%; }
        .ttd-kanan { 
            width: 50%; 
            text-align: left; 
            padding-left: 20px;
        }
        .ttd-area { 
            height: 90px; 
            margin: 10px 0; 
            position: relative;
        }
        .ttd-img { 
            height: 80px; 
            position: absolute;
            top: 0;
            left: 0;
        }
        .ttd-img img { 
            height: 80px; 
        }
        .materai {
            position: absolute;
            top: -10px;
            left: 100px;
            width: 60px;
            height: 40px;
            border: 1px dashed #aaa;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-size: 8pt;
            color: #aaa;
            transform: rotate(-15deg);
        }
        .nama-ttd { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="watermark">SMK INFORMATIKA PESAT</div>

    <div class="kop-surat">
        <h1>SMK INFORMATIKA PESAT</h1>
        <h2>Yayasan Pendidikan Teknologi Informasi</h2>
        <p>Jl. Raya Contoh No. 123, Bogor, Jawa Barat | Telp: (021) 1234567 | Email: info@smkpesat.sch.id</p>
    </div>
    <div class="garis-ganda"></div>

    @php
        \Carbon\Carbon::setLocale('id');
        $tglSurat = $surat->tanggal_surat->translatedFormat('d F Y');
        $hari = $surat->tanggal_izin->translatedFormat('l');
        $tglIzin = $surat->tanggal_izin->translatedFormat('d F Y');
    @endphp

    <div class="tanggal">Bogor, {{ $tglSurat }}</div>

    <div class="tujuan">
        Kepada,<br>
        <b>Yth. Kepala SMK Informatika Pesat</b><br>
        di tempat
    </div>


    <p>Assalamu'alaikum Warahmatullahi Wabarakatuh</p>
    <p>Dengan Hormat,</p>
    <p>Saya yang bertanda tangan dibawah ini :</p>

    <table class="data">
        <tr>
            <td class="label-col">Nama</td>
            <td class="separator-col">:</td>
            <td><b>{{ $surat->nama }}</b></td>
        </tr>
        <tr>
            <td class="label-col">Jabatan</td>
            <td class="separator-col">:</td>
            <td>{{ $surat->jabatan }}</td>
        </tr>
    </table>
    

    <p>
        Dengan ini mengajukan permohonan izin pada hari <b>{{ $hari }}</b>,
        tanggal <b>{{ $tglIzin }}</b>, dikarenakan {{ $surat->alasan }}.
    </p>

    <p>Demikian surat izin ini saya ajukan, atas perhatiannya saya ucapkan terima kasih.</p>


    <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh</p>

    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                <td class="ttd-kiri">
                </td>
                <td class="ttd-kanan">
                    Hormat Saya,<br>
                    <div class="ttd-area">
                        <div class="materai">Meterai</div>
                        
                        @if($surat->tanda_tangan)
                            <div class="ttd-img">
                                <img src="{{ $surat->tanda_tangan }}">
                            </div>
                        @endif
                    </div>
                    <span class="nama-ttd">( {{ $surat->nama }} )</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>