<a {{ $attributes->merge([
    'class' => 'group flex items-center w-full px-4 py-2.5 text-start text-sm font-semibold 
                text-gray-700 dark:text-gray-300 
                rounded-xl 
                hover:bg-[#0B1120] hover:text-white 
                dark:hover:bg-[#F4B22E] dark:hover:text-[#0B1120] 
                focus:outline-none focus:bg-[#0B1120] focus:text-white dark:focus:bg-[#F4B22E] dark:focus:text-[#0B1120] 
                transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] 
                [&>svg]:transition-transform [&>svg]:duration-300 [&>svg]:group-hover:translate-x-1'
]) }}>
    {{ $slot }}
</a>