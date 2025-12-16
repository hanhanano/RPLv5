{{-- Form Target Kinerja (Shared antara Modal Add & Edit) --}}
<div class="p-6 space-y-6" x-data="{
    plan_tahapan: 0,
    t_q1: 0, t_q2: 0, t_q3: 0, t_q4: 0,
    plan_output: 0,
    o_q1: 0, o_q2: 0, o_q3: 0, o_q4: 0,
    
    selectedReport: '',
    isMonthly: false,
    
    {{-- Daftar indikator spesial --}}
    specialIndicators: [
        'Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar',
        'Indeks Pelayanan Publik - Penilaian Mandiri',
        'Nilai SAKIP oleh Inspektorat',
        'Indeks Implementasi BerAKHLAK'
    ],
    
    {{-- Computed property untuk cek apakah indikator spesial --}}
    get isSpecial() {
        return this.specialIndicators.includes(this.selectedReport);
    }
}">
    {{-- BAGIAN 1: IDENTITAS --}}
    <div class="border-b pb-4">
        <h4 class="text-sm font-bold text-gray-700 mb-3">Identitas Laporan</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Tim/PIC --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tim/PIC</label>
                <select name="publication_pic" required class="w-full border-gray-300 rounded-lg text-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Tim --</option>
                    <option value="Tim Neraca">Tim Neraca</option>
                    <option value="Tim Produksi">Tim Produksi</option>
                    <option value="Tim Distribusi">Tim Distribusi</option>
                    <option value="Tim Sosial">Tim Sosial</option>
                    <option value="Tim IPDS">Tim IPDS</option>
                    <option value="Tim Tata Usaha">Tim Tata Usaha</option>
                </select>
            </div>

            {{-- Nama Sasaran/Laporan --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Sasaran/Laporan</label>
                <select name="publication_report" x-model="selectedReport" required 
                    class="w-full border-gray-300 rounded-lg text-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Laporan --</option>
                    
                    <optgroup label="Indikator Normal">
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
                    </optgroup>
                    
                    {{-- Tambah optgroup untuk 4 Indikator Spesial --}}
                    <optgroup label="Indikator Spesial">
                        <option value="Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar">Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar</option>
                        <option value="Indeks Pelayanan Publik - Penilaian Mandiri">Indeks Pelayanan Publik - Penilaian Mandiri</option>
                        <option value="Nilai SAKIP oleh Inspektorat">Nilai SAKIP oleh Inspektorat</option>
                        <option value="Indeks Implementasi BerAKHLAK">Indeks Implementasi BerAKHLAK</option>
                    </optgroup>
                    
                    <option value="other">Lainnya (Input Manual)</option>
                </select>
                <input type="text" name="publication_report_other" x-show="selectedReport === 'other'" 
                    placeholder="Masukkan nama laporan..."
                    class="mt-2 w-full border-gray-300 rounded-lg text-sm p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Nama Kegiatan --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Kegiatan</label>
                <input type="text" name="publication_name" required 
                    class="w-full border-gray-300 rounded-lg text-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Survei Angkatan Kerja Nasional">
            </div>
        </div>
    </div>

    {{-- Alert untuk Indikator Spesial --}}
    <div x-show="isSpecial" x-cloak 
        class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <p class="font-semibold text-amber-800">Indikator Spesial Terdeteksi</p>
                <p class="text-amber-700 mt-1">
                    Untuk indikator spesial, target dan realisasi dihitung berdasarkan <strong>POIN</strong> (bukan jumlah dokumen). 
                    Target per triwulan bisa berbeda. <strong>Realisasi poin diinput di halaman Daftar Publikasi.</strong>
                </p>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: TARGET TAHAPAN (Hanya untuk Indikator Normal) --}}
    <div x-show="!isSpecial" x-cloak class="border-b pb-4">
        <h4 class="text-sm font-bold text-blue-800 mb-3">Target Kinerja Tahapan</h4>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Total Tahapan</label>
                <input type="number" name="q1_plan" x-model="plan_tahapan" min="0" 
                    class="w-full border-gray-300 rounded-lg text-sm p-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Target TW I</label>
                <input type="number" name="q1_real" x-model="t_q1" min="0" 
                    class="w-full border-gray-300 rounded-lg text-sm p-2" placeholder="0">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Target TW II</label>
                <input type="number" name="q2_real" x-model="t_q2" min="0" 
                    class="w-full border-gray-300 rounded-lg text-sm p-2" placeholder="0">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Target TW III</label>
                <input type="number" name="q3_real" x-model="t_q3" min="0" 
                    class="w-full border-gray-300 rounded-lg text-sm p-2" placeholder="0">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Target TW IV</label>
                <input type="number" name="q4_real" x-model="t_q4" min="0" 
                    class="w-full border-gray-300 rounded-lg text-sm p-2" placeholder="0">
            </div>
        </div>
    </div>

    {{-- BAGIAN 3: TARGET OUTPUT/POIN (Unified untuk Normal & Spesial) --}}
    <div class="border-b pb-4">
        <h4 class="text-sm font-bold text-purple-800 mb-3" x-text="isSpecial ? 'Target Poin per Triwulan' : 'Target Kinerja Output'"></h4>
        <p class="text-xs text-gray-500 mb-3" x-show="isSpecial">Masukkan target poin yang harus dicapai di setiap triwulan (bisa berbeda-beda). <strong>Gunakan titik (.) untuk desimal.</strong></p>
        
        <div class="grid gap-4 items-end" :class="isSpecial ? 'grid-cols-2 md:grid-cols-4' : 'grid-cols-1 md:grid-cols-5'">
            {{-- Total Output (Hanya untuk Normal) --}}
            <div x-show="!isSpecial">
                <label class="block text-xs font-medium text-gray-600 mb-1">Total Output</label>
                <input type="number" name="output_plan" x-model="plan_output" min="0" 
                    class="w-full border-gray-300 rounded-lg text-sm p-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
            </div>
            
            {{-- Target TW I --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1" x-text="isSpecial ? 'Target TW I' : 'Target TW I'"></label>
                <input type="number" name="output_real_q1" x-model="o_q1" min="0" step="any"
                    :class="isSpecial ? 'border-purple-300 bg-purple-50 focus:ring-purple-500 focus:border-purple-500' : 'border-gray-300'"
                    class="w-full rounded-lg text-sm p-2" :placeholder="isSpecial ? '0.00' : '0'">
            </div>
            
            {{-- Target TW II --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Target TW II</label>
                <input type="number" name="output_real_q2" x-model="o_q2" min="0" step="any"
                    :class="isSpecial ? 'border-purple-300 bg-purple-50 focus:ring-purple-500 focus:border-purple-500' : 'border-gray-300'"
                    class="w-full rounded-lg text-sm p-2" :placeholder="isSpecial ? '0.00' : '0'">
            </div>
            
            {{-- Target TW III --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Target TW III</label>
                <input type="number" name="output_real_q3" x-model="o_q3" min="0" step="any"
                    :class="isSpecial ? 'border-purple-300 bg-purple-50 focus:ring-purple-500 focus:border-purple-500' : 'border-gray-300'"
                    class="w-full rounded-lg text-sm p-2" :placeholder="isSpecial ? '0.00' : '0'">
            </div>
            
            {{-- Target TW IV --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Target TW IV</label>
                <input type="number" name="output_real_q4" x-model="o_q4" min="0" step="any"
                    :class="isSpecial ? 'border-purple-300 bg-purple-50 focus:ring-purple-500 focus:border-purple-500' : 'border-gray-300'"
                    class="w-full rounded-lg text-sm p-2" :placeholder="isSpecial ? '0.00' : '0'">
            </div>
        </div>
        
        <div class="mt-2 space-y-1">
            <p class="text-xs text-gray-400 italic" x-show="isSpecial">* Gunakan titik (.) untuk pemisah desimal, contoh: 25.50</p>
            <p class="text-xs text-gray-400 italic" x-show="isSpecial">* Realisasi poin diinput melalui halaman Daftar Publikasi</p>
        </div>
    </div>

    {{-- BAGIAN 4: OPSI BULANAN (Hanya untuk Indikator Normal) --}}
    <div x-show="!isSpecial" x-cloak class="monthly-options-wrapper">
        <div class="flex items-center gap-2 mb-3">
            <input type="checkbox" name="is_monthly" id="is_monthly" x-model="isMonthly" value="1"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <label for="is_monthly" class="text-sm font-medium text-gray-700">Generate Publikasi Bulanan</label>
        </div>
        
        <div x-show="isMonthly" x-cloak class="grid grid-cols-3 md:grid-cols-6 gap-2 p-3 bg-gray-50 rounded-lg">
            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $i => $bulan)
            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                <input type="checkbox" name="months[]" value="{{ $i + 1 }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                {{ $bulan }}
            </label>
            @endforeach
        </div>
    </div>
</div>