@props(['status'])

@if ($status)
    <div {{ $attributes->merge([
        'class' => 'flex items-start gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300'
    ]) }} role="alert">
        
        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <div class="flex-1 min-w-0 pt-1">
            <p class="text-sm font-bold text-emerald-800 leading-relaxed">
                {{ $status }}
            </p>
        </div>
    </div>
@endif
