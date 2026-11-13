@php
    $edit = isset($surat);
    $namaAkun = auth()->user()->name;
    $jabatanAkun = \App\Support\Peran::data()['label'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">Nama</label>
        <div class="relative">
            <input type="text" value="{{ $namaAkun }}" readonly 
                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 cursor-not-allowed outline-none focus:ring-0">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
        </div>
        <div class="text-[11px] font-semibold text-gray-400 mt-2">Sesuai nama akun, tidak bisa diubah</div>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan</label>
        <div class="relative">
            <input type="text" value="{{ $jabatanAkun }}" readonly 
                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 cursor-not-allowed outline-none focus:ring-0">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
        </div>
        <div class="text-[11px] font-semibold text-gray-400 mt-2">Otomatis dari role akun</div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Izin <span class="text-rose-500">*</span></label>
        <input type="date" name="tanggal_izin"
               value="{{ old('tanggal_izin', $edit ? $surat->tanggal_izin->format('Y-m-d') : '') }}" required
               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none">
        @error('tanggal_izin')
            <div class="text-xs font-bold text-rose-500 mt-2 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-2">Alasan / Keperluan <span class="text-rose-500">*</span></label>
        <textarea name="alasan" rows="4" placeholder="Contoh : Menghadiri acara keluarga..." required
                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none resize-none placeholder:text-gray-400 placeholder:font-normal">{{ old('alasan', $edit ? $surat->alasan : '') }}</textarea>
        @error('alasan')
            <div class="text-xs font-bold text-rose-500 mt-2 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="md:col-span-2 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
        <label class="block text-sm font-bold text-gray-700 mb-4">Tanda Tangan Digital <span class="text-rose-500">*</span></label>

        @if($edit && $surat->tanda_tangan)
            <div class="mb-6 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm inline-block w-full max-w-sm">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Tanda tangan saat ini :</div>
                <div class="bg-gray-50 rounded-xl p-4 flex justify-center border border-gray-100">
                    <img src="{{ $surat->tanda_tangan }}" class="h-[80px] object-contain">
                </div>
                <div class="text-[11px] font-semibold text-gray-400 mt-4 leading-relaxed">
                    Gambar ulang di kotak bawah untuk menggantinya, atau biarkan kosong untuk tetap memakai yang ini.
                </div>
                <label class="mt-4 flex items-center gap-3 p-3 bg-rose-50 rounded-xl border border-rose-100 cursor-pointer hover:bg-rose-100 transition-colors">
                    <input type="checkbox" name="hapus_ttd" value="1" class="w-4 h-4 text-rose-600 rounded border-gray-300 focus:ring-rose-500">
                    <span class="text-sm font-bold text-rose-700">Hapus tanda tangan lama</span>
                </label>
            </div>
        @endif

        <div class="relative w-full h-48 md:h-64 bg-white border-2 border-dashed border-gray-300 rounded-2xl overflow-hidden hover:border-[#F4B22E]/50 hover:bg-[#F4B22E]/5 transition-all duration-300 group">
            <canvas id="sig" class="sig w-full h-full cursor-crosshair touch-none"></canvas>
            <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center text-gray-300 group-hover:text-[#F4B22E]/50 transition-colors" id="sigPlaceholder">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span class="text-sm font-bold">Gambar tanda tangan di sini</span>
            </div>
        </div>
        
        <input type="hidden" name="tanda_tangan" id="tanda_tangan">
        
        <div class="flex justify-end mt-4">
            <button type="button" id="clearSig" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 hover:border-gray-300 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus Coretan
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    const canvas = document.getElementById('sig');
    const ctx = canvas.getContext('2d');
    const input = document.getElementById('tanda_tangan');
    const placeholder = document.getElementById('sigPlaceholder'); 
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
    function start(e) { e.preventDefault(); drawing = true; if(placeholder) placeholder.style.display = 'none'; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }
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
        if(placeholder) placeholder.style.display = 'flex'; 
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