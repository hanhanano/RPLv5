<div class="bg-white px-5 py-4 space-y-4" x-data="{ 
    plan_tahapan: '', 
    real_tahapan: '', 
    plan_output: '', 
    real_output: '',
    isMonthly: false,
    selectAll: true
}">

    {{-- BARIS 1: TIM & JENIS LAPORAN --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Tim</label>
            {{-- UBAH name="team_name" JADI name="publication_pic" --}}
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
                {{-- UBAH name="report_name_select" JADI name="publication_report" --}}
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

                {{-- UBAH name="report_name_manual" JADI name="publication_report_other" --}}
                <input type="text" name="publication_report_other" x-show="reportType === 'other'" 
                    placeholder="Tulis nama laporan baru..."
                    class="w-full rounded text-xs border-gray-300 px-2 py-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition-all">
            </div>
        </div>
    </div>

    {{-- BARIS 2: NAMA KEGIATAN --}}
    <div>
        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kegiatan/Target Dasar</label>
        {{-- UBAH name="activity_name" JADI name="publication_name" --}}
        <input type="text" name="publication_name" required placeholder="Contoh: Survei Angkatan Kerja"
            class="w-full rounded text-xs border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
    </div>

    {{-- BARIS 3: OPSI BULANAN --}}
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
    {{-- (Bagian bawah ini tidak perlu diubah, name-nya sudah benar sesuai logika update) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-blue-50 px-3 py-3 rounded border border-blue-100">
            <h4 class="text-xs font-bold text-blue-900 mb-2 flex items-center gap-1.5">
                <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span> Data Tahapan
            </h4>
            <div class="space-y-2">
                <div>
                    <label class="block text-[10px] text-gray-500 mb-0.5">Target (Per Triwulan)</label>
                    <input type="number" x-model="plan_tahapan" placeholder="0"
                        class="w-full rounded text-xs border-gray-300 px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <input type="hidden" name="q1_plan" :value="plan_tahapan">
                    <input type="hidden" name="q2_plan" :value="plan_tahapan">
                    <input type="hidden" name="q3_plan" :value="plan_tahapan">
                    <input type="hidden" name="q4_plan" :value="plan_tahapan">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-0.5">Realisasi</label>
                    <input type="number" x-model="real_tahapan" placeholder="0"
                        class="w-full rounded text-xs border-gray-300 px-2 py-1.5 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                    <input type="hidden" name="q1_real" :value="real_tahapan">
                    <input type="hidden" name="q2_real" :value="real_tahapan">
                    <input type="hidden" name="q3_real" :value="real_tahapan">
                    <input type="hidden" name="q4_real" :value="real_tahapan">
                </div>
            </div>
        </div>

        <div class="bg-purple-50 px-3 py-3 rounded border border-purple-100">
            <h4 class="text-xs font-bold text-purple-900 mb-2 flex items-center gap-1.5">
                <span class="w-1.5 h-4 bg-purple-600 rounded-full"></span> Data Output
            </h4>
            <div class="space-y-2">
                <div>
                    <label class="block text-[10px] text-gray-500 mb-0.5">Target Output</label>
                    <input type="number" name="output_plan" x-model="plan_output" placeholder="0"
                        class="w-full rounded text-xs border-purple-200 px-2 py-1.5 focus:ring-purple-500 focus:border-purple-500 shadow-sm">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-0.5">Realisasi Output</label>
                    <input type="number" name="output_real" x-model="real_output" placeholder="0"
                        class="w-full rounded text-xs border-purple-200 px-2 py-1.5 focus:ring-purple-500 focus:border-purple-500 shadow-sm">
                </div>
            </div>
        </div>
    </div>
</div>