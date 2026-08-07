<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AturanThresholdSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'poin_batas' => 25,
                'level' => 'ringan',
                'judul_notifikasi' => 'Peringatan Ringan',
                'deskripsi' => 'Siswa telah mencapai poin pelanggaran 25. Perlu pemantauan dari Guru BK dan Wali Kelas.',
                'template_surat' => null,
                'has_surat_panggilan' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'poin_batas' => 50,
                'level' => 'sedang',
                'judul_notifikasi' => 'Peringatan Sedang',
                'deskripsi' => 'Siswa telah mencapai poin pelanggaran 50. Sistem akan menghasilkan draf Surat Panggilan Orang Tua.',
                'template_surat' => 'surat_panggilan_ortu_level_1',
                'has_surat_panggilan' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'poin_batas' => 100,
                'level' => 'berat',
                'judul_notifikasi' => 'Peringatan Berat',
                'deskripsi' => 'Siswa telah mencapai poin pelanggaran 100. Status siswa ditandai untuk tindakan Kesiswaan.',
                'template_surat' => 'surat_panggilan_ortu_level_2',
                'has_surat_panggilan' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ] as $threshold) {
            DB::table('aturan_threshold')->updateOrInsert(
                ['poin_batas' => $threshold['poin_batas']],
                $threshold
            );
        }
    }
}
