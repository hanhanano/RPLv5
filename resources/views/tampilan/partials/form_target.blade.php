<div class="bg-white px-5 py-4 space-y-4" x-data="{ 
    plan_tahapan: '', 
    // Variabel untuk Realisasi Tahapan per Triwulan
    t_q1: '', t_q2: '', t_q3: '', t_q4: '',
    
    plan_output: '', 
    // Variabel untuk Realisasi Output per Triwulan
    o_q1: '', o_q2: '', o_q3: '', o_q4: '',

    isMonthly: false,
    selectAll: true
}">

    {{-- BARIS 1: TIM & JENIS LAPORAN (TIDAK BERUBAH) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Tim</label>
            <select name="publication_pic" required
                class="w-full rounded text-xs border-gray-300 px-2 py-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                @php
                    $user = auth()->user();
                    $teams = ['Umum', 'Produksi', 'Distribusi', 'Neraca', 'Sosial', 'IPDS'];
                @endphp
                @if($user->role === 'ketua_tim')
                    <option value="{{ $user->team }}" selected>Tim {{ $user->team }}</option>
                @else
                    <option value="">-- Pilih Tim --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team }}">Tim {{ $team }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Sasaran/Laporan (Induk)</label>
            <div x-data="{ reportType: 'select', customReport: '' }">
                <select x-model="reportType" name="publication_report"
                    class="w-full rounded text-xs border-gray-300 px-2 py-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm mb-1">
                    <option value="">-- Pilih Kategori --</option>
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
                    <option value="other">-- Tambahkan Lainnya (Manual) --</option>
                </select>

                <input type="text" name="publication_report_other" x-show="reportType === 'other'" 
                    placeholder="Tulis nama laporan baru..."
                    class="w-full rounded text-xs border-gray-300 px-2 py-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition-all">
            </div>
        </div>
    </div>

    {{-- BARIS 2: NAMA KEGIATAN (TIDAK BERUBAH) --}}
    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kegiatan/Target Dasar</label>
        <input type="text" name="publication_name" required placeholder="Contoh: Survei Angkatan Kerja"
            class="w-full rounded text-xs border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
    </div>

    {{-- BARIS 3: OPSI BULANAN (TIDAK BERUBAH) --}}
    <div class="monthly-options-wrapper bg-emerald-50 px-3 py-2 rounded border border-emerald-100">
        <div class="flex items-center justify-between">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_monthly" value="1" x-model="isMonthly"
                    class="mr-2 w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-bold text-emerald-900">Generate Target & Publikasi Bulanan</span>
            </label>
            
            <button type="button" x-show="isMonthly"
                    @click="selectAll = !selectAll; $el.closest('div').parentElement.querySelectorAll('input[type=checkbox][name^=months]').forEach(cb => cb.checked = selectAll)"
                    class="text-[10px] text-emerald-600 hover:text-emerald-800 underline font-bold">
                <span x-text="selectAll ? 'Bersihkan' : 'Pilih Semua'"></span>
            </button>
        </div>

        <div x-show="isMonthly" x-transition class="mt-2">
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                @php $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']; @endphp
                @foreach($monthNames as $index => $month)
                    <label class="flex items-center justify-center px-1 py-1 bg-white border rounded hover:bg-gray-50 cursor-pointer text-[10px]">
                        <input type="checkbox" name="months[]" value="{{ $index + 1 }}" checked
                            class="mr-1.5 w-3 h-3 text-emerald-600 rounded focus:ring-emerald-500">
                        {{ $month }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <hr class="border-gray-100">

    {{-- BARIS 4: TARGET & OUTPUT --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        
        {{-- KOLOM KIRI: DATA TAHAPAN --}}
        <div class="bg-blue-50 px-3 py-3 rounded border border-blue-100">
            <h4 class="text-xs font-bold text-blue-900 mb-2 flex items-center gap-1.5">
                <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span> Data Tahapan
            </h4>
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] text-gray-500 mb-0.5">Target (Per Triwulan)</label>
                    <input type="number" x-model="plan_tahapan" placeholder="Target per Q"
                        class="w-full rounded text-xs border-gray-300 px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    {{-- Hidden input mengisi otomatis ke 4 triwulan jika input manual per Q tidak diisi di controller --}}
                    <input type="hidden" name="q1_plan" :value="plan_tahapan">
                    <input type="hidden" name="q2_plan" :value="plan_tahapan">
                    <input type="hidden" name="q3_plan" :value="plan_tahapan">
                    <input type="hidden" name="q4_plan" :value="plan_tahapan">
                </div>

                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Realisasi (Per Triwulan)</label>
                    <div class="grid grid-cols-4 gap-1">
                        <input type="number" name="q1_real" x-model="t_q1" placeholder="Q1" class="w-full rounded text-[10px] border-gray-300 px-1 py-1.5 focus:ring-emerald-500 text-center">
                        <input type="number" name="q2_real" x-model="t_q2" placeholder="Q2" class="w-full rounded text-[10px] border-gray-300 px-1 py-1.5 focus:ring-emerald-500 text-center">
                        <input type="number" name="q3_real" x-model="t_q3" placeholder="Q3" class="w-full rounded text-[10px] border-gray-300 px-1 py-1.5 focus:ring-emerald-500 text-center">
                        <input type="number" name="q4_real" x-model="t_q4" placeholder="Q4" class="w-full rounded text-[10px] border-gray-300 px-1 py-1.5 focus:ring-emerald-500 text-center">
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DATA OUTPUT --}}
        <div class="bg-purple-50 px-3 py-3 rounded border border-purple-100">
            <h4 class="text-xs font-bold text-purple-900 mb-2 flex items-center gap-1.5">
                <span class="w-1.5 h-4 bg-purple-600 rounded-full"></span> Data Output
            </h4>
            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] text-gray-500 mb-0.5">Target Output</label>
                    <input type="number" name="output_plan" x-model="plan_output" placeholder="Target Output Total"
                        class="w-full rounded text-xs border-purple-200 px-2 py-1.5 focus:ring-purple-500 focus:border-purple-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Realisasi Output (Per Triwulan)</label>
                    <div class="grid grid-cols-4 gap-1">
                        {{-- TAMBAHKAN name="output_real_q..." AGAR TERBACA CONTROLLER --}}
                        <input type="number" name="output_real_q1" x-model="o_q1" placeholder="Q1" class="w-full rounded text-[10px] border-purple-200 px-1 py-1.5 focus:ring-purple-500 text-center">
                        <input type="number" name="output_real_q2" x-model="o_q2" placeholder="Q2" class="w-full rounded text-[10px] border-purple-200 px-1 py-1.5 focus:ring-purple-500 text-center">
                        <input type="number" name="output_real_q3" x-model="o_q3" placeholder="Q3" class="w-full rounded text-[10px] border-purple-200 px-1 py-1.5 focus:ring-purple-500 text-center">
                        <input type="number" name="output_real_q4" x-model="o_q4" placeholder="Q4" class="w-full rounded text-[10px] border-purple-200 px-1 py-1.5 focus:ring-purple-500 text-center">
                    </div>
                    
                    {{-- Hidden Input: Tetap ada untuk menyimpan Total (output_real) --}}
                    <input type="hidden" name="output_real" 
                        :value="(parseFloat(o_q1)||0) + (parseFloat(o_q2)||0) + (parseFloat(o_q3)||0) + (parseFloat(o_q4)||0)">
                </div>
            </div>
        </div>
    </div>
</div>