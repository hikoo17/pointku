<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function overview(Request $request)
    {
        $periode = $request->input('periode', 'bulan_ini');

        $startDate = match ($periode) {
            'minggu_ini' => now()->startOfWeek(),
            'bulan_ini' => now()->startOfMonth(),
            'tahun_ini' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $endDate = match ($periode) {
            'minggu_ini' => now()->endOfWeek(),
            'bulan_ini' => now()->endOfMonth(),
            'tahun_ini' => now()->endOfYear(),
            default => now()->endOfMonth(),
        };

        $catatanQuery = CatatanPoin::whereBetween('tanggal', [$startDate, $endDate])
            ->where('status_validasi', 'disetujui');

        $totalPelanggaran = (clone $catatanQuery)
            ->join('kategori_poin', 'catatan_poin.kategori_poin_id', '=', 'kategori_poin.id')
            ->where('kategori_poin.jenis', 'pelanggaran')
            ->sum('kategori_poin.bobot_poin');
        $totalApresiasi = (clone $catatanQuery)
            ->join('kategori_poin', 'catatan_poin.kategori_poin_id', '=', 'kategori_poin.id')
            ->where('kategori_poin.jenis', 'apresiasi')
            ->sum('kategori_poin.bobot_poin');

        $totalTransaksi = (clone $catatanQuery)->count();

        $siswaButuhPenanganan = Siswa::where('total_poin_pelanggaran', '>=', 25)->count();

        $topPelanggaran = KategoriPoin::where('jenis', 'pelanggaran')
            ->leftJoin('catatan_poin', 'kategori_poin.id', '=', 'catatan_poin.kategori_poin_id')
            ->select('kategori_poin.id', 'kategori_poin.nama_kategori', DB::raw('COUNT(catatan_poin.id) as jumlah'))
            ->whereBetween('catatan_poin.tanggal', [$startDate, $endDate])
            ->groupBy('kategori_poin.id', 'kategori_poin.nama_kategori')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        return response()->json([
            'periode' => $periode,
            'total_poin_pelanggaran' => $totalPelanggaran,
            'total_poin_apresiasi' => $totalApresiasi,
            'total_transaksi' => $totalTransaksi,
            'siswa_butuh_penanganan' => $siswaButuhPenanganan,
            'top_kategori_pelanggaran' => $topPelanggaran,
        ], 200);
    }

    public function statistikKelas(Request $request)
    {
        $kelas = DB::table('kelas')
            ->leftJoin('siswa', 'kelas.id', '=', 'siswa.kelas_id')
            ->leftJoin('catatan_poin', function ($join) {
                $join->on('siswa.id', '=', 'catatan_poin.siswa_id')
                    ->whereBetween('catatan_poin.tanggal', [now()->startOfMonth(), now()->endOfMonth()])
                    ->where('catatan_poin.status_validasi', 'disetujui');
            })
            ->leftJoin('kategori_poin', 'catatan_poin.kategori_poin_id', '=', 'kategori_poin.id')
            ->select(
                'kelas.nama_kelas',
                DB::raw('COUNT(siswa.id) as total_siswa'),
                DB::raw('SUM(CASE WHEN kategori_poin.jenis = "pelanggaran" THEN kategori_poin.bobot_poin ELSE 0 END) as total_poin_pelanggaran'),
                DB::raw('SUM(CASE WHEN kategori_poin.jenis = "apresiasi" THEN kategori_poin.bobot_poin ELSE 0 END) as total_poin_apresiasi')
            )
            ->groupBy('kelas.id', 'kelas.nama_kelas')
            ->get();

        return response()->json($kelas, 200);
    }
}
