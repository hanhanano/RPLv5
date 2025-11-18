<div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-lg border rounded-xl" 
     x-data="{
         tab: 'publikasi',
         triwulan: 1,
         loading: true,
         data: {
             publikasi: {
                 total: 0,
                 sedangBerlangsung: 0,
                 sudahSelesai: 0,
                 belumBerlangsung: 0
             },
             tahapan: {
                 total: 0,
                 belumBerlangsung:0,
                 sedangBerlangsung: 0,
                 sudahSelesai: 0,
                 tertunda: 0,
                 persentaseRealisasi: 0
             }
         },
         async fetchData() {
             this.loading = true;
             try {
                 const response = await fetch(`{{ route('daftarpublikasi') }}?triwulan=${this.triwulan}`, {
                     headers: {
                         'X-Requested-With': 'XMLHttpRequest',
                         'Accept': 'application/json'
                     }
                 });
                 const result = await response.json();
                 this.data = result;
             } catch (error) {
                 console.error('Error fetching data:', error);
                 alert('Gagal memuat data. Silakan coba lagi.');
             } finally {
                 this.loading = false;
             }
         }
     }"
     x-init="fetchData(); $watch('triwulan', () => fetchData())">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-4 border-b-2 border-gradient">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-blue-600">
                    <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M12.75 3a.75.75 0 0 1 .75-.75 8.25 8.25 0 0 1 8.25 8.25.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3Z" clip-rule="evenodd" />
                </svg>
                Statistik Dashboard
            </h2>
            <p class="text-sm text-gray-500 mt-1">Rekapitulasi data berdasarkan triwulan dan jenis tampilan</p>
        </div>
        
        <!-- Dropdown Triwulan -->
        <div class="flex items-center gap-3 mt-4 md:mt-0">
            <label for="triwulan" class="text-sm font-medium text-gray-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600">
                    <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd" />
                </svg>
                Periode:
            </label>
            <select id="triwulan" 
                    class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-sm font-medium px-4 py-2" 
                    x-model="triwulan"
                    :disabled="loading">
                <option value="1">Triwulan I (Jan-Mar)</option>
                <option value="2">Triwulan II (Apr-Jun)</option>
                <option value="3">Triwulan III (Jul-Sep)</option>
                <option value="4">Triwulan IV (Okt-Des)</option>
            </select>
            
            <!-- Loading Spinner -->
            <span x-show="loading" class="ml-2">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </div>
    </div>
    
    <!-- Tab Button -->
    <div class="flex flex-col sm:flex-row gap-2 mb-6">
        <button 
            class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg font-medium transition-all duration-200 shadow-sm"
            :class="tab === 'publikasi' 
                ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md transform scale-105' 
                : 'bg-gray-100 text-gray-600 hover:bg-white hover:text-blue-700 hover:shadow'"
            @click="tab = 'publikasi'">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M15.988 3.012A2.25 2.25 0 0 1 18 5.25v6.5A2.25 2.25 0 0 1 15.75 14H13.5v3.25a.75.75 0 0 1-1.5 0V14H5.25A2.25 2.25 0 0 1 3 11.75v-6.5a2.25 2.25 0 0 1 2.012-2.238A2.25 2.25 0 0 1 7.25 1h1.5a2.25 2.25 0 0 1 2.238 2.012c.875.092 1.6.686 1.884 1.488H15A2.25 2.25 0 0 1 17.25 7v1.75h.75a.75.75 0 0 1 0 1.5h-.75V12c0 .414.336.75.75.75h.75a.75.75 0 0 1 0 1.5h-.75a2.25 2.25 0 0 1-2.25-2.25v-.5h-2.5V15a.75.75 0 0 1-1.5 0v-3.25h-7.5V15a.75.75 0 0 1-1.5 0v-3.25H3.75a.75.75 0 0 1 0-1.5h.75V9a.75.75 0 0 1 0-1.5h-.75a.75.75 0 0 1 0-1.5h.75a2.25 2.25 0 0 1 2.25-2.25h7.5a2.25 2.25 0 0 1 2.226 1.988ZM7.25 2.5a.75.75 0 0 0-.75.75v5.5c0 .414.336.75.75.75h1.5a.75.75 0 0 0 .75-.75v-5.5a.75.75 0 0 0-.75-.75h-1.5Z" clip-rule="evenodd" />
            </svg>
            <span class="hidden sm:inline">Rekapitulasi Publikasi</span>
            <span class="sm:hidden">Publikasi</span>
        </button>
        
        <button 
            class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg font-medium transition-all duration-200 shadow-sm"
            :class="tab === 'tahapan' 
                ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md transform scale-105' 
                : 'bg-gray-100 text-gray-600 hover:bg-white hover:text-blue-700 hover:shadow'"
            @click="tab = 'tahapan'">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path d="M15.98 1.804a1 1 0 0 0-1.96 0l-.24 1.192a1 1 0 0 1-.784.785l-1.192.238a1 1 0 0 0 0 1.962l1.192.238a1 1 0 0 1 .785.785l.238 1.192a1 1 0 0 0 1.962 0l.238-1.192a1 1 0 0 1 .785-.785l1.192-.238a1 1 0 0 0 0-1.962l-1.192-.238a1 1 0 0 1-.785-.785l-.238-1.192ZM6.949 5.684a1 1 0 0 0-1.898 0l-.683 2.051a1 1 0 0 1-.633.633l-2.051.683a1 1 0 0 0 0 1.898l2.051.684a1 1 0 0 1 .633.632l.683 2.051a1 1 0 0 0 1.898 0l.683-2.051a1 1 0 0 1 .633-.633l2.051-.683a1 1 0 0 0 0-1.898l-2.051-.683a1 1 0 0 1-.633-.633L6.95 5.684ZM13.949 13.684a1 1 0 0 0-1.898 0l-.184.551a1 1 0 0 1-.632.633l-.551.183a1 1 0 0 0 0 1.898l.551.183a1 1 0 0 1 .633.633l.183.551a1 1 0 0 0 1.898 0l.184-.551a1 1 0 0 1 .632-.633l.551-.183a1 1 0 0 0 0-1.898l-.551-.184a1 1 0 0 1-.633-.632l-.183-.551Z" />
            </svg>
            <span class="hidden sm:inline">Rekapitulasi Tahapan</span>
            <span class="sm:hidden">Tahapan</span>
        </button>
    </div>
    
    <!-- Konten Tab -->
    <div x-show="!loading" class="animate-fadeIn">
        <div x-show="tab === 'publikasi'" x-transition:enter="transition ease-out duration-200">
            @include('statistik.rekapitulasi-publikasi')
        </div>

        <div x-show="tab === 'tahapan'" x-transition:enter="transition ease-out duration-200">
            @include('statistik.rekapitulasi-tahapan')
        </div>
    </div>
    
    <!-- Loading State -->
    <div x-show="loading" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="animate-pulse bg-gray-200 h-32 rounded-lg"></div>
            <div class="animate-pulse bg-gray-200 h-32 rounded-lg"></div>
            <div class="animate-pulse bg-gray-200 h-32 rounded-lg"></div>
            <div class="animate-pulse bg-gray-200 h-32 rounded-lg"></div>
        </div>
        <p class="text-center text-gray-500 mt-4">Memuat data...</p>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.border-gradient {
    border-image: linear-gradient(to right, #3b82f6, #8b5cf6) 1;
}
</style>