<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Notifikasi::with(['siswa.user', 'aturanThreshold']);

        if ($request->user()->role->nama_role === 'Siswa') {
            $siswa = $request->user()->siswa;
            $query->where('siswa_id', $siswa->id);
        } elseif ($request->user()->hasRole('Wali Kelas')) {
            $query->whereHas('siswa', function ($siswaQuery) use ($request) {
                if ($request->user()->role->nama_role === 'Wali Kelas') {
                    $siswaQuery->where('kelas_id', $request->user()->kelas?->id);
                }
            });
        }

        if ($request->filled('is_read')) {
            if ($request->is_read === 'true') {
                $query->whereNotNull('dibaca_pada');
            } else {
                $query->whereNull('dibaca_pada');
            }
        }

        if ($request->filled('is_resolved')) {
            $query->where('is_resolved', $request->is_resolved === 'true');
        }

        $limit = $request->filled('limit') ? $request->limit : 15;
        $notifikasi = $query->latest()->paginate($limit);

        return response()->json($notifikasi, 200);
    }

    public function markAsRead(Request $request, $id)
    {
        $notifikasi = $this->ownedQuery($request)->findOrFail($id);
        $notifikasi->update(['dibaca_pada' => now()]);

        return response()->json($notifikasi, 200);
    }

    public function resolve(Request $request, $id)
    {
        $notifikasi = $this->ownedQuery($request)->findOrFail($id);
        $notifikasi->update(['is_resolved' => true]);

        return response()->json($notifikasi, 200);
    }

    public function unreadCount(Request $request)
    {
        $query = $this->ownedQuery($request)->whereNull('dibaca_pada');

        return response()->json([
            'unread_count' => $query->count(),
        ], 200);
    }

    private function ownedQuery(Request $request)
    {
        $query = Notifikasi::query();
        $user = $request->user();

        if ($user->role->nama_role === 'Siswa') {
            return $query->where('siswa_id', $user->siswa?->id);
        }

        if ($user->role->nama_role === 'Wali Kelas') {
            return $query->whereHas('siswa', fn ($siswaQuery) => $siswaQuery->where('kelas_id', $user->kelas?->id));
        }

        if ($user->role->nama_role === 'Guru BK') {
            return $query->whereHas('siswa');
        }

        return $query;
    }
}
