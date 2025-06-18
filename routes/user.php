<?php

// routes/user.php

use App\Http\Controllers\User\BranchController;
use App\Http\Controllers\User\BusinessController;
use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\User\ProductController;
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
Route::controller(ProductController::class)->prefix('user/products')->name('user.products.')->group(function () {
    Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/search', 'search')->name('search'); // Move search before {product} route
        Route::get('/{product}', 'show')->name('show');
        Route::put('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
        
        // Additional product routes
        Route::patch('/{product}/toggle-pin', 'togglePin')->name('toggle-pin');
        Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
        Route::post('/{product}/generate-whatsapp-order', 'generateWhatsAppOrder')->name('generate-whatsapp-order');
});

Route::prefix('user/gallery')->name('user.gallery.')->group(function () {
     Route::get('/', [GalleryController::class, 'index'])
          ->name('index');
     Route::post('/', [GalleryController::class, 'store'])
          ->name('store');
     Route::delete('/{gallery}', [GalleryController::class, 'destroy'])
          ->name('destroy');
     // Removed: show, update, bulk-action, featured, search, toggle-featured, update-order, stats, quota
 });

Route::middleware(['auth'])->prefix('dashboard')->name('user.')->group(function () {
    
    // Cabang
    Route::get('/branches', [DashboardController::class, 'branches'])->name('branches');
    
    // Produk
    
    
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