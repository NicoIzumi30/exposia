<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\BusinessHighlight;
use App\Http\Requests\AboutBusinessRequest;
use App\Http\Requests\BusinessHighlightRequest;

class AboutController extends Controller
{
    /**
     * Display about business management page.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return redirect()->route('user.business.index')
                ->with('warning', 'Silakan lengkapi data usaha terlebih dahulu sebelum mengatur halaman tentang bisnis.');
        }

        $highlights = $business->highlights()
            ->ordered()
            ->get();

        $highlightStats = [
            'total' => $business->highlights()->count(),
            'complete' => $business->highlights()->get()->filter(function ($highlight) {
                return $highlight->isComplete();
            })->count(),
        ];

        $availableIcons = BusinessHighlight::getAvailableIcons();

        return view('user.about.index', compact('user', 'business', 'highlights', 'highlightStats', 'availableIcons'));
    }

    /**
     * Update business story and about image.
     */
    public function updateStory(AboutBusinessRequest $request)
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $updateData = [];

            // Handle full story
            if ($request->has('full_story')) {
                $updateData['full_story'] = $request->input('full_story');
            }

            // Handle primary about image upload
            if ($request->hasFile('about_image') && $request->file('about_image')->isValid()) {
                // Delete old image if exists
                if ($business->about_image && Storage::disk('public')->exists($business->about_image)) {
                    Storage::disk('public')->delete($business->about_image);
                }

                // Store new image with optimized name
                $file = $request->file('about_image');
                $filename = 'about-' . $business->id . '-' . time() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('about-images', $filename, 'public');
                $updateData['about_image'] = $imagePath;
            }

            // Handle secondary about image upload
            if ($request->hasFile('about_image_secondary') && $request->file('about_image_secondary')->isValid()) {
                // Delete old secondary image if exists
                if ($business->about_image_secondary && Storage::disk('public')->exists($business->about_image_secondary)) {
                    Storage::disk('public')->delete($business->about_image_secondary);
                }

                // Store new secondary image with optimized name
                $file = $request->file('about_image_secondary');
                $filename = 'about-secondary-' . $business->id . '-' . time() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('about-images', $filename, 'public');
                $updateData['about_image_secondary'] = $imagePath;
            }

            if (!empty($updateData)) {
                $business->update($updateData);

                // Update progress completion
                $business->updateProgressCompletion();

                // Log activity with details
                $changes = [];
                if (isset($updateData['full_story'])) {
                    $changes[] = 'cerita bisnis';
                }
                if (isset($updateData['about_image'])) {
                    $changes[] = 'gambar utama tentang bisnis';
                }
                if (isset($updateData['about_image_secondary'])) {
                    $changes[] = 'gambar kedua tentang bisnis';
                }

                log_activity('Halaman tentang bisnis diperbarui: ' . implode(', ', $changes), $business);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Halaman tentang bisnis berhasil diperbarui!',
                    'business' => $business->fresh(),
                    'story_stats' => [
                        'word_count' => $request->getStoryWordCount(),
                        'char_count' => $request->getStoryCharCount(),
                        'has_meaningful_content' => $request->hasMeaningfulStory()
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Halaman tentang bisnis berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('About business update error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui halaman tentang bisnis. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui halaman tentang bisnis. Silakan coba lagi.');
        }
    }

