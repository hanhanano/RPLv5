<div class="fixed top-0 left-0 right-0 w-full z-200">
    <nav class="relative z-50 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 shadow-lg" x-data="{isOpen: false}">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg/960px-Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg.png" 
                             alt="Logo BPS" 
                             class="h-8 w-8 drop-shadow-lg">
                    </div>
                    
                    <div class="hidden lg:block">
                        <h1 class="text-lg font-bold text-white tracking-tight">SIMONICA</h1>
                        <p class="text-xs text-blue-200">BPS Kota Bekasi</p>
                    </div>
                </div>

                {{-- MENU UTAMA DESKTOP --}}
                <div class="hidden md:flex items-center space-x-2">
                    <a href="/dashboard" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->is('dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="/laporan" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->is('laporan') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                        Laporan
                    </a>

                    <a href="{{ route('capaian.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('capaian.index') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="M15.5 2A1.5 1.5 0 0014 3.5v8a1.5 1.5 0 001.5 1.5h1.5A1.5 1.5 0 0018.5 11.5v-8A1.5 1.5 0 0017 2h-1.5zM9 9a1.5 1.5 0 00-1.5 1.5v2A1.5 1.5 0 009 14h1.5a1.5 1.5 0 001.5-1.5v-2A1.5 1.5 0 0010.5 9H9zM2 10.5a1.5 1.5 0 011.5-1.5h1.5A1.5 1.5 0 016.5 10.5v2a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 012 12.5v-2z" />
                        </svg>
                        Capaian Kinerja
                    </a>
                    
                </div>

                <div class="hidden md:flex items-center space-x-3">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden lg:block">
                                @auth
                                    {{ Str::limit(auth()->user()->name, 15) }}
                                @else
                                    Menu
                                @endauth
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 origin-top-right rounded-lg bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-1">
                                @auth
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                        @if(auth()->user()->team)
                                            <p class="text-xs text-emerald-600 mt-1">Tim {{ auth()->user()->team }}</p>
                                        @endif
                                    </div>
                                    
                                    @if(in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                                        <a href="{{ route('target.index') }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors {{ request()->is('target-kinerja') ? 'bg-blue-50 text-blue-700' : '' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                            </svg>
                                            Target Laporan
                                        </a>
                                    @endif

                                    <a href="{{ route('password.change') }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                        </svg>
                                        Ubah Password
                                    </a>
                                    
                                    @if(auth()->user()->role === 'admin')
                                        <a href="/admin" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                            </svg>
                                            Halaman Admin
                                        </a>
                                    @endif
                                    
                                    <div class="border-t border-gray-100">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                                                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                                </svg>
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <a href="/login" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Masuk
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL HAMBURGER (MOBILE) --}}
                <div class="-mr-2 flex md:hidden">
                    <button @click="isOpen = !isOpen" type="button" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-blue-100 hover:bg-blue-800 hover:text-white focus:outline-none transition-colors" 
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!isOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="isOpen" x-cloak class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- MENU MOBILE (Perbaikan Scroll & Item Hilang) --}}
                <div x-show="isOpen" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"

                    class="md:hidden absolute left-0 w-full top-full bg-blue-800 border-t border-blue-700 overflow-y-auto max-h-[85vh] shadow-xl z-[300]">
                    
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="/" 
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-base font-medium
                                {{ request()->is('/') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                            </svg>
                            Dashboard
                        </a>

                        <a href="/laporan" 
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-base font-medium
                                {{ request()->is('laporan') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                            Laporan
                        </a>

                        <a href="{{ route('capaian.index') }}" 
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-base font-medium
                                {{ request()->routeIs('capaian.index') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path d="M15.5 2A1.5 1.5 0 0014 3.5v8a1.5 1.5 0 001.5 1.5h1.5A1.5 1.5 0 0018.5 11.5v-8A1.5 1.5 0 0017 2h-1.5zM9 9a1.5 1.5 0 00-1.5 1.5v2A1.5 1.5 0 009 14h1.5a1.5 1.5 0 001.5-1.5v-2A1.5 1.5 0 0010.5 9H9zM2 10.5a1.5 1.5 0 011.5-1.5h1.5A1.5 1.5 0 016.5 10.5v2a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 012 12.5v-2z" />
                            </svg>
                            Capaian Kinerja
                        </a>
                    </div>
                    
                    <div class="border-t border-blue-700 pt-4 pb-3">
                        @auth
                            <div class="px-4 pb-3">
                                <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                                <div class="text-sm text-blue-200">{{ auth()->user()->email }}</div>
                            </div>
                            
                            {{-- Target Kinerja (Hanya Ketua Tim/Admin) --}}
                            @if(in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                                <div class="px-2 mb-1">
                                    <a href="{{ route('target.index') }}" 
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-base font-medium text-blue-100 hover:bg-white/10 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                        </svg>
                                        Target Kinerja
                                    </a>
                                </div>
                            @endif

                            {{-- PERBAIKAN 2: Tambahkan Menu 'Ubah Password' --}}
                            <div class="px-2 mb-1">
                                <a href="{{ route('password.change') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-base font-medium text-blue-100 hover:bg-white/10 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                    </svg>
                                    Ubah Password
                                </a>
                            </div>

                            {{-- PERBAIKAN 3: Tambahkan Menu 'Halaman Admin' jika user admin --}}
                            @if(auth()->user()->role === 'admin')
                                <div class="px-2 mb-1">
                                    <a href="/admin" 
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-base font-medium text-blue-100 hover:bg-white/10 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                        </svg>
                                        Halaman Admin
                                    </a>
                                </div>
                            @endif

                            <div class="space-y-1 px-2 mt-2 pt-2 border-t border-blue-700/50">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center w-full gap-2 px-3 py-2 rounded-lg text-base font-medium text-red-300 hover:bg-red-500/20 hover:text-red-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="px-2">
                                <a href="/login" 
                                class="flex items-center px-3 py-2 rounded-lg text-base font-medium text-blue-100 hover:bg-white/10 hover:text-white">
                                    Masuk
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        
    </nav>

    <div class="bg-gradient-to-r from-blue-50 via-white to-blue-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-1">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
                <div class="text-center md:text-left">
                    <h2 class="text-base font-bold bg-gradient-to-r from-blue-800 to-blue-600 bg-clip-text text-transparent leading-tight">
                        Sistem Monitoring Capaian Kinerja
                    </h2>
                    <div class="flex items-center justify-center md:justify-start space-x-1 md:border-l md:border-gray-300 md:pl-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-blue-600">
                            <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-xs text-gray-600">Badan Pusat Statistik Kota Bekasi</p>
                    </div>
                </div>
                {{-- Filter Tahun --}}
                <div class="mt-1 md:mt-0">
                    <form action="{{ route('change.year') }}" method="POST" id="yearForm">
                        @csrf
                        <div class="flex items-center gap-2">
                            <label for="year" class="text-sm font-medium text-gray-600">Tahun:</label>
                            <select name="year" id="year" 
                                    onchange="document.getElementById('yearForm').submit()" 
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-xs py-0.5 pl-2 pr-7 h-7">
                                
                                @php 
                                    $currentYear = now()->year;
                                    $selectedYear = session('selected_year', $currentYear);
                                    $startYear = 2024; 
                                @endphp
                                
                                {{-- Loop dari Tahun Depan lalu mundur sampai Tahun 2024 --}}
                                @for ($i = $currentYear + 1; $i >= $startYear; $i--)
                                    <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor

                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="h-20"></div>