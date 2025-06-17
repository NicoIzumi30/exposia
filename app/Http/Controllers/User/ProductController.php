<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display products management page.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;
        
        if (!$business) {
            return redirect()->route('user.business.index')
                ->with('warning', 'Silakan lengkapi data usaha terlebih dahulu sebelum menambah produk.');
        }
        
        $products = $business->products()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $productStats = [
            'total' => $business->products()->count(),
            'pinned' => $business->products()->where('is_pinned', true)->count(),
            'with_images' => $business->products()->whereNotNull('product_image')->count(),
            'with_wa_links' => $business->products()->whereNotNull('product_wa_link')->count(),
        ];
        
        return view('user.products.index', compact('user', 'business', 'products', 'productStats'));
    }

    /**
     * Store a new product.
     */
    public function store(ProductRequest $request)
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
            
            $productData = $request->only([
                'product_name',
                'product_description',
                'product_price',
                'product_wa_link',
                'is_pinned'
            ]);

            // Handle product image upload
            if ($request->hasFile('product_image')) {
                $imagePath = $request->file('product_image')->store('product-images', 'public');
                $productData['product_image'] = $imagePath;
            }

            // Format WhatsApp link if provided
            if (!empty($productData['product_wa_link']) && !filter_var($productData['product_wa_link'], FILTER_VALIDATE_URL)) {
                $productData['product_wa_link'] = whatsapp_link($productData['product_wa_link']);
            }

            // Handle is_pinned checkbox
            $productData['is_pinned'] = $request->has('is_pinned');

            $product = $business->products()->create($productData);

            // Log activity
            log_activity('Created new product: ' . $product->product_name, $product);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan!',
                    'product' => $product->load('business')
                ]);
            }

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product creation error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan produk. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan produk. Silakan coba lagi.');
        }
    }

    /**
     * Show the specified product for editing.
     */
    public function show(Product $product)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($product->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($product->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $productData = $request->only([
                'product_name',
                'product_description',
                'product_price',
                'product_wa_link',
                'is_pinned'
            ]);

            // Handle product image upload
            if ($request->hasFile('product_image')) {
                // Delete old image if exists
                if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                    Storage::disk('public')->delete($product->product_image);
                }

                // Store new image
                $imagePath = $request->file('product_image')->store('product-images', 'public');
                $productData['product_image'] = $imagePath;
            }

            // Format WhatsApp link if provided
            if (!empty($productData['product_wa_link']) && !filter_var($productData['product_wa_link'], FILTER_VALIDATE_URL)) {
                $productData['product_wa_link'] = whatsapp_link($productData['product_wa_link']);
            }

            // Handle is_pinned checkbox
            $productData['is_pinned'] = $request->has('is_pinned');

            $product->update($productData);

            // Log activity
            log_activity('Updated product: ' . $product->product_name, $product);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil diperbarui!',
                    'product' => $product->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui produk. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui produk. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($product->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $productName = $product->product_name;

            // Delete product image if exists
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }

            $product->delete();

            // Log activity
            log_activity('Deleted product: ' . $productName);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Toggle pin status of a product.
     */
    public function togglePin(Product $product)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($product->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $product->update([
                'is_pinned' => !$product->is_pinned
            ]);

            $status = $product->is_pinned ? 'dipinned' : 'di-unpin';
            
            // Log activity
            log_activity('Product ' . $status . ': ' . $product->product_name, $product);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ' . $status . '!',
                'is_pinned' => $product->is_pinned
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product pin toggle error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status pin produk.'
            ], 500);
        }
    }

    /**
     * Bulk actions for products.
     */
    public function bulkAction(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;

        $request->validate([
            'action' => 'required|in:delete,pin,unpin',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id'
        ]);

        try {
            DB::beginTransaction();
            
            $productIds = $request->product_ids;
            $action = $request->action;

            // Verify ownership of all products
            $products = Product::whereIn('id', $productIds)
                ->where('business_id', $business->id)
                ->get();

            if ($products->count() !== count($productIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa produk tidak ditemukan.'
                ], 404);
            }

            $affectedCount = 0;
            
            switch ($action) {
                case 'delete':
                    foreach ($products as $product) {
                        // Delete image if exists
                        if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                            Storage::disk('public')->delete($product->product_image);
                        }
                        $product->delete();
                        $affectedCount++;
                    }
                    $message = "Berhasil menghapus {$affectedCount} produk.";
                    log_activity("Bulk delete {$affectedCount} products");
                    break;
                    
                case 'pin':
                    Product::whereIn('id', $productIds)->update(['is_pinned' => true]);
                    $affectedCount = count($productIds);
                    $message = "Berhasil pin {$affectedCount} produk.";
                    log_activity("Bulk pin {$affectedCount} products");
                    break;
                    
                case 'unpin':
                    Product::whereIn('id', $productIds)->update(['is_pinned' => false]);
                    $affectedCount = count($productIds);
                    $message = "Berhasil unpin {$affectedCount} produk.";
                    log_activity("Bulk unpin {$affectedCount} products");
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product bulk action error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan aksi bulk.'
            ], 500);
        }
    }

    /**
     * Search products.
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;

        $query = $request->get('q', '');
        
        $products = $business->products()
            ->when($query, function ($q) use ($query) {
                return $q->where('product_name', 'like', "%{$query}%")
                         ->orWhere('product_description', 'like', "%{$query}%");
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Generate WhatsApp order link for product.
     */
    public function generateWhatsAppOrder(Request $request, Product $product)
    {
        $user = Auth::user();
        
        // Check ownership
        if ($product->business_id !== $user->business?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        $phone = $product->product_wa_link ?: $product->business->main_phone;
        
        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak tersedia untuk produk ini.'
            ], 400);
        }

        $message = "Halo! Saya tertarik dengan produk:\n\n";
        $message .= "*{$product->product_name}*\n";
        $message .= "Harga: " . format_currency($product->product_price) . "\n\n";
        $message .= "Mohon informasi lebih lanjut. Terima kasih!";

        $waLink = whatsapp_link($phone, $message);

        return response()->json([
            'success' => true,
            'whatsapp_link' => $waLink,
            'message' => $message
        ]);
    }
}