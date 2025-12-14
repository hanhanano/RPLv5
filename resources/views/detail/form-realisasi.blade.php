<div class="space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-1">
            <label class="block text-sm font-semibold text-gray-700">Tanggal Mulai Realisasi</label>
            <input type="date" name="actual_started" x-model="actual_started" @input="validateDates('realisasi')"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors sm:text-sm py-2.5">
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-semibold text-gray-700">Tanggal Akhir Realisasi</label>
            <input type="date" name="actual_ended" x-model="actual_ended" @input="validateDates('realisasi')"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors sm:text-sm py-2.5">
        </div>
        
        <div x-show="datesAreInvalid" x-transition class="md:col-span-2 flex items-center p-3 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50" role="alert">
            <span class="font-medium mr-1">Kesalahan:</span> Tanggal selesai tidak boleh lebih awal dari tanggal mulai.
        </div>
    </div>

    <div class="space-y-1">
        <label for="final_desc" class="block text-sm font-semibold text-gray-700">Narasi Realisasi</label>
        <textarea 
            x-model="final_desc"
            @input="updateFormValidity()" 
            @change="updateFormValidity()"
            name="final_desc"
            id="final_desc"
            rows="4"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors sm:text-sm p-3"
            placeholder="Jelaskan rincian realisasi kegiatan yang telah dilakukan..."></textarea>

        <template x-if="final_desc.trim() !== '' && !isTextValid(final_desc)">
            <div class="mt-2 text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100">
                <ul class="list-disc ml-4 space-y-0.5">
                    <li x-show="hasInvalidChars(final_desc)">Mengandung karakter khusus yang dilarang.</li>
                    <li x-show="!hasMinWords(final_desc)">Minimal harus 3 kata.</li>
                </ul>
            </div>
        </template>
    </div>

    <hr class="border-gray-200">

    <div class="space-y-3">
        <div class="flex justify-between items-center">
            <label class="block text-sm font-bold text-gray-800 tracking-wide">Daftar Kendala & Solusi</label>
        </div>

        <div class="struggles-wrapper space-y-4">
            @forelse($final->struggles as $i => $s)
                <div class="struggle-item bg-gray-50 border border-gray-200 p-4 rounded-xl relative transition-all duration-300">
                    <input type="hidden" name="struggles[{{ $i }}][struggle_id]" value="{{ $s->id }}">
                    <div class="flex items-center justify-between mb-3 border-b border-gray-200 pb-2">
                        <span class="text-sm font-bold text-gray-700 struggle-number">#{{ $i+1 }} Kendala & Solusi</span>
                        <button type="button" class="remove-struggle-btn text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded transition-colors {{ $loop->first ? 'hidden' : '' }}">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                </svg>
                                Hapus
                            </span>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Kendala</label>
                            <textarea name="struggles[{{ $i }}][struggle_desc]" rows="2" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">{{ old("struggles.$i.struggle_desc", $s->struggle_desc) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Solusi</label>
                            <textarea name="struggles[{{ $i }}][solution_desc]" rows="2" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">{{ old("struggles.$i.solution_desc", $s->solution_desc) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Bukti Solusi</label>
                            <input type="file" name="struggles[{{ $i }}][solution_doc]" accept=".png,.jpg,.jpeg,.pdf"
                                class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300">
                            
                            @if($s->solution_doc)
                                <input type="hidden" name="struggles[{{ $i }}][existing_solution_doc]" value="{{ $s->solution_doc }}">
                                <div class="mt-1 flex items-center gap-1 file-preview-old">
                                    <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                    <a href="{{ asset('storage/'.$s->solution_doc) }}" target="_blank" class="text-xs text-blue-600 hover:underline break-all">
                                        Lihat dokumen lama
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="struggle-item bg-gray-50 border border-gray-200 p-4 rounded-xl relative transition-all duration-300">
                    <input type="hidden" name="struggles[0][struggle_id]" value="">
                    <div class="flex items-center justify-between mb-3 border-b border-gray-200 pb-2">
                        <span class="text-sm font-bold text-gray-700 struggle-number">#1 Kendala & Solusi</span>
                        <button type="button" class="remove-struggle-btn hidden text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                            Hapus
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Kendala</label>
                            <textarea name="struggles[0][struggle_desc]" rows="2" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                placeholder="Jelaskan kendala yang terjadi">{{ old('struggles.0.struggle_desc', optional($struggle)->struggle_desc ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Solusi</label>
                            <textarea name="struggles[0][solution_desc]" rows="2" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                placeholder="Jelaskan solusi yang dilakukan">{{ old('struggles.0.solution_desc', optional($struggle)->solution_desc ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Bukti Solusi</label>
                            <input type="file" name="struggles[0][solution_doc]" accept=".png,.jpg,.jpeg,.pdf"
                                class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300">
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <button type="button" class="add-struggle-button mt-2 w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-emerald-700 bg-emerald-100 hover:bg-emerald-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kendala Lain
        </button>
    </div>
    
    <hr class="border-gray-200">

    <div class="space-y-1">
        <label for="next_step" class="block text-sm font-semibold text-gray-700">Tindak Lanjut Realisasi</label>
        <textarea 
            x-model="next_step"
            @input="updateFormValidity()" 
            @change="updateFormValidity()"
            name="next_step"
            id="next_step"
            rows="3"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors sm:text-sm p-3"
            placeholder="Rencana langkah selanjutnya setelah ini..."></textarea>

        <template x-if="next_step.trim() !== '' && !isTextValid(next_step)">
            <div class="mt-2 text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100">
                <ul class="list-disc ml-4 space-y-0.5">
                    <li x-show="hasInvalidChars(next_step)">Mengandung karakter khusus yang dilarang.</li>
                    <li x-show="!hasMinWords(next_step)">Minimal harus 3 kata.</li>
                </ul>
            </div>
        </template>
    </div>

    <div class="bg-emerald-50/50 p-4 rounded-lg border border-emerald-100 border-dashed">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Bukti Pendukung Realisasi</label>
        <input type="file" name="final_doc" 
            @change="handleFileChange($event, 'hasFinalDoc')"
            class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-xs file:font-semibold
            file:bg-emerald-100 file:text-emerald-700
            hover:file:bg-emerald-200
            transition-all cursor-pointer">
        
        <p class="mt-1 text-xs text-gray-500">Format: PNG, JPG, PDF, DOCX (Max. 2MB)</p>

        <p x-show="fileSizeError" class="text-sm text-red-600 mt-1 font-medium">⚠️ Ukuran file melebihi 2MB.</p>
        <p x-show="docTypeError" class="text-sm text-red-600 mt-1 font-medium">⚠️ Tipe file tidak diizinkan.</p>

        {{-- Tampilkan nama dokumen lama jika ada --}}
        @if (optional($final)->final_doc)
            <div class="mt-3 flex items-center p-2 bg-white border border-emerald-100 rounded-md">
                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-500">File Saat Ini:</p>
                    <a href="{{ asset('storage/' . $final->final_doc) }}" target="_blank" class="text-sm font-medium text-emerald-600 hover:text-emerald-800 truncate block">
                        {{ basename($final->final_doc) }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>