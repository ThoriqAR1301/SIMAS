<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pengajuan Surat Perizinan Sakit — {{ config('app.name', 'SIMAS') }}</title>

        <style>
            :root {
                color-scheme: light;
                --bg: #fdfdfc;
                --surface: #ffffff;
                --text: #1b1b18;
                --text-muted: #706f6c;
                --border: #e3e3e0;
                --border-strong: rgba(26, 26, 0, 0.16);
                --accent: #1b1b18;
                --accent-text: #ffffff;
                --danger: #b91c1c;
            }

            *,
            *::before,
            *::after {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 2rem 1rem;
                background: var(--bg);
                color: var(--text);
                font-family: "Instrument Sans", ui-sans-serif, system-ui, -apple-system,
                    "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                font-size: 15px;
                line-height: 1.5;
            }

            .page {
                width: 100%;
                max-width: 40rem;
            }

            .page-header {
                margin-bottom: 1.25rem;
            }

            .page-header h1 {
                margin: 0 0 0.25rem;
                font-size: 1.35rem;
                font-weight: 600;
            }

            .page-header p {
                margin: 0;
                color: var(--text-muted);
                font-size: 0.9rem;
            }

            .card {
                background: var(--surface);
                border: 1px solid var(--border);
                box-shadow: inset 0 0 0 1px var(--border-strong);
                border-radius: 0.75rem;
                padding: 1.5rem;
            }

            .link-row {
                margin-bottom: 1rem;
            }

            .link {
                color: var(--text);
                font-size: 0.85rem;
                font-weight: 600;
                text-decoration: underline;
                text-underline-offset: 4px;
            }

            .link:hover {
                color: var(--text-muted);
            }

            .field {
                margin-bottom: 1.1rem;
            }

            .field-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            label {
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.85rem;
                font-weight: 600;
            }

            .required {
                color: var(--danger);
            }

            input[type="date"],
            input[type="file"],
            textarea {
                width: 100%;
                padding: 0.55rem 0.7rem;
                border: 1px solid var(--border);
                border-radius: 0.5rem;
                background: var(--surface);
                color: var(--text);
                font: inherit;
            }

            input:focus-visible,
            textarea:focus-visible {
                outline: 2px solid var(--text);
                outline-offset: 1px;
                border-color: var(--text);
            }

            textarea {
                min-height: 7rem;
                resize: vertical;
            }

            .hint {
                margin: 0.3rem 0 0;
                color: var(--text-muted);
                font-size: 0.78rem;
            }

            .error {
                margin: 0.3rem 0 0;
                color: var(--danger);
                font-size: 0.8rem;
            }

            .alert {
                margin-bottom: 1.25rem;
                padding: 0.7rem 0.85rem;
                border: 1px solid #bbf7d0;
                border-radius: 0.5rem;
                background: #f0fdf4;
                color: #166534;
                font-size: 0.85rem;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                margin-top: 1.5rem;
                padding-top: 1.25rem;
                border-top: 1px solid var(--border);
            }

            button,
            .btn {
                display: inline-block;
                padding: 0.55rem 1.25rem;
                border-radius: 0.5rem;
                font: inherit;
                font-size: 0.9rem;
                cursor: pointer;
                text-decoration: none;
            }

            button[type="submit"] {
                background: var(--accent);
                color: var(--accent-text);
                border: 1px solid var(--accent);
            }

            button[type="submit"]:hover {
                opacity: 0.88;
            }

            .btn {
                background: transparent;
                color: var(--text);
                border: 1px solid var(--border);
            }

            .btn:hover {
                border-color: var(--text-muted);
            }

            @media (max-width: 32rem) {
                .field-row {
                    grid-template-columns: 1fr;
                    gap: 0;
                }
            }
        </style>
    </head>
    <body>
        <div class="page">
            <div class="link-row">
                <a class="link" href="{{ route('surat-perizinan-sakit.index') }}">
                    Lihat Riwayat Pengajuan &rarr;
                </a>
            </div>

            <header class="page-header">
                <h1>Pengajuan Surat Perizinan Sakit</h1>
                <p>
                    Isi formulir berikut untuk mengajukan izin sakit. Pengajuan akan
                    diproses oleh Tata Usaha setelah dikirim.
                </p>
            </header>

            <main class="card">
                @if (session('status'))
                    <div class="alert" role="status">{{ session('status') }}</div>
                @endif

                <form
                    action="{{ route('surat-perizinan-sakit.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="field-row">
                        <div class="field">
                            <label for="tanggal_mulai">
                                Tanggal Mulai Izin <span class="required">*</span>
                            </label>
                            <input
                                type="date"
                                id="tanggal_mulai"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai') }}"
                                required
                            >
                            @error('tanggal_mulai')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="tanggal_selesai">
                                Tanggal Selesai Izin <span class="required">*</span>
                            </label>
                            <input
                                type="date"
                                id="tanggal_selesai"
                                name="tanggal_selesai"
                                value="{{ old('tanggal_selesai') }}"
                                min=""
                                required
                            >
                            @error('tanggal_selesai')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="field">
                        <label for="alasan">Alasan Sakit <span class="required">*</span></label>
                        <textarea id="alasan" name="alasan" required>{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="surat_dokter">
                            Surat Dokter <span class="required">*</span>
                        </label>
                        <input
                            type="file"
                            id="surat_dokter"
                            name="surat_dokter"
                            accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                            required
                        >
                        <p class="hint">Format file: PDF, JPG, atau PNG.</p>
                        @error('surat_dokter')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="actions">
                        <button type="submit">Kirim Pengajuan</button>
                        <button type="reset" class="btn">Reset</button>
                    </div>
                </form>
            </main>
        </div>

        <script>
            (function () {
                var mulai = document.getElementById("tanggal_mulai");
                var selesai = document.getElementById("tanggal_selesai");

                function sinkronkan() {
                    selesai.min = mulai.value || "";
                    if (selesai.value && selesai.value < mulai.value) {
                        selesai.value = "";
                    }
                }

                mulai.addEventListener("change", sinkronkan);
                sinkronkan();
            })();
        </script>
    </body>
</html>
