<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\WebAuthController;
use Illuminate\Support\Facades\Route;

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

        Route::get('/master/pengguna', [DashboardController::class, 'masterUsers'])->name('master.users');
        Route::post('/master/pengguna', [DashboardController::class, 'storeMasterUser'])->name('master.users.store');
        Route::put('/master/pengguna/{user}', [DashboardController::class, 'updateMasterUser'])->name('master.users.update');
        Route::delete('/master/pengguna/{user}', [DashboardController::class, 'destroyMasterUser'])->name('master.users.destroy');
        Route::get('/master/kelas', [DashboardController::class, 'masterClasses'])->name('master.classes');
        Route::post('/master/kelas', [DashboardController::class, 'storeMasterClass'])->name('master.classes.store');
        Route::put('/master/kelas/{kelas}', [DashboardController::class, 'updateMasterClass'])->name('master.classes.update');
        Route::delete('/master/kelas/{kelas}', [DashboardController::class, 'destroyMasterClass'])->name('master.classes.destroy');
        Route::get('/master/siswa', [DashboardController::class, 'masterStudents'])->name('master.students');
        Route::post('/master/siswa', [DashboardController::class, 'storeMasterStudent'])->name('master.students.store');
        Route::put('/master/siswa/{siswa}', [DashboardController::class, 'updateMasterStudent'])->name('master.students.update');
        Route::delete('/master/siswa/{siswa}', [DashboardController::class, 'destroyMasterStudent'])->name('master.students.destroy');
        Route::get('/master/kategori-poin', [DashboardController::class, 'masterCategories'])->name('master.categories');
        Route::post('/master/kategori-poin', [DashboardController::class, 'storeMasterCategory'])->name('master.categories.store');
        Route::put('/master/kategori-poin/{kategori}', [DashboardController::class, 'updateMasterCategory'])->name('master.categories.update');
        Route::delete('/master/kategori-poin/{kategori}', [DashboardController::class, 'destroyMasterCategory'])->name('master.categories.destroy');
        Route::get('/master/threshold', [DashboardController::class, 'masterThresholds'])->name('master.thresholds');
        Route::post('/master/threshold', [DashboardController::class, 'storeMasterThreshold'])->name('master.thresholds.store');
        Route::put('/master/threshold/{threshold}', [DashboardController::class, 'updateMasterThreshold'])->name('master.thresholds.update');
        Route::delete('/master/threshold/{threshold}', [DashboardController::class, 'destroyMasterThreshold'])->name('master.thresholds.destroy');
    });

    Route::middleware('role:Guru BK,Guru Pelapor')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/catatan-poin', [DashboardController::class, 'records'])->name('records');
        Route::get('/catatan-poin/{catatan}', [DashboardController::class, 'teacherRecord'])->name('records.show');
        Route::post('/catatan-poin', [DashboardController::class, 'storeRecord'])->name('records.store');
    });

    Route::middleware('role:Guru BK')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teacher'])->name('dashboard');
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
