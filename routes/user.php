<?php

// routes/user.php

use App\Http\Controllers\User\BranchController;
use App\Http\Controllers\User\BusinessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::get('user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
Route::controller(BusinessController::class)->prefix('user/business')->name('user.business.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::put('/update', 'update')->name('update');
    Route::post('/generate-url', 'generateUrl')->name('generate-url');
    Route::post('/check-url', 'checkUrl')->name('check-url');
    Route::post('/update-url', 'updateUrl')->name('update-url');
    Route::post('/generate-qr', 'generateQrCode')->name('generate-qr');
});
Route::controller(BranchController::class)->prefix('user/branches')->name('user.branches.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::get('/{branch}', 'show')->name('show');
    Route::put('/{branch}', 'update')->name('update');
    Route::delete('/{branch}', 'destroy')->name('destroy');
    
    // Additional utility routes
    Route::post('/validate-maps-url', 'validateMapsUrl')->name('validate-maps-url');
    Route::post('/generate-whatsapp-link', 'generateWhatsAppLink')->name('generate-whatsapp-link');
});
Route::middleware(['auth'])->prefix('dashboard')->name('user.')->group(function () {
    
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