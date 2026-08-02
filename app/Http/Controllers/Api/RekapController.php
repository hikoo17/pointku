<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function siswa(Request $request)
    {
        $siswa = $request->user()->siswa;

        if (! $siswa) {
            return response()->json(['message' => 'No student profile found for this user'], 404);
        }

        return response()->json([
            'siswa' => $siswa->load('kelas'),
            'total_poin_pelanggaran' => $siswa->total_poin_pelanggaran,
            'total_poin_apresiasi' => $siswa->total_poin_apresiasi,
            'saldo_poin' => $siswa->saldo_poin,
            'notifikasi_terbuka' => $siswa->notifikasi()->whereNull('dibaca_pada')->count(),
        ], 200);
    }

    public function riwayat(Request $request, $siswa_id = null)
    {
        $query = CatatanPoin::with(['kategoriPoin'])->where('status_validasi', 'disetujui');

        if ($request->user()->role->nama_role === 'Siswa') {
            $siswa = $request->user()->siswa;
            $query->where('siswa_id', $siswa->id);
        } elseif ($siswa_id || $request->filled('siswa_id')) {
            $requestedSiswaId = $siswa_id ?: $request->siswa_id;

            if ($request->user()->hasRole('Wali Kelas')) {
                $query->where('siswa_id', $requestedSiswaId)
                    ->whereHas('siswa', fn ($q) => $q->where('kelas_id', $request->user()->kelas?->id));
            } else {
                $query->where('siswa_id', $requestedSiswaId);
            }
        }

        if ($request->filled('jenis')) {
            $query->whereHas('kategoriPoin', fn ($q) => $q->where('jenis', $request->jenis));
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        $limit = $request->filled('limit') ? $request->limit : 15;
        $riwayat = $query->latest()->paginate($limit);

        return response()->json($riwayat, 200);
    }

    public function kelas(Request $request)
    {
        $user = $request->user();

        if ($user->role->nama_role !== 'Wali Kelas') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $kelas = $user->kelas;
        if (! $kelas) {
            return response()->json(['message' => 'No class assigned to this teacher'], 404);
        }

        $siswa = $kelas->siswa()->get();

        $data = $siswa->map(function ($s) {
            return [
                'id' => $s->id,
                'nisn' => $s->nisn,
                'nama' => $s->user->nama_lengkap,
                'kelas' => $s->kelas->nama_kelas,
                'total_poin_pelanggaran' => $s->total_poin_pelanggaran,
                'total_poin_apresiasi' => $s->total_poin_apresiasi,
                'saldo_poin' => $s->saldo_poin,
            ];
        });

        return response()->json($data, 200);
    }

    public function kategoriPoin()
    {
        $kategori = KategoriPoin::all();

        return response()->json($kategori, 200);
    }
}
