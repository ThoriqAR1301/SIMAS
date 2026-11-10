@props([

    'title' => 'Belum Ada Data',
    'description' => 'Data akan muncul di sini setelah ditambahkan',
    'actionLabel' => null,
    'actionUrl' => null,
])

<div class="relative overflow-hidden text-center py-20 px-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(244,178,46,0.08)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(0,0,0,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,0,0,0.03)_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none dark:bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)]"></div>

    <div class="relative mx-auto w-24 h-24 mb-8 flex items-center justify-center">
        <div class="absolute inset-0 bg-[#F4B22E]/20 rounded-full blur-xl animate-pulse"></div>
        
        <div class="absolute inset-0 rounded-full border border-[#F4B22E]/30 dark:border-[#F4B22E]/20"></div>
        
        <div class="relative w-20 h-20 rounded-3xl bg-white dark:bg-[#0B1120] border border-gray-100 dark:border-white/10 shadow-[0_10px_30px_rgba(244,178,46,0.2)] flex items-center justify-center rotate-3 transition-transform duration-500 hover:rotate-0 hover:scale-105">
            <svg class="w-10 h-10 text-[#F4B22E] drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
    </div>

    <div class="relative z-10">
        <h3 class="text-2xl font-black text-brand-900 dark:text-white tracking-tight">
            {{ $title }}
        </h3>
        <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
            {{ $description }}
        </p>
        
        @if($actionLabel && $actionUrl)
            <a href="{{ $actionUrl }}" class="group relative inline-flex items-center justify-center gap-2 mt-8 px-8 py-3.5 bg-[#0B1120] dark:bg-white text-white dark:text-[#0B1120] font-black text-sm uppercase tracking-wider rounded-xl shadow-[0_10px_20px_rgba(11,17,32,0.2)] hover:scale-105 hover:shadow-[0_15px_30px_rgba(11,17,32,0.3)] transition-all duration-300 overflow-hidden">
                <span class="relative z-10 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#F4B22E] dark:text-[#0B1120] group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ $actionLabel }}
                </span>
                <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
            </a>
        @endif
    </div>
</div>