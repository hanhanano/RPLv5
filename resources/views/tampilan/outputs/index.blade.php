<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kelola Output Publikasi</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    
    <div>
        <x-navbar></x-navbar>
    </div>

    <main class="py-8 mt-4 md:mt-0">
        <div class="max-w-7xl mx-auto px-4 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 pt-4 mb-6">
                
                <div class="space-y-4">
                    <a href="{{ url('/dashboard') }}" 
                        class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors text-sm font-medium shadow-sm w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M14 8a.75.75 0 0 1-.75.75H4.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L4.56 7.25h8.69A.75.75 0 0 1 14 8Z" clip-rule="evenodd" />
                        </svg>
                        Kembali ke Dashboard
                    </a>

                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Output Publikasi</h1>
                        <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-600">
                            <span class="flex items-center gap-1 bg-gray-100 text-gray-800 px-2.5 py-1 rounded border border-gray-200">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ $publication->publication_report }}
                            </span>
                            <span class="flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded border border-blue-100">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                {{ $publication->publication_name }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="md:self-center">
                    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                        <button onclick="openAddModal()" 
                            class="flex gap-2 items-center bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 shadow-md hover:shadow-lg transition-all text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                            </svg>
                            Tambah Output
                        </button>
                    @endif
                </div>
            </div>

            {{-- PESAN ERROR & SUKSES--}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">Gagal Menyimpan!</strong>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- TABEL OUTPUT (TETAP SAMA) --}}
            <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-5 h-5 text-blue-600">
                            <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                            <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                        </svg>
                        Daftar Output
                    </h3>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Nama Output</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Jadwal Rencana</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Tanggal Rilis</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">File</th>
                                @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                                    <th scope="col" class="px-6 py-3 text-center font-semibold">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($publication->publicationPlans as $plan)
                            <tr class="bg-white hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $plan->plan_name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-300">
                                        {{ \Carbon\Carbon::parse($plan->plan_date)->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($plan->actual_date)
                                        <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded border border-emerald-300">
                                            {{ \Carbon\Carbon::parse($plan->actual_date)->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">- Belum rilis -</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($plan->file_path)
                                        <a href="{{ asset('storage/' . $plan->file_path) }}" target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 font-semibold hover:underline flex justify-center items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                                <path d="M8.75 2.75a.75.75 0 0 0-1.5 0v5.69L5.03 6.22a.75.75 0 0 0-1.06 1.06l3.5 3.5a.75.75 0 0 0 1.06 0l3.5-3.5a.75.75 0 0 0-1.06-1.06L8.75 8.44V2.75Z" />
                                                <path d="M3.5 9.75a.75.75 0 0 0-1.5 0v1.5A2.75 2.75 0 0 0 4.75 14h6.5A2.75 2.75 0 0 0 14 11.25v-1.5a.75.75 0 0 0-1.5 0v1.5c0 .69-.56 1.25-1.25 1.25h-6.5c-.69 0-1.25-.56-1.25-1.25v-1.5Z" />
                                            </svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <button onclick="openEditModal(
                                            '{{ $plan->id }}', 
                                            '{{ addslashes($plan->plan_name) }}', 
                                            '{{ \Carbon\Carbon::parse($plan->plan_date)->format('Y-m-d') }}', 
                                            '{{ $plan->actual_date ? \Carbon\Carbon::parse($plan->actual_date)->format('Y-m-d') : '' }}'
                                        )"
                                            class="inline-flex items-center px-3 py-1.5 {{ $plan->actual_date ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white text-xs font-medium rounded shadow-sm transition">
                                            {{ $plan->actual_date ? 'Edit' : 'Upload' }}
                                        </button>

                                        <form action="{{ route('outputs.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus output ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.49 1.45 48.627 48.627 0 0 0-7.48-1 48.549 48.549 0 0 0-7.731 1 .75.75 0 1 1-.492-1.451c1.11-.1 2.37-.201 3.655-.28v-.223c0-.827.67-1.5 1.5-1.5h3.5c.83 0 1.5.673 1.5 1.5Zm-13.5 6.845a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5h-.75v10.375c0 .966-.846 1.75-1.75 1.75H6.75c-.904 0-1.75-.784-1.75-1.75V11.328h-.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2 opacity-50">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                        <p>Belum ada output. Silakan tambah output baru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
        {{-- MODAL TAMBAH --}}
        <div id="addOutputModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background-color: rgba(0,0,0,0.5);">
            <div class="relative w-full max-w-lg p-4 mx-auto">
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all">
                    <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Tambah Rencana Output</h3>
                        <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <form action="{{ route('outputs.store', $publication->slug_publication) }}" method="POST">
                        @csrf
                        <div class="p-6 bg-white space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Output</label>
                                <input type="text" name="plan_name" required placeholder="Contoh: Publikasi Bulan Januari"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal Rencana</label>
                                <input type="date" name="plan_date" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border text-sm">
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">
                            <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm transition shadow-sm">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-md transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL EDIT --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden justify-center overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
        <div class="relative w-full max-w-4xl p-4 mx-auto mt-32 mb-10">
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all">
                <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800" id="modal-title">Edit Data Output</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-5">
                                <h4 class="text-sm font-bold text-blue-600 tracking-wide border-b pb-2 mb-4">Rencana Kegiatan</h4>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Output</label>
                                    <input type="text" name="plan_name" id="modalOutputName" required
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal Rencana</label>
                                    <input type="date" name="plan_date" id="modalPlanDate" required oninput="validateDates()"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border text-sm">
                                </div>
                            </div>
                            <div class="space-y-5 md:border-l md:pl-8 border-gray-100">
                                <h4 class="text-sm font-bold text-gray-500 tracking-wide border-b pb-2 mb-4">Realisasi</h4>
                                <div>
                                    <label for="modalActualDate" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Rilis (Realisasi)</label>
                                    <input type="date" name="actual_date" id="modalActualDate" oninput="validateDates()"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border text-sm">
                                    <p id="dateErrorMsg" class="hidden mt-2 text-xs text-red-600 font-semibold flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                        </svg>
                                        Tanggal rilis tidak boleh lebih awal dari rencana!
                                    </p>
                                </div>
                                <div>
                                    <label for="file_output" class="block text-sm font-semibold text-gray-700 mb-2">Upload File (PDF/Excel)</label>
                                    <input type="file" name="file_output" id="file_output"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer">
                                    <p class="mt-2 text-xs text-gray-500">*Maksimal 10MB. Biarkan kosong jika tidak ingin mengubah file.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm transition shadow-sm">Batal</button>
                        <button type="submit" id="btnSimpan" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal Tambah (Tetap)
        function openAddModal() {
            document.getElementById('addOutputModal').classList.remove('hidden');
            document.getElementById('addOutputModal').classList.add('flex');
        }
        function closeAddModal() {
            document.getElementById('addOutputModal').classList.add('hidden');
            document.getElementById('addOutputModal').classList.remove('flex');
        }

        // --- FUNGSI VALIDASI TANGGAL (BARU) ---
        function validateDates() {
            const planDateVal = document.getElementById('modalPlanDate').value;
            const actualDateVal = document.getElementById('modalActualDate').value;
            const btnSimpan = document.getElementById('btnSimpan');
            const errorMsg = document.getElementById('dateErrorMsg');
            const actualInput = document.getElementById('modalActualDate');

            // Reset state jika realisasi kosong
            if (!actualDateVal) {
                btnSimpan.disabled = false;
                errorMsg.classList.add('hidden');
                actualInput.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                actualInput.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
                return;
            }

            // Bandingkan Tanggal
            if (planDateVal && actualDateVal < planDateVal) {
                // ERROR: Tanggal Rilis Lebih Kecil dari Rencana
                btnSimpan.disabled = true; // Matikan tombol
                errorMsg.classList.remove('hidden'); // Munculkan pesan error
                
                // Ubah warna input jadi merah
                actualInput.classList.remove('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
                actualInput.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            } else {
                // SUKSES
                btnSimpan.disabled = false; // Hidupkan tombol
                errorMsg.classList.add('hidden'); // Sembunyikan error
                
                // Kembalikan warna input normal
                actualInput.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                actualInput.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
            }
        }

        // Fungsi Modal Edit
        function openEditModal(id, name, planDate, actualDate) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            const nameInput = document.getElementById('modalOutputName');
            const planDateInput = document.getElementById('modalPlanDate');
            const actualDateInput = document.getElementById('modalActualDate');

            form.action = `/publication-plans/${id}`;
            
            nameInput.value = name;
            planDateInput.value = planDate;
            actualDateInput.value = actualDate ? actualDate : '';

            // JALANKAN VALIDASI SAAT MODAL DIBUKA (PENTING!)
            // Untuk memastikan jika data lama ada yg salah, tombol langsung mati
            validateDates(); 

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>