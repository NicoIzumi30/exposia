<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessContact;
use App\Models\BusinessHighlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    /**
     * Show the public website for a business.
     */
    // public function show($slug)
    // {
    //     // Cari bisnis berdasarkan slug dari URL publik
    //     $business = Business::where('public_url', 'like', "%/{$slug}")
    //         ->first();

    //     // Jika bisnis tidak ditemukan sama sekali
    //     if (!$business) {
    //         // Gunakan custom message untuk halaman 404
    //         return response()->view('errors.404', [
    //             'title' => 'Website Tidak Ditemukan',
    //             'message' => 'Website yang Anda cari tidak tersedia.'
    //         ], 404);
    //     }

    //     // Jika bisnis ditemukan tapi belum dipublikasikan
    //     if (!$business->publish_status) {
    //         return response()->view('errors.404', [
    //             'title' => 'Website Belum Dipublikasikan',
    //             'message' => 'Website ini ada tetapi belum dipublikasikan oleh pemiliknya.'
    //         ], 404);
    //     }

    //     // Catat kunjungan
    //     $this->recordVisit($business);

    //     // Ambil data yang diperlukan untuk tampilan
    //     $template = $business->getActiveTemplate();
    //     $colorPalette = $business->getColorPalette();
    //     $activeSections = $business->getActiveSections();

    //     // Tampilkan website dengan template yang sesuai
    //     return view('public.show', compact('business', 'template', 'colorPalette', 'activeSections'));
    // }
    public function show($slug)
    {
        $business = Business::where('public_url', 'like', "%/{$slug}")
            ->first();
        if ($business->publish_status == 0) {
            return abort(404);
        }
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

        $highlightsArray = $highlights->mapWithKeys(function ($item) {
            return [
                $item->section => [
                    'icon' => $item->icon,
                    'title' => $item->title,
                    'description' => $item->description,
                ]
            ];
        })->toArray();

        // dd($highlightsArray);

        $highlights = BusinessHighlight::where('business_id', $business->id)->get();

        $aboutData = [
            'description' => $business->full_story ?? 'No Text',
            'img-1' => $business->about_image_url ? asset($business->about_image_url) : asset('img/no-image.jpg'),
            'img-2' => $business->about_image_secondary_url ? asset($business->about_image_secondary_url) : asset('img/no-image.jpg'),

            'pros-icon-1' => $highlights['0']['icon'] ?? 'fas fa-question',
            'pros-title-1' => $highlights['0']['title'] ?? 'Title missing',
            'pros-description-1' => $highlights['0']['description'] ?? 'Description missing',

            'pros-icon-2' => $highlights['1']['icon'] ?? 'fas fa-question',
            'pros-title-2' => $highlights['1']['title'] ?? 'Title missing',
            'pros-description-2' => $highlights['1']['description'] ?? 'Description missing',

            'pros-icon-3' => $highlights['2']['icon'] ?? 'fas fa-question',
            'pros-title-3' => $highlights['2']['title'] ?? 'Title missing',
            'pros-description-3' => $highlights['2']['description'] ?? 'Description missing',
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
                    'type' => $type,
                    'name' => $meta['name'],
                    'title' => $meta['title'],
                    'description' => $meta['description'],
                    'icon' => $meta['icon'],
                    'url' => $meta['prefix'] . ltrim($item->contact_value, '/'),
                ];
            })->toArray(),
        ];

        // Footer Data
        $footerData = [
            'description' => $business->full_story,
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

        return view('public.show', compact(
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
    /**
     * Record visitor information
     */
    private function recordVisit(Business $business)
    {
        try {
            // Jika model BusinessVisitor dan metode recordVisit tersedia
            if (class_exists('App\Models\BusinessVisitor')) {
                $visitor = new \App\Models\BusinessVisitor([
                    'business_id' => $business->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'referrer' => request()->headers->get('referer')
                ]);

                $visitor->save();
            }
        } catch (\Exception $e) {
            // Log error jika terjadi masalah saat mencatat kunjungan
            \Log::error('Error recording visit: ' . $e->getMessage());
        }
    }
}
