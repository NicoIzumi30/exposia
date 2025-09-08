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
            $business->updateProgressCompletion();
            // dd($business->hero_image_url);
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
        try {
            $business = auth()->user()->business;

            // Check if the business has a template
            if (!$business->businessTemplate) {
                return redirect()->route('user.templates.index')
                    ->with('error', 'Harap pilih template terlebih dahulu');
            }

            // Prepare data for view
            $viewData = $this->prepareViewData($business);
            
            // Add business slug for chat widget
            $viewData['businessSlug'] = $this->extractBusinessSlug($business->public_url);
            
            
            return view('user.templates.preview', $viewData);

        } catch (\Exception $e) {

            return redirect()->route('user.templates.index')
                ->with('error', 'Terjadi kesalahan saat memuat preview. Silakan coba lagi.');
        }
    }

    /**
     * Prepare all view data
     *
     * @param Business $business
     * @return array
     */
    private function prepareViewData($business)
    {
        return [
            'business' => $business,
            'sectionVariants' => $this->getSectionVariants($business),
            'colorPalette' => $this->getColorPalette($business),
            'navbarData' => $this->prepareNavbarData($business),
            'heroData' => $this->prepareHeroData($business),
            'aboutData' => $this->prepareAboutData($business),
            'productData' => $this->prepareProductData($business),
            'galleryData' => $this->prepareGalleryData($business),
            'testimonialData' => $this->prepareTestimonialData($business),
            'contactData' => $this->prepareContactData($business),
            'footerData' => $this->prepareFooterData($business),
        ];
    }

    /**
     * Get section variants configuration
     *
     * @param Business $business
     * @return array
     */
    private function getSectionVariants($business)
    {
        return $business->businessSections
            ->where('is_active', true)
            ->pluck('style_variant', 'section')
            ->toArray();
    }

    /**
     * Get color palette configuration
     *
     * @param Business $business
     * @return array
     */
    private function getColorPalette($business)
    {
        return $business->businessTemplate ? 
            $business->businessTemplate->getColorPalette() : 
            [
                'primary' => '#3B82F6',
                'secondary' => '#64748B',
                'accent' => '#F59E0B',
                'highlight' => '#8B5CF6'
            ];
    }

    /**
     * Prepare navbar data
     *
     * @param Business $business
     * @return array
     */
    private function prepareNavbarData($business)
    {
        return [
            'logo' => $this->getBusinessLogo($business),
            'business_name' => $business->business_name,
            'sections' => $this->getActiveSectionNames($business)
        ];
    }

    /**
     * Prepare hero section data
     *
     * @param Business $business
     * @return array
     */
    private function prepareHeroData($business)
    {
        return [
            'title' => $business->business_name ?? 'Welcome',
            'description' => $business->short_description ?? 'Discover our amazing products and services',
            'img-1' => $this->getImageUrl($business->hero_image_url),
            'img-2' => $this->getImageUrl($business->hero_image_secondary_url),
            'cta_text' => 'Hubungi Kami',
            'whatsapp_number' => $this->getWhatsAppNumber($business)
        ];
    }

    /**
     * Prepare about section data
     *
     * @param Business $business
     * @return array
     */
    private function prepareAboutData($business)
    {
        $businessStory = $this->formatBusinessStory($business->full_story);
        $highlights = $this->prepareHighlights($business);

        return [
            'description' => $businessStory ?: 'Learn more about our business and what makes us special.',
            'img-1' => $business->about_image_url,
            'img-2' => $business->about_image_secondary_url,
            'highlights' => $highlights,
            'stats' => $this->getBusinessStats($business)
        ];
    }

    /**
     * Prepare product section data
     *
     * @param Business $business
     * @return array
     */
    private function prepareProductData($business)
    {
        $products = $business->products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->product_name,
                'description' => $product->product_description,
                'short_description' => $this->truncateText($product->product_description, 100),
                'price' => $product->product_price,
                'formatted_price' => $this->formatPrice($product->product_price),
                'img' => $this->getImageUrl($product->product_image, 'product'),
                'is_pinned' => $product->is_pinned ?? false,
                'whatsapp_link' => $this->generateProductWhatsAppLink($product)
            ];
        })->toArray();

        return [
            'products' => $products,
            'featured_products' => array_filter($products, fn($p) => $p['is_pinned']),
            'total_count' => count($products),
            'has_products' => count($products) > 0
        ];
    }

    /**
     * Prepare gallery section data
     *
     * @param Business $business
     * @return array
     */
    private function prepareGalleryData($business)
    {
        $images = $business->galleries->map(function ($gallery) {
            return [
                'id' => $gallery->id,
                'url' => $this->getImageUrl($gallery->gallery_image, 'gallery'),
                'thumbnail' => $this->getImageUrl($gallery->gallery_image, 'gallery'),
                'alt' => 'Gallery image for ' . $gallery->business->business_name,
                'created_at' => $gallery->created_at->format('M Y')
            ];
        })->toArray();

        return [
            'images' => $images,
            'total_count' => count($images),
            'has_images' => count($images) > 0
        ];
    }

    /**
     * Prepare testimonial section data
     *
     * @param Business $business
     * @return array
     */
    private function prepareTestimonialData($business)
    {
        $testimonials = $business->testimonials->map(function ($testimonial) {
            return [
                'id' => $testimonial->id,
                'text' => $testimonial->testimonial_content,
                'name' => $testimonial->testimonial_name,
                'position' => $testimonial->testimonial_position ?? '',
                'img' => $this->getImageUrl($testimonial->testimonial_image ?? null, 'profile'),
                'rating' => 5,
                'date' => $testimonial->created_at->format('M Y')
            ];
        })->toArray();

        return [
            'testimonies' => $testimonials,
            'total_count' => count($testimonials),
            'has_testimonials' => count($testimonials) > 0,
            'average_rating' => 5.0
        ];
    }

    /**
     * Prepare contact section data
     *
     * @param Business $business
     * @return array
     */
    private function prepareContactData($business)
    {
        $contacts = $business->contacts->map(function ($contact) {
            $type = $contact->contact_type;
            $meta = BusinessContact::$availableTypes[$type] ?? BusinessContact::$availableTypes['custom'] ?? [];
            
            // Ensure $meta is an array
            if (!is_array($meta)) {
                $meta = BusinessContact::$availableTypes['custom'] ?? [];
            }
            
            return [
                'id' => $contact->id,
                'type' => $contact->contact_type,
                'name' => $meta['name'] ?? 'Custom',
                'title' => $contact->contact_title ?: ($meta['title'] ?? 'Contact'),
                'description' => $contact->contact_description ?: ($meta['description'] ?? ''),
                'icon' => $contact->contact_icon ?: ($meta['icon'] ?? 'fas fa-link'),
                'url' => $this->buildContactUrl($contact, $meta),
                'value' => $contact->contact_value,
                'order' => $contact->order ?? 0
            ];
        })->sortBy('order')->toArray();

        return [
            'contacts' => array_values($contacts),
            'primary_whatsapp' => $this->getWhatsAppNumber($business),
            'business_phone' => $business->user->phone ?? null,
            'business_email' => $business->user->email ?? null,
            'has_contacts' => count($contacts) > 0
        ];
    }

    /**
     * Prepare footer section data
     *
     * @param Business $business
     * @return array
     */
    private function prepareFooterData($business)
    {
        $branches = $business->branches->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->branch_name ?? 'Main Branch',
                'address' => $branch->branch_address ?? 'Address not available',
                'address_link' => $branch->branch_google_maps_link ?? '#',
                'opening_time' => $branch->branch_operational_hours ?? 'Hours not specified',
                'phone_number' => $this->formatPhoneNumber($branch->branch_phone),
                'whatsapp_link' => $branch->branch_phone ? $this->generateWhatsAppLink($branch->branch_phone) : null
            ];
        })->toArray();

        return [
            'business_name' => $business->business_name,
            'description' => $this->truncateText($business->full_story ?? $business->short_description, 200),
            'logo' => $this->getBusinessLogo($business),
            'branches' => $branches,
            'main_branch' => $branches[0] ?? null,
            'social_links' => $this->getSocialLinks($business),
            'copyright_year' => date('Y'),
            'powered_by' => 'Exposia'
        ];
    }

    /**
     * Prepare business highlights
     *
     * @param Business $business
     * @return array
     */
    private function prepareHighlights($business)
    {
        return $business->highlights->map(function ($highlight) {
            return [
                'id' => $highlight->id,
                'icon' => $highlight->icon ?? 'fas fa-star',
                'title' => $highlight->title ?? 'Feature',
                'description' => $highlight->description ?? 'Description not available'
            ];
        })->toArray();
    }

    /**
     * Get business statistics
     *
     * @param Business $business
     * @return array
     */
    private function getBusinessStats($business)
    {
        return [
            'products_count' => $business->products->count(),
            'branches_count' => $business->branches->count(),
            'testimonials_count' => $business->testimonials->count(),
            'years_experience' => $this->calculateBusinessAge($business),
            'happy_customers' => $business->testimonials->count() * 10
        ];
    }

    /**
     * Get business logo URL
     *
     * @param Business $business
     * @return string
     */
    private function getBusinessLogo($business)
    {
        if ($business->logo_url) {
            return Storage::url($business->logo_url);
        }
        
        return asset('img/default-logo.png');
    }

    /**
     * Get image URL with fallback
     *
     * @param string|null $imagePath
     * @param string $type
     * @return string
     */
    private function getImageUrl($imagePath, $type = 'general')
    {
        if ($imagePath) {
            if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                return $imagePath;
            }
            
            return Storage::url($imagePath);
        }

        $fallbacks = [
            'product' => 'img/no-product.jpg',
            'gallery' => 'img/no-image.jpg',
            'profile' => 'img/empty-profile-pic.jpg',
            'about' => 'img/about-placeholder.jpg',
            'general' => 'img/no-image.jpg'
        ];

        return asset($fallbacks[$type] ?? $fallbacks['general']);
    }

    /**
     * Format business story with proper styling
     *
     * @param string|null $story
     * @return string|null
     */
    private function formatBusinessStory($story)
    {
        if (!$story) {
            return null;
        }

        return preg_replace('/<p(?![^>]*class=)/i', '<p class="text-justify"', $story);
    }

    /**
     * Format price for display
     *
     * @param float|int $price
     * @return string
     */
    private function formatPrice($price)
    {
        if (!$price) {
            return 'Hubungi Kami';
        }

        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    /**
     * Format phone number
     *
     * @param string|null $phone
     * @return string|null
     */
    private function formatPhoneNumber($phone)
    {
        if (!$phone) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) >= 10) {
            return preg_replace('/(\\d{4})(\\d{4})(\\d+)/', '$1-$2-$3', $phone);
        }

        return $phone;
    }

    /**
     * Get WhatsApp number
     *
     * @param Business $business
     * @return string|null
     */
    private function getWhatsAppNumber($business)
    {
        $phone = $business->user->phone ?? null;
        
        if (!$phone) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Generate WhatsApp link
     *
     * @param string $phone
     * @param string|null $message
     * @return string
     */
    private function generateWhatsAppLink($phone, $message = null)
    {
        $formattedPhone = $this->formatPhoneForWhatsApp($phone);
        $encodedMessage = $message ? urlencode($message) : '';
        
        return "https://wa.me/{$formattedPhone}" . ($message ? "?text={$encodedMessage}" : '');
    }

    /**
     * Format phone for WhatsApp
     *
     * @param string $phone
     * @return string
     */
    private function formatPhoneForWhatsApp($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 1) === '0') {
            return '62' . substr($phone, 1);
        }
        
        return $phone;
    }

    /**
     * Generate product WhatsApp link
     *
     * @param object $product
     * @return string|null
     */
    private function generateProductWhatsAppLink($product)
    {
        $business = $product->business;
        $phone = $this->getWhatsAppNumber($business);
        
        if (!$phone) {
            return null;
        }

        $message = "Halo! Saya tertarik dengan produk:\\n\\n";
        $message .= "*{$product->product_name}*\\n";
        $message .= "Harga: " . $this->formatPrice($product->product_price) . "\\n\\n";
        $message .= "Mohon informasi lebih lanjut. Terima kasih!";

        return $this->generateWhatsAppLink($phone, $message);
    }

    /**
     * Build contact URL
     *
     * @param object $contact
     * @param array $meta
     * @return string
     */
    private function buildContactUrl($contact, $meta)
    {
        $value = $contact->contact_value;
        
        // Ensure $meta is an array
        if (!is_array($meta)) {
            $meta = [];
        }
        
        $prefix = $meta['prefix'] ?? '';

        if ($contact->contact_type === 'whatsapp') {
            $value = $this->formatPhoneForWhatsApp($value);
        }

        if ($prefix) {
            $value = ltrim($value, '/');
        }

        return $prefix . $value;
    }

    /**
     * Get social media links
     *
     * @param Business $business
     * @return array
     */
    private function getSocialLinks($business)
    {
        $socialTypes = ['instagram', 'facebook', 'tiktok', 'youtube', 'twitter'];
        $socialLinks = [];

        foreach ($business->contacts as $contact) {
            if (in_array($contact->contact_type, $socialTypes)) {
                $meta = BusinessContact::$availableTypes[$contact->contact_type] ?? [];
                
                // Ensure $meta is an array
                if (!is_array($meta)) {
                    $meta = [];
                }
                
                $socialLinks[$contact->contact_type] = $this->buildContactUrl($contact, $meta);
            }
        }

        return $socialLinks;
    }

    /**
     * Get active section names
     *
     * @param Business $business
     * @return array
     */
    private function getActiveSectionNames($business)
    {
        return $business->businessSections
            ->where('is_active', true)
            ->pluck('section')
            ->toArray();
    }

    /**
     * Calculate business age in years
     *
     * @param Business $business
     * @return int
     */
    private function calculateBusinessAge($business)
    {
        return max(1, now()->diffInYears($business->created_at));
    }

    /**
     * Truncate text to specified length
     *
     * @param string|null $text
     * @param int $length
     * @return string
     */
    private function truncateText($text, $length = 100)
    {
        if (!$text) {
            return '';
        }

        $text = strip_tags($text);
        
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . '...';
    }

    /**
     * Extract business slug from URL
     *
     * @param string $publicUrl
     * @return string|null
     */
    private function extractBusinessSlug($publicUrl)
    {
        if (!$publicUrl) {
            return null;
        }

        return basename(parse_url($publicUrl, PHP_URL_PATH));
    }
}
