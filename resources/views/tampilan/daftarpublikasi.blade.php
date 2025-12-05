<div class="max-w-6xl mx-auto mt-6 p-6 bg-white bordershadow border rounded-lg">
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="text-lg font-semibold text-blue-900">Daftar Sasaran/Laporan Kinerja</h2>
            <p class="text-sm text-gray-500">Tabel ringkasan per sasaran/laporan per triwulan</p>
        </div>

        <div class="flex flex-wrap gap-2 justify-start sm:justify-end" x-data="{ open: false }">
            
            <a href="{{ route('publications.exportTable') }}"
                class="flex items-center justify-center gap-1 border text-gray-700 px-3 py-2 rounded-lg text-xs sm:text-sm shadow hover:text-white hover:bg-emerald-800 whitespace-nowrap min-w-[100px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                        <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                    </svg>
                    Unduh Excel
            </a>
            
            <!-- <div x-show="open" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                    <button @click="open = false" class="absolute top-2 right-2 text-gray-600 hover:text-red-600">✖</button>
                    <h2 class="text-lg font-semibold">Formulir Tambah Sasaran/Laporan</h2>
                    <p class="text-xs text-gray-500 mb-4">Catatan: Nama Laporan dapat memiliki banyak Nama Kegiatan</p>
                    
                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-100 border border-red-300">
                            <ul class="list-disc ml-4 text-xs text-red-600">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('publications.store') }}"> 
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Laporan/Publikasi</label>
                            <select id="publication_report" name="publication_report" class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="">-- Pilih Nama Laporan --</option>
                                <option value="Laporan Statistik Kependudukan dan Ketenagakerjaan">Laporan Statistik Kependudukan dan Ketenagakerjaan</option>
                                <option value="Laporan Statistik Statistik Kesejahteraan Rakyat">Laporan Statistik Statistik Kesejahteraan Rakyat</option>
                                <option value="Laporan Statistik Ketahanan Sosial">Laporan Statistik Ketahanan Sosial</option>
                                <option value="Laporan Statistik Tanaman Pangan">Laporan Statistik Tanaman Pangan</option>
                                <option value="Laporan Statistik Peternakan, Perikanan, dan Kehutanan">Laporan Statistik Peternakan, Perikanan, dan Kehutanan</option>
                                <option value="Laporan Statistik Industri">Laporan Statistik Industri</option>
                                <option value="Laporan Statistik Distribusi">Laporan Statistik Distribusi</option>
                                <option value="Laporan Statistik Harga">Laporan Statistik Harga</option>
                                <option value="Laporan Statistik Keuangan, Teknologi Informasi, dan Pariwisata">Laporan Statistik Keuangan, Teknologi Informasi, dan Pariwisata</option>
                                <option value="Laporan Neraca Produksi">Laporan Neraca Produksi</option>
                                <option value="Laporan Neraca Pengeluaran">Laporan Neraca Pengeluaran</option>
                                <option value="Laporan Analisis dan Pengembangan Statistik">Laporan Analisis dan Pengembangan Statistik</option>
                                <option value="Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar">Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar</option>
                                <option value="Indeks Pelayanan Publik - Penilaian Mandiri">Indeks Pelayanan Publik - Penilaian Mandiri</option>
                                <option value="Nilai SAKIP oleh Inspektorat">Nilai SAKIP oleh Inspektorat</option>
                                <option value="Indeks Implementasi BerAKHLAK">Indeks Implementasi BerAKHLAK</option>
                                <option value="other" {{ old('publication_report') == 'other' ? 'selected' : '' }}>-- Tambahkan Lainnya --</option>
                            </select>
                        </div>

                        <div class="mb-3" id="other_input" style="display: {{ old('publication_report') == 'other' ? 'block' : 'none' }};">
                            <label class="block text-sm font-medium text-gray-700">Nama Laporan Lainnya</label>
                            <input type="text" name="publication_report_other" value="{{ old('publication_report_other') }}" class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Tulis nama laporan lain di sini...">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                            <input type="text" name="publication_name" value="{{ old('publication_name') }}" required class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Contoh: Sakernas">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">PIC</label>
                            <select name="publication_pic" required class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="">-- Pilih PIC --</option>
                                @php $user = auth()->user(); $teams = ['Umum', 'Produksi', 'Distribusi', 'Neraca', 'Sosial', 'IPDS']; @endphp
                                @foreach($teams as $team)
                                    @if($user && in_array($user->role, ['ketua_tim', 'operator']))
                                        @if($user->team === $team) <option value="{{ $team }}" selected>Tim {{ $team }}</option> @endif
                                    @else
                                        <option value="{{ $team }}" {{ old('publication_pic') == $team ? 'selected' : '' }}>Tim {{ $team }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div x-data="{ isMonthly: {{ old('is_monthly') ? 'true' : 'false' }}, selectAll: true }">
                            <div class="mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_monthly" value="1" {{ old('is_monthly') ? 'checked' : '' }} x-model="isMonthly" class="mr-2 w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                                    <span class="text-sm font-medium text-gray-700">Generate Publikasi Bulanan</span>
                                </label>
                            </div>
                            <div x-show="isMonthly" x-transition class="mb-3 border rounded-lg p-3 bg-gray-50">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Pilih Bulan</label>
                                    <button type="button" @click="selectAll = !selectAll; document.querySelectorAll('input[name=\'months[]\']').forEach(cb => cb.checked = selectAll)" class="text-xs text-emerald-600 hover:text-emerald-800 underline">
                                        <span x-text="selectAll ? 'Bersihkan' : '✓ Semua'"></span>
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                    @for($i = 1; $i <= 12; $i++)
                                        @php $monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; @endphp
                                        <label class="flex items-center p-2 border rounded hover:bg-white cursor-pointer transition">
                                            <input type="checkbox" name="months[]" value="{{ $i }}" {{ (is_array(old('months')) && in_array($i, old('months'))) || !old('months') ? 'checked' : '' }} class="mr-2 w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                                            <span class="text-sm">{{ $monthNames[$i-1] }}</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 gap-2">
                            <button type="button" @click="open = false" class="text-xs sm:text-sm bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Batal</button>
                            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div> -->
        </div>
    </div>

    <div class="mb-4 mt-1 border rounded-lg">
        <input type="text" id="search" placeholder="Cari Berdasarkan Nama Sasaran/Laporan" class="w-full px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-800 text-xs border-y">
                    <tr class="border-y">
                        <th class="px-3 py-2" rowspan="2">No</th>
                        <th class="px-3 py-2 min-w-[160px]" rowspan="2">Nama Sasaran/Laporan</th>
                        <th class="px-3 py-2 min-w-[130px]" rowspan="2">Nama Kegiatan</th>
                        <th class="px-3 py-2" rowspan="2">PIC</th>
                        <th class="px-3 py-2 min-w-[110px]" rowspan="2">Jenis</th>
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

                            {{-- ... kode rencana di atasnya ... --}}

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
                                    <a href="{{ route('steps.index', $publication->slug_publication) }}" class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm">Detail</a>
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

                        {{-- BARIS 3: Realisasi Output (Logika Lama) --}}
                        <tr>
                            @php
                                $pubPlansQ = [1=>0, 2=>0, 3=>0, 4=>0];
                                $pubFinalsQ = [1=>0, 2=>0, 3=>0, 4=>0];
                                $pubListPlansQ = [1=>[], 2=>[], 3=>[], 4=>[]];
                                $pubListFinalsQ = [1=>[], 2=>[], 3=>[], 4=>[]];
                                foreach($publication->publicationPlans as $plan) {
                                    if ($plan->plan_date) {
                                        $month = \Carbon\Carbon::parse($plan->plan_date)->month;
                                        $q = ceil($month / 3);
                                        $pubPlansQ[$q]++;
                                        $pubListPlansQ[$q][] = $plan->plan_name;
                                        if($plan->actual_date) {
                                            $pubFinalsQ[$q]++;
                                            $pubListFinalsQ[$q][] = $plan->plan_name;
                                        }
                                    }
                                }
                                $totalPubPlans = array_sum($pubPlansQ);
                                $totalPubFinals = array_sum($pubFinalsQ);
                                $percentPub = $totalPubPlans > 0 ? ($totalPubFinals / $totalPubPlans) * 100 : 0;
                            @endphp

                            <td class="px-4 py-4 align-top bg-purple-50">
                                <div class="text-sm font-medium text-gray-700">Realisasi Output</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $totalPubFinals }}/{{ $totalPubPlans }} Item</div>
                                <div class="mt-1">
                                    <span class="px-2 py-0.5 text-xs bg-purple-100 border rounded-full">{{ round($percentPub) }}% selesai</span>
                                </div>
                            </td>

                            @for($q = 1; $q <= 4; $q++)
                                <td class="px-4 py-4 text-center bg-purple-50 align-top">
                                    @if($pubPlansQ[$q] > 0)
                                        <div class="relative group inline-block">
                                            <div class="px-3 py-1 rounded-full bg-blue-700 text-white inline-block cursor-pointer hover:bg-blue-600 transition text-xs">
                                                {{ $pubPlansQ[$q] }} Rencana
                                            </div>
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                <p class="font-semibold text-gray-800 mb-1">Daftar Rencana:</p>
                                                <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto text-left">
                                                    @foreach($pubListPlansQ[$q] as $itemName) <li>{{ $itemName }}</li> @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @else
                                        <div class="px-3 py-1 text-gray-400 inline-block text-xs"> - </div>
                                    @endif
                                </td>
                            @endfor

                            @for($q = 1; $q <= 4; $q++)
                                <td class="px-4 py-4 text-center bg-purple-50 align-top">
                                    @if($pubFinalsQ[$q] > 0)
                                        <div class="relative inline-block group">
                                            <div class="px-3 py-1 rounded-full bg-green-600 text-white inline-block cursor-pointer text-xs">
                                                {{ $pubFinalsQ[$q] }} Selesai
                                            </div>
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                <p class="font-semibold text-gray-800 mb-1">Daftar Selesai:</p>
                                                <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto text-left">
                                                    @foreach($pubListFinalsQ[$q] as $itemName) <li>{{ $itemName }}</li> @endforeach
                                                </ul>
                                            </div>
                                        </div>
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
        
        <!-- {{-- {{-- Edit Modal --}}
        <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
             <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative" x-data="{ editReport: '', editOther: false, editReportOther: '' }">
                <button type="button" onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">✖</button>
                <h2 class="text-lg font-semibold mb-4">Edit Publikasi</h2>
                <form id="editForm" method="POST" action="">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Nama Laporan/Publikasi</label>
                        <select id="edit_publication_report" name="publication_report" x-model="editReport" @change="editReport === 'other' ? editOther = true : editOther = false" class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            <option value="">-- Pilih Nama Laporan --</option>
                            <option value="Laporan Statistik Kependudukan dan Ketenagakerjaan">Laporan Statistik Kependudukan dan Ketenagakerjaan</option>
                            {{-- (Opsi lain disingkat agar ringkas, gunakan opsi lengkap Anda) --}}
                            <option value="other"> -- Tambahkan Lainnya -- </option>
                        </select>
                    </div>
                    <div class="mb-3" x-show="editOther" x-transition>
                        <label class="block text-sm font-medium text-gray-700">Nama Laporan Lainnya</label>
                        <input type="text" name="publication_report_other" x-model="editReportOther" class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium">Nama Kegiatan</label>
                        <input type="text" id="edit_name" name="publication_name" class="w-full border rounded-lg p-2">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">PIC</label>
                        <select id="edit_pic" name="publication_pic" required class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            <option value="">-- Pilih PIC --</option>
                            {{-- LOGIKA PEMBATASAN TIM --}}
                                @php
                                    $user = auth()->user();
                                    $teams = ['Umum', 'Produksi', 'Distribusi', 'Neraca', 'Sosial', 'IPDS'];
                                @endphp

                                @if($user->role === 'ketua_tim')
                                    {{-- Jika Ketua Tim: Hanya tampilkan timnya sendiri & otomatis selected --}}
                                    <option value="{{ $user->team }}" selected>Tim {{ $user->team }}</option>
                                @else
                                    {{-- Jika Admin: Tampilkan semua pilihan --}}
                                    <option value="">-- Pilih Tim --</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team }}">Tim {{ $team }}</option>
                                    @endforeach
                                @endif
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg mt-3">Update</button>
                </form>
            </div>
        </div> --}} -->

    </div>
