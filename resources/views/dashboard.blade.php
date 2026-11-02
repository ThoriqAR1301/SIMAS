<x-app-layout title="Dashboard">
    @php
        $user    = auth()->user();
        $isAdmin = \App\Support\Peran::isAdmin($user);

        $base = \App\Models\SuratIzin::query();
        if (!$isAdmin) { $base->where('user_id', $user->id); }

        $total     = (clone $base)->count();
        $pending   = (clone $base)->where('status', 'pending')->count();
        $disetujui = (clone $base)->where('status', 'disetujui')->count();
        $ditolak   = (clone $base)->where('status', 'ditolak')->count();
        $terbaru   = (clone $base)->latest()->take(5)->get();
        \Carbon\Carbon::setLocale('id');
    @endphp

    <div class="hello">
        <h1>Halo, {{ $user->name }}</h1>
        <p>{{ now()->translatedFormat('l, d F Y') }} &middot;
            {{ $isAdmin ? 'Ada '.$pending.' surat izin menunggu persetujuan.' : 'Kelola surat izin kamu dengan mudah.' }}</p>
    </div>

    <div class="stats">
        <div class="stat"><b>{{ $total }}</b><span>{{ $isAdmin ? 'Semua surat izin' : 'Total surat izin saya' }}</span></div>
        <div class="stat"><b>{{ $pending }}</b><span>Menunggu</span></div>
        <div class="stat"><b>{{ $disetujui }}</b><span>Disetujui</span></div>
        <div class="stat"><b>{{ $ditolak }}</b><span>Ditolak</span></div>
    </div>

    <div class="actions">
        <a href="{{ route('surat-izin.create') }}" class="action">
            <div class="ic"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg></div>
            <b>Buat Surat Izin</b><span>Isi data, tanda tangan, print PDF</span>
        </a>
        @if($isAdmin)
        <a href="{{ route('admin.surat-izin.index') }}" class="action">
            <div class="ic"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></div>
            <b>Kelola Surat Izin</b><span>Setujui, tolak, dan print</span>
        </a>
        @endif
        <a href="{{ route('pengajuan.index') }}" class="action">
            <div class="ic"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/></svg></div>
            <b>Pengajuan Surat</b><span>Surat kegiatan & lainnya</span>
        </a>
    </div>

    <div class="card">
        <div class="top" style="margin-bottom:14px">
            <b style="font-size:16px">{{ $isAdmin ? 'Surat izin terbaru (semua guru & pegawai)' : 'Surat izin saya' }}</b>
            <a href="{{ $isAdmin ? route('admin.surat-izin.index') : route('surat-izin.index') }}" class="btn btn-ghost">Lihat semua</a>
        </div>

        @if($terbaru->count())
        <div class="table-wrap">
            <table>
                <thead><tr><th>Tgl Izin</th><th>Nama</th><th>Jabatan</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($terbaru as $s)
                    <tr>
                        <td style="white-space:nowrap">{{ $s->tanggal_izin->translatedFormat('d M Y') }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->jabatan }}</td>
                        <td><span class="badge b-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
                        <td style="text-align:right">
                            <a href="{{ $isAdmin ? route('admin.surat-izin.show', $s) : route('surat-izin.show', $s) }}" class="btn btn-ghost">Lihat</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="empty">Belum ada surat izin.</div>
        @endif
    </div>
</x-app-layout>