<?php

namespace Database\Seeders;

use App\Models\AturanThreshold;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(20260807);

        $roles = Role::pluck('id', 'nama_role');
        $password = 'password123';

        $kesiswaan = User::where('username', 'kesiswaan')->firstOrFail();
        $bkUsers = collect([
            ['username' => 'guru.bk', 'nama' => 'Budi Santoso'],
            ['username' => 'guru.bk.2', 'nama' => 'Siti Rahmawati'],
            ['username' => 'guru.bk.3', 'nama' => 'Agus Setiawan'],
        ])->map(fn (array $data) => User::updateOrCreate(
            ['username' => $data['username']],
            ['nama_lengkap' => $data['nama'], 'role_id' => $roles['Guru BK'], 'password' => $password]
        ));

        $reporters = collect([
            ['username' => 'guru.pelapor', 'nama' => 'Rina Wulandari'],
            ['username' => 'guru.pelapor.2', 'nama' => 'Dedi Kurniawan'],
            ['username' => 'guru.pelapor.3', 'nama' => 'Maya Lestari'],
            ['username' => 'guru.pelapor.4', 'nama' => 'Fajar Nugroho'],
        ])->map(fn (array $data) => User::updateOrCreate(
            ['username' => $data['username']],
            ['nama_lengkap' => $data['nama'], 'role_id' => $roles['Guru Pelapor'], 'password' => $password]
        ));

        $classNames = ['X-A', 'X-B', 'X-C', 'XI-A', 'XI-B', 'XI-C', 'XII-A', 'XII-B'];
        $classes = collect($classNames)->map(function (string $className, int $index) use ($roles, $password) {
            $suffix = strtolower(str_replace('-', '.', $className));
            $homeroom = User::updateOrCreate(
                ['username' => "wali.{$suffix}"],
                [
                    'nama_lengkap' => fake()->unique()->name(),
                    'role_id' => $roles['Wali Kelas'],
                    'password' => $password,
                ]
            );

            if ($index === 0) {
                $homeroom = User::where('username', 'wali.kelas')->firstOrFail();
            }

            return Kelas::updateOrCreate(
                ['nama_kelas' => $className],
                ['wali_kelas_id' => $homeroom->id]
            );
        });

        $students = collect();
        foreach ($classes as $classIndex => $class) {
            for ($number = 1; $number <= 20; $number++) {
                $serial = ($classIndex * 20) + $number;
                $username = $serial === 1 ? 'siswa' : sprintf('siswa%03d', $serial);
                $user = User::updateOrCreate(
                    ['username' => $username],
                    [
                        'nama_lengkap' => $serial === 1 ? 'Andi Pratama' : fake()->unique()->name(),
                        'role_id' => $roles['Siswa'],
                        'password' => $password,
                    ]
                );

                $students->push(Siswa::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'kelas_id' => $class->id,
                        'nisn' => sprintf('2026%06d', $serial),
                        'jenis_kelamin' => $serial % 2 === 0 ? 'P' : 'L',
                        'status' => 'aktif',
                        'dinonaktifkan_pada' => null,
                    ]
                ));
            }
        }

        $categories = KategoriPoin::all();
        $violations = $categories->where('jenis', 'pelanggaran')->values();
        $appreciations = $categories->where('jenis', 'apresiasi')->values();
        // A previous seed may have duplicated threshold rows; one alert is allowed per limit.
        $thresholds = AturanThreshold::where('is_active', true)
            ->orderBy('poin_batas')
            ->get()
            ->unique('poin_batas')
            ->values();

        DB::transaction(function () use ($students, $violations, $appreciations, $reporters, $bkUsers, $kesiswaan, $thresholds) {
            DB::table('surat_panggilan_histories')->delete();
            DB::table('surat_panggilan')->delete();
            DB::table('approval_laporan')->delete();
            DB::table('laporan_kesiswaan')->delete();
            DB::table('notifikasi')->delete();
            DB::table('catatan_poin')->delete();

            foreach ($students as $index => $student) {
                $approvedViolations = 0;
                $approvedAppreciations = 0;
                $recordCount = 2 + ($index % 8);

                for ($recordIndex = 0; $recordIndex < $recordCount; $recordIndex++) {
                    $isViolation = $recordIndex < ceil($recordCount * 0.7);
                    $categoryPool = $isViolation ? $violations : $appreciations;
                    $category = $categoryPool[($index + $recordIndex) % $categoryPool->count()];
                    $status = match (($index + $recordIndex) % 12) {
                        0 => 'menunggu_validasi',
                        1 => 'ditolak',
                        default => 'disetujui',
                    };
                    $date = Carbon::today()->subDays(($index * 3 + $recordIndex * 11) % 330);

                    CatatanPoin::create([
                        'siswa_id' => $student->id,
                        'kategori_poin_id' => $category->id,
                        'pencatat_id' => $reporters[($index + $recordIndex) % $reporters->count()]->id,
                        'guru_id' => $bkUsers[$index % $bkUsers->count()]->id,
                        'tanggal' => $date,
                        'keterangan' => $this->recordDescription($category->nama_kategori, $date),
                        'status_validasi' => $status,
                        'created_at' => $date->copy()->setTime(8 + ($recordIndex % 7), 15),
                        'updated_at' => $date->copy()->setTime(8 + ($recordIndex % 7), 15),
                    ]);

                    if ($status === 'disetujui') {
                        if ($isViolation) {
                            $approvedViolations += $category->bobot_poin;
                        } else {
                            $approvedAppreciations += $category->bobot_poin;
                        }
                    }
                }

                $student->update([
                    'total_poin_pelanggaran' => $approvedViolations,
                    'total_poin_apresiasi' => $approvedAppreciations,
                ]);

                $eligibleThresholds = $thresholds->where('poin_batas', '<=', $approvedViolations);
                foreach ($eligibleThresholds as $threshold) {
                    $createdAt = Carbon::today()->subDays(($index * 5 + $threshold->poin_batas) % 180);
                    DB::table('notifikasi')->insert([
                        'siswa_id' => $student->id,
                        'aturan_threshold_id' => $threshold->id,
                        'level' => $threshold->level,
                        'judul' => $threshold->judul_notifikasi,
                        'pesan' => "{$student->user->nama_lengkap} mencapai {$approvedViolations} poin pelanggaran.",
                        'notifikasiable_type' => Siswa::class,
                        'notifikasiable_id' => $student->id,
                        'dibaca_pada' => $index % 3 === 0 ? $createdAt->copy()->addDay() : null,
                        'is_resolved' => $index % 5 === 0,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }

                if ($approvedViolations < 25 || $index % 3 === 0) {
                    continue;
                }

                $submittedAt = Carbon::today()->subDays(($index * 7) % 120);
                $status = ['pending', 'disetujui', 'ditolak'][$index % 3];
                $reportId = DB::table('laporan_kesiswaan')->insertGetId([
                    'siswa_id' => $student->id,
                    'bk_id' => $bkUsers[$index % $bkUsers->count()]->id,
                    'kesiswaan_id' => $status === 'pending' ? null : $kesiswaan->id,
                    'jenis_tindakan' => $approvedViolations >= 100 ? 'Konferensi kasus dan pemanggilan orang tua' : 'Konseling dan pemantauan berkala',
                    'status' => $status,
                    'diajukan_pada' => $submittedAt,
                    'selesai_pada' => $status === 'pending' ? null : $submittedAt->copy()->addDays(2),
                    'catatan_kesiswaan' => $status === 'pending' ? null : ($status === 'disetujui' ? 'Tindak lanjut disetujui.' : 'Perlu kelengkapan data pendukung.'),
                    'created_at' => $submittedAt,
                    'updated_at' => $submittedAt,
                ]);

                if ($status !== 'pending') {
                    DB::table('approval_laporan')->insert([
                        'laporan_kesiswaan_id' => $reportId,
                        'approver_id' => $kesiswaan->id,
                        'status' => $status,
                        'catatan_approval' => $status === 'disetujui' ? 'Disetujui untuk ditindaklanjuti.' : 'Data perlu diperbaiki.',
                        'disetujui_pada' => $status === 'disetujui' ? $submittedAt->copy()->addDays(2) : null,
                        'created_at' => $submittedAt->copy()->addDays(2),
                        'updated_at' => $submittedAt->copy()->addDays(2),
                    ]);
                }

                $letterThreshold = $eligibleThresholds->where('has_surat_panggilan', true)->last();
                if (! $letterThreshold) {
                    continue;
                }

                $letterStatus = ['draft', 'diajukan', 'disetujui', 'dicetak', 'dikirim', 'selesai'][$index % 6];
                $letterDate = $submittedAt->copy()->addDay();
                $letterId = DB::table('surat_panggilan')->insertGetId([
                    'siswa_id' => $student->id,
                    'laporan_kesiswaan_id' => $reportId,
                    'aturan_threshold_id' => $letterThreshold->id,
                    'poin_pemicu' => $letterThreshold->poin_batas,
                    'nomor_surat' => in_array($letterStatus, ['draft', 'diajukan']) ? null : sprintf('SP/%03d/BK/VIII/2026', $index + 1),
                    'tanggal_surat' => $letterDate,
                    'alasan_pemanggilan' => "Akumulasi poin pelanggaran mencapai {$approvedViolations} poin.",
                    'daftar_kejadian' => 'Rangkuman kejadian pelanggaran yang telah divalidasi oleh Guru BK.',
                    'total_poin' => $approvedViolations,
                    'tindakan_direkomendasikan' => 'Pertemuan orang tua, siswa, Wali Kelas, dan Guru BK.',
                    'status' => $letterStatus,
                    'catatan' => 'Data simulasi untuk pengujian alur surat panggilan.',
                    'dibuat_oleh' => $bkUsers[$index % $bkUsers->count()]->id,
                    'diajukan_oleh' => $letterStatus === 'draft' ? null : $bkUsers[$index % $bkUsers->count()]->id,
                    'disetujui_oleh' => in_array($letterStatus, ['disetujui', 'dicetak', 'dikirim', 'selesai']) ? $kesiswaan->id : null,
                    'diajukan_pada' => $letterStatus === 'draft' ? null : $letterDate,
                    'disetujui_pada' => in_array($letterStatus, ['disetujui', 'dicetak', 'dikirim', 'selesai']) ? $letterDate->copy()->addDay() : null,
                    'dicetak_pada' => in_array($letterStatus, ['dicetak', 'dikirim', 'selesai']) ? $letterDate->copy()->addDays(2) : null,
                    'dikirim_pada' => in_array($letterStatus, ['dikirim', 'selesai']) ? $letterDate->copy()->addDays(3) : null,
                    'selesai_pada' => $letterStatus === 'selesai' ? $letterDate->copy()->addDays(7) : null,
                    'created_at' => $letterDate,
                    'updated_at' => $letterDate,
                ]);

                DB::table('surat_panggilan_histories')->insert([
                    'surat_panggilan_id' => $letterId,
                    'user_id' => $bkUsers[$index % $bkUsers->count()]->id,
                    'status_sebelumnya' => null,
                    'status_baru' => 'draft',
                    'catatan' => 'Surat dibuat otomatis dari data demo.',
                    'created_at' => $letterDate,
                    'updated_at' => $letterDate,
                ]);
            }
        });
    }

    private function recordDescription(string $category, Carbon $date): string
    {
        return "Catatan {$category} pada {$date->translatedFormat('d F Y')} untuk kebutuhan data demonstrasi.";
    }
}
