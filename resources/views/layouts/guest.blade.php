<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <div class="min-h-screen flex flex-col justify-between bg-white dark:bg-dark-bg px-6 py-8 sm:px-8 sm:py-12">
            <div class="flex-1 flex flex-col justify-center w-full max-w-md mx-auto">
                <div class="mb-12 text-center">
                    <a href="/" class="inline-block">
                        <x-application-logo class="w-16 h-16 sm:w-20 sm:h-20 fill-current text-primary-500" />
                    </a>
                </div>

                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
