<?php

use App\Http\Controllers\User\BranchController;
use App\Http\Controllers\User\BusinessController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
| All routes for authenticated users managing their business dashboard
|
*/

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Business Management
    Route::controller(BusinessController::class)->prefix('business')->name('business.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/update', 'update')->name('update');
        Route::post('/generate-url', 'generateUrl')->name('generate-url');
        Route::post('/check-url', 'checkUrl')->name('check-url');
        Route::post('/update-url', 'updateUrl')->name('update-url');
        Route::post('/generate-qr', 'generateQrCode')->name('generate-qr');
    });
    
    // Branch Management
    Route::controller(BranchController::class)->prefix('branches')->name('branches.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{branch}', 'show')->name('show');
        Route::put('/{branch}', 'update')->name('update');
        Route::delete('/{branch}', 'destroy')->name('destroy');
        
        // Utility routes
        Route::post('/validate-maps-url', 'validateMapsUrl')->name('validate-maps-url');
        Route::post('/generate-whatsapp-link', 'generateWhatsAppLink')->name('generate-whatsapp-link');
    });
    
    // Product Management
    Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/search', 'search')->name('search'); // Specific routes before parameter routes
        Route::get('/{product}', 'show')->name('show');
        Route::put('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
        
        // Product utility routes
        Route::patch('/{product}/toggle-pin', 'togglePin')->name('toggle-pin');
        Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
        Route::post('/{product}/generate-whatsapp-order', 'generateWhatsAppOrder')->name('generate-whatsapp-order');
    });
    
    // Gallery Management
    Route::controller(GalleryController::class)->prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::delete('/{gallery}', 'destroy')->name('destroy');
    });
    
    // Testimonial Management
    Route::controller(TestimonialController::class)->prefix('testimonials')->name('testimonials.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{testimonial}', 'show')->name('show');
        Route::put('/{testimonial}', 'update')->name('update');
        Route::delete('/{testimonial}', 'destroy')->name('destroy');
    });
    
    // Dashboard Pages
    Route::prefix('dashboard')->group(function () {
        Route::get('/branches', [DashboardController::class, 'branches'])->name('branches');
        Route::get('/about', [DashboardController::class, 'about'])->name('about');
        Route::get('/templates', [DashboardController::class, 'templates'])->name('templates');
        Route::get('/ai-content', [DashboardController::class, 'aiContent'])->name('ai-content');
        Route::get('/publish', [DashboardController::class, 'publish'])->name('publish');
        Route::get('/support', [DashboardController::class, 'support'])->name('support');
        Route::get('/account', [DashboardController::class, 'account'])->name('account');
    });
    
});