<x-app-layout>
    <x-slot name="title">Edit Surat Masuk</x-slot>
    <x-slot name="subtitle">Perbarui Data Surat Masuk Industri</x-slot>

    <div class="max-w-9xl mx-auto pb-12">
        <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data" 
              class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 p-8 lg:p-10 space-y-8">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-100 pb-6">
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Perbarui Surat Masuk</h3>
                <p class="text-sm font-semibold text-gray-500 mt-1">Ubah Informasi Yang Diperlukan</p>
            </div>

            <div class="space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Random Code</label>
                    <input type="text" value="{{ $suratMasuk->random_code }}" readonly 
                           class="w-full px-4 py-3 bg-[#F4B22E]/5 border border-[#F4B22E]/20 rounded-xl text-sm font-mono font-bold text-[#d99a1a] outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama / Perihal Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_surat" value="{{ old('nama_surat', $suratMasuk->nama_surat) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none placeholder:text-gray-400 placeholder:font-normal">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Folder <span class="text-red-500">*</span></label>
                    <input type="text" name="kategori_folder" value="{{ old('kategori_folder', $suratMasuk->kategori_folder) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none placeholder:text-gray-400 placeholder:font-normal">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Asal Surat (Perusahaan / Industri) <span class="text-red-500">*</span></label>
                    <input type="text" name="asal_surat" value="{{ old('asal_surat', $suratMasuk->asal_surat) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none placeholder:text-gray-400 placeholder:font-normal">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Diterima <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', $suratMasuk->tanggal_diterima) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Deskripsi</label>
                    <textarea name="deskripsi" rows="3" 
                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none resize-none placeholder:text-gray-400 placeholder:font-normal">{{ old('deskripsi', $suratMasuk->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ganti File Surat (Opsional)</label>
                    
                    @if($suratMasuk->file_surat)
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $suratMasuk->file_surat) }}" target="_blank" 
                               class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d99a1a] bg-[#F4B22E]/10 px-3 py-1.5 rounded-lg hover:bg-[#F4B22E]/20 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat File Lampiran Saat Ini
                            </a>
                        </div>
                    @endif
                    
                    <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" 
                           class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-600 font-semibold focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#F4B22E]/10 file:text-[#d99a1a] hover:file:bg-[#F4B22E]/20 file:transition-colors">
                </div>
            </div>

            <div class="flex justify-between items-center pt-8 border-t border-gray-100">
                <a href="{{ route('surat-masuk.index') }}" 
                   class="px-6 py-3 bg-white border border-gray-200 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all text-center">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-[#F4B22E] hover:bg-[#d99a1a] text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition-all">
                    Perbarui Surat
                </button>
            </div>

        </form>
    </div>
</x-app-layout>