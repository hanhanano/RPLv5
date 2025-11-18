<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard SIMONICA</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        window.userRole = '{{ auth()->user()->role ?? "guest" }}';
        window.userTeam = '{{ auth()->user()->team ?? "" }}';
    </script>
</head>
<body>
    <header class="fixed top-0 left-0 right-0 w-full bg-[#002b6b] z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center space-x-3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg/960px-Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg.png" 
                     class="h-8 me-3" alt="Logo BPS" />
            <span class="text-white font-semibold">BADAN PUSAT STATISTIK</span>
        </div>
    </header>

    <div>
        <x-navbar></x-navbar>
    </div>

    <main>
        <div class="max-w-7xl mx-auto px-4 pt-0 space-y-6">
            
            <!-- Statistik Dashboard -->
            @include('tampilan.statistik') 
            
            <!-- Daftar Publikasi Survei -->
            @include('tampilan.daftarpublikasi')
            
            {{-- TAMBAHAN: Filter Panel --}}
            <div class="max-w-6xl mx-auto mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-md" 
                 x-data="{
                     showFilters: false,
                     filterTim: 'semua',
                     filterTriwulan: 'semua',
                     applyFilters() {
                         // Trigger refresh charts dengan filter
                         this.refreshCharts();
                     },
                     resetFilters() {
                         this.filterTim = 'semua';
                         this.filterTriwulan = 'semua';
                         this.refreshCharts();
                     },
                     refreshCharts() {
                         // Logic untuk refresh chart ada di bawah
                         window.dispatchEvent(new CustomEvent('filter-changed', {
                             detail: {
                                 tim: this.filterTim,
                                 triwulan: this.filterTriwulan
                             }
                         }));
                     }
                 }">
                
                <!-- Header Filter -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600">
                            <path d="M18.75 12.75h1.5a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM12 6a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 6ZM12 18a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 18ZM3.75 6.75h1.5a.75.75 0 1 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM5.25 18.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 0 1.5ZM3 12a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3 12ZM9 3.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM12.75 12a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0ZM9 15.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                        </svg>
                        <h3 class="text-lg font-bold text-gray-800">Filter Data</h3>
                    </div>
                    
                    <button @click="showFilters = !showFilters" 
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        <span x-text="showFilters ? 'Sembunyikan' : 'Tampilkan'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" 
                             class="w-4 h-4 transition-transform"
                             :class="showFilters ? 'rotate-180' : ''">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- Filter Options -->
                <div x-show="showFilters" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- Filter Tim -->
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Tim</label>
                        <select x-model="filterTim" 
                                @change="applyFilters()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="semua">Semua Tim</option>
                            <option value="Umum">Tim Umum</option>
                            <option value="Produksi">Tim Produksi</option>
                            <option value="Distribusi">Tim Distribusi</option>
                            <option value="Neraca">Tim Neraca</option>
                            <option value="Sosial">Tim Sosial</option>
                            <option value="IPDS">Tim IPDS</option>
                        </select>
                    </div>
                    @else
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tim Anda</label>
                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800">
                            Tim {{ auth()->user()->team ?? 'Tidak Ada' }}
                        </div>
                    </div>
                    @endif

                    <!-- Filter Triwulan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Triwulan</label>
                        <select x-model="filterTriwulan" 
                                @change="applyFilters()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="semua">Semua Triwulan</option>
                            <option value="1">Triwulan I (Jan-Mar)</option>
                            <option value="2">Triwulan II (Apr-Jun)</option>
                            <option value="3">Triwulan III (Jul-Sep)</option>
                            <option value="4">Triwulan IV (Okt-Des)</option>
                        </select>
                    </div>

                    <!-- Tombol Reset -->
                    <div class="flex items-end">
                        <button @click="resetFilters()" 
                                class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd" />
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                </div>

                <!-- Info Teks -->
                <div x-show="showFilters" class="mt-3 text-xs text-gray-600 bg-blue-50 p-2 rounded">
                    <span class="font-semibold">💡 Tips:</span> Gunakan filter untuk melihat data spesifik per tim atau triwulan tertentu
                </div>
            </div>
            
            <!-- Grafik Ringkasan -->
            @include('tampilan.dashboard', [
                'dataGrafikBatang' => $dataGrafikBatang,
                'dataGrafikRing' => $dataGrafikRing,
                'dataTahapanSummary' => $dataTahapanSummary,
                'dataRingSummary' => $dataRingSummary,
                'dataGrafikPerTim' => $dataGrafikPerTim
            ])
        </div>
    </main>

    <footer class="bg-blue-950 text-white mt-8">
        <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg/960px-Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg.png" 
                     class="h-8 me-3" alt="Logo BPS" />
                <span class="font-semibold text-sm md:text-base">BADAN PUSAT STATISTIK</span>
            </div>
            <div class="mt-4 md:mt-0 text-xs md:text-sm text-center md:text-right">
                <p>© 2025 Badan Pusat Statistik Kota Bekasi</p>
                <p class="italic">Developed by Mahasiswa STIS</p>
            </div>
        </div>
    </footer>
