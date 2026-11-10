@props(['status', 'size' => 'sm'])

@php
    $key = $status->value ?? $status;

    $config = [
        'draft' => [
            'bg' => 'bg-slate-500/10 dark:bg-slate-400/10',
            'text' => 'text-slate-600 dark:text-slate-300',
            'border' => 'border-slate-500/20 dark:border-slate-400/20',
            'shadow' => 'shadow-[0_0_10px_rgba(100,116,139,0.1)]',
            'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>',
            'label' => 'Draft'
        ],
        'diajukan' => [
            'bg' => 'bg-blue-500/10 dark:bg-blue-400/10',
            'text' => 'text-blue-600 dark:text-blue-400',
            'border' => 'border-blue-500/20 dark:border-blue-400/20',
            'shadow' => 'shadow-[0_0_12px_rgba(59,130,246,0.2)]',
            'icon' => '<svg class="w-full h-full animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>',
            'label' => 'Menunggu Verifikasi'
        ],
        'diproses' => [
            'bg' => 'bg-[#FF5A1F]/10 dark:bg-[#FF5A1F]/10',
            'text' => 'text-[#FF5A1F] dark:text-[#FF8A5C]',
            'border' => 'border-[#FF5A1F]/20 dark:border-[#FF5A1F]/20',
            'shadow' => 'shadow-[0_0_15px_rgba(255,90,31,0.25)]',
            'icon' => '<svg class="w-full h-full animate-[spin_3s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'label' => 'Menunggu Persetujuan'
        ],
        'disetujui' => [
            'bg' => 'bg-emerald-500/10 dark:bg-emerald-400/10',
            'text' => 'text-emerald-600 dark:text-emerald-400',
            'border' => 'border-emerald-500/20 dark:border-emerald-400/20',
            'shadow' => 'shadow-[0_0_12px_rgba(16,185,129,0.2)]',
            'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>',
            'label' => 'Disetujui'
        ],
        'ditolak' => [
            'bg' => 'bg-red-500/10 dark:bg-red-400/10',
            'text' => 'text-red-600 dark:text-red-400',
            'border' => 'border-red-500/20 dark:border-red-400/20',
            'shadow' => 'shadow-[0_0_12px_rgba(239,68,68,0.2)]',
            'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>',
            'label' => 'Ditolak'
        ],
        'selesai' => [
            'bg' => 'bg-[#0B1120] dark:bg-white/10',
            'text' => 'text-white dark:text-white',
            'border' => 'border-[#0B1120] dark:border-white/20',
            'shadow' => 'shadow-lg',
            'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>',
            'label' => 'Selesai'
        ],
    ];

    $style = $config[$key] ?? $config['draft'];
    
    $padding = $size === 'lg' ? 'px-3.5 py-1.5' : 'px-2.5 py-1';
    $textSize = $size === 'lg' ? 'text-xs' : 'text-[10px]';
    $iconSize = $size === 'lg' ? 'w-3.5 h-3.5' : 'w-3 h-3';
@endphp

<span class="inline-flex items-center gap-1.5 {{ $padding }} rounded-full {{ $textSize }} font-black uppercase tracking-widest border {{ $style['bg'] }} {{ $style['text'] }} {{ $style['border'] }} {{ $style['shadow'] }} backdrop-blur-sm transition-all duration-300 hover:scale-105 cursor-default">
    
    <span class="{{ $iconSize }} flex-shrink-0 drop-shadow-sm">
        {!! $style['icon'] !!}
    </span>
    
    {{ $style['label'] }}
    
</span>