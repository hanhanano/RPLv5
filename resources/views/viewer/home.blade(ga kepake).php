<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <!-- Tailwind Elements -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body>
    <div>
        <x-navbar ></x-navbar>
    </div>

    <main>
        <div class="max-w-7xl mx-auto px-4 space-y-6">
            <!-- Statistik Dashboard -->
            <div class="max-w-6xl mx-auto mt-6 p-6 bg-white bordershadow border rounded-lg" x-data="{ tab: 'publikasi' }">
                <div class="flex justify-between items-center mb-4">
                    <!-- Header -->
                    <div>
                        <h2 class="text-lg font-semibold text-blue-900">Statistik Dashboard</h2>
                        <p class="text-sm text-gray-500">Rekapitulasi data berdasarkan triwulan dan jenis tampilan</p>
                    </div>
                    <!-- Dropdown Triwulan -->
                    <div>
                        <label for="triwulan" class="mr-2 text-sm font-medium text-gray-700">Triwulan:</label>
                        <select id="triwulan" class="rounded-md border text-sm" x-model="triwulan">
                            <option>Triwulan I</option>
                            <option>Triwulan II</option>
                            <option>Triwulan III</option>
                            <option>Triwulan IV</option>
                        </select>
                    </div>
                </div>
                <!-- Tab Button -->
                <div class="flex flex-col sm:flex-row border rounded overflow-hidden text-sm font-medium mb-4">
                    <button 
                        class="flex items-center justify-center gap-2 flex-1 py-2"
                        :class="tab === 'publikasi' ? 'bg-blue-100 text-blue-900' : 'bg-gray-100 text-gray-600 hover:bg-white hover:text-blue-900'"
                        @click="tab = 'publikasi'">
                        <!-- Ikon Home dari Heroicons -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M11.986 3H12a2 2 0 0 1 2 2v6a2 2 0 0 1-1.5 1.937V7A2.5 2.5 0 0 0 10 4.5H4.063A2 2 0 0 1 6 3h.014A2.25 2.25 0 0 1 8.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM10.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M3 6a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H3Zm1.75 2.5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5ZM4 11.75a.75.75 0 0 1 .75-.75h3.5a.75.75 0 0 1 0 1.5h-3.5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        </svg>
                        Rekapitulasi Publikasi
                    </button>
                    <button 
                        class="flex items-center justify-center gap-2 flex-1 py-2"
                        :class="tab === 'tahapan' ? 'bg-blue-100 text-blue-900' : 'bg-gray-100 text-gray-600 hover:bg-white hover:text-blue-900'"
                        @click="tab = 'tahapan'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M4 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H4Zm.75 7a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 1.5 0v-1.5A.75.75 0 0 0 4.75 9Zm2.5-1.75a.75.75 0 0 1 1.5 0v4a.75.75 0 0 1-1.5 0v-4Zm4-3.25a.75.75 0 0 0-.75.75v6.5a.75.75 0 0 0 1.5 0v-6.5a.75.75 0 0 0-.75-.75Z" clip-rule="evenodd" />
                        </svg>
                        Rekapitulasi Tahapan
                    </button>
                </div>
                <!-- Konten Tab -->
                <div>
                    <div x-show="tab === 'publikasi'">
                        @include('dashboard.rekapitulasi-publikasi')
                    </div>
                    <div x-show="tab === 'tahapan'">
                        @include('dashboard.rekapitulasi-tahapan')
                    </div>
                </div>
            </div>

            <!-- Daftar Publikasi Survei -->
            <div class="max-w-6xl mx-auto mt-6 p-6 bg-white bordershadow border rounded-lg">
                <!-- Header -->
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <h2 class="text-lg font-semibold text-blue-900">Daftar Publikasi Survei</h2>
                        <p class="text-sm text-gray-500">Tabel ringkasan publikasi survei tracking per triwulan</p>
                    </div>
                </div>
                <!-- Table -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse">
                            <thead class="bg-gray-100 text-gray-600 text-xs">
                                <tr>
                                    <th class="px-3 py-2 border">No</th>
                                    <th class="px-3 py-2 border">Nama Publikasi</th>
                                    <th class="px-3 py-2 border">Tahapan</th>
                                    <th class="px-3 py-2 border" colspan="4">Rencana Kegiatan</th>
                                    <th class="px-3 py-2 border" colspan="4">Realisasi Kegiatan</th>
                                    <th class="px-3 py-2 border">Aksi</th>
                                </tr>
                                <tr class="bg-gray-50 text-gray-500 text-xs">
                                    <th class="px-3 py-2"></th>
                                    <th class="px-3 py-2"></th>
                                    <th class="px-3 py-2"></th>
                                    <th class="px-3 py-2">Triwulan I</th>
                                    <th class="px-3 py-2">Triwulan II</th>
                                    <th class="px-3 py-2">Triwulan III</th>
                                    <th class="px-3 py-2">Triwulan IV</th>
                                    <th class="px-3 py-2">Triwulan I</th>
                                    <th class="px-3 py-2">Triwulan II</th>
                                    <th class="px-3 py-2">Triwulan III</th>
                                    <th class="px-3 py-2">Triwulan IV</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- No -->
                                    <td class="px-4 py-4 align-top">1</td>
                                    <!-- Nama Publikasi -->
                                    <td class="px-4 py-4 align-top ">
                                        <div class="font-semibold text-gray-700">Sakernas</div>
                                    </td>
                                    <!-- Tahapan -->
                                    <td class="px-4 py-4 align-top">
                                        <div class="text-sm font-medium text-gray-700">5/6 Tahapan</div>
                                        <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 border rounded-full">50% selesai</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Perekrutan Anggota, Pelatihan Anggota, +4 lainnya</p>
                                    </td>
                                    <!-- Rencana Kegiatan -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block">3 Rencana</div>
                                        <p class="text-xs text-gray-500 mt-1">100% selesai</p>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block">3 Rencana</div>
                                        <p class="text-xs text-gray-500 mt-1">67% selesai</p>
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-400">-</td>
                                    <td class="px-4 py-4 text-center text-gray-400">-</td>
                                    <!-- Realisasi Kegiatan -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block">3 Selesai</div>
                                        <p class="text-xs text-gray-500 mt-1">3 sesuai rencana</p>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block">1 Selesai</div>
                                        <p class="text-xs text-gray-500 mt-1">1 sesuai rencana</p>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block">1 Selesai</div>
                                        <p class="text-xs text-orange-500 mt-1">+1 lintas triwulan</p>
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-400">Belum Realisasi</td>
                                    <!-- Aksi -->
                                    <td class="px-4 py-4 text-center relative" x-data="{ open: false}">
                                        {{-- tombol trigger --}}
                                        <button 
                                            @click="open = !open"
                                            {{-- icon --}}
                                            class="p-2 rounded-xl hover:bg-emerald-600 hover:text-white focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                <path d="M2 8a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM6.5 8a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM12.5 6.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z" />
                                            </svg>
                                        </button>
                                        {{-- Dropdown menu --}}
                                        <div
                                            x-show="open"
                                            @click.outside= "open=false"
                                            class="absolute right-0 mt-2 w-32 bg-white border rounded-lg shadow-lg z-10">
                                            <button class="flex gap-1 sm:text-xs w-full text-left px-4 py-2 text-sm text-black hover:bg-emerald-600 hover:text-white">
                                                {{-- icon --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                                    <path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd" />
                                                </svg>
                                                Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Baris Kedua -->
                                <tr>
                                    <!-- No -->
                                    <td class="px-4 py-4 align-top">2</td>
                                    <!-- Nama Publikasi -->
                                    <td class="px-4 py-4 align-top ">
                                        <div class="font-semibold text-gray-700">Susenas</div>
                                    </td>
                                    <!-- Tahapan -->
                                    <td class="px-4 py-4 align-top">
                                        <div class="text-sm font-medium text-gray-700">2/4 Tahapan</div>
                                        <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 border rounded-full">25% selesai</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Penyusunan Kuesioner, Wawancara Rumah Tangga, +2 lainnya</p>
                                    </td>
                                    <!-- Rencana Kegiatan-->
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block">1 Rencana</div>
                                        <p class="text-xs text-gray-500 mt-1">100% selesai</p>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block">2 Rencana</div>
                                        <p class="text-xs text-gray-500 mt-1">50% selesai</p>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-blue-900 text-white inline-block">1 Rencana</div>
                                        <p class="text-xs text-gray-500 mt-1">0% selesai</p>
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-400">-</td>
                                    <!-- Realisasi Kegiatan -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block">1 Selesai</div>
                                        <p class="text-xs text-gray-500 mt-1">1 sesuai rencana</p>
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-400">Belum Realisasi</td>
                                    <td class="px-4 py-4 text-center text-gray-400">Belum Realisasi</td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="px-3 py-1 rounded-full bg-emerald-600 text-white inline-block">1 Selesai</div>
                                        <p class="text-xs text-orange-500 mt-1">+1 lintas triwulan</p>
                                    </td>
                                    <!-- Aksi -->
                                    <td class="px-4 py-4 text-center relative" x-data="{ open: false}">
                                        {{-- tombol trigger --}}
                                        <button 
                                            @click="open = !open"
                                            {{-- icon --}}
                                            class="p-2 rounded-xl hover:bg-emerald-600 hover:text-white focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                <path d="M2 8a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM6.5 8a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM12.5 6.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z" />
                                            </svg>
                                        </button>
                                        {{-- Dropdown menu --}}
                                        <div
                                            x-show="open"
                                            @click.outside= "open=false"
                                            class="absolute right-0 mt-2 w-32 bg-white border rounded-lg shadow-lg z-10">
                                            <button class="flex gap-1 sm:text-xs w-full text-left px-4 py-2 text-sm text-black hover:bg-emerald-600 hover:text-white">
                                                {{-- icon --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                                    <path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd" />
                                                </svg>
                                                Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Grafik Ringkasan -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="font-semibold mb-2">Ringkasan Kinerja</h2>
                    <div class="h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                        Chart Placeholder
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="font-semibold mb-2">Statistik Tahapan per Triwulan</h2>
                    <div class="h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                        Chart Placeholder
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="font-semibold mb-2">Proporsi Publikasi vs Tahapan Selesai</h2>
                    <div class="h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                        Pie Chart Placeholder
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>