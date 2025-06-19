<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AiContentController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index()
    {
        $user = Auth::user();
        $business = $user->business;
        $products = $business ? $business->products()->get() : collect();

        return view('user.ai-content.index', compact('user', 'business', 'products'));
    }

    public function generateBusinessDescription(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'main_products' => 'required|string|max:500',
            'strengths' => 'required|string|max:500',
            'target_market' => 'required|string|max:255',
            'format' => 'nullable|string|in:text,json'
        ]);

        try {
            if ($request->format === 'json') {
                $result = $this->geminiService->generateBusinessDescriptionJson($request->all());

                // Jika hasil berupa array
                if (is_array($result)) {
                    return response()->json([
                        'success' => true,
                        'content' => $result
                    ]);
                }
            } else {
                $rawResult = $this->geminiService->generateBusinessDescription($request->all());

                $shortDesc = '';
                $fullDesc = '';

                if (preg_match('/DESKRIPSI_SINGKAT\s*:\s*(.+?)(?=DESKRIPSI_LENGKAP|$)/s', $rawResult, $shortMatches)) {
                    $shortDesc = trim($shortMatches[1]);
                }

                if (preg_match('/DESKRIPSI_LENGKAP\s*:\s*(.+)$/s', $rawResult, $fullMatches)) {
                    $fullDesc = trim($fullMatches[1]);
                }

                if (empty($shortDesc)) {
                    $shortDesc = substr(strip_tags($rawResult), 0, 150);
                }

                if (empty($fullDesc)) {
                    $fullDesc = $rawResult;
                }

                $result = [
                    'short_description' => $shortDesc,
                    'full_description' => $fullDesc
                ];

                return response()->json([
                    'success' => true,
                    'content' => $result
                ]);
            }

            return response()->json([
                'success' => true,
                'content' => $rawResult
            ]);
        } catch (\Exception $e) {
            Log::error('AI Business Description generation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat deskripsi bisnis. Silakan coba lagi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateProductDescription(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:products,id',
            'category' => 'required|string|max:255',
            'features' => 'required|string|max:500',
            'benefits' => 'required|string|max:500',
            'target_user' => 'required|string|max:255',
            'format' => 'nullable|string|in:text,json'
        ]);

        try {
            // Fetch the product from database
            $user = Auth::user();
            $product = $user->business->products()->findOrFail($request->product_id);

            // Build the product info array
            $productInfo = [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'price' => format_currency($product->product_price),
                'category' => $request->category,
                'features' => $request->features,
                'benefits' => $request->benefits,
                'target_user' => $request->target_user,
            ];

            if ($request->format === 'json') {
                $result = $this->geminiService->generateProductDescriptionJson($productInfo);

                if (is_array($result)) {
                    return response()->json([
                        'success' => true,
                        'content' => $result,
                        'product_id' => $product->id
                    ]);
                }
            } else {
                $rawResult = $this->geminiService->generateProductDescription($productInfo);

                // Parse response with format: DESKRIPSI_PRODUK: [text]
                $prodDesc = '';

                // Extract product description with regex
                if (preg_match('/DESKRIPSI_PRODUK\s*:\s*(.+)$/s', $rawResult, $matches)) {
                    $prodDesc = trim($matches[1]);
                } else {
                    $prodDesc = $rawResult;
                }

                $result = [
                    'product_description' => $prodDesc
                ];

                return response()->json([
                    'success' => true,
                    'content' => $result,
                    'product_id' => $product->id
                ]);
            }

            // Fallback jika terjadi kesalahan
            return response()->json([
                'success' => true,
                'content' => $rawResult,
                'product_id' => $product->id
            ]);
        } catch (\Exception $e) {
            Log::error('AI Product Description generation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat deskripsi produk. Silakan coba lagi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateHeadline(Request $request)
    {

        try {
            // Dapatkan data bisnis dari user yang login
            $user = Auth::user();
            $business = $user->business;

            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data bisnis tidak ditemukan. Silakan lengkapi profil bisnis Anda terlebih dahulu.'
                ], 400);
            }

            // Jika short_description atau full_description kosong, berikan pesan error
            if (empty($business->short_description) && empty($business->full_description)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Deskripsi bisnis belum diisi. Silakan lengkapi deskripsi bisnis Anda terlebih dahulu.'
                ], 400);
            }

            // Siapkan data untuk dikirim ke Gemini API
            $headlineInfo = [
                'business_name' => $business->business_name,
                'business_type' => $request->input('business_type', ''), // Masih menerima input jenis bisnis dari form
                'short_description' => $business->short_description ?: '',
                'full_description' => $business->full_description ?: '',
                'full_story' => $business->full_story ?: '',
                'target_market' => $request->input('target_market', ''), // Masih menerima input target pasar dari form
                'core_values' => $request->input('core_values', ''), // Masih menerima input nilai dari form
                'strengths' => $request->input('strengths', '') // Masih menerima input kekuatan dari form
            ];
            $result = $this->geminiService->generateHeadlineJson($headlineInfo);

            // Jika hasil berupa array
            if (is_array($result)) {
                return response()->json([
                    'success' => true,
                    'content' => $result
                ]);
            }

            // Fallback jika terjadi kesalahan
            return response()->json([
                'success' => true,
                'content' => $rawResult
            ]);
        } catch (\Exception $e) {
            Log::error('AI Headline generation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat headline. Silakan coba lagi: ' . $e->getMessage()
            ], 500);
        }
    }

    // Fungsi pembantu untuk mengekstrak keunggulan bisnis dari deskripsi
    private function extractBusinessStrengths($description)
    {
        // Implementasi sederhana - mengambil beberapa kalimat dari deskripsi
        $sentences = preg_split('/(?<=[.!?])\s+/', $description, -1, PREG_SPLIT_NO_EMPTY);
        $relevantSentences = array_slice($sentences, 0, 3); // Ambil 3 kalimat pertama

        return implode(' ', $relevantSentences);
    }

    // Fungsi pembantu untuk mengekstrak nilai-nilai bisnis dari deskripsi
    private function extractBusinessValues($description)
    {
        // Implementasi sederhana - mengambil beberapa kalimat dari deskripsi
        $sentences = preg_split('/(?<=[.!?])\s+/', $description, -1, PREG_SPLIT_NO_EMPTY);
        $relevantSentences = array_slice($sentences, min(3, count($sentences) - 1), 3); // Ambil 3 kalimat berikutnya

        return implode(' ', $relevantSentences);
    }

    public function saveBusinessDescription(Request $request)
    {
        $request->validate([
            'short_description' => 'required|string|max:160',
            'full_description' => 'required|string',
        ]);

        try {
            $user = Auth::user();
            $business = $user->business;

            $business->update([
                'short_description' => $request->short_description,
                'full_description' => $request->full_description,
            ]);

            // Recalculate completion
            $business->updateProgressCompletion();

            // Log activity
            log_activity('Updated business description using AI generator', $business);

            return response()->json([
                'success' => true,
                'message' => 'Deskripsi bisnis berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            Log::error('Save AI business description error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan deskripsi. Silakan coba lagi.'
            ], 500);
        }
    }

    public function saveProductDescription(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:products,id',
            'product_description' => 'required|string|max:1000',
        ]);

        try {
            $user = Auth::user();
            $product = $user->business->products()->findOrFail($request->product_id);

            $product->update([
                'product_description' => $request->product_description,
            ]);

            // Log activity
            log_activity('Updated product description using AI generator', $product);

            return response()->json([
                'success' => true,
                'message' => 'Deskripsi produk berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            Log::error('Save AI product description error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan deskripsi produk. Silakan coba lagi.'
            ], 500);
        }
    }
}