</div>

<script>
// // (Bagian Open/Close Modal Edit Tetap Sama)
// function openEditModal(slug, report, name, pic) {
//     let modal = document.getElementById('editModal');
//     modal.classList.remove('hidden'); modal.classList.add('flex');
//     let form = document.getElementById('editForm');
//     form.action = `/publications/${slug}`;
//     document.getElementById('edit_name').value = name;
//     document.getElementById('edit_pic').value = pic;
//     // Alpine Logic untuk Edit Report (Tetap sama)
//     const alpineElement = modal.querySelector('[x-data]');
//     if (alpineElement && alpineElement._x_dataStack) {
//         const alpineData = alpineElement._x_dataStack[0];
//         alpineData.editReport = report;
//         alpineData.editOther = (report === 'other');
//     }
// }
// function closeEditModal() {
//     let modal = document.getElementById('editModal');
//     modal.classList.add('hidden'); modal.classList.remove('flex');
// }

// Search Logic Updated for 4 Rows
document.getElementById('search').addEventListener('keyup', function() {
    let query = this.value;
    let tbody = document.getElementById('publication-table-body');

    fetch(`/publications/search?query=${query}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="15" class="text-center text-gray-500 py-4">Tidak ada data ditemukan</td></tr>`;
                return;
            }

            // Generate HTML HTML sesuai struktur 4 Baris
            state.dataHtml = data.map((item, index) => {
                let outputCount = item.filesCount || 0;
                
                // BARIS 1: Realisasi Tahapan (Blue)
                let row1 = `
                <tr class="border-t border-gray-200">
                    <td class="px-4 py-4 align-top" rowspan="4">${index + 1}</td>
                    <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="4">${item.publication_report}</td>
                    <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="4">${item.publication_name}</td>
                    <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="4">${item.publication_pic}</td>
                    
                    <td class="px-4 py-4 align-top bg-blue-50">
                        <div class="text-sm font-medium text-gray-700">Realisasi Tahapan</div>
                        <div class="text-xs text-gray-500 mt-1">
                            ${(Object.values(item.rekapFinals ?? {}).reduce((a,b)=>a+b,0))}/${(Object.values(item.rekapPlans ?? {}).reduce((a,b)=>a+b,0))} Item
                        </div>
                        <div class="mt-1"><span class="px-2 py-0.5 text-xs bg-blue-100 border rounded-full">${Math.round(item.progressKumulatif ?? 0)}% selesai</span></div>
                    </td>
                    ${generateQuarterColumns(item)}
                    <td class="px-4 py-4 text-center" rowspan="4">${generateActionButtons(item)}</td>
                </tr>`;

                // BARIS 2: Target Tahapan (Blue Light)
                let row2 = `
                <tr class="bg-blue-50/50 border-b border-white">
                    <td class="px-4 py-2 align-top bg-blue-100">
                        <div class="text-xs font-bold text-blue-900">Target Tahapan</div>
                    </td>
                    <td class="px-4 py-2 text-center text-blue-900 font-bold text-xs">${item.target_q1_plan ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-blue-900 font-bold text-xs">${item.target_q2_plan ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-blue-900 font-bold text-xs">${item.target_q3_plan ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-blue-900 font-bold text-xs">${item.target_q4_plan ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-emerald-700 font-bold text-xs">${item.target_q1_real ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-emerald-700 font-bold text-xs">${item.target_q2_real ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-emerald-700 font-bold text-xs">${item.target_q3_real ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-emerald-700 font-bold text-xs">${item.target_q4_real ?? '-'}</td>
                </tr>`;

                // BARIS 3: Realisasi Output (Purple)
                let row3 = `
                <tr>
                    <td class="px-4 py-4 align-top bg-purple-50">
                        <div class="text-sm font-medium text-gray-700">Realisasi Output</div>
                        <div class="text-xs text-gray-500 mt-1">${outputCount} Output</div>
                        <div class="mt-1"><span class="px-2 py-0.5 text-xs bg-purple-100 border rounded-full">0% selesai</span></div>
                    </td>
                    ${generateEmptyPurpleColumns()}
                </tr>`;

                // BARIS 4: Target Output (Purple Light)
                let row4 = `
                <tr class="bg-purple-50/50">
                    <td class="px-4 py-2 align-top bg-purple-100">
                        <div class="text-xs font-bold text-purple-900">Target Output</div>
                    </td>
                    <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">${item.target_output_plan ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">${item.target_output_plan ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">${item.target_output_plan ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">${item.target_output_plan ?? '-'}</td>
                    
                    <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">${item.target_output_real_q1 ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">${item.target_output_real_q2 ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">${item.target_output_real_q3 ?? '-'}</td>
                    <td class="px-4 py-2 text-center text-purple-900 font-bold text-xs">${item.target_output_real_q4 ?? '-'}</td>
                </tr>`;

                return row1 + row2 + row3 + row4;
            });

            state.currentPage = 1;
            updatePagination();
        });
});

