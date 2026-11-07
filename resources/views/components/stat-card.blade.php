@props([
    'label' => '',
    'value' => 0,
    'icon' => 'chart-bar',
    'trend' => null,
    'color' => 'brand', // brand | gold | emerald | red | blue
])

@php
    $colorMap = [
        'brand' => ['text-brand-600 dark:text-brand-400', 'bg-brand-100 dark:bg-brand-900/40'],
        'gold' => ['text-gold-600 dark:text-gold-400',  'bg-gold-100 dark:bg-gold-900/30'],
        'emerald' => ['text-emerald-600 dark:text-emerald-400', 'bg-emerald-100 dark:bg-emerald-900/30'],
        'red' => ['text-red-600 dark:text-red-400', 'bg-red-100 dark:bg-red-900/30'],
        'blue' => ['text-blue-600 dark:text-blue-400', 'bg-blue-100 dark:bg-blue-900/30'],
    ];
    [$textColor, $bgColor] = $colorMap[$color] ?? $colorMap['brand'];
@endphp

<div class="stat-card animate-in">
    <div class="flex items-start justify-between mb-4">
        <div class="w-12 h-12 rounded-2xl {{ $bgColor }} flex items-center justify-center">
            <svg class="w-6 h-6 {{ $textColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        @if($trend)
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ str_starts_with($trend, '+') ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                {{ $trend }}
            </span>
        @endif
    </div>
    <div>
        <div class="text-3xl font-bold text-brand-900 dark:text-ink-50 tracking-tight">
            {{ is_numeric($value) ? number_format($value) : $value }}
        </div>
        <div class="text-xs font-medium text-ink-500 dark:text-ink-400 mt-1 uppercase tracking-wide">
            {{ $label }}
        </div>
    </div>
</div>