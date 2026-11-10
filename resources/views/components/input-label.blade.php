@props(['value'])

<label {{ $attributes->merge([
    'class' => 'block text-[11px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2 transition-colors duration-300 group-focus-within:text-[#F4B22E]'
]) }}>
    {{ $value ?? $slot }}
    
    @if ($attributes->has('required') || $attributes->has('data-required'))
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>
