<div class="max-w-6xl mx-auto mt-6 p-6 bg-white shadow-lg border rounded-xl" 
     x-data="{ chartsLoaded: false }"
     x-init="setTimeout(() => chartsLoaded = true, 500)">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600">
                <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z" />
            </svg>
            Analisis Visual Kinerja
        </h2>

        <button onclick="window.print()" 
                class="flex items-center gap-2 px-2 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium print:hidden mb-3 ml-auto"
                >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.097 1.126.153A2.212 2.212 0 0 1 18 8.653v4.097A2.25 2.25 0 0 1 15.75 15h-.241l.305 1.984A1.75 1.75 0 0 1 14.084 19H5.915a1.75 1.75 0 0 1-1.73-2.016L4.492 15H4.25A2.25 2.25 0 0 1 2 12.75V8.653c0-1.082.775-2.034 1.874-2.198.374-.056.75-.107 1.127-.153L5 2.75ZM6.75 2.5a.25.25 0 0 0-.25.25v3.05c.59-.033 1.181-.058 1.775-.075V2.5h-1.525Zm5.025 0v3.225c.594.017 1.185.042 1.775.075V2.75a.25.25 0 0 0-.25-.25h-1.525ZM5.618 15.25l-.356 2.316a.25.25 0 0 0 .247.284h8.382a.25.25 0 0 0 .247-.284l-.356-2.316H5.618Z" clip-rule="evenodd" />
            </svg>
            Print Dashboard
        </button>
    </div>
    
    {{-- Skeleton Loading --}}
    <div x-show="!chartsLoaded" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="animate-pulse">
            <div class="bg-gray-200 h-8 w-3/4 rounded mb-4"></div>
            <div class="bg-gray-200 h-48 rounded-xl"></div>
        </div>
        <div class="animate-pulse">
            <div class="bg-gray-200 h-8 w-3/4 rounded mb-4"></div>
            <div class="bg-gray-200 h-48 rounded-xl"></div>
        </div>
        <div class="animate-pulse">
            <div class="bg-gray-200 h-8 w-3/4 rounded mb-4"></div>
            <div class="bg-gray-200 h-48 rounded-xl"></div>
        </div>
    </div>
    
    {{-- Actual Charts --}}
    <div x-show="chartsLoaded" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart 1 -->
        <div class="bg-white shadow-md rounded-xl p-6 border-2 border-gray-100 hover:shadow-lg transition-shadow relative">
            <button onclick="downloadChart('kinerjaChart', 'Status_Publikasi_Tahun.png')"
                    class="absolute top-4 right-4 p-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group"
                    title="Download Chart">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600">
                    <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                    <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                </svg>
                {{-- Tooltip untuk tombol download --}}
                <span class="absolute right-full mr-2 top-1/2 -translate-y-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                    Download PNG
                </span>
            </button>
        
            <h3 class="text-base font-semibold mb-1 text-gray-800">Status Publikasi Tahunan</h3>
            <p class="text-xs text-gray-500 mb-4">Distribusi status penyelesaian publikasi</p>
            <div class="flex justify-center">
                <canvas id="kinerjaChart" class="max-h-48"></canvas>
            </div>
            <div class="flex flex-wrap gap-3 mt-4 text-xs justify-center">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm" style="background-color: #2a9d90"></span>
                    <span class="text-gray-700">Selesai</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm" style="background-color: #f4a261"></span>
                    <span class="text-gray-700">Berlangsung</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm" style="background-color: #e76f51"></span>
                    <span class="text-gray-700">Belum</span>
                </div>
            </div>
        </div>

        <!-- Chart 2 -->
        <div class="bg-white shadow-md rounded-xl p-6 border-2 border-gray-100 hover:shadow-lg transition-shadow relative">
            
            {{-- Tombol Download --}}
            <button onclick="downloadChart('tahapanChart', 'Rencana_vs_Realisasi.png')"
                    class="absolute top-4 right-4 p-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group"
                    title="Download Chart">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600">
                    <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                    <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                </svg>
                {{-- Tooltip untuk tombol download --}}
                <span class="absolute right-full mr-2 top-1/2 -translate-y-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                    Download PNG
                </span>
            </button>

            <h3 class="text-base font-semibold mb-1 text-gray-800">Rencana vs Realisasi</h3>
            <p class="text-xs text-gray-500 mb-4">Perbandingan target dan pencapaian per triwulan</p>
            <div class="flex justify-center">
                <canvas id="tahapanChart" class="max-h-48"></canvas>
            </div>
            
            <div class="flex flex-wrap gap-3 mt-4 text-xs justify-center">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm bg-blue-900"></span>
                    <span class="text-gray-700">Rencana</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm bg-emerald-500"></span>
                    <span class="text-gray-700">Tepat Waktu</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm bg-orange-500"></span>
                    <span class="text-gray-700">Terlambat</span>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-2 mt-4 text-center">
                @foreach($dataTahapanSummary as $index => $data)
                    <div id="tw-card-{{ $index + 1 }}" class="bg-white border border-gray-200 rounded-xl p-2.5 shadow-sm flex flex-col justify-center h-full">
                        
                        <p class="text-[10px] font-bold text-gray-900 leading-none mb-1.5">
                            {{ $data['q'] }}
                        </p>
                        
                        <p id="tw-ratio-{{ $index + 1 }}" class="text-[10px] font-medium text-gray-500 leading-none mb-1">
                            {{ $data['ratio'] }}
                        </p>
                        
                        <p id="tw-percent-{{ $index + 1 }}" class="text-[10px] font-bold leading-none {{ $data['color'] }}">
                            {{ $data['percent_text'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Chart 3 -->
        <div class="bg-white shadow-md rounded-xl p-6 border-2 border-gray-100 hover:shadow-lg transition-shadow relative">
            
            {{-- Tombol Download --}}
            <button onclick="downloadChart('ringChart', 'Proporsi_Penyelesaian.png')"
                    class="absolute top-4 right-4 p-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group"
                    title="Download Chart">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600">
                    <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                    <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                </svg>
                <span class="absolute right-full mr-2 top-1/2 -translate-y-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                    Download PNG
                </span>
            </button>

            <h3 class="text-base font-semibold mb-1 text-gray-800">Proporsi Penyelesaian</h3>
            <p class="text-xs text-gray-500 mb-4">Perbandingan publikasi & tahapan selesai</p>
            <div class="flex justify-center">
                <canvas id="ringChart" class="max-h-48"></canvas>
            </div>
            
            <div class="grid grid-cols-3 gap-2 mt-4 text-xs">
                <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-200 text-center hover:bg-emerald-100 transition-colors">
                    <p class="text-emerald-700 text-2xl font-bold">
                        <span id="summary-pub-selesai">{{ $dataRingSummary['publikasiSelesai'] }}</span>
                    </p>
                    <p class="text-gray-600 font-medium mt-1">Publikasi Selesai</p>
                    <p class="text-gray-500 text-[10px]">
                        dari <span id="summary-pub-total">{{ $dataRingSummary['totalPublikasi'] }}</span> total
                    </p>
                </div>

                <div class="bg-blue-50 p-3 rounded-lg border border-blue-200 text-center hover:bg-blue-100 transition-colors">
                    <p class="text-blue-700 text-2xl font-bold">
                        <span id="summary-tahap-selesai">{{ $dataRingSummary['tahapanSelesai'] }}</span>
                    </p>
                    <p class="text-gray-600 font-medium mt-1">Tahapan Selesai</p>
                    <p class="text-gray-500 text-[10px]">
                        dari <span id="summary-tahap-total">{{ $dataRingSummary['totalTahapan'] }}</span> total
                    </p>
                </div>

                <div class="bg-purple-50 p-3 rounded-lg border border-purple-200 text-center hover:bg-purple-100 transition-colors">
                    <p class="text-purple-700 text-2xl font-bold">
                        <span id="summary-output-selesai">{{ $dataRingSummary['outputSelesai'] }}</span>
                    </p>
                    <p class="text-gray-600 font-medium mt-1">Output Selesai</p>
                    <p class="text-gray-500 text-[10px]">
                        dari <span id="summary-output-total">{{ $dataRingSummary['totalOutput'] }}</span> total
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Chart 4: Kinerja Per Tim --}}
    <div x-show="chartsLoaded"
         x-transition:enter="transition ease-out duration-300 delay-150"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="mt-8 bg-white shadow-md rounded-xl p-6 border-2 border-gray-100 relative">
        
        {{-- Tombol Download --}}
        <button onclick="downloadChart('timChart', 'Kinerja_Per_Tim.png')"
                class="absolute top-4 right-4 p-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group"
                title="Download Chart">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600">
                <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
            </svg>
            <span class="absolute right-full mr-2 top-1/2 -translate-y-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                Download PNG
            </span>
        </button>

        <h3 class="text-lg font-semibold mb-2 text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-purple-600">
                <path d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
            </svg>
            Kinerja Per Tim
        </h3>
        <p class="text-sm text-gray-500 mb-4">Perbandingan pencapaian antar tim</p>
        <div class="h-64">
            <canvas id="timChart"></canvas>
        </div>
        
        {{-- Info tambahan --}}
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
            @foreach($dataGrafikPerTim['labels'] as $index => $tim)
                @php
                    // Logika warna awal PHP (tetap biarkan untuk render pertama kali)
                    $plans = $dataGrafikPerTim['plans'][$index];
                    $finals = $dataGrafikPerTim['tepat_waktu'][$index] + $dataGrafikPerTim['terlambat'][$index];
                    $percent = $plans > 0 ? round(($finals / $plans) * 100) : 0;
                    
                    if ($percent >= 80) { $badgeColor = 'bg-green-100 text-green-700 border-green-300'; } 
                    elseif ($percent >= 60) { $badgeColor = 'bg-yellow-100 text-yellow-700 border-yellow-300'; } 
                    else { $badgeColor = 'bg-red-100 text-red-700 border-red-300'; }
                @endphp
                
                <div id="tim-card-{{ $index }}" class="p-2 border rounded-lg {{ $badgeColor }} hover:shadow transition-shadow">
                    
                    <p class="font-bold">Tim {{ $tim }}</p>
                    
                    <p id="tim-stats-{{ $index }}" class="text-[10px] mt-1">
                        {{ $finals }}/{{ $plans }} tahapan
                    </p>
                    
                    <p id="tim-percent-{{ $index }}" class="font-semibold">
                        {{ $percent }}%
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
@media print {
    nav, header, footer, .print\\:hidden {
        display: none !important;
    }
    
    @page {
        size: A4 landscape;
        margin: 1cm;
    }
    
    /* .page-break {
        page-break-after: always;
    } */
    
    body {
        font-size: 10pt;
    }
    
    canvas {
        max-width: 100% !important;
        height: auto !important;
    }
}
</style>
