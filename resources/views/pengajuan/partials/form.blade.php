@php
    $isEdit = $pengajuan !== null;
    $val = fn($field, $default = '') => old($field, $isEdit ? $pengajuan->$field : $default);
@endphp

<style>
    .premium-input-wrapper { position: relative; }
    .premium-input-wrapper input,
    .premium-input-wrapper select,
    .premium-input-wrapper textarea {
        width: 100%;
        padding: 0.85rem 1rem 0.85rem 3rem;
        background-color: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: #0F172A;
        transition: all 0.3s ease;
    }
    .premium-input-wrapper input:focus,
    .premium-input-wrapper select:focus,
    .premium-input-wrapper textarea:focus {
        background-color: #ffffff;
        border-color: #FF5A1F;
        outline: none;
        box-shadow: 0 0 0 4px rgba(255, 90, 31, 0.1);
    }
    .premium-input-wrapper .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        transition: color 0.3s ease;
        pointer-events: none;
    }
    .premium-input-wrapper textarea ~ .input-icon {
        top: 1.25rem;
        transform: none;
    }
    .premium-input-wrapper input:focus ~ .input-icon,
    .premium-input-wrapper select:focus ~ .input-icon,
    .premium-input-wrapper textarea:focus ~ .input-icon {
        color: #FF5A1F;
    }
</style>

<div class="space-y-6">
    <div>
        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">
            Jenis Blanko Surat <span class="text-red-500">*</span>
        </label>
        <div class="premium-input-wrapper">
            <select name="blanko_id" required>
                <option value="">-- Pilih Blanko --</option>
                @foreach($blanko as $b)
                    <option value="{{ $b->id }}" data-jenis="{{ $b->jenis }}" @selected($val('blanko_id') == $b->id)>
                        {{ $b->nama }} ({{ $b->kode }})
                    </option>
                @endforeach
            </select>
            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        @error('blanko_id') <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">
            Jenis Surat <span class="text-red-500">*</span>
        </label>
        <div class="premium-input-wrapper">
            <input type="text" name="jenis_surat" value="{{ $val('jenis_surat') }}" required placeholder="Contoh: Surat Tugas / Undangan">
            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        @error('jenis_surat') <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">
            Nama Kegiatan <span class="text-red-500">*</span>
        </label>
        <div class="premium-input-wrapper">
            <input type="text" name="nama_kegiatan" value="{{ $val('nama_kegiatan') }}" required placeholder="Contoh: Rapat Koordinasi MGMP">
            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        @error('nama_kegiatan') <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">
                Tanggal <span class="text-red-500">*</span>
            </label>
            <div class="premium-input-wrapper">
                <input type="date" name="tanggal_pelaksanaan" value="{{ $val('tanggal_pelaksanaan', now()->format('Y-m-d')) }}" required>
                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            @error('tanggal_pelaksanaan') <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Waktu Mulai</label>
            <div class="premium-input-wrapper">
                <input type="time" name="waktu_mulai" value="{{ $val('waktu_mulai') }}">
                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div>
            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Waktu Selesai</label>
            <div class="premium-input-wrapper">
                <input type="time" name="waktu_selesai" value="{{ $val('waktu_selesai') }}">
                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            @error('waktu_selesai') <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Tempat</label>
        <div class="premium-input-wrapper">
            <input type="text" name="tempat" value="{{ $val('tempat') }}" placeholder="Contoh: Aula Utama Sekolah">
            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
    </div>

    <div>
        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Keterangan</label>
        <div class="premium-input-wrapper">
            <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan penting di sini (opsional)">{{ $val('keterangan') }}</textarea>
            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
        </div>
    </div>

    <div x-data="{ files: [] }">
        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">
            Lampiran (Maks 5 file, PDF/JPG/PNG, Maks 5MB)
        </label>
        
        <div class="relative w-full border-2 border-dashed border-gray-300 hover:border-[#FF5A1F] transition-colors rounded-2xl bg-gray-50 hover:bg-orange-50/30 p-6 flex flex-col items-center justify-center text-center cursor-pointer group">
            <input type="file" name="lampiran[]" multiple accept=".pdf,.jpg,.jpeg,.png" @change="files = Array.from($event.target.files)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
            
            <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-gray-400 group-hover:text-[#FF5A1F] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            </div>
            <p class="text-sm font-bold text-gray-700 group-hover:text-[#FF5A1F] transition-colors">Klik untuk upload file baru</p>
            <p class="text-xs font-medium text-gray-400 mt-1">Bisa pilih lebih dari satu file</p>
        </div>

        <template x-if="files.length">
            <div class="mt-4 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">File yang akan diunggah:</p>
                <template x-for="f in files" :key="f.name">
                    <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg border border-gray-100 text-xs font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-[#FF5A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <span x-text="f.name" class="truncate"></span>
                    </div>
                </template>
            </div>
        </template>

        @if($isEdit && $pengajuan->lampiran->count())
            <div class="mt-5 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">File tersimpan sebelumnya :</p>
                <ul class="space-y-2">
                    @foreach($pengajuan->lampiran as $lap)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <a href="{{ $lap->url() }}" target="_blank" class="text-xs font-bold text-brand-700 hover:text-[#FF5A1F] hover:underline transition-colors">
                                {{ $lap->nama_file }}
                            </a>
                            <span class="text-[10px] font-bold text-gray-400">({{ $lap->ukuranFormat() }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>