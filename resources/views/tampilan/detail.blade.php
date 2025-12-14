<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Publikasi</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div>
        <x-navbar></x-navbar>
    </div>

    <main class="py-8 mt-16 md:mt-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ open: false }">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                    <div class="space-y-4">
                        <a href="/dashboard" 
                            class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors text-sm font-medium shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M14 8a.75.75 0 0 1-.75.75H4.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L4.56 7.25h8.69A.75.75 0 0 1 14 8Z" clip-rule="evenodd" />
                            </svg>
                            Kembali ke Dashboard
                        </a>
                        
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $publication->publication_report }}</h1>
                            <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-600">
                                <span class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                    {{ $publication->publication_name }}
                                </span>
                                <span class="flex items-center gap-1 bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $publication->publication_pic }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-1/3 bg-gray-50 p-4 rounded-xl border border-gray-100 md:self-center">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-medium text-gray-600">Progress Keseluruhan</span>
                            <span class="text-2xl font-bold text-blue-600">
                                {{ round($publication->progressKumulatif, 2) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-out" 
                                style="width: {{ $publication->progressKumulatif }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center border-t border-gray-100 pt-6">
                    <div class="{{ (auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin'])) ? 'sm:col-span-8 lg:col-span-9' : 'sm:col-span-12' }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="search-input"
                                autocomplete="off" 
                                placeholder="Cari nama tahapan..." 
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow"
                            >
                        </div>
                    </div>

                    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                        <div class="sm:col-span-4 lg:col-span-3 flex gap-2 justify-end">
                            <a href="{{ route('publication.export', $publication->slug_publication) }}" 
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-emerald-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                    <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                    <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                                </svg>
                                <span>Excel</span>
                            </a>

                            <div x-data="{ open: false }">
                                <button 
                                    @click="open = true" 
                                    class="w-full flex items-center justify-center gap-2 bg-emerald-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium shadow-sm hover:bg-emerald-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                        <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                                    </svg>
                                    <span>Tahapan</span>
                                </button>

                                <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="flex justify-between items-start mb-4">
                                                    <div>
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Tahapan Baru</h3>
                                                        <p class="text-sm text-gray-500 mt-1">Isi form di bawah untuk menambahkan tahapan.</p>
                                                    </div>
                                                    <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                                                        <span class="sr-only">Close</span>
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <form method="POST" action="{{ route('steps.store', $publication->slug_publication) }}">
                                                    @csrf
                                                    <input type="hidden" name="publication_id" value="{{ $publication->slug_publication }}">
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Tahapan</label>
                                                        <select name="plan_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5">
                                                            <option value="">-- Pilih Jenis Tahapan --</option>
                                                            <option value="persiapan">Persiapan</option>
                                                            <option value="pengumpulan data">Pengumpulan Data</option>
                                                            <option value="pengolahan data">Pengolahan Data</option>
                                                            <option value="analisis data">Analisis Data</option>
                                                            <option value="diseminasi">Diseminasi</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tahapan</label>
                                                        <input type="text" name="plan_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5" placeholder="Contoh: Perekrutan Petugas">
                                                    </div>
                                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:col-start-2 sm:text-sm">Simpan</button>
                                                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        {{ $total_rencana }} Tahapan Rencana
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        {{ $total_realisasi }} Tahapan Selesai
                    </span>
                </div>
            </div>

            <div class="space-y-6">
                @foreach ($stepsplans as $plan)
                    @php
                        $final = $plan->stepsFinals;
                        $struggle = $final ? $final->struggles->first() : null;
                        
                        $colors = [
                            'persiapan' => 'bg-blue-600',
                            'pengumpulan data' => 'bg-yellow-500',
                            'pengolahan data' => 'bg-orange-500',
                            'analisis data' => 'bg-purple-600',
                            'diseminasi' => 'bg-emerald-600',
                        ];
                        $bgColorClass = $colors[$plan->plan_type] ?? 'bg-gray-500';
                        $initial = strtoupper(substr($plan->plan_type, 0, 1));
                    @endphp

                    <div x-data="{ 
                        editMode: false, 
                        tab:'rencana', 
                        DatesAreInvalid: false,
                        formIsInvalid: false,
                        fileSizeError:false, 
                        docTypeError:false, 
                        allowedTypes: ['image/jpeg', 'image/png', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],

                        plan_start_date: '{{ $plan->plan_start_date ? $plan->plan_start_date->format('Y-m-d') : '' }}',
                        plan_end_date: '{{ $plan->plan_end_date ? $plan->plan_end_date->format('Y-m-d') : '' }}',
                        plan_desc: `{{ trim(old('plan_desc', $plan->plan_desc ?? '')) }}`,
                        hasPlanDoc: {{ $plan->plan_doc ? 'true' : 'false' }},
                        
                        actual_started: '{{ optional($plan->stepsFinals->actual_started ?? null)->format('Y-m-d') ?? '' }}',
                        actual_ended: '{{ optional($plan->stepsFinals->actual_ended ?? null)->format('Y-m-d') ?? '' }}',
                        final_desc: '{{ old('final_desc', optional($final)->final_desc) }}',
                        next_step: '{{ old('next_step', optional($final)->next_step) }}',
                        hasFinalDoc: {{ optional($final)->final_doc ? 'true' : 'false' }},
                        
                        hasInvalidChars(text) { return /[^a-zA-Z0-9\s.,?!()\-\/\:'%]/g.test(text); },
                        hasMinWords(text, minWords = 3) { return text.trim().split(/\s+/).filter(word => word.length > 0).length >= minWords; },
                        isTextValid(text) { if (!text || text.trim() === '') return false; return !this.hasInvalidChars(text) && this.hasMinWords(text); },
                        
                        validateDates(type) {
                            if (type === 'rencana') { this.datesAreInvalid = (this.plan_start_date && this.plan_end_date) && new Date(this.plan_end_date) < new Date(this.plan_start_date); } 
                            else { this.datesAreInvalid = (this.actual_started && this.actual_ended) && new Date(this.actual_ended) < new Date(this.actual_started); }
                            this.updateFormValidity();
                        },

                        updateFormValidity() {
                            let isDocMissing = false;
                            let isPlanDescValid = this.isTextValid(this.plan_desc);
                            let isFinalDescValid = this.isTextValid(this.final_desc);
                            let isNextStepValid = this.isTextValid(this.next_step);

                            if (this.tab === 'rencana') {
                                isDocMissing = !this.hasPlanDoc && !this.fileSizeError && !this.docTypeError;
                                this.formIsInvalid = !this.plan_start_date || !this.plan_end_date || !isPlanDescValid || !this.plan_desc.trim() || this.datesAreInvalid || this.fileSizeError || this.docTypeError || isDocMissing;
                            } else if (this.tab === 'realisasi') {
                                isDocMissing = !this.hasFinalDoc && !this.fileSizeError && !this.docTypeError;
                                this.formIsInvalid = !this.actual_started || !this.actual_ended || !isFinalDescValid || !isNextStepValid || this.datesAreInvalid || this.fileSizeError || this.docTypeError || isDocMissing;
                            }
                        },
                        
                        handleFileChange(event, hasExistingDocVariable) {
                            if (event.target.files.length > 0) {
                                this.fileSizeError = event.target.files[0].size > 2097152;
                                this.docTypeError = !this.allowedTypes.includes(event.target.files[0].type);
                                this[hasExistingDocVariable] = true;
                            } else {
                                this.fileSizeError = false;
                                this.docTypeError = false;
                                this[hasExistingDocVariable] = false;
                            }
                            this.updateFormValidity();
                        },

                    }" x-init="updateFormValidity()" 
                    class="searchable-item bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        
                        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl {{ $bgColorClass }} text-white text-xl font-bold shadow-sm ring-4 ring-white">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-900 leading-tight">{{ $plan->plan_name }}</h2>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 capitalize">
                                                {{ $plan->plan_type }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                Triwulan {{ getQuarter($plan->plan_start_date) }}
                                            </span>
                                            @if($final)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-600">
                                                    Dalam Rencana
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin', 'operator']))
                                    <div class="flex gap-2">
                                        <div x-data="{ open: false }">
                                            <button @click="open = true" class="text-gray-400 hover:text-blue-600 transition-colors p-1 rounded-md hover:bg-blue-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-cloak class="fixed inset-0 z-[999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    
                                                    <div x-show="open" 
                                                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                                                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="open = false">
                                                    </div>

                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                                    <div x-show="open" 
                                                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                                        
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="flex justify-between items-start mb-4">
                                                                <div>
                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Judul Tahapan</h3>
                                                                    <p class="text-sm text-gray-500 mt-1">Ubah form di bawah untuk memperbarui tahapan.</p>
                                                                </div>
                                                                <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                                                                    <span class="sr-only">Close</span>
                                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            <form action="{{ route('plans.update_stage', $plan->step_plan_id) }}" method="POST">
                                                                @csrf 
                                                                @method('PUT')
                                                                
                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Tahapan</label>
                                                                    <select name="plan_type" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5">
                                                                        @foreach(['persiapan', 'pengumpulan data', 'pengolahan data', 'analisis data', 'diseminasi'] as $type)
                                                                            <option value="{{ $type }}" {{ old('plan_type', $plan->plan_type) == $type ? 'selected' : '' }}>
                                                                                {{ ucwords($type) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tahapan</label>
                                                                    <input type="text" name="plan_name" value="{{ old('plan_name', $plan->plan_name) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5">
                                                                </div>

                                                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:col-start-2 sm:text-sm">
                                                                        Simpan Perubahan
                                                                    </button>
                                                                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm">
                                                                        Batal
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div x-show="!editMode" x-transition class="p-0">
                            <div class="grid md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                                <div class="p-6 bg-white">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-1 h-6 bg-blue-800 rounded-full"></div>
                                        <h3 class="font-bold text-gray-900 text-lg">Rencana</h3>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Periode Jadwal</p>
                                            @if($plan->plan_start_date && $plan->plan_end_date)
                                                <div class="flex items-center gap-2 text-sm font-medium text-gray-900">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    {{ $plan->plan_start_date->format('d M Y') }} - {{ $plan->plan_end_date->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 italic">Belum Diisi</span>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Narasi Kegiatan</p>
                                            <p class="text-sm text-gray-700 leading-relaxed">
                                                {{ $plan->plan_desc ?? 'Belum ada deskripsi rencana.' }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Dokumen</p>
                                            @if ($plan->plan_doc)
                                                @php $cleanPlanDoc = str_replace(['public/', 'public\\'], '', $plan->plan_doc); @endphp
                                                <a href="{{ asset('storage/' . $cleanPlanDoc) }}" target="_blank" 
                                                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium hover:bg-blue-100 transition-colors">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                                    Lihat Bukti Rencana
                                                </a>
                                            @else
                                                <p class="text-sm text-gray-400 italic">Tidak ada dokumen dilampirkan</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 bg-gray-50/30">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-1 h-6 bg-emerald-600 rounded-full"></div>
                                        <h3 class="font-bold text-gray-900 text-lg">Realisasi</h3>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Periode Aktual</p>
                                            @if($final)
                                                <div class="flex items-center gap-2 text-sm font-medium text-gray-900">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    {{ $final->actual_started->format('d M Y') }} - {{ $final->actual_ended->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Belum Terealisasi
                                                </span>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Narasi Hasil</p>
                                            <p class="text-sm text-gray-700 leading-relaxed">
                                                {{ optional($final)->final_desc ?? '-' }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kendala & Solusi</p>
                                            <div class="space-y-3">
                                                @forelse(optional($final)->struggles ?? [] as $s)
                                                    <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded-r-md">
                                                        <div class="mb-2">
                                                            <span class="text-xs font-bold text-red-600 uppercase">Kendala:</span>
                                                            <p class="text-sm text-gray-800">{{ $s->struggle_desc }}</p>
                                                        </div>
                                                        <div>
                                                            <span class="text-xs font-bold text-green-600 uppercase">Solusi:</span>
                                                            <p class="text-sm text-gray-800">{{ $s->solution_desc }}</p>
                                                        </div>
                                                        @if($s->solution_doc)
                                                            @php $cleanSolDoc = str_replace(['public/', 'public\\'], '', $s->solution_doc); @endphp
                                                            <a href="{{ asset('storage/' . $cleanSolDoc) }}" target="_blank" class="mt-2 inline-flex items-center text-xs text-blue-600 hover:underline">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                                                Bukti Solusi
                                                            </a>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <p class="text-sm text-gray-400 italic">Tidak ada kendala dilaporkan.</p>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 pt-2">
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rencana Selanjutnya</p>
                                                <p class="text-sm text-gray-700">{{ optional($final)->next_step ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Bukti Realisasi</p>
                                                @if (optional($final)->final_doc)
                                                    @php $cleanFinalDoc = str_replace(['public/', 'public\\'], '', $final->final_doc); @endphp
                                                    <a href="{{ asset('storage/' . $cleanFinalDoc) }}" target="_blank" 
                                                       class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 text-sm font-medium hover:bg-emerald-100 transition-colors">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                                        Dokumen Realisasi
                                                    </a>
                                                @else
                                                    <p class="text-sm text-gray-400 italic">Tidak ada dokumen</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(auth()->check() && (auth()->user()->role === 'ketua_tim' || auth()->user()->role === 'admin' || auth()->user()->role === 'operator'))
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                                @if(auth()->user()->role === 'ketua_tim' || auth()->user()->role === 'admin')
                                    <div x-data="{ showConfirm: false }">
                                        <button @click="showConfirm = true" type="button" class="inline-flex items-center px-3 py-1.5 border border-red-200 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none">
                                            Hapus
                                        </button>
                                        <div x-show="showConfirm" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full p-6">
                                                    <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
                                                    <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus tahapan <b>{{ $plan->plan_name }}</b>?</p>
                                                    <div class="mt-4 flex justify-end gap-2">
                                                        <button @click="showConfirm = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm">Batal</button>
                                                        <form action="{{ route('plans.destroy', $plan->step_plan_id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <button @click="editMode = true" class="inline-flex items-center px-4 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 hover:text-blue-600 focus:outline-none">
                                    Edit Data
                                </button>
                            </div>
                            @endif
                        </div>

                        <div x-show="editMode" x-cloak class="p-6 bg-gray-50 border-t border-gray-100">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                <div class="flex space-x-1 mb-6 bg-gray-100 p-1 rounded-lg w-fit">
                                    <button type="button" 
                                            class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200"
                                            :class="tab === 'rencana' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                            @click="tab = 'rencana'">
                                        Edit Rencana
                                    </button>
                                    <button type="button"
                                            class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200"
                                            :class="tab === 'realisasi' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                            @click="tab = 'realisasi'">
                                        Edit Realisasi
                                    </button>
                                </div>  

                                <div>
                                    <form x-show="tab === 'rencana'" class="space-y-4" method="POST" action="{{ route('plans.update', $plan->step_plan_id) }}" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        @include('detail.form-rencana', ['plan' => $plan])
                                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                            <button type="button" @click="editMode = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                                            <button type="submit" :disabled="formIsInvalid" :class="formIsInvalid ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-800'" class="px-4 py-2 bg-blue-700 text-white rounded-lg text-sm font-medium shadow-sm transition-colors">Simpan Perubahan</button>
                                        </div>
                                    </form>

                                    <form x-show="tab === 'realisasi'" class="space-y-4" method="POST" action="{{ route('finals.update', $plan->step_plan_id) }}" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        @php
                                            $final = $plan->stepsFinals ?? new \App\Models\StepsFinal();
                                            $struggle = $final->struggles->first() ?? new \App\Models\Struggle();
                                        @endphp
                                        @include('detail.form-realisasi', ['final' => $final, 'struggle' => $struggle])
                                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                            <button type="button" @click="editMode = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                                            <button type="submit" :disabled="formIsInvalid" :class="formIsInvalid ? 'opacity-50 cursor-not-allowed' : 'hover:bg-emerald-700'" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium shadow-sm transition-colors">Simpan Realisasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>  
                @endforeach 
                <div id="no-results-message" class="text-center py-12 hidden">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900">Tidak ada hasil ditemukan</h3>
                    <p class="text-sm text-gray-500 mt-1">Coba kata kunci lain atau periksa ejaan Anda.</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateStruggleIndices(wrapper) {
                const items = wrapper.querySelectorAll('.struggle-item');
                
                items.forEach((item, index) => {
                    const titleSpan = item.querySelector('.struggle-number');
                    if (titleSpan) {
                        titleSpan.innerText = `#${index + 1} Kendala & Solusi`;
                    }

                    item.querySelectorAll('input, textarea, select').forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/\[\d+\]/, `[${index}]`);
                            input.setAttribute('name', newName);
                        }
                    });

                    const removeBtn = item.querySelector('.remove-struggle-btn');
                    if (removeBtn) {
                        if (index === 0) {
                            removeBtn.classList.add('hidden');
                        } else {
                            removeBtn.classList.remove('hidden');
                        }
                    }
                });
            }

            // --- EVENT LISTENER UTAMA ---
            document.addEventListener('click', function(e) {
                
                if (e.target.closest('.add-struggle-button')) {
                    e.preventDefault();
                    const button = e.target.closest('.add-struggle-button');
                    const form = button.closest('form');
                    
                    let wrapper = form.querySelector('.struggles-wrapper');
                    if (!wrapper) wrapper = button.previousElementSibling;

                    if (wrapper) {
                        const items = wrapper.querySelectorAll('.struggle-item');
                        if (items.length > 0) {
                            const lastItem = items[items.length - 1];
                            const clone = lastItem.cloneNode(true);
                            
                            clone.querySelectorAll('input, textarea, select').forEach(input => {
                                input.value = ''; 
                            });

                            const oldFile = clone.querySelector('.file-preview-old');
                            if (oldFile) oldFile.remove();
                            wrapper.appendChild(clone);
                            updateStruggleIndices(wrapper);
                        }
                    }
                }

                // B. LOGIKA TOMBOL HAPUS 
                if (e.target.closest('.remove-struggle-btn')) {
                    e.preventDefault();
                    const btn = e.target.closest('.remove-struggle-btn');
                    const item = btn.closest('.struggle-item');
                    const wrapper = item.closest('.struggles-wrapper');
                    
                    item.remove();
                    
                    if (wrapper) {
                        updateStruggleIndices(wrapper);
                    }
                }
            });
                
            const searchInput = document.getElementById('search-input');
            const items = document.querySelectorAll('.searchable-item');

           if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const term = e.target.value.toLowerCase();
                    const noResultsMsg = document.getElementById('no-results-message');
                    let visibleCount = 0;

                    items.forEach(item => {
                        const text = item.innerText.toLowerCase();
                        
                        if(text.includes(term)) {
                            item.style.display = ''; 
                            visibleCount++; 
                        } else {
                            item.style.display = 'none'; 
                        }
                    });
                    
                    if (visibleCount === 0) {
                        noResultsMsg.classList.remove('hidden');
                    } else {
                        noResultsMsg.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>