<?php
return [
    'api_key' => env('GEMINI_API_KEY', ''),
    'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'), 
    'api_url' => 'https://generativelanguage.googleapis.com/v1beta/models/', 
    'temperature' => env('GEMINI_TEMPERATURE', 0.4),
    'max_output_tokens' => env('GEMINI_MAX_OUTPUT_TOKENS', 1024),
    'top_p' => env('GEMINI_TOP_P', 0.8),
    'top_k' => env('GEMINI_TOP_K', 40),
];