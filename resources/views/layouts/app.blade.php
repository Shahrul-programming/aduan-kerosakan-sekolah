<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-papm6QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1QK1Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- allow pages to push additional styles --}}
    @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content', $slot ?? '')
            </main>
        {{-- allow pages to push scripts (lightbox, inline page scripts) --}}
        @stack('scripts')
        </div>

        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
            <!-- Toasts will be dynamically added here -->
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden items-center justify-center">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <span class="text-gray-700">Memproses...</span>
            </div>
        </div>
    </body>
</html>