</body>

<script>
    
let kinerjaChart, tahapanChart, ringChart, timChart;

// Data original dari backend
const originalData = {
    publikasi: @json($dataGrafikPublikasi),
    tahapan: @json($dataGrafikBatang),
    ring: @json($dataGrafikRing),
    tim: @json($dataGrafikPerTim),
    publications: @json($publications) 
};

// Fungsi untuk filter data
function filterData(filterTim, filterTriwulan) {
    let filteredPublications = originalData.publications;
    
    if (filterTim !== 'semua') {
        filteredPublications = filteredPublications.filter(pub => 
            pub.publication_pic === filterTim
        );
    }
    
    // Hitung ulang data untuk chart
    let chartPlans = [0, 0, 0, 0];
    let chartTepatWaktu = [0, 0, 0, 0];
    let chartTerlambat = [0, 0, 0, 0];
    let chartPerTim = {};
    
    // Status publikasi
    let selesai = 0, berlangsung = 0, belum = 0;
    
    filteredPublications.forEach(pub => {
        const totalPlans = Object.values(pub.rekapPlans || {}).reduce((a,b) => a+b, 0);
        const totalFinals = Object.values(pub.rekapFinals || {}).reduce((a,b) => a+b, 0);
        
        if (totalPlans === 0) {
            belum++;
        } else if (totalFinals === totalPlans) {
            selesai++;
        } else {
            berlangsung++;
        }
        
        [1, 2, 3, 4].forEach(q => {
            if (filterTriwulan !== 'semua' && q !== parseInt(filterTriwulan)) {
                return;
            }
            
            const idx = q - 1;
            chartPlans[idx] += pub.rekapPlans?.[q] || 0;
            chartTepatWaktu[idx] += pub.tepatWaktu?.[q] || 0;
            chartTerlambat[idx] += pub.terlambat?.[q] || 0;
        });
        
        const pic = pub.publication_pic;
        if (!chartPerTim[pic]) {
            chartPerTim[pic] = { plans: 0, tepat_waktu: 0, terlambat: 0 };
        }
        chartPerTim[pic].plans += totalPlans;
        chartPerTim[pic].tepat_waktu += Object.values(pub.tepatWaktu || {}).reduce((a,b) => a+b, 0);
        chartPerTim[pic].terlambat += Object.values(pub.terlambat || {}).reduce((a,b) => a+b, 0);
    });
    
    return {
        publikasi: {
            labels: ['Selesai', 'Berlangsung', 'Belum'],
            data: [selesai, berlangsung, belum]
        },
        tahapan: {
            labels: filterTriwulan === 'semua' 
                ? ['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4']
                : [`Triwulan ${filterTriwulan}`],
            rencana: filterTriwulan === 'semua' ? chartPlans : [chartPlans[parseInt(filterTriwulan)-1]],
            tepat_waktu: filterTriwulan === 'semua' ? chartTepatWaktu : [chartTepatWaktu[parseInt(filterTriwulan)-1]],
            terlambat: filterTriwulan === 'semua' ? chartTerlambat : [chartTerlambat[parseInt(filterTriwulan)-1]]
        },
        tim: {
            labels: Object.keys(chartPerTim),
            plans: Object.values(chartPerTim).map(t => t.plans),
            tepat_waktu: Object.values(chartPerTim).map(t => t.tepat_waktu),
            terlambat: Object.values(chartPerTim).map(t => t.terlambat)
        }
    };
}

