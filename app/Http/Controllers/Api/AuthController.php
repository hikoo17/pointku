<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = mb_strtolower(trim($request->string('username')->toString()));
        $user = User::whereRaw('LOWER(username) = ?', [$username])->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password tidak sesuai.'],
            ]);
        }


        if ($user->siswa && $user->siswa->status !== 'aktif') {
            throw ValidationException::withMessages([
                'username' => ['Akun siswa sudah dinonaktifkan. Hubungi pihak sekolah.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        $userData = $user->load('role');
        $siswa = null;
        if ($user->role->nama_role === 'Siswa') {
            $siswa = Siswa::where('user_id', $user->id)->first();
        }

        return response()->json([
            'user' => $userData,
            'siswa' => $siswa,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('role');
        $siswa = null;
        if ($user->role->nama_role === 'Siswa') {
            $siswa = Siswa::where('user_id', $user->id)->first();
        }

        return response()->json([
            'user' => $user,
            'siswa' => $siswa,
        ], 200);
    }
}
