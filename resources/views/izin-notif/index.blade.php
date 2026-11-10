<x-app-layout title="Notifikasi">
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="max-w-9xl mx-auto space-y-8 pb-12">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#FF5A1F]/10 border border-[#FF5A1F]/20 shadow-sm mb-4">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF5A1F] opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FF5A1F]"></span>
                    </span>
                    <span class="text-[10px] font-black text-[#FF5A1F] uppercase tracking-widest">Pusat Informasi</span>
                </div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Notifikasi Izin</h1>
                <p class="text-sm font-medium text-gray-500 mt-2">Klik notifikasi untuk membuka surat terkait. Notifikasi yang sudah dibuka akan diarsipkan.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('notifikasi.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
                    Notifikasi Pengajuan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                @if($notifs->count())
                    <form method="POST" action="{{ route('izin-notif.baca-semua') }}">
                        @csrf
                        <button class="px-5 py-2.5 bg-[#0B1120] text-white font-bold text-sm rounded-xl hover:bg-gray-900 transition-colors shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="divide-y divide-gray-50">
                @forelse($notifs as $n)
                    <form method="POST" action="{{ route('izin-notif.baca', $n->id) }}" class="group">
                        @csrf
                        <button type="submit" class="w-full text-left px-6 py-5 hover:bg-[#F8FAFC] transition-all duration-300 flex items-start gap-5 relative focus:outline-none focus:bg-gray-50">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#FF5A1F] opacity-0 group-hover:opacity-100 transition-opacity"></div>

                            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-[#FF5A1F] flex items-center justify-center flex-shrink-0 shadow-sm border border-orange-100 group-hover:scale-105 group-hover:bg-[#FF5A1F] group-hover:text-white transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>

                            <div class="flex-1 min-w-0 pt-1">
                                <h4 class="text-base font-bold text-gray-900 group-hover:text-[#FF5A1F] transition-colors leading-snug">
                                    {{ $n->judul }}
                                </h4>
                                <p class="text-sm font-medium text-gray-500 mt-1.5 leading-relaxed">
                                    {{ $n->pesan }}
                                </p>
                                <div class="flex items-center gap-1.5 mt-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $n->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0 mt-3 text-gray-400 group-hover:bg-[#FF5A1F] group-hover:text-white transition-all duration-300">
                                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </button>
                    </form>
                @empty
                    <div class="p-16 text-center flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-2">Tidak Ada Notifikasi Baru</h3>
                        <p class="text-sm font-medium text-gray-500 max-w-sm">
                            Semua notifikasi izin sudah Anda baca atau belum ada pengajuan baru yang masuk.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>