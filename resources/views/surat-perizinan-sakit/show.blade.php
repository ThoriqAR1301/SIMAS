
<x-app-layout>
    <x-slot name="title">Detail Pengajuan Surat Perizinan Sakit</x-slot>
    <x-slot name="subtitle">Informasi lengkap pengajuan izin sakit Anda</x-slot>

    @php
        $statusStyles = [
            'menunggu' => [
                'label' => 'Menunggu',
                'class' => 'bg-amber-100 text-amber-700 border-amber-200',
            ],
            'disetujui' => [
                'label' => 'Disetujui',
                'class' => 'bg-green-100 text-green-700 border-green-200',
            ],
            'ditolak' => [
                'label' => 'Ditolak',
                'class' => 'bg-red-100 text-red-700 border-red-200',
            ],
            'perlu_revisi' => [
                'label' => 'Perlu Revisi',
                'class' => 'bg-amber-100 text-amber-800 border-amber-300',
            ],
        ];

        $status = $statusStyles[$pengajuan->status] ?? [
            'label' => 'Tidak Diketahui',
            'class' => 'bg-gray-100 text-gray-700 border-gray-200',
        ];

        $tanggalMulai = $pengajuan->tanggal_mulai
            ? \Illuminate\Support\Carbon::parse($pengajuan->tanggal_mulai)->format('Y-m-d')
            : '';

        $tanggalSelesai = $pengajuan->tanggal_selesai
            ? \Illuminate\Support\Carbon::parse($pengajuan->tanggal_selesai)->format('Y-m-d')
            : '';

        $extension = strtolower(
            pathinfo($pengajuan->surat_dokter_path ?? '', PATHINFO_EXTENSION)
        );

        $documentUrl = $pengajuan->surat_dokter_path
            ? route('surat-perizinan-sakit.dokter', $pengajuan)
            : null;
    @endphp

    <div class="min-h-screen px-4 py-8 sm:px-6 lg:px-8"
         style="background-color: #F5F3EC;">

        <div class="mx-auto max-w-4xl space-y-6">

            {{-- NAVIGASI --}}
            <a href="{{ route('surat-perizinan-sakit.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold hover:underline"
               style="color: #293B70;">
                &larr; Kembali ke Riwayat Pengajuan
            </a>

            {{-- HEADER --}}
            <section class="relative overflow-hidden rounded-2xl p-6 shadow-lg sm:p-8"
                     style="background: linear-gradient(120deg, #1E2B55 0%, #293B70 100%);">

                <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full"
                     style="background: rgba(242,181,29,0.10);">
                </div>

                <div class="relative z-10">
                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold tracking-wider"
                          style="background: rgba(242,181,29,0.15); color: #F2B51D; border: 1px solid rgba(242,181,29,0.35);">
                        DETAIL PENGAJUAN
                    </span>

                    <h1 class="mt-4 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Detail Izin Sakit
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-relaxed sm:text-base"
                       style="color: #E0E7F5;">
                        Informasi lengkap pengajuan izin sakit yang telah Anda kirim
                        kepada pihak Tata Usaha.
                    </p>
                </div>
            </section>

            {{-- NOTIFIKASI BERHASIL --}}
            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                    <div class="flex items-start gap-3">
                        <span class="text-xl font-bold text-green-600">✓</span>
                        <div>
                            <p class="font-bold text-green-800">Berhasil!</p>
                            <p class="mt-1 text-sm text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- NOTIFIKASI ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                    <p class="font-bold text-red-800">
                        Pengajuan belum berhasil dikirim.
                    </p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- DETAIL CARD --}}
            <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
                     style="border-color: #E5E7EB;">

                <div class="flex flex-col justify-between gap-3 border-b px-5 py-5 sm:flex-row sm:items-center sm:px-7"
                     style="border-color: #E5E7EB;">

                    <div>
                        <h2 class="text-lg font-bold" style="color: #1E2B55;">
                            Informasi Pengajuan
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Rincian data permohonan izin sakit Anda.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center gap-2 rounded-full border px-4 py-2 text-xs font-bold {{ $status['class'] }}">
                        <span class="h-2 w-2 rounded-full bg-current"></span>
                        {{ $status['label'] }}
                    </span>
                </div>

                <div class="space-y-6 p-5 sm:p-7">

                    {{-- PERIODE IZIN --}}
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div class="rounded-xl p-4"
                             style="background-color: #F8F9FC; border: 1px solid #E9EDF7;">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                                Tanggal Mulai Izin
                            </p>
                            <p class="mt-2 text-base font-bold" style="color: #1E2B55;">
                                {{ \Illuminate\Support\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d M Y') }}
                            </p>
                        </div>

                        <div class="rounded-xl p-4"
                             style="background-color: #F8F9FC; border: 1px solid #E9EDF7;">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                                Tanggal Selesai Izin
                            </p>
                            <p class="mt-2 text-base font-bold" style="color: #1E2B55;">
                                {{ \Illuminate\Support\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- ALASAN --}}
                    <div>
                        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                            Alasan Sakit
                        </p>
                        <div class="rounded-xl p-4"
                             style="background-color: #F8F9FC; border: 1px solid #E9EDF7;">
                            <p class="whitespace-pre-line text-sm leading-relaxed text-gray-700">
                                {{ $pengajuan->alasan }}
                            </p>
                        </div>
                    </div>

                    {{-- DOKUMEN --}}
                    <div>
                        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                            Surat Keterangan Dokter
                        </p>

                        @if ($documentUrl)
                            <div class="rounded-xl border p-4"
                                 style="background-color: #FFF9E9; border-color: #F2E2B5;">

                                <p class="mb-3 text-sm font-bold" style="color: #1E2B55;">
                                    Dokumen telah dilampirkan
                                </p>

                                @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $documentUrl }}"
                                         alt="Surat keterangan dokter"
                                         class="mx-auto max-h-[600px] w-auto rounded-lg border bg-white object-contain">
                                @elseif ($extension === 'pdf')
                                    <iframe src="{{ $documentUrl }}"
                                            class="h-[600px] w-full rounded-lg border bg-white"
                                            title="Surat keterangan dokter">
                                    </iframe>
                                @else
                                    <p class="text-sm text-red-600">
                                        Format dokumen tidak dapat ditampilkan.
                                    </p>
                                @endif

                                <a href="{{ $documentUrl }}?download=1"
                                   class="mt-4 inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold"
                                   style="background-color: #1E2B55; color: white;">
                                    Unduh Dokumen
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-red-600">
                                Dokumen tidak tersedia.
                            </p>
                        @endif
                    </div>

                    {{-- CATATAN TU --}}
                    @if (filled($pengajuan->catatan_tu))
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                Catatan Tata Usaha
                            </p>

                            <div class="rounded-xl border p-4"
                                 style="background-color: #FFF9E9; border-color: #F2E2B5;">
                                <p class="whitespace-pre-line text-sm leading-relaxed text-gray-700">
                                    {{ $pengajuan->catatan_tu }}
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </section>

            {{-- FORM REVISI KHUSUS GURU --}}
            @if (
                auth()->user()->isGuru() &&
                $pengajuan->user_id === auth()->id() &&
                $pengajuan->status === \App\Models\SuratPerizinanSakit::STATUS_PERLU_REVISI
            )
                <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
                         style="border-color: #F2D98C;">

                    <div class="border-b px-5 py-5 sm:px-7"
                         style="background-color: #FFF9E9; border-color: #F2E2B5;">

                        <h2 class="text-lg font-bold" style="color: #1E2B55;">
                            Perbaiki Pengajuan
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Silakan periksa catatan Tata Usaha dan perbaiki data
                            pengajuan sebelum dikirim kembali.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('surat-perizinan-sakit.revisi', $pengajuan) }}"
                          enctype="multipart/form-data"
                          class="space-y-5 p-5 sm:p-7">

                        @csrf

                        {{-- TANGGAL MULAI --}}
                        <div>
                            <label for="tanggal_mulai"
                                   class="mb-2 block text-sm font-semibold text-gray-700">
                                Tanggal Mulai Izin
                            </label>

                            <input type="date"
                                   id="tanggal_mulai"
                                   name="tanggal_mulai"
                                   value="{{ old('tanggal_mulai', $tanggalMulai) }}"
                                   required
                                   class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">

                            @error('tanggal_mulai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TANGGAL SELESAI --}}
                        <div>
                            <label for="tanggal_selesai"
                                   class="mb-2 block text-sm font-semibold text-gray-700">
                                Tanggal Selesai Izin
                            </label>

                            <input type="date"
                                   id="tanggal_selesai"
                                   name="tanggal_selesai"
                                   value="{{ old('tanggal_selesai', $tanggalSelesai) }}"
                                   min="{{ old('tanggal_mulai', $tanggalMulai) }}"
                                   required
                                   class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">

                            @error('tanggal_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ALASAN --}}
                        <div>
                            <label for="alasan"
                                   class="mb-2 block text-sm font-semibold text-gray-700">
                                Alasan Sakit
                            </label>

                            <textarea id="alasan"
                                      name="alasan"
                                      rows="4"
                                      required
                                      maxlength="2000"
                                      class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                      placeholder="Perbaiki keterangan izin sakit Anda...">{{ old('alasan', $pengajuan->alasan) }}</textarea>

                            @error('alasan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- UPLOAD DOKUMEN PENGGANTI --}}
                        <div>
                            <label for="surat_dokter"
                                   class="mb-2 block text-sm font-semibold text-gray-700">
                                Ganti Surat Keterangan Dokter
                                <span class="font-normal text-gray-400">(Opsional)</span>
                            </label>

                            <input type="file"
                                   id="surat_dokter"
                                   name="surat_dokter"
                                   accept=".pdf,.jpg,.jpeg,.png,image/jpeg,image/png,application/pdf"
                                   class="w-full rounded-xl border border-gray-300 bg-white p-3 text-sm">

                            <p class="mt-2 text-xs text-gray-500">
                                Format yang diperbolehkan: PDF, JPG, JPEG, PNG.
                                Maksimal ukuran 5 MB. Kosongkan jika tidak ingin mengganti dokumen.
                            </p>

                            @error('surat_dokter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PREVIEW FILE BARU --}}
                        <div id="preview-container" class="hidden rounded-xl border p-4"
                             style="background-color: #F8F9FC; border-color: #E9EDF7;">

                            <p class="mb-3 text-sm font-bold" style="color: #1E2B55;">
                                Preview Dokumen Baru
                            </p>

                            <img id="image-preview"
                                 src=""
                                 alt="Preview surat dokter baru"
                                 class="hidden mx-auto max-h-[450px] w-auto rounded-lg border bg-white object-contain">

                            <iframe id="pdf-preview"
                                    class="hidden h-[450px] w-full rounded-lg border bg-white"
                                    title="Preview PDF surat dokter">
                            </iframe>

                            <p id="file-name" class="mt-3 break-all text-xs text-gray-600"></p>
                        </div>

                        {{-- TOMBOL --}}
                        <div class="flex flex-col-reverse gap-3 border-t pt-5 sm:flex-row sm:justify-end"
                             style="border-color: #E5E7EB;">

                            <a href="{{ route('surat-perizinan-sakit.index') }}"
                               class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                                Batal
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-bold transition hover:brightness-110"
                                    style="background-color: #F2B51D; color: #1E2B55;">
                                Kirim Perbaikan ke TU
                            </button>
                        </div>

                    </form>
                </section>
            @endif

            {{-- FOOTER --}}
            <div class="flex justify-end">
                <a href="{{ route('surat-perizinan-sakit.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-bold transition hover:brightness-110"
                   style="background-color: #1E2B55; color: #FFFFFF;">
                    &larr; Kembali ke Riwayat
                </a>
            </div>

            <p class="pb-4 text-center text-xs text-gray-400">
                SIMAS · Sistem Informasi Manajemen Administrasi Sekolah
            </p>

        </div>
    </div>

    {{-- JAVASCRIPT PREVIEW DOKUMEN --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('surat_dokter');

            if (!input) return;

            const container = document.getElementById('preview-container');
            const image = document.getElementById('image-preview');
            const pdf = document.getElementById('pdf-preview');
            const fileName = document.getElementById('file-name');

            let previewUrl = null;

            input.addEventListener('change', function () {
                const file = this.files[0];

                image.classList.add('hidden');
                pdf.classList.add('hidden');

                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }

                if (!file) {
                    container.classList.add('hidden');
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'application/pdf'
                ];

                if (!allowedTypes.includes(file.type)) {
                    this.value = '';
                    container.classList.add('hidden');
                    alert('Format file harus PDF, JPG, JPEG, atau PNG.');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    this.value = '';
                    container.classList.add('hidden');
                    alert('Ukuran dokumen maksimal 5 MB.');
                    return;
                }

                previewUrl = URL.createObjectURL(file);
                fileName.textContent = file.name;
                container.classList.remove('hidden');

                if (file.type.startsWith('image/')) {
                    image.src = previewUrl;
                    image.classList.remove('hidden');
                } else {
                    pdf.src = previewUrl;
                    pdf.classList.remove('hidden');
                }
            });
        });
    </script>
</x-app-layout>