// Fungsi untuk update semua charts
function updateAllCharts(filterTim = 'semua', filterTriwulan = 'semua') {
    const filtered = filterData(filterTim, filterTriwulan);
    
    kinerjaChart.data.labels = filtered.publikasi.labels;
    kinerjaChart.data.datasets[0].data = filtered.publikasi.data;
    kinerjaChart.update();
    
    tahapanChart.data.labels = filtered.tahapan.labels;
    tahapanChart.data.datasets[0].data = filtered.tahapan.rencana;
    tahapanChart.data.datasets[1].data = filtered.tahapan.tepat_waktu;
    tahapanChart.data.datasets[2].data = filtered.tahapan.terlambat;
    tahapanChart.update();
    
    if (timChart) {
        timChart.data.labels = filtered.tim.labels;
        timChart.data.datasets[0].data = filtered.tim.plans;
        timChart.data.datasets[1].data = filtered.tim.tepat_waktu;
        timChart.data.datasets[2].data = filtered.tim.terlambat;
        timChart.update();
    }
}

// Listen untuk filter changes
window.addEventListener('filter-changed', (e) => {
    updateAllCharts(e.detail.tim, e.detail.triwulan);
});

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    
    // Chart 1: Status Publikasi
    kinerjaChart = new Chart(document.getElementById('kinerjaChart'), {
        type: 'bar',
        data: {
            labels: @json($dataGrafikPublikasi['labels']),
            datasets: [{
                label: 'Jumlah Publikasi', 
                data: @json($dataGrafikPublikasi['data']), 
                backgroundColor: ['#2a9d90', '#f4a261', '#e76f51'],
                borderRadius: 8,
                barThickness: 40
            }]
        },
        options: { 
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Chart 2: Rencana vs Realisasi
    tahapanChart = new Chart(document.getElementById('tahapanChart'), {
        type: 'bar',
        data: {
            labels: @json($dataGrafikBatang['labels']),
            datasets: [
                { 
                    label: 'Rencana', 
                    data: @json($dataGrafikBatang['rencana']), 
                    backgroundColor: '#00458a',
                    borderRadius: 6,
                    barThickness: 20
                },
                { 
                    label: 'Tepat Waktu', 
                    data: @json($dataGrafikBatang['tepat_waktu']), 
                    backgroundColor: '#2a9d90',
                    borderRadius: 6,
                    barThickness: 20
                },
                { 
                    label: 'Terlambat', 
                    data: @json($dataGrafikBatang['terlambat']), 
                    backgroundColor: '#f97316',
                    borderRadius: 6,
                    barThickness: 20
                }
            ]
        },
        options: { 
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    callbacks: {
                        afterBody: function(context) {
                            const idx = context[0].dataIndex;
                            const rencana = @json($dataGrafikBatang['rencana'])[idx];
                            const tepat = @json($dataGrafikBatang['tepat_waktu'])[idx];
                            const terlambat = @json($dataGrafikBatang['terlambat'])[idx];
                            const total = tepat + terlambat;
                            const persen = rencana > 0 ? Math.round((total / rencana) * 100) : 0;
                            return `Total: ${total}/${rencana} (${persen}%)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Chart 3: Proporsi
    ringChart = new Chart(document.getElementById('ringChart'), {
        type: 'doughnut',
        data: {
            labels: @json($dataGrafikRing['labels']),
            datasets: [{
                data: @json($dataGrafikRing['data']), 
                backgroundColor: ['#2a9d90', '#00458a'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: { 
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 11 },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12
                }
            }
        }
    });

    // Chart 4: Kinerja Per Tim
    timChart = new Chart(document.getElementById('timChart'), {
        type: 'bar',
        data: {
            labels: @json($dataGrafikPerTim['labels']),
            datasets: [
                {
                    label: 'Rencana',
                    data: @json($dataGrafikPerTim['plans']),
                    backgroundColor: '#3b82f6',
                    borderRadius: 6
                },
                {
                    label: 'Tepat Waktu',
                    data: @json($dataGrafikPerTim['tepat_waktu']),
                    backgroundColor: '#10b981',
                    borderRadius: 6
                },
                {
                    label: 'Terlambat',
                    data: @json($dataGrafikPerTim['terlambat']),
                    backgroundColor: '#f59e0b',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    callbacks: {
                        afterBody: function(context) {
                            const idx = context[0].dataIndex;
                            const rencana = @json($dataGrafikPerTim['plans'])[idx];
                            const tepat = @json($dataGrafikPerTim['tepat_waktu'])[idx];
                            const terlambat = @json($dataGrafikPerTim['terlambat'])[idx];
                            const total = tepat + terlambat;
                            const persen = rencana > 0 ? Math.round((total / rencana) * 100) : 0;
                            return `Progress: ${total}/${rencana} (${persen}%)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>

</html>
