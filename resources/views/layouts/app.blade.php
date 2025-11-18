<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 dark:bg-gray-300">
    
    {{-- Navbar --}}
    <x-navbar ></x-navbar>

    {{-- Main Content --}}
    <main class="min-h-screen pt-36">
        @yield('content')
    </main>

</body>
</html>
