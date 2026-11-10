<x-app-layout>
    <x-slot name="title">Detail Surat Masuk</x-slot>
    <x-slot name="subtitle">Informasi Lengkap Arsip Surat Industri</x-slot>

    <div class="max-w-9xl mx-auto pb-12">
        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 p-8 lg:p-10 space-y-8">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-6">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Random Code</span>
                    <p class="text-2xl font-black text-[#F4B22E] font-mono tracking-tight">{{ $suratMasuk->random_code }}</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 text-xs font-bold bg-[#F4B22E]/10 text-[#d99a1a] rounded-full border border-[#F4B22E]/20">
                        {{ $suratMasuk->kategori_folder }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Nama / Perihal Surat</span>
                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $suratMasuk->nama_surat }}</p>
                </div>

                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Asal Industri / Instansi</span>
                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $suratMasuk->asal_surat }}</p>
                </div>

                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Tanggal Diterima</span>
                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $suratMasuk->tanggal_diterima }}</p>
                </div>

                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Penginput (Guru)</span>
                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $suratMasuk->user->name ?? 'System' }}</p>
                </div>
            </div>

            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Keterangan / Deskripsi</span>
                <div class="bg-gray-50/80 border border-gray-100 p-4 rounded-xl">
                    <p class="text-sm font-semibold text-gray-600 leading-relaxed">
                        {{ $suratMasuk->deskripsi ?? 'Tidak ada keterangan tambahan' }}
                    </p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-4">Preview Lampiran Surat</span>
                
                @if($suratMasuk->file_surat)
                    @php
                        $extension = pathinfo($suratMasuk->file_surat, PATHINFO_EXTENSION);
                    @endphp

                    <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-6 flex flex-col items-center justify-center min-h-[250px] relative overflow-hidden group">
                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                            <img src="{{ asset('storage/' . $suratMasuk->file_surat) }}" alt="Surat Masuk" class="max-h-[400px] w-auto rounded-xl shadow-sm object-contain mb-6">
                        @elseif(strtolower($extension) === 'pdf')
                            <iframe src="{{ asset('storage/' . $suratMasuk->file_surat) }}" class="w-full h-[400px] rounded-xl border border-gray-200 mb-6 bg-white"></iframe>
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                        @endif

                        <a href="{{ asset('storage/' . $suratMasuk->file_surat) }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-[#F4B22E] hover:bg-[#d99a1a] text-white text-sm font-bold rounded-xl shadow-md transition-all duration-300 transform group-hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Buka File di Tab Baru / Download
                        </a>
                    </div>
                @else
                    <div class="bg-gray-50/50 border border-gray-100 border-dashed rounded-2xl p-8 flex flex-col items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <p class="text-sm font-semibold text-gray-400 italic">Tidak ada file surat yang dilampirkan.</p>
                    </div>
                @endif
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-between items-center pt-8 border-t border-gray-100 gap-4">
                <a href="{{ route('surat-masuk.index') }}" 
                   class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-200 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all text-center">
                    Kembali ke Daftar
                </a>
                <a href="{{ route('surat-masuk.edit', $suratMasuk->id) }}" 
                   class="w-full sm:w-auto px-8 py-3 bg-[#0B1120] hover:bg-gray-900 text-white font-bold text-sm rounded-xl shadow-md transition-all text-center">
                    Edit Surat
                </a>
            </div>

        </div>
    </div>
</x-app-layout>