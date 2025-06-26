<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;
require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/admin.php';
Route::get('/', function () {
    return view('welcome');
});
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