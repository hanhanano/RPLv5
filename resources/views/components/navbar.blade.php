<div class="fixed top-0 left-0 right-0 w-full z-50">
    <!-- Main Navbar -->
    <nav class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 shadow-lg" x-data="{isOpen: false}">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg/960px-Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg.png" 
                             alt="Logo BPS" 
                             class="h-10 w-10 drop-shadow-lg">
                    </div>
                    
                    <!-- Brand Text -->
                    <div class="hidden lg:block">
                        <h1 class="text-xl font-bold text-white tracking-tight">SIMONICA</h1>
                        <p class="text-xs text-blue-200">BPS Kota Bekasi</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="/dashboard" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->is('/dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
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

                    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                        <a href="{{ route('target.index') }}" 
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->is('target.index') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            Target Kinerja
                        </a>
                    @endif
                </div>

                <!-- User Menu Desktop -->
                <div class="hidden md:flex items-center space-x-3">
                    <!-- User Dropdown -->
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

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
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
                                    
                                    @if(auth()->user()->role !== 'admin')
                                        <a href="{{ route('password.change') }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                            </svg>
                                            Ubah Password
                                        </a>
                                    @endif
                                    
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

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button @click="isOpen = !isOpen" 
                            class="inline-flex items-center justify-center p-2 rounded-lg text-white hover:bg-white/10 transition-colors">
                        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="md:hidden bg-blue-800 border-t border-blue-700">
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
            </div>
            
            <!-- Mobile User Menu -->
            <div class="border-t border-blue-700 pt-4 pb-3">
                @auth
                    <div class="px-4 pb-3">
                        <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                        <div class="text-sm text-blue-200">{{ auth()->user()->email }}</div>
                        @if(auth()->user()->team)
                            <div class="text-xs text-emerald-300 mt-1">Tim {{ auth()->user()->team }}</div>
                        @endif
                    </div>
                    
                    <div class="space-y-1 px-2">
                        @if(auth()->user()->role !== 'admin')
                            <a href="{{ route('password.change') }}" 
                               class="flex items-center px-3 py-2 rounded-lg text-base font-medium text-blue-100 hover:bg-white/10 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                </svg>
                                Ubah Password
                            </a>
                        @endif
                        
                        @if(auth()->user()->role === 'admin')
                            <a href="/admin" 
                               class="flex items-center px-3 py-2 rounded-lg text-base font-medium text-blue-100 hover:bg-white/10 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                </svg>
                                Halaman Admin
                            </a>
                        @endif
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-3 py-2 rounded-lg text-base font-medium text-red-300 hover:bg-red-500/20 hover:text-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Masuk
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Header/Subtitle Bar -->
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
</div>

<!-- Spacer for fixed navbar -->
<div class="h-32"></div>