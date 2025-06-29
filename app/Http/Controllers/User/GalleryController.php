<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Gallery;
use App\Http\Requests\GalleryRequest;

class GalleryController extends Controller
{
    /**
     * Maximum images per business
     */
    const MAX_GALLERY_IMAGES = 10;

    /**
     * Display gallery management page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business profile not found.',
                    'redirect' => route('user.business.index')
                ], 404);
            }

            return redirect()->route('user.business.index')
                ->with('warning', 'Silakan lengkapi data usaha terlebih dahulu sebelum mengelola galeri.');
        }

        // Get galleries with pagination
        $galleries = $business->galleries()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Simple gallery stats
        $galleryStats = [
            'total' => $business->galleries()->count(),
            'remaining' => max(0, self::MAX_GALLERY_IMAGES - $business->galleries()->count()),
            'can_upload' => $business->galleries()->count() < self::MAX_GALLERY_IMAGES,
        ];

        // If AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'galleries' => $galleries->map(function ($gallery) {
                    return $gallery->getSimpleData();
                }),
                'stats' => $galleryStats,
                'pagination' => [
                    'current_page' => $galleries->currentPage(),
                    'total_pages' => $galleries->lastPage(),
                    'total_items' => $galleries->total(),
                    'per_page' => $galleries->perPage(),
                    'has_more' => $galleries->hasMorePages()
                ]
            ]);
        }

        return view('user.gallery.index', compact('user', 'business', 'galleries', 'galleryStats'));
    }

    /**
     * Store new gallery images.
     */
    public function store(GalleryRequest $request)
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found.'
            ], 404);
        }

        // Check gallery quota
        $currentCount = $business->galleries()->count();
        $images = $request->file('gallery_images', []);
        $newImagesCount = count($images);

        if (($currentCount + $newImagesCount) > self::MAX_GALLERY_IMAGES) {
            $remaining = self::MAX_GALLERY_IMAGES - $currentCount;
            return response()->json([
                'success' => false,
                'message' => "Anda hanya dapat menambahkan {$remaining} gambar lagi. Total maksimal: " . self::MAX_GALLERY_IMAGES . " gambar."
            ], 422);
        }

        // Validate files
        foreach ($images as $index => $image) {
            if (!$image || !$image->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => "File #{$index} tidak valid atau rusak."
                ], 422);
            }

            // Check if it's actually an image
            $mimeType = $image->getMimeType();
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

            if (!in_array($mimeType, $allowedTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => "File '{$image->getClientOriginalName()}' bukan gambar yang valid. Format yang diizinkan: JPEG, PNG, WebP."
                ], 422);
            }

            // Check file size (5MB max)
            if ($image->getSize() > 5 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => "File '{$image->getClientOriginalName()}' terlalu besar. Maksimal 5MB per file."
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $uploadedImages = [];

            // Process each uploaded image
            foreach ($images as $index => $image) {
                // Generate unique filename
                $filename = generate_gallery_filename(
                    $image->getClientOriginalName(),
                    $business->id
                );

                // Store image
                $imagePath = $image->storeAs('gallery-images', $filename, 'public');

                // Optimize image if needed
                if (function_exists('optimize_gallery_image')) {
                    optimize_gallery_image($imagePath);
                }

                // Create gallery record (minimal data)
                $galleryImage = Gallery::create([
                    'business_id' => $business->id,
                    'gallery_image' => $imagePath,
                ]);

                $uploadedImages[] = $galleryImage;

                Log::info('Gallery image created:', [
                    'id' => $galleryImage->id,
                    'path' => $imagePath
                ]);
            }

            // Update business completion
            if (method_exists($business, 'updateCompletionWithMedia')) {
                $business->updateCompletionWithMedia();
            }

            // Log activity
            log_activity('Uploaded ' . count($uploadedImages) . ' gallery images', $business);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengupload ' . count($uploadedImages) . ' gambar!',
                'images' => collect($uploadedImages)->map(function ($image) {
                    return $image->getSimpleData();
                }),
                'stats' => [
                    'total' => $business->galleries()->count(),
                    'remaining' => max(0, self::MAX_GALLERY_IMAGES - $business->galleries()->count()),
                    'can_upload' => $business->galleries()->count() < self::MAX_GALLERY_IMAGES,
                ]
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();

            // Cleanup uploaded files on validation error
            foreach ($uploadedImages as $image) {
                if (method_exists($image, 'deleteImageFile')) {
                    $image->deleteImageFile();
                }
                $image->delete();
            }

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gallery upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Cleanup uploaded files on error
            foreach ($uploadedImages as $image) {
                if (method_exists($image, 'deleteImageFile')) {
                    $image->deleteImageFile();
                }
                $image->delete();
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload gambar: ' . $e->getMessage(),
                'error_details' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified gallery image.
     */
    public function destroy(Gallery $gallery)
    {
        $user = Auth::user();

        // Check ownership
        if ($gallery->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery image not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $business = $gallery->business;

            // Delete image and file (file deletion handled in model event)
            $gallery->delete();

            // Update business completion
            if (method_exists($business, 'updateCompletionWithMedia')) {
                $business->updateCompletionWithMedia();
            }

            // Log activity (already handled in model event)

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gambar galeri berhasil dihapus!',
                'stats' => [
                    'total' => $business->galleries()->count(),
                    'remaining' => max(0, self::MAX_GALLERY_IMAGES - $business->galleries()->count()),
                    'can_upload' => $business->galleries()->count() < self::MAX_GALLERY_IMAGES,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gallery deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar galeri. Silakan coba lagi.',
                'error_details' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }
}
