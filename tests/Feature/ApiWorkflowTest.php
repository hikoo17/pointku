<?php

namespace Tests\Feature;

use App\Models\AturanThreshold;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use App\Models\SuratPanggilan;
use App\Services\SuratPanggilanWorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_access_role_protected_endpoint(): void
    {
        $role = Role::create(['nama_role' => 'Guru BK']);
        $user = User::create([
            'username' => 'guru.bk',
            'nama_lengkap' => 'Guru BK',
            'role_id' => $role->id,
            'password' => 'rahasia123',
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'guru.bk',
            'password' => 'rahasia123',
        ]);

        $response->assertOk()->assertJsonStructure(['user', 'token']);

        $this->withToken($response->json('token'))
            ->getJson('/api/catatan-poin')
            ->assertOk();

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_login_normalizes_username_whitespace_and_case(): void
    {
        $role = Role::create(['nama_role' => 'Siswa']);
        User::create([
            'username' => 'siswa',
            'nama_lengkap' => 'Siswa Uji',
            'role_id' => $role->id,
            'password' => 'password123',
        ]);

        $this->postJson('/api/login', [
            'username' => '  SISWA  ',
            'password' => 'password123',
        ])->assertOk()->assertJsonStructure(['user', 'token']);
    }

    public function test_point_totals_follow_approved_records_only(): void
    {
        [$siswa, $pencatat] = $this->createStudentAndRecorder();
        $pelanggaran = KategoriPoin::create([
            'jenis' => 'pelanggaran',
            'nama_kategori' => 'Terlambat',
            'bobot_poin' => 10,
            'tingkat' => 'ringan',
        ]);
        $apresiasi = KategoriPoin::create([
            'jenis' => 'apresiasi',
            'nama_kategori' => 'Prestasi',
            'bobot_poin' => 15,
            'tingkat' => 'ringan',
        ]);

        $catatan = CatatanPoin::create([
            'siswa_id' => $siswa->id,
            'kategori_poin_id' => $pelanggaran->id,
            'pencatat_id' => $pencatat->id,
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Datang terlambat',
            'status_validasi' => 'draft',
        ]);

        $this->assertSame(0, $siswa->refresh()->total_poin_pelanggaran);

        $catatan->update(['status_validasi' => 'disetujui']);
        $this->assertSame(10, $siswa->refresh()->total_poin_pelanggaran);

        $catatan->update(['kategori_poin_id' => $apresiasi->id]);
        $this->assertSame(0, $siswa->refresh()->total_poin_pelanggaran);
        $this->assertSame(15, $siswa->total_poin_apresiasi);

        $catatan->update(['status_validasi' => 'ditolak']);
        $this->assertSame(0, $siswa->refresh()->total_poin_apresiasi);

        $catatan->delete();
        $this->assertSame(0, $siswa->refresh()->total_poin_pelanggaran);
        $this->assertSame(0, $siswa->total_poin_apresiasi);
    }

    public function test_crossing_threshold_creates_notification_and_letter_draft_once(): void
    {
        [$siswa, $pencatat] = $this->createStudentAndRecorder();
        $pelanggaran = KategoriPoin::create([
            'jenis' => 'pelanggaran',
            'nama_kategori' => 'Pelanggaran sedang',
            'bobot_poin' => 30,
            'tingkat' => 'sedang',
        ]);
        $ringan = AturanThreshold::create([
            'poin_batas' => 25,
            'level' => 'ringan',
            'judul_notifikasi' => 'Peringatan Ringan',
            'deskripsi' => 'Perlu pemantauan.',
            'has_surat_panggilan' => false,
            'is_active' => true,
        ]);
        $sedang = AturanThreshold::create([
            'poin_batas' => 50,
            'level' => 'sedang',
            'judul_notifikasi' => 'Panggilan Orang Tua',
            'deskripsi' => 'Perlu panggilan orang tua.',
            'template_surat' => 'surat_panggilan_ortu_level_1',
            'has_surat_panggilan' => true,
            'is_active' => true,
        ]);

        $this->createApprovedViolation($siswa, $pencatat, $pelanggaran, 'Pelanggaran pertama');

        $this->assertDatabaseHas('notifikasi', [
            'siswa_id' => $siswa->id,
            'aturan_threshold_id' => $ringan->id,
        ]);
        $this->assertDatabaseCount('surat_panggilan', 0);

        $this->createApprovedViolation($siswa, $pencatat, $pelanggaran, 'Pelanggaran kedua');

        $this->assertSame(60, $siswa->refresh()->total_poin_pelanggaran);
        $this->assertDatabaseHas('notifikasi', [
            'siswa_id' => $siswa->id,
            'aturan_threshold_id' => $sedang->id,
        ]);
        $this->assertDatabaseHas('surat_panggilan', [
            'siswa_id' => $siswa->id,
            'aturan_threshold_id' => $sedang->id,
            'total_poin' => 60,
            'status' => 'draft',
        ]);

        $this->createApprovedViolation($siswa, $pencatat, $pelanggaran, 'Pelanggaran ketiga');

        $this->assertDatabaseCount('notifikasi', 2);
        $this->assertDatabaseCount('surat_panggilan', 1);
    }

    public function test_highest_letter_threshold_creates_a_new_draft_at_each_multiple(): void
    {
        [$siswa, $pencatat] = $this->createStudentAndRecorder();
        $pelanggaran = KategoriPoin::create([
            'jenis' => 'pelanggaran',
            'nama_kategori' => 'Pelanggaran berat',
            'bobot_poin' => 100,
            'tingkat' => 'berat',
        ]);
        $berat = AturanThreshold::create([
            'poin_batas' => 100,
            'level' => 'berat',
            'judul_notifikasi' => 'Peringatan Berat',
            'deskripsi' => 'Perlu tindak lanjut BK dan Kesiswaan.',
            'has_surat_panggilan' => true,
            'is_active' => true,
        ]);

        $this->createApprovedViolation($siswa, $pencatat, $pelanggaran, 'Pelanggaran pertama');

        $this->assertDatabaseHas('surat_panggilan', [
            'siswa_id' => $siswa->id,
            'aturan_threshold_id' => $berat->id,
            'poin_pemicu' => 100,
            'total_poin' => 100,
            'status' => 'draft',
        ]);

        $suratPertama = SuratPanggilan::firstOrFail();
        $suratPertama->update(['status' => 'selesai']);

        $this->createApprovedViolation($siswa, $pencatat, $pelanggaran, 'Pelanggaran kedua');

        $this->assertSame(200, $siswa->refresh()->total_poin_pelanggaran);
        $this->assertDatabaseCount('surat_panggilan', 2);
        $this->assertDatabaseHas('surat_panggilan', [
            'siswa_id' => $siswa->id,
            'aturan_threshold_id' => $berat->id,
            'poin_pemicu' => 200,
            'total_poin' => 200,
            'status' => 'draft',
        ]);
        $this->assertSame('selesai', $suratPertama->fresh()->status);
    }

    public function test_reporter_cannot_access_or_delete_another_reporters_record(): void
    {
        [$siswa, $owner] = $this->createStudentAndRecorder();
        $reporterRole = Role::create(['nama_role' => 'Guru Pelapor']);
        $otherReporter = User::create([
            'username' => 'reporter.lain',
            'nama_lengkap' => 'Reporter Lain',
            'role_id' => $reporterRole->id,
            'password' => 'rahasia123',
        ]);
        $kategori = KategoriPoin::create([
            'jenis' => 'pelanggaran',
            'nama_kategori' => 'Terlambat',
            'bobot_poin' => 5,
            'tingkat' => 'ringan',
        ]);
        $catatan = CatatanPoin::create([
            'siswa_id' => $siswa->id,
            'kategori_poin_id' => $kategori->id,
            'pencatat_id' => $owner->id,
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Catatan milik guru lain',
            'status_validasi' => 'menunggu_validasi',
        ]);

        $this->actingAs($otherReporter, 'sanctum')
            ->getJson('/api/catatan-poin/'.$catatan->id)
            ->assertNotFound();

        $this->actingAs($otherReporter, 'sanctum')
            ->deleteJson('/api/catatan-poin/'.$catatan->id)
            ->assertNotFound();
    }

    public function test_student_history_api_excludes_unapproved_records(): void
    {
        [$siswa, $pencatat] = $this->createStudentAndRecorder();
        $kategori = KategoriPoin::create([
            'jenis' => 'pelanggaran',
            'nama_kategori' => 'Terlambat',
            'bobot_poin' => 5,
            'tingkat' => 'ringan',
        ]);
        $student = $siswa->user;
        CatatanPoin::create([
            'siswa_id' => $siswa->id,
            'kategori_poin_id' => $kategori->id,
            'pencatat_id' => $pencatat->id,
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Belum disetujui',
            'status_validasi' => 'menunggu_validasi',
        ]);

        $this->actingAs($student, 'sanctum')
            ->getJson('/api/siswa/riwayat')
            ->assertOk()
            ->assertJsonPath('total', 0);
    }

    public function test_letter_workflow_requires_valid_transition_and_records_history(): void
    {
        [$siswa, $guru] = $this->createStudentAndRecorder();
        $kesiswaanRole = Role::create(['nama_role' => 'Kesiswaan']);
        $kesiswaan = User::create([
            'username' => 'kesiswaan', 'nama_lengkap' => 'Kesiswaan', 'role_id' => $kesiswaanRole->id,
            'password' => 'rahasia123',
        ]);
        $threshold = AturanThreshold::create([
            'poin_batas' => 25, 'level' => 'sedang', 'judul_notifikasi' => 'Panggilan',
            'deskripsi' => 'Perlu tindak lanjut', 'has_surat_panggilan' => true, 'is_active' => true,
        ]);
        $surat = SuratPanggilan::create([
            'siswa_id' => $siswa->id, 'aturan_threshold_id' => $threshold->id,
            'tanggal_surat' => now()->toDateString(), 'alasan_pemanggilan' => 'Pelanggaran',
            'total_poin' => 25, 'tindakan_direkomendasikan' => 'Panggilan orang tua', 'status' => 'draft',
            'dibuat_oleh' => $guru->id,
        ]);

        $service = app(SuratPanggilanWorkflowService::class);
        $service->transition($surat, 'diajukan', $guru);
        $surat = $service->transition($surat->fresh(), 'disetujui', $kesiswaan);

        $this->assertSame('disetujui', $surat->status);
        $this->assertNotNull($surat->nomor_surat);
        $surat = $service->transition($surat, 'dicetak', $kesiswaan);
        $surat = $service->transition($surat, 'dikirim', $kesiswaan);
        $surat = $service->transition($surat, 'selesai', $kesiswaan);
        $this->assertSame('selesai', $surat->status);
        $this->assertNotNull($surat->dicetak_pada);
        $this->assertNotNull($surat->dikirim_pada);
        $this->assertNotNull($surat->selesai_pada);
        $this->assertDatabaseCount('surat_panggilan_histories', 5);
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->transition($surat, 'dikirim', $kesiswaan);
    }

    private function createApprovedViolation(
        Siswa $siswa,
        User $pencatat,
        KategoriPoin $kategori,
        string $keterangan
    ): CatatanPoin {
        return CatatanPoin::create([
            'siswa_id' => $siswa->id,
            'kategori_poin_id' => $kategori->id,
            'pencatat_id' => $pencatat->id,
            'tanggal' => now()->toDateString(),
            'keterangan' => $keterangan,
            'status_validasi' => 'disetujui',
        ]);
    }

    private function createStudentAndRecorder(): array
    {
        $guruRole = Role::create(['nama_role' => 'Guru BK']);
        $siswaRole = Role::create(['nama_role' => 'Siswa']);
        $pencatat = User::create([
            'username' => 'pencatat',
            'nama_lengkap' => 'Guru Pencatat',
            'role_id' => $guruRole->id,
            'password' => 'rahasia123',
        ]);
        $siswaUser = User::create([
            'username' => 'siswa',
            'nama_lengkap' => 'Siswa Uji',
            'role_id' => $siswaRole->id,
            'password' => 'rahasia123',
        ]);
        $kelas = Kelas::create([
            'nama_kelas' => 'X-A',
            'wali_kelas_id' => $pencatat->id,
        ]);
        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'kelas_id' => $kelas->id,
            'nisn' => '1234567890',
            'jenis_kelamin' => 'L',
        ]);

        return [$siswa, $pencatat];
    }
}
