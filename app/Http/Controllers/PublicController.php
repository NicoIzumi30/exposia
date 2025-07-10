<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessContact;
use App\Models\BusinessVisitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicController extends Controller
{
    /**
     * Show the public website for a business.
     *
     * @param string $slug Business slug from URL
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            // Find and validate business
            $business = $this->findBusinessBySlug($slug);
            
            if (!$business) {
                return $this->handleBusinessNotFound($slug);
            }

            // Validate business status
            if (!$business->publish_status) {
                return $this->handleUnpublishedBusiness($business);
            }

            // Validate business template
            if (!$business->businessTemplate) {
                return $this->handleMissingTemplate($business);
            }

            // Record visitor
            $this->recordVisit($business);

            // Prepare data for view
            $viewData = $this->prepareViewData($business);
            
            // Add business slug for chat widget
            $viewData['businessSlug'] = $this->extractBusinessSlug($business->public_url);
            return view('public.show', $viewData);

        } catch (\Exception $e) {
            Log::error('Error loading business page', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->handleServerError();
        }
    }

    /**
     * Find business by slug with optimized query
     *
     * @param string $slug
     * @return Business|null
     */
    private function findBusinessBySlug($slug)
    {
        // Cache business data for better performance
        $cacheKey = "business_public_{$slug}";
        
        return Cache::remember($cacheKey, 3600, function () use ($slug) {
            return Business::where('public_url', 'like', "%/{$slug}")
                ->where('publish_status', true)
                ->with([
                    'user',
                    'businessTemplate.template',
                    'businessSections' => function ($query) {
                        $query->where('is_active', true);
                    },
                    'products' => function ($query) {
                        $query->defaultOrder()->limit(20);
                    },
                    'galleries' => function ($query) {
                        $query->latest()->limit(12);
                    },
                    'testimonials' => function ($query) {
                        $query->latest()->limit(10);
                    },
                    'highlights' => function ($query) {
                        $query->limit(6);
                    },
                    'contacts' => function ($query) {
                        $query->where('is_active', true)->orderBy('order');
                    },
                    'branches'
                ])
                ->first();
        });
    }

    /**
     * Prepare all view data
     *
     * @param Business $business
     * @return array
     */
    private function prepareViewData(Business $business)
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
    private function getSectionVariants(Business $business)
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
    private function getColorPalette(Business $business)
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
    private function prepareNavbarData(Business $business)
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
    private function prepareHeroData(Business $business)
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
    private function prepareAboutData(Business $business)
    {
        $businessStory = $this->formatBusinessStory($business->full_story);
        $highlights = $this->prepareHighlights($business);

        return [
            'description' => $businessStory ?: 'Learn more about our business and what makes us special.',
            'img-1' => $this->getImageUrl($business->about_image, 'about'),
            'img-2' => $this->getImageUrl($business->about_image_secondary, 'about'),
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
    private function prepareProductData(Business $business)
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
                'is_pinned' => $product->is_pinned,
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
    private function prepareGalleryData(Business $business)
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
    private function prepareTestimonialData(Business $business)
    {
        $testimonials = $business->testimonials->map(function ($testimonial) {
            return [
                'id' => $testimonial->id,
                'text' => $testimonial->testimonial_content,
                'name' => $testimonial->testimonial_name,
                'position' => $testimonial->testimonial_position ?? '',
                'img' => $this->getImageUrl($testimonial->testimonial_image ?? null, 'profile'),
                'rating' => 5, // Default rating
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
    private function prepareContactData(Business $business)
    {
        $contacts = $business->contacts->map(function ($contact) {
            $type = $contact->contact_type;
            $meta = BusinessContact::$availableTypes[$type] ?? BusinessContact::$availableTypes['custom'];
            
            return [
                'id' => $contact->id,
                'type' => $contact->contact_type,
                'name' => $meta['name'],
                'title' => $contact->contact_title ?: $meta['title'],
                'description' => $contact->contact_description ?: $meta['description'],
                'icon' => $contact->contact_icon ?: $meta['icon'],
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
    private function prepareFooterData(Business $business)
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
            'description' => $this->truncateText($business->full_description ?? $business->short_description, 200),
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
    private function prepareHighlights(Business $business)
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
    private function getBusinessStats(Business $business)
    {
        return [
            'products_count' => $business->products->count(),
            'branches_count' => $business->branches->count(),
            'testimonials_count' => $business->testimonials->count(),
            'years_experience' => $this->calculateBusinessAge($business),
            'happy_customers' => $business->testimonials->count() * 10 // Estimated
        ];
    }

    /**
     * Get business logo URL
     *
     * @param Business $business
     * @return string
     */
    private function getBusinessLogo(Business $business)
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
            // Handle both full URLs and storage paths
            if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                return $imagePath;
            }
            
            return Storage::url($imagePath);
        }

        // Return appropriate fallback based on type
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

        // Add text-justify class to paragraphs
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

        // Basic phone formatting
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) >= 10) {
            return preg_replace('/(\d{4})(\d{4})(\d+)/', '$1-$2-$3', $phone);
        }

        return $phone;
    }

    /**
     * Get WhatsApp number
     *
     * @param Business $business
     * @return string|null
     */
    private function getWhatsAppNumber(Business $business)
    {
        $phone = $business->user->phone ?? null;
        
        if (!$phone) {
            return null;
        }

        // Convert to international format
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

        $message = "Halo! Saya tertarik dengan produk:\n\n";
        $message .= "*{$product->product_name}*\n";
        $message .= "Harga: " . $this->formatPrice($product->product_price) . "\n\n";
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
        $prefix = $meta['prefix'] ?? '';

        // Special handling for WhatsApp
        if ($contact->contact_type === 'whatsapp') {
            $value = $this->formatPhoneForWhatsApp($value);
        }

        // Remove leading slash from value if prefix is provided
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
    private function getSocialLinks(Business $business)
    {
        $socialTypes = ['instagram', 'facebook', 'tiktok', 'youtube', 'twitter'];
        $socialLinks = [];

        foreach ($business->contacts as $contact) {
            if (in_array($contact->contact_type, $socialTypes)) {
                $socialLinks[$contact->contact_type] = $this->buildContactUrl($contact, 
                    BusinessContact::$availableTypes[$contact->contact_type] ?? []
                );
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
    private function getActiveSectionNames(Business $business)
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
    private function calculateBusinessAge(Business $business)
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

    /**
     * Record visitor information
     *
     * @param Business $business
     * @return void
     */
    private function recordVisit(Business $business)
    {
        try {
            // Check if visitor already recorded in this session
            $sessionKey = "visitor_recorded_{$business->id}";
            
            if (!session()->has($sessionKey)) {
                BusinessVisitor::create([
                    'business_id' => $business->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'referrer' => request()->header('referer')
                ]);

                // Mark as recorded for this session
                session()->put($sessionKey, true);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to record visitor', [
                'business_id' => $business->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle business not found
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    private function handleBusinessNotFound($slug)
    {
        Log::info('Business not found', ['slug' => $slug, 'ip' => request()->ip()]);

        return response()->view('errors.404', [
            'title' => 'Website Tidak Ditemukan',
            'message' => 'Website yang Anda cari tidak tersedia atau telah dipindahkan.',
            'suggestions' => [
                'Periksa kembali URL yang Anda masukkan',
                'Hubungi pemilik website untuk mendapatkan link yang benar',
                'Kembali ke halaman utama'
            ]
        ], 404);
    }

    /**
     * Handle unpublished business
     *
     * @param Business $business
     * @return \Illuminate\Http\Response
     */
    private function handleUnpublishedBusiness(Business $business)
    {
        Log::info('Unpublished business accessed', [
            'business_id' => $business->id,
            'slug' => basename(parse_url($business->public_url, PHP_URL_PATH)),
            'ip' => request()->ip()
        ]);

        return response()->view('errors.503', [
            'title' => 'Website Sedang Dalam Maintenance',
            'message' => 'Website ini sedang dalam tahap pengembangan dan belum dapat diakses publik.',
            'business_name' => $business->business_name,
            'contact_info' => $business->user->email ?? null
        ], 503);
    }

    /**
     * Handle missing template
     *
     * @param Business $business
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleMissingTemplate(Business $business)
    {
        Log::warning('Business accessed without template', [
            'business_id' => $business->id,
            'business_name' => $business->business_name
        ]);

        return response()->view('errors.503', [
            'title' => 'Website Belum Siap',
            'message' => 'Website ini sedang dalam proses setup dan belum dapat diakses.',
            'business_name' => $business->business_name
        ], 503);
    }

    /**
     * Handle server error
     *
     * @return \Illuminate\Http\Response
     */
    private function handleServerError()
    {
        return response()->view('errors.500', [
            'title' => 'Terjadi Kesalahan',
            'message' => 'Maaf, terjadi kesalahan saat memuat halaman. Silakan coba lagi nanti.'
        ], 500);
    }
}