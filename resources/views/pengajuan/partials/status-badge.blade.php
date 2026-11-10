@props(['status', 'size' => 'sm'])

@php
    // Desain Glassmorphism: Menggunakan opasitas /10 untuk background, /20 untuk border
    $variants = [
        'draft' => ['bg-ink-500/10 text-ink-600 border-ink-500/20 dark:bg-ink-400/10 dark:text-ink-300 dark:border-ink-400/20', 'bg-ink-400', 'Draft'],
        'diajukan' => ['bg-blue-500/10 text-blue-600 border-blue-500/20 dark:bg-blue-400/10 dark:text-blue-300 dark:border-blue-400/20', 'bg-blue-500', 'Menunggu Verifikasi'],
        'diproses' => ['bg-gold-500/10 text-gold-700 border-gold-500/20 dark:bg-gold-400/10 dark:text-gold-300 dark:border-gold-400/20', 'bg-gold-500', 'Menunggu Persetujuan'],
        'disetujui' => ['bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-400/10 dark:text-emerald-300 dark:border-emerald-400/20', 'bg-emerald-500', 'Disetujui'],
        'ditolak' => ['bg-red-500/10 text-red-600 border-red-500/20 dark:bg-red-400/10 dark:text-red-300 dark:border-red-400/20', 'bg-red-500', 'Ditolak'],
        'selesai' => ['bg-brand-500/10 text-brand-600 border-brand-500/20 dark:bg-brand-400/10 dark:text-brand-300 dark:border-brand-400/20', 'bg-brand-500', 'Selesai'],
    ];
    
    $key = $status->value ?? $status;
    [$bg, $dot, $label] = $variants[$key] ?? $variants['draft'];
    
    // Padding sedikit diperlebar agar terlihat lebih proporsional
    $padding = $size === 'lg' ? 'px-3.5 py-1.5 text-xs' : 'px-2.5 py-1 text-[9px]';
@endphp

<span class="inline-flex items-center gap-2 {{ $padding }} rounded-full font-black uppercase tracking-widest border shadow-[0_2px_10px_rgba(0,0,0,0.02)] {{ $bg }}">
    
    <span class="relative flex h-2 w-2">
        @if(in_array($key, ['diajukan', 'diproses']))
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $dot }} opacity-75"></span>
        @endif
        
        <span class="relative inline-flex rounded-full h-2 w-2 {{ $dot }} shadow-[0_0_8px_currentColor]"></span>
    </span>
    
    {{ $label }}
</span>