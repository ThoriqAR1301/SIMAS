@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'p-2'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-56', 
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open" class="cursor-pointer">
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-300"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 -translate-y-3"
            class="absolute z-50 mt-3 {{ $width }} rounded-2xl 
                   shadow-[0_20px_50px_-12px_rgba(0,0,0,0.2)] 
                   border border-gray-100 dark:border-white/10 
                   bg-white/90 dark:bg-[#0B1120]/90 
                   backdrop-blur-xl 
                   {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
