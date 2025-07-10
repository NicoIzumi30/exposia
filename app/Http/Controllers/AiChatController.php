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

        try {
            // Find business berdasarkan slug
            $business = $this->findBusinessBySlug($businessSlug);
            
            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bisnis tidak ditemukan.'
                ], 404);
            }

            // Generate session ID jika belum ada
            $sessionId = $request->session_id ?: uniqid('chat_');

            // Build business context untuk AI
            $businessContext = $this->buildBusinessContext($business);

            // Generate AI response
            $aiResponse = $this->geminiService->generateChatResponse(
                $request->message,
                $businessContext,
                $this->getChatHistory($sessionId)
            );

            // Save chat to history
            $this->saveChatHistory($sessionId, $request->message, $aiResponse);

            // Increment visitor if first message in session
            $this->trackVisitorInteraction($business, $sessionId);

            return response()->json([
                'success' => true,
                'response' => $aiResponse,
                'session_id' => $sessionId,
                'business_name' => $business->business_name,
                'whatsapp_contact' => $this->getWhatsAppContact($business)
            ]);

        } catch (\Exception $e) {
            Log::error('AI Chat error: ' . $e->getMessage(), [
                'business_slug' => $businessSlug,
                'message' => $request->message,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $sessionId,
                'fallback_action' => 'whatsapp'
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
        $history = $this->getChatHistory($sessionId);
        
        $history[] = [
            'user' => $userMessage,
            'ai' => $aiResponse,
            'timestamp' => now()->toISOString()
        ];

        // Keep only last 10 exchanges to manage context size
        if (count($history) > 10) {
            $history = array_slice($history, -10);
        }

        // Cache for 2 hours
        Cache::put("chat_history_{$sessionId}", $history, 7200);
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