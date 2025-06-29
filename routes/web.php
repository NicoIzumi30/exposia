<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
require __DIR__ . '/auth.php';
require __DIR__ . '/user.php';
require __DIR__ . '/admin.php';
Route::get('/', function () {
    return view('welcome');
});


Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');
Route::post('/report', [ReportController::class, 'store'])->name('report.store');
Route::get('/report/confirmation/{report}', [ReportController::class, 'confirmation'])->name('report.confirmation');
Route::get('/report/check', [ReportController::class, 'check'])->name('report.check');
Route::post('/report/check', [ReportController::class, 'checkStatus'])->name('report.checkStatus');
Route::get('/{slug}', [PublicController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+') // Pastikan format URL sesuai (huruf kecil, angka, dan tanda hubung)
    ->name('public.show');
// Tambahkan fallback route untuk URL yang tidak valid
Route::fallback(function () {
    return response()->view('errors.404', [
        'title' => 'Halaman Tidak Ditemukan',
        'message' => 'Halaman yang Anda cari tidak ada.'
    ], 404);
});