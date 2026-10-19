@props(['status', 'size' => 'sm'])

@php
    $variants = [
        'draft' => ['bg-ink-100 text-ink-700 dark:bg-ink-800/60 dark:text-ink-300', 'bg-ink-400', 'Draft'],
        'diajukan' => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300', 'bg-blue-500', 'Menunggu Verifikasi'],
        'diproses' => ['bg-gold-100 text-gold-800 dark:bg-gold-900/30 dark:text-gold-300', 'bg-gold-500', 'Menunggu Persetujuan'],
        'disetujui' => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300', 'bg-emerald-500', 'Disetujui'],
        'ditolak' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300', 'bg-red-500', 'Ditolak'],
        'selesai' => ['bg-brand-100 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300', 'bg-brand-500', 'Selesai'],
    ];
    $key = $status->value ?? $status;
    [$bg, $dot, $label] = $variants[$key] ?? $variants['draft'];
    $padding = $size === 'lg' ? 'px-3 py-1.5 text-xs' : 'px-2.5 py-1 text-[10px]';
@endphp

<span class="inline-flex items-center gap-1.5 {{ $padding }} rounded-full font-bold uppercase tracking-wider {{ $bg }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }} {{ in_array($key, ['diajukan', 'diproses']) ? 'animate-pulse-soft' : '' }}"></span>
    {{ $label }}
</span>