window.userRole = "{{ auth()->check() ? auth()->user()->role : 'viewer' }}";
const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

document.addEventListener("DOMContentLoaded", function() {
    const elements = {
        tbody: document.getElementById('publication-table-body'),
        rowsSelect: document.getElementById('rowsPerPage'),
        btnPrev: document.getElementById('btnPrev'),
        btnNext: document.getElementById('btnNext'),
        pageInfo: document.getElementById('pageInfo'),
        search: document.getElementById('search')
    };

    window.state = { currentPage: 1, rowsPerPage: 10, dataHtml: [] };

    // Init Load: Mengambil data HTML yang dirender dari Blade (Per 4 Baris)
    function initLoad() {
        const rawRows = Array.from(elements.tbody.getElementsByTagName('tr'));
        state.dataHtml = [];
        
        // Loop setiap 4 baris (Row 1,2,3,4 adalah 1 item)
        for (let i = 0; i < rawRows.length; i += 4) {
            if (rawRows[i] && rawRows[i+1] && rawRows[i+2] && rawRows[i+3]) {
                state.dataHtml.push(
                    rawRows[i].outerHTML + 
                    rawRows[i+1].outerHTML + 
                    rawRows[i+2].outerHTML + 
                    rawRows[i+3].outerHTML
                );
            }
        }
        window.updatePagination();
    }

    window.updatePagination = function() {
        const totalItems = state.dataHtml.length;
        if (totalItems === 0) {
            elements.tbody.innerHTML = `<tr><td colspan="15" class="text-center text-gray-500 py-4">Tidak ada data ditemukan</td></tr>`;
            elements.pageInfo.innerText = "0 data";
            elements.btnPrev.disabled = true; elements.btnNext.disabled = true;
            return;
        }
        const totalPages = Math.ceil(totalItems / state.rowsPerPage);
        if (state.currentPage < 1) state.currentPage = 1;
        if (state.currentPage > totalPages) state.currentPage = totalPages;

        const start = (state.currentPage - 1) * state.rowsPerPage;
        const end = start + state.rowsPerPage;
        elements.tbody.innerHTML = state.dataHtml.slice(start, end).join('');
        elements.pageInfo.innerText = `${start + 1}-${Math.min(end, totalItems)} dari ${totalItems}`;
        elements.btnPrev.disabled = (state.currentPage === 1);
        elements.btnNext.disabled = (state.currentPage >= totalPages);
    }

    // Pagination Listeners
    elements.rowsSelect.addEventListener('change', function() { state.rowsPerPage = parseInt(this.value); state.currentPage = 1; updatePagination(); });
    elements.btnPrev.addEventListener('click', function() { if(state.currentPage > 1) { state.currentPage--; updatePagination(); } });
    elements.btnNext.addEventListener('click', function() { if(state.currentPage < Math.ceil(state.dataHtml.length / state.rowsPerPage)) { state.currentPage++; updatePagination(); } });

    initLoad();
});


