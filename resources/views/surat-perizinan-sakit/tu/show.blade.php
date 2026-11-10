<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">
                Pemeriksaan Detail Pengajuan
            </h2>
            <p class="text-[11px] font-medium text-gray-500 mt-0.5">
                Pratinjau detail pengajuan izin sakit untuk pemeriksaan Tata Usaha.
            </p>
        </div>
    </x-slot>

    @php
        $statusLabels = [
            \App\Models\SuratPerizinanSakit::STATUS_MENUNGGU => ['Menunggu', 'bg-amber-500/10 text-amber-600 border-amber-500/20', 'bg-amber-500'],
            \App\Models\SuratPerizinanSakit::STATUS_DISETUJUI => ['Disetujui', 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20', 'bg-emerald-500'],
            \App\Models\SuratPerizinanSakit::STATUS_DITOLAK => ['Ditolak', 'bg-red-500/10 text-red-600 border-red-500/20', 'bg-red-500'],
            \App\Models\SuratPerizinanSakit::STATUS_PERLU_REVISI => ['Perlu Revisi', 'bg-orange-500/10 text-orange-600 border-orange-500/20', 'bg-orange-500'],
        ];
        [$statusLabel, $statusClass, $statusDot] = $statusLabels[$pengajuan->status] ?? ['Tidak Dikenal', 'bg-gray-100 text-gray-600 border-gray-200', 'bg-gray-400'];
    @endphp

    <div class="max-w-9xl mx-auto space-y-6 pb-12">

        <div class="relative rounded-[2rem] overflow-hidden bg-[#0B1120] shadow-2xl shadow-black/20 group">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#F5B324] rounded-full blur-[100px] opacity-20 group-hover:opacity-30 transition-opacity duration-700 pointer-events-none"></div>

            <div class="relative p-8 lg:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 mb-4 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-[#F5B324] animate-pulse"></span>
                        <span class="text-[10px] font-black text-[#F5B324] uppercase tracking-widest">Pemeriksaan Tata Usaha</span>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight mb-3">
                        Detail Izin Sakit
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-gray-400">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#F5B324] to-[#D99A1A] flex items-center justify-center text-[#0B1120] font-black">
                                {{ strtoupper(substr($pengajuan->user?->name ?? 'G', 0, 1)) }}
                            </div>
                            {{ $pengajuan->user?->name ?? 'Pengguna tidak diketahui' }}
                        </div>
                        <span class="text-gray-600">•</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg border text-[10px] font-black uppercase tracking-wider {{ $statusClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <div class="lg:col-span-7 space-y-6">
                
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-900 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900">Informasi Pengajuan</h3>
                            <p class="text-xs font-medium text-gray-500">Data izin sakit yang dikirim Guru.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Tanggal Mulai</span>
                            <div class="text-base font-bold text-[#0B1120]">{{ $pengajuan->tanggal_mulai?->format('d/m/Y') }}</div>
                        </div>
                        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Tanggal Selesai</span>
                            <div class="text-base font-bold text-[#0B1120]">{{ $pengajuan->tanggal_selesai?->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Alasan Sakit</span>
                        <div class="p-6 rounded-2xl bg-orange-50/50 border border-orange-100 relative">
                            <svg class="absolute top-4 right-4 w-10 h-10 text-orange-200 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            <p class="text-sm font-medium text-gray-800 leading-relaxed relative z-10 whitespace-pre-line">
                                {{ $pengajuan->alasan }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-[#F5B324]/20 text-[#946600] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.6-4.6a2 2 0 012.8 0L16 16m-2-2l1.6-1.6a2 2 0 012.8 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900">Surat Keterangan Dokter</h3>
                            <p class="text-xs font-medium text-gray-500">Periksa keaslian dan kelengkapan dokumen.</p>
                        </div>
                    </div>

                    @if ($pengajuan->surat_dokter_path)
                        @php
                            $ext = strtolower(pathinfo($pengajuan->surat_dokter_path, PATHINFO_EXTENSION));
                            $dokterUrl = route('surat-perizinan-sakit.dokter', $pengajuan);
                        @endphp
                        
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 overflow-hidden flex flex-col items-center justify-center">
                            @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                <a href="{{ $dokterUrl }}" target="_blank" class="w-full flex justify-center">
                                    <img src="{{ $dokterUrl }}" alt="Surat dokter" class="max-w-full h-auto max-h-[600px] object-contain rounded-xl shadow-2xl border border-white">
                                </a>
                                <p class="mt-4 text-xs font-bold text-gray-400">Klik gambar untuk membuka ukuran penuh.</p>
                            @elseif ($ext === 'pdf')
                                <div class="py-8 text-center">
                                    <div class="w-16 h-16 mx-auto bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4 text-[#F5B324]">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800 mb-4">Dokumen PDF Tersedia</p>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ $dokterUrl }}" target="_blank" class="px-5 py-2.5 bg-[#0B1120] text-white text-xs font-bold rounded-xl hover:bg-gray-800 transition-colors">Buka PDF</a>
                                        <a href="{{ $dokterUrl }}?download=1" class="px-5 py-2.5 bg-white text-gray-700 border border-gray-200 text-xs font-bold rounded-xl hover:bg-gray-50 transition-colors">Unduh PDF</a>
                                    </div>
                                </div>
                            @else
                                <div class="py-6 text-sm font-bold text-red-500">Format dokumen tidak didukung.</div>
                            @endif
                        </div>
                    @else
                        <div class="rounded-2xl border-2 border-dashed border-red-200 bg-red-50 p-8 text-center flex flex-col items-center">
                            <svg class="w-10 h-10 text-red-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <p class="text-sm font-bold text-red-600">Guru belum mengunggah surat dokter.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-5 space-y-6">
                
                @if (filled($pengajuan->catatan_tu))
                    <div class="bg-[#FFF9E9] rounded-[2rem] p-6 lg:p-8 border border-[#F2E2B5] shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-[#F5B324]/20 rounded-bl-full pointer-events-none"></div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#F5B324] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#F5B324]"></span>
                            </span>
                            <h3 class="text-xs font-black text-[#946600] uppercase tracking-widest">Catatan Sebelumnya</h3>
                        </div>
                        <p class="text-sm font-medium text-gray-800 leading-relaxed whitespace-pre-line relative z-10 italic">
                            "{{ $pengajuan->catatan_tu }}"
                        </p>
                    </div>
                @endif

                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.08)] lg:sticky lg:top-28 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0B1120] via-[#F5B324] to-[#0B1120]"></div>

                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-[#0B1120] text-[#F5B324] flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900">Hasil Pemeriksaan</h3>
                            <p class="text-xs font-medium text-gray-500">Tentukan keputusan pengajuan.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('surat-perizinan-sakit.verify', $pengajuan) }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="catatan_tu" class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Catatan Pemeriksaan</label>
                            <textarea name="catatan_tu" id="catatan_tu" rows="4" class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 focus:bg-white focus:border-[#F5B324] focus:ring-4 focus:ring-[#F5B324]/20 transition-all outline-none resize-none placeholder-gray-400" placeholder="Tuliskan catatan. Wajib diisi untuk revisi atau penolakan.">{{ old('catatan_tu', $pengajuan->catatan_tu) }}</textarea>
                            @error('catatan_tu') <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <p class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-3">Pilih Keputusan</p>
                            <div class="space-y-3">
                                
                                <label class="relative flex items-center p-4 rounded-xl border-2 border-gray-100 bg-white cursor-pointer hover:border-emerald-300 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:shadow-md group">
                                    <input type="radio" name="keputusan" value="disetujui" {{ old('keputusan') === 'disetujui' ? 'checked' : '' }} required class="w-5 h-5 text-emerald-500 border-gray-300 focus:ring-emerald-500 focus:ring-offset-0 transition-all">
                                    <div class="ml-4 flex-1">
                                        <span class="block text-sm font-bold text-gray-900 group-has-[:checked]:text-emerald-700">Setujui Pengajuan</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">Pengajuan valid dan disetujui.</span>
                                    </div>
                                    <div class="opacity-0 group-has-[:checked]:opacity-100 transition-opacity text-emerald-500">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-4 rounded-xl border-2 border-gray-100 bg-white cursor-pointer hover:border-orange-300 transition-all has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50 has-[:checked]:shadow-md group">
                                    <input type="radio" name="keputusan" value="perlu_revisi" {{ old('keputusan') === 'perlu_revisi' ? 'checked' : '' }} class="w-5 h-5 text-orange-500 border-gray-300 focus:ring-orange-500 focus:ring-offset-0 transition-all">
                                    <div class="ml-4 flex-1">
                                        <span class="block text-sm font-bold text-gray-900 group-has-[:checked]:text-orange-700">Minta Revisi</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">Dokumen kurang lengkap atau salah.</span>
                                    </div>
                                    <div class="opacity-0 group-has-[:checked]:opacity-100 transition-opacity text-orange-500">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-4 rounded-xl border-2 border-gray-100 bg-white cursor-pointer hover:border-red-300 transition-all has-[:checked]:border-red-500 has-[:checked]:bg-red-50 has-[:checked]:shadow-md group">
                                    <input type="radio" name="keputusan" value="ditolak" {{ old('keputusan') === 'ditolak' ? 'checked' : '' }} class="w-5 h-5 text-red-500 border-gray-300 focus:ring-red-500 focus:ring-offset-0 transition-all">
                                    <div class="ml-4 flex-1">
                                        <span class="block text-sm font-bold text-gray-900 group-has-[:checked]:text-red-700">Tolak Pengajuan</span>
                                        <span class="block text-xs text-gray-500 mt-0.5">Pengajuan tidak dapat diterima.</span>
                                    </div>
                                    <div class="opacity-0 group-has-[:checked]:opacity-100 transition-opacity text-red-500">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                    </div>
                                </label>

                            </div>
                            @error('keputusan') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="relative w-full py-4 bg-[#0B1120] text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-[0_10px_20px_rgba(11,17,32,0.2)] hover:scale-[1.02] hover:shadow-[0_15px_30px_rgba(11,17,32,0.3)] transition-all duration-300 flex items-center justify-center gap-2 overflow-hidden group">
                            <span class="relative z-10 flex items-center gap-2">
                                Simpan Keputusan
                                <svg class="w-4 h-4 text-[#F5B324] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes shine {
            100% { left: 125%; }
        }
        .animate-shine {
            animation: shine 0.8s;
        }
    </style>
</x-app-layout>