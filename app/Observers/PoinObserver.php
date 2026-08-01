<?php

namespace App\Observers;

use App\Models\CatatanPoin;
use App\Models\Siswa;
use App\Services\ThresholdAlertService;

class PoinObserver
{
    public function __construct(private readonly ThresholdAlertService $thresholdAlertService) {}

    public function saved(CatatanPoin $catatan): void
    {
        $siswaIds = [$catatan->siswa_id];

        if ($catatan->wasChanged('siswa_id')) {
            $siswaIds[] = $catatan->getOriginal('siswa_id');
        }

        foreach (array_unique(array_filter($siswaIds)) as $siswaId) {
            $this->recalculateTotals((int) $siswaId, $catatan);
        }
    }

    public function deleted(CatatanPoin $catatan): void
    {
        $this->recalculateTotals($catatan->siswa_id, $catatan);
    }

    protected function recalculateTotals(int $siswaId, CatatanPoin $catatan): void
    {
        $siswa = Siswa::find($siswaId);

        if (! $siswa) {
            return;
        }

        $this->thresholdAlertService->synchronize($siswa, $catatan);
    }
}
