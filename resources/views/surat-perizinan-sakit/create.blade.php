<x-app-layout>
    <x-slot name="title">Pengajuan Surat Izin Sakit</x-slot>
    <x-slot name="subtitle">Formulir Pengajuan Resmi Administrasi Sekolah</x-slot>

    <div class="min-h-screen px-4 py-8 sm:px-6 lg:px-8" style="background-color: #F5F3EC;">

        <div class="mx-auto max-w-5xl space-y-6">

            {{-- HEADER --}}
            <section class="relative overflow-hidden rounded-2xl p-6 shadow-lg sm:p-8"
                style="background: linear-gradient(120deg, #1E2B55 0%, #293B70 100%);">

                <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full"
                    style="background: rgba(242,181,29,0.10);">
                </div>

                <div class="pointer-events-none absolute -bottom-20 right-1/3 h-52 w-52 rounded-full"
                    style="background: rgba(255,255,255,0.04);">
                </div>

                <div class="relative z-10 flex flex-col justify-between gap-5 md:flex-row md:items-center">

                    <div class="max-w-2xl">
                        <div class="mb-3 inline-flex items-center gap-2 rounded-full px-3 py-1.5"
                            style="background: rgba(242,181,29,0.15); border: 1px solid rgba(242,181,29,0.35);">
                            <span class="h-2 w-2 rounded-full" style="background-color: #F2B51D;"></span>
                            <span class="text-xs font-semibold tracking-wider" style="color: #F2B51D;">
                                LAYANAN ADMINISTRASI SEKOLAH
                            </span>
                        </div>

                        <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            Pengajuan Surat Izin Sakit
                        </h1>

                        <p class="mt-3 max-w-xl text-sm leading-relaxed sm:text-base" style="color: #E0E7F5;">
                            Gunakan formulir ini untuk mengajukan izin ketidakhadiran karena sakit.
                            Pastikan seluruh informasi dan dokumen pendukung sudah benar sebelum dikirim.
                        </p>
                    </div>

                    <div class="inline-flex w-fit items-center gap-3 rounded-xl px-4 py-3"
                        style="background: rgba(255,255,255,0.10); border: 1px solid rgba(242,181,29,0.35);">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg"
                            style="background-color: #F2B51D;">
                            <svg class="h-5 w-5" fill="none" stroke="#1E2B55" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs" style="color: #DCE4F5;">
                                Pengguna aktif
                            </p>
                            <p class="text-sm font-bold text-white">
                                Guru / Pegawai
                            </p>
                        </div>
                    </div>

                </div>
            </section>

            {{-- NOTIFIKASI SUKSES --}}
            @if(session('success'))
                <div class="flex items-start gap-3 rounded-xl border p-4 shadow-sm"
                    style="background-color: #EFFAF1; border-color: #A7D9B5;">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                        style="background-color: #D5F2DC;">
                        <svg class="h-6 w-6" fill="none" stroke="#23834A" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-bold" style="color: #23834A;">
                            Pengajuan Berhasil Dikirim!
                        </h3>
                        <p class="mt-1 text-sm leading-relaxed" style="color: #28663C;">
                            {{ session('success') }}
                        </p>
                        <p class="mt-2 text-xs" style="color: #43865A;">
                            Silakan pantau perkembangan pengajuan melalui menu Riwayat.
                        </p>
                    </div>
                </div>
            @endif

            {{-- NOTIFIKASI ERROR --}}
            @if($errors->any())
                <div class="rounded-xl border p-4" style="background-color: #FEF2F2; border-color: #FECACA;">
                    <div class="flex items-start gap-3">
                        <svg class="h-6 w-6 shrink-0" fill="none" stroke="#DC2626" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14.02A2 2 0 003.92 21h16.16a2 2 0 001.73-3.12l-8.1-14.02a2 2 0 00-3.42 0z" />
                        </svg>

                        <div>
                            <p class="font-semibold text-red-700">
                                Periksa kembali formulir kamu.
                            </p>
                            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">
                    <div class="flex items-start gap-3">
                        <span class="text-xl text-green-600">✓</span>
                        <div>
                            <p class="font-bold text-green-800">
                                Pengajuan Berhasil Dikirim!
                            </p>
                            <p class="mt-1 text-sm text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- FORM CARD --}}
            <section class="overflow-hidden rounded-2xl border bg-white shadow-lg" style="border-color: #E5E7EB;">

                {{-- JUDUL FORM --}}
                <div class="mt-5 px-6 py-5 sm:px-8" style="border-color: #E5E7EB; background-color: #FFFFFF;">
                    <h2 class="text-lg font-bold" style="color: #1E2B55;">
                        Formulir Pengajuan
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Lengkapi informasi berikut sesuai kondisi sebenarnya.
                    </p>

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5"
                            style="background-color: #FFF7DF; color: #946600;">
                            <span class="font-bold">*</span>
                            Wajib diisi
                        </span>
                        <span class="text-gray-400">
                            Data digunakan untuk keperluan administrasi sekolah.
                        </span>
                    </div>
                </div>

                <form action="{{ route('surat-perizinan-sakit.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-7 p-6 sm:p-8">
                    @csrf

                    {{-- PERIODE IZIN --}}
                    <div>
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg"
                                style="background-color: #E9EDF7; color: #1E2B55;">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-bold" style="color: #1E2B55;">
                                    Periode Izin
                                </h3>
                                <p class="text-xs text-gray-500">
                                    Tentukan tanggal awal dan akhir ketidakhadiran.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="tanggal_mulai" class="mb-2 block text-sm font-semibold"
                                    style="color: #1E2B55;">
                                    Tanggal Mulai Izin
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                    value="{{ old('tanggal_mulai') }}" required
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-100">

                                <p class="mt-2 text-xs text-gray-500">
                                    Pilih tanggal pertama kamu tidak dapat hadir.
                                </p>

                                @error('tanggal_mulai')
                                    <p class="mt-1 text-xs font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="mb-2 block text-sm font-semibold"
                                    style="color: #1E2B55;">
                                    Tanggal Selesai Izin
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                    value="{{ old('tanggal_selesai') }}" required
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-100">

                                <p class="mt-2 text-xs text-gray-500">
                                    Pilih tanggal terakhir kamu tidak dapat hadir.
                                </p>

                                @error('tanggal_selesai')
                                    <p class="mt-1 text-xs font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ALASAN SAKIT --}}
                    <div>
                        <div class="mb-4 flex items-center gap-3 mt-5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg"
                                style="background-color: #E9EDF7; color: #1E2B55;">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h8M8 14h5m-8 6h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-bold" style="color: #1E2B55;">
                                    Keterangan Sakit
                                </h3>
                                <p class="text-xs text-gray-500">
                                    Berikan informasi singkat mengenai kondisi kamu.
                                </p>
                            </div>
                        </div>

                        <label for="alasan" class="mb-2 block text-sm font-semibold" style="color: #1E2B55;">
                            Alasan Sakit
                            <span class="text-red-500">*</span>
                        </label>

                        <textarea id="alasan" name="alasan" rows="4" required maxlength="2000"
                            placeholder="Contoh: Mengalami demam dan batuk sehingga membutuhkan waktu istirahat..."
                            class="w-full resize-y rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-blue-700 focus:ring-2 focus:ring-blue-100">{{ old('alasan') }}</textarea>

                        <p class="mt-2 text-xs text-gray-500">
                            Jelaskan keluhan atau kondisi sakit secara singkat dan jelas.
                            Tidak perlu menuliskan informasi yang tidak relevan.
                        </p>

                        @error('alasan')
                            <p class="mt-1 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- SURAT DOKTER --}}
                    <div>
                        <div class="mt-5 mb-4 flex items-center gap-3">
                            <div class="flex h-10 w-9 items-center justify-center rounded-lg"
                                style="background-color: #FFF5D9; color: #946600;">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 4h8l4 4v12H4V4h4zm4 5v6m-3-3h6" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-bold" style="color: #1E2B55; margin-top: 10px;">
                                    Dokumen Pendukung
                                </h3>
                                <p class="text-xs text-gray-500">
                                    Lampirkan surat keterangan dari dokter.
                                </p>
                            </div>
                        </div>

                        <label for="surat_dokter" class="mb-2 block text-sm font-semibold" style="color: #1E2B55;">
                            Surat Keterangan Dokter
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="rounded-xl border-2 border-dashed p-5 sm:p-7"
                            style="border-color: #C9D1E2; background-color: #F8F9FC;">

                            <div class="flex flex-col items-center text-center">
                                <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full"
                                    style="background-color: #E9EDF7; color: #1E2B55;">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>

                                <p class="text-sm font-bold" style="color: #1E2B55;">
                                    Unggah Surat Keterangan Dokter
                                </p>

                                <p class="mt-1 max-w-md text-xs leading-relaxed text-gray-500">
                                    Pilih dokumen surat dokter dari perangkat kamu.
                                    Pastikan dokumen dapat dibaca dengan jelas.
                                </p>

                                <input type="file" id="surat_dokter" name="surat_dokter"
                                    accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" required
                                    class="mt-5 block w-full max-w-md cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:cursor-pointer file:border-0 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-white"
                                    style="--file-button-bg: #1E2B55;">

                                <p class="mt-3 text-xs font-medium text-gray-500">
                                    Format: PDF, JPG, PNG · Maksimal 5 MB
                                </p>
                            </div>
                        </div>

                        {{-- PREVIEW DOKUMEN --}}
                        <div id="preview-container" class="mt-5 hidden rounded-xl border p-4"
                            style="border-color: #D5DCEB; background-color: #FFFFFF;">

                            <div class="mb-3 flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold" style="color: #1E2B55;">
                                        Pratinjau Dokumen
                                    </p>
                                    <p id="preview-filename" class="mt-1 text-xs text-gray-500"></p>
                                </div>

                                <button type="button" id="remove-preview"
                                    class="rounded-lg px-3 py-2 text-xs font-semibold"
                                    style="background-color: #FEE2E2; color: #B91C1C;">
                                    Ganti Dokumen
                                </button>
                            </div>

                            <img id="image-preview" src="" alt="Preview surat dokter"
                                class="hidden mx-auto max-h-[500px] w-auto rounded-lg border object-contain">

                            <iframe id="pdf-preview" class="hidden h-[500px] w-full rounded-lg border"
                                title="Preview surat dokter"></iframe>

                            <p id="preview-error" class="hidden text-sm text-red-600">
                                Format dokumen tidak dapat ditampilkan.
                            </p>
                        </div>

                        @error('surat_dokter')
                            <p class="mt-2 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- PERNYATAAN --}}
                    <div class="flex items-start gap-3 rounded-xl p-4 mt-5"
                        style="background-color: #F5F3EC; border: 1px solid #E9E3D3;">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="#946600" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14.02A2 2 0 003.92 21h16.16a2 2 0 001.73-3.12l-8.1-14.02a2 2 0 00-3.42 0z" />
                        </svg>

                        <p class="py-5 text-xs leading-relaxed" style="color: #66552B;">
                            Pastikan tanggal izin, keterangan sakit, dan surat dokter sudah benar.
                            Pengajuan akan diteruskan kepada Tata Usaha untuk diperiksa.
                        </p>
                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-2 flex flex-col-reverse gap-4 pt-8 pb-2 sm:flex-row sm:items-center sm:justify-end">

                        <button type="reset"
                            class="min-h-12 rounded-xl border border-gray-300 bg-white px-3 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-100 ">
                            Reset Form
                        </button>

                        <button type="submit"
                            class="inline-flex min-h-12 items-center justify-center gap-3 rounded-xl px-3 py-3 text-sm font-bold text-white shadow-lg transition hover:brightness-110"
                            style="background-color: #1E2B55; box-shadow: 0 5px 14px rgba(30,43,85,0.20);">
                            <span>Kirim Pengajuan</span>

                            <svg class="h-4 w-4" fill="none" stroke="#F2B51D" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>

                </form>
            </section>

            <p class="pb-4 text-center text-xs text-gray-400">
                SIMAS · Sistem Informasi Manajemen Administrasi Sekolah
            </p>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('surat_dokter');
            const container = document.getElementById('preview-container');
            const image = document.getElementById('image-preview');
            const pdf = document.getElementById('pdf-preview');
            const filename = document.getElementById('preview-filename');
            const removeButton = document.getElementById('remove-preview');
            const error = document.getElementById('preview-error');

            let previewUrl = null;

            function clearPreview() {
                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }

                image.src = '';
                pdf.src = '';
                image.classList.add('hidden');
                pdf.classList.add('hidden');
                error.classList.add('hidden');
                container.classList.add('hidden');
            }

            input.addEventListener('change', function () {
                clearPreview();

                const file = input.files[0];

                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran dokumen maksimal 5 MB.');
                    input.value = '';
                    return;
                }

                const isImage = ['image/jpeg', 'image/png'].includes(file.type);
                const isPdf = file.type === 'application/pdf';

                filename.textContent = file.name;
                previewUrl = URL.createObjectURL(file);
                container.classList.remove('hidden');

                if (isImage) {
                    image.src = previewUrl;
                    image.classList.remove('hidden');
                } else if (isPdf) {
                    pdf.src = previewUrl;
                    pdf.classList.remove('hidden');
                } else {
                    error.classList.remove('hidden');
                }
            });

            removeButton.addEventListener('click', function () {
                input.value = '';
                clearPreview();
            });

            window.addEventListener('beforeunload', function () {
                if (previewUrl) URL.revokeObjectURL(previewUrl);
            });
        });
    </script>
</x-app-layout>