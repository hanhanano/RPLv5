<!DOCTYPE html>
<html lang="en">
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
        <div class="max-w-7xl mx-auto px-4 space-y-6" x-data="{ open: false }">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6 pt-4">
                    <a href="/dashboard" 
                        class="flex gap-1 items-center bg-white-600 text-black px-4 py-2 rounded-lg hover:bg-green-700 hover:text-white shadow transition text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M14 8a.75.75 0 0 1-.75.75H4.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L4.56 7.25h8.69A.75.75 0 0 1 14 8Z" clip-rule="evenodd" />
                        </svg>
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Judul -->
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">{{ $publication->publication_report }}</h1>
                        <p class="text-xs sm:text-sm text-gray-600 mb-2">{{ $publication->publication_name }}</p>
                        <p class="text-xs sm:text-sm text-gray-600 mb-2">{{ $publication->publication_pic }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-semibold text-blue-800">{{ $publication->progressKumulatif }}%</p>
                        <p class="text-xs sm:text-sm text-blue-800">Progress Keseluruhan</p>
                    </div>
                </div>

                <!-- {{-- <div class="max-w-6xl mx-auto mt-6 p-6 bg-white border shadow rounded-lg" x-data="{ uploading: false }">
                    
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-5 h-5 text-blue-600">
                                    <path fill-rule="evenodd" d="M2 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Zm10.5 5.707a.5.5 0 0 0-.146-.353l-1-1a.5.5 0 0 0-.708 0L9.354 9.646a.5.5 0 0 1-.708 0L6.354 7.354a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0-.146.353V12a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V9.707ZM12 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" clip-rule="evenodd" />
                                </svg>
                                File Publikasi
                            </h3>
                            <p class="text-sm text-gray-500">Upload output akhir publikasi (PDF, Excel, Word, ZIP)</p>
                        </div>

                        @if($publication->files->count() > 0)
                            <a href="{{ route('publications.downloadAllFiles', $publication->slug_publication) }}" 
                            class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                    <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                    <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                                </svg>
                                Unduh Semua (ZIP)
                            </a>
                        @endif
                    </div>

                    
                    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin', 'operator']))
                    <form action="{{ route('publications.uploadFiles', $publication->slug_publication) }}" 
                        method="POST" 
                        enctype="multipart/form-data"
                        class="mb-6"
                        @submit="uploading = true">
                        @csrf
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-400 transition">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400 mb-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                
                                <label for="file-upload" class="cursor-pointer">
                                    <span class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        Pilih File
                                    </span>
                                    <input id="file-upload" 
                                        name="files[]" 
                                        type="file" 
                                        multiple 
                                        accept=".pdf,.xlsx,.xls,.docx,.doc,.zip"
                                        class="hidden"
                                        onchange="updateFileList(this)">
                                </label>
                                
                                <p class="text-sm text-gray-500 mt-2">atau drag & drop file di sini</p>
                                <p class="text-xs text-gray-400 mt-1">PDF, Excel, Word, ZIP (Max 10MB per file, max 10 files)</p>
                            </div>

                            <div id="file-preview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">File yang akan diupload:</p>
                                <ul id="file-list" class="space-y-1 text-sm text-gray-600"></ul>
                            </div>
                        </div>

                        <button type="submit" 
                                :disabled="uploading"
                                class="mt-4 w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <span x-show="!uploading">📤 Upload File</span>
                            <span x-show="uploading" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengupload...
                            </span>
                        </button>
                    </form>
                    @endif

                    @if($publication->files->count() > 0)
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 text-gray-600">
                                <path fill-rule="evenodd" d="M2 4.75C2 3.784 2.784 3 3.75 3h4.836c.464 0 .909.184 1.237.513l1.414 1.414c.329.328.513.773.513 1.237v4.586A1.75 1.75 0 0 1 10 12.75h-6.25A1.75 1.75 0 0 1 2 11V4.75Z" clip-rule="evenodd" />
                            </svg>
                            File Tersimpan ({{ $publication->files->count() }})
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($publication->files as $file)
                            <div class="border rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3 flex-1 min-w-0">
                                        <span class="text-3xl">{{ $file->file_icon }}</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 truncate" title="{{ $file->file_name }}">
                                                {{ $file->file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $file->file_size_human }} • 
                                                {{ $file->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex gap-1">
                                        
                                        <a href="{{ route('publications.downloadFile', $file->file_id) }}" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Download">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                                <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                                <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                                            </svg>
                                        </a>

                                        @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                                        <button onclick="deleteFile({{ $file->file_id }})"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-3 opacity-50">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                        </svg>
                        <p class="text-sm">Belum ada file publikasi yang diupload</p>
                    </div>
                    @endif
                </div> 
                --}} -->

                <div class="grid grid-cols-1 sm:grid-cols-6 gap-2 mb-4 items-center pt-4">
                    <!-- Search -->
                    <div class="{{ (auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin'])) ? 'sm:col-span-4' : 'sm:col-span-6' }}">
                            <input 
                            type="text" 
                            id="search-input"
                            name="search"
                            placeholder="Cari Nama Tahapan..." 
                            value="{{ request('search') }}"
                            class="w-full border px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                    </div>
                    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                        <!-- Tombol Unduh Excel -->
                        <div class="sm:col-span-1">
                            <a href="{{ route('publication.export', $publication->slug_publication) }}" 
                                class="flex items-center justify-center gap-1 border text-gray-700 px-3 py-2 rounded-lg text-xs sm:text-sm shadow hover:text-white hover:bg-emerald-800 whitespace-nowrap min-w-[100px]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                    <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                    <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                                </svg>
                                Unduh
                            </a>
                        </div>
                        <!-- Tambah Tahapan -->
                        <div class="sm:col-span-1">
                            <div x-data="{ open: false }">
                                <button 
                                    @click="open = true" 
                                    class="w-full flex gap-1 items-center justify-center bg-emerald-600 text-white px-3 py-2 rounded-lg text-sm shadow hover:bg-emerald-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                        <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                                    </svg>
                                    Tahapan
                                </button>

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
                                        <h2 class="text-lg font-semibold">Tambah Tahapan</h2>
                                        <p class="text-sm text-gray-500 mb-2">Tambahkan tahapan baru untuk publikasi/laporan</p>
                                        <form method="POST" action="{{ route('steps.store', $publication->slug_publication) }}">
                                            @csrf
                                            <input type="hidden" name="publication_id" value="{{ $publication->slug_publication }}">
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Jenis Tahapan</label>
                                                <select name="plan_type" 
                                                    class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                                    <option value="">-- Pilih Jenis Tahapan --</option>
                                                    <option value="persiapan">Persiapan</option>
                                                    <option value="pengumpulan data">Pengumpulan Data</option>
                                                    <option value="pengolahan data">Pengolahan Data</option>
                                                    <option value="analisis data">Analisis Data</option>
                                                    <option value="diseminasi">Diseminasi</option>
                                                </select>
                                            </div>

                                            <!-- Tambah Tahapan Survei -->
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Nama Tahapan</label>
                                                <input type="text" name="plan_name" 
                                                    class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                                    placeholder="Contoh: Perekrutan Anggota Pelatihan Anggota">
                                            </div>

                                            <!-- Tombol Simpan -->
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
                    @endif
                </div>

                <!-- Badges -->
                <div class="flex gap-2 mb-6">
                    <span class="px-3 py-1 bg-blue-800 text-white rounded-full text-sm">{{ $total_rencana }} Tahapan</span>
                    <span class="px-3 py-1 bg-emerald-600 text-white rounded-full text-sm">{{ $total_realisasi }} Selesai</span>
                </div>

                <!-- Card -->
                @foreach ($stepsplans as $plan)
                    @php
                        // Mengambil data realisasi (akan null jika belum ada)
                        $final = $plan->stepsFinals;

                        // Inisialisasi $struggle. Jika $final null, ini akan tetap null.
                        $struggle = null;
                        if ($final) {
                            $struggle = $final->struggles->first();
                        }
                    @endphp

                    <div x-data="{ 
                    // State utama Alpine.js
                    editMode: false, 
                    tab:'rencana', 
                    DatesAreInvalid: false,
                    formIsInvalid: false,
                    fileSizeError:false, 
                    docTypeError:false, 
                    allowedTypes: ['image/jpeg', 'image/png', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],

                    // State utama Alpine.js
                    plan_start_date: '{{ $plan->plan_start_date ? $plan->plan_start_date->format('Y-m-d') : '' }}',
                    plan_end_date: '{{ $plan->plan_end_date ? $plan->plan_end_date->format('Y-m-d') : '' }}',
                    plan_desc: `{{ trim(old('plan_desc', $plan->plan_desc ?? '')) }}`,
                    hasPlanDoc: {{ $plan->plan_doc ? 'true' : 'false' }},
                    
                    // Variabel untuk form Realisasi
                    actual_started: '{{ optional($plan->stepsFinals->actual_started ?? null)->format('Y-m-d') ?? '' }}',
                    actual_ended: '{{ optional($plan->stepsFinals->actual_ended ?? null)->format('Y-m-d') ?? '' }}',
                    final_desc: '{{ old('final_desc', optional($final)->final_desc) }}',
                    next_step: '{{ old('next_step', optional($final)->next_step) }}',
                    hasFinalDoc: {{ optional($final)->final_doc ? 'true' : 'false' }},
                    
                    hasInvalidChars(text) {
                        // Regex: Memperbolehkan huruf, angka, spasi, koma (,), titik (.), tanda tanya (?), tanda seru (!), dan tanda kurung ().
                        // Semua karakter lain dianggap tidak valid/khusus.
                        return /[^a-zA-Z0-9\s.,?!()]/g.test(text);
                    },

                    // Fungsi validasi untuk minimal jumlah kata (minimal 3 kata)
                    hasMinWords(text, minWords = 3) {
                        // Hilangkan spasi berlebih dan pisahkan berdasarkan spasi
                        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
                        return words.length >= minWords;
                    },

                    // Fungsi untuk mendapatkan status validasi teks
                    isTextValid(text) {
                        if (!text || text.trim() === '') return false;
                        return !this.hasInvalidChars(text) && this.hasMinWords(text);
                    },
                    
                    validateDates(type) {
                        if (type === 'rencana') {
                            this.datesAreInvalid = (this.plan_start_date && this.plan_end_date) && new Date(this.plan_end_date) < new Date(this.plan_start_date);
                        } else { // type === 'realisasi'
                            this.datesAreInvalid = (this.actual_started && this.actual_ended) && new Date(this.actual_ended) < new Date(this.actual_started);
                        }
                        this.updateFormValidity();
                    },

                   // Logika validasi form utama
                    updateFormValidity() {
                        let isDocMissing = false;
                        let isAnyStruggleEmpty = false;

                        // Panggil validasi teks
                        let isPlanDescValid = this.isTextValid(this.plan_desc);
                        let isFinalDescValid = this.isTextValid(this.final_desc);
                        let isNextStepValid = this.isTextValid(this.next_step);

                        if (this.tab === 'rencana') {
                            isDocMissing = !this.hasPlanDoc && !this.fileSizeError && !this.docTypeError;
                            this.formIsInvalid = !this.plan_start_date || !this.plan_end_date || !isPlanDescValid || !this.plan_desc.trim() || this.datesAreInvalid || this.fileSizeError || this.docTypeError || isDocMissing;
                        } else if (this.tab === 'realisasi') {
                            isDocMissing = !this.hasFinalDoc && !this.fileSizeError && !this.docTypeError;
                            // Logika struggle bisa ditambahkan di sini jika Anda ingin validasi sisi klien
                            this.formIsInvalid = !this.actual_started || !this.actual_ended || !isFinalDescValid || !isNextStepValid || this.datesAreInvalid || this.fileSizeError || this.docTypeError || isDocMissing || isAnyStruggleEmpty;
                        }
                    },
                    
                    handleFileChange(event, hasExistingDocVariable) {
                        if (event.target.files.length > 0) {
                            this.fileSizeError = event.target.files[0].size > 2097152;
                            this.docTypeError = !this.allowedTypes.includes(event.target.files[0].type);
                            // Tambahkan logika untuk memperbarui hasDocVariable
                            this[hasExistingDocVariable] = true;
                        } else {
                            this.fileSizeError = false;
                            this.docTypeError = false;
                            // Atur kembali hasDocVariable jika file tidak dipilih
                            this[hasExistingDocVariable] = false;
                        }
                        this.updateFormValidity();
                    },

                    }" x-init="updateFormValidity()" class="bg-white rounded-xl shadow p-6 border mb-5">
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                @php
                                    $colors = [
                                        'persiapan' => 'bg-blue-800',
                                        'pengumpulan data' => 'bg-yellow-600',
                                        'pengolahan data' => 'bg-orange-600',
                                        'analisis data' => 'bg-purple-600',
                                        'diseminasi' => 'bg-green-600',
                                    ];
                                    $bgColorClass = $colors[$plan->plan_type] ?? 'bg-gray-600';
                                @endphp
                                <div class="h-10 w-10 flex items-center justify-center rounded-full {{ $bgColorClass }} text-white font-semibold">
                                    {{ strtoupper($plan->plan_type[0]) }}
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold"></h2>
                                    <span class="py-3 text-lg font-bold">{{ $plan->plan_name }}</span>
                                    <div class="flex gap-2 mt-1">
                                        <span class="px-2 py-0.5 bg-gray-200 rounded-lg text-xs">{{ $plan->plan_type }}</span>
                                        @if($final)
                                            <span class="px-2 py-0.5 bg-emerald-600 text-white rounded-lg text-xs">Selesai</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-blue-800 text-white rounded-lg text-xs">Rencana</span>
                                        @endif
                                        <span class="px-2 py-0.5 bg-gray-200 rounded-lg text-xs">Q{{ getQuarter($plan->plan_start_date) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin', 'operator']))
                                <div x-data="{ open: false }">
                                    <button 
                                        @click="open = true"
                                        class="text-xs sm:text-sm flex gap-1 px-4 py-2 rounded-lg text-gray-700 hover:bg-emerald-600 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                            <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                                            <h2 class="text-lg font-semibold">Edit Tahapan</h2>
                                            <p class="text-sm text-gray-500 mb-2">Mengedit tahapan publikasi/laporan</p>
                                            <form action="{{ route('plans.update_stage', $plan->step_plan_id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <!-- Jenis Tahapan -->
                                                <div class="mb-3">
                                                    <label class="block text-sm font-medium text-gray-700">Jenis Tahapan</label>
                                                    <select name="plan_type" required
                                                        class="px-2 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                                        <option value="">-- Pilih Jenis Tahapan --</option>
                                                        {{-- Menandai opsi yang dipilih --}}
                                                        <option value="persiapan" @if(old('plan_type', $plan->plan_type) == 'persiapan') selected @endif>Persiapan</option>
                                                        <option value="pengumpulan data" @if(old('plan_type', $plan->plan_type) == 'pengumpulan data') selected @endif>Pengumpulan Data</option>
                                                        <option value="pengolahan data" @if(old('plan_type', $plan->plan_type) == 'pengolahan data') selected @endif>Pengolahan Data</option>
                                                        <option value="analisis data" @if(old('plan_type', $plan->plan_type) == 'analisis data') selected @endif>Analisis Data</option>
                                                        <option value="diseminasi" @if(old('plan_type', $plan->plan_type) == 'diseminasi') selected @endif>Diseminasi</option>
                                                    </select>
                                                </div>

                                                <!-- Nama Tahapan -->
                                                <div class="mb-3">
                                                    <label class="block text-sm font-medium text-gray-700">Nama Tahapan</label>
                                                    <input type="text" name="plan_name" value="{{ old('plan_name', $plan->plan_name) }}" required
                                                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                                        placeholder="Contoh: Perekrutan Anggota Pelatihan Anggota">
                                                </div>

                                                <!-- Tombol Simpan -->
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
                            @endif
                        </div>

                        <!-- Konten Card -->
                        <div x-show="!editMode" x-transition>
                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Rencana -->
                                <div>
                                    <h3 class="font-semibold mb-2">Rencana</h3>
                                    <p class="text-sm text-gray-600">Periode</p>
                                    <p class="text-sm mb-2">
                                        @if($plan->plan_start_date && $plan->plan_end_date)
                                            {{ $plan->plan_start_date->format('d F Y') }} - {{ $plan->plan_end_date->format('d F Y') }}
                                        @else
                                            <span class="text-gray-500 italic text-xs">Belum Diisi</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">Narasi</p>
                                    <p class="text-sm mb-2">
                                        @if($plan->plan_desc)
                                            {{ $plan->plan_desc }}
                                        @else
                                            <span class="text-gray-500 italic text-xs">Belum Diisi</span>
                                        @endif
                                    </p>

                                    <p class="text-sm text-gray-600">Dokumen</p>
                                    @if ($plan->plan_doc)
                                        @php 
                                            $cleanPlanDoc = str_replace(['public/', 'public\\'], '', $plan->plan_doc); 
                                        @endphp
                                        <a href="{{ asset('storage/' . $cleanPlanDoc) }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all flex items-center gap-1">
                                            📄 Bukti Rencana
                                        </a>
                                    @else
                                        <p class="text-xs italic text-gray-500">Tidak ada dokumen</p>
                                    @endif
                                </div>
                                <!-- Realisasi -->
                                <div>
                                    <h3 class="font-semibold mb-2">Realisasi</h3>
                                    <p class="text-sm text-gray-600">Periode Aktual</p>
                                    <p class="text-sm mb-2">
                                        @if($final)
                                            {{ $final->actual_started->format('d F Y') }} - {{ $final->actual_ended->format('d F Y') }}
                                        @else
                                            <span class="text-gray-500 italic text-xs">Belum Diisi</span>
                                        @endif
                                    </p>

                                    <p class="text-sm text-gray-600">Narasi</p>
                                    <p class="text-sm mb-2">
                                        @if( optional($final)->final_desc)
                                            {{ $final->final_desc }}
                                        @else
                                            <span class="text-gray-500 italic text-xs">Belum Diisi</span>
                                        @endif
                                    </p>

                                    <p class="text-sm text-gray-600">Kendala & Solusi</p>
                                    @forelse(optional($final)->struggles ?? [] as $s)
                                        <div class="border p-2 rounded mb-2">
                                            <p class="text-sm">Kendala: {{ $s->struggle_desc }}</p>
                                            <p class="text-sm">Solusi: {{ $s->solution_desc }}</p>
                                            @if($s->solution_doc)
                                                @php 
                                                    $cleanSolDoc = str_replace(['public/', 'public\\'], '', $s->solution_doc); 
                                                @endphp
                                                <a href="{{ asset('storage/' . $cleanSolDoc) }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all flex items-center gap-1 mt-1">
                                                    📄 Bukti Solusi
                                                </a>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-sm mb-2">
                                            <span class="text-gray-500 italic text-xs">Belum diisi</span>
                                        </p>
                                    @endforelse

                                    <p class="text-sm text-gray-600">Rencana Selanjutnya</p>
                                    <p class="text-sm mb-2">
                                        @if( optional($final)->next_step)
                                            {{ $final->next_step }}
                                        @else
                                            <span class="text-gray-500 italic text-xs">Belum Diisi</span>
                                        @endif
                                    </p>

                                    <p class="text-sm text-gray-600">Bukti Pendukung Solusi</p>
                                    <div class="flex flex-col gap-1">
                                        @if (optional($final)->final_doc)
                                            @php
                                                $cleanFinalDoc = str_replace(['public/', 'public\\'], '', $final->final_doc);
                                            @endphp
                                            <a href="{{ asset('storage/' . $cleanFinalDoc) }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all flex items-center gap-1">
                                                📄 Dokumen Realisasi
                                            </a>
                                        @else
                                            <p class="text-xs italic text-gray-500">Tidak ada dokumen</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Edit -->
                            <div class="flex justify-end mt-4 gap-2">
                                @if(auth()->check()) 
                                    @if(auth()->user()->role === 'ketua_tim' || auth()->user()->role === 'admin')
                                        <div x-data="{ showConfirm: false }">
                                            <button type="button"
                                                @click="showConfirm = true"
                                                class="text-xs sm:text-sm flex gap-1 px-4 py-2  rounded-lg bg-gray-200 text-red-500 hover:bg-red-600 hover:text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                    <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd" />
                                                </svg>
                                                Hapus
                                            </button>

                                            <div
                                                x-show="showConfirm"
                                                x-transition 
                                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                                                    <h2 class="text-lg font-semibold text-gray-800">Hapus Tahapan</h2>
                                                    <p class="text-xs text-gray-500">Apakah Anda yakin ingin menghapus tahapan "{{ $plan->plan_type }}" ini ? </p>
                                                    <div class="flex justify-end mt-4 gap-2">
                                                        <button  @click="showConfirm = false" 
                                                            class="text-xs bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                                                            Batal
                                                        </button>
                                                        <form action="{{ route('plans.destroy', $plan->step_plan_id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-red-800">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    @endif
                                    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin', 'operator']))
                                        <button @click="editMode = true"
                                            class="text-xs sm:text-sm flex gap-1 px-4 py-2  rounded-lg bg-gray-200 text-gray-700 hover:bg-emerald-600 hover:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                <path fill-rule="evenodd" d="M11.013 2.513a1.75 1.75 0 0 1 2.475 2.474L6.226 12.25a2.751 2.751 0 0 1-.892.596l-2.047.848a.75.75 0 0 1-.98-.98l.848-2.047a2.75 2.75 0 0 1 .596-.892l7.262-7.261Z" clip-rule="evenodd" />
                                            </svg>
                                            Edit
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Konten Card -->
                        <div x-show="editMode">
                            <div class="flex space-x-2 mb-4">
                                <button type="button" 
                                        class=" text-xs px-4 py-2 rounded"
                                        :class="tab === 'rencana' ? 'bg-blue-800 text-white' : 'bg-gray-200 text-gray-500 hover:bg-white hover:text-blue-900'"
                                        @click="tab = 'rencana'">
                                    Edit Rencana
                                </button>
                                <button type="button"
                                        class=" text-xs px-4 py-2 rounded"
                                        :class="tab === 'realisasi' ? 'bg-blue-800 text-white' : 'bg-gray-200 text-gray-500 hover:bg-white hover:text-blue-900'"
                                        @click="tab = 'realisasi'">
                                    Edit Realisasi
                                </button>
                            </div>  

                            <!-- Konten Tab -->
                            <div>
                                <form x-show="tab === 'rencana'" class="rencana-form" method="POST" action="{{ route('plans.update', $plan->step_plan_id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    @include('detail.form-rencana', ['plan' => $plan])
                                    <div class="flex justify-end space-x-2 mt-4">
                                        <button type="button" @click="editMode = false"
                                            class="text-xs sm:text-sm bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            :disabled="formIsInvalid"
                                            :class="formIsInvalid ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-800 hover:bg-blue-700'"
                                            class="text-xs sm:text-sm bg-blue-800 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                            Simpan
                                        </button>
                                    </div>
                                </form>

                                <form x-show="tab === 'realisasi'" class="realisasi-form" method="POST" action="{{ route('finals.update', $plan->step_plan_id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    @php
                                        $final = $plan->stepsFinals ?? new \App\Models\StepsFinal();
                                        $struggle = $final->struggles->first() ?? new \App\Models\Struggle();
                                    @endphp
                                    @include('detail.form-realisasi', ['final' => $final, 'struggle' => $struggle])
                                    <div class="flex justify-end space-x-2 mt-4">
                                        <button type="button" @click="editMode = false"
                                            class="text-xs sm:text-sm bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            :disabled="formIsInvalid"
                                            :class="formIsInvalid ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-800 hover:bg-blue-700'"
                                            class="text-xs sm:text-sm bg-blue-800 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            
                        </div>
                    </div>  
                @endforeach     
            </div>
        </div>
    </main>

</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addButtons = document.querySelectorAll('.add-struggle-button');

        addButtons.forEach(button => {
            button.addEventListener('click', function () {
                const wrapper = this.parentNode.querySelector('.struggles-wrapper');
                const struggleItems = wrapper.querySelectorAll('.struggle-item');
                const struggleIndex = struggleItems.length;

                const div = document.createElement('div');
                div.classList.add('struggle-item', 'border', 'p-3', 'rounded-lg');
                div.innerHTML = `
                    <input type="hidden" name="struggles[${struggleIndex}][struggle_id]" value="">
                    <div class="flex items-center justify-between mb-2">
                        <span class="block text-lg font-medium text-gray-700">Kendala dan Solusi ${struggleIndex + 1}</span>
                        <button type="button" class="delete-struggle-button text-xs sm:text-sm flex gap-1 px-3 py-1 rounded-lg bg-gray-200 text-red-500 hover:bg-red-600 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <label class="block text-sm font-medium text-gray-700">Kendala</label>
                    <textarea name="struggles[${struggleIndex}][struggle_desc]" rows="2" required class="w-full border rounded px-3 py-2"></textarea>
                    <label class="block text-sm font-medium text-gray-700">Solusi</label>
                    <textarea name="struggles[${struggleIndex}][solution_desc]" rows="2" required class="w-full border rounded px-3 py-2"></textarea>
                    <label class="block text-sm font-medium text-gray-700">Bukti Solusi</label>
                    <input type="file" name="struggles[${struggleIndex}][solution_doc]" accept=".png,.jpg,.jpeg,.pdf"
                    class="w-full border rounded px-3 py-2 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gray-600 file:text-white">
                `;
                wrapper.appendChild(div);
            });
        });

        document.addEventListener('click', function(event) {
            if (event.target.closest('.delete-struggle-button')) {
                const struggleItem = event.target.closest('.struggle-item');
                struggleItem.remove();
            }
        });
    });

    function debounce(func, delay) {
        let timeoutId;
        return function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(this, args);
            }, delay);
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        
        if (searchInput) {
            const baseUrl = "{{ route('steps.index', $publication->slug_publication) }}";

            const performSearch = function() {
                const searchText = searchInput.value.trim();
                const url = new URL(baseUrl);
                url.search = '';
                
                if (searchText) {
                    url.searchParams.set('search', searchText);
                }
                
                window.location.href = url.toString();
            };

            const debouncedSearch = debounce(performSearch, 400);
            searchInput.addEventListener('input', debouncedSearch);
        }
    });

    // function updateFileList(input) {
    //     const preview = document.getElementById('file-preview');
    //     const list = document.getElementById('file-list');
        
    //     if (input.files.length > 0) {
    //         preview.classList.remove('hidden');
    //         list.innerHTML = '';
            
    //         Array.from(input.files).forEach((file, index) => {
    //             const li = document.createElement('li');
    //             li.className = 'flex items-center gap-2';
    //             li.innerHTML = `
    //                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 text-green-600">
    //                     <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
    //                 </svg>
    //                 <span>${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
    //             `;
    //             list.appendChild(li);
    //         });
    //     } else {
    //         preview.classList.add('hidden');
    //     }
    // }

    // Delete file
    // function deleteFile(fileId) {
    //     if (!confirm('Yakin ingin menghapus file ini?')) return;

    //     const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    //     fetch(`/publication-files/${fileId}`, {
    //         method: 'DELETE',
    //         headers: {
    //             'X-CSRF-TOKEN': csrfToken,
    //             'Accept': 'application/json',
    //         }
    //     })
    //     .then(res => res.json())
    //     .then(data => {
    //         if (data.success) {
    //             alert('File berhasil dihapus!');
    //             location.reload();
    //         } else {
    //             alert('Gagal menghapus file: ' + data.message);
    //         }
    //     })
    //     .catch(err => {
    //         console.error('Error:', err);
    //         alert('Terjadi kesalahan saat menghapus file');
    //     });
    // }
</script>