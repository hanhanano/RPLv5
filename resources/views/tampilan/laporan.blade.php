<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan - SIMONICA</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50">
    {{-- Header / Navbar --}}
    <header class="fixed top-0 left-0 right-0 w-full bg-[#002b6b] z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center space-x-3">
            <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" class="h-8">
            <span class="text-white font-semibold">BADAN PUSAT STATISTIK</span>
        </div>
    </header>

    <div>
        <x-navbar></x-navbar>
    </div>

    <main class="pt-12 px-4 max-w-7xl mx-auto mt-8 md:mt-0">
        
        <h1 class="text-2xl font-bold text-blue-900 mb-6">Halaman Laporan</h1>
        <div class="bg-white border shadow-sm rounded-lg min-h-[400px] flex flex-col items-center justify-center p-8 text-center">
            <div class="bg-blue-50 p-6 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Fitur Laporan Sedang Dalam Pengembangan</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-6">
                Kami sedang menyiapkan format laporan capaian kinerja yang lebih lengkap. Mohon ditunggu pembaruan selanjutnya!
            </p>
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-900 rounded-lg hover:bg-blue-800">
                Kembali ke Dashboard
            </a>
        </div>

    </main>
</body>
</html>