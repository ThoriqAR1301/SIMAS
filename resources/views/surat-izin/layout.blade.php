<x-app-layout :title="$__env->yieldContent('title', 'Surat Izin')">
    <div class="min-h-screen bg-slate-50 relative overflow-hidden font-sans">
        
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-[#F4B22E]/10 rounded-full blur-[120px] pointer-events-none transform translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-[120px] pointer-events-none transform -translate-x-1/3 translate-y-1/3"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            @yield('content')
        </div>
        
    </div>
</x-app-layout>