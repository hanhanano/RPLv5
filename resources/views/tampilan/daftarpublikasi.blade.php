<div class="max-w-6xl mx-auto mt-6 p-6 bg-white bordershadow border rounded-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="text-lg font-semibold text-blue-900">Daftar Sasaran/Laporan Kinerja</h2>
            <p class="text-sm text-gray-500">Tabel ringkasan per sasaran/laporan per triwulan</p>
        </div>

        <div class="flex flex-wrap gap-2 justify-start sm:justify-end" x-data="{ open: false }">
            @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                
                <button 
                    @click="open = true" 
                    class="flex items-center justify-center gap-1 bg-emerald-600 text-white px-3 py-2 rounded-lg text-xs sm:text-sm shadow hover:bg-emerald-800 whitespace-nowrap min-w-[110px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                        </svg>
                    Laporan
                </button>
            @endif
            
            <a href="{{ route('publications.exportTable') }}"
                class="flex items-center justify-center gap-1 border text-gray-700 px-3 py-2 rounded-lg text-xs sm:text-sm shadow hover:text-white hover:bg-emerald-800 whitespace-nowrap min-w-[100px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                        <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                    </svg>
                    Unduh Excel
            </a>
            
            <div 
                x-show="open" 
                x-transition 
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                    
                    <button 
                        @click="open = false" 
                        class="absolute top-2 right-2 text-gray-600 hover:text-red-600">
                        ✖
                    </button>
                    
                    <h2 class="text-lg font-semibold">Formulir Tambah Sasaran/Laporan</h2>
                    <p class="text-xs text-gray-500 mb-4">Catatan: Nama Laporan dapat memiliki banyak Nama Kegiatan</p>
                    
                    {{-- Tampilkan error validation --}}
                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-100 border border-red-300">
                            <p class="text-sm font-semibold text-red-700 mb-1">Terjadi kesalahan:</p>
                            <ul class="list-disc ml-4 text-xs text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tampilkan error session --}}
                    @if (session('error'))
                        <div class="mb-4 p-3 rounded bg-red-100 border border-red-300">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    @endif
                    
                    <!-- Form -->
                    <form method="POST" action="{{ route('publications.store') }}"> 
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Laporan/Publikasi</label>
                            <select id="publication_report" name="publication_report" 
                                class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
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
                                <option value="other" {{ old('publication_report') == 'other' ? 'selected' : '' }}>
                                    -- Tambahkan Lainnya --
                                </option>
                            </select>
                        </div>

                        <div class="mb-3" id="other_input" style="display: {{ old('publication_report') == 'other' ? 'block' : 'none' }};">
                            <label class="block text-sm font-medium text-gray-700">Nama Laporan Lainnya</label>
                            <input type="text" name="publication_report_other" 
                                value="{{ old('publication_report_other') }}"
                                class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Tulis nama laporan lain di sini...">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                            <input type="text" name="publication_name" 
                                value="{{ old('publication_name') }}"
                                required
                                class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Contoh: Sakernas">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">PIC</label>
                            <select name="publication_pic" 
                                required
                                class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="">-- Pilih PIC --</option>
                                
                                @php
                                    $user = auth()->user();
                                    $teams = ['Umum', 'Produksi', 'Distribusi', 'Neraca', 'Sosial', 'IPDS'];
                                @endphp

                                @foreach($teams as $team)
                                    {{-- Ketua tim & operator hanya bisa pilih tim sendiri --}}
                                    @if($user && in_array($user->role, ['ketua_tim', 'operator']))
                                        @if($user->team === $team)
                                            <option value="{{ $team }}" selected>Tim {{ $team }}</option>
                                        @endif
                                    @else
                                        {{-- Admin bisa pilih semua --}}
                                        <option value="{{ $team }}" {{ old('publication_pic') == $team ? 'selected' : '' }}>
                                            Tim {{ $team }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div x-data="{ 
                            isMonthly: {{ old('is_monthly') ? 'true' : 'false' }}, 
                            selectAll: true 
                        }">
                            
                            <div class="mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                        name="is_monthly" 
                                        value="1"
                                        {{ old('is_monthly') ? 'checked' : '' }}
                                        x-model="isMonthly"
                                        class="mr-2 w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                                    <span class="text-sm font-medium text-gray-700">
                                        Generate Publikasi Bulanan
                                    </span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">
                                    Centang jika ingin membuat laporan untuk beberapa bulan sekaligus
                                </p>
                            </div>

                            <div x-show="isMonthly" 
                                x-transition
                                class="mb-3 border rounded-lg p-3 bg-gray-50">
                                
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Pilih Bulan yang Akan Di-generate
                                    </label>
                                    <button type="button" 
                                            @click="selectAll = !selectAll; 
                                                    document.querySelectorAll('input[name=\'months[]\']').forEach(cb => cb.checked = selectAll)"
                                            class="text-xs text-emerald-600 hover:text-emerald-800 underline">
                                        <span x-text="selectAll ? 'Bersihkan' : '✓ Semua'"></span>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                    @for($i = 1; $i <= 12; $i++)
                                        @php
                                            $monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                        @endphp
                                        <label class="flex items-center p-2 border rounded hover:bg-white cursor-pointer transition">
                                            <input type="checkbox" name="months[]" value="{{ $i }}" 
                                                {{ (is_array(old('months')) && in_array($i, old('months'))) || !old('months') ? 'checked' : '' }}
                                                class="mr-2 w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                                            <span class="text-sm">{{ $monthNames[$i-1] }}</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 gap-2">
                            <button type="button" @click="open = false" 
                                class="text-xs sm:text-sm bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                                Batal
                            </button>
                            <button type="submit" 
                                class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-4 mt-1 border rounded-lg">
        <input 
            type="text"
            id="search"
            placeholder="Cari Berdasarkan Nama Sasaran/Laporan"
            class="w-full  px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-800 text-xs border-y">
                    <tr class="border-y">
                        <th class="px-3 py-2" rowspan="2">No</th>
                        <th class="px-3 py-2" rowspan="2">Nama Sasaran/Laporan</th>
                        <th class="px-3 py-2" rowspan="2">Nama Kegiatan</th>
                        <th class="px-3 py-2" rowspan="2">PIC</th>
                        <th class="px-3 py-2" rowspan="2">Jenis</th>
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
                        <tr>
                            <td class="px-4 py-4 align-top" rowspan="2">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">
                                {{ $publication->publication_report }}
                            </td>
                            <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">
                                {{ $publication->publication_name }}
                            </td>
                            <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">
                                {{ $publication->publication_pic }}
                            </td>
                            
                            <td class="px-4 py-4 align-top bg-blue-50">
                                <div class="text-sm font-medium text-gray-700">
                                    {{ array_sum($publication->rekapFinals ?? []) }}/{{ array_sum($publication->rekapPlans ?? []) }} Tahapan
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 text-xs bg-blue-100 border rounded-full">
                                        {{ round($publication->progressKumulatif ?? 0) }}% selesai
                                    </span>
                                </div>
                            </td>

                            @for($q = 1; $q <= 4; $q++)
                                <td class="px-4 py-4 text-center bg-blue-50">
                                    @if(($publication->rekapPlans[$q] ?? 0) > 0)
                                        <div class="relative group inline-block">
                                            <div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block cursor-pointer hover:bg-blue-800 transition text-xs">
                                                {{ $publication->rekapPlans[$q] }} Rencana
                                            </div>
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                <p class="font-semibold text-gray-800 mb-1">Daftar Rencana:</p>
                                                <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto">
                                                    @foreach($publication->listPlans[$q] ?? [] as $item)
                                                        <li>{{ $item }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ number_format($publication->progressTriwulan[$q] ?? 0, 0) }}% selesai
                                        </p>
                                    @else
                                        <div class="px-3 py-1 text-black inline-block text-xs"> - </div>
                                        <p class="text-xs text-gray-500 mt-1">0% Direncanakan</p>
                                    @endif
                                </td>
                            @endfor

                            @for($q = 1; $q <= 4; $q++)
                                <td class="px-4 py-4 text-center bg-blue-50">
                                    @if(($publication->rekapFinals[$q] ?? 0) > 0)
                                        <div class="relative inline-block group">
                                            <div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block cursor-pointer text-xs">
                                                {{ $publication->rekapFinals[$q] }} Selesai
                                            </div>
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                <p class="font-semibold text-gray-800 mb-1">Daftar Realisasi:</p>
                                                <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto">
                                                    @foreach($publication->listFinals[$q] ?? [] as $item)
                                                        <li>{{ $item }}</li>
                                                    @endforeach
                                                </ul>
                                                @if(($publication->lintasTriwulan[$q] ?? 0) > 0)
                                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                                        <p class="text-xs text-orange-500 font-medium">
                                                            +{{ $publication->lintasTriwulan[$q] }} lintas triwulan:
                                                        </p>
                                                        <ul class="list-disc pl-4 text-xs">
                                                            @foreach($publication->listLintas[$q] ?? [] as $lintas)
                                                                <li>{{ $lintas['plan_name'] }} (Q{{ $lintas['from_quarter'] }} → Q{{ $lintas['to_quarter'] }})</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @if(($publication->lintasTriwulan[$q] ?? 0) > 0)
                                            <p class="text-xs text-orange-500 mt-1">+{{ $publication->lintasTriwulan[$q] }} lintas triwulan</p>
                                        @endif
                                    @else
                                        <div class="px-3 py-1 text-black inline-block text-xs"> - </div>
                                    @endif
                                </td>
                            @endfor

                            <td class="px-4 py-4 text-center" rowspan="2">
                                <div class="flex flex-col gap-1 w-full items-center">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('steps.index', $publication->slug_publication) }}" 
                                       class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm">
                                        <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
                                            <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" clip-rule="evenodd" />
                                        </svg> -->
                                        Detail
                                    </a>

                                    {{-- Tombol Output --}}
                                    <a href="{{ route('outputs.index', $publication->slug_publication) }}" 
                                       class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors shadow-sm">
                                        <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                                            <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                                        </svg> -->
                                        Output
                                    </a>

                                    {{-- Tombol Edit & Hapus (Hanya Ketua Tim & Admin) --}}
                                    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                                        
                                        <button onclick="openEditModal('{{ $publication->slug_publication }}', '{{ $publication->publication_report }}', '{{ $publication->publication_name }}', '{{ $publication->publication_pic }}')"
                                            class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
                                            <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                            </svg> -->
                                            Edit
                                        </button>

                                        <form action="{{ route('publications.destroy', $publication->slug_publication) }}" method="POST" 
                                            onsubmit="return confirm('Yakin hapus publikasi ini?')" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">
                                                <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                                </svg> -->
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <tr class="border-t border-gray-200">
                            
                            {{-- Logika Hitung Triwulan--}}
                            @php
                                // Inisialisasi array triwulan
                                $pubPlansQ = [1=>0, 2=>0, 3=>0, 4=>0];
                                $pubFinalsQ = [1=>0, 2=>0, 3=>0, 4=>0];
                                $pubListPlansQ = [1=>[], 2=>[], 3=>[], 4=>[]];
                                $pubListFinalsQ = [1=>[], 2=>[], 3=>[], 4=>[]];

                                // Loop data dari database baru (publicationPlans)
                                foreach($publication->publicationPlans as $plan) {
                                    if ($plan->plan_date) {
                                        // Tentukan triwulan berdasarkan bulan (1-3=Q1, 4-6=Q2, dst)
                                        $month = \Carbon\Carbon::parse($plan->plan_date)->month;
                                        $q = ceil($month / 3);
                                        
                                        // Tambah counter rencana
                                        $pubPlansQ[$q]++;
                                        $pubListPlansQ[$q][] = $plan->plan_name;

                                        // Cek apakah sudah realisasi (ada actual_date)
                                        if($plan->actual_date) {
                                            $pubFinalsQ[$q]++;
                                            $pubListFinalsQ[$q][] = $plan->plan_name;
                                        }
                                    }
                                }

                                // Hitung Total untuk Summary
                                $totalPubPlans = array_sum($pubPlansQ);
                                $totalPubFinals = array_sum($pubFinalsQ);
                                $percentPub = $totalPubPlans > 0 ? ($totalPubFinals / $totalPubPlans) * 100 : 0;
                            @endphp

                            <td class="px-4 py-4 align-top bg-purple-50">
                                <div class="text-sm font-medium text-gray-700">
                                    {{ $totalPubFinals }}/{{ $totalPubPlans }} Output
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 text-xs bg-purple-100 border rounded-full">
                                        {{ round($percentPub) }}% selesai
                                    </span>
                                </div>
                            </td>

                            @for($q = 1; $q <= 4; $q++)
                                <td class="px-4 py-4 text-center bg-purple-50">
                                    @if($pubPlansQ[$q] > 0)
                                        @php
                                            $qPercent = ($pubFinalsQ[$q] / $pubPlansQ[$q]) * 100;
                                        @endphp
                                        <div class="relative group inline-block">
                                            <div class="px-3 py-1 rounded-full bg-blue-700 text-white inline-block cursor-pointer hover:bg-blue-600 transition text-xs">
                                                {{ $pubPlansQ[$q] }} Rencana
                                            </div>
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                <p class="font-semibold text-gray-800 mb-1">Daftar Rencana:</p>
                                                <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto">
                                                    @foreach($pubListPlansQ[$q] as $itemName)
                                                        <li>{{ $itemName }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ number_format($qPercent, 0) }}% selesai
                                        </p>
                                    @else
                                        <div class="px-3 py-1 text-black inline-block text-xs"> - </div>
                                        <p class="text-xs text-gray-500 mt-1">0% Direncanakan</p>
                                    @endif
                                </td>
                            @endfor

                            @for($q = 1; $q <= 4; $q++)
                                <td class="px-4 py-4 text-center bg-purple-50">
                                    @if($pubFinalsQ[$q] > 0)
                                        <div class="relative inline-block group">
                                            <div class="px-3 py-1 rounded-full bg-green-600 text-white inline-block cursor-pointer text-xs">
                                                {{ $pubFinalsQ[$q] }} Selesai
                                            </div>
                                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                                                <p class="font-semibold text-gray-800 mb-1">Daftar Selesai:</p>
                                                <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto">
                                                    @foreach($pubListFinalsQ[$q] as $itemName)
                                                        <li>{{ $itemName }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @else
                                        <div class="px-3 py-1 text-black inline-block text-xs"> - </div>
                                    @endif
                                </td>
                            @endfor
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
                <div id="pageInfo" class="font-medium whitespace-nowrap">
                    Menghitung data...
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button id="btnPrev" class="p-1.5 rounded-md border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button id="btnNext" class="p-1.5 rounded-md border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
            
            <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

                <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative" 
                    x-data="{ editReport: '', editOther: false, editReportOther: '' }">

                    <button type="button" onclick="closeEditModal()" 
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                    ✖
                    </button>

                    <h2 class="text-lg font-semibold mb-4">Edit Publikasi</h2>

                    <form id="editForm" method="POST" action="{{ isset($publication) ? route('publications.update', $publication->slug_publication) : route('publications.store') }}">
                        @csrf
                        @if(isset($publication))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Laporan/Publikasi</label>
                            <select id="edit_publication_report" name="publication_report" 
                                    x-model="editReport"
                                    @change="editReport === 'other' ? editOther = true : editOther = false"
                                    class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm 
                                        focus:border-emerald-500 focus:ring-emerald-500 text-sm">

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
                            <option value="other"> -- Tambahkan Lainnya -- </option>
                            </select>
                        </div>

                        <div class="mb-3" x-show="editOther" x-transition>
                            <label class="block text-sm font-medium text-gray-700">Nama Laporan Lainnya</label>
                            <input type="text" name="publication_report_other" 
                                x-model="editReportOther"
                                class="w-full border rounded-lg px-3 py-2 mt-1 
                                        focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Tulis nama laporan lain di sini...">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium">Nama Kegiatan</label>
                            <input type="text" id="edit_name" name="publication_name" 
                                class="w-full border rounded-lg p-2">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">PIC</label>
                            <select name="publication_pic" 
                                required
                                class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="">-- Pilih PIC --</option>
                                
                                @php
                                    $user = auth()->user();
                                    $teams = ['Umum', 'Produksi', 'Distribusi', 'Neraca', 'Sosial', 'IPDS'];
                                @endphp

                                @foreach($teams as $team)
                                    {{-- Ketua tim & operator hanya bisa pilih tim sendiri --}}
                                    @if($user && in_array($user->role, ['ketua_tim', 'operator']))
                                        @if($user->team === $team)
                                            <option value="{{ $team }}" selected>Tim {{ $team }}</option>
                                        @endif
                                    @else
                                        {{-- Admin bisa pilih semua --}}
                                        <option value="{{ $team }}" {{ old('publication_pic') == $team ? 'selected' : '' }}>
                                            Tim {{ $team }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg mt-3">
                            Update
                        </button>
                    </form>
                </div>
            </div>

    </div>
</div>

<script>
    
function openEditModal(slug, report, name, pic) {
    let modal = document.getElementById('editModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    let form = document.getElementById('editForm');
    form.action = `/publications/${slug}`;
    
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    } else {
        methodInput.value = 'PUT';
    }

    document.getElementById('edit_name').value = name;
    document.getElementById('edit_pic').value = pic;

    if (window.Alpine) {
        const alpineElement = modal.querySelector('[x-data]');
        
        if (alpineElement && alpineElement._x_dataStack) {
            const alpineData = alpineElement._x_dataStack[0];
            
            alpineData.editReport = report;
            alpineData.editOther = (report === 'other');
            alpineData.editReportOther = '';
            
            if (window.Alpine.effect) {
                window.Alpine.effect(() => {
                    alpineData.editReport;
                });
            }
        } else {
            console.warn('Alpine component not found, using fallback');
            const select = document.getElementById('edit_publication_report');
            if (select) {
                select.value = report;
                
                const event = new Event('change', { bubbles: true });
                select.dispatchEvent(event);
            }
        }
    } else {
        console.error('Alpine.js not loaded');
        const select = document.getElementById('edit_publication_report');
        if (select) {
            select.value = report;
        }
    }
}

function closeEditModal() {
    let modal = document.getElementById('editModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Search functionality
document.getElementById('search').addEventListener('keyup', function() {
    let query = this.value;
    let tbody = document.getElementById('publication-table-body');

    fetch(`/publications/search?query=${query}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="15" class="text-center text-gray-500 py-4">
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = data.map((item, index) => `
                <tr>
                    <td class="px-4 py-4 align-top">${index + 1}</td>
                    <td class="px-4 py-4 align-top font-semibold text-gray-700">${item.publication_report}</td>
                    <td class="px-4 py-4 align-top font-semibold text-gray-700">${item.publication_name}</td>
                    <td class="px-4 py-4 align-top font-semibold text-gray-700">${item.publication_pic}</td>
                    ${generatePublicationColumn(item)}
                    <td class="px-4 py-4 align-top">
                        <div class="text-sm font-medium text-gray-700">
                            ${(Object.values(item.rekapFinals ?? {}).reduce((a,b)=>a+b,0))}/
                            ${(Object.values(item.rekapPlans ?? {}).reduce((a,b)=>a+b,0))} Tahapan
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 text-xs bg-gray-100 border rounded-full">
                                ${Math.round(item.progressKumulatif ?? 0)}% selesai
                            </span>
                        </div>
                    </td>
                    ${generateQuarterColumns(item)}
                    <td class="px-4 py-4 text-center">
                        ${generateActionButtons(item)}
                    </td>
                </tr>
            `).join('');

            currentPage = 1;  
            renderPagination();
        })
        .catch(err => {
            console.error('Error:', err);
            tbody.innerHTML = `
                <tr>
                    <td colspan="15" class="text-center text-red-500 py-4">
                        Terjadi kesalahan saat memuat data
                    </td>
                </tr>
            `;
        });
});

// Helper function untuk generate kolom triwulan
function generateQuarterColumns(item) {
    let html = '';
    
    // Kolom Rencana (1-4)
    for (let q = 1; q <= 4; q++) {
        const count = item.rekapPlans?.[q] ?? 0;
        const progress = Math.round(item.progressTriwulan?.[q] ?? 0);
        const plans = item.listPlans?.[q] || [];
        
        html += `
            <td class="px-4 py-4 text-center">
                ${count > 0 ? `
                    <div class="relative group inline-block">
                        <div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block cursor-pointer hover:bg-blue-800 transition">
                            ${count} Rencana
                        </div>
                        <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                            <p class="font-semibold text-gray-800 mb-1">Daftar Rencana:</p>
                            <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto">
                                ${plans.map(plan => `<li>${plan}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">${progress}% selesai</p>
                ` : `
                    <div class="px-3 py-1 text-black inline-block"> - </div>
                    <p class="text-xs text-gray-500 mt-1">0% Direncanakan</p>
                `}
            </td>
        `;
    }
    
    // Kolom Realisasi (1-4)
    for (let q = 1; q <= 4; q++) {
        const count = item.rekapFinals?.[q] ?? 0;
        const finals = item.listFinals?.[q] || [];
        const lintasCount = item.lintasTriwulan?.[q] ?? 0;
        const lintasList = item.listLintas?.[q] || [];
        
        html += `
            <td class="px-4 py-4 text-center">
                ${count > 0 ? `
                    <div class="relative inline-block group">
                        <div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block cursor-pointer">
                            ${count} Selesai
                        </div>
                        <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block bg-white border border-gray-200 shadow-lg rounded-lg p-2 w-64 text-sm text-gray-700 z-50">
                            <p class="font-semibold text-gray-800 mb-1">Daftar Realisasi:</p>
                            <ul class="list-disc pl-4 space-y-1 max-h-40 overflow-y-auto">
                                ${finals.map(final => `<li>${final}</li>`).join('')}
                            </ul>
                            ${lintasCount > 0 ? `
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <p class="text-xs text-orange-500 font-medium">
                                        +${lintasCount} lintas triwulan:
                                    </p>
                                    <ul class="list-disc pl-4 text-xs">
                                        ${lintasList.map(lintas => `
                                            <li>${lintas.plan_name} (${lintas.from_quarter} → ${lintas.to_quarter})</li>
                                        `).join('')}
                                    </ul>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    ${lintasCount > 0 ? `<p class="text-xs text-orange-500 mt-1">+${lintasCount} lintas triwulan</p>` : ''}
                ` : `
                    <div class="px-3 py-1 text-black inline-block"> - </div>
                `}
            </td>
        `;
    }
    
    return html;
}

// Helper function untuk generate tombol aksi
function generateActionButtons(item) {
    const escapeQuotes = (str) => (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    
    let html = `
        <a href="/publications/${item.slug_publication}/steps" 
           class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                <path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd" />
            </svg>
            Detail
        </a>
    `;

    if (window.userRole === 'ketua_tim' || window.userRole === 'admin') {
        html += `
            <button onclick="openEditModal('${item.slug_publication}', '${escapeQuotes(item.publication_report)}', '${escapeQuotes(item.publication_name)}', '${escapeQuotes(item.publication_pic)}')"
                class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13l-3.247.974.974-3.247a4.5 4.5 0 011.13-1.897l10.32-10.32z" />
                </svg>
                Edit
            </button>
            <button onclick="deletePublication('${item.slug_publication}')"
                class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd"/>
                </svg>
                Hapus
            </button>
        `;
    }

    return html;
}

// Delete function
function deletePublication(slug_publication) {
    if (!confirm("Yakin ingin menghapus publikasi ini?")) return;

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : '';

    if (!csrfToken) {
        alert('Error: CSRF token tidak ditemukan');
        return;
    }

    fetch(`/publications/${slug_publication}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => {
                throw new Error(err.message || `HTTP error! status: ${res.status}`);
            });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message || "Berhasil dihapus");
            const searchInput = document.getElementById('search');
            if (searchInput && searchInput.value) {
                searchInput.dispatchEvent(new Event('keyup'));
            } else {
                location.reload();
            }
        } else {
            throw new Error(data.message || 'Gagal menghapus');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Terjadi kesalahan: ' + err.message);
    });
}

// Skrip untuk modal tambah publikasi
document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("publication_report");
    const otherInput = document.getElementById("other_input");

    if (select && otherInput) {
        select.addEventListener("change", function () {
            if (this.value === "other") {
                otherInput.style.display = "block";
            } else {
                otherInput.style.display = "none";
            }
        });
    }

    const picSelect = document.querySelector('select[name="publication_pic"]');
    const form = picSelect?.closest('form');
    
    if (form && picSelect) {
        form.addEventListener('submit', function(e) {
            const selectedPic = picSelect.value;
            
            if (!selectedPic) {
                e.preventDefault();
                alert('Pilih PIC terlebih dahulu!');
                return false;
            }

            @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'operator']))
                const userTeam = '{{ auth()->user()->team }}';
                if (selectedPic !== userTeam) {
                    e.preventDefault();
                    alert('Anda hanya bisa membuat publikasi untuk Tim ' + userTeam);
                    return false;
                }
            @endif
        });
    }
});

