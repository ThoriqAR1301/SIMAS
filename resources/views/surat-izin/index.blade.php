<x-app-layout title="Surat Izin">
    <div class="top">
        <div>
            <h1>Surat Izin</h1>
            <p class="sub">Riwayat pengajuan izin guru & pegawai</p>
        </div>
        <a href="{{ route('surat-izin.create') }}" class="btn btn-primary">+ Buat Surat Izin</a>
    </div>

    <div class="card">
        @if($surats->count())
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Tanggal Izin</th><th>Nama</th><th>Jabatan</th><th>Alasan</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                @foreach($surats as $s)
                    <tr>
                        <td style="white-space:nowrap">{{ $s->tanggal_izin->locale('id')->translatedFormat('d M Y') }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->jabatan }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($s->alasan, 40) }}</td>
                        <td><span class="badge b-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
                        <td style="text-align:right">
                            <a href="{{ route('surat-izin.show', $s) }}" class="btn btn-ghost">Lihat</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px">{{ $surats->links() }}</div>
        @else
            <div class="empty">Belum ada surat izin.<br>Klik "Buat Surat Izin" untuk memulai.</div>
        @endif
    </div>
</x-app-layout>