@extends('surat-izin.layout')
@section('title', 'Detail Surat Izin (Admin)')

@section('content')
<div class="top">
    <div>
        <h1>Detail Surat Izin</h1>
        <p class="sub">Status: <span class="badge b-{{ $surat->status }}">{{ ucfirst($surat->status) }}</span></p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="{{ route('admin.surat-izin.index') }}" class="btn btn-ghost">← Kembali</a>
        <a href="{{ route('admin.surat-izin.pdf', $surat) }}" target="_blank" class="btn btn-ghost">Buka PDF / Print</a>
        <a href="{{ route('admin.surat-izin.pdf', [$surat, 'download' => 1]) }}" class="btn btn-primary">Download PDF</a>
    </div>
</div>

<div class="card" style="margin-bottom:20px">
    <dl class="detail">
        <dt>Nama</dt><dd>{{ $surat->nama }}</dd>
        <dt>Jabatan</dt><dd>{{ $surat->jabatan }}</dd>
        <dt>Tanggal surat</dt><dd>{{ $surat->tanggal_surat->locale('id')->translatedFormat('d F Y') }}</dd>
        <dt>Tanggal izin</dt><dd>{{ $surat->tanggal_izin->locale('id')->translatedFormat('l, d F Y') }}</dd>
        <dt>Alasan</dt><dd>{{ $surat->alasan }}</dd>
        @if($surat->status !== 'pending')
            <dt>Diproses oleh</dt><dd>{{ $surat->pemroses->name ?? '-' }} ({{ optional($surat->diproses_pada)?->locale("id")?->translatedFormat('d M Y H:i') }})</dd>
            <dt>Catatan</dt><dd>{{ $surat->catatan_admin ?: '-' }}</dd>
        @endif
    </dl>
</div>

@if($surat->status === 'pending')
<div class="card" style="margin-bottom:20px">
    <form method="POST" action="{{ route('admin.surat-izin.setujui', $surat) }}" style="margin-bottom:22px">
        @csrf
        <label>Catatan (opsional)</label>
        <input type="text" name="catatan_admin" placeholder="Contoh: Disetujui, jangan lupa serahkan tugas ke piket">
        <button type="submit" class="btn btn-green" style="margin-top:12px">✓ Setujui</button>
    </form>

    <form method="POST" action="{{ route('admin.surat-izin.tolak', $surat) }}">
        @csrf
        <label>Alasan penolakan</label>
        <textarea name="catatan_admin" rows="2" placeholder="Wajib diisi jika menolak" required></textarea>
        @error('catatan_admin')<div class="err">{{ $message }}</div>@enderror
        <button type="submit" class="btn btn-red" style="margin-top:12px">✕ Tolak</button>
    </form>
</div>
@endif

<div class="card" style="padding:0;overflow:hidden">
    <iframe src="{{ route('admin.surat-izin.pdf', $surat) }}" style="width:100%;height:80vh;border:0"></iframe>
</div>
@endsection