    public function removeSecondaryAboutImage()
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found.'
            ], 404);
        }

        if (!$business->about_image_secondary) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada gambar kedua untuk dihapus.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $imagePath = $business->about_image_secondary;

            // Delete image file if exists
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $business->update(['about_image_secondary' => null]);

            // Update progress completion
            $business->updateProgressCompletion();

            // Log activity
            log_activity('Gambar kedua tentang bisnis dihapus', $business);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gambar kedua berhasil dihapus!',
                'business' => $business->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Secondary about image removal error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar kedua. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Store a new business highlight.
     */
    public function storeHighlight(BusinessHighlightRequest $request)
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found.'
            ], 404);
        }

        // Check if business already has maximum highlights
        $maxHighlights = 6;
        if ($business->highlights()->count() >= $maxHighlights) {
            return response()->json([
                'success' => false,
                'message' => "Maksimal $maxHighlights highlight per bisnis."
            ], 422);
        }

        try {
            DB::beginTransaction();

            $highlightData = $request->validated();
            $highlight = $business->highlights()->create($highlightData);

            // Update business progress completion
            $business->updateProgressCompletion();

            // Log activity
            log_activity('Business highlight ditambahkan: ' . $highlight->title, $highlight);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Highlight berhasil ditambahkan!',
                    'highlight' => $highlight->load('business'),
                    'highlight_stats' => $business->fresh()->highlight_stats
                ]);
            }

            return redirect()->back()->with('success', 'Highlight berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Business highlight creation error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan highlight. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan highlight. Silakan coba lagi.');
        }
    }

    /**
     * Show the specified business highlight for editing.
     */
    public function showHighlight(BusinessHighlight $highlight)
    {
        $user = Auth::user();

        // Check ownership
        if ($highlight->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Highlight not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'highlight' => [
                'id' => $highlight->id,
                'icon' => $highlight->icon,
                'title' => $highlight->title,
                'description' => $highlight->description,
                'created_at' => $highlight->created_at->format('d M Y H:i'),
                'is_complete' => $highlight->isComplete()
            ]
        ]);
    }

    /**
     * Update the specified business highlight.
     */
    public function updateHighlight(BusinessHighlightRequest $request, BusinessHighlight $highlight)
    {
        $user = Auth::user();

        // Check ownership
        if ($highlight->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Highlight not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $highlightData = $request->validated();
            $oldTitle = $highlight->title;

            $highlight->update($highlightData);

            // Update business progress completion
            $user->business->updateProgressCompletion();

            // Log activity
            log_activity("Business highlight diperbarui: '$oldTitle' -> '{$highlight->title}'", $highlight);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Highlight berhasil diperbarui!',
                    'highlight' => $highlight->fresh(),
                    'highlight_stats' => $user->business->fresh()->highlight_stats
                ]);
            }

            return redirect()->back()->with('success', 'Highlight berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Business highlight update error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui highlight. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui highlight. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified business highlight.
     */
    public function destroyHighlight(BusinessHighlight $highlight)
    {
        $user = Auth::user();

        // Check ownership
        if ($highlight->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Highlight not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $highlightTitle = $highlight->title;
            $highlight->delete();

            // Update business progress completion
            $user->business->updateProgressCompletion();

            // Log activity
            log_activity('Business highlight dihapus: ' . $highlightTitle);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Highlight berhasil dihapus!',
                'highlight_stats' => $user->business->fresh()->highlight_stats
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Business highlight deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus highlight. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Remove about image.
     */
    public function removeAboutImage()
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found.'
            ], 404);
        }

        if (!$business->about_image) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada gambar untuk dihapus.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $imagePath = $business->about_image;

            // Delete image file if exists
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $business->update(['about_image' => null]);

            // Update progress completion
            $business->updateProgressCompletion();

            // Log activity
            log_activity('Gambar tentang bisnis dihapus', $business);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus!',
                'business' => $business->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('About image removal error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Get about section completion status.
     */
    public function getCompletionStatus()
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found.'
            ], 404);
        }

        $aboutSection = $business->getAboutSectionData();

        return response()->json([
            'success' => true,
            'completion' => [
                'has_story' => !empty($business->full_story),
                'has_image' => $business->has_about_image,
                'has_secondary_image' => $business->has_about_image_secondary,
                'highlights_count' => $business->highlights_count,
                'has_sufficient_highlights' => $business->hasSufficientHighlights(),
                'overall_percentage' => $business->getAboutCompletionPercentage(),
                'is_complete' => $business->hasCompleteAboutSection(),
                'is_public_ready' => $business->isAboutSectionPublicReady()
            ],
            'stats' => $business->highlight_stats,
            'about_section' => $aboutSection
        ]);
    }

    /**
     * Preview about section as it would appear on public website.
     */
    public function previewAboutSection()
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Business profile not found.'
            ], 404);
        }

        $aboutData = $business->getAboutSectionData();

        return response()->json([
            'success' => true,
            'preview' => $aboutData,
            'is_ready' => $business->isAboutSectionPublicReady()
        ]);
    }
}