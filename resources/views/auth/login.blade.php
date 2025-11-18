<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="/js/admin.js" defer></script>
</head>

<body class="h-screen flex flex-col overflow-hidden bg-white">
    
    <div class="bg-gradient-to-r from-blue-50 via-white to-blue-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
                <!-- Title & Subtitle -->
                <div class="text-center md:text-left">
                    <h2 class="text-lg md:text-xl font-bold bg-gradient-to-r from-blue-800 to-blue-600 bg-clip-text text-transparent">
                        Sistem Monitoring Capaian Kinerja
                    </h2>
                    <div class="flex items-center justify-center md:justify-start space-x-2 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-blue-600">
                            <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-gray-600">Badan Pusat Statistik Kota Bekasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-1 flex flex-col md:flex-row h-full">
        
        <div class="hidden md:flex md:w-1/2 flex-col items-center justify-center p-10">
            <h1 class="mb-4 text-2xl font-bold text-center md:text-4xl">Sistem Monitoring Capaian Kinerja</h1>
            <h1 class="mb-4 text-2xl font-bold text-center md:text-4xl">BPS Kota Bekasi</h1>
            
            <img src="img/mascot.png" alt="Maskot Mang Ntat" class="mt-6 max-h-[30vh] w-auto object-contain">
            
            <p class="mt-4 text-base tracking-widest text-center md:text-lg">
                Masukkan email dan kata sandi Anda untuk mengakses.
            </p>
        </div>

        <div class="p-4 text-white md:hidden bg-primary shrink-0">
            <a href="#" class="flex items-center justify-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg/960px-Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg.png"
                    class="h-8 me-3" alt="BPS Logo" />
                <span class="self-center text-lg font-medium tracking-normal sm:text-xl whitespace-nowrap text-gray-900 dark:text-white">
                    Tim SAKIP BPS Kota Bekasi
                </span>
            </a>
        </div>

        <div class="flex flex-col items-center justify-center w-full md:w-1/2 bg-secondary h-full p-6 md:p-10">
            
            <form method="POST" action="{{ route('login.post') }}"
                class="flex flex-col w-full max-w-md p-6 bg-white rounded-lg shadow-md md:p-10">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-bold md:text-lg">Username</label>
                    <input type="text" name="email" id="email" placeholder="Masukkan Email"
                        class="block w-full px-3 py-2 mt-1 tracking-widest text-center border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm md:placeholder:text-base lg:placeholder:text-lg">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-bold md:text-lg">Kata Sandi</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi"
                        class="block w-full px-3 py-2 mt-1 tracking-widest text-center border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm md:placeholder:text-base lg:placeholder:text-lg">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex justify-center mt-4">
                        <button type="submit"
                            class="w-full md:w-[200px] py-2 px-4 md:py-3 md:px-6 border border-transparent rounded-lg shadow-md 
                                   text-base md:text-xl font-bold text-white bg-blue-800 
                                   hover:bg-emerald-800 hover:text-white 
                                   focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-emerald-500 tracking-wider transition-colors duration-200">
                            Login
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

</body>
</html>