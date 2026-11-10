@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-2 space-y-2']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start gap-2.5 p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-sm font-bold text-red-600 dark:text-red-400 animate-in fade-in slide-in-from-top-2 duration-300">
                
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                
                <span class="flex-1 leading-relaxed">
                    {{ $message }}
                </span>
            </li>
        @endforeach
    </ul>
@endif
