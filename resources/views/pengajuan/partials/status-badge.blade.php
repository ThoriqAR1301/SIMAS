@props(['status'])

@php
    $warna = $status->warna();
    $label = $status->label();
@endphp

<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $warna }}-100 text-{{ $warna }}-800 dark:bg-{{ $warna }}-900/30 dark:text-{{ $warna }}-300">
    {{ $label }}
</span>