// Helper: Quarter Columns untuk AJAX (Row 1)
function generateQuarterColumns(item) {
    let html = '';
    
    let totalAnnualPlan = 0;
    for(let i = 1; i <= 4; i++) {
        totalAnnualPlan += (item.rekapPlans?.[i] || 0);
    }
    // Render kolom Rencana
    for (let q = 1; q <= 4; q++) {
        let quarterInput = item.rekapPlans?.[q] || 0;
        let content = '';
        if (totalAnnualPlan > 0) {
            let tooltipText = quarterInput > 0 ? `Jadwal Q${q}` : `Bagian dari target tahunan`;
            content = `<div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block text-xs" title="${tooltipText}">${totalAnnualPlan} Rencana</div>`;
        } else {
            content = `<div class="text-xs text-gray-400">-</div>`;
        }
        html += `<td class="px-4 py-4 text-center bg-blue-50 align-top">${content}</td>`;
    }

    let cumulativeRealization = 0; // Reset penampung

    for (let q = 1; q <= 4; q++) {
        let currentReal = item.rekapFinals?.[q] || 0;
        
        cumulativeRealization += currentReal;

        let content = '';
        if (cumulativeRealization > 0) {
            // Tampilkan TOTAL kumulatif
            content = `<div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block text-xs" title="Total Selesai s.d Q${q}: ${cumulativeRealization}">${cumulativeRealization} Selesai</div>`;
            
            if (item.lintasTriwulan?.[q] > 0) {
                content += `<p class="text-xs text-orange-500 mt-1">+${item.lintasTriwulan[q]} Lintas</p>`;
            }
        } else {
            content = `<div class="text-xs text-gray-400">-</div>`;
        }
        html += `<td class="px-4 py-4 text-center bg-blue-50 align-top">${content}</td>`;
    }
    
    return html;
}

