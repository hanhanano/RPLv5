{{-- resources/views/statistik/rekapitulasi-output.blade.php --}}

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 mb-6">
    
    <div class="relative p-5 border-2 border-purple-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-purple-50 to-white group">
        
        {{-- Ikon & Tooltip Container --}}
        <div class="absolute top-3 right-3 text-purple-600 bg-purple-100 p-2 rounded-full cursor-help">
            {{-- Ikon Utama --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path d="M10 2a.75.75 0 0 1 .75.75v1.5h1.5a.75.75 0 0 1 0 1.5h-1.5v1.5a.75.75 0 0 1-1.5 0v-1.5h-1.5a.75.75 0 0 1 0-1.5h1.5v-1.5A.75.75 0 0 1 10 2Z" />
                <path d="M4.5 10a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5A.75.75 0 0 1 4.5 10Zm5.25-.75a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5ZM14.25 10a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75ZM4.5 14.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75Zm5.25-.75a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Zm5.25.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75Z" />
            </svg>

            {{-- Tooltip Content --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10 text-left">
                <p class="font-semibold mb-1">📊 Total Output</p>
                <p class="text-gray-300">Jumlah keseluruhan output (dokumen/laporan) yang ditargetkan rilis pada triwulan ini.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>

        <p class="text-sm text-gray-600 font-medium mb-1">Total Target Output</p>
        <p class="text-3xl font-bold text-purple-700" x-text="data.output.total">0</p>
        <p class="text-xs text-gray-500 mt-2">Target pada triwulan ini</p>
    </div>
    
    <div class="relative p-5 border-2 border-gray-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-orange-50 to-white group">
        
        {{-- Ikon & Tooltip Container --}}
        <div class="absolute top-3 right-3 text-orange-600 bg-orange-100 p-2 rounded-full cursor-help">
            {{-- Ikon Utama --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
            </svg>

            {{-- Tooltip Content --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10 text-left">
                <p class="font-semibold mb-1">⏳ Belum Selesai</p>
                <p class="text-gray-300">Output yang sudah dijadwalkan namun dokumennya belum diunggah atau belum dilaporkan selesai.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>

        <p class="text-sm text-gray-600 font-medium mb-1">Belum Selesai</p>
        <p class="text-3xl font-bold text-orange-600" x-text="data.output.belumBerlangsung">0</p>
        <p class="text-xs text-gray-500 mt-2">Output belum terealisasi</p>
    </div>

    <div class="relative p-5 border-2 border-gray-200 rounded-xl text-center hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-emerald-50 to-white group">
        
        {{-- Ikon & Tooltip Container --}}
        <div class="absolute top-3 right-3 text-emerald-600 bg-emerald-100 p-2 rounded-full cursor-help">
            {{-- Ikon Utama --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
            </svg>

            {{-- Tooltip Content --}}
            <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10 text-left">
                <p class="font-semibold mb-1">✅ Sudah Selesai</p>
                <p class="text-gray-300">Output yang telah berhasil diselesaikan dan dokumennya sudah diunggah ke dalam sistem.</p>
                <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
            </div>
        </div>

        <p class="text-sm text-gray-600 font-medium mb-1">Sudah Selesai</p>
        <p class="text-3xl font-bold text-emerald-600" x-text="data.output.sudahSelesai">0</p>
        <p class="text-xs text-gray-500 mt-2">Output berhasil dicapai</p>
    </div>

</div>

<div class="border border-gray-200 rounded-xl p-6 bg-gradient-to-br from-purple-50 to-white group relative shadow-sm">
    
    {{-- Tooltip Progress Bar --}}
    <div class="absolute top-4 right-4 text-purple-600 bg-purple-100 p-2 rounded-full cursor-help hover:bg-purple-200 transition-colors z-20">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
        </svg>
        <div class="absolute right-0 top-full mt-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-30 text-left">
            <p class="font-semibold mb-1 text-purple-300">📈 Tingkat Realisasi Output</p>
            <p class="text-gray-300 leading-relaxed">Persentase jumlah output yang sudah selesai dibandingkan dengan total target output yang direncanakan.</p>
            <div class="absolute -top-1 right-3 w-2 h-2 bg-gray-900 transform rotate-45"></div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-3 pr-10">
        <div class="flex items-center gap-2">
            <div class="p-1.5 bg-purple-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-purple-600">
                    <path d="M15.5 2A1.5 1.5 0 0 0 14 3.5v13a1.5 1.5 0 0 0 1.5 1.5h1a1.5 1.5 0 0 0 1.5-1.5v-13A1.5 1.5 0 0 0 16.5 2h-1ZM9.5 6A1.5 1.5 0 0 0 8 7.5v9A1.5 1.5 0 0 0 9.5 18h1a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 10.5 6h-1ZM3.5 10A1.5 1.5 0 0 0 2 11.5v5A1.5 1.5 0 0 0 3.5 18h1A1.5 1.5 0 0 0 6 16.5v-5A1.5 1.5 0 0 0 4.5 10h-1Z" />
                </svg>
            </div>
            <p class="text-sm font-bold text-gray-700">Tingkat Realisasi Output</p>
        </div>
        
        <p class="text-2xl font-extrabold text-purple-600">
            <span x-text="Math.round(Number(data.output.persentaseRealisasi || 0) * 100) / 100">0</span>%
        </p>
    </div>
    
    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
        <div class="h-full bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full transition-all duration-700 ease-out"
             :style="`width: ${data.output.persentaseRealisasi}%`">
        </div>
    </div>
    
    <p class="text-xs text-gray-500 mt-3 text-center">
        Persentase output yang diselesaikan terhadap target output triwulan ini
    </p>
</div>