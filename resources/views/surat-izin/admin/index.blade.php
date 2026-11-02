@extends('surat-izin.layout')
@section('title', 'Kelola Surat Izin')

@section('content')
<div class="top">
    <div>
        <h1>Kelola Surat Izin</h1>
        <p class="sub">Semua pengajuan izin guru & pegawai</p>
    </div>
</div>

<div class="stats">
    <div class="stat"><b>{{ $hitung['pending'] }}</b><span>Menunggu persetujuan</span></div>
    <div class="stat"><b>{{ $hitung['disetujui'] }}</b><span>Disetujui</span></div>
    <div class="stat"><b>{{ $hitung['ditolak'] }}</b><span>Ditolak</span></div>
</div>

<div class="card">
    <form method="GET" class="filters">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau alasan...">
        <select name="jabatan">
            <option value="">Semua jabatan</option>
            <option value="Guru" @selected(request('jabatan')=='Guru')>Guru</option>
            <option value="Pegawai" @selected(request('jabatan')=='Pegawai')>Pegawai</option>
        </select>
        <select name="status">
            <option value="">Semua status</option>
            <option value="pending" @selected(request('status')=='pending')>Pending</option>
            <option value="disetujui" @selected(request('status')=='disetujui')>Disetujui</option>
            <option value="ditolak" @selected(request('status')=='ditolak')>Ditolak</option>
        </select>
        <button class="btn btn-primary" type="submit">Filter</button>
        <a href="{{ route('admin.surat-izin.index') }}" class="btn btn-ghost">Reset</a>
    </form>

    @if($surats->count())
    <div class="table-wrap">
    <table>
        <thead>
            <tr><th>Tgl Izin</th><th>Nama</th><th>Jabatan</th><th>Alasan</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
        @foreach($surats as $s)
            <tr>
                <td style="white-space:nowrap">{{ $s->tanggal_izin->locale('id')->translatedFormat('d M Y') }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->jabatan }}</td>
                <td>{{ \Illuminate\Support\Str::limit($s->alasan, 40) }}</td>
                <td><span class="badge b-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="{{ route('admin.surat-izin.show', $s) }}" class="btn btn-ghost">Detail</a>
                    <a href="{{ route('admin.surat-izin.pdf', $s) }}" target="_blank" class="btn btn-ghost">Print</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <div style="margin-top:16px">{{ $surats->links() }}</div>
    @else
        <div class="empty">Tidak ada surat izin yang cocok.</div>
    @endif
</div>
@endsection