<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiChatController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Handle chat message untuk specific business
     */
   public function chat(Request $request, $businessSlug)
{
    $request->validate([
        'message' => 'required|string|max:1000',
        'session_id' => 'nullable|string|max:255'
    ]);

    Log::info('=== CHAT REQUEST START ===', [
        'business_slug' => $businessSlug,
        'message' => $request->message,
        'session_id' => $request->session_id,
        'step' => 'request_received'
    ]);

    try {
        // Find business berdasarkan slug
        $business = $this->findBusinessBySlug($businessSlug);
        Log::info('Business found', ['step' => 'business_found', 'business_id' => $business?->id]);
        
        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'Bisnis tidak ditemukan.'
            ], 404);
        }

        // Generate session ID jika belum ada
        $sessionId = $request->session_id ?: uniqid('chat_');
        Log::info('Session ID generated', ['step' => 'session_id', 'session_id' => $sessionId]);

        // Build business context untuk AI
        $businessContext = $this->buildBusinessContext($business);
        Log::info('Business context built', ['step' => 'context_built', 'context_size' => strlen(json_encode($businessContext))]);

        // Get chat history - INI YANG BERMASALAH DI CHAT KEDUA
        $chatHistory = $this->getChatHistory($sessionId);
        Log::info('Chat history retrieved', [
            'step' => 'history_retrieved', 
            'history_count' => count($chatHistory),
            'history_size' => strlen(json_encode($chatHistory))
        ]);

        // Generate AI response - INI TEMPAT ERROR KEMUNGKINAN TERJADI
        Log::info('Calling Gemini service', ['step' => 'before_gemini_call']);
        
        $aiResponse = $this->geminiService->generateChatResponse(
            $request->message,
            $businessContext,
            $chatHistory
        );
        
        Log::info('Gemini response received', ['step' => 'after_gemini_call']);

        // Save chat to history
        $this->saveChatHistory($sessionId, $request->message, $aiResponse);
        Log::info('Chat history saved', ['step' => 'history_saved']);

        // Increment visitor if first message in session
        $this->trackVisitorInteraction($business, $sessionId);
        
        Log::info('=== CHAT REQUEST SUCCESS ===');

        return response()->json([
            'success' => true,
            'response' => $aiResponse,
            'session_id' => $sessionId,
            'business_name' => $business->business_name,
            'whatsapp_contact' => $this->getWhatsAppContact($business)
        ]);

    } catch (\Exception $e) {
        Log::error('=== CHAT REQUEST ERROR ===', [
            'business_slug' => $businessSlug,
            'message' => $request->message,
            'error_message' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi via WhatsApp.',
            'fallback_action' => 'whatsapp',
            'session_id' => $request->session_id ?? uniqid('chat_')
        ], 500);
    }
}

    /**
     * Get business info untuk chat initialization
     */
    public function getBusinessInfo($businessSlug)
    {
        try {
            $business = $this->findBusinessBySlug($businessSlug);
            
            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bisnis tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'business' => [
                    'name' => $business->business_name,
                    'description' => $business->short_description,
                    'logo' => $business->logo_url ? asset('storage/' . $business->logo_url) : null,
                    'operational_hours' => $business->main_operational_hours,
                    'whatsapp' => $this->getWhatsAppContact($business)
                ],
                'welcome_message' => $this->generateWelcomeMessage($business)
            ]);

        } catch (\Exception $e) {
            Log::error('Get business info error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat informasi bisnis.'
            ], 500);
        }
    }

    /**
     * Find business by slug dari URL
     */
    private function findBusinessBySlug($slug)
    {
        // Cache business data for 1 hour
        return Cache::remember("business_slug_{$slug}", 3600, function () use ($slug) {
            return Business::where('public_url', 'like', "%/{$slug}")
                          ->with(['products' => function ($query) {
                              $query->select('id', 'business_id', 'product_name', 'product_price', 'product_description', 'is_pinned')
                                    ->where('is_pinned', true)
                                    ->limit(6);
                          }])
                          ->with(['branches' => function ($query) {
                              $query->select('id', 'business_id', 'branch_name', 'branch_address', 'branch_operational_hours', 'branch_phone');
                          }])
                          ->with(['highlights' => function ($query) {
                              $query->select('id', 'business_id', 'title', 'description')
                                    ->limit(5);
                          }])
                          ->with(['contacts' => function ($query) {
                              $query->active()->ordered();
                          }])
                          ->first();
        });
    }

    /**
     * Build comprehensive business context untuk AI
     */
    private function buildBusinessContext($business)
    {
        return [
            'business_info' => [
                'name' => $business->business_name,
                'description' => $business->short_description ?? $business->full_description,
                'address' => $business->main_address,
                'operational_hours' => $business->main_operational_hours,
                'story' => $business->full_story ? substr(strip_tags($business->full_story), 0, 500) : null
            ],
            'products' => $business->products->map(function ($product) {
                return [
                    'name' => $product->product_name,
                    'price' => $product->product_price,
                    'description' => $product->product_description,
                    'formatted_price' => format_currency($product->product_price)
                ];
            })->toArray(),
            'branches' => $business->branches->map(function ($branch) {
                return [
                    'name' => $branch->branch_name,
                    'address' => $branch->branch_address,
                    'operational_hours' => $branch->branch_operational_hours,
                    'phone' => $branch->branch_phone
                ];
            })->toArray(),
            'highlights' => $business->highlights->map(function ($highlight) {
                return [
                    'title' => $highlight->title,
                    'description' => $highlight->description
                ];
            })->toArray(),
            'contacts' => $business->contacts->map(function ($contact) {
                return [
                    'type' => $contact->contact_type,
                    'title' => $contact->contact_title,
                    'value' => $contact->contact_value
                ];
            })->toArray()
        ];
    }

    /**
     * Get chat history from cache
     */
    private function getChatHistory($sessionId)
    {
        return Cache::get("chat_history_{$sessionId}", []);
    }

    /**
     * Save chat to history
     */
 private function saveChatHistory($sessionId, $userMessage, $aiResponse)
{
    try {
        $history = $this->getChatHistory($sessionId);
        
        // Pastikan aiResponse dalam format yang benar
        $responseText = is_array($aiResponse) ? 
            ($aiResponse['message'] ?? json_encode($aiResponse)) : 
            (string) $aiResponse;
        
        $history[] = [
            'user' => $userMessage,
            'ai' => $responseText,
            'timestamp' => now()->toISOString()
        ];

        // Keep only last 5 exchanges untuk mengurangi context size
        if (count($history) > 5) {
            $history = array_slice($history, -5);
        }

        Cache::put("chat_history_{$sessionId}", $history, 7200);
        
        Log::debug('Chat history saved successfully', [
            'session_id' => $sessionId,
            'history_count' => count($history)
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error saving chat history', [
            'session_id' => $sessionId,
            'error' => $e->getMessage()
        ]);
        // Jangan throw error, biarkan chat tetap berjalan
    }
}


    /**
     * Track visitor interaction
     */
    private function trackVisitorInteraction($business, $sessionId)
    {
        $cacheKey = "visitor_tracked_{$sessionId}";
        
        if (!Cache::has($cacheKey)) {
            // Create visitor record
            $business->visitors()->create([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referrer' => request()->header('referer')
            ]);

            // Mark as tracked for this session
            Cache::put($cacheKey, true, 3600);
        }
    }

    /**
     * Get WhatsApp contact info
     */
    private function getWhatsAppContact($business)
    {
        $rawNumber = $business->user->phone ?? null;
        
        if (!$rawNumber) {
            return null;
        }

        $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $rawNumber));
        
        return [
            'number' => $waNumber,
            'url' => "https://wa.me/{$waNumber}",
            'formatted' => format_phone_wa($rawNumber)
        ];
    }

    /**
     * Generate welcome message
     */
    private function generateWelcomeMessage($business)
    {
        $messages = [
            "Halo! Selamat datang di {$business->business_name}! 👋",
            "Ada yang bisa saya bantu hari ini?",
            "Saya siap membantu Anda dengan informasi produk, lokasi, jam operasional, dan pertanyaan lainnya!"
        ];

        return implode("\n\n", $messages);
    }

    /**
     * Clear chat history
     */
    public function clearHistory(Request $request, $businessSlug)
    {
        $request->validate([
            'session_id' => 'required|string'
        ]);

        Cache::forget("chat_history_{$request->session_id}");

        return response()->json([
            'success' => true,
            'message' => 'Riwayat chat telah dihapus.'
        ]);
    }
}