<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'password123';

        $users = collect([
            ['role' => 'Kesiswaan', 'username' => 'kesiswaan', 'nama' => 'Admin Kesiswaan'],
            ['role' => 'Guru BK', 'username' => 'guru.bk', 'nama' => 'Budi Santoso'],
            ['role' => 'Guru Pelapor', 'username' => 'guru.pelapor', 'nama' => 'Rina Wulandari'],
            ['role' => 'Wali Kelas', 'username' => 'wali.kelas', 'nama' => 'Dewi Anggraini'],
            ['role' => 'Siswa', 'username' => 'siswa', 'nama' => 'Andi Pratama'],
        ])->mapWithKeys(function (array $account) use ($password) {
            $role = Role::where('nama_role', $account['role'])->firstOrFail();
            $user = User::updateOrCreate(
                ['username' => $account['username']],
                ['nama_lengkap' => $account['nama'], 'role_id' => $role->id, 'password' => $password]
            );

            return [$account['role'] => $user];
        });

        $kelas = Kelas::updateOrCreate(
            ['nama_kelas' => 'X-A'],
            ['wali_kelas_id' => $users['Wali Kelas']->id]
        );

        Siswa::updateOrCreate(
            ['user_id' => $users['Siswa']->id],
            [
                'kelas_id' => $kelas->id,
                'nisn' => '0012345678',
                'jenis_kelamin' => 'L',
            ]
        );
    }
}
