<?php

namespace App\Services;

use App\Models\AturanThreshold;
use App\Models\CatatanPoin;
use App\Models\Notifikasi;
use App\Models\Siswa;
use App\Models\SuratPanggilan;
use Illuminate\Support\Facades\DB;

class ThresholdAlertService
{
    public function synchronize(Siswa $siswa, CatatanPoin $catatan): void
    {
        DB::transaction(function () use ($siswa, $catatan) {
            $totalSebelumnya = (int) $siswa->total_poin_pelanggaran;
            $totals = $this->calculateTotals($siswa->id);

            $siswa->update([
                'total_poin_pelanggaran' => $totals['pelanggaran'],
                'total_poin_apresiasi' => $totals['apresiasi'],
            ]);

            if ($totals['pelanggaran'] <= $totalSebelumnya) {
                return;
            }

            $thresholds = AturanThreshold::query()
                ->where('is_active', true)
                ->where('poin_batas', '>', $totalSebelumnya)
                ->where('poin_batas', '<=', $totals['pelanggaran'])
                ->orderBy('poin_batas')
                ->get();

            foreach ($thresholds as $threshold) {
                $this->createNotification($siswa, $catatan, $threshold, $totals['pelanggaran']);

                if ($threshold->has_surat_panggilan) {
                    $this->createDraftLetter($siswa, $threshold, $totals['pelanggaran']);
                }
            }
        });
    }

    private function calculateTotals(int $siswaId): array
    {
        $totals = CatatanPoin::query()
            ->join('kategori_poin', 'kategori_poin.id', '=', 'catatan_poin.kategori_poin_id')
            ->where('catatan_poin.siswa_id', $siswaId)
            ->where('catatan_poin.status_validasi', 'disetujui')
            ->selectRaw("COALESCE(SUM(CASE WHEN kategori_poin.jenis = 'pelanggaran' THEN kategori_poin.bobot_poin ELSE 0 END), 0) AS pelanggaran")
            ->selectRaw("COALESCE(SUM(CASE WHEN kategori_poin.jenis = 'apresiasi' THEN kategori_poin.bobot_poin ELSE 0 END), 0) AS apresiasi")
            ->first();

        return [
            'pelanggaran' => (int) $totals->pelanggaran,
            'apresiasi' => (int) $totals->apresiasi,
        ];
    }

    private function createNotification(
        Siswa $siswa,
        CatatanPoin $catatan,
        AturanThreshold $threshold,
        int $totalPoin
    ): void {
        Notifikasi::firstOrCreate(
            [
                'siswa_id' => $siswa->id,
                'aturan_threshold_id' => $threshold->id,
            ],
            [
                'level' => $threshold->level,
                'judul' => $threshold->judul_notifikasi,
                'pesan' => $threshold->deskripsi.' Total poin: '.$totalPoin,
                'notifikasiable_type' => CatatanPoin::class,
                'notifikasiable_id' => $catatan->id,
                'is_resolved' => false,
            ]
        );
    }

    private function createDraftLetter(Siswa $siswa, AturanThreshold $threshold, int $totalPoin): void
    {
        SuratPanggilan::firstOrCreate(
            [
                'siswa_id' => $siswa->id,
                'aturan_threshold_id' => $threshold->id,
            ],
            [
                'nomor_surat' => $this->generateLetterNumber($siswa, $threshold),
                'tanggal_surat' => now()->toDateString(),
                'alasan_pemanggilan' => $threshold->deskripsi,
                'daftar_kejadian' => $this->getViolationSummary($siswa->id),
                'total_poin' => $totalPoin,
                'tindakan_direkomendasikan' => $threshold->judul_notifikasi,
                'status' => 'draft',
                'catatan' => $threshold->template_surat
                    ? 'Template: '.$threshold->template_surat
                    : 'Dibuat otomatis oleh sistem threshold.',
            ]
        );
    }

    private function generateLetterNumber(Siswa $siswa, AturanThreshold $threshold): string
    {
        return sprintf(
            'AUTO/%s/S%06d/T%03d',
            now()->format('Ym'),
            $siswa->id,
            $threshold->id
        );
    }

    private function getViolationSummary(int $siswaId): string
    {
        return CatatanPoin::query()
            ->with('kategoriPoin:id,nama_kategori,bobot_poin')
            ->where('siswa_id', $siswaId)
            ->where('status_validasi', 'disetujui')
            ->whereHas('kategoriPoin', fn ($query) => $query->where('jenis', 'pelanggaran'))
            ->orderByDesc('tanggal')
            ->get()
            ->map(fn (CatatanPoin $catatan) => sprintf(
                '%s - %s (%d poin): %s',
                $catatan->tanggal->format('d-m-Y'),
                $catatan->kategoriPoin->nama_kategori,
                $catatan->kategoriPoin->bobot_poin,
                $catatan->keterangan
            ))
            ->implode("\n");
    }
}