// Helper: Empty Purple Columns untuk AJAX (Row 3 - Realisasi Output)
function generateEmptyPurpleColumns() {
    let html = '';
    for(let i=0; i<8; i++) html += `<td class="px-4 py-4 text-center bg-purple-50 align-top"><div class="text-xs text-gray-400">-</div></td>`;
    return html;
}

// Helper: Action Buttons untuk AJAX
function generateActionButtons(item) {
    const escapeQuotes = (str) => (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    let html = `
        <a href="/publications/${item.slug_publication}/steps" class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg mb-1 justify-center items-center w-full">Detail</a>
        <a href="/publications/${item.slug_publication}/outputs" class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-purple-600 hover:bg-purple-700 rounded-lg mb-1 justify-center items-center w-full">Output</a>
    `;
    if (window.userRole === 'ketua_tim' || window.userRole === 'admin') {
        html += `
            <button onclick="openEditModal('${item.slug_publication}', '${escapeQuotes(item.publication_report)}', '${escapeQuotes(item.publication_name)}', '${escapeQuotes(item.publication_pic)}')" class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg mb-1 justify-center items-center w-full">Edit</button>
            <form action="/publications/${item.slug_publication}" method="POST" onsubmit="return confirm('Yakin hapus publikasi ini?')" class="w-full">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg mb-1 justify-center items-center w-full">Hapus</button>
            </form>
        `;
    }
    return html;
}
</script>

{{-- Auto-open modal jika error --}}
@if($errors->any() || session('error'))
<script>document.addEventListener('DOMContentLoaded', function() { const modalTrigger = document.querySelector('[x-data] button'); if (modalTrigger) modalTrigger.click(); });</script>
@endif