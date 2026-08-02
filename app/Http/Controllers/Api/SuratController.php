<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SuratPanggilan;
use App\Services\SuratPanggilanWorkflowService;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratPanggilan::with(['siswa.user', 'aturanThreshold', 'laporanKesiswaan']);

        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $limit = $request->filled('limit') ? $request->limit : 15;
        $surat = $query->latest()->paginate($limit);

        return response()->json($surat, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'aturan_threshold_id' => 'required|exists:aturan_threshold,id',
            'laporan_kesiswaan_id' => 'nullable|exists:laporan_kesiswaan,id',
            'alasan_pemanggilan' => 'required|string',
            'daftar_kejadian' => 'nullable|string',
            'total_poin' => 'required|integer',
            'tindakan_direkomendasikan' => 'required|string',
            'tanggal_surat' => 'required|date',
        ]);

        $surat = SuratPanggilan::create([
            'siswa_id' => $request->siswa_id,
            'laporan_kesiswaan_id' => $request->laporan_kesiswaan_id,
            'aturan_threshold_id' => $request->aturan_threshold_id,
            'nomor_surat' => null,
            'tanggal_surat' => $request->tanggal_surat,
            'alasan_pemanggilan' => $request->alasan_pemanggilan,
            'daftar_kejadian' => $request->daftar_kejadian,
            'total_poin' => $request->total_poin,
            'tindakan_direkomendasikan' => $request->tindakan_direkomendasikan,
            'status' => 'draft',
            'dibuat_oleh' => $request->user()->id,
        ]);

        $surat->load(['siswa.user', 'aturanThreshold']);

        return response()->json($surat, 201);
    }

    public function show($id)
    {
        $surat = $this->ownedQuery(request())->with(['siswa.user', 'aturanThreshold', 'laporanKesiswaan', 'histories.user'])->findOrFail($id);

        return response()->json($surat, 200);
    }

    public function updateStatus(Request $request, $id, SuratPanggilanWorkflowService $workflow)
    {
        $request->validate([
            'status' => 'required|in:diajukan,perlu_revisi,disetujui,dicetak,dikirim,selesai,dibatalkan',
            'catatan' => 'nullable|string|max:3000',
        ]);

        $surat = $this->ownedQuery($request)->findOrFail($id);
        $surat = $workflow->transition($surat, $request->status, $request->user(), $request->catatan);

        return response()->json($surat, 200);
    }

    public function destroy($id)
    {
        abort_unless(request()->user()->hasRole('Kesiswaan'), 403);
        $surat = SuratPanggilan::findOrFail($id);
        abort_unless(in_array($surat->status, ['draft', 'dibatalkan'], true), 422, 'Surat aktif tidak dapat dihapus.');
        $surat->delete();

        return response()->json(['message' => 'Surat panggilan deleted'], 200);
    }

    public function exportPdf($id)
    {
        $surat = SuratPanggilan::with(['siswa.user', 'aturanThreshold', 'laporanKesiswaan'])->findOrFail($id);

        $pdfContent = view('pdf.surat-panggilan', compact('surat'))->render();

        return response()->json([
            'surat' => $surat,
            'pdf_content' => base64_encode($pdfContent),
        ], 200);
    }

    private function ownedQuery(Request $request)
    {
        $query = SuratPanggilan::query();

        if ($request->user()->hasRole('Guru BK')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('laporanKesiswaan', fn ($report) => $report->where('bk_id', $request->user()->id))
                    ->orWhere('dibuat_oleh', $request->user()->id)
                    ->orWhereIn('status', ['disetujui', 'dicetak', 'dikirim', 'selesai']);
            });
        }

        return $query;
    }
}
