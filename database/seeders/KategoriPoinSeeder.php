<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriPoinSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            // Pelanggaran
            ['pelanggaran', 'Terlambat', 5, 'ringan'],
            ['pelanggaran', 'Tidak membawa buku', 5, 'ringan'],
            ['pelanggaran', 'Seragam tidak rapi', 10, 'ringan'],
            ['pelanggaran', 'Bolos tidak ada alasan', 15, 'sedang'],
            ['pelanggaran', 'Merokok', 30, 'sedang'],
            ['pelanggaran', 'Ditanggungjawabkan oleh Kesiswaan (Narkoba)', 100, 'berat'],
            ['pelanggaran', 'Penganiayaan teman', 100, 'berat'],

            // Apresiasi
            ['apresiasi', 'Juara lomba', 15, 'ringan'],
            ['apresiasi', 'Puliza pujian', 10, 'ringan'],
            ['apresiasi', 'Dudah mengawasi acara', 15, 'sedang'],
            ['apresiasi', 'Juara kompetisi nasional', 50, 'berat'],
        ];

        foreach ($kategori as $item) {
            DB::table('kategori_poin')->insert([
                'jenis' => $item[0],
                'nama_kategori' => $item[1],
                'bobot_poin' => $item[2],
                'tingkat' => $item[3],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
