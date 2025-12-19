<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capaian Kinerja</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <style>
        .overflow-auto::-webkit-scrollbar { width: 10px; height: 10px; }
        .overflow-auto::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 5px; }
        
        .sticky-col-1, .sticky-col-2 { position: static; } 
        thead .sticky-col-1, thead .sticky-col-2 { 
            z-index: 60 !important; 
            position: sticky !important; 
            top: 0 !important; 
            height: auto;  
        }
        .sticky-col-shadow { border-right: none; box-shadow: none; }

        @media (min-width: 768px) {
            .sticky-col-1 { position: sticky; left: 0; z-index: 30; } 
            .sticky-col-2 { position: sticky; left: 280px; z-index: 30; }             
            .sticky-col-shadow { border-right: 2px solid #e5e7eb; box-shadow: 4px 0 4px -2px rgba(0,0,0,0.05); }
        }
    </style>
</head>
<body class="bg-gray-50 font-inter">
    
    <div><x-navbar></x-navbar></div>

    <main class="py-8 mt-12 md:mt-0" x-data="{ activeTab: 'indikator' }">
        <div class="max-w-[98%] mx-auto px-4 space-y-6">
            
            @php
                // Hitung IKU dari indikator normal + spesial
                $totalScoreIKU = 0;
                $countActiveIKU = 0;
                
                // Dari indikator normal
                foreach($laporanKinerjaSasaran as $item) {
                    $scoreOutputTW4 = $item['capaian']['output']['thn'][4] ?? 0;
                    if ($scoreOutputTW4 > 0) {
                        $totalScoreIKU += $scoreOutputTW4;
                        $countActiveIKU++;
                    }
                }
                
                // Dari indikator spesial
                foreach($laporanKinerjaSpesialSasaran as $item) {
                    $scoreOutputTW4 = $item['capaian']['output']['thn'][4] ?? 0;
                    if ($scoreOutputTW4 > 0) {
                        $totalScoreIKU += $scoreOutputTW4;
                        $countActiveIKU++;
                    }
                }
                
                $nilaiIKU = $countActiveIKU > 0 ? ($totalScoreIKU / $countActiveIKU) : 0;
                
                // Tentukan Predikat
                if ($nilaiIKU > 90) { $predikatIKU = "AA/Sangat Memuaskan"; }
                elseif ($nilaiIKU > 80) { $predikatIKU = "A/Memuaskan"; }
                elseif ($nilaiIKU > 70) { $predikatIKU = "BB/Sangat Baik"; }
                elseif ($nilaiIKU > 60) { $predikatIKU = "B/Baik"; }
                elseif ($nilaiIKU > 50) { $predikatIKU = "CC/Cukup (Memadai)"; }
                elseif ($nilaiIKU > 30) { $predikatIKU = "C/Kurang"; }
                else { $predikatIKU = "D/Sangat Kurang"; }

                // Tentukan Warna
                if ($nilaiIKU > 90) { 
                    $ikuColor = 'text-blue-700'; $ikuBg = 'bg-blue-100'; $ikuBorder = 'border-blue-400';
                } elseif ($nilaiIKU > 80) { 
                    $ikuColor = 'text-cyan-700'; $ikuBg = 'bg-cyan-100'; $ikuBorder = 'border-cyan-400';
                } elseif ($nilaiIKU > 70) { 
                    $ikuColor = 'text-green-700'; $ikuBg = 'bg-green-100'; $ikuBorder = 'border-green-400';
                } elseif ($nilaiIKU > 60) { 
                    $ikuColor = 'text-lime-700'; $ikuBg = 'bg-lime-100'; $ikuBorder = 'border-lime-400';
                } elseif ($nilaiIKU > 50) { 
                    $ikuColor = 'text-yellow-700'; $ikuBg = 'bg-yellow-100'; $ikuBorder = 'border-yellow-400';
                } elseif ($nilaiIKU > 30) { 
                    $ikuColor = 'text-amber-700'; $ikuBg = 'bg-amber-100'; $ikuBorder = 'border-amber-400';
                } else { 
                    $ikuColor = 'text-red-700'; $ikuBg = 'bg-red-100'; $ikuBorder = 'border-red-400';
                }
            @endphp

            {{-- CARD IKU --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden p-4 md:p-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-lg md:text-2xl font-bold text-gray-900">Capaian Kinerja Utama (IKU)</h2>
                        <p class="text-xs md:text-base text-gray-600 mt-1">Rata-rata capaian kinerja output seluruh sasaran/laporan aktif terhadap target tahunan.</p>
                    </div>
                    
                    <div class="flex items-center gap-2 md:gap-4 {{ $ikuBg }} px-3 py-2 md:px-6 md:py-4 rounded-xl border {{ $ikuBorder }} w-full md:w-auto justify-center md:justify-start">
                        <div class="text-right">
                            <p class="text-[10px] md:text-xs font-bold uppercase tracking-wider text-gray-500 mb-0 md:mb-1">Total Capaian</p>
                            <p class="text-xl md:text-3xl font-extrabold {{ $ikuColor }} leading-none">{{ number_format($nilaiIKU, 2) }}%</p>
                            <p class="text-[10px] md:text-sm font-bold {{ $ikuColor }} mt-0.5 md:mt-1">{{ $predikatIKU }}</p>
                        </div>
                        <div class="p-1.5 md:p-3 bg-white rounded-full shadow-sm ring-1 ring-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 md:w-10 md:h-10 {{ $ikuColor }}">
                                <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M12.75 3a.75.75 0 0 1 .5-.218 8.25 8.25 0 0 1 8.287 8.287.75.75 0 0 1-.218.5H12.75V3Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border shadow-sm rounded-lg p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-blue-900">Tabel Rincian Capaian Kinerja</h2>
                        <p class="text-sm text-gray-500">Matriks monitoring realisasi per triwulan Tahun {{ $year }}</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto mt-2 md:mt-0">
                        {{-- Tombol export untuk Tab Indikator Normal --}}
                        <a href="{{ route('publications.exportTable') }}" 
                        x-show="activeTab === 'indikator'" 
                        x-transition
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-all w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 text-green-600">
                                <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                            </svg>
                            Unduh Excel (Indikator)
                        </a>

                        {{-- Tombol export untuk Tab Indikator Spesial --}}
                        <a href="{{ route('publications.exportSpesialIndikator') }}" 
                        x-show="activeTab === 'spesial'" 
                        x-transition
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-amber-300 rounded-lg text-sm font-medium text-amber-700 hover:bg-amber-50 shadow-sm transition-all w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 text-amber-600">
                                <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                            </svg>
                            Unduh Excel (Indikator T2 & T3)
                        </a>

                        {{-- Tombol export untuk Tab Detail Sasaran (gabungan normal + spesial) --}}
                        <a href="{{ route('publications.exportSasaran') }}" 
                        x-show="activeTab === 'sasaran'" 
                        x-transition
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-all w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 text-green-600">
                                <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                            </svg>
                            Unduh Excel (Sasaran/Laporan)
                        </a>
                        
                        <div class="flex bg-gray-100 p-1 rounded-lg w-full sm:w-auto">
                            <button @click="activeTab = 'indikator'" 
                                :class="activeTab === 'indikator' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 sm:flex-none justify-center px-3 py-1.5 text-sm font-medium rounded-md transition-all duration-200 flex items-center">
                                Indikator T1
                            </button>
                            <button @click="activeTab = 'spesial'" 
                                :class="activeTab === 'spesial' ? 'bg-white text-amber-700 shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 sm:flex-none justify-center px-3 py-1.5 text-sm font-medium rounded-md transition-all duration-200 flex items-center">
                                Indikator T2 atau T3
                            </button>
                            <button @click="activeTab = 'sasaran'" 
                                :class="activeTab === 'sasaran' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 sm:flex-none justify-center px-3 py-1.5 text-sm font-medium rounded-md transition-all duration-200 flex items-center">
                                Detail Sasaran
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg overflow-hidden relative">
                    <div class="overflow-auto max-h-[75vh] relative border rounded-lg">
                        
                        {{-- TABEL 1: INDIKATOR NORMAL --}}
                        <table x-show="activeTab === 'indikator'" class="w-full text-sm text-center border-collapse whitespace-nowrap">
                            <thead class="text-xs"> 
                                <tr class="bg-gray-100 text-gray-700 font-semibold border-b h-[40px]">
                                    <th rowspan="3" class="px-4 py-2 min-w-[280px] w-[280px] text-left align-middle bg-gray-100 sticky-col-1 border-r border-gray-300">Nama Indikator</th> 
                                    <th rowspan="3" class="px-3 py-2 w-32 align-middle bg-gray-100 sticky-col-2 sticky-col-shadow border-r border-gray-300">Jenis</th>
                                    <th colspan="4" class="px-2 py-1 border-r bg-blue-50 text-blue-900 border-b border-blue-200 sticky top-0 z-40">Jumlah Rencana Tahapan/Output (Y)</th>
                                    <th colspan="4" class="px-2 py-1 border-r bg-emerald-50 text-emerald-900 border-b border-emerald-200 sticky top-0 z-40">Jumlah Realisasi Tahapan/Output (X)</th>
                                    <th colspan="8" class="px-2 py-1 bg-purple-50 text-purple-900 border-b border-purple-200 sticky top-0 z-40">Capaian Kinerja (%)</th>
                                </tr>
                                
                                <tr class="text-[11px] font-medium border-b h-[35px]">
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan I</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan II</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan III</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan IV</th>
                                    
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan I</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan II</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan III</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan IV</th>
                                    
                                    <th colspan="4" class="px-2 py-1 border-r border-b bg-indigo-50 text-indigo-900 sticky top-[40px] z-40">Terhadap Target Triwulan</th>
                                    <th colspan="4" class="px-2 py-1 border-b bg-emerald-100 text-emerald-900 sticky top-[40px] z-40">Terhadap Target Tahunan</th>
                                </tr>
                                <tr class="text-[10px] text-gray-500 font-medium border-b">
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan I</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan II</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan III</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan IV</th>
                                    
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan I</th>
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan II</th>
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan III</th>
                                    <th class="px-1 py-1 bg-emerald-50 sticky top-[75px] z-30">Triwulan IV</th>
                                </tr>
                            </thead>

                            <tbody>
                            @forelse($laporanKinerjaIndikator as $row) 
                                <tbody class="group border-b border-gray-200 bg-white">
                                    {{-- Baris 1: Realisasi Tahapan --}}
                                    <tr class="hover:bg-blue-50 transition">
                                        <td rowspan="4" class="px-4 py-2 text-left align-top border-r border-gray-200 sticky-col-1 bg-white group-hover:bg-blue-50 transition-colors duration-200">
                                            <div class="font-medium text-gray-800 text-xs whitespace-normal leading-snug">{{ $row['report_name'] }}</div>
                                        </td>
                                        <td class="px-2 py-2 font-medium text-blue-900 bg-blue-50 text-[10px] sticky-col-2 sticky-col-shadow align-middle">Realisasi Tahapan</td>
                                        
                                        @for($i=1; $i<=4; $i++) <td class="px-2 py-2 text-blue-800 bg-blue-50/30 border-r align-middle font-medium">{{ $row['data']['row1_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-2 py-2 text-emerald-700 border-r align-middle bg-emerald-50/20 font-medium">{{ $row['data']['row1_green'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['tahapan']['tw'][$i] ?? 0; @endphp <td rowspan="2" class="px-2 py-2 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['tahapan']['thn'][$i] ?? 0; @endphp <td rowspan="2" class="px-2 py-2 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                    </tr>
                                    
                                    {{-- Baris 2: Target Tahapan --}}
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                                        <td class="px-2 py-2 font-medium text-gray-500 bg-white text-[10px] sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">Target Tahapan</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-2 py-2 text-gray-600 border-r align-middle hover:bg-gray-50 font-medium">{{ $row['data']['row2_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-2 py-2 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50 font-medium">{{ $row['data']['row2_green'][$i] ?? 0 }}</td> @endfor
                                    </tr>
                                    
                                    {{-- Baris 3: Realisasi Output --}}
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-2 py-2 font-medium text-purple-900 bg-purple-50 text-[10px] sticky-col-2 sticky-col-shadow align-middle">Realisasi Output</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-2 py-2 text-purple-800 bg-gray-50/30 border-r align-middle font-medium">{{ $row['data']['row3_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-2 py-2 text-emerald-700 border-r align-middle bg-emerald-50/20 font-medium">{{ $row['data']['row3_green'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['output']['tw'][$i] ?? 0; @endphp <td rowspan="2" class="px-2 py-2 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['output']['thn'][$i] ?? 0; @endphp <td rowspan="2" class="px-2 py-2 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                    </tr>

                                    {{-- Baris 4: Target Output --}}
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-300">
                                        <td class="px-2 py-2 font-medium text-gray-500 bg-white text-[10px] sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">Target Output</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-2 py-2 text-gray-600 border-r align-middle hover:bg-gray-50 font-medium">{{ $row['data']['row4_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-2 py-2 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50 font-medium">{{ $row['data']['row4_green'][$i] ?? 0 }}</td> @endfor
                                    </tr>
                                </tbody>
                            @empty
                                <tr><td colspan="18" class="text-center py-8 text-gray-500">Tidak ada data indikator T1</td></tr>
                            @endforelse
                            </tbody>
                        </table>

                        {{-- TABEL 2: INDIKATOR SPESIAL (3 Indikator dengan 4 Laporan) --}}
                        <table x-show="activeTab === 'spesial'" x-cloak class="w-full text-sm text-center border-collapse whitespace-nowrap">
                            <thead class="text-xs"> 
                                <tr class="bg-gray-100 text-gray-700 font-semibold border-b h-[40px]">
                                    <th rowspan="3" class="px-4 py-2 min-w-[280px] w-[280px] text-left align-middle bg-gray-100 sticky-col-1 border-r border-gray-300">Nama Indikator</th> 
                                    <th rowspan="3" class="px-3 py-2 w-32 align-middle bg-gray-100 sticky-col-2 sticky-col-shadow border-r border-gray-300">Jenis</th>
                                    <th colspan="4" class="px-2 py-1 border-r bg-blue-50 text-blue-900 border-b border-blue-200 sticky top-0 z-40">Jumlah Rencana Tahapan/Output (Y)</th>
                                    <th colspan="4" class="px-2 py-1 border-r bg-emerald-50 text-emerald-900 border-b border-emerald-200 sticky top-0 z-40">Jumlah Realisasi Tahapan/Output (X)</th>
                                    <th colspan="8" class="px-2 py-1 bg-purple-50 text-purple-900 border-b border-purple-200 sticky top-0 z-40">Capaian Kinerja (%)</th>
                                </tr>
                                
                                <tr class="text-[11px] font-medium border-b h-[35px]">
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan I</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan II</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan III</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan IV</th>
                                    
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan I</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan II</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan III</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan IV</th>
                                    
                                    <th colspan="4" class="px-2 py-1 border-r border-b bg-indigo-50 text-indigo-900 sticky top-[40px] z-40">Terhadap Target Triwulan</th>
                                    <th colspan="4" class="px-2 py-1 border-b bg-emerald-100 text-emerald-900 sticky top-[40px] z-40">Terhadap Target Tahunan</th>
                                </tr>
                                <tr class="text-[10px] text-gray-500 font-medium border-b">
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan I</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan II</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan III</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan IV</th>
                                    
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan I</th>
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan II</th>
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan III</th>
                                    <th class="px-1 py-1 bg-emerald-50 sticky top-[75px] z-30">Triwulan IV</th>
                                </tr>
                            </thead>

                            @forelse($laporanKinerjaSpesialIndikator as $row)
                            <tbody class="group border-b border-gray-200 bg-white">
                                <tr class="hover:bg-amber-50 transition-colors duration-200">
                                    <td class="px-4 py-3 text-left align-middle border-r border-gray-200 sticky-col-1 bg-white group-hover:bg-amber-50 transition-colors duration-200">
                                        <div class="font-medium text-gray-800 text-xs whitespace-normal leading-snug">
                                            {{ $row['report_name'] }}
                                        </div>
                                        <span class="inline-block mt-1 px-2 py-0.5 text-[10px] bg-amber-100 text-amber-800 rounded-full">T2 atau T3</span>
                                    </td>
                                    <td class="px-2 py-2 font-medium text-blue-900 bg-blue-50 text-[10px] sticky-col-2 sticky-col-shadow align-middle">Poin</td>
                                    
                                    {{-- Target Poin per TW --}}
                                    @for($i=1; $i<=4; $i++) 
                                        <td class="px-2 py-2 text-blue-800 bg-blue-50/30 border-r align-middle font-medium">
                                            {{ number_format($row['data']['target_q'][$i] ?? 0, 2) }}
                                        </td> 
                                    @endfor
                                    
                                    {{-- Realisasi Poin per TW --}}
                                    @for($i=1; $i<=4; $i++) 
                                        <td class="px-2 py-2 text-emerald-700 border-r align-middle bg-emerald-50/20 font-medium">
                                            {{ number_format($row['data']['actual_q'][$i] ?? 0, 2) }}
                                        </td> 
                                    @endfor
                                    
                                    {{-- Capaian vs Target TW --}}
                                    @for($i=1; $i<=4; $i++) 
                                        @php $val = $row['capaian']['output']['tw'][$i] ?? 0; @endphp 
                                        <td class="px-2 py-2 border-r align-middle bg-indigo-50/10">
                                            <span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">
                                                {{ number_format($val, 1) }}%
                                            </span>
                                        </td> 
                                    @endfor
                                    
                                    {{-- Capaian vs Target Tahun --}}
                                    @for($i=1; $i<=4; $i++) 
                                        @php $val = $row['capaian']['output']['thn'][$i] ?? 0; @endphp 
                                        <td class="px-2 py-2 {{ $i < 4 ? 'border-r' : '' }} align-middle bg-emerald-50/10">
                                            <span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">
                                                {{ number_format($val, 1) }}%
                                            </span>
                                        </td> 
                                    @endfor
                                </tr>
                            @empty
                                <tr><td colspan="17" class="text-center py-8 text-gray-500">Tidak ada data indikator T2 atau T3</td></tr>
                            @endforelse
                            </tbody>
                        </table>

                        {{-- TABEL 3: DETAIL SASARAN/LAPORAN (Normal + Spesial) --}}
                        <table x-show="activeTab === 'sasaran'" class="w-full text-sm text-center border-collapse whitespace-nowrap" style="display: none;">
                            <thead class="text-xs"> 
                                
                                <tr class="bg-gray-100 text-gray-700 font-semibold border-b h-[40px]">
                                    <th rowspan="3" class="px-4 py-2 min-w-[280px] w-[280px] text-left align-middle bg-gray-100 sticky-col-1 border-r border-gray-300">Nama Indikator</th> 
                                    <th rowspan="3" class="px-3 py-2 w-32 align-middle bg-gray-100 sticky-col-2 sticky-col-shadow border-r border-gray-300">Jenis</th>
                                    <th colspan="4" class="px-2 py-1 border-r bg-blue-50 text-blue-900 border-b border-blue-200 sticky top-0 z-40">Jumlah Rencana Tahapan/Output (Y)</th>
                                    <th colspan="4" class="px-2 py-1 border-r bg-emerald-50 text-emerald-900 border-b border-emerald-200 sticky top-0 z-40">Jumlah Realisasi Tahapan/Output (X)</th>
                                    <th colspan="8" class="px-2 py-1 bg-purple-50 text-purple-900 border-b border-purple-200 sticky top-0 z-40">Capaian Kinerja (%)</th>
                                </tr>
                                
                                <tr class="text-[11px] font-medium border-b h-[35px]">
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan I</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan II</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan III</th>
                                    <th class="px-2 py-1 border-r bg-blue-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan IV</th>
                                    
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan I</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan II</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan III</th>
                                    <th class="px-2 py-1 border-r bg-emerald-50 text-gray-600 min-w-[60px] sticky top-[40px] z-40" rowspan="2">Triwulan IV</th>
                                    
                                    <th colspan="4" class="px-2 py-1 border-r border-b bg-indigo-50 text-indigo-900 sticky top-[40px] z-40">Terhadap Target Triwulan</th>
                                    <th colspan="4" class="px-2 py-1 border-b bg-emerald-100 text-emerald-900 sticky top-[40px] z-40">Terhadap Target Tahunan</th>
                                </tr>

                                <tr class="text-[10px] text-gray-500 font-medium border-b">
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan I</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan II</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan III</th>
                                    <th class="px-1 py-1 border-r bg-indigo-50 sticky top-[75px] z-30">Triwulan IV</th>
                                    
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan I</th>
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan II</th>
                                    <th class="px-1 py-1 border-r bg-emerald-50 sticky top-[75px] z-30">Triwulan III</th>
                                    <th class="px-1 py-1 bg-emerald-50 sticky top-[75px] z-30">Triwulan IV</th>
                                </tr>
                            </thead>

                            @foreach($laporanKinerjaSasaran as $row)
                                <tbody class="group border-b border-gray-200 bg-white">
                                    <tr class="hover:bg-blue-50 transition">
                                        <td rowspan="4" class="px-4 py-2 text-left align-top border-r border-gray-200 sticky-col-1 bg-white group-hover:bg-blue-50 transition-colors duration-200">
                                            <div class="font-medium text-gray-800 text-xs whitespace-normal leading-snug">{{ $row['report_name'] }}</div>
                                        </td>
                                        <td class="px-2 py-2 font-medium text-blue-900 bg-blue-50 text-[10px] sticky-col-2 sticky-col-shadow align-middle">Realisasi Tahapan</td>

                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-blue-800 bg-blue-50/30 border-r align-middle font-medium">{{ $row['data']['row1_blue'][$i] ?? 0 }}</td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-emerald-700 border-r align-middle bg-emerald-50/20 font-medium">{{ $row['data']['row1_green'][$i] ?? 0 }}</td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            @php $val = $row['capaian']['tahapan']['tw'][$i] ?? 0; @endphp
                                            <td rowspan="2" class="px-2 py-2 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span>
                                            </td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            @php $val = $row['capaian']['tahapan']['thn'][$i] ?? 0; @endphp
                                            <td rowspan="2" class="px-2 py-2 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span>
                                            </td>
                                        @endfor
                                    </tr>
                                    
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                                        <td class="px-2 py-2 font-medium text-gray-500 bg-white text-[10px] sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">Target Tahapan</td>
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-gray-600 border-r align-middle font-medium hover:bg-gray-50">{{ $row['data']['row2_blue'][$i] ?? 0 }}</td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50 font-medium">{{ $row['data']['row2_green'][$i] ?? 0 }}</td>
                                        @endfor
                                    </tr>
                                    
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-2 py-2 font-medium text-purple-900 bg-purple-50 text-[10px] sticky-col-2 sticky-col-shadow align-middle">Realisasi Output</td>
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-purple-800 bg-gray-50/30 border-r align-middle font-medium">{{ $row['data']['row3_blue'][$i] ?? 0 }}</td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-emerald-700 border-r align-middle bg-emerald-50/20 font-medium">{{ $row['data']['row3_green'][$i] ?? 0 }}</td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            @php $val = $row['capaian']['output']['tw'][$i] ?? 0; @endphp
                                            <td rowspan="2" class="px-2 py-2 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span>
                                            </td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            @php $val = $row['capaian']['output']['thn'][$i] ?? 0; @endphp
                                            <td rowspan="2" class="px-2 py-2 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span>
                                            </td>
                                        @endfor
                                    </tr>

                                    <tr class="hover:bg-gray-50 transition border-b border-gray-300">
                                        <td class="px-2 py-2 font-medium text-gray-500 bg-white text-[10px] sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">Target Output</td>
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-gray-600 border-r align-middle font-medium hover:bg-gray-50">{{ $row['data']['row4_blue'][$i] ?? 0 }}</td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50 font-medium">{{ $row['data']['row4_green'][$i] ?? 0 }}</td>
                                        @endfor
                                    </tr>
                                </tbody>
                            @endforeach
                                    
                            @foreach($laporanKinerjaSpesialSasaran as $row)
                                <tbody class="group border-b border-gray-200 bg-white hover:bg-amber-50/20 transition">
                                    <tr class="border-b border-gray-200 hover:bg-amber-50 transition">
                                        <td rowspan="2" class="px-4 py-2 text-left align-top border-r border-gray-200 sticky-col-1 bg-white group-hover:bg-amber-50 transition-colors duration-200">
                                            <div class="font-medium text-gray-800 text-xs whitespace-normal leading-snug">{{ $row['report_name'] }}</div>
                                        </td>
                                        <td class="px-2 py-2 border-r align-middle sticky-col-2 sticky-col-shadow bg-white group-hover:bg-amber-50 transition-colors duration-200">
                                            <span class="px-2 py-0.5 text-[10px] bg-amber-100 text-amber-800 rounded-full font-medium">T2 atau T3</span>
                                        </td>
                                        
                                        {{-- Target Poin per TW --}}
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-blue-800 bg-blue-50/30 border-r align-middle font-medium">{{ number_format($row['data']['target_q'][$i] ?? 0, 2) }}</td>
                                        @endfor
                                        
                                        {{-- Realisasi Poin per TW --}}
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-2 py-2 text-emerald-700 border-r align-middle bg-emerald-50/20 font-medium">{{ number_format($row['data']['actual_q'][$i] ?? 0, 2) }}</td>
                                        @endfor
                                        
                                        {{-- Capaian vs Target TW --}}
                                        @for($i=1; $i<=4; $i++) 
                                            @php $val = $row['capaian']['output']['tw'][$i] ?? 0; @endphp 
                                            <td class="px-2 py-2 border-r align-middle bg-indigo-50/10">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">
                                                    {{ number_format($val, 1) }}%
                                                </span>
                                            </td> 
                                        @endfor
                                        
                                        {{-- Capaian vs Target Tahun --}}
                                        @for($i=1; $i<=4; $i++) 
                                            @php $val = $row['capaian']['output']['thn'][$i] ?? 0; @endphp 
                                            <td class="px-2 py-2 {{ $i < 4 ? 'border-r' : '' }} align-middle bg-emerald-50/10">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-medium' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">
                                                    {{ number_format($val, 1) }}%
                                                </span>
                                            </td> 
                                        @endfor
                                    </tr>
                                    
                                    {{-- Baris kedua untuk data tambahan --}}
                                    <tr class="border-b border-gray-200 hover:bg-amber-50/30 transition"></tr>
                                </tbody>
                            @endforeach
                            <!-- <tbody><tr><td colspan="22" class="px-4 py-8 text-center text-gray-500 italic">Belum ada data.</td></tr></tbody> -->
                        </table>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="text-xs font-bold text-gray-700 mb-2">Keterangan:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-100 rounded"></span>
                            <span><strong>Indikator T1:</strong> Target flat, realisasi dari count dokumen</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-amber-100 rounded"></span>
                            <span><strong>Indikator T2 atau T3:</strong> Target berbeda per TW, realisasi input manual poin</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-green-600 font-medium">100%+</span>
                            <span>Capaian memenuhi/melebihi target</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-blue-600">0-99%</span>
                            <span>Capaian belum memenuhi target</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
