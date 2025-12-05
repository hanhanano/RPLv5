<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
        {
            $users = User::all();
             return view('tampilan.adminpage', compact('users'));
        }

     // Tambah user
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|unique:users,email',
            'role'     => 'required|string',
            'team'     => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'team'     => $request->team,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('adminpage')->with('success', 'User berhasil ditambahkan.');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return redirect()->route('adminpage')->with('error', 'Tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('adminpage')->with('success', 'User berhasil dihapus.');
    }

    public function search(Request $request)
{
    $query = $request->input('query');

    $users = User::select('id','name','email','role', 'team')
        ->when($query, function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('role', 'like', "%{$query}%")
              ->orWhere('team', 'like', "%{$query}%");
        })
        ->get();

    return response()->json($users);
}

// Fitur Reset Password User Lain (Khusus Admin)
    public function resetPassword(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        // 2. Cari user
        $user = User::findOrFail($id);

        // 3. Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // 4. Kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Password untuk pengguna ' . $user->name . ' berhasil diubah.');
    }
}
