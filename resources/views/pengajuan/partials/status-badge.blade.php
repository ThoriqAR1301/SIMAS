@props(['status', 'size' => 'sm'])

@php
    $warna = $status->warna();
    $label = $status->label();
    $padding = $size === 'lg' ? 'px-3 py-1.5 text-sm' : 'px-2.5 py-1 text-xs';
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1.5 $padding rounded-full font-medium
                bg-$warna-100 text-$warna-800
                dark:bg-$warna-900/30 dark:text-$warna-300"
]) }}>
    <span class="w-1.5 h-1.5 rounded-full bg-{{ $warna }}-500 animate-pulse"></span>
    {{ $label }}
</span>