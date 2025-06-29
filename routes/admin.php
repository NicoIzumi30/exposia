<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\HelpController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for the admin dashboard and related functionalities
|
*/

// Admin route group with middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    
    // Website Management
    Route::resource('websites', WebsiteController::class);
    Route::get('websites/{website}/preview', [WebsiteController::class, 'preview'])->name('websites.preview');
    Route::post('websites/{website}/publish', [WebsiteController::class, 'publish'])->name('websites.publish');
    Route::post('websites/{website}/unpublish', [WebsiteController::class, 'unpublish'])->name('websites.unpublish');
    
    // Content Monitoring
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/', [ContentController::class, 'index'])->name('index');
        Route::get('products', [ContentController::class, 'products'])->name('products');
        Route::get('galleries', [ContentController::class, 'galleries'])->name('galleries');
        Route::get('testimonials', [ContentController::class, 'testimonials'])->name('testimonials');
        Route::get('about', [ContentController::class, 'about'])->name('about');
        Route::delete('delete/{type}/{id}', [ContentController::class, 'delete'])->name('delete');
    });
    
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::get('/{report}/resolve', [ReportController::class, 'showResolveForm'])->name('showResolveForm');
        Route::post('/{report}/resolve', [ReportController::class, 'resolve'])->name('resolve');
        Route::post('/{report}/reject', [ReportController::class, 'reject'])->name('reject');
    });
    // Statistics & Analytics
    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('/', [StatisticController::class, 'index'])->name('index');
        Route::get('visitors', [StatisticController::class, 'visitors'])->name('visitors');
        Route::get('top-sites', [StatisticController::class, 'topSites'])->name('top-sites');
        Route::get('new-websites', [StatisticController::class, 'newWebsites'])->name('new-websites');
        Route::get('export/{type}', [StatisticController::class, 'export'])->name('export');
    });
    
    // Platform Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('platform', [SettingController::class, 'platform'])->name('platform');
        Route::post('platform', [SettingController::class, 'updatePlatform'])->name('platform.update');
        Route::get('terms', [SettingController::class, 'terms'])->name('terms');
        Route::post('terms', [SettingController::class, 'updateTerms'])->name('terms.update');
        Route::get('privacy', [SettingController::class, 'privacy'])->name('privacy');
        Route::post('privacy', [SettingController::class, 'updatePrivacy'])->name('privacy.update');
        Route::get('account', [SettingController::class, 'account'])->name('account');
        Route::post('account', [SettingController::class, 'updateAccount'])->name('account.update');
    });
    
    // Admin Management
    Route::resource('admins', AdminController::class);
    Route::post('admins/{admin}/activate', [AdminController::class, 'activate'])->name('admins.activate');
    Route::post('admins/{admin}/deactivate', [AdminController::class, 'deactivate'])->name('admins.deactivate');
    
    // Activity Logs
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('actions', [ActivityLogController::class, 'actions'])->name('actions');
        Route::get('audit-trail', [ActivityLogController::class, 'auditTrail'])->name('audit-trail');
        Route::get('export', [ActivityLogController::class, 'export'])->name('export');
    });
    
    // Help
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', [HelpController::class, 'index'])->name('index');
        Route::get('faq', [HelpController::class, 'faq'])->name('faq');
        Route::resource('messages', HelpController::class)->only(['index', 'show', 'destroy']);
        Route::post('messages/{message}/reply', [HelpController::class, 'reply'])->name('messages.reply');
    });
});