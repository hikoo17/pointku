<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatatanPoinController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\RekapController;
use App\Http\Controllers\Api\StatistikController;
use App\Http\Controllers\Api\SuratController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Catatan Poin routes
    Route::middleware('role:Guru BK,Guru Pelapor')->group(function () {
        Route::get('/catatan-poin', [CatatanPoinController::class, 'index']);
        Route::get('/catatan-poin/{id}', [CatatanPoinController::class, 'show']);
        Route::post('/catatan-poin', [CatatanPoinController::class, 'store']);
        Route::put('/catatan-poin/{id}', [CatatanPoinController::class, 'update']);
        Route::delete('/catatan-poin/{id}', [CatatanPoinController::class, 'destroy']);
        Route::get('/siswa/cari', [CatatanPoinController::class, 'cariSiswa']);
        Route::get('/kategori-poin', [RekapController::class, 'kategoriPoin']);
    });

    // Wali Kelas routes
    Route::middleware('role:Wali Kelas')->group(function () {
        Route::get('/kelas/rekap', [RekapController::class, 'kelas']);
        Route::get('/siswa/{siswa_id}/riwayat', [RekapController::class, 'riwayat']);
    });

    // Siswa routes
    Route::middleware('role:Siswa')->group(function () {
        Route::get('/siswa/rekap', [RekapController::class, 'siswa']);
        Route::get('/siswa/riwayat', [RekapController::class, 'riwayat']);
    });

    // Kesiswaan routes
    Route::middleware('role:Kesiswaan')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index']);
        Route::post('/laporan', [LaporanController::class, 'store']);
        Route::get('/laporan/{id}', [LaporanController::class, 'show']);
        Route::put('/laporan/{id}', [LaporanController::class, 'update']);
        Route::delete('/laporan/{id}', [LaporanController::class, 'destroy']);
        Route::post('/laporan/{id}/approval', [LaporanController::class, 'approval']);

        Route::get('/statistik/overview', [StatistikController::class, 'overview']);
        Route::get('/statistik/kelas', [StatistikController::class, 'statistikKelas']);

        Route::get('/surat-panggilan', [SuratController::class, 'index']);
        Route::post('/surat-panggilan', [SuratController::class, 'store']);
        Route::get('/surat-panggilan/{id}', [SuratController::class, 'show']);
        Route::put('/surat-panggilan/{id}/status', [SuratController::class, 'updateStatus']);
        Route::delete('/surat-panggilan/{id}', [SuratController::class, 'destroy']);
        Route::get('/surat-panggilan/{id}/export', [SuratController::class, 'exportPdf']);
    });

    // Guru BK routes
    Route::middleware('role:Guru BK')->group(function () {
        Route::get('/siswa/riwayat', [RekapController::class, 'riwayat']);

        Route::get('/laporan', [LaporanController::class, 'index']);
        Route::post('/laporan', [LaporanController::class, 'store']);
        Route::get('/laporan/{id}', [LaporanController::class, 'show']);

        Route::put('/notifikasi/{id}/resolve', [NotifikasiController::class, 'resolve']);

        Route::get('/surat-panggilan', [SuratController::class, 'index']);
        Route::post('/surat-panggilan', [SuratController::class, 'store']);
        Route::get('/surat-panggilan/{id}', [SuratController::class, 'show']);
        Route::put('/surat-panggilan/{id}/status', [SuratController::class, 'updateStatus']);
        Route::get('/surat-panggilan/{id}/export', [SuratController::class, 'exportPdf']);
    });

    // Shared routes for all authenticated users
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::put('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead']);
    Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount']);
});
