<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Membedakan tampilan admin
            // if ($user->role === 'admin') {
            //     return redirect()->route('adminpage');
            // }

            return redirect()->route('daftarpublikasi');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request) {
    $request->validate([
        'current_password'      => 'required',
        'password'              => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    return back()->with('success', 'Password berhasil diperbarui.');
    }

}