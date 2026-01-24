@props(['hideNavigation' => false, 'transparentHeader' => false, 'title' => null, 'description' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="{{ auth()->user()?->preferences['theme'] ?? 'system' }}"
      class="{{ (auth()->user()?->preferences['theme'] ?? 'system') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' | ' . config('app.name', 'Snacc') : config('app.name', 'Snacc') }}</title>
        
        @if($description)
            <meta name="description" content="{{ $description }}">
            <meta property="og:description" content="{{ $description }}">
        @endif
        
        <meta property="og:title" content="{{ $title ?? config('app.name', 'Snacc') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-white dark:bg-dark-bg">
            <x-top-bar />

            @unless($hideNavigation)
                @include('layouts.navigation')
                <x-right-sidebar />
                <x-create-snacc-fab />
            @endunless

            <!-- Main Content Area -->
            <main class="{{ !$hideNavigation ? 'lg:pl-64 lg:pr-80' : '' }} {{ !$transparentHeader ? 'pt-14 lg:pt-0' : '' }} pb-16 lg:pb-0">
                {{ $slot }}
            </main>

            <!-- Create Snacc Modal -->
            <x-posts.create.modal />

            <!-- GIF Picker Modal -->
            <x-gif-picker.modal />

            <!-- Lightbox -->
            <x-lightbox />

            <!-- Global Toast -->
            <x-toast />
        </div>
    </body>
</html>
