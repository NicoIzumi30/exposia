<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Testimonial;
use App\Http\Requests\TestimonialRequest;

class TestimonialController extends Controller
{
    /**
     * Display testimonials management page.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;
        
        if (!$business) {
            return redirect()->route('user.business.index')
                ->with('warning', 'Silakan lengkapi data usaha terlebih dahulu sebelum menambah testimoni.');
        }
        
        $testimonials = $business->testimonials()
            ->latest()
            ->paginate(15);
        
        $testimonialStats = [
            'total' => $business->testimonials()->count(),
            'this_month' => $business->testimonials()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('user.testimonials.index', compact('user', 'business', 'testimonials', 'testimonialStats'));
    }

    /**
     * Store a new testimonial.
     */
    public function store(TestimonialRequest $request)
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
            
            $testimonialData = $request->only([
                'testimonial_name',
                'testimonial_content',
                'testimonial_position'
            ]);

            $testimonial = $business->testimonials()->create($testimonialData);

            // Log activity
            log_activity('Testimoni baru ditambahkan: ' . $testimonial->testimonial_name, $testimonial);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Testimoni berhasil ditambahkan!',
                    'testimonial' => $testimonial->load('business')
                ]);
            }

            return redirect()->back()->with('success', 'Testimoni berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Testimonial creation error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan testimoni. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan testimoni. Silakan coba lagi.');
        }
    }

    /**
     * Show the specified testimonial for editing.
     */
    public function show(Testimonial $testimonial)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($testimonial->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'testimonial' => $testimonial
        ]);
    }

    /**
     * Update the specified testimonial.
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($testimonial->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $testimonialData = $request->only([
                'testimonial_name',
                'testimonial_content',
                'testimonial_position'
            ]);

            $testimonial->update($testimonialData);

            // Log activity
            log_activity('Testimoni diperbarui: ' . $testimonial->testimonial_name, $testimonial);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Testimoni berhasil diperbarui!',
                    'testimonial' => $testimonial->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Testimoni berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Testimonial update error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui testimoni. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui testimoni. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(Testimonial $testimonial)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($testimonial->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $testimonialName = $testimonial->testimonial_name;
            $testimonial->delete();

            // Log activity
            log_activity('Testimoni dihapus: ' . $testimonialName);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Testimoni berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Testimonial deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus testimoni. Silakan coba lagi.'
            ], 500);
        }
    }
}