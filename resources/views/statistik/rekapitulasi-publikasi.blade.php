{{-- resources/views/statistik/rekapitulasi-publikasi.blade.php --}}

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    
    <!-- Belum Berlangsung -->
    <div class="relative p-5 border-2 border-gray-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-blue-50 to-white group">
        <div class="absolute top-3 right-3 text-blue-500 bg-blue-100 p-2 rounded-full cursor-help">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492ZM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0Z"/>
                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319Z"/>
            </svg>
            
            {{-- Tooltip --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <p class="font-semibold mb-1">⏱️ Belum Berlangsung</p>
                <p class="text-gray-300">Publikasi yang belum memulai tahapan atau belum ada jadwal yang direncanakan.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">Belum Berlangsung</p>
        <p class="text-3xl font-bold text-blue-600" x-text="data.publikasi.belumBerlangsung">0</p>
        <p class="text-xs text-gray-500 mt-2">Publikasi belum dimulai</p>
    </div>

    <!-- Sedang Berlangsung -->
    <div class="relative p-5 border-2 border-gray-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-yellow-50 to-white group">
        <div class="absolute top-3 right-3 text-yellow-600 bg-yellow-100 p-2 rounded-full cursor-help">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8Zm7.75-4.25a.75.75 0 0 0-1.5 0V8c0 .414.336.75.75.75h3.25a.75.75 0 0 0 0-1.5h-2.5v-3.5Z" clip-rule="evenodd" />
            </svg>
            
            {{-- Tooltip --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <p class="font-semibold mb-1">⚙️ Sedang Berlangsung</p>
                <p class="text-gray-300">Publikasi yang sedang dalam proses pengerjaan dan belum menyelesaikan semua tahapan.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">Sedang Berlangsung</p>
        <p class="text-3xl font-bold text-yellow-600" x-text="data.publikasi.sedangBerlangsung">0</p>
        <p class="text-xs text-gray-500 mt-2">Publikasi dalam proses</p>
    </div>

    <!-- Sudah Selesai -->
    <div class="relative p-5 border-2 border-gray-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-emerald-50 to-white group">
        <div class="absolute top-3 right-3 text-emerald-600 bg-emerald-100 p-2 rounded-full cursor-help">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 4.79-1.649-1.833a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.15-.043l4.25-5.5Z" clip-rule="evenodd" />
            </svg>
            
            {{-- Tooltip --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <p class="font-semibold mb-1">✅ Publikasi Selesai</p>
                <p class="text-gray-300">Publikasi yang telah menyelesaikan semua tahapan sesuai rencana dan realisasi.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">Sudah Selesai</p>
        <p class="text-3xl font-bold text-emerald-600" x-text="data.publikasi.sudahSelesai">0</p>
        <p class="text-xs text-gray-500 mt-2">Publikasi telah selesai</p>
    </div>

    <!-- Total -->
    <div class="relative p-5 border-2 border-blue-300 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-blue-100 to-white group">
        <div class="absolute top-3 right-3 text-blue-700 bg-blue-200 p-2 rounded-full cursor-help">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8c0 .982-.472 1.854-1.202 2.402a2.995 2.995 0 0 1-.848 2.547 2.995 2.995 0 0 1-2.548.849A2.996 2.996 0 0 1 8 15a2.996 2.996 0 0 1-2.402-1.202 2.995 2.995 0 0 1-2.547-.848 2.995 2.995 0 0 1-.849-2.548A2.996 2.996 0 0 1 1 8c0-.982.472-1.854 1.202-2.402a2.995 2.995 0 0 1 .848-2.547 2.995 2.995 0 0 1 2.548-.849A2.995 2.995 0 0 1 8 1c.982 0 1.854.472 2.402 1.202a2.995 2.995 0 0 1 2.547.848c.695.695.978 1.645.849 2.548A2.996 2.996 0 0 1 15 8Zm-3.291-2.843a.75.75 0 0 1 .135 1.052l-4.25 5.5a.75.75 0 0 1-1.151.043l-2.25-2.5a.75.75 0 1 1 1.114-1.004l1.65 1.832 3.7-4.789a.75.75 0 0 1 1.052-.134Z" clip-rule="evenodd" />
            </svg>
            
            {{-- Tooltip --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <p class="font-semibold mb-1">📊 Total Publikasi</p>
                <p class="text-gray-300">Jumlah keseluruhan publikasi/laporan yang terdaftar dalam sistem untuk tahun berjalan.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">Total Publikasi</p>
        <p class="text-3xl font-bold text-blue-700" x-text="data.publikasi.total">0</p>
        <p class="text-xs text-gray-500 mt-2">Total Kumulatif</p>
    </div>
</div>