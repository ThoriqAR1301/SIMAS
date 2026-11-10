@props(['active'])

@php
$classes = ($active ?? false)
            ? 'relative inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-[#0B1120] text-sm font-black tracking-wide text-white shadow-[0_8px_20px_-6px_rgba(11,17,32,0.4)] transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]'
            : 'relative inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-bold tracking-wide text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-white/5 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    
    @if($active ?? false)
        <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-6 h-[2px] rounded-full bg-[#F4B22E] shadow-[0_0_8px_#F4B22E]"></span>
    @endif
</a>
