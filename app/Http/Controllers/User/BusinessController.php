<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    /**
     * Display the business data management page.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;
        
        // If no business exists, create empty one
        if (!$business) {
            $business = $user->business()->create([
                'business_name' => '',
                'main_address' => '',
                'main_operational_hours' => '',
                'google_maps_link' => '',
                'logo_url' => '',
                'short_description' => '',
                'full_description' => '',
                'progress_completion' => 0,
                'publish_status' => false // 
            ]);
        }
        
        return view('user.business.index', compact('user', 'business'));
    }

    /**
     * Update business information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;
        
        if (!$business) {
            return redirect()->back()->with('error', 'Business profile not found.');
        }

        // Validation rules
        $rules = [
            'business_name' => 'required|string|max:255',
            'main_address' => 'required|string|max:500',
            'main_operational_hours' => 'required|string|max:255',
            'google_maps_link' => 'nullable|url|max:500',
            'short_description' => 'required|string|max:160',
            'full_description' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $request->validate($rules);

        try {
            $data = $request->only([
                'business_name', 
                'main_address', 
                'main_operational_hours',
                'google_maps_link',
                'short_description',
                'full_description'
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($business->logo_url && Storage::disk('public')->exists($business->logo_url)) {
                    Storage::disk('public')->delete($business->logo_url);
                }

                // Store new logo
                $logoPath = $request->file('logo')->store('business-logos', 'public');
                $data['logo_url'] = $logoPath;
            }

            // Update business data
            $business->update($data);

            // Recalculate progress completion
            $business->update([
                'progress_completion' => business_completion($business)
            ]);

            // Log activity
            log_activity('Updated business information', $business);

            return redirect()->back()->with('success', 'Business information updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Business update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update business information. Please try again.');
        }
    }

    /**
     * Generate business URL slug
     */
    public function generateUrl(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255'
        ]);

        $businessName = $request->business_name;
        $suggestedUrl = generate_business_url($businessName);
        
        return response()->json([
            'success' => true,
            'suggested_url' => $suggestedUrl,
            'full_url' => url($suggestedUrl)
        ]);
    }

    /**
     * Check URL availability
     */
    public function checkUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:100'
        ]);

        $url = $request->url;
        $currentBusinessId = Auth::user()->business?->id;
        
        $exists = \App\Models\Business::where('public_url', 'like', "%/{$url}")
            ->when($currentBusinessId, function($query) use ($currentBusinessId) {
                return $query->where('id', '!=', $currentBusinessId);
            })
            ->exists();

        return response()->json([
            'available' => !$exists,
            'url' => $url
        ]);
    }

    /**
     * Update business URL
     */
    public function updateUrl(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;
        
        if (!$business) {
            return response()->json(['success' => false, 'message' => 'Business not found'], 404);
        }

        $request->validate([
            'public_url' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('businesses', 'public_url')->ignore($business->id)
            ]
        ]);

        try {
            $newUrl = url('/' . $request->public_url);
            
            $business->update([
                'public_url' => $newUrl
            ]);

            // Log activity
            log_activity('Updated business URL', $business);

            return response()->json([
                'success' => true,
                'message' => 'Business URL updated successfully',
                'new_url' => $newUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update URL'
            ], 500);
        }
    }

    /**
     * Generate QR Code for business
     */
    public function generateQrCode()
    {
        $user = Auth::user();
        $business = $user->business;
        
        if (!$business || !$business->public_url) {
            return response()->json(['success' => false, 'message' => 'Business URL not set'], 400);
        }

        try {
            // Generate QR code (you would use a QR code library here)
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($business->public_url);
            
            $business->update(['qr_code' => $qrCodeUrl]);

            return response()->json([
                'success' => true,
                'qr_code_url' => $qrCodeUrl
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate QR code'], 500);
        }
    }
}