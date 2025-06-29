<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateUpdateRequest;
use App\Models\Product;
use App\Models\Business;
use App\Models\BusinessContact;
use App\Models\BusinessHighlight;
use App\Models\BusinessSection;
use App\Models\BusinessTemplate;
use App\Models\Gallery;
use App\Models\Template;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * Display the template customization page
     */

    public function index()
    {
        $business = auth()->user()->business;
        $templates = Template::active()->get();
        $businessTemplate = BusinessTemplate::where('business_id', $business->id)->first();

        // Get active sections with their variants
        $activeSections = BusinessSection::where('business_id', $business->id)
            ->get()
            ->keyBy('section');

        // Get available sections data
        $availableSections = BusinessSection::getAvailableSections();

        return view('user.templates.index', compact(
            'business',
            'templates',
            'businessTemplate',
            'activeSections',
            'availableSections'
        ));
    }

    /**
     * Update the template for a business
     */
    public function updateTemplate(Request $request)
    {
        $templateId = $request->input('template_id');
        $defaultStyle = $request->input('default_style', 'A');

        // Validate template
        $template = Template::active()->find($templateId);
        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Template tidak valid'
            ]);
        }

        $business = auth()->user()->business;

        // Begin transaction
        \DB::beginTransaction();

        try {
            // Update template_id
            BusinessTemplate::updateOrCreate(
                ['business_id' => $business->id],
                ['template_id' => $templateId]
            );

            // Update all sections to the default style
            foreach (BusinessSection::$availableSections as $sectionKey => $config) {
                BusinessSection::updateSectionStyle($business->id, $sectionKey, $defaultStyle);
            }

            // Update business completion
            $business->updateProgressCompletion();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Template dan style berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update the color palette for a business template
     */
    public function updateColors(Request $request)
    {
        $primaryColor = $request->input('primary');
        $secondaryColor = $request->input('secondary');
        $accentColor = $request->input('accent');
        $highlightColor = $request->input('highlight'); // Tambahkan ini

        // Validate color format
        $colorPattern = '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';
        if (
            !preg_match($colorPattern, $primaryColor) ||
            !preg_match($colorPattern, $secondaryColor) ||
            !preg_match($colorPattern, $accentColor) ||
            !preg_match($colorPattern, $highlightColor)
        ) { // Tambahkan validasi untuk highlight

            return response()->json([
                'success' => false,
                'message' => 'Format warna tidak valid. Gunakan format hex (#FFFFFF)'
            ]);
        }

        $business = auth()->user()->business;

        try {
            // Get or create business template
            $businessTemplate = BusinessTemplate::firstOrCreate(
                ['business_id' => $business->id],
                ['template_id' => Template::active()->first()->id ?? null]
            );

            // Update color palette
            $colorPalette = [
                'primary' => strtoupper($primaryColor),
                'secondary' => strtoupper($secondaryColor),
                'accent' => strtoupper($accentColor),
                'highlight' => strtoupper($highlightColor) // Tambahkan warna highlight
            ];

            $businessTemplate->color_palette = $colorPalette;
            $businessTemplate->save();

            return response()->json([
                'success' => true,
                'message' => 'Warna berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update the hero image for a business
     */
    public function updateHeroImage(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'hero_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            $business = auth()->user()->business;

            // Log request untuk debugging
            \Log::info('Hero Image Upload Request', [
                'has_file' => $request->hasFile('hero_image'),
                'content_type' => $request->header('Content-Type'),
                'request_size' => $request->header('Content-Length')
            ]);

            if (!$request->hasFile('hero_image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan dalam request'
                ], 400);
            }

            // Delete old hero image if exists
            if ($business->hero_image_url && Storage::disk('public')->exists($business->hero_image_url)) {
                Storage::disk('public')->delete($business->hero_image_url);
            }

            // Store new hero image (mengikuti pola yang sama dengan upload logo)
            $heroImagePath = $request->file('hero_image')->store('business-hero', 'public');

            // Update business
            $business->update([
                'hero_image_url' => $heroImagePath
            ]);

            // Update completion status
            $business->updateProgressCompletion();

            return response()->json([
                'success' => true,
                'message' => 'Gambar hero berhasil diperbarui',
                'image_url' => Storage::url($heroImagePath)
            ]);
        } catch (\Exception $e) {
            \Log::error('Hero Image Upload Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle a section on/off
     */
    public function toggleSection(Request $request)
    {
        $section = $request->input('section');

        // Validate section
        if (!array_key_exists($section, BusinessSection::$availableSections)) {
            return response()->json([
                'success' => false,
                'message' => 'Bagian tidak valid'
            ]);
        }

        $business = auth()->user()->business;

        try {
            // Toggle section
            $businessSection = BusinessSection::toggleSection($business->id, $section);

            $message = $businessSection->is_active
                ? "Bagian {$businessSection->getSectionName()} berhasil diaktifkan"
                : "Bagian {$businessSection->getSectionName()} berhasil dinonaktifkan";

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $businessSection->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    public function updateSecondaryHeroImage(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'hero_image_secondary' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            $business = auth()->user()->business;

            // Log request untuk debugging
            \Log::info('Secondary Hero Image Upload Request', [
                'has_file' => $request->hasFile('hero_image_secondary'),
                'content_type' => $request->header('Content-Type'),
                'request_size' => $request->header('Content-Length')
            ]);

            if (!$request->hasFile('hero_image_secondary')) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan dalam request'
                ], 400);
            }

            // Delete old secondary hero image if exists
            if ($business->hero_image_secondary_url && Storage::disk('public')->exists($business->hero_image_secondary_url)) {
                Storage::disk('public')->delete($business->hero_image_secondary_url);
            }

            // Store new secondary hero image
            $heroSecondaryImagePath = $request->file('hero_image_secondary')->store('business-hero-secondary', 'public');

            // Update business
            $business->update([
                'hero_image_secondary_url' => $heroSecondaryImagePath
            ]);

            // Update completion status
            $business->updateProgressCompletion();

            return response()->json([
                'success' => true,
                'message' => 'Gambar hero kedua berhasil diperbarui',
                'image_url' => Storage::url($heroSecondaryImagePath)
            ]);
        } catch (\Exception $e) {
            \Log::error('Secondary Hero Image Upload Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the secondary hero image
     */
    public function removeSecondaryHeroImage(Request $request)
    {
        try {
            $business = auth()->user()->business;

            if (!$business->hero_image_secondary_url) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada gambar hero kedua'
                ], 400);
            }

            // Delete secondary hero image if exists
            if (Storage::disk('public')->exists($business->hero_image_secondary_url)) {
                Storage::disk('public')->delete($business->hero_image_secondary_url);
            }

            // Update business
            $business->update([
                'hero_image_secondary_url' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gambar hero kedua berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Secondary Hero Image Removal Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Update a section style variant
     */
    public function updateSectionStyle(Request $request)
    {
        $section = $request->input('section');
        $styleVariant = $request->input('style_variant');

        // Validate section & style
        if (!array_key_exists($section, BusinessSection::$availableSections)) {
            return response()->json([
                'success' => false,
                'message' => 'Bagian tidak valid'
            ]);
        }

        if (!array_key_exists($styleVariant, BusinessSection::$availableSections[$section]['variants'])) {
            return response()->json([
                'success' => false,
                'message' => 'Style tidak valid'
            ]);
        }

        $business = auth()->user()->business;

        try {
            // Update section style
            $businessSection = BusinessSection::updateSectionStyle(
                $business->id,
                $section,
                $styleVariant
            );

            return response()->json([
                'success' => true,
                'message' => "Style untuk {$businessSection->getSectionName()} berhasil diperbarui",
                'display_name' => $businessSection->getFullDisplayName()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Preview the business website
     */

    public function preview()
    {
        $business = auth()->user()->business;
        // Add class to <p> tags if not present
        $businessFullStory = preg_replace('/<p(?![^>]*class=)/i', '<p class="text-justify"', $business->full_story);

        // Check if the business has a template
        if (!$business->businessTemplate) {
            return redirect()->route('user.templates.index')
                ->with('error', 'Harap pilih template terlebih dahulu');
        }

        // Get Selected Section
        $sections = $business->businessSections()->where('is_active', true)->get();
        $sectionVariants = $sections->pluck('style_variant', 'section')->toArray();

        // Get Selected Color Palette
        $colorPalette = $business->getColorPalette();

        // Navbar Data
        $navbarData = [
            'logo' => $business->getLogoUrl(),
        ];

        // Hero Data
        $heroData = [
            'title' => $business->business_name ?? 'No Title',
            'description' => $business->short_description ?? 'No Description',
            'img-1' => $business->hero_image_url ? asset($business->hero_image_url) : asset('img/no-image.jpg'),
            'img-2' => $business->hero_image_secondary_url ? Storage::url($business->hero_image_secondary_url) : asset('img/no-image.jpg'),
        ];

        // About Data
        $highlights = $business->highlights()->limit(6)->get();
        // $highlights = $business->highlights->keyBy('section');

        // $highlightsArray = $highlights->mapWithKeys(function ($item) {
        //     return [
        //         $item->section => [
        //             'icon' => $item->icon,
        //             'title' => $item->title,
        //             'description' => $item->description,
        //         ]
        //     ];
        // })->toArray();

        // $highlights = BusinessHighlight::where('business_id', $business->id)->get();

        $aboutData = [
            'description' => $businessFullStory ?? 'No Text',
            'img-1' => $business->about_image_url ? asset($business->about_image_url) : asset('img/no-image.jpg'),
            'img-2' => $business->about_image_secondary_url ? asset($business->about_image_secondary_url) : asset('img/no-image.jpg'),

            'highlights' => $highlights->map(function ($item) {
                return [
                    'icon' => $item->icon ?? 'fas fa-question',
                    'title' => $item->title ?? 'Title missing',
                    'description' => $item->description ?? 'Description missing',
                ];
            })->toArray(),
        ];

        // Product Data
        $productData = [
            'products' => $business->products->map(function ($product) {
                return [
                    'title' => $product->product_name,
                    'description' => $product->product_description,
                    'price' => $product->product_price,
                    'img' => $product->product_image ? Storage::url($product->product_image) : asset('img/no-image.jpg'),
                ];
            })->toArray(),
        ];

        // Gallery Data
        $galleryData = [
            'images' => $business->galleries->map(function ($gallery) {
                return $gallery->gallery_image ? Storage::url($gallery->gallery_image) : asset('img/no-image.jpg');
            })->toArray(),
        ];

        // Testimonial Data
        $testimonialData = [
            'testimonies' => $business->testimonials->map(function ($testimonial) {
                return [
                    'text' => $testimonial->testimonial_content,
                    'name' => $testimonial->testimonial_name,
                    'img' => $testimonial->testimonial_image ? Storage::url($testimonial->testimonial_image) : asset('img/empty-profile-pic.jpg'),
                ];
            })->toArray(),
        ];

        // Contact Data
        $contactData = [
            'contacts' => $business->contacts->map(function ($item) {
                $type = $item->contact_type;
                $meta = BusinessContact::$availableTypes[$type] ?? BusinessContact::$availableTypes['custom'];
                return [
                    'type' => $item['contact_type'],
                    'name' => $meta['name'],
                    'title' => $item['contact_title'],
                    'description' => $item['contact_description'],
                    'icon' => $meta['icon'],
                    'url' => $meta['prefix'] . ltrim($item->contact_value, '/'),
                ];
            })->toArray(),
        ];

        // Footer Data
        $footerData = [
            'description' => $businessFullStory,
            'branches' => $business->branches->map(function ($branch) {
                return [
                    'name' => $branch->branch_name,
                    'address' => $branch->branch_address,
                    'address_link' => $branch->branch_google_maps_link,
                    'opening_time' => $branch->branch_operational_hours,
                    'phone_number' => $branch->branch_phone,
                ];
            })->toArray(),
        ];

        return view('user.templates.preview', compact(
            'sectionVariants',
            'colorPalette',
            'navbarData',
            'heroData',
            'aboutData',
            'productData',
            'galleryData',
            'testimonialData',
            'contactData',
            'footerData'
        ));
    }
}
