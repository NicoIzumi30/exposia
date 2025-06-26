<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Testimonial;
use App\Models\BusinessHighlight;

class ContentController extends Controller
{
    /**
     * Display the content monitoring dashboard
     */
    public function index(Request $request)
    {
        $businessId = $request->input('business_id');
        $type = $request->input('type', 'products');
        $search = $request->input('search');
        
        // Get all businesses for the filter dropdown
        $businesses = Business::orderBy('business_name')->get();
        
        // Set the active business
        $activeBusiness = null;
        if ($businessId) {
            $activeBusiness = Business::find($businessId);
        }
        
        // Determine which content to display based on type
        switch ($type) {
            case 'products':
                return $this->products($request);
            case 'galleries':
                return $this->galleries($request);
            case 'testimonials':
                return $this->testimonials($request);
            case 'about':
                return $this->about($request);
            default:
                return $this->products($request);
        }
    }
    
    /**
     * Display products content
     */
    public function products(Request $request)
    {
        $businessId = $request->input('business_id');
        $search = $request->input('search');
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Get all businesses for the filter dropdown
        $businesses = Business::orderBy('business_name')->get();
        
        // Build the query
        $query = Product::with('business');
        
        // Apply business filter
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_description', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting
        $query->orderBy($sortField, $sortDirection);
        
        // Get paginated results
        $products = $query->paginate(15)->withQueryString();
        
        // Set the active business
        $activeBusiness = null;
        if ($businessId) {
            $activeBusiness = Business::find($businessId);
        }
        
        return view('admin.content.products', compact(
            'products', 
            'businesses', 
            'activeBusiness', 
            'search', 
            'sortField', 
            'sortDirection'
        ));
    }
    
    /**
     * Display galleries content
     */
    public function galleries(Request $request)
    {
        $businessId = $request->input('business_id');
        $search = $request->input('search');
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Get all businesses for the filter dropdown
        $businesses = Business::orderBy('business_name')->get();
        
        // Build the query
        $query = Gallery::with('business');
        
        // Apply business filter
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        // Apply sorting
        $query->orderBy($sortField, $sortDirection);
        
        // Get paginated results
        $galleries = $query->paginate(24)->withQueryString();
        
        // Set the active business
        $activeBusiness = null;
        if ($businessId) {
            $activeBusiness = Business::find($businessId);
        }
        
        return view('admin.content.galleries', compact(
            'galleries', 
            'businesses', 
            'activeBusiness', 
            'sortField', 
            'sortDirection'
        ));
    }
    
    /**
     * Display testimonials content
     */
    public function testimonials(Request $request)
    {
        $businessId = $request->input('business_id');
        $search = $request->input('search');
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Get all businesses for the filter dropdown
        $businesses = Business::orderBy('business_name')->get();
        
        // Build the query
        $query = Testimonial::with('business');
        
        // Apply business filter
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('testimonial_name', 'like', "%{$search}%")
                  ->orWhere('testimonial_content', 'like', "%{$search}%")
                  ->orWhere('testimonial_position', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting
        $query->orderBy($sortField, $sortDirection);
        
        // Get paginated results
        $testimonials = $query->paginate(15)->withQueryString();
        
        // Set the active business
        $activeBusiness = null;
        if ($businessId) {
            $activeBusiness = Business::find($businessId);
        }
        
        return view('admin.content.testimonials', compact(
            'testimonials', 
            'businesses', 
            'activeBusiness', 
            'search', 
            'sortField', 
            'sortDirection'
        ));
    }
    
    /**
     * Display about business content
     */
    public function about(Request $request)
    {
        $businessId = $request->input('business_id');
        
        // Get all businesses for the filter dropdown
        $businesses = Business::orderBy('business_name')->get();
        
        // Build the query for highlights
        $query = BusinessHighlight::with('business');
        
        // Apply business filter
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        // Get highlights
        $highlights = $query->get();
        
        // Set the active business
        $activeBusiness = null;
        if ($businessId) {
            $activeBusiness = Business::find($businessId);
        }
        
        return view('admin.content.about', compact(
            'highlights', 
            'businesses', 
            'activeBusiness'
        ));
    }
    
    /**
     * Delete a content item
     */
    public function delete(Request $request, $type, $id)
    {
        $redirectUrl = route('admin.content.index', ['type' => $type]);
        
        try {
            switch ($type) {
                case 'products':
                    $item = Product::findOrFail($id);
                    $businessId = $item->business_id;
                    $name = $item->product_name;
                    $item->delete();
                    $message = "Produk \"{$name}\" berhasil dihapus";
                    break;
                case 'galleries':
                    $item = Gallery::findOrFail($id);
                    $businessId = $item->business_id;
                    $item->delete();
                    $message = "Item galeri berhasil dihapus";
                    break;
                case 'testimonials':
                    $item = Testimonial::findOrFail($id);
                    $businessId = $item->business_id;
                    $name = $item->testimonial_name;
                    $item->delete();
                    $message = "Testimonial dari \"{$name}\" berhasil dihapus";
                    break;
                case 'highlights':
                    $item = BusinessHighlight::findOrFail($id);
                    $businessId = $item->business_id;
                    $title = $item->title;
                    $item->delete();
                    $message = "Highlight \"{$title}\" berhasil dihapus";
                    break;
                default:
                    return redirect($redirectUrl)->with('error', 'Tipe konten tidak valid');
            }
            
            // Log activity
            activity()
                ->withProperties([
                    'action' => 'deleted',
                    'type' => $type,
                    'item_id' => $id,
                    'business_id' => $businessId
                ])
                ->log("Admin deleted {$type} content");
            
            // Redirect back with success message
            return redirect($redirectUrl)->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect($redirectUrl)->with('error', 'Gagal menghapus konten: ' . $e->getMessage());
        }
    }
}