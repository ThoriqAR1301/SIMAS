@props([

    'title' => 'Belum Ada Data',
    'description' => 'Data Akan Muncul Di Sini Setelah Ditambahkan',
    'actionLabel' => null,
    'actionUrl' => null,
])

<div class="text-center py-16 px-4 animate-in">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-gold-100 to-gold-50 dark:from-brand-800 dark:to-brand-900 mb-5 shadow-card">
        <svg class="w-10 h-10 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <h3 class="text-lg font-bold text-brand-900 dark:text-ink-50">{{ $title }}</h3>
    <p class="mt-1.5 text-sm text-ink-500 dark:text-ink-400 max-w-sm mx-auto">{{ $description }}</p>
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn-primary mt-6 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ $actionLabel }}
        </a>
    @endif
</div>