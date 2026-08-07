<?php

namespace Tests\Feature;

use App\Models\CatatanPoin;
use App\Models\Kelas;
use App\Models\LaporanKesiswaan;
use App\Models\Notifikasi;
use App\Models\Siswa;
use App\Models\SuratPanggilan;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LargeDemoDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_large_consistent_demo_dataset(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertGreaterThanOrEqual(8, Kelas::count());
        $this->assertGreaterThanOrEqual(160, Siswa::count());
        $this->assertGreaterThanOrEqual(500, CatatanPoin::count());
        $this->assertGreaterThan(0, LaporanKesiswaan::count());
        $this->assertGreaterThan(0, Notifikasi::count());
        $this->assertGreaterThan(0, SuratPanggilan::count());

        $student = Siswa::where('total_poin_pelanggaran', '>', 0)->firstOrFail();
        $approvedPoints = $student->catatanPoin()
            ->where('status_validasi', 'disetujui')
            ->whereHas('kategoriPoin', fn ($query) => $query->where('jenis', 'pelanggaran'))
            ->with('kategoriPoin')
            ->get()
            ->sum(fn ($record) => $record->kategoriPoin->bobot_poin);

        $this->assertSame($approvedPoints, $student->total_poin_pelanggaran);
    }
}
