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
    /* Scrollbar */
    .overflow-auto::-webkit-scrollbar { width: 10px; height: 10px; }
    .overflow-auto::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 5px; }

    /* KONFIGURASI STICKY */
    
    /* 1. Kolom Kiri (Horizontal Freeze) */
    .sticky-col-1 { position: sticky; left: 0; z-index: 30; } 
    .sticky-col-2 { position: sticky; left: 280px; z-index: 30; } 
    
    /* 2. Header & Intersection (Tetap paling tinggi) */
    thead .sticky-col-1, 
    thead .sticky-col-2 { 
        z-index: 60 !important; 
        position: sticky !important;
        top: 0 !important;
        height: auto;
    }
    
    .sticky-col-shadow { border-right: 2px solid #e5e7eb; box-shadow: 4px 0 4px -2px rgba(0,0,0,0.05); }
</style>
</head>
<body class="bg-gray-50 font-inter">
    
    <div><x-navbar></x-navbar></div>

    <main class="py-8">
        <div class="max-w-[98%] mx-auto px-4 space-y-6">
            <div class="bg-white border shadow-sm rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-blue-900">Laporan Capaian Kinerja</h2>
                        <p class="text-sm text-gray-500">Tahun {{ $year }}</p>
                    </div>
                </div>

                <div class="border rounded-lg overflow-hidden relative">
                    {{-- Pastikan container memiliki max-height dan overflow-auto --}}
                    <div class="overflow-auto max-h-[75vh] relative border rounded-lg">
                        <table class="w-full text-sm text-center border-collapse whitespace-nowrap">
                            
                            <thead class="text-sm">
                                {{-- =============== BARIS 1 (Main Header) =============== --}}
                                {{-- Kita set tinggi baris ini h-[50px] --}}
                                <tr class="bg-gray-100 text-gray-700 font-semibold border-b h-[50px]">
                                    
                                    {{-- KOLOM FREEZE KIRI & ATAS (Corner) --}}
                                    {{-- Kita beri bg-gray-100 SOLID agar tidak tembus --}}
                                    <th rowspan="3" class="px-4 py-3 min-w-[280px] w-[280px] text-left align-middle bg-gray-100 sticky-col-1 border-r border-gray-300">
                                        Nama Sasaran/Laporan
                                    </th>
                                    <th rowspan="3" class="px-3 py-3 w-32 align-middle bg-gray-100 sticky-col-2 sticky-col-shadow border-r border-gray-300">
                                        Jenis
                                    </th>

                                    {{-- Header Group --}}
                                    {{-- top-0, z-index 50 --}}
                                    <th colspan="4" class="px-2 py-2 border-r bg-blue-50 text-blue-900 border-b border-blue-200 sticky top-0 z-50">
                                        Rencana Kegiatan
                                    </th>
                                    <th colspan="4" class="px-2 py-2 border-r bg-emerald-50 text-emerald-900 border-b border-emerald-200 sticky top-0 z-50">
                                        Realisasi Kegiatan
                                    </th>
                                    <th colspan="8" class="px-2 py-2 bg-purple-50 text-purple-900 border-b border-purple-200 sticky top-0 z-50">
                                        Capaian Kinerja (%)
                                    </th>
                                </tr>

                                {{-- =============== BARIS 2 (Sub Header TW & Target) =============== --}}
                                {{-- Posisi top: 50px (tinggi baris 1). Tinggi baris ini kita set h-[45px] --}}
                                <tr class="text-xs font-medium border-b h-[45px]">
                                    
                                    {{-- TW Rencana (Background SOLID bg-blue-50) --}}
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW I</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW II</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW III</th>
                                    <th class="px-4 py-2 border-r bg-blue-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW IV</th>
                                    
                                    {{-- TW Realisasi (Background SOLID bg-emerald-50) --}}
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW I</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW II</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW III</th>
                                    <th class="px-4 py-2 border-r bg-emerald-50 text-gray-600 min-w-[80px] sticky top-[50px] z-40" rowspan="2">TW IV</th>

                                    {{-- Header Capaian Group (Background SOLID) --}}
                                    <th colspan="4" class="px-4 py-2 border-r border-b bg-indigo-50 text-indigo-900 sticky top-[50px] z-40">
                                        Terhadap Target TW
                                    </th>
                                    <th colspan="4" class="px-4 py-2 border-b bg-emerald-100 text-emerald-900 sticky top-[50px] z-40">
                                        Terhadap Target THN
                                    </th>
                                </tr>

                                {{-- =============== BARIS 3 (Sub Header Capaian TW) =============== --}}
                                {{-- Posisi top: 95px (50px + 45px). Tinggi auto/sisa. --}}
                                <tr class="text-xs text-gray-500 font-medium border-b">
                                    {{-- Background SOLID --}}
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
                            
                            @forelse($laporanKinerja as $row)
                                {{-- Tambahkan class 'group' agar kita bisa mendeteksi hover pada satu blok penuh --}}
                                <tbody class="group border-b border-gray-200 bg-white">
                                    
                                    {{-- =============== BARIS 1: REALISASI TAHAPAN =============== --}}
                                    {{-- Hapus border-t lama, kita andalkan border-b pada tbody --}}
                                    <tr class="hover:bg-blue-50 transition">
                                        
                                        {{-- KOLOM STICKY 1: NAMA SASARAN --}}
                                        <td rowspan="4" class="px-4 py-4 text-left align-top border-r border-gray-200 sticky-col-1 bg-white group-hover:bg-blue-50 transition-colors duration-200">
                                            <div class="font-bold text-gray-800 text-sm whitespace-normal leading-snug">{{ $row['report_name'] }}</div>
                                        </td>

                                        {{-- KOLOM STICKY 2: LABEL JENIS --}}
                                        <td class="px-3 py-3 font-bold text-blue-900 bg-blue-50 text-xs sticky-col-2 sticky-col-shadow align-middle">
                                            Realisasi Tahapan
                                        </td>
                                        
                                        {{-- DATA SCROLLABLE --}}
                                        @for($i=1; $i<=4; $i++) 
                                            <td class="px-3 py-3 text-gray-500 bg-blue-50/30 border-r align-middle">
                                                {{ $row['data']['row1_blue'][$i] ?? 0 }}
                                            </td> 
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-3 py-3 text-emerald-700 border-r align-middle bg-emerald-50/20 font-bold">
                                                {{ $row['data']['row1_green'][$i] ?? 0 }}
                                            </td>
                                        @endfor

                                        {{-- CAPAIAN TAHAPAN (ROWSPAN 2) --}}
                                        @for($i=1; $i<=4; $i++)
                                            @php $val = $row['capaian']['tahapan']['tw'][$i] ?? 0; @endphp
                                            <td rowspan="2" class="px-3 py-3 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span>
                                            </td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            @php $val = $row['capaian']['tahapan']['thn'][$i] ?? 0; @endphp
                                            <td rowspan="2" class="px-3 py-3 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span>
                                            </td>
                                        @endfor
                                    </tr>

                                    {{-- =============== BARIS 2: TARGET TAHAPAN =============== --}}
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                                        <td class="px-3 py-3 font-bold text-gray-500 bg-white text-xs sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">
                                            Target Tahapan
                                        </td>
                                        
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-3 py-3 text-blue-800 border-r align-middle font-medium hover:bg-gray-50">
                                                {{ $row['data']['row2_blue'][$i] ?? 0 }}
                                            </td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++) 
                                            <td class="px-3 py-3 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50">
                                                {{ $row['data']['row2_green'][$i] ?? 0 }}
                                            </td> 
                                        @endfor
                                    </tr>

                                    {{-- =============== BARIS 3: REALISASI OUTPUT =============== --}}
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-3 py-3 font-bold text-purple-900 bg-purple-50 text-xs sticky-col-2 sticky-col-shadow align-middle">
                                            Realisasi Output
                                        </td>
                                        
                                        @for($i=1; $i<=4; $i++) 
                                            <td class="px-3 py-3 text-gray-400 bg-gray-50/30 border-r align-middle">
                                                    {{ $row['data']['row3_blue'][$i] ?? 0 }}
                                            </td> 
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-3 py-3 text-emerald-700 border-r align-middle bg-emerald-50/20 font-bold">
                                                {{ $row['data']['row3_green'][$i] ?? 0 }}
                                            </td>
                                        @endfor

                                        {{-- CAPAIAN OUTPUT (ROWSPAN 2) --}}
                                        {{-- Gunakan group-hover juga --}}
                                        @for($i=1; $i<=4; $i++)
                                            @php $val = $row['capaian']['output']['tw'][$i] ?? 0; @endphp
                                            <td rowspan="2" class="px-3 py-3 border-r align-middle bg-indigo-50/10 border-b group-hover:bg-indigo-50 transition-colors">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span>
                                            </td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++)
                                            @php $val = $row['capaian']['output']['thn'][$i] ?? 0; @endphp
                                            <td rowspan="2" class="px-3 py-3 bg-emerald-50/10 border-r last:border-r-0 align-middle border-b group-hover:bg-emerald-50 transition-colors">
                                                <span class="{{ $val >= 100 ? 'text-green-600 font-bold' : ($val > 0 ? 'text-blue-600' : 'text-gray-400') }}">{{ number_format($val, 0) }}%</span>
                                            </td>
                                        @endfor
                                    </tr>

                                    {{-- =============== BARIS 4: TARGET OUTPUT =============== --}}
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-300">
                                        <td class="px-3 py-3 font-bold text-gray-500 bg-white text-xs sticky-col-2 sticky-col-shadow align-middle group-hover:bg-gray-50">
                                            Target Output
                                        </td>
                                        
                                        @for($i=1; $i<=4; $i++)
                                            <td class="px-3 py-3 text-purple-800 border-r align-middle font-medium hover:bg-gray-50">
                                                {{ $row['data']['row4_blue'][$i] ?? 0 }}
                                            </td>
                                        @endfor
                                        @for($i=1; $i<=4; $i++) 
                                            <td class="px-3 py-3 text-gray-600 border-r align-middle bg-gray-50/30 hover:bg-gray-50 font-medium">
                                                {{ $row['data']['row4_green'][$i] ?? 0 }}
                                            </td> 
                                        @endfor
                                    </tr>

                                </tbody>
                            @empty
                                <tbody>
                                    <tr>
                                        <td colspan="22" class="px-4 py-12 text-center text-gray-500 italic">
                                            Belum ada data capaian kinerja untuk tahun ini.
                                        </td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>