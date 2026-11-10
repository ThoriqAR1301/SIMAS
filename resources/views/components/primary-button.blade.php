<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'relative inline-flex items-center justify-center gap-2 px-6 py-3.5 
                bg-[#0B1120] text-white font-black text-sm uppercase tracking-wider rounded-xl 
                shadow-[0_10px_20px_-8px_rgba(11,17,32,0.5),inset_0_1px_0_rgba(255,255,255,0.1)] 
                hover:bg-[#1E2A5A] hover:shadow-[0_15px_30px_-10px_rgba(11,17,32,0.7)] 
                hover:-translate-y-0.5 
                active:scale-95 
                focus:outline-none focus:ring-4 focus:ring-[#F4B22E]/20 
                border border-white/10 
                transition-all duration-300 group overflow-hidden'
]) }}>
    
    <span class="relative z-10 flex items-center gap-2">
        {{ $slot }}
    </span>

    <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
</button>
