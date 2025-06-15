<?php

// routes/user.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
Route::middleware(['auth'])->prefix('dashboard')->name('user.')->group(function () {
    
    // Dashboard Beranda
    
    // Data Usaha
    Route::get('/business', [DashboardController::class, 'business'])->name('business');
    
    // Cabang
    Route::get('/branches', [DashboardController::class, 'branches'])->name('branches');
    
    // Produk
    Route::get('/products', [DashboardController::class, 'products'])->name('products');
    
    // Galeri
    Route::get('/gallery', [DashboardController::class, 'gallery'])->name('gallery');
    
    // Testimoni
    Route::get('/testimonials', [DashboardController::class, 'testimonials'])->name('testimonials');
    
    // Tentang Usaha
    Route::get('/about', [DashboardController::class, 'about'])->name('about');
    
    // Template & Tampilan
    Route::get('/templates', [DashboardController::class, 'templates'])->name('templates');
    
    // AI Konten Generator
    Route::get('/ai-content', [DashboardController::class, 'aiContent'])->name('ai-content');
    
    // Publikasi & Link Website
    Route::get('/publish', [DashboardController::class, 'publish'])->name('publish');
    
    // Bantuan & Support
    Route::get('/support', [DashboardController::class, 'support'])->name('support');
    
    // Akun Saya
    Route::get('/account', [DashboardController::class, 'account'])->name('account');
});