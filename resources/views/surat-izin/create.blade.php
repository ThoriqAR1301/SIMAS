<x-app-layout title="Buat Surat Izin">
    <div class="min-h-screen bg-slate-50 relative overflow-hidden pb-12">
        
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#F4B22E]/10 rounded-full blur-[100px] pointer-events-none translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-[100px] pointer-events-none -translate-x-1/3 translate-y-1/3"></div>

        <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 relative z-10 space-y-8">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-1 rounded-md bg-[#F4B22E]/10 text-[#d99a1a] text-[10px] font-black uppercase tracking-widest border border-[#F4B22E]/20">Form Pengajuan</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buat Surat Izin</h1>
                    <p class="text-sm font-medium text-slate-500">Isi data diri, tanda tangan digital, lalu surat siap di-print</p>
                </div>
            </div>

            <form method="POST" action="{{ route('surat-izin.store') }}" 
                  class="bg-white/80 backdrop-blur-xl rounded-[2rem] border border-slate-200/60 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] overflow-hidden">
                @csrf
                
                <div class="p-6 md:p-10">
                    @include('surat-izin._form')
                </div>

                <div class="bg-slate-50/80 px-6 py-5 md:px-10 border-t border-slate-100 flex justify-end">
                    <button type="submit" 
                            class="group relative inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[#F4B22E] to-[#e0a020] hover:from-[#e0a020] hover:to-[#c98a15] text-white font-bold text-sm rounded-xl shadow-[0_8px_20px_rgba(244,178,46,0.3)] hover:shadow-[0_8px_25px_rgba(244,178,46,0.4)] transition-all duration-300 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Simpan & Buat Surat
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>