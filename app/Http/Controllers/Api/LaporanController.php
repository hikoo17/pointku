<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApprovalLaporan;
use App\Models\LaporanKesiswaan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanKesiswaan::with(['siswa.user', 'bk', 'kesiswaan']);

        if ($request->user()->role->nama_role === 'Guru BK') {
            $query->where('bk_id', $request->user()->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $limit = $request->filled('limit') ? $request->limit : 15;
        $laporan = $query->latest()->paginate($limit);

        return response()->json($laporan, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_tindakan' => 'required|string',
            'catatan_kesiswaan' => 'nullable|string',
        ]);

        $laporan = LaporanKesiswaan::create([
            'siswa_id' => $request->siswa_id,
            'bk_id' => $request->user()->id,
            'jenis_tindakan' => $request->jenis_tindakan,
            'status' => 'pending',
            'catatan_kesiswaan' => $request->catatan_kesiswaan,
            'diajukan_pada' => now(),
        ]);

        $laporan->load(['siswa.user', 'bk']);

        return response()->json($laporan, 201);
    }

    public function show($id)
    {
        $laporan = $this->ownedQuery(request())->with(['siswa.user', 'bk', 'kesiswaan', 'approvalLaporan.approver', 'suratPanggilan'])->findOrFail($id);

        return response()->json($laporan, 200);
    }

    public function update(Request $request, $id)
    {
        $laporan = $this->ownedQuery($request)->findOrFail($id);

        if ($request->user()->hasRole('Guru BK') && $laporan->status !== 'pending') {
            return response()->json(['message' => 'Laporan yang sudah diproses tidak dapat diubah.'], 403);
        }

        $request->validate([
            'siswa_id' => 'sometimes|exists:siswa,id',
            'jenis_tindakan' => 'sometimes|string',
            'status' => 'in:pending,disetujui,ditolak',
            'catatan_kesiswaan' => 'nullable|string',
        ]);

        $laporan->update([
            'siswa_id' => $request->siswa_id ?? $laporan->siswa_id,
            'jenis_tindakan' => $request->jenis_tindakan ?? $laporan->jenis_tindakan,
            'status' => $request->status ?? $laporan->status,
            'catatan_kesiswaan' => $request->catatan_kesiswaan ?? $laporan->catatan_kesiswaan,
        ]);

        $laporan->load(['siswa.user', 'bk']);

        return response()->json($laporan, 200);
    }

    public function destroy($id)
    {
        $laporan = $this->ownedQuery($request)->findOrFail($id);

        if ($laporan->status !== 'pending') {
            return response()->json(['message' => 'Laporan ini sudah diproses.'], 422);
        }
        $laporan->delete();

        return response()->json(['message' => 'Laporan deleted'], 200);
    }

    public function approval(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'catatan_approval' => 'nullable|string',
        ]);

        $laporan = LaporanKesiswaan::findOrFail($id);
        $laporan->status = $request->status;
        $laporan->kesiswaan_id = $request->user()->id;
        $laporan->selesai_pada = $request->status === 'pending' ? null : now();
        $laporan->save();

        ApprovalLaporan::create([
            'laporan_kesiswaan_id' => $laporan->id,
            'approver_id' => $request->user()->id,
            'status' => $request->status,
            'catatan_approval' => $request->catatan_approval,
            'disetujui_pada' => $request->status === 'disetujui' ? now() : null,
        ]);

        $laporan->load(['siswa.user', 'bk', 'kesiswaan', 'approvalLaporan.approver']);

        return response()->json($laporan, 200);
    }

    private function ownedQuery(Request $request)
    {
        $query = LaporanKesiswaan::query();

        if ($request->user()->hasRole('Guru BK')) {
            $query->where('bk_id', $request->user()->id);
        }

        return $query;
    }
}
