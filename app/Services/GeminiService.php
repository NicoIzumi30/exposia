<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $model;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('gemini.api_key');
        $this->model = config('gemini.model');
        $this->apiUrl = config('gemini.api_url');
    }

    public function generateContent($prompt)
    {
        try {
            $url = $this->apiUrl . $this->model . ':generateContent?key=' . $this->apiKey;

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => (float) config('gemini.temperature'),
                    'maxOutputTokens' => (int) config('gemini.max_output_tokens'),
                    'topP' => (float) config('gemini.top_p'),
                    'topK' => (int) config('gemini.top_k')
                ]
            ];

            // Log untuk debugging
            Log::debug('Gemini API Request: ' . json_encode($payload));

            $response = $this->client->post($url, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $responseBody = $response->getBody()->getContents();
            Log::debug('Gemini API Response: ' . $responseBody);

            $result = json_decode($responseBody, true);

            // Extract text from response
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $result['candidates'][0]['content']['parts'][0]['text'];
                return $text;
            }

            if (isset($result['error'])) {
                Log::error('Gemini API Error: ' . json_encode($result['error']));
                throw new \Exception('Gemini API Error: ' . ($result['error']['message'] ?? 'Unknown error'));
            }

            Log::warning('Unexpected response structure: ' . json_encode($result));
            return 'Tidak ada konten yang dihasilkan.';

        } catch (RequestException $e) {
            Log::error('Gemini API request error: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                $errorMessage = $response['error']['message'] ?? $e->getMessage();
                throw new \Exception('Gemini API error: ' . $errorMessage);
            }

            throw new \Exception('Gemini API error: ' . $e->getMessage());
        }
    }

    // Modifikasi metode untuk deskripsi bisnis - menggunakan format yang lebih jelas
    public function generateBusinessDescription($businessInfo)
    {
        $prompt = <<<EOT
Buat dua jenis deskripsi untuk bisnis {$businessInfo['business_name']} ({$businessInfo['business_type']}) di {$businessInfo['location']}:

1. DESKRIPSI_SINGKAT untuk SEO (maksimal 150 karakter).
2. DESKRIPSI_LENGKAP untuk halaman "Tentang Kami" (400-500 kata).

Informasi bisnis:
- Produk/Layanan: {$businessInfo['main_products']}
- Keunggulan: {$businessInfo['strengths']}
- Target Pasar: {$businessInfo['target_market']}

PENTING: Format respons harus seperti ini:
---
DESKRIPSI_SINGKAT: [isi deskripsi singkat tanpa judul atau format lain]

DESKRIPSI_LENGKAP: [isi deskripsi lengkap tanpa judul atau format lain]
---

Jangan tambahkan teks penjelasan, instruksi, atau judul lain di luar format yang diminta.
EOT;

        return $this->generateContent($prompt);
    }

    // Modifikasi untuk JSON menggunakan format respons yang eksplisit
    public function generateBusinessDescriptionJson($businessInfo)
    {
        $prompt = <<<EOT
Buat dua jenis deskripsi untuk bisnis {$businessInfo['business_name']} ({$businessInfo['business_type']}) di {$businessInfo['location']}:

1. Deskripsi singkat untuk SEO (maksimal 150 karakter)
2. Deskripsi lengkap untuk halaman "Tentang Kami" (400-500 kata)

Informasi bisnis:
- Produk/Layanan: {$businessInfo['main_products']}
- Keunggulan: {$businessInfo['strengths']}
- Target Pasar: {$businessInfo['target_market']}

PENTING: Respons harus berupa teks yang diformatkan seperti JSON berikut:

```json
{
  "short_description": "Deskripsi singkat di sini (maksimal 150 karakter)",
  "full_description": "Deskripsi lengkap di sini (400-500 kata)"
}
  Berikan HANYA teks JSON, tanpa penjelasan atau komentar tambahan. Pastikan format JSON valid dengan tanda kutip ganda di semua kunci dan nilai.
EOT;
        $response = $this->generateContent($prompt);

        // Ekstrak JSON dari respons
        $jsonMatch = [];
        if (
            preg_match('/```json\s*(.*?)\s*```/s', $response, $jsonMatch) ||
            preg_match('/({[\s\S]*})/', $response, $jsonMatch)
        ) {
            try {
                $json = json_decode($jsonMatch[1], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $json;
                }
            } catch (\Exception $e) {
                Log::error('Error parsing JSON response: ' . $e->getMessage());
            }
        }

        // Fallback jika parsing JSON gagal
        return [
            'short_description' => $this->extractShortDescription($response),
            'full_description' => $this->extractFullDescription($response)
        ];
    }

    // Helper methods untuk ekstraksi respons
    private function extractShortDescription($text)
    {
        if (preg_match('/short_description["\']\s*:\s*["\']([^"\']+)["\']/', $text, $matches)) {
            return $matches[1];
        }

        if (preg_match('/DESKRIPSI_SINGKAT\s*:\s*(.+?)(?=DESKRIPSI_LENGKAP|$)/s', $text, $matches)) {
            return trim($matches[1]);
        }

        // Fallback - ambil maksimal 150 karakter pertama
        return substr(strip_tags($text), 0, 150);
    }

    private function extractFullDescription($text)
    {
        if (preg_match('/full_description["\']\s*:\s*["\']([^"\']+)["\']/', $text, $matches)) {
            return $matches[1];
        }

        if (preg_match('/DESKRIPSI_LENGKAP\s*:\s*(.+)$/s', $text, $matches)) {
            return trim($matches[1]);
        }

        // Fallback - gunakan seluruh teks
        return $text;
    }

    // Updated product description generator to match business description pattern
    public function generateProductDescription($productInfo)
    {
        $prompt = <<<EOT
Buat deskripsi produk untuk "{$productInfo['product_name']}" dengan harga {$productInfo['price']} dengan format berikut:

DESKRIPSI_PRODUK: [Deskripsi produk yang menarik, 50-70 kata yang menjelaskan manfaat dan keunggulan produk]

Informasi produk:
- Kategori: {$productInfo['category']}
- Fitur: {$productInfo['features']}
- Manfaat: {$productInfo['benefits']}
- Target Pengguna: {$productInfo['target_user']}

PENTING: Format respons harus seperti ini:
---
DESKRIPSI_PRODUK: [isi deskripsi produk tanpa judul atau format lain]
---

Jangan tambahkan teks penjelasan, instruksi, atau judul lain di luar format yang diminta.
EOT;

        return $this->generateContent($prompt);
    }

    public function generateProductDescriptionJson($productInfo)
    {
        $prompt = <<<EOT
Buat deskripsi produk untuk "{$productInfo['product_name']}" dengan harga {$productInfo['price']} dengan format berikut:

Deskripsi produk utama (150-200 kata)
3-5 fitur kunci produk (dalam format list)
Meta description untuk SEO (maksimal 150 karakter)

Informasi produk:
- Kategori: {$productInfo['category']}
- Fitur: {$productInfo['features']}
- Manfaat: {$productInfo['benefits']}
- Target Pengguna: {$productInfo['target_user']}

PENTING: Respons harus berupa teks yang diformatkan seperti JSON berikut:
```json
{
  "product_description": "Deskripsi produk lengkap di sini",
  "key_features": ["Fitur 1", "Fitur 2", "Fitur 3", "Fitur 4", "Fitur 5"],
  "meta_description": "Meta description untuk SEO (maksimal 150 karakter)"
}
  Berikan HANYA teks JSON, tanpa penjelasan atau komentar tambahan. Pastikan format JSON valid dengan tanda kutip ganda di semua kunci dan nilai.
EOT;
        $response = $this->generateContent($prompt);
        // Ekstrak JSON dari respons
        $jsonMatch = [];
        if (
            preg_match('/```json\s*(.*?)\s*```/s', $response, $jsonMatch) ||
            preg_match('/({[\s\S]*})/', $response, $jsonMatch)
        ) {
            try {
                $json = json_decode($jsonMatch[1], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $json;
                }
            } catch (\Exception $e) {
                Log::error('Error parsing JSON response: ' . $e->getMessage());
            }
        }

        // Fallback jika parsing JSON gagal
        return [
            'product_description' => $this->extractProductDescription($response),
            'key_features' => $this->extractKeyFeatures($response),
            'meta_description' => $this->extractMetaDescription($response)
        ];
    }

    private function extractProductDescription($text)
    {
        if (preg_match('/product_description["\']\s*:\s*["\']([^"\']+)["\']/', $text, $matches)) {
            return $matches[1];
        }

        if (preg_match('/DESKRIPSI_PRODUK\s*:\s*(.+)$/s', $text, $matches)) {
            return trim($matches[1]);
        }

        return $text;
    }

    private function extractKeyFeatures($text)
    {
        $features = [];

        if (preg_match('/key_features["\']\s*:\s*\[(.*?)\]/s', $text, $matches)) {
            $featuresStr = $matches[1];
            preg_match_all('/["\'](.*?)["\']/s', $featuresStr, $featureMatches);
            if (!empty($featureMatches[1])) {
                return $featureMatches[1];
            }
        }

        // Fallback - cari fitur dengan pola daftar
        preg_match_all('/[\-\*]\s*(.+)$/m', $text, $matches);
        if (!empty($matches[1])) {
            return array_slice($matches[1], 0, 5);
        }

        return $features;
    }

    private function extractMetaDescription($text)
    {
        if (preg_match('/meta_description["\']\s*:\s*["\']([^"\']+)["\']/', $text, $matches)) {
            return $matches[1];
        }

        return substr(strip_tags($text), 0, 150);
    }

    public function generateHeadline($headlineInfo)
    {
        // Menggunakan data dari bisnis yang sudah ada
        $businessName = $headlineInfo['business_name'];
        $businessType = $headlineInfo['business_type'] ?: 'Bisnis';
        $shortDescription = $headlineInfo['short_description'] ?: '';
        $fullDescription = $headlineInfo['full_description'] ?: '';
        $fullStory = $headlineInfo['full_story'] ?: '';
        $targetMarket = $headlineInfo['target_market'] ?: '';
        $coreValues = $headlineInfo['core_values'] ?: '';
        $strengths = $headlineInfo['strengths'] ?: '';
        
        // Bangun prompt berdasarkan data yang tersedia
        $businessContext = '';
        
        if (!empty($shortDescription)) {
            $businessContext .= "Deskripsi singkat: $shortDescription\n";
        }
        
        if (!empty($fullDescription)) {
            $businessContext .= "Deskripsi lengkap: $fullDescription\n";
        }
        
        if (!empty($fullStory)) {
            $businessContext .= "Cerita bisnis: $fullStory\n";
        }
        
        $prompt = <<<EOT
    Buat 5 headline/tagline menarik untuk bisnis "$businessName" ($businessType) dengan format berikut:
    
    HEADLINE_1: [Headline 1]
    HEADLINE_2: [Headline 2]
    HEADLINE_3: [Headline 3]
    HEADLINE_4: [Headline 4]
    HEADLINE_5: [Headline 5]
    
    Informasi bisnis:
    $businessContext
    Target Pasar: $targetMarket
    Nilai Utama: $coreValues
    Keunggulan: $strengths
    
    PENTING: 
    - Setiap headline harus singkat (maksimal 60 karakter)
    - Headline harus menarik dan mencerminkan nilai bisnis
    - Gunakan bahasa Indonesia yang efektif
    - Jangan tambahkan teks lain selain format yang diminta
    EOT;
    
        return $this->generateContent($prompt);
    }

    public function generateHeadlineJson($headlineInfo)
    {
        $prompt = <<<EOT
Buat 5 headline/tagline menarik untuk bisnis "{$headlineInfo['business_name']}" ({$headlineInfo['business_type']}).
Informasi bisnis:
Target Pasar: {$headlineInfo['target_market']}
Nilai Utama: {$headlineInfo['core_values']}
Keunggulan: {$headlineInfo['strengths']}
PENTING: Respons harus berupa teks yang diformatkan seperti JSON berikut:
    {
  "headlines": [
    {"text": "Headline 1", "style": "catchy"},
    {"text": "Headline 2", "style": "professional"},
    {"text": "Headline 3", "style": "motivational"},
    {"text": "Headline 4", "style": "catchy"},
    {"text": "Headline 5", "style": "professional"}
  ]
}
  Setiap headline harus singkat (maksimal 60 karakter), menarik, dan mencerminkan nilai bisnis. Style bisa "catchy", "professional", atau "motivational". Berikan HANYA teks JSON, tanpa penjelasan atau komentar tambahan.
EOT;
        $response = $this->generateContent($prompt);
        // Ekstrak JSON dari respons
        $jsonMatch = [];
        if (
            preg_match('/```json\s*(.*?)\s*```/s', $response, $jsonMatch) ||
            preg_match('/({[\s\S]*})/', $response, $jsonMatch)
        ) {
            try {
                $json = json_decode($jsonMatch[1], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $json;
                }
            } catch (\Exception $e) {
                Log::error('Error parsing JSON response: ' . $e->getMessage());
            }
        }

        // Fallback jika parsing JSON gagal
        return [
            'headlines' => $this->extractHeadlines($response)
        ];
    }

    private function extractHeadlines($text)
    {
        $headlines = [];

        // Coba ekstrak dari format HEADLINE_X: [text]
        preg_match_all('/HEADLINE_(\d+)\s*:\s*(.+)$/m', $text, $matches, PREG_SET_ORDER);
        if (!empty($matches)) {
            foreach ($matches as $match) {
                $headlines[] = [
                    'text' => trim($match[2]),
                    'style' => 'general'
                ];
            }
            return $headlines;
        }

        // Alternatif - cari headline dengan pola daftar
        preg_match_all('/[\d\-\*]\s*(.+)$/m', $text, $listMatches);
        if (!empty($listMatches[1])) {
            foreach ($listMatches[1] as $headline) {
                $headlines[] = [
                    'text' => trim($headline),
                    'style' => 'general'
                ];
            }
            return array_slice($headlines, 0, 5);
        }

        return $headlines;
    }
}