<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
