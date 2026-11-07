<x-app-layout>
    <style>
        .sk-head-title { font-size: 20px; font-weight: 800; color: #1f2a5c; letter-spacing: -0.01em; }
        .sk-head-sub { margin-top: 4px; font-size: 14px; color: #6b7191; }

        .sk-page {
            min-height: 100vh;
            padding: 32px 16px 48px;
            background: linear-gradient(135deg, #fbf7ea 0%, #f8f7f3 45%, #eef0f8 100%);
            color: #1f2a5c;
        }
        @media (min-width: 640px) { .sk-page { padding: 32px 24px 48px; } }
        @media (min-width: 1024px) { .sk-page { padding: 32px 32px 48px; } }
        .sk-wrap { max-width: 1120px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px; }

        .sk-back {
            display: inline-flex; align-items: center; gap: 8px; width: fit-content;
            font-size: 13px; font-weight: 700; color: #1f2a5c; text-decoration: none;
        }
        .sk-back:hover { color: #d99a0b; }
        .sk-back svg { width: 16px; height: 16px; }

        /* Hero */
        .sk-hero {
            position: relative; overflow: hidden; border-radius: 24px; padding: 30px 32px; color: #fff;
            background: linear-gradient(135deg, #1b2452 0%, #222d5f 55%, #30407a 100%);
            box-shadow: 0 20px 40px -18px rgba(31, 42, 92, 0.6);
            display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px;
        }
        .sk-hero::before {
            content: ""; position: absolute; right: -60px; top: -90px; width: 260px; height: 260px;
            border-radius: 50%; background: rgba(255, 255, 255, 0.05);
        }
        .sk-hero::after {
            content: ""; position: absolute; right: 180px; bottom: -110px; width: 220px; height: 220px;
            border-radius: 50%; background: rgba(245, 179, 36, 0.10);
        }
        .sk-hero-main { position: relative; z-index: 1; max-width: 640px; }
        .sk-chip {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 999px;
            background: rgba(255, 255, 255, 0.10); border: 1px solid rgba(245, 179, 36, 0.35);
            color: #f5b324; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em;
        }
        .sk-dot { width: 8px; height: 8px; border-radius: 50%; background: #f5b324; }
        .sk-hero h1 { margin: 18px 0 0; font-size: 28px; line-height: 1.2; font-weight: 800; letter-spacing: -0.02em; color: #fff; }
        .sk-hero p { margin: 10px 0 0; font-size: 15px; line-height: 1.65; color: #c9d0ea; }
        .sk-hero-person {
            position: relative; z-index: 1; display: flex; align-items: center; gap: 12px;
            padding: 12px 18px; border-radius: 18px;
            background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .sk-hero-person small { display: block; font-size: 10.5px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #c9d0ea; }
        .sk-hero-person strong { display: block; margin-top: 2px; font-size: 15px; font-weight: 700; color: #fff; }

        .sk-avatar {
            flex-shrink: 0; width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #f5b324, #e39a0c); color: #1f2a5c; font-size: 15px; font-weight: 800;
        }

        /* Grid */
        .sk-grid { display: grid; gap: 24px; grid-template-columns: 1fr; align-items: start; }
        @media (min-width: 1024px) { .sk-grid { grid-template-columns: 1.4fr 1fr; } }
        .sk-col { display: flex; flex-direction: column; gap: 24px; min-width: 0; }
        @media (min-width: 1024px) { .sk-sticky { position: sticky; top: 24px; } }

        /* Card */
        .sk-card {
            overflow: hidden; border-radius: 24px; background: #fffdf8; border: 1px solid #efe9d8;
            box-shadow: 0 1px 2px rgba(31, 42, 92, 0.04), 0 14px 34px -20px rgba(31, 42, 92, 0.25);
        }
        .sk-card-head { padding: 20px 24px; border-bottom: 1px solid #f1ecdc; display: flex; align-items: center; gap: 12px; }
        .sk-card-icon {
            width: 38px; height: 38px; border-radius: 12px; background: #fff3d6; color: #d99a0b;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .sk-card-icon svg { width: 20px; height: 20px; }
        .sk-card-head h3 { margin: 0; font-size: 16px; font-weight: 800; color: #1f2a5c; }
        .sk-card-head p { margin: 2px 0 0; font-size: 12.5px; color: #6b7191; }
        .sk-card-body { padding: 22px 24px; }

        /* Detail list */
        .sk-dl { margin: 0; display: grid; gap: 0; }
        .sk-dl-row { display: grid; grid-template-columns: 170px 1fr; gap: 16px; padding: 14px 0; border-bottom: 1px solid #f1ecdc; }
        .sk-dl-row:first-child { padding-top: 0; }
        .sk-dl-row:last-child { border-bottom: none; padding-bottom: 0; }
        @media (max-width: 560px) { .sk-dl-row { grid-template-columns: 1fr; gap: 4px; } }
        .sk-dl dt { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #8a90ad; padding-top: 2px; }
        .sk-dl dd { margin: 0; font-size: 14.5px; font-weight: 600; color: #1f2a5c; min-width: 0; }
        .sk-dl dd.sk-reason { font-weight: 500; color: #555b7a; line-height: 1.6; }

        /* Badge */
        .sk-badge {
            display: inline-flex; align-items: center; gap: 7px; padding: 5px 12px; border-radius: 999px;
            border: 1px solid transparent; font-size: 12px; font-weight: 700; white-space: nowrap;
        }
        .sk-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .sk-badge--menunggu { background: #fff6dc; color: #b57a00; border-color: #f6dd9b; }
        .sk-badge--disetujui { background: #e8f7ee; color: #15803d; border-color: #b7e4c7; }
        .sk-badge--ditolak { background: #fdecec; color: #b91c1c; border-color: #f6c3c3; }
        .sk-badge--perlu_revisi { background: #fff0e1; color: #c2410c; border-color: #fbd3ae; }
        .sk-badge--unknown { background: #eef0f6; color: #5b6180; border-color: #d9dcea; }

        /* Preview */
        .sk-preview { border: 1px solid #efe9d8; border-radius: 18px; padding: 16px; background: #faf6ea; text-align: center; }
        .sk-preview img { max-width: 100%; height: auto; border-radius: 12px; border: 1px solid #efe9d8; background: #fff; }
        .sk-hint { margin: 12px 0 0; font-size: 12.5px; color: #8a90ad; }
        .sk-pdf-title { margin: 0 0 14px; font-size: 14px; font-weight: 700; color: #1f2a5c; }
        .sk-actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
        .sk-muted { color: #6b7191; font-size: 14px; margin: 0; }
        .sk-missing {
            margin: 0; padding: 14px 16px; border-radius: 14px; border: 1px solid #f6c3c3;
            background: #fdecec; color: #b91c1c; font-size: 14px; font-weight: 600;
        }

        /* Buttons */
        .sk-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 18px; border-radius: 12px;
            border: 1px solid transparent; font-size: 13px; font-weight: 700; text-decoration: none; cursor: pointer;
            transition: background 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
        }
        .sk-btn:focus-visible { outline: 2px solid #f5b324; outline-offset: 2px; }
        .sk-btn--primary { background: #1f2a5c; color: #fff !important; box-shadow: 0 6px 14px -8px rgba(31, 42, 92, 0.7); }
        .sk-btn--primary:hover { background: #2d3a6e; transform: translateY(-1px); }
        .sk-btn--ghost { background: #fffdf8; color: #1f2a5c !important; border-color: #e6dfca; }
        .sk-btn--ghost:hover { background: #fff3d6; border-color: #f5b324; }
        .sk-btn--block { width: 100%; padding: 13px 18px; font-size: 14px; }
        .sk-btn svg { width: 16px; height: 16px; color: #f5b324; }

        /* Note from TU */
        .sk-note { border-left: 4px solid #f5b324; background: #fff9ec; border-radius: 14px; padding: 14px 16px; }
        .sk-note p { margin: 0; font-size: 14px; line-height: 1.65; color: #555b7a; white-space: pre-line; }

        /* Form */
        .sk-form { display: flex; flex-direction: column; gap: 22px; }
        .sk-label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #1f2a5c; }
        .sk-textarea {
            display: block; width: 100%; padding: 12px 14px; border-radius: 14px; border: 1.5px solid #e6dfca;
            background: #fffdf8; color: #1f2a5c; font: inherit; font-size: 14px; line-height: 1.55; resize: vertical;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .sk-textarea::placeholder { color: #a6aac0; }
        .sk-textarea:focus { outline: none; border-color: #f5b324; box-shadow: 0 0 0 4px rgba(245, 179, 36, 0.18); }
        .sk-error { margin: 6px 0 0; font-size: 13px; font-weight: 600; color: #b91c1c; }

        .sk-options { display: grid; gap: 10px; }
        .sk-option {
            display: flex; align-items: center; gap: 12px; padding: 13px 15px; border-radius: 14px;
            border: 1.5px solid #ece5d0; background: #fffdf8; cursor: pointer;
            font-size: 14px; font-weight: 700; color: #1f2a5c; transition: border-color 0.15s ease, background 0.15s ease;
        }
        .sk-option input { width: 18px; height: 18px; margin: 0; accent-color: var(--c); flex-shrink: 0; }
        .sk-option:hover { border-color: var(--c); }
        .sk-option:has(input:checked) { border-color: var(--c); background: var(--soft); }

        .sk-card-foot { margin-top: 4px; }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="sk-head-title">
                Pemeriksaan Detail Pengajuan
            </h2>
            <p class="sk-head-sub">
                Pratinjau detail pengajuan izin sakit untuk pemeriksaan Tata Usaha.
            </p>
        </div>
    </x-slot>

    @php
        $statusLabels = [
            \App\Models\SuratPerizinanSakit::STATUS_MENUNGGU => ['Menunggu', 'sk-badge--menunggu'],
            \App\Models\SuratPerizinanSakit::STATUS_DISETUJUI => ['Disetujui', 'sk-badge--disetujui'],
            \App\Models\SuratPerizinanSakit::STATUS_DITOLAK => ['Ditolak', 'sk-badge--ditolak'],
            \App\Models\SuratPerizinanSakit::STATUS_PERLU_REVISI => ['Perlu Revisi', 'sk-badge--perlu_revisi'],
        ];
        [$statusLabel, $statusClass] = $statusLabels[$pengajuan->status] ?? ['Status tidak dikenal', 'sk-badge--unknown'];
    @endphp

    <div class="sk-page">
        <div class="sk-wrap">

            <a href="{{ url()->previous() }}" class="sk-back">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>

            {{-- Hero --}}
            <div class="sk-hero">
                <div class="sk-hero-main">
                    <span class="sk-chip">
                        <span class="sk-dot"></span>
                        Admin Tata Usaha
                    </span>

                    <h1>Pemeriksaan Detail Pengajuan</h1>
                    <p>Pratinjau detail pengajuan izin sakit untuk pemeriksaan Tata Usaha.</p>
                </div>

                <div class="sk-hero-person">
                    <div class="sk-avatar">
                        {{ strtoupper(substr($pengajuan->user?->name ?? 'G', 0, 1)) }}
                    </div>
                    <div>
                        <small>Pengaju</small>
                        <strong>{{ $pengajuan->user?->name ?? 'Pengguna tidak diketahui' }}</strong>
                    </div>
                </div>
            </div>

            <div class="sk-grid">

                {{-- Kolom kiri: informasi + dokumen --}}
                <div class="sk-col">
                    <div class="sk-card">
                        <div class="sk-card-head">
                            <div class="sk-card-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Informasi Pengajuan</h3>
                                <p>Data izin sakit yang dikirim Guru.</p>
                            </div>
                        </div>

                        <div class="sk-card-body">
                            <dl class="sk-dl">
                                <div class="sk-dl-row">
                                    <dt>Nama Guru</dt>
                                    <dd>{{ $pengajuan->user?->name ?? 'Pengguna tidak diketahui' }}</dd>
                                </div>

                                <div class="sk-dl-row">
                                    <dt>Tanggal Mulai Izin</dt>
                                    <dd>{{ $pengajuan->tanggal_mulai?->format('d/m/Y') }}</dd>
                                </div>

                                <div class="sk-dl-row">
                                    <dt>Tanggal Selesai Izin</dt>
                                    <dd>{{ $pengajuan->tanggal_selesai?->format('d/m/Y') }}</dd>
                                </div>

                                <div class="sk-dl-row">
                                    <dt>Alasan Sakit</dt>
                                    <dd class="sk-reason">{{ $pengajuan->alasan }}</dd>
                                </div>

                                <div class="sk-dl-row">
                                    <dt>Status</dt>
                                    <dd><span class="sk-badge {{ $statusClass }}">{{ $statusLabel }}</span></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="sk-card">
                        <div class="sk-card-head">
                            <div class="sk-card-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M4 16l4.6-4.6a2 2 0 012.8 0L16 16m-2-2l1.6-1.6a2 2 0 012.8 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Surat Dokter</h3>
                                <p>Periksa keaslian dan kelengkapan dokumen.</p>
                            </div>
                        </div>

                        <div class="sk-card-body">
                            @if ($pengajuan->surat_dokter_path)
                                @php
                                    $ext = strtolower(
                                        pathinfo(
                                            $pengajuan->surat_dokter_path,
                                            PATHINFO_EXTENSION
                                        )
                                    );

                                    $dokterUrl = route(
                                        'surat-perizinan-sakit.dokter',
                                        $pengajuan
                                    );
                                @endphp

                                <div class="sk-preview">
                                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <a href="{{ $dokterUrl }}" target="_blank">
                                            <img
                                                src="{{ $dokterUrl }}"
                                                alt="Surat keterangan dokter"
                                                style="max-width: 100%; max-height: 600px; object-fit: contain;"
                                            >
                                        </a>

                                        <p class="sk-hint">
                                            Klik gambar untuk membuka ukuran penuh.
                                        </p>

                                    @elseif ($ext === 'pdf')
                                        <p class="sk-pdf-title">
                                            Dokumen PDF surat dokter
                                        </p>

                                        <div class="sk-actions">
                                            <a
                                                href="{{ $dokterUrl }}"
                                                target="_blank"
                                                class="sk-btn sk-btn--primary"
                                            >
                                                Buka PDF
                                            </a>

                                            <a
                                                href="{{ $dokterUrl }}?download=1"
                                                class="sk-btn sk-btn--ghost"
                                            >
                                                Unduh PDF
                                            </a>
                                        </div>

                                    @else
                                        <p class="sk-muted">
                                            Format dokumen tidak didukung.
                                        </p>
                                    @endif
                                </div>
                            @else
                                <p class="sk-missing">
                                    Guru belum mengunggah surat dokter.
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- CATATAN PEMERIKSAAN --}}
                    @if (filled($pengajuan->catatan_tu))
                        <div class="sk-card">
                            <div class="sk-card-head">
                                <div class="sk-card-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M8 10h8M8 14h5m-9 6l3-3h11a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3>Catatan Tata Usaha</h3>
                                    <p>Catatan pemeriksaan sebelumnya.</p>
                                </div>
                            </div>

                            <div class="sk-card-body">
                                <div class="sk-note">
                                    <p>{{ $pengajuan->catatan_tu }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Kolom kanan: form pemeriksaan --}}
                <div class="sk-col">
                    <div class="sk-card sk-sticky">
                        <div class="sk-card-head">
                            <div class="sk-card-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Pemeriksaan Pengajuan</h3>
                                <p>Tentukan hasil pemeriksaan izin sakit.</p>
                            </div>
                        </div>

                        <div class="sk-card-body">
                            {{-- FORM PEMERIKSAAN ADMIN TU --}}
                            <form
                                method="POST"
                                action="{{ route('surat-perizinan-sakit.verify', $pengajuan) }}"
                                class="sk-form"
                            >
                                @csrf

                                <div>
                                    <label for="catatan_tu" class="sk-label">
                                        Catatan Pemeriksaan
                                    </label>

                                    <textarea
                                        name="catatan_tu"
                                        id="catatan_tu"
                                        class="sk-textarea"
                                        rows="4"
                                        placeholder="Tuliskan catatan. Wajib diisi untuk revisi atau penolakan."
                                    >{{ old('catatan_tu', $pengajuan->catatan_tu) }}</textarea>

                                    @error('catatan_tu')
                                        <p class="sk-error">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <p class="sk-label">
                                        Pilih Keputusan
                                    </p>

                                    <div class="sk-options">

                                        <label class="sk-option" style="--c:#16a34a; --soft:#e8f7ee;">
                                            <input
                                                type="radio"
                                                name="keputusan"
                                                value="disetujui"
                                                {{ old('keputusan') === 'disetujui' ? 'checked' : '' }}
                                                required
                                            >
                                            <span>Setujui Pengajuan</span>
                                        </label>

                                        <label class="sk-option" style="--c:#f97316; --soft:#fff0e1;">
                                            <input
                                                type="radio"
                                                name="keputusan"
                                                value="perlu_revisi"
                                                {{ old('keputusan') === 'perlu_revisi' ? 'checked' : '' }}
                                            >
                                            <span>Minta Revisi</span>
                                        </label>

                                        <label class="sk-option" style="--c:#dc2626; --soft:#fdecec;">
                                            <input
                                                type="radio"
                                                name="keputusan"
                                                value="ditolak"
                                                {{ old('keputusan') === 'ditolak' ? 'checked' : '' }}
                                            >
                                            <span>Tolak Pengajuan</span>
                                        </label>

                                    </div>

                                    @error('keputusan')
                                        <p class="sk-error">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <button
                                    type="submit"
                                    class="sk-btn sk-btn--primary sk-btn--block"
                                >
                                    Simpan Keputusan
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>