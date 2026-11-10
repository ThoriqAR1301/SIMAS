@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
    'class' => 'w-full px-4 py-3.5 
                bg-gray-50 dark:bg-[#0B1120]/50 
                border-2 border-transparent 
                rounded-xl 
                text-sm font-semibold text-gray-900 dark:text-white 
                placeholder-gray-400 dark:placeholder-gray-500 
                transition-all duration-300 
                focus:bg-white dark:focus:bg-[#0B1120] 
                focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/20 
                disabled:opacity-60 disabled:cursor-not-allowed 
                outline-none'
]) }}>