<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        return Auth::check() ? redirect()->route('dashboard') : view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['username' => ['required', 'string'], 'password' => ['required', 'string']]);
        $user = User::whereRaw('LOWER(username) = ?', [mb_strtolower(trim($credentials['username']))])->first();

        if (! $user || ! password_verify($credentials['password'], $user->password)) {
            return back()->withErrors(['username' => 'Username atau password tidak sesuai.'])->withInput();
        }

        if ($user->siswa && $user->siswa->status !== 'aktif') {
            return back()->withErrors(['username' => 'Akun siswa sudah dinonaktifkan. Hubungi pihak sekolah.'])->withInput();
        }

        // The current users table does not have remember_token, so use a session-only login.
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
