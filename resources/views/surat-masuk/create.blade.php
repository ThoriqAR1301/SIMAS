<x-app-layout>
    <x-slot name="title">Input Surat Masuk</x-slot>
    <x-slot name="subtitle">Catat Surat Dari Industri Atau Instansi Secara Manual</x-slot>

    <div class="max-w-9xl mx-auto pb-12">
        <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data" 
              class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 p-8 lg:p-10 space-y-8">
            @csrf

            <div class="border-b border-gray-100 pb-6">
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Formulir Surat Masuk</h3>
                <p class="text-sm font-semibold text-gray-500 mt-1">Lengkapi Detail Informasi Surat Di Bawah Ini</p>
            </div>

            <div class="space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Random Code (Auto Generated)</label>
                    <input type="text" name="random_code" value="{{ $randomCode }}" readonly 
                           class="w-full px-4 py-3 bg-[#F4B22E]/5 border border-[#F4B22E]/20 rounded-xl text-sm font-mono font-bold text-[#d99a1a] outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama / Perihal Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_surat" value="{{ old('nama_surat') }}" required placeholder="Contoh : Undangan Workshop Industri" 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none placeholder:text-gray-400 placeholder:font-normal">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Folder <span class="text-red-500">*</span></label>
                    <input type="text" name="kategori_folder" value="{{ old('kategori_folder') }}" required placeholder="Contoh : MoU Industri, Undangan, Kerjasama" 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none placeholder:text-gray-400 placeholder:font-normal">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Asal Surat (Perusahaan / Industri) <span class="text-red-500">*</span></label>
                    <input type="text" name="asal_surat" value="{{ old('asal_surat') }}" required placeholder="Contoh : PT Telkom Indonesia" 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none placeholder:text-gray-400 placeholder:font-normal">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Diterima <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', date('Y-m-d')) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Keterangan tambahan (Opsional)" 
                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none resize-none placeholder:text-gray-400 placeholder:font-normal">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload File Surat (PDF / Gambar)</label>
                    <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" 
                           class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-600 font-semibold focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#F4B22E]/10 file:text-[#d99a1a] hover:file:bg-[#F4B22E]/20 file:transition-colors">
                    <p class="text-xs font-semibold text-gray-400 mt-2">Format: PDF, JPG, PNG · Maksimal 2MB</p>
                </div>
            </div>

            <div class="flex justify-between items-center pt-8 border-t border-gray-100">
                <a href="{{ route('surat-masuk.index') }}" 
                   class="px-6 py-3 bg-white border border-gray-200 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all text-center">
                    Kembali
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-[#F4B22E] hover:bg-[#d99a1a] text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition-all">
                    Simpan Surat
                </button>
            </div>

        </form>
    </div>
</x-app-layout>