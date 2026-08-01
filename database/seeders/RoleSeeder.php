<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nama_role' => 'Kesiswaan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'Guru BK', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'Guru Pelapor', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'Wali Kelas', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'Siswa', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
