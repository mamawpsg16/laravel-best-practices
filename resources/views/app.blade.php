<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app" >
        <main-app :authuser="{{ $user }}"></main-app>
    </div>
    <script>
    </script>
</body>
</html>