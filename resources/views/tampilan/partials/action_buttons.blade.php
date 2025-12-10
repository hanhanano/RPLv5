<div class="flex flex-col gap-1 w-full items-center">
    @if(auth()->check() && in_array(auth()->user()->role, ['ketua_tim', 'admin']))
        {{-- Tombol Edit --}}
        <button onclick="openEditModal({{ json_encode($item) }}, '{{ route('target.update', $item->id) }}')"
            class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
            Edit
        </button>

        {{-- Tombol Hapus --}}
        <form action="{{ route('target.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')" class="w-full">
            @csrf @method('DELETE')
            <button type="submit" 
                class="flex items-center justify-center gap-2 w-full px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">
                Hapus
            </button>
        </form>
    @endif
</div>