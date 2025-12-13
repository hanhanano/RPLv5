<div class="max-w-6xl mx-auto mt-6 p-6 bg-white bordershadow border rounded-lg">
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="text-lg font-semibold text-blue-900">Daftar Sasaran/Laporan Kinerja</h2>
            <p class="text-sm text-gray-500">Tabel ringkasan per sasaran/laporan per triwulan</p>
        </div>
    </div>

    <div class="mb-4 mt-1 border rounded-lg">
        {{-- Input Search Client-Side --}}
        <input type="text" id="searchInput" placeholder="Cari Berdasarkan Nama Sasaran/Laporan..." class="w-full px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-800 text-xs border-y">
                    <tr class="border-y">
                        <th class="px-3 py-2" rowspan="2">No</th>
                        <th class="px-3 py-2 min-w-[150px]" rowspan="2">Nama Sasaran/Laporan</th>
                        <th class="px-3 py-2 min-w-[130px]" rowspan="2">Nama Kegiatan</th>
                        <th class="px-3 py-2" rowspan="2">PIC</th>
                        <th class="px-3 py-2 min-w-[120px]" rowspan="2">Jenis</th>
                        <th class="px-3 py-2" colspan="4">Rencana Kegiatan</th>
                        <th class="px-3 py-2" colspan="4">Realisasi Kegiatan</th>
                        <th class="px-3 py-2" rowspan="2">Aksi</th>
                    </tr>
                    <tr class="bg-gray-100 text-xs whitespace-nowrap">
                        <th class="px-3 py-2 text-blue-800">Triwulan I</th>
                        <th class="px-3 py-2 text-blue-800">Triwulan II</th>
                        <th class="px-3 py-2 text-blue-800">Triwulan III</th>
                        <th class="px-3 py-2 text-blue-800">Triwulan IV</th>
                        <th class="px-3 py-2 text-emerald-800">Triwulan I</th>
                        <th class="px-3 py-2 text-emerald-800">Triwulan II</th>
                        <th class="px-3 py-2 text-emerald-800">Triwulan III</th>
                        <th class="px-3 py-2 text-emerald-800">Triwulan IV</th>
                    </tr>
                </thead>
                <tbody id="publication-table-body">
                    @if($publications->count())
                        @foreach($publications as $index => $publication)
                        
                        {{-- LOGIKA PERHITUNGAN --}}
                        @php
                            // A. TARGET TAHAPAN (Kembali ke Per Triwulan / Tidak Kumulatif)
                            $tp1 = $publication->teamTarget->q1_plan ?? '-';
                            $tp2 = $publication->teamTarget->q2_plan ?? '-';
                            $tp3 = $publication->teamTarget->q3_plan ?? '-';
                            $tp4 = $publication->teamTarget->q4_plan ?? '-';

                            // Target Realisasi (Manual Input)
                            $tr1 = $publication->teamTarget->q1_real ?? '-';
                            $tr2 = $publication->teamTarget->q2_real ?? '-';
                            $tr3 = $publication->teamTarget->q3_real ?? '-';
                            $tr4 = $publication->teamTarget->q4_real ?? '-';

                            // B. REALISASI TAHAPAN (KUMULATIF OTOMATIS)
                            // Ambil data per triwulan
                            $rawRt1 = $publication->rekapFinals[1] ?? 0;
                            $rawRt2 = $publication->rekapFinals[2] ?? 0;
                            $rawRt3 = $publication->rekapFinals[3] ?? 0;
                            $rawRt4 = $publication->rekapFinals[4] ?? 0;

                            // Hitung Kumulatif
                            $cumRt1 = $rawRt1;
                            $cumRt2 = $cumRt1 + $rawRt2;
                            $cumRt3 = $cumRt2 + $rawRt3;
                            $cumRt4 = $cumRt3 + $rawRt4;
                            
                            // Array untuk looping di view
                            $cumRt = [1 => $cumRt1, 2 => $cumRt2, 3 => $cumRt3, 4 => $cumRt4];
                        @endphp

                        {{-- BARIS 1: Identitas & Realisasi Tahapan --}}
                        <tr class="border-t border-gray-200">
                            {{-- Kolom Identitas dengan Rowspan 4 (Untuk mengakomodir Tahapan, Target Tahapan, Output, Target Output) --}}
                            <td class="px-4 py-4 align-top" rowspan="4">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="4">
                                {{ $publication->publication_report }}
                            </td>
                            <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="4">
                                {{ $publication->publication_name }}
                            </td>
                            <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="4">
                                {{ $publication->publication_pic }}
                            </td>
                            
                            {{-- Jenis: Realisasi Tahapan (Biru Default) --}}
                            <td class="px-4 py-4 align-top bg-blue-50">
                                <div class="text-sm font-medium text-gray-700">
                                    Realisasi Tahapan
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ array_sum($publication->rekapFinals ?? []) }}/{{ array_sum($publication->rekapPlans ?? []) }} Item
                                </div>
                                <div class="mt-1">
                                    <span class="px-2 py-0.5 text-xs bg-blue-100 border rounded-full">
                                        {{ round($publication->progressKumulatif ?? 0) }}% Selesai
                                    </span>
                                </div>
                            </td>

                            {{-- Rencana & Realisasi Tahapan (Dari StepsPlan) --}}
                            {{-- LOGIKA TOTAL TAHUNAN (FLAT) --}}
                            @php 
                                // Hitung total rencana dalam setahun
                                $totalAnnualPlan = array_sum($publication->rekapPlans ?? []);
                            @endphp

                            @for($q = 1; $q <= 4; $q++)
                                <td class="px-4 py-4 text-center bg-blue-50 align-top">
                                    @if($totalAnnualPlan > 0)
                                        <div class="relative group inline-block">
                                            {{-- TAMPILAN UTAMA: Selalu Tampilkan Total Setahun --}}
                                            <div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block cursor-pointer hover:bg-blue-800 transition text-xs">
                                                {{ $totalAnnualPlan }} Rencana
                                            </div>

                                            {{-- TOOLTIP: Detail Rencana --}}
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                @php $quarterInput = $publication->rekapPlans[$q] ?? 0; @endphp
                                                
                                                {{-- Tampilkan detail item jika ada input di triwulan ini --}}
                                                @if($quarterInput > 0)
                                                    <p class="font-semibold text-gray-800 mb-1">Jadwal Triwulan {{ $q }}:</p>
                                                    <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto text-left text-xs">
                                                        @foreach($publication->listPlans[$q] ?? [] as $item) 
                                                            <li>{{ $item }}</li> 
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-xs text-gray-500 italic">Tidak ada jadwal spesifik di Triwulan {{ $q }}, namun merupakan bagian dari total {{ $totalAnnualPlan }} target tahunan.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="px-3 py-1 text-gray-400 inline-block text-xs"> - </div>
                                    @endif
                                </td>
                            @endfor

                            {{-- REALISASI TAHAPAN (LOGIKA RUNNING SUM / PENJUMLAHAN BERTINGKAT) --}}
                            @php $cumulativeRealization = 0; @endphp

                            @for($q = 1; $q <= 4; $q++)
                                @php
                                    // Ambil input realisasi di triwulan ini
                                    $currentReal = $publication->rekapFinals[$q] ?? 0;
                                    
                                    // TAMBAHKAN ke total kumulatif
                                    $cumulativeRealization += $currentReal;
                                @endphp

                                <td class="px-4 py-4 text-center bg-blue-50 align-top">
                                    @if($cumulativeRealization > 0)
                                        <div class="relative inline-block group">
                                            {{-- TAMPILAN UTAMA: Tampilkan Total Kumulatif --}}
                                            <div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block cursor-pointer text-xs">
                                                {{ $cumulativeRealization }} Selesai
                                            </div>

                                            {{-- TOOLTIP: Detail Realisasi --}}
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                @if($currentReal > 0)
                                                    <p class="font-semibold text-gray-800 mb-1">Selesai di Q{{ $q }}:</p>
                                                    <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto text-left">
                                                        @foreach($publication->listFinals[$q] ?? [] as $item) <li>{{ $item }}</li> @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-xs text-gray-500 italic">Total akumulasi {{ $cumulativeRealization }} tahapan selesai sampai triwulan ini.</p>
                                                @endif

                                                {{-- Info Lintas Triwulan --}}
                                                @if(($publication->lintasTriwulan[$q] ?? 0) > 0)
                                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                                        <p class="text-xs text-orange-500 font-medium">+{{ $publication->lintasTriwulan[$q] }} lintas triwulan:</p>
                                                        <ul class="list-disc pl-4 text-xs text-left">
                                                            @foreach($publication->listLintas[$q] ?? [] as $lintas)
                                                                <li>{{ $lintas['plan_name'] }} (Q{{ $lintas['from_quarter'] }} → Q{{ $lintas['to_quarter'] }})</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- Tanda kecil jika ada lintas triwulan --}}
                                        @if(($publication->lintasTriwulan[$q] ?? 0) > 0)
                                            <p class="text-xs text-orange-500 mt-1">+{{ $publication->lintasTriwulan[$q] }} Lintas</p>
                                        @endif
                                    @else
                                        <div class="px-3 py-1 text-gray-400 inline-block text-xs"> - </div>
                                    @endif
                                </td>
                            @endfor

                            {{-- Kolom Aksi dengan Rowspan 4 --}}
                            <td class="px-4 py-4 text-center align-middle" rowspan="4">
                                <div class="flex flex-col gap-1 w-full items-center">
                                    <a href="{{ route('steps.index', $publication->slug_publication) }}" class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm">Tahapan</a>
                                    <a href="{{ route('outputs.index', $publication->slug_publication) }}" class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors shadow-sm">Output</a>
                                    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                                        <!-- <button onclick="openEditModal('{{ $publication->slug_publication }}', '{{ $publication->publication_report }}', '{{ $publication->publication_name }}', '{{ $publication->publication_pic }}')" class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">Edit</button> -->
                                        <form action="{{ route('publications.destroy', $publication->slug_publication) }}" method="POST" onsubmit="return confirm('Yakin hapus publikasi ini?')" class="w-full">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- BARIS 2: Target Kinerja Tahapan (NEW) --}}
                        <tr class="bg-blue-50/50 border-b border-white">
                             <td class="px-4 py-2 align-top bg-blue-100">
                                <div class="text-xs font-bold text-blue-900">Target Tahapan</div>
                            </td>
                            {{-- Target Tahapan: Plan Q1-Q4 --}}
                            <td class="px-4 py-2 text-center text-blue-900 font-bold text-xs">{{ $publication->teamTarget->q1_plan ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-blue-900 font-bold text-xs">{{ $publication->teamTarget->q2_plan ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-blue-900 font-bold text-xs">{{ $publication->teamTarget->q3_plan ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-blue-900 font-bold text-xs">{{ $publication->teamTarget->q4_plan ?? '-' }}</td>
                            
                            {{-- Target Tahapan: Realisasi Q1-Q4 (Dari tabel TeamTarget, bukan StepsPlan) --}}
                            <td class="px-4 py-2 text-center text-emerald-700 font-bold text-xs">{{ $publication->teamTarget->q1_real ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-emerald-700 font-bold text-xs">{{ $publication->teamTarget->q2_real ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-emerald-700 font-bold text-xs">{{ $publication->teamTarget->q3_real ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-emerald-700 font-bold text-xs">{{ $publication->teamTarget->q4_real ?? '-' }}</td>
                        </tr>

                        {{-- BARIS 3: Realisasi Output --}}
                        <tr>
                            @php
                                // 1. Inisialisasi Variabel
                                $pubPlansQ = [1=>0, 2=>0, 3=>0, 4=>0];
                                $pubFinalsQ = [1=>0, 2=>0, 3=>0, 4=>0];
                                $pubLintasQ = [1=>0, 2=>0, 3=>0, 4=>0]; // Variabel Lintas Output
                                
                                $pubListPlansQ = [1=>[], 2=>[], 3=>[], 4=>[]];
                                $pubListFinalsQ = [1=>[], 2=>[], 3=>[], 4=>[]];
                                $pubListLintasQ = [1=>[], 2=>[], 3=>[], 4=>[]]; // Detail Lintas untuk Tooltip

                                foreach($publication->publicationPlans as $plan) {
                                    $qPlan = null;
                                    $qActual = null;

                                    // 2. Hitung RENCANA
                                    if ($plan->plan_date) {
                                        $monthPlan = \Carbon\Carbon::parse($plan->plan_date)->month;
                                        $qPlan = ceil($monthPlan / 3);
                                        
                                        $pubPlansQ[$qPlan]++;
                                        $pubListPlansQ[$qPlan][] = $plan->plan_name;
                                    }

                                    // 3. Hitung REALISASI
                                    if ($plan->actual_date) {
                                        $monthActual = \Carbon\Carbon::parse($plan->actual_date)->month;
                                        $qActual = ceil($monthActual / 3);

                                        $pubFinalsQ[$qActual]++;
                                        $pubListFinalsQ[$qActual][] = $plan->plan_name;

                                        // 4. Hitung LINTAS TRIWULAN (Jika Realisasi > Rencana)
                                        if ($qPlan && $qActual > $qPlan) {
                                            $pubLintasQ[$qActual]++; // Dihitung di kuartal saat SELESAI
                                            
                                            // Simpan detail untuk tooltip
                                            $pubListLintasQ[$qActual][] = [
                                                'name' => $plan->plan_name,
                                                'from' => $qPlan,
                                                'to'   => $qActual
                                            ];
                                        }
                                    }
                                }

                                $totalPubPlans = array_sum($pubPlansQ);
                                $totalPubFinals = array_sum($pubFinalsQ);
                                $percentPub = $totalPubPlans > 0 ? ($totalPubFinals / $totalPubPlans) * 100 : 0;
                            @endphp

                            {{-- Kolom Judul Baris --}}
                            <td class="px-4 py-4 align-top bg-purple-50">
                                <div class="text-sm font-medium text-gray-700">Realisasi Output</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $totalPubFinals }}/{{ $totalPubPlans }} Item</div>
                                <div class="mt-1">
                                    <span class="px-2 py-0.5 text-xs bg-purple-100 border rounded-full">{{ round($percentPub) }}% selesai</span>
                                </div>
                            </td>

                            {{-- KOLOM RENCANA (TAMPILAN TOTAL TAHUNAN) --}}
                            @for($q = 1; $q <= 4; $q++)
                                <td class="px-4 py-4 text-center bg-purple-50 align-top">
                                    @if($totalPubPlans > 0)
                                        <div class="relative group inline-block">
                                            <div class="px-3 py-1 rounded-full bg-blue-700 text-white inline-block cursor-pointer hover:bg-blue-600 transition text-xs">
                                                {{ $totalPubPlans }} Rencana
                                            </div>
                                            {{-- Tooltip Rencana --}}
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                @if(count($pubListPlansQ[$q]) > 0)
                                                    <p class="font-semibold text-gray-800 mb-1">Jadwal Q{{ $q }}:</p>
                                                    <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto text-left text-xs">
                                                        @foreach($pubListPlansQ[$q] as $itemName) <li>{{ $itemName }}</li> @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-xs text-gray-500 italic">Bagian dari total {{ $totalPubPlans }} target output tahunan.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="px-3 py-1 text-gray-400 inline-block text-xs"> - </div>
                                    @endif
                                </td>
                            @endfor

                            {{-- KOLOM REALISASI (KUMULATIF + LINTAS) --}}
                            @php $cumulativeOutput = 0; @endphp 

                            @for($q = 1; $q <= 4; $q++)
                                @php 
                                    $currentQCount = $pubFinalsQ[$q] ?? 0; 
                                    $cumulativeOutput += $currentQCount;   
                                    $lintasCount = $pubLintasQ[$q] ?? 0; // Ambil jumlah lintas di Q ini
                                @endphp

                                <td class="px-4 py-4 text-center bg-purple-50 align-top">
                                    @if($cumulativeOutput > 0)
                                        <div class="relative inline-block group">
                                            {{-- Angka Kumulatif --}}
                                            <div class="px-3 py-1 rounded-full bg-green-600 text-white inline-block cursor-pointer text-xs">
                                                {{ $cumulativeOutput }} Selesai
                                            </div>

                                            {{-- Tooltip Detail --}}
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                @if($currentQCount > 0)
                                                    <p class="font-semibold text-gray-800 mb-1">Selesai di Q{{ $q }}:</p>
                                                    <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto text-left text-xs">
                                                        @foreach($pubListFinalsQ[$q] as $itemName) <li>{{ $itemName }}</li> @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-xs text-gray-500 italic">Total akumulasi {{ $cumulativeOutput }} output selesai.</p>
                                                @endif

                                                {{-- Info Detail Lintas di Tooltip --}}
                                                @if($lintasCount > 0)
                                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                                        <p class="text-xs text-orange-500 font-medium">+{{ $lintasCount }} Lintas Triwulan:</p>
                                                        <ul class="list-disc pl-4 text-xs text-left">
                                                            @foreach($pubListLintasQ[$q] ?? [] as $lintas)
                                                                <li>{{ $lintas['name'] }} (Q{{ $lintas['from'] }} → Q{{ $lintas['to'] }})</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Teks Lintas di Bawah Badge (Visual Utama) --}}
                                        @if($lintasCount > 0)
                                            <p class="text-xs text-orange-500 mt-1 font-semibold">+{{ $lintasCount }} Lintas</p>
                                        @endif
                                    @else
                                        <div class="px-3 py-1 text-gray-400 inline-block text-xs"> - </div>
                                    @endif
                                </td>
                            @endfor
                        </tr>

                        {{-- BARIS 4: Target Kinerja Output --}}
                        <tr class="bg-purple-50/50">
                             <td class="px-4 py-2 align-top bg-purple-100">
                                <div class="text-xs font-bold text-purple-900">Target Output</div>
                            </td>
                            {{-- Target Output: Plan (Tetap sama) --}}
                            <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">{{ $publication->teamTarget->output_plan ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">{{ $publication->teamTarget->output_plan ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">{{ $publication->teamTarget->output_plan ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">{{ $publication->teamTarget->output_plan ?? '-' }}</td>
                            
                            {{-- [PERBAIKAN DISINI] Target Output: Realisasi (Gunakan Data Per Triwulan) --}}
                            <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">{{ $publication->teamTarget->output_real_q1 ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">{{ $publication->teamTarget->output_real_q2 ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">{{ $publication->teamTarget->output_real_q3 ?? '-' }}</td>
                            <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">{{ $publication->teamTarget->output_real_q4 ?? '-' }}</td>
                        </tr>

                        @endforeach
                    @else
                        <tr>
                            <td colspan="15" class="text-center text-gray-500 py-4">Tidak ada data ditemukan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div> 

        {{-- Pagination & Modals (Sama seperti sebelumnya) --}}
        <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-lg flex flex-col sm:flex-row items-center justify-between gap-4 mt-2" id="pagination-container">
            <div class="flex items-center gap-4 text-sm text-gray-700 w-full sm:w-auto">
                <div class="flex items-center gap-2">
                    <span>Rows:</span>
                    <select id="rowsPerPage" class="border-gray-300 rounded text-sm py-1 pl-2 pr-8 focus:ring-blue-500 focus:border-blue-500 cursor-pointer shadow-sm">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div id="pageInfo" class="font-medium whitespace-nowrap">Menghitung data...</div>
            </div>
            <div class="flex items-center gap-2">
                <button id="btnPrev" class="p-1.5 rounded-md border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                </button>
                <button id="btnNext" class="p-1.5 rounded-md border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        </div>

    </div>
</div>

<script>

// Fungsi Helper untuk Modal (Jika diperlukan untuk tombol Edit/Hapus)
window.userRole = "{{ auth()->check() ? auth()->user()->role : 'viewer' }}";
const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

document.addEventListener("DOMContentLoaded", function() {
    // 1. Definisi Elemen
    const tbody = document.getElementById('publication-table-body');
    const rowsSelect = document.getElementById('rowsPerPage');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const pageInfo = document.getElementById('pageInfo');
    const searchInput = document.getElementById('searchInput');

    // Cek keberadaan tabel
    if (!tbody) return;

    // 2. Ambil Data Mentah (Semua baris TR)
    const rawRows = Array.from(tbody.querySelectorAll('tr'));
    let dataItems = [];

    // Cek apakah data kosong
    const isEmpty = rawRows.length === 0 || (rawRows.length === 1 && rawRows[0].innerText.includes("Tidak ada data"));

    if (!isEmpty) {
        // 3. GROUPING DATA: 1 Item Laporan = 4 Baris TR
        // Berbeda dengan team_target (2 baris), disini kita lompat 4 (i += 4)
        for (let i = 0; i < rawRows.length; i += 4) {
            // Pastikan ke-4 baris tersedia sebelum dikelompokkan
            if (rawRows[i] && rawRows[i+1] && rawRows[i+2] && rawRows[i+3]) {
                dataItems.push([
                    rawRows[i],     // Baris 1: Info & Realisasi Tahapan
                    rawRows[i+1],   // Baris 2: Target Tahapan
                    rawRows[i+2],   // Baris 3: Realisasi Output
                    rawRows[i+3]    // Baris 4: Target Output
                ]);
            }
        }
    }

    // State Awal
    let currentPage = 1;
    let rowsPerPage = 10;
    let filteredData = [...dataItems]; // Data aktif (awal = semua data)

    // --- FUNGSI UPDATE TAMPILAN (PAGINATION) ---
    function updatePagination() {
        const totalItems = filteredData.length;

        // Langkah 1: Sembunyikan SEMUA baris di tabel terlebih dahulu
        rawRows.forEach(row => row.style.display = 'none');

        // Jika data kosong (setelah search atau memang kosong)
        if (totalItems === 0) {
            pageInfo.innerText = "0 data ditemukan";
            btnPrev.disabled = true;
            btnNext.disabled = true;
            
            // Jika tabel aslinya memang kosong/pesan tidak ada data, tampilkan kembali pesan itu
            if (dataItems.length === 0 && rawRows.length > 0) {
                rawRows[0].style.display = '';
            }
            return;
        }

        // Hitung Total Halaman
        const totalPages = Math.ceil(totalItems / rowsPerPage);

        // Validasi Halaman Aktif
        if (currentPage < 1) currentPage = 1;
        if (currentPage > totalPages) currentPage = totalPages;

        // Hitung Batas Slice Array
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        // Ambil data yang harus tampil
        const pageItems = filteredData.slice(start, end);

        // Langkah 2: Tampilkan baris yang terpilih
        pageItems.forEach(group => {
            // Loop setiap baris dalam grup (ada 4 baris) dan tampilkan
            group.forEach(tr => {
                tr.style.display = ''; // Reset display ke default (table-row)
            });
        });

        // Update Info Pagination
        pageInfo.innerText = `${start + 1}-${Math.min(end, totalItems)} dari ${totalItems} data`;

        // Update Status Tombol
        btnPrev.disabled = (currentPage === 1);
        btnNext.disabled = (currentPage >= totalPages);
    }

    // --- EVENT LISTENERS ---

    // 1. Search (Real-time Filter)
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();

            // Filter dataItems
            filteredData = dataItems.filter(group => {
                // Kita cek teks pada Baris 1 (Nama Laporan, Kegiatan, PIC ada disitu)
                // group[0] adalah Baris pertama dari 4 baris
                const textContent = group[0].innerText.toLowerCase();
                return textContent.includes(query);
            });

            // Reset ke halaman 1 saat mencari
            currentPage = 1;
            updatePagination();
        });
    }

    // 2. Ganti Jumlah Rows per Page
    if (rowsSelect) {
        rowsSelect.addEventListener('change', function() {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            updatePagination();
        });
    }

    // 3. Tombol Previous
    if (btnPrev) {
        btnPrev.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });
    }

    // 4. Tombol Next
    if (btnNext) {
        btnNext.addEventListener('click', function() {
            const totalPages = Math.ceil(filteredData.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });
    }

    // Jalankan saat load pertama kali
    updatePagination();
});
</script>

{{-- Auto-open modal jika error --}}
@if($errors->any() || session('error'))
<script>document.addEventListener('DOMContentLoaded', function() { const modalTrigger = document.querySelector('[x-data] button'); if (modalTrigger) modalTrigger.click(); });</script>
@endif