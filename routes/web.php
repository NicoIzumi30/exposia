<?php

use Illuminate\Support\Facades\Route;
require __DIR__.'/auth.php';
require __DIR__.'/user.php';
Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth'])->get('/test-gemini', function () {
    $geminiService = app(App\Services\GeminiService::class);
    try {
        $businessInfo = [
            'business_name' => 'Kita Suka',
            'business_type' => 'Bakso pedas',
            'location' => 'Yogyakarta',
            'main_products' => 'Bakso pedas level 1-10, Mie ayam pedas',
            'strengths' => 'Cabai organik segar, Kuah kaldu alami, Tanpa MSG',
            'target_market' => 'Pecinta makanan pedas, Mahasiswa, Wisatawan'
        ];
        
        // Test metode untuk format JSON
        $jsonResult = $geminiService->generateBusinessDescriptionJson($businessInfo);
        
        return response()->json([
            'success' => true,
            'json_result' => $jsonResult,
            'extracted_short' => isset($jsonResult['short_description']) ? $jsonResult['short_description'] : 'Not available',
            'extracted_full' => isset($jsonResult['full_description']) ? substr($jsonResult['full_description'], 0, 100) . '...' : 'Not available'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});