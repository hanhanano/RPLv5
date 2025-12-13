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

    <main class="pt-12 md:pt-0"> 
        <div class="max-w-7xl mx-auto px-4 space-y-6">
            
            <!-- Statistik Dashboard -->
            @include('tampilan.statistik') 
            
            <!-- Daftar Publikasi Survei -->
            @include('tampilan.daftarpublikasi')
            
            {{-- Filter Panel --}}
            <div class="max-w-6xl mx-auto mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-md" 
                 x-data="{
                     showFilters: false,
                     filterTim: 'semua',
                     filterTriwulan: 'semua',
                     applyFilters() {
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

   {{-- PERBAIKAN 1: Menambahkan outputPlans & outputFinals agar terbaca JS --}}
    @php
        $chartPublicationsData = $publications->map(function($p) {
            return [
                'publication_pic' => $p->publication_pic,
                'rekapPlans' => $p->rekapPlans,
                'rekapFinals' => $p->rekapFinals,
                'tepatWaktu' => $p->tepatWaktu,
                'terlambat' => $p->terlambat,
                // Tambahkan dua baris ini:
                'outputPlans' => $p->outputPlans,
                'outputFinals' => $p->outputFinals,
            ];
        })->values();
    @endphp

<script>
    function downloadChart(chartId, filename) {
        const canvas = document.getElementById(chartId);
        if (canvas) {
            // Buat link download virtual
            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL('image/png');
            link.click();
        } else {
            console.error("Canvas tidak ditemukan: " + chartId);
        }
    }

    // --- 1. INISIALISASI VARIABEL GLOBAL ---
    let kinerjaChart = null;
    let tahapanChart = null;
    let ringChart = null;
    let timChart = null;

    // --- 2. DATA MASTER DARI CONTROLLER ---
    const originalData = {
        publications: @json($chartPublicationsData ?? []), 
        defaultPublikasi: @json($dataGrafikPublikasi ?? []),
        defaultTahapan: @json($dataGrafikBatang ?? []),
        defaultRing: @json($dataGrafikRing ?? []),
        defaultTim: @json($dataGrafikPerTim ?? [])
    };

    // --- 3. LOGIKA FILTER UTAMA ---
    function filterData(filterTim, filterTriwulan) {
        let filteredPubs = originalData.publications;

        // A. Filter Tim
        if (filterTim !== 'semua') {
            filteredPubs = filteredPubs.filter(pub => {
                let dbTeam = pub.publication_pic || "";
                return dbTeam.includes(filterTim); 
            });
        }

        // B. Siapkan Variabel Penampung
        let stats = {
            selesai: 0, berlangsung: 0, belum: 0, 
            rencana: [0, 0, 0, 0], realisasi: [0, 0, 0, 0], 
            perTim: {} // Key: Nama Tim, Value: {plans, tepat, terlambat}
        };
        
        // Penampung untuk Summary Chart 3 (Donut) & Teks
        let summary = {
            pubSelesai: 0, totalPub: 0, 
            tahapanSelesai: 0, totalTahapan: 0,
            outputSelesai: 0, totalOutput: 0
        };

        // Data lengkap untuk Kotak Kecil (selalu 4 triwulan meskipun difilter)
        let fullYearStats = {
            rencana: [0, 0, 0, 0],
            realisasi: [0, 0, 0, 0]
        };

        // C. Loop Data untuk Kalkulasi
        filteredPubs.forEach(pub => {
            let scopePlans = 0; let scopeFinals = 0;
            let scopeOutputPlans = 0; let scopeOutputFinals = 0;

            // Loop Triwulan 1-4
            for(let i=1; i<=4; i++) {
                let p = (pub.rekapPlans && pub.rekapPlans[i]) ? pub.rekapPlans[i] : 0;
                let f = (pub.rekapFinals && pub.rekapFinals[i]) ? pub.rekapFinals[i] : 0;
                let pOut = (pub.outputPlans && pub.outputPlans[i]) ? pub.outputPlans[i] : 0;
                let fOut = (pub.outputFinals && pub.outputFinals[i]) ? pub.outputFinals[i] : 0;
                
                let tw = (pub.tepatWaktu && pub.tepatWaktu[i]) ? pub.tepatWaktu[i] : 0;
                let tl = (pub.terlambat && pub.terlambat[i]) ? pub.terlambat[i] : 0;

                // Hitung data tahunan (untuk kotak kecil chart 2)
                fullYearStats.rencana[i-1] += p;
                fullYearStats.realisasi[i-1] += f;

                // --- LOGIKA FILTER TRIWULAN ---
                let isInTriwulan = (filterTriwulan === 'semua' || parseInt(filterTriwulan) === i);

                if (isInTriwulan) {
                    // Masukkan ke array Chart Batang
                    stats.rencana[i-1] += p;
                    stats.realisasi[i-1] += f;

                    // Update Summary Angka
                    summary.totalTahapan += p;
                    summary.tahapanSelesai += f;
                    summary.totalOutput += pOut;
                    summary.outputSelesai += fOut;
                    
                    scopePlans += p;
                    scopeFinals += f;
                    scopeOutputPlans += pOut;
                    scopeOutputFinals += fOut;

                    // Hitung Agregat Chart Tim
                    // Ambil nama tim bersih (hapus 'Tim ')
                    let timName = pub.publication_pic.replace('Tim ', '').trim(); 
                    if (!stats.perTim[timName]) {
                        stats.perTim[timName] = { plans: 0, tepat: 0, terlambat: 0 };
                    }
                    stats.perTim[timName].plans += p;
                    stats.perTim[timName].tepat += tw;
                    stats.perTim[timName].terlambat += tl;
                }
            }

            // D. Tentukan Status Publikasi (Dalam Scope Filter)
            let scopeActivity = scopePlans + scopeOutputPlans;
            if (scopeActivity > 0) {
                summary.totalPub++;
                
                let isStagesDone = (scopePlans === 0) ? true : (scopeFinals >= scopePlans);
                let isOutputsDone = (scopeOutputPlans === 0) ? true : (scopeOutputFinals >= scopeOutputPlans);

                if (isStagesDone && isOutputsDone) {
                    stats.selesai++;
                    summary.pubSelesai++;
                } else {
                    stats.berlangsung++;
                }
            } else {
                 // Jika filter triwulan aktif dan tidak ada kegiatan di triwulan itu, jangan dihitung 'Belum'
                 if (filterTriwulan === 'semua') stats.belum++;
            }
        });

        // E. Format Return untuk Chart.js
        let labelsBatang, dataRencana, dataRealisasi;
        if (filterTriwulan === 'semua') {
            labelsBatang = ['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4'];
            dataRencana = stats.rencana;
            dataRealisasi = stats.realisasi;
        } else {
            let idx = parseInt(filterTriwulan) - 1;
            labelsBatang = [`Triwulan ${filterTriwulan}`];
            dataRencana = [stats.rencana[idx]];
            dataRealisasi = [stats.realisasi[idx]];
        }

        // Siapkan data Tim untuk Chart
        let timLabels = Object.keys(stats.perTim);
        let timPlans = timLabels.map(t => stats.perTim[t].plans);
        let timTepat = timLabels.map(t => stats.perTim[t].tepat);
        let timTerlambat = timLabels.map(t => stats.perTim[t].terlambat);

        return {
            c1: [stats.selesai, stats.berlangsung, stats.belum],
            c2: { 
                labels: labelsBatang, rencana: dataRencana, realisasi: dataRealisasi,
                fullYear: fullYearStats // Return data lengkap untuk kotak kecil
            },
            c3: { 
                data: [summary.pubSelesai, summary.tahapanSelesai, summary.outputSelesai], 
                summary: summary 
            },
            c4: { 
                labels: timLabels, plans: timPlans, tepat: timTepat, terlambat: timTerlambat,
                rawTim: stats.perTim // Return raw object untuk update kotak tim
            }
        };
    }

    // --- 4. FUNGSI UPDATE TAMPILAN KE DOM ---
    function updateAllCharts(filterTim = 'semua', filterTriwulan = 'semua') {
        const newData = filterData(filterTim, filterTriwulan);

        // Update Chart 1 (Status)
        if(kinerjaChart) {
            kinerjaChart.data.datasets[0].data = newData.c1;
            kinerjaChart.update();
        }

        // Update Chart 2 (Batang & Kotak Kecil)
        if(tahapanChart) {
            tahapanChart.data.labels = newData.c2.labels;
            tahapanChart.data.datasets[0].data = newData.c2.rencana;
            tahapanChart.data.datasets[1].data = newData.c2.realisasi; 
            tahapanChart.update();

            // Update Kotak Kecil (Triwulan 1-4)
            for(let i=1; i<=4; i++) {
                // Ambil elemen container (Card), Angka, dan Persen
                const cardEl = document.getElementById(`tw-card-${i}`);
                const ratioEl = document.getElementById(`tw-ratio-${i}`);
                const percentEl = document.getElementById(`tw-percent-${i}`);
                
                if (cardEl && ratioEl && percentEl) {
                    // 1. Logika Update Angka
                    let r = newData.c2.fullYear.rencana[i-1];
                    let f = newData.c2.fullYear.realisasi[i-1];
                    let percent = (r > 0) ? Math.round((f / r) * 100) : 0;

                    ratioEl.innerText = `${f}/${r}`;
                    percentEl.innerText = `${percent}% selesai`;
                    
                    // Update Warna Teks
                    percentEl.className = "text-[10px] font-bold leading-none";
                    if (percent == 100) percentEl.classList.add('text-green-600');
                    else if (percent >= 67) percentEl.classList.add('text-yellow-600');
                    else if (percent >= 50) percentEl.classList.add('text-orange-600');
                    else percentEl.classList.add('text-red-600');

                    if (filterTriwulan === 'semua' || parseInt(filterTriwulan) === i) {
                        cardEl.style.display = ''; 
                    } else {
                        cardEl.style.display = 'none'; // Sembunyikan
                    }
                }
            }
        }

        // Update Chart 3 (Ringkasan & Teks)
        if(ringChart) {
            ringChart.data.datasets[0].data = newData.c3.data;
            ringChart.update();

            // Helper set text
            const setVal = (id, val) => { const el = document.getElementById(id); if(el) el.innerText = val ?? 0; };
            setVal('summary-pub-selesai', newData.c3.summary.pubSelesai);
            setVal('summary-pub-total', newData.c3.summary.totalPub);
            setVal('summary-tahap-selesai', newData.c3.summary.tahapanSelesai);
            setVal('summary-tahap-total', newData.c3.summary.totalTahapan);
            setVal('summary-output-selesai', newData.c3.summary.outputSelesai);
            setVal('summary-output-total', newData.c3.summary.totalOutput);
        }

        // Update Chart 4 (Kinerja Tim & Kotak Tim)
        if(timChart) {
            timChart.data.labels = newData.c4.labels;
            timChart.data.datasets[0].data = newData.c4.tepat;
            timChart.data.datasets[1].data = newData.c4.terlambat;
            let sisa = newData.c4.plans.map((p, i) => Math.max(0, p - (newData.c4.tepat[i] + newData.c4.terlambat[i])));
            timChart.data.datasets[2].data = sisa;
            timChart.update();

            // Update Kotak Tim
           const allTeams = originalData.defaultTim.labels;
            allTeams.forEach((teamName, idx) => {
                const cardEl = document.getElementById(`tim-card-${idx}`);
                const statsEl = document.getElementById(`tim-stats-${idx}`);
                const percentEl = document.getElementById(`tim-percent-${idx}`);

                if (cardEl && statsEl && percentEl) {
                    // 1. Logika Update Angka
                    const teamData = newData.c4.rawTim[teamName] || { plans: 0, tepat: 0, terlambat: 0 };
                    
                    const final = teamData.tepat + teamData.terlambat;
                    const plan = teamData.plans;
                    const percent = (plan > 0) ? Math.round((final / plan) * 100) : 0;

                    statsEl.innerText = `${final}/${plan} tahapan`;
                    percentEl.innerText = `${percent}%`;

                    // Update Warna Card
                    cardEl.className = "p-2 border rounded-lg transition-shadow hover:shadow";
                    if (percent >= 80) cardEl.classList.add('bg-green-100', 'text-green-700', 'border-green-300');
                    else if (percent >= 60) cardEl.classList.add('bg-yellow-100', 'text-yellow-700', 'border-yellow-300');
                    else cardEl.classList.add('bg-red-100', 'text-red-700', 'border-red-300');

                    // 2. Logika Sembunyikan Card (Fitur Baru)
                    // Tampilkan jika filter 'semua' ATAU jika nama tim sama persis dengan filter
                    if (filterTim === 'semua' || teamName === filterTim) {
                        cardEl.style.display = ''; // Tampilkan
                    } else {
                        cardEl.style.display = 'none'; // Sembunyikan
                    }
                }
            });
        }
    }

    // --- 5. INITIAL RENDER (Saat Halaman Dimuat) ---
    // Kode ini dipindahkan dari dashboard.blade.php ke sini agar scope-nya global
    document.addEventListener("DOMContentLoaded", function() {
        
        // Chart 1
        const ctxKinerja = document.getElementById('kinerjaChart');
        if (ctxKinerja) {
            kinerjaChart = new Chart(ctxKinerja, {
                type: 'bar',
                data: {
                    labels: originalData.defaultPublikasi.labels, 
                    datasets: [{
                        label: 'Jumlah',
                        data: originalData.defaultPublikasi.data, 
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], 
                        borderRadius: 4, barPercentage: 0.6
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { display: true, drawBorder: false } }, x: { grid: { display: false } } } }
            });
        }

        // Chart 2
        const ctxTahapan = document.getElementById('tahapanChart');
        if (ctxTahapan) {
            tahapanChart = new Chart(ctxTahapan, {
                type: 'bar',
                data: {
                    labels: originalData.defaultTahapan.labels, 
                    datasets: [
                        { label: 'Rencana', data: originalData.defaultTahapan.rencana, backgroundColor: '#1e40af', borderRadius: 4 },
                        { label: 'Realisasi', data: originalData.defaultTahapan.realisasi, backgroundColor: '#10b981', borderRadius: 4 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } } }
            });
        }

        // Chart 3
        const ctxRing = document.getElementById('ringChart');
        if (ctxRing) {
            ringChart = new Chart(ctxRing, {
                type: 'doughnut',
                data: {
                    labels: originalData.defaultRing.labels,
                    datasets: [{
                        data: originalData.defaultRing.data,
                        backgroundColor: ['#10b981', '#1e40af', '#9333ea'], 
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }

        // Chart 4
        const ctxTim = document.getElementById('timChart');
        if (ctxTim) {
            const timData = originalData.defaultTim;
            let sisaRencana = [];
            if(timData.plans) {
                sisaRencana = timData.plans.map((plan, i) => Math.max(0, plan - (timData.tepat_waktu[i] + timData.terlambat[i])));
            }

            timChart = new Chart(ctxTim, {
                type: 'bar',
                data: {
                    labels: timData.labels,
                    datasets: [
                        { label: 'Tepat Waktu', data: timData.tepat_waktu, backgroundColor: '#4472C4', barPercentage: 0.6 },
                        { label: 'Terlambat', data: timData.terlambat, backgroundColor: '#ED7D31', barPercentage: 0.6 },
                        { label: 'Sisa Target', data: sisaRencana, backgroundColor: '#cdcbcbff', barPercentage: 0.6 }
                    ]
                },
                options: {
                    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', axis: 'y', intersect: false },
                    scales: { x: { stacked: true, grid: { display: true } }, y: { stacked: true, grid: { display: false } } },
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } }
                }
            });
        }
    });

    // Event Listener dari AlpineJS (Filter Changed)
    window.addEventListener('filter-changed', (e) => {
        updateAllCharts(e.detail.tim, e.detail.triwulan);
    });
</script>

</html>
