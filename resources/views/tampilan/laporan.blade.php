<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard SIMONICA</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        window.userRole = '{{ auth()->user()->role ?? "guest" }}';
        window.userTeam = '{{ auth()->user()->team ?? "" }}';
    </script>
</head>
<body>
    <header class="fixed top-0 left-0 right-0 w-full bg-[#002b6b] z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center space-x-3">
            <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" class="h-8">
            <span class="text-white font-semibold">BADAN PUSAT STATISTIK</span>
        </div>
    </header>

    <div>
        <x-navbar></x-navbar>
    </div>

    <main class="pt-24 px-4 max-w-7xl mx-auto">
        
        <h1 class="text-2xl font-bold text-blue-900 mb-6">Halaman Laporan</h1>

        @include('tampilan.daftarpublikasi')
        
    </main>
</body>
</html>