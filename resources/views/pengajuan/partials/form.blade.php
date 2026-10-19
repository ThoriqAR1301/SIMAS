@php
    $isEdit = $pengajuan !== null;
    $val = fn($field, $default = '') => old($field, $isEdit ? $pengajuan->$field : $default);
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Jenis Blanko Surat <span class="text-red-500">*</span>
    </label>
    <select name="blanko_id" required class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
        <option value="">-- Pilih Blanko --</option>
        @foreach($blanko as $b)
            <option value="{{ $b->id }}" data-jenis="{{ $b->jenis }}" @selected($val('blanko_id') == $b->id)>
                {{ $b->nama }} ({{ $b->kode }})
            </option>
        @endforeach
    </select>
    @error('blanko_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Jenis Surat <span class="text-red-500">*</span>
    </label>
    <input type="text" name="jenis_surat" value="{{ $val('jenis_surat') }}" required class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
    @error('jenis_surat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Nama Kegiatan <span class="text-red-500">*</span>
    </label>
    <input type="text" name="nama_kegiatan" value="{{ $val('nama_kegiatan') }}" required class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
    @error('nama_kegiatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Tanggal Pelaksanaan <span class="text-red-500">*</span>
        </label>
        <input type="date" name="tanggal_pelaksanaan" value="{{ $val('tanggal_pelaksanaan', now()->format('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
        @error('tanggal_pelaksanaan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Waktu Mulai
        </label>
        <input type="time" name="waktu_mulai" value="{{ $val('waktu_mulai') }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Waktu Selesai
        </label>
        <input type="time" name="waktu_selesai" value="{{ $val('waktu_selesai') }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
        @error('waktu_selesai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat</label>
    <input type="text" name="tempat" value="{{ $val('tempat') }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
    <textarea name="keterangan" rows="3" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">{{ $val('keterangan') }}</textarea>
</div>

<div x-data="{ files: [] }">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Lampiran (Maks 5 file, PDF/JPG/PNG, Maks 5MB)
    </label>
    <input type="file" name="lampiran[]" multiple accept=".pdf,.jpg,.jpeg,.png" @change="files = Array.from($event.target.files)" class="w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    <template x-if="files.length">
        <ul class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
            <template x-for="f in files" :key="f.name">
                <li>• <span x-text="f.name"></span></li>
            </template>
        </ul>
    </template>
    @if($isEdit && $pengajuan->lampiran->count())
        <div class="mt-3 text-xs">
            <p class="font-medium text-gray-600 dark:text-gray-400 mb-1">Lampiran Tersimpan :</p>
            <ul class="space-y-1">
                @foreach($pengajuan->lampiran as $lap)
                    <li>
                        <a href="{{ $lap->url() }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $lap->nama_file }}
                        </a>
                        <span class="text-gray-400">({{ $lap->ukuranFormat() }})</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>