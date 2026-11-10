<x-app-layout title="Detail Surat Izin">
    <div class="top">
        <div>
            <h1>Surat Izin</h1>
            <p class="sub">Status: <span class="badge b-{{ $surat->status }}">{{ ucfirst($surat->status) }}</span></p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('surat-izin.index') }}" class="btn btn-ghost">← Kembali</a>
            @if($surat->status === 'pending' && $surat->user_id === auth()->id())
                <a href="{{ route('surat-izin.edit', $surat) }}" class="btn btn-ghost">Edit</a>
            @endif
            <a href="{{ route('surat-izin.pdf', $surat) }}" target="_blank" class="btn btn-ghost">Buka PDF / Print</a>
            <a href="{{ route('surat-izin.pdf', [$surat, 'download' => 1]) }}" class="btn btn-primary">Download PDF</a>
        </div>
    </div>

    <div class="card" style="padding:0;overflow:hidden">
        <iframe src="{{ route('surat-izin.pdf', $surat) }}" style="width:100%;height:80vh;border:0"></iframe>
    </div>
</x-app-layout>