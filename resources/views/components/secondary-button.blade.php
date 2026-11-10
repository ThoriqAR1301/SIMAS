<button {{ $attributes->merge([
    'type' => 'button', 
    'class' => 'relative inline-flex items-center justify-center gap-2 px-6 py-3.5 
                bg-white dark:bg-[#151D3F] 
                text-gray-700 dark:text-gray-200 
                font-black text-sm uppercase tracking-wider rounded-xl 
                border border-gray-200 dark:border-white/10 
                shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05),inset_0_1px_0_rgba(255,255,255,1)] 
                dark:shadow-[0_2px_8px_-2px_rgba(0,0,0,0.2),inset_0_1px_0_rgba(255,255,255,0.05)]
                hover:bg-gray-50 dark:hover:bg-[#1E2A5A] 
                hover:shadow-[0_10px_25px_-8px_rgba(0,0,0,0.1)] 
                hover:-translate-y-0.5 
                hover:border-gray-300 dark:hover:border-white/20
                active:scale-[0.98] 
                focus:outline-none focus:ring-4 focus:ring-[#F4B22E]/20 focus:border-[#F4B22E] 
                disabled:opacity-50 disabled:cursor-not-allowed 
                transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] group'
]) }}>
    
    <span class="relative z-10 flex items-center gap-2">
        {{ $slot }}
    </span>
</button>
