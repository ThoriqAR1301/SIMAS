@props([
    'label' => '',
    'value' => 0,
    'icon' => 'chart-bar',
    'trend' => null,
    'color' => 'brand', // brand | gold | emerald | red | blue
])

@php
    $colorMap = [
        'brand' => ['text-brand-600 dark:text-brand-400', 'bg-brand-50 dark:bg-brand-900/20', 'bg-brand-500/20'],
        'gold' => ['text-gold-600 dark:text-gold-400', 'bg-gold-50 dark:bg-gold-900/20', 'bg-gold-500/20'],
        'emerald' => ['text-emerald-600 dark:text-emerald-400', 'bg-emerald-50 dark:bg-emerald-900/20', 'bg-emerald-500/20'],
        'red' => ['text-red-600 dark:text-red-400', 'bg-red-50 dark:bg-red-900/20', 'bg-red-500/20'],
        'blue' => ['text-blue-600 dark:text-blue-400', 'bg-blue-50 dark:bg-blue-900/20', 'bg-blue-500/20'],
    ];
    [$textColor, $bgColor, $orbColor] = $colorMap[$color] ?? $colorMap['brand'];

    $iconPath = match($icon) {
        'chart-bar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.9M9 20H2v-2a4 4 0 014-4h3m8-3a4 4 0 10-8 0 4 4 0 008 0zm5-2a3 3 0 11-6 0"/>',
        'document' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
        'clock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
        'x-circle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>',
        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
    };
@endphp

<div class="group relative overflow-hidden bg-white/70 dark:bg-[#0B1120]/70 backdrop-blur-xl border border-white/60 dark:border-white/5 rounded-[2rem] p-6 lg:p-7 shadow-[0_8px_30px_rgba(0,0,0,0.04),inset_0_1px_0_rgba(255,255,255,0.8)] dark:shadow-[0_8px_30px_rgba(0,0,0,0.2),inset_0_1px_0_rgba(255,255,255,0.05)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-10px_rgba(0,0,0,0.1)] hover:border-gray-200 dark:hover:border-white/10 animate-in">
    
    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full {{ $orbColor }} blur-3xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>

    <div class="relative z-10 flex items-start justify-between mb-6">
        <div class="w-14 h-14 rounded-2xl {{ $bgColor }} {{ $textColor }} flex items-center justify-center shadow-inner border border-white/50 dark:border-white/10 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $iconPath !!}
            </svg>
        </div>
        
        @if($trend)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider backdrop-blur-md border 
                         {{ str_starts_with($trend, '+') 
                             ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:text-emerald-400' 
                             : 'bg-red-500/10 text-red-600 border-red-500/20 dark:text-red-400' }}">
                @if(str_starts_with($trend, '+'))
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                @else
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                @endif
                {{ $trend }}
            </span>
        @endif
    </div>

    <div class="relative z-10">
        <div class="text-4xl font-black text-brand-900 dark:text-white tracking-tighter">
            {{ is_numeric($value) ? number_format($value) : $value }}
        </div>
        <div class="text-[11px] font-black text-gray-500 dark:text-gray-400 mt-2 uppercase tracking-widest">
            {{ $label }}
        </div>
    </div>
</div>