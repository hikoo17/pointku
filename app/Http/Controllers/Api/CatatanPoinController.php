<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use App\Models\Siswa;
use Illuminate\Http\Request;

class CatatanPoinController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->ownedQuery($request)->with(['siswa.user', 'kategoriPoin', 'pencatat']);

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $request->kelas_id));
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        $limit = $request->filled('limit') ? $request->limit : 15;
        $catatan = $query->latest()->paginate($limit);

        return response()->json($catatan, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kategori_poin_id' => 'required|exists:kategori_poin,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'bukti_foto' => 'nullable|image|max:2048',
            'status_validasi' => 'in:draft,menunggu_validasi,disetujui,ditolak',
        ]);

        $kategori = KategoriPoin::findOrFail($request->kategori_poin_id);
        $poin = $kategori->bobot_poin;

        $buktiPath = null;
        if ($request->hasFile('bukti_foto')) {
            $buktiPath = $request->file('bukti_foto')->store('bukti-poin', 'public');
        }

        $statusValidasi = $request->user()->role->nama_role === 'Guru Pelapor'
            ? 'menunggu_validasi'
            : ($request->status_validasi ?? 'disetujui');

        $catatan = CatatanPoin::create([
            'siswa_id' => $request->siswa_id,
            'kategori_poin_id' => $request->kategori_poin_id,
            'pencatat_id' => $request->user()->id,
            'guru_id' => $request->guru_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'bukti_foto' => $buktiPath,
            'status_validasi' => $statusValidasi,
        ]);

        $catatan->load(['siswa.user', 'kategoriPoin', 'pencatat']);

        return response()->json($catatan, 201);
    }

    public function show($id)
    {
        $catatan = $this->ownedQuery(request())->with(['siswa.user', 'kategoriPoin', 'pencatat'])->findOrFail($id);

        return response()->json($catatan, 200);
    }

    public function update(Request $request, $id)
    {
        $catatan = $this->ownedQuery($request)->findOrFail($id);

        if ($request->user()->hasRole('Guru Pelapor') && $catatan->status_validasi !== 'menunggu_validasi') {
            return response()->json(['message' => 'Catatan yang sudah diproses tidak dapat diubah.'], 403);
        }

        $request->validate([
            'kategori_poin_id' => 'sometimes|exists:kategori_poin,id',
            'tanggal' => 'sometimes|date',
            'keterangan' => 'sometimes|string',
            'bukti_foto' => 'sometimes|image|max:2048',
            'status_validasi' => 'in:draft,menunggu_validasi,disetujui,ditolak',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->only([
            'kategori_poin_id', 'guru_id', 'tanggal',
            'keterangan', 'status_validasi',
        ]);

        if ($request->user()->hasRole('Guru Pelapor')) {
            unset($data['status_validasi']);
        }

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti-poin', 'public');
        }

        $catatan->update($data);
        $catatan->load(['siswa.user', 'kategoriPoin', 'pencatat']);

        return response()->json($catatan, 200);
    }

    public function destroy($id)
    {
        $request = request();
        $catatan = $this->ownedQuery($request)->findOrFail($id);

        if ($catatan->status_validasi === 'disetujui') {
            return response()->json(['message' => 'Catatan yang sudah disetujui tidak dapat dihapus.'], 403);
        }

        $catatan->delete();

        return response()->json(['message' => 'Catatan poin deleted successfully'], 200);
    }

    public function cariSiswa(Request $request)
    {
        $query = Siswa::with(['user', 'kelas']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('nisn', 'like', "%$keyword%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('nama_lengkap', 'like', "%$keyword%"));
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $limit = $request->filled('limit') ? $request->limit : 15;
        $siswa = $query->paginate($limit);

        return response()->json($siswa, 200);
    }

    private function ownedQuery(Request $request)
    {
        $query = CatatanPoin::query();

        if ($request->user()->hasRole('Guru Pelapor')) {
            $query->where('pencatat_id', $request->user()->id);
        }

        return $query;
    }
}
