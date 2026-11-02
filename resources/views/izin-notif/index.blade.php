<x-app-layout title="Notifikasi">
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <style>
        .nitem{display:block;width:100%;text-align:left;background:#fff;border:0;border-bottom:1px solid #e5e7eb;padding:16px 18px;cursor:pointer;font-family:inherit;transition:.15s}
        .nitem:hover{background:#f9fafb}
        .nitem b{display:block;font-size:14px;color:#111827}
        .nitem span{display:block;font-size:13px;color:#6b7280;margin-top:2px}
        .nitem small{display:block;font-size:12px;color:#9ca3af;margin-top:6px}
        .nform:last-child .nitem{border-bottom:0}
    </style>

    <div class="top">
        <div>
            <h1>Notifikasi</h1>
            <p class="sub">Klik notifikasi untuk membuka suratnya. Notifikasi yang sudah dilihat otomatis hilang.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('notifikasi.index') }}" class="btn btn-ghost">Notifikasi Pengajuan →</a>
            @if($notifs->count())
                <form method="POST" action="{{ route('izin-notif.baca-semua') }}">
                    @csrf
                    <button class="btn btn-primary">Tandai semua dibaca</button>
                </form>
            @endif
        </div>
    </div>

    <div class="card" style="padding:0;overflow:hidden">
        @forelse($notifs as $n)
            <form method="POST" action="{{ route('izin-notif.baca', $n->id) }}" class="nform">
                @csrf
                <button type="submit" class="nitem">
                    <b>{{ $n->judul }}</b>
                    <span>{{ $n->pesan }}</span>
                    <small>{{ $n->created_at->diffForHumans() }}</small>
                </button>
            </form>
        @empty
            <div class="empty">Tidak ada notifikasi baru.</div>
        @endforelse
    </div>
</x-app-layout>