<?php

use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AiContentController;
use App\Http\Controllers\User\BranchController;
use App\Http\Controllers\User\BusinessController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\GalleryController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\PublishController;
use App\Http\Controllers\User\TemplateController;
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
          Route::get('/support', [DashboardController::class, 'support'])->name('support');
          Route::get('/account', [DashboardController::class, 'account'])->name('account');
     });

     Route::prefix('about')->name('about.')->group(function () {
          Route::get('/', [App\Http\Controllers\User\AboutController::class, 'index'])->name('index');
          Route::post('/story', [App\Http\Controllers\User\AboutController::class, 'updateStory'])->name('story.update');
          Route::delete('/remove-image', [App\Http\Controllers\User\AboutController::class, 'removeAboutImage'])->name('image.remove');
          Route::delete('/about/remove-secondary-image', [App\Http\Controllers\User\AboutController::class, 'removeSecondaryAboutImage'])->name('user.about.remove-secondary-image');

          // Business Highlights Resource Routes
          Route::prefix('highlights')->name('highlights.')->group(function () {
               Route::post('/', [App\Http\Controllers\User\AboutController::class, 'storeHighlight'])->name('store');
               Route::get('/{highlight}', [App\Http\Controllers\User\AboutController::class, 'showHighlight'])->name('show');
               Route::put('/{highlight}', [App\Http\Controllers\User\AboutController::class, 'updateHighlight'])->name('update');
               Route::delete('/{highlight}', [App\Http\Controllers\User\AboutController::class, 'destroyHighlight'])->name('destroy');
          });
     });
     Route::controller(TemplateController::class)
          ->prefix('templates')->name('templates.')->group(function () {
               Route::get('/', 'index')->name('index');
               Route::post('/update-template', 'updateTemplate')->name('update-template');
               Route::post('/update-colors', 'updateColors')->name('update-colors');
               Route::post('/update-hero', 'updateHeroImage')->name('update-hero');
               Route::post('/toggle-section', 'toggleSection')->name('toggle-section');
               Route::get('/preview', 'preview')->name('preview');
               Route::post('/update-section-style', 'updateSectionStyle')->name('update-section-style');
               Route::post('/templates/update-secondary-hero',  'updateSecondaryHeroImage')->name('update-secondary-hero');
               Route::delete('/templates/remove-secondary-hero',  'removeSecondaryHeroImage')->name('remove-secondary-hero');
          });
     Route::controller(AiContentController::class)->prefix('ai-content')->name('ai-content.')->group(function () {
          Route::get('/', 'index')->name('index');
          Route::post('/generate-business-description', 'generateBusinessDescription')->name('generate-business-description');
          Route::post('/generate-product-description', 'generateProductDescription')->name('generate-product-description');
          Route::post('/generate-headline', 'generateHeadline')->name('generate-headline');
          Route::post('/save-business-description', 'saveBusinessDescription')->name('save-business-description');
          Route::post('/save-product-description', 'saveProductDescription')->name('save-product-description');
     });
     Route::controller(PublishController::class)->prefix('publish')->name('publish.')->group(function () {
          Route::get('/', 'index')->name('index');
          Route::post('/toggle-status', 'togglePublishStatus')->name('toggle-status');
          Route::post('/update-url', 'updateUrl')->name('update-url');
          Route::get('download-qr', 'downloadQrCode')->name('download-qr');
          Route::get('qr-code', 'displayQrCode')->name('display-qr');
     });
     // Account Management
     Route::controller(AccountController::class)->prefix('account')->name('account.')->group(function () {
          Route::get('/', 'index')->name('index');
          Route::put('/update-profile', 'updateProfile')->name('update-profile');
          Route::put('/update-password', 'updatePassword')->name('update-password');
          Route::post('/logout-all-devices', 'logoutAllDevices')->name('logout-all-devices');
     });
     Route::prefix('contacts')->name('contacts.')->group(function () {
          Route::get('/', [ContactController::class, 'index'])->name('index');
          Route::post('/', [ContactController::class, 'store'])->name('store');
          Route::get('/{contact}', [ContactController::class, 'show'])->name('show');
          Route::put('/{contact}', [ContactController::class, 'update'])->name('update');
          Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('destroy');
          Route::post('/order', [ContactController::class, 'updateOrder'])->name('order');
          Route::post('/{contact}/toggle', [ContactController::class, 'toggleActive'])->name('toggle');
      });
});