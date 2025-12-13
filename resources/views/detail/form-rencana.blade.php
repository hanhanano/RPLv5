<div class="space-y-5">
    <input type="hidden" name="edit_type" value="simple">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-1">
            <label class="block text-sm font-semibold text-gray-700">Tanggal Mulai Rencana</label>
            <input type="date" name="plan_start_date" x-model="plan_start_date" @input="validateDates('rencana')" required
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-2.5">
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-semibold text-gray-700">Tanggal Selesai Rencana</label>
            <input type="date" name="plan_end_date" x-model="plan_end_date" @input="validateDates('rencana')" value="{{ old('plan_end_date', $plan->plan_end_date ? $plan->plan_end_date->format('Y-m-d') : '') }}" required
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm py-2.5">
        </div>
        
        <div x-show="datesAreInvalid" x-transition class="md:col-span-2 flex items-center p-3 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">Kesalahan Tanggal:</span> Tanggal selesai tidak boleh lebih awal dari tanggal mulai.
            </div>
        </div>
    </div>

    <div class="space-y-1">
        <label for="plan_desc" class="block text-sm font-semibold text-gray-700">Narasi Rencana</label>
        <textarea 
            x-model="plan_desc"
            @input="updateFormValidity()" 
            @change="updateFormValidity()"
            name="plan_desc" 
            id="plan_desc" 
            rows="4"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors sm:text-sm p-3"
            placeholder="Deskripsikan rencana kegiatan untuk tahapan ini..."></textarea>

         <template x-if="plan_desc.trim() !== '' && !isTextValid(plan_desc)">
            <div class="mt-2 text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100">
                <p class="font-bold mb-1">Teks tidak valid:</p>
                <ul class="list-disc ml-4 space-y-0.5">
                    <li x-show="hasInvalidChars(plan_desc)">Mengandung karakter khusus yang dilarang.</li>
                    <li x-show="!hasMinWords(plan_desc)">Minimal harus terdiri dari 3 kata.</li>
                </ul>
            </div>
        </template>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 border-dashed">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Dokumen Pendukung Rencana</label>
        <input type="file" name="plan_doc" 
            @change="handleFileChange($event, 'hasPlanDoc')"
            class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-xs file:font-semibold
            file:bg-blue-50 file:text-blue-700
            hover:file:bg-blue-100
            transition-all cursor-pointer">
        
        <p class="mt-1 text-xs text-gray-500">Format: PNG, JPG, PDF, DOCX (Max. 2MB)</p>

        <p x-show="fileSizeError" class="text-sm text-red-600 mt-1 font-medium">⚠️ Ukuran file melebihi 2MB.</p>
        <p x-show="docTypeError" class="text-sm text-red-600 mt-1 font-medium">⚠️ Tipe file tidak diizinkan.</p>

        {{-- Tampilkan nama dokumen lama jika ada --}}
        @if ($plan->plan_doc)
            <div class="mt-3 flex items-center p-2 bg-white border border-gray-200 rounded-md">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-500">File Saat Ini:</p>
                    <a href="{{ asset('storage/' . $plan->plan_doc) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 truncate block">
                        {{ basename($plan->plan_doc) }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>