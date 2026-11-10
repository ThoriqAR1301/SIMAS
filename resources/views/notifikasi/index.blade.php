<x-app-layout>
    <x-slot name="title">Pusat Notifikasi</x-slot>
    <x-slot name="subtitle">Informasi terbaru seputar aktivitas administrasi Anda</x-slot>

    <div class="max-w-9xl mx-auto space-y-6 pb-12">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#FF5A1F]/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#FF5A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    Notifikasi Anda
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-2">Semua pembaruan terkait pengajuan dan surat masuk.</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse ($notifications as $notification)
                <a
                    href="{{ $notification->data['url'] ?? '#' }}"
                    class="group relative block bg-white rounded-2xl p-5 lg:p-6 border transition-all duration-300 overflow-hidden
                           {{ $notification->read_at 
                               ? 'border-gray-100 hover:border-gray-300 hover:shadow-lg' 
                               : 'border-[#FF5A1F]/30 shadow-[0_8px_30px_rgba(255,90,31,0.08)] hover:shadow-[0_12px_40px_rgba(255,90,31,0.15)] hover:border-[#FF5A1F]' }}"
                >
                    @if(!$notification->read_at)
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#FF5A1F] shadow-[0_0_15px_#FF5A1F]"></div>
                    @endif

                    <div class="flex items-start gap-4 sm:gap-5">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110 duration-300
                                    {{ $notification->read_at ? 'bg-gray-50 text-gray-400' : 'bg-orange-50 text-[#FF5A1F]' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-base font-bold text-gray-900 truncate group-hover:text-[#FF5A1F] transition-colors">
                                    {{ $notification->data['judul'] ?? 'Notifikasi' }}
                                </p>
                                
                                @if(!$notification->read_at)
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-[#FF5A1F] text-white shadow-sm flex-shrink-0 animate-pulse">
                                        Baru
                                    </span>
                                @endif
                            </div>

                            @if (!empty($notification->data['pesan']))
                                <p class="mt-1.5 text-sm font-medium text-gray-500 leading-relaxed line-clamp-2">
                                    {{ $notification->data['pesan'] }}
                                </p>
                            @endif

                            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <div class="hidden sm:flex w-8 h-8 rounded-full bg-gray-50 group-hover:bg-[#FF5A1F] items-center justify-center flex-shrink-0 mt-2 transition-colors duration-300">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors group-hover:translate-x-0.5 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-12 text-center">
                    <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">Semua Beres!</h3>
                    <p class="text-sm font-medium text-gray-500 max-w-sm mx-auto">
                        Belum ada notifikasi baru untuk Anda saat ini. Kami akan memberi tahu Anda jika ada aktivitas.
                    </p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="mt-8 pt-6 border-t border-gray-100">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
