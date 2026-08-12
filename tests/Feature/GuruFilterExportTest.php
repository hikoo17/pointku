<?php

namespace Tests\Feature;

use App\Models\AturanThreshold;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\SuratPanggilan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruFilterExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_filter_records_by_validation_status(): void
    {
        [$guru, $siswa, $category] = $this->createContext();
        $approved = $this->createRecord($siswa, $guru, $category, '2026-08-01', 'disetujui');
        $rejected = $this->createRecord($siswa, $guru, $category, '2026-08-02', 'ditolak');

        $response = $this->actingAs($guru)->get(route('guru.records', ['status' => 'disetujui']));

        $response->assertOk();
        $records = $response->viewData('records');
        $this->assertSame([$approved->id], $records->pluck('id')->all());
        $this->assertNotContains($rejected->id, $records->pluck('id')->all());
    }

    public function test_student_recap_and_export_use_the_selected_date_range(): void
    {
        [$guru, $siswa, $category] = $this->createContext();
        $this->createRecord($siswa, $guru, $category, '2026-07-20', 'disetujui');
        $this->createRecord($siswa, $guru, $category, '2026-08-05', 'disetujui');

        $filters = ['dari' => '2026-08-01', 'sampai' => '2026-08-31'];
        $response = $this->actingAs($guru)->get(route('guru.students', $filters));

        $response->assertOk();
        $student = $response->viewData('students')->first();
        $this->assertSame(10, (int) $student->periode_poin_pelanggaran);

        $export = $this->actingAs($guru)->get(route('guru.students.export', $filters));
        $export->assertOk();
        $this->assertStringContainsString('Siswa Uji', $export->streamedContent());
        $this->assertStringContainsString(',10,0,-10,', $export->streamedContent());
    }

    public function test_guru_bk_can_filter_letters_by_status_and_date(): void
    {
        [$guru, $siswa] = $this->createContext();
        $threshold = AturanThreshold::create([
            'poin_batas' => 25,
            'level' => 'ringan',
            'judul_notifikasi' => 'Pemantauan',
            'deskripsi' => 'Perlu pemantauan.',
            'has_surat_panggilan' => true,
            'is_active' => true,
        ]);
        $matching = $this->createLetter($siswa, $threshold, '2026-08-10', 'draft');
        $this->createLetter($siswa, $threshold, '2026-07-10', 'selesai');

        $response = $this->actingAs($guru)->get(route('guru.letters', [
            'status' => 'draft',
            'dari' => '2026-08-01',
            'sampai' => '2026-08-31',
        ]));

        $response->assertOk();
        $this->assertSame([$matching->id], $response->viewData('letters')->pluck('id')->all());
    }

    private function createContext(): array
    {
        $guruRole = Role::create(['nama_role' => 'Guru BK']);
        $studentRole = Role::create(['nama_role' => 'Siswa']);
        $homeroomRole = Role::create(['nama_role' => 'Wali Kelas']);
        $guru = User::create(['username' => 'guru.bk', 'nama_lengkap' => 'Guru BK', 'role_id' => $guruRole->id, 'password' => 'password123']);
        $homeroom = User::create(['username' => 'wali', 'nama_lengkap' => 'Wali Kelas', 'role_id' => $homeroomRole->id, 'password' => 'password123']);
        $studentUser = User::create(['username' => 'siswa', 'nama_lengkap' => 'Siswa Uji', 'role_id' => $studentRole->id, 'password' => 'password123']);
        $class = Kelas::create(['nama_kelas' => 'XI IPA 1', 'wali_kelas_id' => $homeroom->id]);
        $student = Siswa::create(['user_id' => $studentUser->id, 'kelas_id' => $class->id, 'nisn' => '0011223344', 'jenis_kelamin' => 'L']);
        $category = KategoriPoin::create(['jenis' => 'pelanggaran', 'nama_kategori' => 'Terlambat', 'bobot_poin' => 10, 'tingkat' => 'ringan']);

        return [$guru, $student, $category];
    }

    private function createRecord(Siswa $siswa, User $guru, KategoriPoin $category, string $date, string $status): CatatanPoin
    {
        return CatatanPoin::create([
            'siswa_id' => $siswa->id,
            'kategori_poin_id' => $category->id,
            'pencatat_id' => $guru->id,
            'tanggal' => $date,
            'keterangan' => 'Catatan pengujian',
            'status_validasi' => $status,
        ]);
    }

    private function createLetter(Siswa $siswa, AturanThreshold $threshold, string $date, string $status): SuratPanggilan
    {
        return SuratPanggilan::create([
            'siswa_id' => $siswa->id,
            'aturan_threshold_id' => $threshold->id,
            'nomor_surat' => 'SP-'.uniqid(),
            'tanggal_surat' => $date,
            'alasan_pemanggilan' => 'Pengujian',
            'total_poin' => 25,
            'tindakan_direkomendasikan' => 'Pemantauan',
            'status' => $status,
        ]);
    }
}
