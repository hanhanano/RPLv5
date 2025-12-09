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
        .sticky-col-1 { position: sticky; left: 0; z-index: 30; } 
        .sticky-col-2 { position: sticky; left: 280px; z-index: 30; } 
        thead .sticky-col-1, thead .sticky-col-2 { z-index: 60 !important; position: sticky !important; top: 0 !important; height: auto; }
        .sticky-col-shadow { border-right: 2px solid #e5e7eb; box-shadow: 4px 0 4px -2px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-gray-50 font-inter">
    
    <div><x-navbar></x-navbar></div>

    <main class="py-8" x-data="{ activeTab: 'indikator' }">
        <div class="max-w-[98%] mx-auto px-4 space-y-6">
            
            @php
                $totalScoreIKU = 0;
                $countActiveIKU = 0;
                foreach($laporanKinerjaSasaran as $item) {
                    $scoreOutputTW4 = $item['capaian']['output']['thn'][4] ?? 0;
                    if ($scoreOutputTW4 > 0) {
                        $totalScoreIKU += $scoreOutputTW4;
                        $countActiveIKU++;
                    }
                }
                $nilaiIKU = $countActiveIKU > 0 ? ($totalScoreIKU / $countActiveIKU) : 0;
                
                // 1. Tentukan Predikat
                if ($nilaiIKU > 90) { $predikatIKU = "AA/Sangat Memuaskan"; }
                elseif ($nilaiIKU > 80) { $predikatIKU = "A/Memuaskan"; }
                elseif ($nilaiIKU > 70) { $predikatIKU = "BB/Sangat Baik"; }
                elseif ($nilaiIKU > 60) { $predikatIKU = "B/Baik"; }
                elseif ($nilaiIKU > 50) { $predikatIKU = "CC/Cukup (Memadai)"; }
                elseif ($nilaiIKU > 30) { $predikatIKU = "C/Kurang"; }
                else { $predikatIKU = "D/Sangat Kurang"; }

                // 2. Tentukan Warna (Gradasi)
                if ($nilaiIKU > 90) { 
                    // AA: Biru Tua - Excellent
                    $ikuColor = 'text-blue-700'; 
                    $ikuBg = 'bg-blue-100'; 
                    $ikuBorder = 'border-blue-400';
                } elseif ($nilaiIKU > 80) { 
                    // A: Cyan - Very Good
                    $ikuColor = 'text-cyan-700'; 
                    $ikuBg = 'bg-cyan-100'; 
                    $ikuBorder = 'border-cyan-400';
                } elseif ($nilaiIKU > 70) { 
                    // BB: Hijau - Good+
                    $ikuColor = 'text-green-700'; 
                    $ikuBg = 'bg-green-100'; 
                    $ikuBorder = 'border-green-400';
                } elseif ($nilaiIKU > 60) { 
                    // B: Oranye - Good
                    $ikuColor = 'text-lime-700'; 
                    $ikuBg = 'bg-lime-100'; 
                    $ikuBorder = 'border-lime-400';
                } elseif ($nilaiIKU > 50) { 
                    // CC: Kuning - Fair
                    $ikuColor = 'text-yellow-700'; 
                    $ikuBg = 'bg-yellow-100'; 
                    $ikuBorder = 'border-yellow-400';
                } elseif ($nilaiIKU > 30) { 
                    // C: Amber - Poor
                    $ikuColor = 'text-amber-700'; 
                    $ikuBg = 'bg-amber-100'; 
                    $ikuBorder = 'border-amber-400';
                } else { 
                    // D: Merah - Critical
                    $ikuColor = 'text-red-700'; 
                    $ikuBg = 'bg-red-100'; 
                    $ikuBorder = 'border-red-400';
                }
            @endphp

            <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Capaian Kinerja Utama (IKU)</h2>
                        <p class="text-gray-500 mt-1">Rata-rata capaian kinerja output seluruh sasaran/laporan aktif terhadap target tahunan.</p>
                    </div>
                    <div class="flex items-center gap-4 {{ $ikuBg }} px-6 py-4 rounded-xl border {{ $ikuBorder }}">
                        <div class="text-right">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Total Capaian</p>
                            <p class="text-4xl font-extrabold {{ $ikuColor }} leading-none">{{ number_format($nilaiIKU, 2) }}%</p>
                            <p class="text-sm font-bold {{ $ikuColor }} mt-1">{{ $predikatIKU }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-full shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 {{ $ikuColor }}">
                                <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M12.75 3a.75.75 0 0 1 .5-.218 8.25 8.25 0 0 1 8.287 8.287.75.75 0 0 1-.218.5H12.75V3Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border shadow-sm rounded-lg p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <!-- Kiri: Judul -->
                    <div>
                        <h2 class="text-lg font-semibold text-blue-900">Tabel Rincian Capaian Kinerja</h2>
                        <p class="text-sm text-gray-500">Matriks monitoring realisasi per triwulan Tahun {{ $year }}</p>
                    </div>

                    <!-- Kanan: Tombol Excel & Tab Switcher -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <!-- Tombol Unduh Excel -->
                        {{-- TOMBOL UNDUH INDIKATOR (Memanggil exportTable biasa) --}}
                        <a href="{{ route('publications.exportTable') }}" 
                           x-show="activeTab === 'indikator'" 
                           x-transition
                           class="flex items-center justify-center gap-1 border text-gray-700 px-3 py-2 rounded-lg text-xs sm:text-sm shadow hover:text-white hover:bg-emerald-800 whitespace-nowrap transition-colors bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                            </svg>
                            Unduh Excel (Indikator)
                        </a>

                        {{-- TOMBOL UNDUH SASARAN (Memanggil exportTableSasaran BARU) --}}
                        <a href="{{ route('publications.exportSasaran') }}" 
                           x-show="activeTab === 'sasaran'" 
                           x-transition
                           class="flex items-center justify-center gap-1 border text-gray-700 px-3 py-2 rounded-lg text-xs sm:text-sm shadow hover:text-white hover:bg-blue-800 whitespace-nowrap transition-colors bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                            </svg>
                            Unduh Excel (Sasaran)
                        </a>
                        
                        <!-- Tab Switcher -->
                        <div class="flex bg-gray-100 p-1 rounded-lg">
                            <button @click="activeTab = 'indikator'" 
                                :class="activeTab === 'indikator' ? 'bg-white text-blue-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200">
                                Berdasarkan Indikator
                            </button>
                            <button @click="activeTab = 'sasaran'" 
                                :class="activeTab === 'sasaran' ? 'bg-white text-blue-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200">
                                Berdasarkan Sasaran/Laporan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg overflow-hidden relative">
                    <div class="overflow-auto max-h-[75vh] relative border rounded-lg">
                        
                        {{-- TABEL 1: INDIKATOR --}}
                        <table x-show="activeTab === 'indikator'" class="w-full text-sm text-center border-collapse whitespace-nowrap">
                            <thead class="text-sm">
                                <tr class="bg-gray-100 text-gray-700 font-semibold border-b h-[50px]">
                                    <th rowspan="3" class="px-4 py-3 min-w-[280px] w-[280px] text-left align-middle bg-gray-100 sticky-col-1 border-r border-gray-300">Nama Indikator</th>
                                    <th rowspan="3" class="px-3 py-3 w-32 align-middle bg-gray-100 sticky-col-2 sticky-col-shadow border-r border-gray-300">Jenis</th>
                                    <th colspan="4" class="px-2 py-2 border-r bg-blue-50 text-blue-900 border-b border-blue-200 sticky top-0 z-40">Rencana Kegiatan</th>
                                    <th colspan="4" class="px-2 py-2 border-r bg-emerald-50 text-emerald-900 border-b border-emerald-200 sticky top-0 z-40">Realisasi Kegiatan</th>
                                    <th colspan="8" class="px-2 py-2 bg-purple-50 text-purple-900 border-b border-purple-200 sticky top-0 z-40">Capaian Kinerja (%)</th>
                                </tr>
                                <tr class="text-xs font-medium border-b h-[45px]">
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW I</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW II</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW III</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW IV</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW I</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW II</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW III</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW IV</th>
                                    <th colspan="4" class="px-4 py-2 border-r border-b bg-indigo-50 text-indigo-900 sticky top-[50px] z-40">Terhadap Target TW</th>
                                    <th colspan="4" class="px-4 py-2 border-b bg-emerald-100 text-emerald-900 sticky top-[50px] z-40">Terhadap Target THN</th>
                                </tr>
                                <tr class="text-xs text-gray-500 font-medium border-b">
                                    <th class="px-2 py-2 border-r bg-indigo-50 sticky top-[95px] z-30">TW I</th>
                                    <th class="px-2 py-2 border-r bg-indigo-50 sticky top-[95px] z-30">TW II</th>
                                    <th class="px-2 py-2 border-r bg-indigo-50 sticky top-[95px] z-30">TW III</th>
                                    <th class="px-2 py-2 border-r bg-indigo-50 sticky top-[95px] z-30">TW IV</th>
                                    <th class="px-2 py-2 border-r bg-emerald-50 sticky top-[95px] z-30">TW I</th>
                                    <th class="px-2 py-2 border-r bg-emerald-50 sticky top-[95px] z-30">TW II</th>
                                    <th class="px-2 py-2 border-r bg-emerald-50 sticky top-[95px] z-30">TW III</th>
                                    <th class="px-2 py-2 bg-emerald-50 sticky top-[95px] z-30">TW IV</th>
                                </tr>
                            </thead>
                            @forelse($laporanKinerjaIndikator as $row)
                                <tbody class="group border-b border-gray-200 bg-white">
                                    <tr class="hover:bg-blue-50 transition">
                                        <td rowspan="4" class="px-4 py-4 text-left align-top border-r border-gray-200 sticky-col-1 bg-white group-hover:bg-blue-50 transition-colors duration-200">
                                            <div class="font-bold text-gray-800 text-sm whitespace-normal leading-snug">{{ $row['report_name'] }}</div>
                                        </td>
                                        <td class="px-3 py-3 font-bold text-blue-900 bg-blue-50 text-xs sticky-col-2 sticky-col-shadow align-middle">Realisasi Tahapan</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-gray-500 bg-blue-50/30 border-r align-middle">{{ $row['data']['row1_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-emerald-700 border-r align-middle bg-emerald-50/20 font-bold">{{ $row['data']['row1_green'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['tahapan']['tw'][$i] ?? 0; @endphp <td rowspan="2" class="px-3 py-3 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['tahapan']['thn'][$i] ?? 0; @endphp <td rowspan="2" class="px-3 py-3 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                                        <td class="px-3 py-3 font-bold text-gray-500 bg-white text-xs sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">Target Tahapan</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-blue-800 border-r align-middle font-medium hover:bg-gray-50">{{ $row['data']['row2_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50">{{ $row['data']['row2_green'][$i] ?? 0 }}</td> @endfor
                                    </tr>
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-3 py-3 font-bold text-purple-900 bg-purple-50 text-xs sticky-col-2 sticky-col-shadow align-middle">Realisasi Output</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-gray-400 bg-gray-50/30 border-r align-middle">{{ $row['data']['row3_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-emerald-700 border-r align-middle bg-emerald-50/20 font-bold">{{ $row['data']['row3_green'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['output']['tw'][$i] ?? 0; @endphp <td rowspan="2" class="px-3 py-3 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['output']['thn'][$i] ?? 0; @endphp <td rowspan="2" class="px-3 py-3 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-300">
                                        <td class="px-3 py-3 font-bold text-gray-500 bg-white text-xs sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">Target Output</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-purple-800 border-r align-middle font-medium hover:bg-gray-50">{{ $row['data']['row4_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50 font-medium">{{ $row['data']['row4_green'][$i] ?? 0 }}</td> @endfor
                                    </tr>
                                </tbody>
                            @empty
                                <tbody><tr><td colspan="22" class="px-4 py-12 text-center text-gray-500 italic">Belum ada data.</td></tr></tbody>
                            @endforelse
                        </table>

                        {{-- TABEL 2: SASARAN/LAPORAN --}}
                        <table x-show="activeTab === 'sasaran'" class="w-full text-sm text-center border-collapse whitespace-nowrap" style="display: none;">
                            <thead class="text-sm">
                                <tr class="bg-gray-100 text-gray-700 font-semibold border-b h-[50px]">
                                    <th rowspan="3" class="px-4 py-3 min-w-[280px] w-[280px] text-left align-middle bg-gray-100 sticky-col-1 border-r border-gray-300">Nama Sasaran/Laporan</th>
                                    <th rowspan="3" class="px-3 py-3 w-32 align-middle bg-gray-100 sticky-col-2 sticky-col-shadow border-r border-gray-300">Jenis</th>
                                    <th colspan="4" class="px-2 py-2 border-r bg-blue-50 text-blue-900 border-b border-blue-200 sticky top-0 z-50">Rencana Kegiatan</th>
                                    <th colspan="4" class="px-2 py-2 border-r bg-emerald-50 text-emerald-900 border-b border-emerald-200 sticky top-0 z-50">Realisasi Kegiatan</th>
                                    <th colspan="8" class="px-2 py-2 bg-purple-50 text-purple-900 border-b border-purple-200 sticky top-0 z-50">Capaian Kinerja (%)</th>
                                </tr>
                                <tr class="text-xs font-medium border-b h-[45px]">
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW I</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW II</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW III</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW IV</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW I</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW II</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW III</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW IV</th>
                                    <th colspan="4" class="px-4 py-2 border-r border-b bg-indigo-50 text-indigo-900 sticky top-[50px] z-40">Terhadap Target TW</th>
                                    <th colspan="4" class="px-4 py-2 border-b bg-emerald-100 text-emerald-900 sticky top-[50px] z-40">Terhadap Target THN</th>
                                </tr>
                                <tr class="text-xs text-gray-500 font-medium border-b">
                                    <th class="px-2 py-2 border-r bg-indigo-50 sticky top-[95px] z-30">TW I</th>
                                    <th class="px-2 py-2 border-r bg-indigo-50 sticky top-[95px] z-30">TW II</th>
                                    <th class="px-2 py-2 border-r bg-indigo-50 sticky top-[95px] z-30">TW III</th>
                                    <th class="px-2 py-2 border-r bg-indigo-50 sticky top-[95px] z-30">TW IV</th>
                                    <th class="px-2 py-2 border-r bg-emerald-50 sticky top-[95px] z-30">TW I</th>
                                    <th class="px-2 py-2 border-r bg-emerald-50 sticky top-[95px] z-30">TW II</th>
                                    <th class="px-2 py-2 border-r bg-emerald-50 sticky top-[95px] z-30">TW III</th>
                                    <th class="px-2 py-2 bg-emerald-50 sticky top-[95px] z-30">TW IV</th>
                                </tr>
                            </thead>
                            @forelse($laporanKinerjaSasaran as $row)
                                <tbody class="group border-b border-gray-200 bg-white">
                                    <tr class="hover:bg-blue-50 transition">
                                        <td rowspan="4" class="px-4 py-4 text-left align-top border-r border-gray-200 sticky-col-1 bg-white group-hover:bg-blue-50 transition-colors duration-200">
                                            <div class="font-bold text-gray-800 text-sm whitespace-normal leading-snug">{{ $row['report_name'] }}</div>
                                        </td>
                                        <td class="px-3 py-3 font-bold text-blue-900 bg-blue-50 text-xs sticky-col-2 sticky-col-shadow align-middle">Realisasi Tahapan</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-gray-500 bg-blue-50/30 border-r align-middle">{{ $row['data']['row1_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-emerald-700 border-r align-middle bg-emerald-50/20 font-bold">{{ $row['data']['row1_green'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['tahapan']['tw'][$i] ?? 0; @endphp <td rowspan="2" class="px-3 py-3 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['tahapan']['thn'][$i] ?? 0; @endphp <td rowspan="2" class="px-3 py-3 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                                        <td class="px-3 py-3 font-bold text-gray-500 bg-white text-xs sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">Target Tahapan</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-blue-800 border-r align-middle font-medium hover:bg-gray-50">{{ $row['data']['row2_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50">{{ $row['data']['row2_green'][$i] ?? 0 }}</td> @endfor
                                    </tr>
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-3 py-3 font-bold text-purple-900 bg-purple-50 text-xs sticky-col-2 sticky-col-shadow align-middle">Realisasi Output</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-gray-400 bg-gray-50/30 border-r align-middle">{{ $row['data']['row3_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-emerald-700 border-r align-middle bg-emerald-50/20 font-bold">{{ $row['data']['row3_green'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['output']['tw'][$i] ?? 0; @endphp <td rowspan="2" class="px-3 py-3 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                        @for($i=1; $i<=4; $i++) @php $val = $row['capaian']['output']['thn'][$i] ?? 0; @endphp <td rowspan="2" class="px-3 py-3 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors"><span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span></td> @endfor
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-300">
                                        <td class="px-3 py-3 font-bold text-gray-500 bg-white text-xs sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">Target Output</td>
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-purple-800 border-r align-middle font-medium hover:bg-gray-50">{{ $row['data']['row4_blue'][$i] ?? 0 }}</td> @endfor
                                        @for($i=1; $i<=4; $i++) <td class="px-3 py-3 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50 font-medium">{{ $row['data']['row4_green'][$i] ?? 0 }}</td> @endfor
                                    </tr>
                                </tbody>
                            @empty
                                <tbody><tr><td colspan="22" class="px-4 py-12 text-center text-gray-500 italic">Belum ada data.</td></tr></tbody>
                            @endforelse
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>