<x-app-layout>
    <x-slot name="title">Pengajuan Surat Izin Sakit</x-slot>
    <x-slot name="subtitle">Formulir Pengajuan Resmi Administrasi Sekolah</x-slot>

    <style>
        .premium-canvas {
            background-color: #F8FAFC;
            background-image: radial-gradient(#CBD5E1 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        .premium-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 2px solid #E2E8F0;
            border-radius: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .premium-input-wrapper:focus-within {
            border-color: #F2B51D;
            box-shadow: 0 0 0 4px rgba(242, 181, 29, 0.15);
            background: #FFFFFF;
        }

        .premium-input-wrapper input,
        .premium-input-wrapper textarea {
            width: 100% !important;
            background: transparent !important;
            border: none !important;
            padding: 1.25rem 1rem 1.25rem 0.5rem !important;
            font-weight: 700 !important;
            font-size: 1rem !important;
            color: #0F172A !important;
            outline: none !important;
            box-shadow: none !important;
        }
        
        .premium-input-wrapper textarea {
            resize: none;
            padding-top: 1.25rem !important;
        }

        .premium-icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 100%;
            flex-shrink: 0;
            color: #94A3B8;
            transition: color 0.3s ease;
            border-right: 2px solid #F1F5F9;
            margin-right: 0.5rem;
        }
        
        .premium-input-wrapper:focus-within .premium-icon-box {
            color: #F2B51D;
            border-right-color: #FEF08A;
        }

        .animate-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="max-w-9xl mx-auto pb-12 pt-8 px-4 sm:px-6 lg:px-8 premium-canvas rounded-3xl min-h-screen">

        <div class="mb-12 text-center animate-in">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#F2B51D]/10 border border-[#F2B51D]/20 shadow-sm mb-6">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#F2B51D] opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#F2B51D]"></span>
                </span>
                <span class="text-[11px] font-black text-[#946600] uppercase tracking-widest">Formulir Layanan Mandiri</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-4">
                Pengajuan <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F2B51D] to-[#D99A1A]">Izin Sakit</span>
            </h1>
            <p class="text-base font-medium text-slate-500 max-w-xl mx-auto leading-relaxed">
                Silakan lengkapi data di bawah ini dengan benar. Pastikan surat keterangan dokter terlampir agar pengajuan dapat segera diproses.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-5 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm flex items-start gap-4 animate-in">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-emerald-900 uppercase tracking-wider">Pengajuan Berhasil!</h3>
                    <p class="mt-1 text-sm font-medium text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-5 rounded-2xl bg-red-50 border border-red-200 shadow-sm flex items-start gap-4 animate-in">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-red-900 uppercase tracking-wider">Periksa Kembali Formulir Anda</h3>
                    <ul class="mt-2 list-inside list-disc text-sm font-medium text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] p-8 md:p-12 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-white relative overflow-hidden animate-in" style="animation-delay: 0.1s;">
            
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-transparent via-[#F2B51D] to-transparent opacity-50"></div>

            <form action="{{ route('surat-perizinan-sakit.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf

                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-[#0B1120] text-[#F2B51D] flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">1. Periode Izin</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <div class="premium-input-wrapper">
                                <div class="premium-icon-box">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                            </div>
                            @error('tanggal_mulai') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <div class="premium-input-wrapper">
                                <div class="premium-icon-box">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                            </div>
                            @error('tanggal_selesai') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-[#0B1120] text-[#F2B51D] flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">2. Keterangan Sakit</h3>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Alasan Sakit <span class="text-red-500">*</span></label>
                        <div class="premium-input-wrapper items-start">
                            <div class="premium-icon-box pt-4 h-auto border-r-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h8M8 14h5m-8 6h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <textarea name="alasan" rows="4" required maxlength="2000" placeholder="Jelaskan kondisi sakit Anda secara singkat (Contoh: Demam dan batuk sejak 2 hari lalu...)">{{ old('alasan') }}</textarea>
                        </div>
                        @error('alasan') <p class="mt-1.5 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-[#0B1120] text-[#F2B51D] flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">3. Unggah Dokumen</h3>
                    </div>

                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Surat Keterangan Dokter <span class="text-red-500">*</span></label>

                    <div class="relative w-full border-2 border-dashed border-slate-300 hover:border-[#F2B51D] bg-slate-50 hover:bg-[#F2B51D]/5 transition-all rounded-3xl p-10 flex flex-col items-center justify-center text-center cursor-pointer group">
                        <input type="file" id="surat_dokter" name="surat_dokter" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-slate-400 group-hover:text-[#F2B51D] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <p class="text-base font-black text-slate-700 group-hover:text-[#946600] transition-colors">Pilih File atau Tarik ke Sini</p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Format: PDF, JPG, PNG (Maksimal 5 MB)</p>
                    </div>
                    @error('surat_dokter') <p class="mt-2 ml-1 text-xs font-bold text-red-500">{{ $message }}</p> @enderror

                    <div id="preview-container" class="mt-6 hidden rounded-2xl border border-slate-200 p-5 bg-white shadow-sm">
                        <div class="mb-4 flex items-center justify-between gap-3 pb-3 border-b border-slate-100">
                            <div>
                                <p class="text-sm font-black text-slate-900">Pratinjau Dokumen</p>
                                <p id="preview-filename" class="mt-1 text-xs font-semibold text-[#F2B51D]"></p>
                            </div>
                            <button type="button" id="remove-preview" class="px-4 py-2 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                                Ganti Dokumen
                            </button>
                        </div>
                        <img id="image-preview" src="" alt="Preview surat dokter" class="hidden mx-auto max-h-[500px] w-auto rounded-xl border object-contain">
                        <iframe id="pdf-preview" class="hidden h-[500px] w-full rounded-xl border" title="Preview surat dokter"></iframe>
                        <p id="preview-error" class="hidden text-sm font-bold text-red-600 text-center py-4">Format dokumen tidak dapat ditampilkan.</p>
                    </div>
                </div>

                <div class="mt-10 flex flex-col-reverse gap-4 pt-8 border-t border-slate-100 sm:flex-row sm:items-center sm:justify-between">
                    <button type="reset" class="min-h-12 rounded-xl px-8 py-3.5 text-sm font-bold text-slate-600 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                        Reset Form
                    </button>
                    <button type="submit" class="relative inline-flex min-h-12 items-center justify-center gap-3 rounded-xl px-10 py-3.5 bg-[#0B1120] text-white font-black text-sm uppercase tracking-widest shadow-[0_15px_30px_rgba(11,17,32,0.3)] hover:scale-105 transition-all duration-300 group overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            Kirim Pengajuan
                            <svg class="w-4 h-4 text-[#F2B51D] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                        <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
                    </button>
                </div>
            </form>
        </div>
        
        <p class="mt-8 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            SIMAS · Sistem Informasi Manajemen Administrasi Sekolah
        </p>
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