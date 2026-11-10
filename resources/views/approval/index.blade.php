<x-app-layout>
    <x-slot name="title">Approval</x-slot>
    <x-slot name="subtitle">{{ $step === 1 ? 'Tahap Verifikasi — Admin TU' : 'Tahap Persetujuan Final — Pimpinan' }}</x-slot>

    @php
        $user = auth()->user();
        $tabs = [
            ['key' => null, 'label' => 'Semua', 'count' => $pengajuan->total()],
            ['key' => 'segera', 'label' => 'Urgent', 'count' => 0],
        ];
    @endphp

    <div class="max-w-9xl mx-auto space-y-6 pb-12" x-data="{ selected: null, bulk: [] }">

        <div class="relative rounded-[2rem] overflow-hidden bg-[#0B1120] shadow-2xl shadow-black/20 group">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#FF5A1F] rounded-full blur-[100px] opacity-20 group-hover:opacity-30 transition-opacity duration-700 pointer-events-none"></div>

            <div class="relative p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-[#FF5A1F] animate-pulse"></span>
                        <span class="text-[10px] font-black text-[#FF5A1F] uppercase tracking-widest">
                            {{ $step === 1 ? 'Step 1 dari 2 · Verifikasi' : 'Step 2 dari 2 · Final' }}
                        </span>
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight mb-4">
                        {{ $step === 1 ? 'Verifikasi Pengajuan' : 'Persetujuan Pimpinan' }}
                    </h1>
                    <p class="text-sm font-medium text-gray-400 leading-relaxed">
                        {{ $step === 1
                            ? 'Periksa kelengkapan dan validitas pengajuan sebelum diteruskan ke Pimpinan.'
                            : 'Beri persetujuan final. Nomor surat akan diterbitkan secara otomatis setelah disetujui.' }}
                    </p>
                </div>

                <div class="relative px-8 py-6 rounded-3xl bg-white/[0.03] backdrop-blur-xl border border-white/10 shadow-lg flex-shrink-0 min-w-[200px]">
                    <div class="text-6xl font-black tracking-tighter text-white mb-1">
                        {{ number_format($pengajuan->total()) }}
                    </div>
                    <div class="text-[11px] font-black uppercase tracking-widest text-[#FF5A1F]">
                        Menunggu Tindakan
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 overflow-x-auto no-scrollbar py-2">
            <a href="{{ route('approval.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ !request('prioritas') ? 'bg-[#0B1120] text-white shadow-lg' : 'bg-white text-gray-500 border border-gray-200 hover:border-gray-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Semua
                <span class="ml-1 px-2 py-0.5 rounded-md text-[10px] {{ !request('prioritas') ? 'bg-white/20' : 'bg-gray-100' }}">{{ $pengajuan->total() }}</span>
            </a>
            
            <a href="{{ route('approval.index', ['prioritas' => 'segera']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ request('prioritas') === 'segera' ? 'bg-[#FF5A1F] text-white shadow-lg shadow-orange-500/30' : 'bg-white text-gray-500 border border-gray-200 hover:border-gray-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Urgent
            </a>
            
            <a href="{{ route('approval.index', ['dari' => now()->subDays(7)->format('Y-m-d')]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-white text-gray-500 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                7 Hari Terakhir
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7 xl:col-span-8 space-y-4">
                @forelse($pengajuan as $p)
                    <a href="{{ route('pengajuan.show', $p) }}" 
                       @mouseenter="selected = {{ $p->id }}"
                       class="group relative block bg-white rounded-2xl p-5 lg:p-6 border transition-all duration-300 overflow-hidden"
                       :class="selected === {{ $p->id }}
                           ? 'border-[#FF5A1F] shadow-[0_15px_40px_-10px_rgba(255,90,31,0.15)] ring-1 ring-[#FF5A1F]'
                           : 'border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:border-gray-300 hover:shadow-lg'">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 transition-colors duration-300"
                             :class="selected === {{ $p->id }} ? 'bg-[#FF5A1F]' : 'bg-transparent group-hover:bg-gray-200'"></div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-6">
                            
                            <div class="relative flex-shrink-0">
                                <div class="w-14 h-14 rounded-2xl bg-[#0B1120] flex items-center justify-center text-white font-black text-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                                    {{ strtoupper(substr($p->user->name, 0, 2)) }}
                                </div>
                                @if($p->prioritas === 'segera')
                                    <span class="absolute -top-2 -right-2 flex h-5 w-5">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 border-2 border-white items-center justify-center text-[10px] text-white font-bold">!</span>
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-2">
                                    <div>
                                        <h3 class="text-lg font-black text-gray-900 truncate group-hover:text-[#FF5A1F] transition-colors">
                                            {{ $p->nama_kegiatan }}
                                        </h3>
                                        <p class="text-sm font-semibold text-gray-500 mt-0.5 truncate">
                                            {{ $p->user->name }} <span class="text-gray-300 mx-1">•</span> {{ $p->user->jabatan }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-pengajuan.status-badge :status="$p->status" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-100">
                                    <div>
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nomor</div>
                                        <div class="font-mono text-xs font-bold text-[#0B1120] truncate bg-gray-50 px-2 py-1 rounded-md inline-block">
                                            {{ $p->nomor_pengajuan }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal</div>
                                        <div class="text-xs font-bold text-gray-800">
                                            {{ $p->tanggal_pelaksanaan->translatedFormat('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="hidden sm:block">
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Jenis Surat</div>
                                        <div class="text-xs font-bold text-gray-800 truncate">
                                            {{ $p->jenis_surat }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hidden sm:flex w-10 h-10 rounded-full bg-gray-50 items-center justify-center flex-shrink-0 group-hover:bg-[#FF5A1F] group-hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors group-hover:translate-x-0.5 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-16 text-center flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-2">Semua Beres! 🎉</h3>
                        <p class="text-sm font-medium text-gray-500 max-w-sm mb-8">
                            Tidak ada pengajuan yang menunggu tindakan Anda saat ini.
                        </p>
                        <a href="{{ route('pengajuan.index') }}" class="px-6 py-3 bg-[#0B1120] text-white font-bold text-sm rounded-xl hover:scale-105 transition-transform shadow-lg">
                            Lihat Semua Pengajuan
                        </a>
                    </div>

                @endforelse

                @if($pengajuan->hasPages())
                    <div class="mt-6">{{ $pengajuan->links() }}</div>
                @endif
            </div>

            <div class="lg:col-span-5 xl:col-span-4 hidden lg:block">
                <div class="lg:sticky lg:top-28">
                    
                    <template x-if="!selected">
                        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-10 text-center flex flex-col items-center justify-center min-h-[400px]">
                            <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 mb-2">Pratinjau Detail</h3>
                            <p class="text-sm font-medium text-gray-500 max-w-[250px]">
                                Arahkan kursor atau klik salah satu pengajuan di sebelah kiri untuk melihat detailnya di sini.
                            </p>
                        </div>
                    </template>

                    @foreach($pengajuan as $p)
                        <div x-show="selected === {{ $p->id }}" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] overflow-hidden">
                                
                                <div class="bg-[#0B1120] p-6 lg:p-8 text-white relative">
                                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#FF5A1F] rounded-full blur-[50px] opacity-30"></div>
                                    <div class="relative z-10">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-[#FF5A1F] mb-2">
                                            Detail Pengajuan
                                        </p>
                                        <h3 class="text-xl font-black leading-tight mb-3">
                                            {{ $p->nama_kegiatan }}
                                        </h3>
                                        <x-pengajuan.status-badge :status="$p->status" />
                                    </div>
                                </div>

                                <div class="p-6 lg:p-8 space-y-4">
                                    <dl class="space-y-3 text-sm">
                                        <div class="flex justify-between items-start gap-3 pb-3 border-b border-gray-50">
                                            <dt class="text-gray-500 font-medium">Pengaju</dt>
                                            <dd class="font-bold text-gray-900 text-right">{{ $p->user->name }}</dd>
                                        </div>
                                        <div class="flex justify-between items-start gap-3 pb-3 border-b border-gray-50">
                                            <dt class="text-gray-500 font-medium">Nomor</dt>
                                            <dd class="font-mono text-xs font-bold text-[#FF5A1F] bg-orange-50 px-2 py-1 rounded text-right">{{ $p->nomor_pengajuan }}</dd>
                                        </div>
                                        <div class="flex justify-between items-start gap-3 pb-3 border-b border-gray-50">
                                            <dt class="text-gray-500 font-medium">Jenis</dt>
                                            <dd class="font-bold text-gray-900 text-right">{{ $p->jenis_surat }}</dd>
                                        </div>
                                        <div class="flex justify-between items-start gap-3 pb-3 border-b border-gray-50">
                                            <dt class="text-gray-500 font-medium">Tanggal</dt>
                                            <dd class="font-bold text-gray-900 text-right">{{ $p->tanggal_pelaksanaan->translatedFormat('d F Y') }}</dd>
                                        </div>
                                        <div class="flex justify-between items-start gap-3">
                                            <dt class="text-gray-500 font-medium">Tempat</dt>
                                            <dd class="font-bold text-gray-900 text-right">{{ $p->tempat ?? '—' }}</dd>
                                        </div>
                                    </dl>

                                    <div class="pt-4">
                                        <a href="{{ route('pengajuan.show', $p) }}" class="w-full py-4 bg-[#0B1120] text-white font-black text-sm uppercase tracking-wider rounded-xl hover:bg-[#FF5A1F] transition-colors duration-300 flex items-center justify-center gap-2 group shadow-lg">
                                            Buka Detail Lengkap
                                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>