<x-app-layout>
    <x-slot name="title">Detail Pengajuan Sakit</x-slot>
    <x-slot name="subtitle">Informasi lengkap pengajuan izin sakit Anda</x-slot>

    @php
        $statusStyles = [
            'menunggu' => [
                'label' => 'Menunggu Verifikasi',
                'bg' => 'bg-amber-500/10',
                'text' => 'text-amber-500',
                'border' => 'border-amber-500/20',
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'glow' => 'shadow-[0_0_20px_rgba(245,158,11,0.2)]'
            ],
            'disetujui' => [
                'label' => 'Disetujui',
                'bg' => 'bg-emerald-500/10',
                'text' => 'text-emerald-500',
                'border' => 'border-emerald-500/20',
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>',
                'glow' => 'shadow-[0_0_20px_rgba(16,185,129,0.2)]'
            ],
            'ditolak' => [
                'label' => 'Ditolak',
                'bg' => 'bg-red-500/10',
                'text' => 'text-red-500',
                'border' => 'border-red-500/20',
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>',
                'glow' => 'shadow-[0_0_20px_rgba(239,68,68,0.2)]'
            ],
            'perlu_revisi' => [
                'label' => 'Perlu Revisi',
                'bg' => 'bg-orange-500/10',
                'text' => 'text-orange-500',
                'border' => 'border-orange-500/20',
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                'glow' => 'shadow-[0_0_20px_rgba(249,115,22,0.2)]'
            ],
        ];

        $status = $statusStyles[$pengajuan->status] ?? [
            'label' => 'Tidak Diketahui',
            'bg' => 'bg-gray-500/10',
            'text' => 'text-gray-500',
            'border' => 'border-gray-500/20',
            'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'glow' => ''
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

    <div class="max-w-9xl mx-auto space-y-6 pb-12">

        @if (session('success'))
            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm flex items-start gap-3 animate-in fade-in slide-in-from-top-4">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-emerald-900 uppercase tracking-widest">Berhasil!</h3>
                    <p class="mt-1 text-sm font-medium text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="p-5 rounded-2xl bg-red-50 border border-red-200 shadow-sm flex items-start gap-3 animate-in fade-in slide-in-from-top-4">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-red-900 uppercase tracking-widest">Pengajuan belum berhasil dikirim.</h3>
                    <ul class="mt-2 list-inside list-disc text-sm font-medium text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-4 space-y-6">
                
                <div class="relative overflow-hidden rounded-[2rem] bg-[#0B1120] p-8 shadow-2xl group">
                    <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#F2B51D] rounded-full blur-[80px] opacity-10 group-hover:opacity-20 transition-opacity duration-700 pointer-events-none"></div>

                    <div class="relative z-10">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border backdrop-blur-md mb-6 {{ $status['bg'] }} {{ $status['text'] }} {{ $status['border'] }} {{ $status['glow'] }}">
                            {!! $status['icon'] !!}
                            {{ $status['label'] }}
                        </span>
                        
                        <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight mb-2 leading-tight">
                            Detail<br>Izin Sakit
                        </h1>
                        <p class="text-sm font-medium text-gray-400 mb-8">
                            Diajukan pada {{ $pengajuan->created_at->translatedFormat('d F Y') }}
                        </p>

                        <div class="space-y-4 pt-6 border-t border-white/10">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Mulai</span>
                                <span class="text-sm font-bold text-white">{{ \Illuminate\Support\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Selesai</span>
                                <span class="text-sm font-bold text-white">{{ \Illuminate\Support\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Linimasa Status</h3>
                    
                    <div class="relative pl-3 space-y-8 before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-100">
                        <div class="relative flex items-start gap-4">
                            <div class="w-4 h-4 rounded-full border-2 border-[#F2B51D] bg-white ring-4 ring-white flex-shrink-0 mt-0.5 z-10">
                                <div class="w-full h-full bg-[#F2B51D] rounded-full scale-50"></div>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">Pengajuan Dikirim</div>
                                <div class="text-xs font-medium text-gray-500 mt-1">{{ $pengajuan->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        
                        @if($pengajuan->status !== 'menunggu')
                        <div class="relative flex items-start gap-4">
                            <div class="w-4 h-4 rounded-full border-2 {{ $status['border'] }} bg-white ring-4 ring-white flex-shrink-0 mt-0.5 z-10">
                                <div class="w-full h-full {{ $status['bg'] }} rounded-full scale-50"></div>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $status['label'] }}</div>
                                <div class="text-xs font-medium text-gray-500 mt-1">{{ $pengajuan->updated_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-6">
                
                @if (filled($pengajuan->catatan_tu))
                    <div class="bg-[#FFF9E9] rounded-[2rem] p-8 border border-[#F2E2B5] shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#F2B51D]/20 rounded-bl-full pointer-events-none"></div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full bg-[#F2B51D] flex items-center justify-center shadow-[0_0_15px_rgba(242,181,29,0.4)]">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-[#946600] uppercase tracking-widest">Catatan Tata Usaha</h3>
                        </div>
                        <p class="text-base font-medium text-gray-800 leading-relaxed whitespace-pre-line relative z-10">
                            "{{ $pengajuan->catatan_tu }}"
                        </p>
                    </div>
                @endif

                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4">Keterangan / Alasan Sakit</h3>
                    <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 relative">
                        <svg class="absolute top-4 right-4 w-10 h-10 text-gray-200 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        <p class="text-base font-medium text-gray-700 leading-relaxed relative z-10 whitespace-pre-line">
                            {{ $pengajuan->alasan }}
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-900 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900">Surat Keterangan Dokter</h3>
                                <p class="text-xs font-medium text-gray-500">Dokumen pendukung pengajuan.</p>
                            </div>
                        </div>
                        @if($documentUrl)
                            <a href="{{ $documentUrl }}?download=1" class="px-4 py-2 bg-[#0B1120] text-white text-xs font-bold rounded-lg hover:bg-[#F2B51D] hover:text-[#0B1120] transition-colors flex items-center gap-2 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh
                            </a>
                        @endif
                    </div>

                    @if ($documentUrl)
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 overflow-hidden flex justify-center">
                            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                                <img src="{{ $documentUrl }}" alt="Surat keterangan dokter" class="w-full h-auto max-h-[600px] object-contain rounded-xl shadow-2xl">
                            @elseif ($extension === 'pdf')
                                <iframe src="{{ $documentUrl }}" class="w-full h-[600px] rounded-xl border-0 bg-white shadow-2xl" title="Surat keterangan dokter"></iframe>
                            @else
                                <div class="p-10 text-center text-red-500 font-bold">
                                    Format dokumen tidak dapat ditampilkan.
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 p-10 text-center flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-sm font-bold text-gray-500">Dokumen tidak tersedia</p>
                        </div>
                    @endif
                </div>

                <div class="lg:hidden">
                    <a href="{{ route('surat-perizinan-sakit.index') }}" class="w-full py-4 bg-gray-50 border border-gray-200 text-gray-600 font-bold text-sm rounded-xl hover:bg-gray-100 hover:text-gray-900 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>

        @if (
            auth()->user()->isGuru() &&
            $pengajuan->user_id === auth()->id() &&
            $pengajuan->status === \App\Models\SuratPerizinanSakit::STATUS_PERLU_REVISI
        )
            <div class="bg-[#0B1120] rounded-[2.5rem] shadow-2xl overflow-hidden mt-8 relative border border-orange-500/30">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-500 to-[#F2B51D]"></div>
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-orange-500 rounded-full blur-[80px] opacity-20 pointer-events-none"></div>

                <div class="p-8 lg:p-10 relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-orange-500/20 text-orange-400 flex items-center justify-center shadow-[0_0_15px_rgba(249,115,22,0.3)]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white">Tindakan Diperlukan</h3>
                            <p class="text-sm font-medium text-gray-400">Silakan periksa catatan Tata Usaha dan perbaiki data pengajuan Anda.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('surat-perizinan-sakit.revisi', $pengajuan) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_mulai" class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Mulai Izin</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $tanggalMulai) }}" required class="w-full rounded-xl bg-white/5 border border-white/10 text-white px-4 py-3 text-sm font-bold focus:bg-white/10 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all outline-none">
                                @error('tanggal_mulai') <p class="mt-2 text-xs font-bold text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Selesai Izin</label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $tanggalSelesai) }}" min="{{ old('tanggal_mulai', $tanggalMulai) }}" required class="w-full rounded-xl bg-white/5 border border-white/10 text-white px-4 py-3 text-sm font-bold focus:bg-white/10 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all outline-none">
                                @error('tanggal_selesai') <p class="mt-2 text-xs font-bold text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="alasan" class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Alasan Sakit</label>
                            <textarea id="alasan" name="alasan" rows="4" required maxlength="2000" class="w-full rounded-xl bg-white/5 border border-white/10 text-white px-4 py-3 text-sm font-semibold focus:bg-white/10 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all outline-none placeholder-gray-500" placeholder="Perbaiki keterangan izin sakit Anda...">{{ old('alasan', $pengajuan->alasan) }}</textarea>
                            @error('alasan') <p class="mt-2 text-xs font-bold text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="surat_dokter" class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Ganti Surat Keterangan Dokter (Opsional)</label>
                            <input type="file" id="surat_dokter" name="surat_dokter" accept=".pdf,.jpg,.jpeg,.png,image/jpeg,image/png,application/pdf" class="w-full rounded-xl border border-white/10 bg-white/5 p-3 text-sm font-medium text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-orange-500/20 file:text-orange-400 hover:file:bg-orange-500/30 transition-all cursor-pointer">
                            <p class="mt-2 text-xs font-medium text-gray-500">Format: PDF, JPG, JPEG, PNG. Maksimal 5 MB. Kosongkan jika tidak diubah.</p>
                            @error('surat_dokter') <p class="mt-2 text-xs font-bold text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div id="preview-container" class="hidden rounded-2xl border border-orange-500/30 p-5 bg-orange-500/5">
                            <p class="mb-4 text-xs font-black text-orange-400 uppercase tracking-widest">Pratinjau Dokumen Baru</p>
                            <img id="image-preview" src="" alt="Preview surat dokter baru" class="hidden mx-auto max-h-[400px] w-auto rounded-xl border border-white/10 bg-white/5 object-contain">
                            <iframe id="pdf-preview" class="hidden h-[400px] w-full rounded-xl border border-white/10 bg-white/5" title="Preview PDF surat dokter"></iframe>
                            <p id="file-name" class="mt-4 break-all text-xs font-bold text-orange-400 text-center"></p>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 border-t border-white/10">
                            <a href="{{ route('surat-perizinan-sakit.index') }}" class="px-6 py-3 bg-white/5 text-gray-300 font-bold text-sm rounded-xl hover:bg-white/10 transition-colors border border-white/10">
                                Batal
                            </a>
                            <button type="submit" class="relative px-8 py-3 bg-orange-500 text-white font-black text-sm rounded-xl shadow-[0_10px_20px_rgba(249,115,22,0.3)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                                <span class="relative z-10 flex items-center gap-2">
                                    Kirim Perbaikan ke TU
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <p class="pt-8 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            SIMAS · Sistem Informasi Manajemen Administrasi Sekolah
        </p>
    </div>

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