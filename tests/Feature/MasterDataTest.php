<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_kesiswaan_can_open_master_data_pages(): void
    {
        $user = $this->createUser('Kesiswaan');

        $this->actingAs($user)->get(route('kesiswaan.master.users'))->assertOk();
        $this->actingAs($user)->get(route('kesiswaan.master.classes'))->assertOk();
        $this->actingAs($user)->get(route('kesiswaan.master.students'))->assertOk();
        $this->actingAs($user)->get(route('kesiswaan.master.categories'))->assertOk();
        $this->actingAs($user)->get(route('kesiswaan.master.thresholds'))->assertOk();
    }

    public function test_non_kesiswaan_cannot_open_master_data(): void
    {
        $this->actingAs($this->createUser('Guru BK'))
            ->get(route('kesiswaan.master.users'))
            ->assertForbidden();
    }

    public function test_kesiswaan_can_create_student_account_and_profile_together(): void
    {
        $kesiswaan = $this->createUser('Kesiswaan');
        $homeroom = $this->createUser('Wali Kelas');
        Role::create(['nama_role' => 'Siswa']);
        $class = Kelas::create(['nama_kelas' => 'XI IPA 1', 'wali_kelas_id' => $homeroom->id]);

        $this->actingAs($kesiswaan)->post(route('kesiswaan.master.students.store'), [
            'nama_lengkap' => 'Siswa Baru',
            'username' => 'siswa.baru',
            'password' => 'password123',
            'nisn' => '0099887766',
            'kelas_id' => $class->id,
            'jenis_kelamin' => 'P',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['username' => 'siswa.baru', 'nama_lengkap' => 'Siswa Baru']);
        $this->assertDatabaseHas('siswa', ['nisn' => '0099887766', 'kelas_id' => $class->id, 'jenis_kelamin' => 'P']);
    }

    public function test_kesiswaan_can_import_students_from_csv(): void
    {
        $kesiswaan = $this->createUser('Kesiswaan');
        $homeroom = $this->createUser('Wali Kelas');
        Role::create(['nama_role' => 'Siswa']);
        Kelas::create(['nama_kelas' => 'XI IPA 1', 'wali_kelas_id' => $homeroom->id]);
        $csv = "nama_lengkap,username,password,nisn,nama_kelas,jenis_kelamin\nSiswa Impor,siswa.impor,password123,0011223344,XI IPA 1,L\n";

        $this->actingAs($kesiswaan)->post(route('kesiswaan.master.students.import'), [
            'file' => UploadedFile::fake()->createWithContent('siswa.csv', $csv),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['username' => 'siswa.impor', 'nama_lengkap' => 'Siswa Impor']);
        $this->assertDatabaseHas('siswa', ['nisn' => '0011223344', 'jenis_kelamin' => 'L']);
    }

    public function test_student_import_rejects_invalid_headers(): void
    {
        $this->actingAs($this->createUser('Kesiswaan'))->post(route('kesiswaan.master.students.import'), [
            'file' => UploadedFile::fake()->createWithContent('siswa.csv', "nama,nisn\nSiswa,123\n"),
        ])->assertRedirect()->assertSessionHasErrors('file');
    }

    public function test_student_without_history_is_deleted_permanently(): void
    {
        [$kesiswaan, $student] = $this->createManagedStudent();
        $userId = $student->user_id;

        $this->actingAs($kesiswaan)
            ->delete(route('kesiswaan.master.students.destroy', $student))
            ->assertRedirect()
            ->assertSessionHas('success', 'Akun dan profil siswa berhasil dihapus.');

        $this->assertDatabaseMissing('users', ['id' => $userId]);
        $this->assertDatabaseMissing('siswa', ['id' => $student->id]);
    }

    public function test_student_with_history_is_deactivated_and_history_is_preserved(): void
    {
        [$kesiswaan, $student] = $this->createManagedStudent();
        $category = KategoriPoin::create(['jenis' => 'pelanggaran', 'nama_kategori' => 'Terlambat', 'bobot_poin' => 10, 'tingkat' => 'ringan']);
        $record = CatatanPoin::create([
            'siswa_id' => $student->id,
            'kategori_poin_id' => $category->id,
            'pencatat_id' => $kesiswaan->id,
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Terlambat masuk sekolah',
            'status_validasi' => 'disetujui',
        ]);

        $this->actingAs($kesiswaan)
            ->delete(route('kesiswaan.master.students.destroy', $student))
            ->assertRedirect()
            ->assertSessionHas('success', 'Siswa dinonaktifkan. Seluruh histori tetap tersimpan.');

        $this->assertDatabaseHas('siswa', ['id' => $student->id, 'status' => 'nonaktif']);
        $this->assertDatabaseHas('users', ['id' => $student->user_id]);
        $this->assertDatabaseHas('catatan_poin', ['id' => $record->id, 'siswa_id' => $student->id]);
    }

    public function test_kesiswaan_can_reactivate_student(): void
    {
        [$kesiswaan, $student] = $this->createManagedStudent();
        $student->update(['status' => 'nonaktif', 'dinonaktifkan_pada' => now()]);

        $this->actingAs($kesiswaan)
            ->patch(route('kesiswaan.master.students.activate', $student))
            ->assertRedirect()
            ->assertSessionHas('success', 'Akun siswa berhasil diaktifkan kembali.');

        $student->refresh();
        $this->assertSame('aktif', $student->status);
        $this->assertNull($student->dinonaktifkan_pada);
    }

    public function test_inactive_student_cannot_login(): void
    {
        [, $student] = $this->createManagedStudent();
        $student->update(['status' => 'nonaktif', 'dinonaktifkan_pada' => now()]);

        $this->post(route('login.store'), ['username' => $student->user->username, 'password' => 'password123'])
            ->assertRedirect()
            ->assertSessionHasErrors(['username' => 'Akun siswa sudah dinonaktifkan. Hubungi pihak sekolah.']);

        $this->assertGuest();
    }

    public function test_guru_bk_sidebar_renders_from_role_navigation(): void
    {
        $this->actingAs($this->createUser('Guru BK'))
            ->get(route('guru.dashboard'))
            ->assertOk()
            ->assertSee('RUANG KERJA')
            ->assertSee('Catatan poin');
    }

    private function createUser(string $roleName): User
    {
        $role = Role::firstOrCreate(['nama_role' => $roleName]);

        return User::create([
            'username' => str($roleName)->slug('.').'.'.User::count(),
            'nama_lengkap' => $roleName,
            'role_id' => $role->id,
            'password' => 'password123',
        ]);
    }

    private function createManagedStudent(): array
    {
        $kesiswaan = $this->createUser('Kesiswaan');
        $homeroom = $this->createUser('Wali Kelas');
        $studentUser = $this->createUser('Siswa');
        $class = Kelas::create(['nama_kelas' => 'XI IPA 1', 'wali_kelas_id' => $homeroom->id]);
        $student = Siswa::create([
            'user_id' => $studentUser->id,
            'kelas_id' => $class->id,
            'nisn' => '009988'.str_pad((string) $studentUser->id, 4, '0', STR_PAD_LEFT),
            'jenis_kelamin' => 'L',
        ]);

        return [$kesiswaan, $student];
    }
}
