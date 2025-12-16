<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target Kinerja Tim</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 font-inter">
    
    <div>
        <x-navbar></x-navbar>
    </div>

    <main class="py-8 mt-12 md:mt-0" x-data="{ openAdd: false }">
        <div class="max-w-7xl mx-auto px-4 space-y-6">            
            <div class="bg-white border shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <h2 class="text-lg font-semibold text-blue-900">Daftar Sasaran/Laporan</h2>
                        <p class="text-sm text-gray-500">Tabel monitoring target dan realisasi</p>
                    </div>

                    <div class="flex gap-2">
                        @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                            <button @click="openAdd = true" 
                                class="flex items-center justify-center gap-1 bg-emerald-600 text-white px-3 py-2 rounded-lg hover:bg-emerald-700 shadow-md hover:shadow-lg transition-all text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                                Laporan
                            </button>
                        @endif
                    </div>
                </div>

                <div class="mb-4 mt-1 border rounded-lg">
                    <input 
                        type="text"
                        id="searchInput" 
                        placeholder="Cari Berdasarkan Nama Sasaran/Laporan..."
                        class="w-full px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="border rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse">
                            <thead class="bg-gray-100 text-gray-800 text-xs border-y">
                                <tr class="border-y">
                                    <th class="px-3 py-2" rowspan="2">No</th>
                                    <th class="px-3 py-2" rowspan="2">Nama Sasaran/Laporan</th> 
                                    <th class="px-3 py-2" rowspan="2">Nama Kegiatan</th>
                                    <th class="px-3 py-2" rowspan="2">PIC</th>
                                    <th class="px-3 py-2" rowspan="2">Jenis</th>
                                    <th class="px-3 py-2 text-center" colspan="4">Rencana Kegiatan (Target)</th>
                                    <th class="px-3 py-2 text-center" colspan="4">Realisasi Kegiatan</th>
                                    <th class="px-3 py-2" rowspan="2">Aksi</th>
                                </tr>
                                <tr class="bg-gray-100 text-xs whitespace-nowrap">
                                    <th class="px-3 py-2 text-blue-800 text-center">Triwulan I</th>
                                    <th class="px-3 py-2 text-blue-800 text-center">Triwulan II</th>
                                    <th class="px-3 py-2 text-blue-800 text-center">Triwulan III</th>
                                    <th class="px-3 py-2 text-blue-800 text-center">Triwulan IV</th>
                                    <th class="px-3 py-2 text-emerald-800 text-center">Triwulan I</th>
                                    <th class="px-3 py-2 text-emerald-800 text-center">Triwulan II</th>
                                    <th class="px-3 py-2 text-emerald-800 text-center">Triwulan III</th>
                                    <th class="px-3 py-2 text-emerald-800 text-center">Triwulan IV</th>
                                </tr>
                            </thead>
                            
                            <tbody class="divide-y divide-gray-200" id="target-table-body">
                                @forelse($targets as $index => $item)
                                
                                {{-- Cek apakah indikator spesial --}}
                                @php
                                    $isSpecial = $item->is_special ?? false;
                                @endphp
                                
                                {{-- BARIS 1: IDENTITAS + TAHAPAN/POIN --}}
                                <tr class="bg-white hover:bg-gray-50 transition border-t border-gray-200">
                                    
                                    <td class="px-4 py-4 align-top" rowspan="2">{{ $index + 1 }}</td>
                                    
                                    <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">
                                        {{ $item->report_name }}
                                        {{-- Badge untuk indikator spesial --}}
                                        @if($isSpecial)
                                            <span class="ml-2 px-2 py-0.5 text-xs bg-amber-100 text-amber-800 rounded-full">Spesial</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">
                                        {{ $item->activity_name }}
                                    </td>
                                    
                                    <td class="px-4 py-4 align-top font-semibold text-gray-700" rowspan="2">
                                        {{ $item->team_name }}
                                    </td>

                                    {{-- Jenis: Tahapan (Normal) atau Target Poin (Spesial) --}}
                                    <td class="px-4 py-4 align-top bg-blue-50 border-b border-white">
                                        <div class="text-sm font-medium text-blue-900">
                                            @if($isSpecial)
                                                Poin
                                            @else
                                                Tahapan
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Rencana/Target Q1-Q4 --}}
                                    @if($isSpecial)
                                        {{-- Indikator Spesial: Target poin per TW berbeda --}}
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-blue-900">{{ number_format($item->output_real_q1 ?? 0, 2) }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-blue-900">{{ number_format($item->output_real_q2 ?? 0, 2) }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-blue-900">{{ number_format($item->output_real_q3 ?? 0, 2) }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-blue-900">{{ number_format($item->output_real_q4 ?? 0, 2) }}</div>
                                        </td>
                                    @else
                                        {{-- Untuk q1_plan sampai q4_plan --}}
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-blue-900">
                                                {{ $item->q1_plan !== null && $item->q1_plan !== '' ? (int)$item->q1_plan : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-blue-900">
                                                {{ $item->q2_plan !== null && $item->q2_plan !== '' ? (int)$item->q2_plan : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-blue-900">
                                                {{ $item->q3_plan !== null && $item->q3_plan !== '' ? (int)$item->q3_plan : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-blue-900">
                                                {{ $item->q4_plan !== null && $item->q4_plan !== '' ? (int)$item->q4_plan : '-' }}
                                            </div>
                                        </td>
                                    @endif

                                    {{-- Realisasi Q1-Q4 --}}
                                    @if($isSpecial)
                                        {{-- Indikator Spesial: Realisasi poin dari actual_output_q1-q4 --}}
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-emerald-700">{{ number_format($item->actual_output_q1 ?? 0, 2) }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-emerald-700">{{ number_format($item->actual_output_q2 ?? 0, 2) }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-emerald-700">{{ number_format($item->actual_output_q3 ?? 0, 2) }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-emerald-700">{{ number_format($item->actual_output_q4 ?? 0, 2) }}</div>
                                        </td>
                                    @else
                                        {{-- Untuk q1_real sampai q4_real --}}
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-emerald-700">
                                                {{ $item->q1_real !== null && $item->q1_real !== '' ? (int)$item->q1_real : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-emerald-700">
                                                {{ $item->q2_real !== null && $item->q2_real !== '' ? (int)$item->q2_real : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-emerald-700">
                                                {{ $item->q3_real !== null && $item->q3_real !== '' ? (int)$item->q3_real : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-blue-50 border-b border-white">
                                            <div class="font-medium text-emerald-700">
                                                {{ $item->q4_real !== null && $item->q4_real !== '' ? (int)$item->q4_real : '-' }}
                                            </div>
                                        </td>
                                    @endif

                                    {{-- Aksi (Rowspan 2) --}}
                                    <td class="px-4 py-4 text-center align-middle" rowspan="2">
                                        <div class="flex flex-col gap-1 w-full items-center">
                                            @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                                                <button onclick="openEditModal({{ json_encode($item) }}, '{{ route('target.update', $item->id) }}')"
                                                    class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
                                                    Edit
                                                </button>
                                                <form action="{{ route('target.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')" class="w-full">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" 
                                                        class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- BARIS 2: OUTPUT (Normal) atau Kosong (Spesial - sudah dihandle di baris 1) --}}
                                <tr class="target-row-item">                       
                                    @if($isSpecial)
                                        {{-- Indikator Spesial: Baris 2 tidak diperlukan (merge semua ke baris 1) --}}
                                        <td colspan="9" class="px-4 py-2 text-center bg-gray-50 text-xs text-gray-400 italic">
                                            Indikator spesial hanya memiliki target & realisasi poin
                                        </td>
                                    @else
                                        {{-- Indikator Normal: Output --}}
                                        <td class="px-4 py-4 align-top bg-purple-50">
                                            <div class="text-sm font-medium text-purple-900">Output</div>
                                        </td>

                                        {{-- Rencana OUTPUT Q1-Q4 --}}
                                        <td class="px-4 py-4 text-center bg-purple-50">
                                            <div class="text-purple-900 font-medium">
                                                {{ $item->output_plan !== null && $item->output_plan !== '' ? (int)$item->output_plan : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-purple-50">
                                            <div class="text-purple-900 font-medium">
                                                {{ $item->output_plan !== null && $item->output_plan !== '' ? (int)$item->output_plan : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-purple-50">
                                            <div class="text-purple-900 font-medium">
                                                {{ $item->output_plan !== null && $item->output_plan !== '' ? (int)$item->output_plan : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-purple-50">
                                            <div class="text-purple-900 font-medium">
                                                {{ $item->output_plan !== null && $item->output_plan !== '' ? (int)$item->output_plan : '-' }}
                                            </div>
                                        </td>

                                        {{-- Realisasi OUTPUT Q1-Q4 --}}
                                        <td class="px-4 py-4 text-center bg-purple-50">
                                            <div class="font-medium text-purple-900">
                                                {{ $item->output_real_q1 !== null && $item->output_real_q1 !== '' ? (int)$item->output_real_q1 : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-purple-50">
                                            <div class="font-medium text-purple-900">
                                                {{ $item->output_real_q2 !== null && $item->output_real_q2 !== '' ? (int)$item->output_real_q2 : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-purple-50">
                                            <div class="font-medium text-purple-900">
                                                {{ $item->output_real_q3 !== null && $item->output_real_q3 !== '' ? (int)$item->output_real_q3 : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center bg-purple-50">
                                            <div class="font-medium text-purple-900">
                                                {{ $item->output_real_q4 !== null && $item->output_real_q4 !== '' ? (int)$item->output_real_q4 : '-' }}
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="16" class="text-center text-gray-500 py-4">Tidak ada data ditemukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer Pagination --}}
                    <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-lg flex flex-col sm:flex-row items-center justify-between gap-4 mt-2" id="pagination-container">
                        <div class="flex items-center gap-4 text-sm text-gray-700 w-full sm:w-auto">
                            <div class="flex items-center gap-2">
                                <span>Rows:</span>
                                <select id="rowsPerPage" class="border-gray-300 rounded text-sm py-1 pl-2 pr-8 focus:ring-blue-500 focus:border-blue-500 cursor-pointer shadow-sm">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                            <div id="pageInfo" class="font-medium whitespace-nowrap">Menghitung data...</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="btnPrev" class="p-1.5 rounded-md border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-gray-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                            </button>
                            <button id="btnNext" class="p-1.5 rounded-md border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-gray-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div x-show="openAdd" style="display: none;" 
             class="relative z-[250]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/50 transition-opacity"></div>
            <div class="fixed inset-0 z-[250] w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-2xl" 
                         @click.away="openAdd = false">
                        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">Tambah Target Kinerja</h3>
                            <button @click="openAdd = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <form action="{{ route('target.store') }}" method="POST" onsubmit="return validateTargetForm(this)">
                            @csrf
                            @include('tampilan.partials.form_target') 
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-2 border-t">
                                <button type="button" @click="openAdd = false" class="px-4 py-2 bg-white border rounded-lg text-sm">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div id="editModal" class="relative z-[250] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/50 transition-opacity"></div>
            <div class="fixed inset-0 z-[250] w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-2xl">
                        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">Edit Target Kinerja</h3>
                            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <form id="editForm" method="POST" action="" onsubmit="return validateTargetForm(this)">
                            @csrf
                            @method('PUT') 
                            @include('tampilan.partials.form_target') 
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-2 border-t">
                                <button type="button" onclick="closeEditModal()" 
                                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 shadow-sm">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const specialIndicators = [
            'Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar',
            'Indeks Pelayanan Publik - Penilaian Mandiri',
            'Nilai SAKIP oleh Inspektorat',
            'Indeks Implementasi BerAKHLAK'
        ];

        function validateTargetForm(form) {
            return true;
        }

        function openEditModal(data, updateUrl) {
            console.log('DEBUG - Data diterima:', data);
            console.log('DEBUG - Data actual_output_q1:', data.actual_output_q1);
            
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            if (!updateUrl) {
                alert("Error: URL Update tidak ditemukan.");
                return;
            }
            form.action = updateUrl;

            const monthlySection = form.querySelector('.monthly-options-wrapper');
            if (monthlySection) monthlySection.style.display = 'none';

            const teamInput = form.querySelector('[name="publication_pic"]');
            if(teamInput) teamInput.value = data.team_name;
            
            const activityInput = form.querySelector('[name="publication_name"]');
            if(activityInput) activityInput.value = data.activity_name;

            const setAlpineValue = (selector, value) => {
                const el = form.querySelector(selector);
                if (el) {
                    // Pastikan value adalah number, bukan string
                    if (typeof value === 'string') {
                        value = parseFloat(value) || 0;
                    }
                    el.value = value;
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    console.log('Set value:', selector, '=', value);
                } else {
                    console.warn('Element not found:', selector);
                }
            };

            const isSpecial = specialIndicators.includes(data.report_name);
            console.log('Is special indicator?', isSpecial);

            if (isSpecial) {
                // Indikator Spesial: Set target poin per TW
                // HANYA set output_real_q1 sampai output_real_q4
                setAlpineValue('input[x-model="o_q1"]', parseFloat(data.output_real_q1) || 0);
                setAlpineValue('input[x-model="o_q2"]', parseFloat(data.output_real_q2) || 0);
                setAlpineValue('input[x-model="o_q3"]', parseFloat(data.output_real_q3) || 0);
                setAlpineValue('input[x-model="o_q4"]', parseFloat(data.output_real_q4) || 0);

                // HAPUS bagian ini - karena tidak ada input actual_q di form
                // setAlpineValue('input[x-model="actual_q1"]', parseFloat(data.actual_output_q1) || 0);
                // setAlpineValue('input[x-model="actual_q2"]', parseFloat(data.actual_output_q2) || 0);
                // setAlpineValue('input[x-model="actual_q3"]', parseFloat(data.actual_output_q3) || 0);
                // setAlpineValue('input[x-model="actual_q4"]', parseFloat(data.actual_output_q4) || 0);
                
            } else {
                // Indikator Normal
                setAlpineValue('input[x-model="plan_tahapan"]', data.q1_plan || 0);
                setAlpineValue('input[x-model="t_q1"]', data.q1_real || 0);
                setAlpineValue('input[x-model="t_q2"]', data.q2_real || 0);
                setAlpineValue('input[x-model="t_q3"]', data.q3_real || 0);
                setAlpineValue('input[x-model="t_q4"]', data.q4_real || 0);

                setAlpineValue('input[x-model="plan_output"]', data.output_plan || 0);
                setAlpineValue('input[x-model="o_q1"]', data.output_real_q1 || 0);
                setAlpineValue('input[x-model="o_q2"]', data.output_real_q2 || 0);
                setAlpineValue('input[x-model="o_q3"]', data.output_real_q3 || 0);
                setAlpineValue('input[x-model="o_q4"]', data.output_real_q4 || 0);
            }
            
            const options = [
                "Laporan Statistik Kependudukan dan Ketenagakerjaan",
                "Laporan Statistik Statistik Kesejahteraan Rakyat",
                ...specialIndicators
            ];

            const selectReport = form.querySelector('[name="publication_report"]');
            const manualInput = form.querySelector('[name="publication_report_other"]');

            if (selectReport) {
                // Cari exact match atau use 'other'
                const allOptions = Array.from(selectReport.options).map(o => o.value);
                
                if (allOptions.includes(data.report_name)) {
                    selectReport.value = data.report_name;
                    selectReport.dispatchEvent(new Event('change', { bubbles: true }));
                    if(manualInput) manualInput.value = '';
                } else {
                    selectReport.value = 'other';
                    selectReport.dispatchEvent(new Event('change', { bubbles: true }));
                    setTimeout(() => {
                        if(manualInput) {
                            manualInput.value = data.report_name;
                            manualInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                    }, 50);
                }
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() { 
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden'); 
            modal.classList.remove('flex');
        }

        document.addEventListener("DOMContentLoaded", function() {
            const tbody = document.getElementById('target-table-body');
            const rowsSelect = document.getElementById('rowsPerPage');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');
            const pageInfo = document.getElementById('pageInfo');
            const searchInput = document.getElementById('searchInput');

            const allRows = Array.from(tbody.querySelectorAll('tr'));
            let dataItems = [];

            for (let i = 0; i < allRows.length; i += 2) {
                if (allRows[i] && allRows[i+1]) {
                    dataItems.push([allRows[i], allRows[i+1]]);
                } else if (allRows[i]) {
                    dataItems.push([allRows[i]]);
                }
            }

            let currentPage = 1;
            let rowsPerPage = 10;
            let filteredData = [...dataItems];

            function updatePagination() {
                const totalItems = filteredData.length;
                
                allRows.forEach(row => row.style.display = 'none');

                if (totalItems === 0) {
                    pageInfo.innerText = "0 data";
                    btnPrev.disabled = true;
                    btnNext.disabled = true;
                    return;
                }

                const totalPages = Math.ceil(totalItems / rowsPerPage);

                if (currentPage < 1) currentPage = 1;
                if (currentPage > totalPages) currentPage = totalPages;

                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                const pageItems = filteredData.slice(start, end);

                pageItems.forEach(itemPair => {
                    itemPair.forEach(tr => tr.style.display = '');
                });

                pageInfo.innerText = `${start + 1}-${Math.min(end, totalItems)} dari ${totalItems} data`;

                btnPrev.disabled = (currentPage === 1);
                btnNext.disabled = (currentPage >= totalPages);
            }

            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();

                filteredData = dataItems.filter(itemPair => {
                    const rowText = itemPair[0].innerText.toLowerCase();
                    return rowText.includes(query);
                });

                currentPage = 1;
                updatePagination();
            });

            rowsSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                updatePagination();
            });

            btnPrev.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                }
            });

            btnNext.addEventListener('click', function() {
                const totalPages = Math.ceil(filteredData.length / rowsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                }
            });

            updatePagination();
        });
    </script>
</body>
</html>
