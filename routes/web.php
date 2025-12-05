<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StepsPlanController;
use App\Http\Controllers\StepsFinalController;
use App\Http\Controllers\StepsController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicationExportController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PublicationOutputController;
use App\Http\Controllers\TeamTargetController;
use App\Models\Publication;

/*
|---------------------
| Web Routes
|---------------------
*/

// Halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Tambahkan middleware('auth') agar hanya user yang sudah login bisa akses
Route::get('/dashboard', [PublicationController::class, 'index'])
    ->name('daftarpublikasi')
    ->middleware('auth');

// Laporan
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

// Halaman Target Kinerja
Route::get('/target-kinerja', [TeamTargetController::class, 'index'])
    ->middleware('auth')
    ->name('target.index');

// Simpan Target Kinerja
Route::post('/target-kinerja', [TeamTargetController::class, 'store'])
    ->middleware('auth')
    ->name('target.store');

Route::put('/target-kinerja/{id}', [TeamTargetController::class, 'update'])->middleware('auth')->name('target.update');
Route::delete('/target-kinerja/{id}', [TeamTargetController::class, 'destroy'])->middleware('auth')->name('target.destroy');

// ----- Publications -----
// Export
Route::get('/publications/exportTable', [PublicationExportController::class, 'exportTable'])->name('publications.exportTable');
Route::get('/publications/export-template/{slug_publication}', [PublicationExportController::class, 'exportTemplate'])->name('publications.export.template');

// Update publikasi
Route::put('/publications/{publication}', [PublicationController::class, 'update'])->name('publications.update');

// Search publications
Route::get('/publications/search', [PublicationController::class, 'search'])->name('publications.search');

// All function
Route::resource('publications', PublicationController::class);

// Hapus publication
Route::delete('/publications/{slug_publication}', [PublicationController::class, 'destroy'])->name('publications.destroy');

// ----- Steps / Tahapan -----
// Tampilkan tahapan untuk 1 publikasi
Route::get('/publications/{publication}/steps', [StepsPlanController::class, 'index'])->name('steps.index');

// Tambah tahapan
Route::post('/publications/{publication}/steps', [StepsPlanController::class, 'store'])->name('steps.store');

// Update tahapan (rencana & realisasi)
Route::put('/plans/{plan}', [StepsPlanController::class, 'update'])->name('plans.update');
Route::put('/plans/{plan}/edit-stage', [StepsPlanController::class, 'updateStage'])->name('plans.update_stage');
Route::put('/finals/{plan}', [StepsFinalController::class, 'update'])->name('finals.update');

// Hapus tahapan
Route::delete('/plans/{plan}', [StepsPlanController::class, 'destroy'])->name('plans.destroy');

// Export Tahapan
Route::get('/export/publication/{slug_publication}', [PublicationExportController::class, 'export'])->name('publication.export');

// ----- Auth -----
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest'); 

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ubah password
Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');

// ----- Admin -----
Route::get('/admin', [AdminController::class, 'index'])->name('adminpage');
Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

// // Upload files publikasi
// Route::post('/publications/{publication}/upload-files', [PublicationController::class, 'uploadFiles'])
//     ->name('publications.uploadFiles')
//     ->middleware('auth');

// // Delete file publikasi
// Route::delete('/publication-files/{file}', [PublicationController::class, 'deleteFile'])
//     ->name('publications.deleteFile')
//     ->middleware('auth');

// // Download single file
// Route::get('/publication-files/{file}/download', [PublicationController::class, 'downloadFile'])
//     ->name('publications.downloadFile')
//     ->middleware('auth');

// // Download all files as ZIP
// Route::get('/publications/{publication}/download-all', [PublicationController::class, 'downloadAllFiles'])
//     ->name('publications.downloadAllFiles')
//     ->middleware('auth');

// Route untuk melihat halaman kelola output
Route::get('/publications/{slug}/outputs', [PublicationOutputController::class, 'index'])
    ->name('outputs.index');

// Route untuk update output (upload file & tanggal rilis)
Route::put('/publication-plans/{id}', [PublicationOutputController::class, 'update'])
    ->name('outputs.update');

// Simpan Output Baru
Route::post('/publications/{slug}/outputs', [PublicationOutputController::class, 'store'])
    ->name('outputs.store');

// Hapus Output
Route::delete('/publication-plans/{id}', [PublicationOutputController::class, 'destroy'])
    ->name('outputs.destroy');

// Ganti Password Role Lain
Route::put('/admin/users/{id}/reset-password', [AdminController::class, 'resetPassword'])
    ->name('admin.resetPassword')
    ->middleware('auth');