<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Show the public website for a business.
     */
    public function show($slug)
    {
        // Cari bisnis berdasarkan slug dari URL publik
        $business = Business::where('public_url', 'like', "%/{$slug}")
            ->first();
        
        // Jika bisnis tidak ditemukan sama sekali
        if (!$business) {
            // Gunakan custom message untuk halaman 404
            return response()->view('errors.404', [
                'title' => 'Website Tidak Ditemukan',
                'message' => 'Website yang Anda cari tidak tersedia.'
            ], 404);
        }
        
        // Jika bisnis ditemukan tapi belum dipublikasikan
        if (!$business->publish_status) {
            return response()->view('errors.404', [
                'title' => 'Website Belum Dipublikasikan',
                'message' => 'Website ini ada tetapi belum dipublikasikan oleh pemiliknya.'
            ], 404);
        }
        
        // Catat kunjungan
        $this->recordVisit($business);
        
        // Ambil data yang diperlukan untuk tampilan
        $template = $business->getActiveTemplate();
        $colorPalette = $business->getColorPalette();
        $activeSections = $business->getActiveSections();
        
        // Tampilkan website dengan template yang sesuai
        return view('public.show', compact('business', 'template', 'colorPalette', 'activeSections'));
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