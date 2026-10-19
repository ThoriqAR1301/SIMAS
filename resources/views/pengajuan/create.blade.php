<x-app-layout>
    <x-slot name="title">Buat Pengajuan Surat</x-slot>
    <x-slot name="subtitle">Isi Formulir Dalam 3 Langkah Mudah</x-slot>

    <div class="max-w-3xl mx-auto" x-data="pengajuanWizard()">
        <div class="card p-6 lg:p-8 mb-5 overflow-hidden">
            <div class="relative">

                <div class="absolute h-1 rounded-full bg-canvas-300 dark:bg-brand-800" :style="`top: 20px; left: calc(${100 / steps.length / 2}%); right: calc(${100 / steps.length / 2}%)`"></div>

                <div class="absolute h-1 rounded-full bg-gradient-gold transition-all duration-500 ease-out shadow-[0_0_12px_rgba(244,178,46,0.4)]" :style="`top: 20px; left: calc(${100 / steps.length / 2}%); width: calc(${step * 100 / steps.length}%)`"></div>

                <div class="relative grid" :style="`grid-template-columns: repeat(${steps.length}, minmax(0, 1fr))`">

                    <template x-for="(s, i) in steps" :key="i">
                        <div class="flex flex-col items-center gap-2">

                            <div class="relative z-10 w-11 h-11 rounded-2xl flex items-center justify-center font-bold text-sm transition-all duration-300" :class="step > i ? 'bg-gradient-brand text-white shadow-lift' : step === i ? 'bg-gradient-gold text-brand-900 shadow-glow-gold scale-110 ring-4 ring-gold-100 dark:ring-gold-900/30' : 'bg-canvas-200 dark:bg-brand-900 text-ink-400 dark:text-ink-500 ring-1 ring-canvas-300 dark:ring-brand-800'">

                                <template x-if="step > i">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </template>

                                <template x-if="step <= i">
                                    <span x-text="i + 1"></span>
                                </template>
                            </div>

                            <span class="text-[10px] font-black uppercase tracking-[0.1em] text-center whitespace-nowrap transition-colors duration-300" :class="step >= i ? 'text-brand-700 dark:text-gold-400' : 'text-ink-400 dark:text-ink-500'" x-text="s.title"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" @submit="loading = true" class="card p-6 lg:p-8">

            @csrf

            <div x-show="step === 0" x-transition.opacity.duration.300ms>
                <div class="mb-6">
                    <h3 class="section-title">Informasi Dasar</h3>
                    <p class="section-subtitle">Pilih Jenis Surat Dan Isi Detail Kegiatan</p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="label label-required">Jenis Blanko Surat</label>
                        <select name="blanko_id" required x-model="form.blanko_id" class="input">
                            <option value="">— Pilih Blanko —</option>
                            @foreach($blanko as $b)
                                <option value="{{ $b->id }}">{{ $b->nama }} ({{ $b->kode }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label label-required">Jenis Surat</label>
                        <input type="text" name="jenis_surat" required x-model="form.jenis_surat" placeholder="Contoh : Surat Tugas, Surat Undangan" class="input">
                    </div>

                    <div>
                        <label class="label label-required">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" required x-model="form.nama_kegiatan" placeholder="Contoh : Rapat Koordinasi MGMP" class="input">
                    </div>
                </div>
            </div>

            <div x-show="step === 1" x-transition.opacity.duration.300ms x-cloak>
                <div class="mb-6">
                    <h3 class="section-title">Waktu & Tempat</h3>
                    <p class="section-subtitle">Kapan Dan Di Mana Kegiatan Akan Dilaksanakan?</p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="label label-required">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_pelaksanaan" required x-model="form.tanggal_pelaksanaan" min="{{ now()->format('Y-m-d') }}" class="input">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" x-model="form.waktu_mulai" class="input">
                        </div>

                        <div>
                            <label class="label">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" x-model="form.waktu_selesai" class="input">
                        </div>
                    </div>

                    <div>
                        <label class="label">Tempat</label>
                        <input type="text" name="tempat" x-model="form.tempat" placeholder="Contoh : Aula Sekolah" class="input">
                    </div>

                    <div>
                        <label class="label">Keterangan</label>
                        <textarea name="keterangan" rows="3" x-model="form.keterangan" placeholder="Keterangan Tambahan (Opsional)" class="input"></textarea>
                    </div>
                </div>
            </div>

            <div x-show="step === 2" x-transition.opacity.duration.300ms x-cloak>
                <div class="mb-6">
                    <h3 class="section-title">Lampiran & Preview</h3>
                    <p class="section-subtitle">Lampirkan Dokumen Pendukung Dan Periksa Kembali</p>
                </div>

                <div class="space-y-5">
                    <div x-data="{ files: [] }">
                        <label class="label">Lampiran (Opsional)</label>
                        <div class="border-2 border-dashed border-ink-200 dark:border-brand-800 rounded-2xl p-6 text-center hover:border-gold-400 hover:bg-gold-50/30 dark:hover:bg-gold-900/10 transition cursor-pointer">
                            <input type="file" name="lampiran[]" multiple accept=".pdf,.jpg,.jpeg,.png" @change="files = Array.from($event.target.files)" class="hidden" id="file-input">
                            <label for="file-input" class="cursor-pointer block">
                                <svg class="w-10 h-10 mx-auto text-gold-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-sm font-semibold text-brand-900 dark:text-ink-50">
                                    Klik Untuk Upload Atau Drag & Drop
                                </p>
                                <p class="text-xs text-ink-500 mt-1">PDF, JPG, PNG · Maks 5MB · Maks 5 File</p>
                            </label>
                        </div>

                        <template x-if="files.length">
                            <div class="mt-3 space-y-2">
                                <template x-for="f in files" :key="f.name">
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gold-50 dark:bg-gold-900/20 border border-gold-200 dark:border-gold-800">
                                        <svg class="w-4 h-4 text-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                        </svg>
                                        <span class="text-xs font-medium flex-1 truncate" x-text="f.name"></span>
                                        <span class="text-[10px] text-ink-500" x-text="(f.size/1024).toFixed(0) + ' KB'"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="rounded-2xl p-5 bg-gradient-to-br from-ink-50 to-white dark:from-brand-900/40 dark:to-brand-900/20 border border-ink-100 dark:border-brand-800">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="glow-dot"></span>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-ink-500">Live Preview</p>
                        </div>

                        <div class="bg-white dark:bg-brand-950 rounded-xl p-5 shadow-inner font-serif space-y-2 text-sm text-ink-700 dark:text-ink-200">
                            <p class="text-center text-xs text-ink-400 mb-4">
                                — PREVIEW SURAT —
                            </p>
                            <p><span class="font-semibold">Nomor :</span>
                                <span class="italic text-gold-600">Auto-Generate</span></p>
                            <p><span class="font-semibold">Jenis :</span>
                                <span x-text="form.jenis_surat || '—'"></span></p>
                            <p><span class="font-semibold">Kegiatan :</span>
                                <span x-text="form.nama_kegiatan || '—'"></span></p>
                            <p><span class="font-semibold">Tanggal :</span>
                                <span x-text="formatTanggal(form.tanggal_pelaksanaan)"></span></p>
                            <p><span class="font-semibold">Waktu :</span>
                                <span x-text="(form.waktu_mulai || '—') + ' s.d. ' + (form.waktu_selesai || '—')"></span></p>
                            <p><span class="font-semibold">Tempat :</span>
                                <span x-text="form.tempat || '—'"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6 mt-6 border-t border-ink-100 dark:border-brand-800">
                <button type="button" @click="prev()" x-show="step > 0" class="btn-outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Sebelumnya
                </button>
                <div x-show="step === 0"></div>

                <div class="flex gap-2">
                    <template x-if="step < 2">
                        <button type="button" @click="next()" class="btn-primary">
                            Selanjutnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </template>

                    <template x-if="step === 2">
                        <div class="flex gap-2">
                            <button type="submit" name="aksi" value="draft" class="btn-outline">
                                Simpan Draft
                            </button>
                            <button type="submit" name="aksi" value="submit" :disabled="loading" class="btn-gold">
                                <span x-show="!loading" class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan & Ajukan
                                </span>
                                <span x-show="loading">Menyimpan...</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function pengajuanWizard() {
        return {
            step: 0,
            loading: false,
            steps: [{ title: 'Info Dasar' }, { title: 'Waktu & Tempat' }, { title: 'Lampiran' }],
            form: {
                blanko_id: '', jenis_surat: '', nama_kegiatan: '',
                tanggal_pelaksanaan: '', waktu_mulai: '', waktu_selesai: '',
                tempat: '', keterangan: '',
            },

            init() {
                setInterval(() => this.saveDraft(), 5000);

                const saved = localStorage.getItem('pengajuan_draft');
                if (saved) try { this.form = { ...this.form, ...JSON.parse(saved) }; } catch(e) {}
            },
            next() { if (this.validateStep()) { this.step++; this.saveDraft(); } },
            prev() { if (this.step > 0) this.step--; },
            validateStep() {
                if (this.step === 0 && (!this.form.blanko_id || !this.form.jenis_surat || !this.form.nama_kegiatan)) {
                    alert('Lengkapi Informasi Dasar Dulu'); return false;
                }
                if (this.step === 1 && !this.form.tanggal_pelaksanaan) {
                    alert('Tanggal Pelaksanaan Wajib Diisi'); return false;
                }
                return true;
            },
            saveDraft() { localStorage.setItem('pengajuan_draft', JSON.stringify(this.form)); },
            formatTanggal(tgl) {
                if (!tgl) return '—';
                return new Date(tgl).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            },
        };
    }
    </script>
    @endpush
</x-app-layout>