// Tambahkan function baru untuk generate kolom publikasi
function generatePublicationColumn(item) {
    const pubPlansCount = item.publicationPlansCount || 0;
    const pubPlansList = item.publicationPlansList || [];
    
    if (pubPlansCount > 0) {
        const plansListHtml = pubPlansList.map(pp => `
            <li class="flex items-start gap-2 text-xs hover:bg-gray-50 p-1.5 rounded">
                <span class="text-base">${pp.hasFinal ? '✅' : '📝'}</span>
                <div class="flex-1 min-w-0">
                    <p class="font-medium truncate">${pp.name}</p>
                    <p class="text-gray-500">
                        ${pp.planDate ? 'Rencana: ' + pp.planDate : ''}
                        ${pp.actualDate ? '<br>Terbit: ' + pp.actualDate : ''}
                    </p>
                </div>
            </li>
        `).join('');
        
        return `
            <td class="px-4 py-4 align-top text-center">
                <div class="relative group inline-block">
                    <div class="px-3 py-1 rounded-full bg-purple-600 text-white inline-block cursor-pointer hover:bg-purple-700 transition">
                        📎 ${pubPlansCount} Output
                    </div>
                    <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 hidden group-hover:block 
                                bg-white border border-gray-200 shadow-xl rounded-lg p-3 w-72 text-sm 
                                text-gray-700 z-50">
                        <p class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M2 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Zm10.5 5.707a.5.5 0 0 0-.146-.353l-1-1a.5.5 0 0 0-.708 0L9.354 9.646a.5.5 0 0 1-.708 0L6.354 7.354a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0-.146.353V12a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V9.707ZM12 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" clip-rule="evenodd" />
                            </svg>
                            Daftar Output:
                        </p>
                        <ul class="space-y-1.5 max-h-48 overflow-y-auto">
                            ${plansListHtml}
                        </ul>
                    </div>
                </div>
            </td>
        `;
    } else {
        return `
            <td class="px-4 py-4 align-top text-center">
                <span class="text-gray-400 text-sm">Belum ada output</span>
            </td>
        `;
    }
}

    window.userRole = "{{ auth()->check() ? auth()->user()->role : 'viewer' }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

    document.addEventListener("DOMContentLoaded", function() {
        // --- 2. REFERENSI ELEMEN ---
        const elements = {
            tbody: document.getElementById('publication-table-body'),
            rowsSelect: document.getElementById('rowsPerPage'),
            btnPrev: document.getElementById('btnPrev'),
            btnNext: document.getElementById('btnNext'),
            pageInfo: document.getElementById('pageInfo'),
            search: document.getElementById('search')
        };

        // --- 3. STATE ---
        let state = {
            currentPage: 1,
            rowsPerPage: 10,
            dataHtml: [] // Menyimpan string HTML per ITEM (2 Baris sekaligus)
        };

        // --- 4. LOGIKA PAGINASI ---
        function updatePagination() {
            const totalItems = state.dataHtml.length;
            const totalPages = Math.ceil(totalItems / state.rowsPerPage);

            if (state.currentPage < 1) state.currentPage = 1;
            if (state.currentPage > totalPages && totalPages > 0) state.currentPage = totalPages;

            const start = (state.currentPage - 1) * state.rowsPerPage;
            const end = start + state.rowsPerPage;
            
            // Gabungkan array HTML menjadi satu string untuk ditampilkan
            const pageData = state.dataHtml.slice(start, end);
            elements.tbody.innerHTML = pageData.join('');

            // Update Info
            if (totalItems === 0) {
                elements.pageInfo.innerText = "0 data";
                elements.btnPrev.disabled = true;
                elements.btnNext.disabled = true;
            } else {
                const displayStart = start + 1;
                const displayEnd = Math.min(end, totalItems);
                elements.pageInfo.innerText = `${displayStart}-${displayEnd} dari ${totalItems}`;
                elements.btnPrev.disabled = (state.currentPage === 1);
                elements.btnNext.disabled = (state.currentPage >= totalPages);
            }
        }

        // --- 5. INIT LOAD (Data Awal dari Blade) ---
        function initLoad() {
            const rawRows = Array.from(elements.tbody.getElementsByTagName('tr'));
            state.dataHtml = [];

            // Cek jika kosong
            if (rawRows.length === 1 && rawRows[0].cells.length === 1) return;

            // Loop loncat 2 baris (Gabungkan Baris Putih & Ungu jadi 1 item)
            for (let i = 0; i < rawRows.length; i += 2) {
                if (rawRows[i] && rawRows[i+1]) {
                    state.dataHtml.push(rawRows[i].outerHTML + rawRows[i+1].outerHTML);
                }
            }
            updatePagination();
        }

        // --- 6. EVENT SEARCH (Data Baru dari AJAX) ---
        if (elements.search) {
            elements.search.addEventListener('keyup', function() {
                let query = this.value;

                fetch(`/publications/search?query=${query}`)
                    .then(res => res.json())
                    .then(data => {
                        state.dataHtml = []; // Reset

                        if (data.length === 0) {
                            elements.tbody.innerHTML = `<tr><td colspan="15" class="text-center py-4 text-gray-500">Tidak ada data ditemukan</td></tr>`;
                            elements.pageInfo.innerText = "0 data";
                            return;
                        }

                        // Generate HTML Item (Harus Sama Persis dengan Struktur Blade)
                        state.dataHtml = data.map((item, index) => {
                            let outputCount = item.filesCount || 0;
                            
                            // BARIS 1: Data Utama (Biru)
                            // Perhatikan rowspan="2" agar layout tidak hancur
                            let row1 = `
                            <tr>
                                <td class="px-4 py-4 align-top" rowspan="2">${index + 1}</td>
                                <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">${item.publication_report}</td>
                                <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">${item.publication_name}</td>
                                <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">${item.publication_pic}</td>
                                
                                <td class="px-4 py-4 align-top bg-blue-50">
                                    <div class="text-sm font-medium text-gray-700">
                                        ${(Object.values(item.rekapFinals ?? {}).reduce((a,b)=>a+b,0))}/
                                        ${(Object.values(item.rekapPlans ?? {}).reduce((a,b)=>a+b,0))} Tahapan
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 text-xs bg-blue-100 border rounded-full">
                                            ${Math.round(item.progressKumulatif ?? 0)}% selesai
                                        </span>
                                    </div>
                                </td>

                                ${generateQuarterColumns(item)}

                                <td class="px-4 py-4 text-center" rowspan="2">
                                    ${generateActionButtons(item)}
                                </td>
                            </tr>`;

                            // BARIS 2: Output (Ungu)
                            let row2 = `
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-4 align-top bg-purple-50">
                                    <div class="text-sm font-medium text-gray-700">
                                        ${outputCount} Output
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 text-xs bg-purple-100 border rounded-full">
                                            0% selesai
                                        </span>
                                    </div>
                                </td>
                                ${generateEmptyPurpleColumns()}
                            </tr>`;

                            return row1 + row2; // Gabungkan jadi satu item
                        });

                        state.currentPage = 1;
                        updatePagination();
                    })
                    .catch(err => console.error(err));
            });
        }

        // --- Helper Functions ---
        function generateQuarterColumns(item) {
            let html = '';
            for (let q = 1; q <= 4; q++) {
                let count = item.rekapPlans?.[q] || 0;
                let content = count > 0 
                    ? `<div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block text-xs">${count} Rencana</div>`
                    : `<div class="text-xs">-</div>`;
                html += `<td class="px-4 py-4 text-center bg-blue-50">${content}</td>`;
            }
            for (let q = 1; q <= 4; q++) {
                let count = item.rekapFinals?.[q] || 0;
                let content = count > 0 
                    ? `<div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block text-xs">${count} Selesai</div>`
                    : `<div class="text-xs">-</div>`;
                html += `<td class="px-4 py-4 text-center bg-blue-50">${content}</td>`;
            }
            return html;
        }

        function generateEmptyPurpleColumns() {
            let html = '';
            for(let i=0; i<8; i++) { 
                html += `<td class="px-4 py-4 text-center bg-purple-50"><div class="text-xs">-</div></td>`;
            }
            return html;
        }

        function generateActionButtons(item) {
            const escapeQuotes = (str) => (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            
            // Tombol Detail (Semua Role)
            let html = `
                <a href="/publications/${item.slug_publication}/steps" 
                class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg mb-1 justify-center items-center w-full">
                    Detail
                </a>
                <a href="/publications/${item.slug_publication}/outputs" 
                class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-purple-600 hover:bg-purple-700 rounded-lg mb-1 justify-center items-center w-full">
                    Output
                </a>
            `;

            // Tombol Edit & Hapus (Hanya Admin & Ketua Tim)
            if (window.userRole === 'ketua_tim' || window.userRole === 'admin') {
                html += `
                    <button onclick="openEditModal('${item.slug_publication}', '${escapeQuotes(item.publication_report)}', '${escapeQuotes(item.publication_name)}', '${escapeQuotes(item.publication_pic)}')"
                        class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg mb-1 justify-center items-center w-full">
                        Edit
                    </button>

                    <form action="/publications/${item.slug_publication}" method="POST" onsubmit="return confirm('Yakin hapus publikasi ini?')" class="w-full">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" 
                            class="flex gap-1 sm:text-xs px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg mb-1 justify-center items-center w-full">
                            Hapus
                        </button>
                    </form>
                `;
            }
            return html;
        }

        // --- Event Listeners Paginasi ---
        if(elements.rowsSelect) {
            elements.rowsSelect.addEventListener('change', function() {
                state.rowsPerPage = parseInt(this.value);
                state.currentPage = 1;
                updatePagination();
            });
        }
        if(elements.btnPrev) {
            elements.btnPrev.addEventListener('click', function() {
                if(state.currentPage > 1) { state.currentPage--; updatePagination(); }
            });
        }
        if(elements.btnNext) {
            elements.btnNext.addEventListener('click', function() {
                const maxPage = Math.ceil(state.dataHtml.length / state.rowsPerPage);
                if(state.currentPage < maxPage) { state.currentPage++; updatePagination(); }
            });
        }

        // Jalankan saat load
        initLoad();
    });
</script>

{{-- Auto-open modal jika ada error --}}
@if($errors->any() || session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalTrigger = document.querySelector('[x-data] button');
        if (modalTrigger) {
            modalTrigger.click();
        }
    });
</script>
@endif