<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIMAS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-canvas-100 dark:bg-brand-950 text-ink-800 dark:text-ink-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <div class="absolute inset-0 bg-grid pointer-events-none"></div>
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-gold-400/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-brand-400/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 animate-in fade-in slide-up duration-500">
        {{ $slot }}
    </div>

</body>
</html>
