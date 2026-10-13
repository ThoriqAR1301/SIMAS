<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Buat Pengajuan Surat
        </h2>
    </x-slot>

    <div class="py-6" x-data="pengajuanWizard()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <template x-for="(s, i) in steps" :key="i">
                        <div class="flex items-center flex-1">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all" :class="step > i ? 'bg-blue-600 text-white' : step === i ? 'bg-blue-600 text-white ring-4 ring-blue-200 dark:ring-blue-900' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'">
                                    <template x-if="step > i">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </template>
                                    <template x-if="step <= i">
                                        <span x-text="i + 1"></span>
                                    </template>
                                </div>
                                <span class="mt-2 text-xs font-medium text-center" :class="step >= i ? 'text-blue-600' : 'text-gray-400'" x-text="s.title"></span>
                            </div>
                            <div x-show="i < steps.length - 1" class="flex-1 h-1 mx-2 rounded transition-all" :class="step > i ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'">
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" @submit="loading = true" class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-5">

                @csrf

                <div x-show="step === 0" x-transition>
                    <h3 class="font-medium mb-4 text-gray-900 dark:text-gray-100">Informasi Dasar</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jenis Blanko <span class="text-red-500">*</span>
                            </label>
                            <select name="blanko_id" required x-model="form.blanko_id"
                                class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">-- Pilih --</option>
                                @foreach($blanko as $b)
                                    <option value="{{ $b->id }}" data-jenis="{{ $b->jenis }}">
                                        {{ $b->nama }} ({{ $b->kode }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jenis Surat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="jenis_surat" required x-model="form.jenis_surat" placeholder="Contoh : Surat Tugas, Surat Undangan" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_kegiatan" required x-model="form.nama_kegiatan" placeholder="Contoh : Rapat Koordinasi MGMP" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        </div>
                    </div>
                </div>

                <div x-show="step === 1" x-transition>
                    <h3 class="font-medium mb-4 text-gray-900 dark:text-gray-100">Waktu & Tempat</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Pelaksanaan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_pelaksanaan" required x-model="form.tanggal_pelaksanaan" min="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai" x-model="form.waktu_mulai" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai" x-model="form.waktu_selesai" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat</label>
                            <input type="text" name="tempat" x-model="form.tempat" placeholder="Contoh : Aula Sekolah" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                            <textarea name="keterangan" rows="3" x-model="form.keterangan" placeholder="Keterangan Tambahan (Opsional)" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                        </div>
                    </div>
                </div>

                <div x-show="step === 2" x-transition>
                    <h3 class="font-medium mb-4 text-gray-900 dark:text-gray-100">Lampiran & Review</h3>

                    <div class="space-y-4">
                        <div x-data="{ files: [] }">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Lampiran (Maks 5, PDF/JPG/PNG, Maks 5MB)
                            </label>
                            <input type="file" name="lampiran[]" multiple accept=".pdf,.jpg,.jpeg,.png" @change="files = Array.from($event.target.files)" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <template x-if="files.length">
                                <ul class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                    <template x-for="f in files" :key="f.name">
                                        <li>📎 <span x-text="f.name"></span>
                                            (<span x-text="(f.size / 1024).toFixed(0)"></span> KB)</li>
                                    </template>
                                </ul>
                            </template>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-dashed border-gray-300 dark:border-gray-700">
                            <p class="text-xs font-medium text-gray-500 uppercase mb-3">Preview Surat</p>
                            <div class="font-serif text-sm space-y-2 text-gray-800 dark:text-gray-200">
                                <p><strong>Nomor :</strong> <span class="italic text-gray-400">(Di-Generate Otomatis)</span></p>
                                <p><strong>Jenis :</strong> <span x-text="form.jenis_surat || '—'"></span></p>
                                <p><strong>Kegiatan :</strong> <span x-text="form.nama_kegiatan || '—'"></span></p>
                                <p><strong>Tanggal :</strong> <span x-text="formatTanggal(form.tanggal_pelaksanaan)"></span></p>
                                <p><strong>Waktu :</strong>
                                    <span x-text="(form.waktu_mulai || '—') + ' s.d. ' + (form.waktu_selesai || '—')"></span>
                                </p>
                                <p><strong>Tempat :</strong> <span x-text="form.tempat || '—'"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6 border-t dark:border-gray-700">
                    <button type="button" @click="prev()" x-show="step > 0"
                        class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        ← Sebelumnya
                    </button>
                    <div x-show="step === 0"></div>

                    <div class="flex gap-2">
                        <template x-if="step < 2">
                            <button type="button" @click="next()" class="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                                Selanjutnya →
                            </button>
                        </template>

                        <template x-if="step === 2">
                            <div class="flex gap-2">
                                <button type="submit" name="aksi" value="draft"
                                    class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                                    Simpan Draft
                                </button>
                                <button type="submit" name="aksi" value="submit" :disabled="loading"
                                    class="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white disabled:opacity-50">
                                    <span x-show="!loading">🚀 Simpan & Ajukan</span>
                                    <span x-show="loading">Menyimpan...</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    function pengajuanWizard() {
        return {
            step: 0,
            loading: false,
            steps: [
                { title: 'Info Dasar' },
                { title: 'Waktu & Tempat' },
                { title: 'Lampiran' },
            ],
            form: {
                blanko_id: '',
                jenis_surat: '',
                nama_kegiatan: '',
                tanggal_pelaksanaan: '',
                waktu_mulai: '',
                waktu_selesai: '',
                tempat: '',
                keterangan: '',
            },

            init() {
                setInterval(() => this.saveDraft(), 5000);

                const saved = localStorage.getItem('pengajuan_draft');
                if (saved) {
                    try {
                        this.form = { ...this.form, ...JSON.parse(saved) };
                    } catch (e) {}
                }
            },

            next() {
                if (this.validateStep()) {
                    this.step++;
                    this.saveDraft();
                }
            },

            prev() {
                if (this.step > 0) this.step--;
            },

            validateStep() {
                if (this.step === 0) {
                    if (!this.form.blanko_id || !this.form.jenis_surat || !this.form.nama_kegiatan) {
                        alert('Lengkapi Informasi Dasar Dulu');
                        return false;
                    }
                }
                if (this.step === 1) {
                    if (!this.form.tanggal_pelaksanaan) {
                        alert('Tanggal Pelaksanaan Wajib Diisi');
                        return false;
                    }
                }
                return true;
            },

            saveDraft() {
                localStorage.setItem('pengajuan_draft', JSON.stringify(this.form));
            },

            formatTanggal(tgl) {
                if (!tgl) return '—';
                const d = new Date(tgl);
                return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            },
        };
    }
    </script>
    @endpush
</x-app-layout>