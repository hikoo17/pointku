<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Sesi Anda telah berakhir. Silakan masuk kembali.'], 401)
                : redirect()->route('login');
        }

        if (! in_array($request->user()->role->nama_role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        if ($request->user()->siswa && $request->user()->siswa->status !== 'aktif') {
            if ($request->expectsJson()) {
                $request->user()->currentAccessToken()?->delete();

                return response()->json(['message' => 'Akun siswa sudah dinonaktifkan.'], 403);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['username' => 'Akun siswa sudah dinonaktifkan. Hubungi pihak sekolah.']);
        }

        return $next($request);
    }
}
