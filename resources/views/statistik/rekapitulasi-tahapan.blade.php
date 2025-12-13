{{-- resources/views/statistik/rekapitulasi-tahapan.blade.php --}}

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    
    <!-- Sedang Berlangsung -->
    <div class="relative p-5 border-2 border-gray-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-yellow-50 to-white group">
        <div class="absolute top-3 right-3 text-yellow-600 bg-yellow-100 p-2 rounded-full cursor-help">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8Zm7.75-4.25a.75.75 0 0 0-1.5 0V8c0 .414.336.75.75.75h3.25a.75.75 0 0 0 0-1.5h-2.5v-3.5Z" clip-rule="evenodd" />
            </svg>
            
            {{-- Tooltip --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <p class="font-semibold mb-1">⚙️ Tahapan Berlangsung</p>
                <p class="text-gray-300">Tahapan yang sudah dimulai tetapi belum selesai direalisasikan.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">Sedang Berlangsung</p>
        <p class="text-3xl font-bold text-yellow-600" x-text="data.tahapan.sedangBerlangsung">0</p>
        <p class="text-xs text-gray-500 mt-2">Tahapan dalam proses</p>
    </div>
    
    <!-- Sudah Selesai (Tepat Waktu) -->
    <div class="relative p-5 border-2 border-gray-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-emerald-50 to-white group">
        <div class="absolute top-3 right-3 text-emerald-600 bg-emerald-100 p-2 rounded-full cursor-help">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 4.79-1.649-1.833a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.15-.043l4.25-5.5Z" clip-rule="evenodd" />
            </svg>
            
            {{-- Tooltip --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <p class="font-semibold mb-1">✅ Tepat Waktu</p>
                <p class="text-gray-300">Tahapan yang diselesaikan pada atau sebelum triwulan yang direncanakan.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">Tepat Waktu</p>
        <p class="text-3xl font-bold text-emerald-600" x-text="data.tahapan.sudahSelesai">0</p>
        <p class="text-xs text-gray-500 mt-2">Selesai sesuai rencana</p>
    </div>
    
    <!-- Tertunda/Terlambat -->
    <div class="relative p-5 border-2 border-gray-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-orange-50 to-white group">
        <div class="absolute top-3 right-3 text-orange-600 bg-orange-100 p-2 rounded-full cursor-help">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
            </svg>
            
            {{-- Tooltip --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <p class="font-semibold mb-1">⚠️ Terlambat</p>
                <p class="text-gray-300">Tahapan yang diselesaikan melewati triwulan yang direncanakan (lintas triwulan).</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">Terlambat</p>
        <p class="text-3xl font-bold text-orange-600" x-text="data.tahapan.tertunda">0</p>
        <p class="text-xs text-gray-500 mt-2">Melewati triwulan rencana</p>
    </div>

    <!-- Total Tahapan -->
    <div class="relative p-5 border-2 border-blue-300 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-blue-100 to-white group">
        <div class="absolute top-3 right-3 text-blue-700 bg-blue-200 p-2 rounded-full cursor-help">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8c0 .982-.472 1.854-1.202 2.402a2.995 2.995 0 0 1-.848 2.547 2.995 2.995 0 0 1-2.548.849A2.996 2.996 0 0 1 8 15a2.996 2.996 0 0 1-2.402-1.202 2.995 2.995 0 0 1-2.547-.848 2.995 2.995 0 0 1-.849-2.548A2.996 2.996 0 0 1 1 8c0-.982.472-1.854 1.202-2.402a2.995 2.995 0 0 1 .848-2.547 2.995 2.995 0 0 1 2.548-.849A2.995 2.995 0 0 1 8 1c.982 0 1.854.472 2.402 1.202a2.995 2.995 0 0 1 2.547.848c.695.695.978 1.645.849 2.548A2.996 2.996 0 0 1 15 8Zm-3.291-2.843a.75.75 0 0 1 .135 1.052l-4.25 5.5a.75.75 0 0 1-1.151.043l-2.25-2.5a.75.75 0 1 1 1.114-1.004l1.65 1.832 3.7-4.789a.75.75 0 0 1 1.052-.134Z" clip-rule="evenodd" />
            </svg>
            
            {{-- Tooltip --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <p class="font-semibold mb-1">📊 Total Tahapan</p>
                <p class="text-gray-300">Jumlah keseluruhan tahapan yang direncanakan pada triwulan yang dipilih.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-medium mb-1">Total Tahapan</p>
        <p class="text-3xl font-bold text-blue-700" x-text="data.tahapan.total">0</p>
        <p class="text-xs text-gray-500 mt-2">Total per triwulan</p>
    </div>
</div>

<!-- Progress Bar Persentase -->
<div class="border border-gray-200 rounded-xl p-6 bg-gradient-to-br from-green-50 to-white group relative shadow-sm">
    
    {{-- Tooltip untuk progress bar --}}
    <div class="absolute top-4 right-4 text-green-600 bg-green-100 p-2 rounded-full cursor-help hover:bg-green-200 transition-colors z-20">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
        </svg>
        
        <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-30">
            <p class="font-semibold mb-1 text-green-300">📈 Tingkat Realisasi</p>
            <p class="text-gray-300 leading-relaxed">Persentase tahapan yang sudah diselesaikan dibandingkan dengan total tahapan yang direncanakan.</p>
            <div class="absolute -top-1 right-3 w-2 h-2 bg-gray-900 transform rotate-45"></div>
        </div>
    </div>
    
    {{-- Header: Judul & Angka Besar --}}
    <div class="flex items-center justify-between mb-3 pr-10"> {{-- pr-10 agar tidak nabrak tombol info --}}
        <div class="flex items-center gap-2">
            <div class="p-1.5 bg-green-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-green-600">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-sm font-bold text-gray-700">Tingkat Realisasi Tahapan</p>
        </div>
        <p class="text-2xl font-extrabold text-green-600">
            <span x-text="data.tahapan.persentaseRealisasi">0</span>%
        </p>
    </div>
    
    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
        <div class="h-full bg-gradient-to-r from-green-500 to-emerald-600 rounded-full transition-all duration-700 ease-out"
             :style="`width: ${data.tahapan.persentaseRealisasi}%`">
        </div>
    </div>
    
    <p class="text-xs text-gray-500 mt-3 text-center">
        Persentase tahapan yang diselesaikan sesuai jadwal rencana
    </p>
</div>