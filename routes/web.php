<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\WebAuthController;

Route::get('/', [WebAuthController::class, 'showLogin']);

Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login'])->name('login.store');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');

    Route::middleware('role:Kesiswaan')->prefix('kesiswaan')->name('kesiswaan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kesiswaan'])->name('dashboard');
        Route::get('/laporan', [DashboardController::class, 'reports'])->name('reports');
        Route::post('/laporan/{laporan}/approval', [DashboardController::class, 'approval'])->name('reports.approval');
        Route::get('/kelas/{kelas}', [DashboardController::class, 'classDetail'])->name('classes.show');
        Route::get('/surat-panggilan', [DashboardController::class, 'letters'])->name('letters');
        Route::get('/surat-panggilan/{surat}', [DashboardController::class, 'letter'])->name('letters.show');
        Route::post('/surat-panggilan/{surat}/transisi', [DashboardController::class, 'transitionLetter'])->name('letters.transition');
        Route::get('/surat-panggilan/{surat}/cetak', [DashboardController::class, 'printLetter'])->name('letters.print');
    });

    Route::middleware('role:Guru BK,Guru Pelapor')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teacher'])->name('dashboard');
        Route::get('/catatan-poin', [DashboardController::class, 'records'])->name('records');
        Route::get('/catatan-poin/{catatan}', [DashboardController::class, 'teacherRecord'])->name('records.show');
        Route::post('/catatan-poin', [DashboardController::class, 'storeRecord'])->name('records.store');
        Route::post('/catatan-poin/{catatan}/validasi', [DashboardController::class, 'validateRecord'])->name('records.validate');
        Route::get('/rekap-siswa', [DashboardController::class, 'studentRecap'])->name('students');
        Route::get('/laporan', [DashboardController::class, 'teacherReports'])->name('reports');
        Route::post('/laporan', [DashboardController::class, 'storeReport'])->name('reports.store');
        Route::get('/surat-panggilan', [DashboardController::class, 'teacherLetters'])->name('letters');
        Route::get('/surat-panggilan/{surat}', [DashboardController::class, 'letter'])->name('letters.show');
        Route::put('/surat-panggilan/{surat}', [DashboardController::class, 'updateLetter'])->name('letters.update');
        Route::post('/surat-panggilan/{surat}/transisi', [DashboardController::class, 'transitionLetter'])->name('letters.transition');
        Route::get('/surat-panggilan/{surat}/cetak', [DashboardController::class, 'printLetter'])->name('letters.print');
    });

    Route::middleware('role:Wali Kelas')->prefix('wali-kelas')->name('wali-kelas.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'homeroom'])->name('dashboard');
        Route::get('/siswa', [DashboardController::class, 'homeroomStudents'])->name('students');
        Route::get('/siswa/{siswa}', [DashboardController::class, 'homeroomStudent'])->name('students.show');
        Route::get('/notifikasi', [DashboardController::class, 'homeroomNotifications'])->name('notifications');
        Route::post('/notifikasi/{notifikasi}/dibaca', [DashboardController::class, 'readHomeroomNotification'])->name('notifications.read');
    });

    Route::middleware('role:Siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');
        Route::get('/riwayat', [DashboardController::class, 'studentHistory'])->name('history');
        Route::get('/riwayat/{catatan}', [DashboardController::class, 'studentRecord'])->name('history.show');
        Route::get('/notifikasi', [DashboardController::class, 'studentNotifications'])->name('notifications');
        Route::post('/notifikasi/{notifikasi}/dibaca', [DashboardController::class, 'readStudentNotification'])->name('notifications.read');
    });
});
