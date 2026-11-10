@props(['active'])

@php
$classes = ($active ?? false)
            ? 'group relative flex items-center w-full px-4 py-3.5 text-base font-black rounded-2xl bg-[#0B1120] text-white shadow-[0_8px_20px_-6px_rgba(11,17,32,0.4)] transition-all duration-300'
            : 'group relative flex items-center w-full px-4 py-3.5 text-base font-bold rounded-2xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    
    @if($active ?? false)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-[#F4B22E] rounded-r-full shadow-[0_0_12px_#F4B22E]"></span>
    @endif

    <div class="flex items-center gap-3 w-full">
        {{ $slot }}
    </div>
</a>
