<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    {{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="/js/admin.js" defer></script>

</head>
<body>
    <div>
        <x-navbar ></x-navbar>
    </div>

    <main class="py-8 mt-4 md:mt-0">
        <div class="max-w-7xl mx-auto px-4 space-y-6">
            {{-- PERBAIKAN: x-data dipindahkan ke sini agar mencakup Modal, Tabel, dan Tombol --}}
            <div class="max-w-6xl mx-auto mt-6 p-6 bg-white bordershadow border rounded-lg"
                 x-data="{ open: false, resetOpen: false, resetAction: '', resetName: '' }">
                
    <div class="flex justify-between items-center mb-3">
        <div>
            <h2 class="text-lg font-semibold text-blue-900">Daftar Pengguna/Pegawai</h2>
            </div>

        {{-- PERBAIKAN: x-data dihapus dari sini karena sudah ada di induknya --}}
        <div class="flex flex-wrap gap-2 justify-start sm:justify-end">

            @if(auth()->check() && auth()->user()->role === 'admin')
                <button 
                    @click="open = true" 
                    class="flex items-center justify-center gap-1 bg-emerald-600 text-white px-3 py-2 rounded-lg text-xs sm:text-sm shadow hover:bg-emerald-800 whitespace-nowrap min-w-[110px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                        <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                        </svg>
                    Pengguna Baru
                </button>
            @endif
            
            <div 
                x-show="open" x-cloak
                x-transition 
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                    <button 
                        @click="open = false" 
                        class="absolute top-2 right-2 text-gray-600 hover:text-red-600">
                        ✖
                    </button>
                    
                    <h2 class="text-lg font-semibold">Formulir Tambah Pengguna Baru</h2>
                    <form method="POST" action="{{ route('admin.store') }}" x-data="{ role: '' }">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" class="w-full border rounded px-3 py-2 mt-1">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="text" name="email" class="w-full border rounded px-3 py-2 mt-1">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" x-model="role" required
                                class="w-full border rounded px-3 py-2 mt-1">
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="ketua_tim">Ketua Tim</option>
                                <option value="operator">Operator/Anggota</option>
                                <option value="viewer">Viewer</option>
                            </select>
                        </div>
                        <div class="mb-3" x-show="role === 'ketua_tim' || role === 'operator'" x-transition>
                            <label class="block text-sm font-medium text-gray-700">Tim</label>
                            <select name="team" 
                                :required="role === 'ketua_tim' || role === 'operator'"
                                class="w-full border rounded px-3 py-2 mt-1">
                                <option value="">-- Pilih Tim --</option>
                                <option value="Umum">Tim Umum</option>
                                <option value="Produksi">Tim Produksi</option>
                                <option value="Distribusi">Tim Distribusi</option>
                                <option value="Neraca">Tim Neraca</option>
                                <option value="Sosial">Tim Sosial</option>
                                <option value="IPDS">Tim IPDS</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required
                                class="w-full border rounded px-3 py-2 mt-1">
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="open = false"
                                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 mt-1 border rounded-lg">
        <input 
            type="text"
            id="search_user"
            placeholder="Cari Pengguna..."
            class="w-full  px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-600 text-xs">
                    <tr>
                        <th class="px-3 py-2 border">No</th>
                        <th class="px-3 py-2 border">Nama Pengguna/Pegawai</th>
                        <th class="px-3 py-2 border">Email</th>
                        <th class="px-3 py-2 border">Role</th>
                        <th class="px-3 py-2 border">Tim</th>
                        <th class="px-3 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    @foreach($users as $index => $user)
                        <tr>
                            <td class="px-4 py-4 align-top">{{ $index + 1 }}</td>

                            <td class="px-4 py-4 align-top font-semibold text-gray-700">
                                {{ $user->name }}
                            </td>

                            <td class="px-4 py-4 align-top font-semibold text-gray-700">
                                {{ $user->email }}
                            </td>

                            <td class="px-4 py-4 align-top font-semibold text-gray-700">
                                {{ $user->role }}
                            </td>

                            <td class="px-4 py-4 align-top font-semibold text-gray-700">
                                {{ $user->team ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-center flex justify-center gap-2">

                                {{--Tombol Reset Password--}}
                                {{-- Tambahan: && auth()->id() != $user->id agar tombol tidak muncul untuk diri sendiri --}}
                                @if(auth()->check() && auth()->user()->role === 'admin' && auth()->id() != $user->id)
                                    <button 
                                        @click="resetOpen = true; 
                                                resetAction = '{{ route('admin.resetPassword', $user->id) }}'; 
                                                resetName = '{{ $user->name }}'"
                                        class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-yellow-700 bg-yellow-100 hover:bg-yellow-200 rounded-lg transition"
                                        title="Reset Password User Ini">
                                        
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7V4.5A3.5 3.5 0 0 0 8 1Zm2 6V4.5a2 2 0 1 0-4 0V7h4Z" clip-rule="evenodd" />
                                        </svg>
                                        Reset
                                    </button>
                                @endif

                                {{-- Aksi hanya untuk admin --}}
                                {{-- Tambahan: && auth()->id() != $user->id agar tombol hapus hilang untuk diri sendiri --}}
                                @if(auth()->check() && auth()->user()->role === 'admin' && auth()->id() != $user->id)
                                    <form method="POST" action="{{ route('admin.destroy', $user->id) }}" onsubmit="return confirm('Yakin hapus data pengguna ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="flex gap-1 sm:text-xs w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-600 hover:text-white">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                fill="currentColor" class="size-4">
                                                <path fill-rule="evenodd"
                                                    d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div 
        x-show="resetOpen" 
        x-cloak
        x-transition 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6 relative">
            <button 
                @click="resetOpen = false" 
                class="absolute top-2 right-2 text-gray-600 hover:text-red-600">
                ✖
            </button>
            
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Reset Password User</h2>
            <p class="text-xs text-gray-500 mb-4">Ubah password untuk: <span x-text="resetName" class="font-bold text-blue-600"></span></p>
            
            <form method="POST" :action="resetAction">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="text" name="new_password" required placeholder="Masukkan password baru..." minlength="6"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">*Minimal 6 karakter</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="resetOpen = false"
                        class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-3 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const select = document.getElementById("user_add");
        const otherInput = document.getElementById("other_input");

        if(select && otherInput){
            select.addEventListener("change", function () {
                if (this.value === "other") {
                    otherInput.style.display = "block";
                } else {
                    otherInput.style.display = "none";
                }
            });
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search_user");
    const tbody = document.querySelector("table tbody");

    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            const query = this.value;

            fetch(`{{ route('admin.search') }}?query=${query}`)
            .then(response => response.json())
                .then(data => {
                    tbody.innerHTML = "";

                    if (data.length > 0) {
                        data.forEach((item, index) => {
                            // Perhatikan: Tombol Reset Password mungkin tidak muncul di hasil pencarian ini
                            // karena script pencarian Anda sebelumnya juga belum menyertakannya.
                            tbody.innerHTML += `
                                <tr>
                                    <td class="px-4 py-4">${index + 1}</td>
                                    <td class="px-4 py-4">${item.name}</td>
                                    <td class="px-4 py-4">${item.email}</td>
                                    <td class="px-4 py-4">${item.role}</td>
                                    <td class="px-4 py-4">${item.team || '-'}</td>
                                    <td class="px-4 py-4 text-center">
                                        <form method="POST" action="/admin/${item.id}" onsubmit="return confirm('Yakin hapus data pengguna ini?')">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="flex gap-1 sm:text-xs w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-600 hover:text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                    <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-4">
                                    Tidak ada data ditemukan
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(err => console.error(err));
        });
    }
});
</script>

        </div>
    </main>
</body>
</html>