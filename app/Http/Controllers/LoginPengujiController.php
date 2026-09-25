<?php

namespace App\Http\Controllers;

use App\Models\LoginPenguji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginPengujiController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login-penguji');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = LoginPenguji::where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email/Username tidak ditemukan.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi admin.'])->withInput();
        }

        Auth::login($user, $request->remember);
        $user->update(['last_login_at' => now()]);

        session([
            'user_id'      => $user->id,
            'nama_penguji' => $user->nama,
            'email'        => $user->email,
            'role'         => $user->role,
            'tipe_penguji' => $user->tipe_penguji,
            'kelompok'     => $user->kelompok,
        ]);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard.penguji');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.penguji');
    }
}