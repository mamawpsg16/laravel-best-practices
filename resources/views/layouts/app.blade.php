<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title','Dummy Title')</title>
        <!-- {{--$config('app.name', 'Laravel') --}} -->
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- <script src="https://kit.fontawesome.com/e768be689c.js" crossorigin="anonymous" defer></script> -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div id="app"></div>
        @include('layouts.nav')
        <div class="container mx-auto p-4 w-3/4 mt-5 bg-gray-100 dark:bg-gray-900">
            <main>
                @yield('content')
            </main>
        </div>
        <script src="https://cdn.tailwindcss.com"></script>
        @vite('resources/js/app.js')
        @yield('scripts')
        @stack('scripts')
    </body>
</html>
