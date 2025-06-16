<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Branch;
use App\Http\Requests\BranchRequest;

class BranchController extends Controller
{
    /**
     * Display branches management page.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;
        
        if (!$business) {
            return redirect()->route('user.business.index')
                ->with('warning', 'Silakan lengkapi data usaha terlebih dahulu sebelum menambah cabang.');
        }
        
        $branches = $business->branches()->orderBy('created_at', 'desc')->get();
        
        return view('user.branches.index', compact('user', 'business', 'branches'));
    }

    /**
     * Store a new branch.
     */
    public function store(BranchRequest $request)
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
            
            $branchData = $request->only([
                'branch_name',
                'branch_address', 
                'branch_operational_hours',
                'branch_google_maps_link',
                'branch_phone'
            ]);

            // Format phone number for WhatsApp if provided
            if ($branchData['branch_phone']) {
                $branchData['branch_phone'] = format_phone_wa($branchData['branch_phone']);
            }

            $branch = $business->branches()->create($branchData);

            // Log activity
            log_activity('Created new branch: ' . $branch->branch_name, $branch);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cabang berhasil ditambahkan!',
                    'branch' => $branch->load('business')
                ]);
            }

            return redirect()->back()->with('success', 'Cabang berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Branch creation error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan cabang. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan cabang. Silakan coba lagi.');
        }
    }

    /**
     * Show the specified branch for editing.
     */
    public function show(Branch $branch)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($branch->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'branch' => $branch
        ]);
    }

    /**
     * Update the specified branch.
     */
    public function update(BranchRequest $request, Branch $branch)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($branch->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $oldData = $branch->toArray();
            
            $branchData = $request->only([
                'branch_name',
                'branch_address', 
                'branch_operational_hours',
                'branch_google_maps_link',
                'branch_phone'
            ]);

            // Format phone number for WhatsApp if provided
            if ($branchData['branch_phone']) {
                $branchData['branch_phone'] = format_phone_wa($branchData['branch_phone']);
            }

            $branch->update($branchData);

            // Log activity with old and new data
            log_activity('Updated branch: ' . $branch->branch_name, $branch);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cabang berhasil diperbarui!',
                    'branch' => $branch->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Cabang berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Branch update error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui cabang. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui cabang. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified branch.
     */
    public function destroy(Branch $branch)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($branch->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $branchName = $branch->branch_name;
            $branch->delete();

            // Log activity
            log_activity('Deleted branch: ' . $branchName);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cabang berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Branch deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus cabang. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Validate Google Maps URL.
     */
    public function validateMapsUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $url = $request->url;
        $isValid = validate_google_maps_url($url);

        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? 'URL Google Maps valid' : 'URL Google Maps tidak valid'
        ]);
    }

    /**
     * Generate WhatsApp link for branch.
     */
    public function generateWhatsAppLink(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $phone = format_phone_wa($request->phone);
        $waLink = whatsapp_link($phone);

        return response()->json([
            'success' => true,
            'formatted_phone' => $phone,
            'whatsapp_link' => $waLink
        ]);
    }
}