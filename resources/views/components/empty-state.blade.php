@props([
    'icon' => 'document-text',
    'title' => 'Belum ada data',
    'description' => '',
    'actionLabel' => null,
    'actionUrl' => null,
])

<div class="text-center py-16 px-4">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
    @endif
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
            {{ $actionLabel }}
        </a>
    @endif
</div>