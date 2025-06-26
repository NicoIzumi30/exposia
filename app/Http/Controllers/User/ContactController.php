<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessContact;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    /**
     * Display halaman manajemen kontak.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;
        
        if (!$business) {
            return redirect()->route('user.business.index')
                ->with('warning', 'Silakan lengkapi data usaha terlebih dahulu sebelum mengatur kontak bisnis.');
        }
        
        $contacts = $business->contacts()
                          ->ordered()
                          ->get();
        
        $availableTypes = BusinessContact::getAvailableTypes();

        return view('user.contacts.index', compact('user', 'business', 'contacts', 'availableTypes'));
    }

    /**
     * Store kontak baru.
     */
    public function store(ContactRequest $request)
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
            
            $contactData = $request->validated();
            $contactData['business_id'] = $business->id;
            
            // Set order sebagai posisi terakhir
            $lastOrder = $business->contacts()->max('order') ?? 0;
            $contactData['order'] = $lastOrder + 1;
            
            $contact = BusinessContact::create($contactData);

            // Update business completion
            $business->updateProgressCompletion();

            // Log activity
            log_activity('Kontak bisnis ditambahkan: ' . $contact->contact_title, $contact);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kontak berhasil ditambahkan!',
                    'contact' => $contact->load('business'),
                ]);
            }

            return redirect()->back()->with('success', 'Kontak berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Business contact creation error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan kontak. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan kontak. Silakan coba lagi.');
        }
    }

    /**
     * Show kontak untuk diedit.
     */
    public function show(BusinessContact $contact)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($contact->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'contact' => $contact
        ]);
    }

    /**
     * Update kontak.
     */
    public function update(ContactRequest $request, BusinessContact $contact)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($contact->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $contactData = $request->validated();
            $oldTitle = $contact->contact_title;
            
            $contact->update($contactData);

            // Update business completion
            $user->business->updateProgressCompletion();

            // Log activity
            log_activity("Kontak bisnis diperbarui: '$oldTitle' -> '{$contact->contact_title}'", $contact);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kontak berhasil diperbarui!',
                    'contact' => $contact->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Kontak berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Business contact update error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui kontak. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui kontak. Silakan coba lagi.');
        }
    }

    /**
     * Delete kontak.
     */
    public function destroy(BusinessContact $contact)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($contact->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $contactTitle = $contact->contact_title;
            $contact->delete();

            // Update business completion
            $user->business->updateProgressCompletion();

            // Log activity
            log_activity('Kontak bisnis dihapus: ' . $contactTitle);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kontak berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Business contact deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kontak. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Update urutan kontak.
     */
    public function updateOrder(Request $request)
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
            
            $orderData = $request->input('order', []);
            
            foreach ($orderData as $position => $contactId) {
                $contact = BusinessContact::where('id', $contactId)
                                      ->where('business_id', $business->id)
                                      ->first();
                
                if ($contact) {
                    $contact->update(['order' => $position + 1]);
                }
            }

            // Log activity
            log_activity('Urutan kontak bisnis diperbarui', $business);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Urutan kontak berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Business contact order update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan kontak. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Toggle status aktif kontak.
     */
    public function toggleActive(BusinessContact $contact)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($contact->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $contact->update(['is_active' => !$contact->is_active]);

            // Update business completion
            $user->business->updateProgressCompletion();

            // Log activity
            $status = $contact->is_active ? 'diaktifkan' : 'dinonaktifkan';
            log_activity("Kontak bisnis {$contact->contact_title} {$status}", $contact);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Kontak berhasil {$status}!",
                'is_active' => $contact->is_active
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Business contact toggle error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status kontak. Silakan coba lagi.'
            ], 500);
        }
    }
}