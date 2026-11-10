@php
    $edit = isset($surat);
    $namaAkun    = auth()->user()->name;
    $jabatanAkun = \App\Support\Peran::data()['label'];
@endphp

<div class="grid">
    <div>
        <label>Nama</label>
        <input type="text" value="{{ $namaAkun }}" readonly style="background:#f3f4f6;color:#6b7280;cursor:not-allowed">
        <div style="font-size:12px;color:#6b7280;margin-top:4px">Sesuai nama akun, tidak bisa diubah.</div>
    </div>
    <div>
        <label>Jabatan</label>
        <input type="text" value="{{ $jabatanAkun }}" readonly style="background:#f3f4f6;color:#6b7280;cursor:not-allowed">
        <div style="font-size:12px;color:#6b7280;margin-top:4px">Otomatis dari role akun.</div>
    </div>
    <div class="full">
        <label>Tanggal Izin</label>
        <input type="date" name="tanggal_izin"
               value="{{ old('tanggal_izin', $edit ? $surat->tanggal_izin->format('Y-m-d') : '') }}" required>
        @error('tanggal_izin')<div class="err">{{ $message }}</div>@enderror
    </div>
    <div class="full">
        <label>Alasan / Keperluan</label>
        <textarea name="alasan" rows="4" placeholder="Contoh: menghadiri acara keluarga" required>{{ old('alasan', $edit ? $surat->alasan : '') }}</textarea>
        @error('alasan')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div class="full">
        <label>Tanda Tangan</label>

        @if($edit && $surat->tanda_tangan)
            <div style="margin-bottom:12px">
                <div style="font-size:13px;color:#6b7280;margin-bottom:6px">Tanda tangan saat ini:</div>
                <div style="border:1px solid #e5e7eb;border-radius:12px;padding:12px;display:inline-block;background:#fff">
                    <img src="{{ $surat->tanda_tangan }}" style="height:70px;display:block">
                </div>
                <div style="font-size:13px;color:#6b7280;margin-top:6px">
                    Gambar ulang di kotak bawah untuk menggantinya, atau biarkan kosong untuk tetap memakai yang ini.
                </div>
                <label style="font-weight:500;margin-top:8px;display:flex;gap:8px;align-items:center">
                    <input type="checkbox" name="hapus_ttd" value="1" style="width:auto"> Hapus tanda tangan
                </label>
            </div>
        @endif

        <canvas id="sig" class="sig"></canvas>
        <input type="hidden" name="tanda_tangan" id="tanda_tangan">
        <button type="button" class="btn btn-ghost" id="clearSig" style="margin-top:10px">Hapus coretan</button>
    </div>
</div>

<script>
(function () {
    const canvas = document.getElementById('sig');
    const ctx = canvas.getContext('2d');
    const input = document.getElementById('tanda_tangan');
    let drawing = false, dirty = false;

    function setup() {
        const r = canvas.getBoundingClientRect();
        canvas.width = r.width; canvas.height = r.height;
        ctx.lineWidth = 2.4; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#111';
    }
    setup();

    function pos(e) {
        const r = canvas.getBoundingClientRect();
        const p = e.touches ? e.touches[0] : e;
        return { x: p.clientX - r.left, y: p.clientY - r.top };
    }
    function start(e) { e.preventDefault(); drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }
    function move(e) { if (!drawing) return; e.preventDefault(); const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); dirty = true; }
    function end() { drawing = false; }

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    window.addEventListener('mouseup', end);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', move, { passive: false });
    canvas.addEventListener('touchend', end);

    document.getElementById('clearSig').onclick = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height); dirty = false; input.value = '';
    };

    function cropped() {
        const w = canvas.width, h = canvas.height;
        const d = ctx.getImageData(0, 0, w, h).data;
        let minX = w, minY = h, maxX = 0, maxY = 0;
        for (let y = 0; y < h; y++) {
            for (let x = 0; x < w; x++) {
                if (d[(y * w + x) * 4 + 3] > 0) {
                    if (x < minX) minX = x;
                    if (x > maxX) maxX = x;
                    if (y < minY) minY = y;
                    if (y > maxY) maxY = y;
                }
            }
        }
        const pad = 6;
        minX = Math.max(0, minX - pad); minY = Math.max(0, minY - pad);
        maxX = Math.min(w - 1, maxX + pad); maxY = Math.min(h - 1, maxY + pad);
        const cw = maxX - minX + 1, ch = maxY - minY + 1;
        const c = document.createElement('canvas');
        c.width = cw; c.height = ch;
        c.getContext('2d').drawImage(canvas, minX, minY, cw, ch, 0, 0, cw, ch);
        return c.toDataURL('image/png');
    }

    canvas.closest('form').addEventListener('submit', () => {
        input.value = dirty ? cropped() : '';
    });
})();
</script>