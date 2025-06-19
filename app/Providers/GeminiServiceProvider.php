<?php

namespace App\Providers;

use App\Services\GeminiService;
use Illuminate\Support\ServiceProvider;

class GeminiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GeminiService::class, function ($app) {
            return new GeminiService();
        });
        
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../../config/gemini.php', 'gemini');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../../config/gemini.php' => config_path('gemini.php'),
        ], 'config');
    }
}