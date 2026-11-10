<x-app-layout>
    <x-slot name="title">Edit Pengajuan Surat</x-slot>
    <x-slot name="subtitle">Perbarui informasi draft pengajuan surat Anda</x-slot>

    <style>
        .premium-input-group { position: relative; }
        
        .premium-input-group input, 
        .premium-input-group select, 
        .premium-input-group textarea {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 3rem;
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: #0F172A;
            transition: all 0.3s ease;
        }
        
        .premium-input-group input:focus, 
        .premium-input-group select:focus, 
        .premium-input-group textarea:focus {
            background-color: #ffffff;
            border-color: #FF5A1F;
            outline: none;
            box-shadow: 0 0 0 4px rgba(255, 90, 31, 0.1);
        }
        
        .premium-input-group .icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            transition: color 0.3s ease;
            pointer-events: none;
        }
        
        .premium-input-group input:focus ~ .icon,
        .premium-input-group select:focus ~ .icon {
            color: #FF5A1F;
        }
        
        .premium-input-group textarea { padding-top: 1rem; resize: none; }
        .premium-input-group textarea ~ .icon {
            top: 1.25rem;
            transform: none;
        }

        .premium-input-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none;
        }
    </style>

    <div class="max-w-7xl mx-auto" x-data="pengajuanWizard(@js([
        'blanko_id' => $pengajuan->blanko_id,
        'jenis_surat' => $pengajuan->jenis_surat,
        'nama_kegiatan' => $pengajuan->nama_kegiatan,
        'tanggal_pelaksanaan' => $pengajuan->tanggal_pelaksanaan ? $pengajuan->tanggal_pelaksanaan->format('Y-m-d') : '',
        'waktu_mulai' => $pengajuan->waktu_mulai,
        'waktu_selesai' => $pengajuan->waktu_selesai,
        'tempat' => $pengajuan->tempat,
        'keterangan' => $pengajuan->keterangan,
        'panitia' => $pengajuan->panitia ?? []
    ]))">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">
                
                <div class="bg-[#0B1120] rounded-[2rem] p-8 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#FF5A1F] rounded-full blur-[60px] opacity-20 pointer-events-none"></div>
                    
                    <h3 class="text-white font-extrabold mb-8 relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Langkah Pengisian
                    </h3>

                    <div class="relative z-10 space-y-6">
                        <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-white/10 z-0"></div>

                        <template x-for="(s, i) in steps" :key="i">
                            <div class="relative z-10 flex items-center gap-4 group">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-500"
                                     :class="step >= i ? 'bg-[#FF5A1F] text-white shadow-[0_0_15px_rgba(255,90,31,0.5)]' : 'bg-white/10 text-white/50'">
                                    <template x-if="step > i">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                    <template x-if="step <= i">
                                        <span x-text="i + 1"></span>
                                    </template>
                                </div>
                                <span class="text-sm font-bold transition-colors duration-300"
                                      :class="step >= i ? 'text-white' : 'text-white/40'" x-text="s.title"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="hidden lg:block bg-white rounded-[2rem] p-6 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)]">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Ringkasan Cepat</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                            <span class="text-xs font-semibold text-gray-500">Kegiatan</span>
                            <span class="text-xs font-bold text-gray-900 truncate max-w-[150px]" x-text="form.nama_kegiatan || '—'"></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                            <span class="text-xs font-semibold text-gray-500">Jenis Surat</span>
                            <span class="text-xs font-bold text-[#FF5A1F]" x-text="form.jenis_surat || '—'"></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                            <span class="text-xs font-semibold text-gray-500">Tanggal</span>
                            <span class="text-xs font-bold text-gray-900" x-text="form.tanggal_pelaksanaan || '—'"></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                            <span class="text-xs font-semibold text-gray-500">Tempat</span>
                            <span class="text-xs font-bold text-gray-900 truncate max-w-[150px]" x-text="form.tempat || '—'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-gray-500">Panitia</span>
                            <span class="text-xs font-bold text-gray-900" x-text="form.panitia.length + ' Orang'"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <form action="{{ route('pengajuan.update', $pengajuan) }}" method="POST" enctype="multipart/form-data" @submit="loading = true" class="bg-white rounded-[2rem] p-8 lg:p-10 border border-gray-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] relative overflow-hidden">
                    @csrf
                    @method('PUT')

                    <div x-show="step === 0" x-transition.opacity.duration.300ms>
                        <div class="mb-8">
                            <h2 class="text-2xl font-black text-gray-900">Informasi Dasar</h2>
                            <p class="text-sm font-medium text-gray-500 mt-1">Ubah jenis blanko dan detail utama kegiatan jika diperlukan.</p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Jenis Blanko <span class="text-red-500">*</span></label>
                                <div class="premium-input-group">
                                    <select name="blanko_id" required x-model="form.blanko_id">
                                        <option value="">— Pilih Blanko Surat —</option>
                                        @foreach($blanko as $b)
                                            <option value="{{ $b->id }}">{{ $b->nama }} ({{ $b->kode }})</option>
                                        @endforeach
                                    </select>
                                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                @error('blanko_id') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Jenis Surat <span class="text-red-500">*</span></label>
                                <div class="premium-input-group">
                                    <input type="text" name="jenis_surat" required x-model="form.jenis_surat" placeholder="Contoh: Surat Tugas / Undangan">
                                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                @error('jenis_surat') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Nama Kegiatan <span class="text-red-500">*</span></label>
                                <div class="premium-input-group">
                                    <input type="text" name="nama_kegiatan" required x-model="form.nama_kegiatan" placeholder="Contoh: Rapat Koordinasi MGMP">
                                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                @error('nama_kegiatan') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div x-show="step === 1" x-transition.opacity.duration.300ms x-cloak>
                        <div class="mb-8">
                            <h2 class="text-2xl font-black text-gray-900">Waktu & Tempat</h2>
                            <p class="text-sm font-medium text-gray-500 mt-1">Perbarui jadwal atau lokasi kegiatan.</p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                                <div class="premium-input-group">
                                    <input type="date" name="tanggal_pelaksanaan" required x-model="form.tanggal_pelaksanaan">
                                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                @error('tanggal_pelaksanaan') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Waktu Mulai</label>
                                    <div class="premium-input-group">
                                        <input type="time" name="waktu_mulai" x-model="form.waktu_mulai">
                                        <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Waktu Selesai</label>
                                    <div class="premium-input-group">
                                        <input type="time" name="waktu_selesai" x-model="form.waktu_selesai">
                                        <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    @error('waktu_selesai') <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Tempat Pelaksanaan</label>
                                <div class="premium-input-group">
                                    <input type="text" name="tempat" x-model="form.tempat" placeholder="Contoh: Aula Utama Sekolah">
                                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="step === 2" x-transition.opacity.duration.300ms x-cloak>
                        
                        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900">Susunan Panitia</h2>
                                <p class="text-sm font-medium text-gray-500 mt-1">Perbarui guru/pegawai yang terlibat dalam kegiatan ini.</p>
                            </div>
                            <button type="button" @click="addPanitia()" class="px-5 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-[#FF5A1F] hover:scale-105 transition-all shadow-md flex items-center justify-center gap-2 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                Tambah Panitia
                            </button>
                        </div>
                        
                        <div class="space-y-3 mb-8">
                            <template x-for="(p, index) in form.panitia" :key="index">
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-4 p-4 lg:p-5 bg-gray-50 rounded-2xl border border-gray-200/60 hover:border-[#FF5A1F]/40 transition-all duration-300 group shadow-sm">
                                    
                                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-black text-xs flex-shrink-0 mt-2 md:mt-0 group-hover:bg-[#FF5A1F] group-hover:text-white transition-colors duration-300">
                                        <span x-text="index + 1"></span>
                                    </div>
                                    
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4 w-full min-w-0">
                                        
                                        <div class="relative w-full group/select min-w-0">
                                            <select :name="`panitia[${index}][nama]`" x-model="p.nama" required class="w-full pl-4 pr-12 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 appearance-none bg-none focus:border-[#FF5A1F] focus:ring-4 focus:ring-[#FF5A1F]/10 hover:border-gray-300 transition-all outline-none cursor-pointer truncate">
                                                <option value="" disabled>Pilih Nama Guru...</option>
                                                <option value="Ahmad Fauzi, S.Pd.">Ahmad Fauzi, S.Pd.</option>
                                                <option value="Dewi Lestari, S.Pd.">Dewi Lestari, S.Pd.</option>
                                                <option value="Rudi Hartono, S.Kom.">Rudi Hartono, S.Kom.</option>
                                                <option value="Sari Indah, S.Pd.">Sari Indah, S.Pd.</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 group-focus-within/select:text-[#FF5A1F] transition-colors duration-300">
                                                <svg class="w-4 h-4 transform group-focus-within/select:-translate-y-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>

                                        <div class="relative w-full group/select min-w-0">
                                            <select :name="`panitia[${index}][jabatan]`" x-model="p.jabatan" required class="w-full pl-4 pr-12 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 appearance-none bg-none focus:border-[#FF5A1F] focus:ring-4 focus:ring-[#FF5A1F]/10 hover:border-gray-300 transition-all outline-none cursor-pointer truncate">
                                                <option value="" disabled>Pilih Jabatan...</option>
                                                <option value="Ketua">Ketua</option>
                                                <option value="Wakil Ketua">Wakil Ketua</option>
                                                <option value="Sekretaris">Sekretaris</option>
                                                <option value="Bendahara">Bendahara</option>
                                                <option value="Koordinator">Koordinator</option>
                                                <option value="Anggota">Anggota</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 group-focus-within/select:text-[#FF5A1F] transition-colors duration-300">
                                                <svg class="w-4 h-4 transform group-focus-within/select:-translate-y-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>

                                        <div class="relative w-full min-w-0">
                                            <input type="text" :name="`panitia[${index}][keterangan]`" x-model="p.keterangan" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:border-[#FF5A1F] focus:ring-4 focus:ring-[#FF5A1F]/10 hover:border-gray-300 transition-all outline-none placeholder-gray-400" placeholder="Keterangan (Opsional)">
                                        </div>
                                    </div>
                                    
                                    <button type="button" @click="removePanitia(index)" class="w-10 h-10 flex items-center justify-center text-red-400 hover:text-white hover:bg-red-500 rounded-xl transition-all duration-300 flex-shrink-0 mt-2 md:mt-0 shadow-sm" title="Hapus Panitia">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </template>
                            
                            <div x-show="form.panitia.length === 0" class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="w-12 h-12 mx-auto bg-white rounded-full flex items-center justify-center mb-3 shadow-sm">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-400">Belum ada panitia yang ditambahkan.</span>
                            </div>
                        </div>

                        <div class="mt-10 pt-8 border-t border-gray-100">
                            <h3 class="text-xl font-black text-gray-900 mb-4">Keterangan Tambahan</h3>
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Catatan (Opsional)</label>
                                <div class="premium-input-group">
                                    <textarea name="keterangan" rows="3" x-model="form.keterangan" placeholder="Tambahkan catatan penting atau instruksi khusus di sini (opsional)" class="!pl-4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-8 mt-10 border-t border-gray-100">
                        <button type="button" @click="prev()" x-show="step > 0" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-200 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali
                        </button>
                        <div x-show="step === 0"></div>

                        <div class="flex gap-3">
                            <template x-if="step < 2">
                                <button type="button" @click="next()" class="px-8 py-3 bg-[#FF5A1F] text-white font-bold text-sm rounded-xl shadow-[0_10px_20px_rgba(255,90,31,0.3)] hover:scale-105 transition-transform flex items-center gap-2">
                                    Selanjutnya
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </template>
                            
                            <template x-if="step === 2">
                                <div class="flex gap-3">
                                    <a href="{{ route('pengajuan.show', $pengajuan) }}" class="px-6 py-3 bg-gray-50 border border-gray-200 text-gray-600 font-bold text-sm rounded-xl hover:bg-gray-100 transition-colors">
                                        Batal
                                    </a>
                                    <button type="submit" name="aksi" value="draft" class="px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-colors">
                                        Simpan Draft
                                    </button>
                                    <button type="submit" name="aksi" value="submit" :disabled="loading" class="px-8 py-3 bg-[#0B1120] text-white font-bold text-sm rounded-xl hover:bg-black transition-colors disabled:opacity-50 flex items-center gap-2 shadow-lg">
                                        <svg class="w-4 h-4 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Ajukan Perubahan
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function pengajuanWizard(initialData = null) {
        return {
            step: 0,
            loading: false,
            steps: [
                { title: 'Info Dasar' }, 
                { title: 'Waktu & Tempat' }, 
                { title: 'Panitia & Catatan' }
            ],
            form: {
                blanko_id: initialData?.blanko_id || '',
                jenis_surat: initialData?.jenis_surat || '',
                nama_kegiatan: initialData?.nama_kegiatan || '',
                tanggal_pelaksanaan: initialData?.tanggal_pelaksanaan || '',
                waktu_mulai: initialData?.waktu_mulai || '',
                waktu_selesai: initialData?.waktu_selesai || '',
                tempat: initialData?.tempat || '',
                keterangan: initialData?.keterangan || '',
                panitia: initialData?.panitia || []
            },
            addPanitia() {
                this.form.panitia.push({ nama: '', jabatan: '', keterangan: '' });
            },
            removePanitia(index) {
                this.form.panitia.splice(index, 1);
            },
            next() { if(this.step < 2) this.step++; },
            prev() { if(this.step > 0) this.step--; }
        }
    }
    </script>
    @endpush
</x-app-layout>