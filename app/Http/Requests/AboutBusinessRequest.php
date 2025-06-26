<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutBusinessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->business;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'full_story' => [
                'nullable',
                'string',
                'max:5000'
            ],
            'about_image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // 2MB
            ],
            'about_image_secondary' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // 2MB
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_story.string' => 'Cerita bisnis harus berupa teks.',
            'full_story.max' => 'Cerita bisnis maksimal 5000 karakter.',

            'about_image.image' => 'File harus berupa gambar.',
            'about_image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'about_image.max' => 'Ukuran gambar maksimal 2MB.',

            'about_image_secondary.image' => 'File gambar kedua harus berupa gambar.',
            'about_image_secondary.mimes' => 'Format gambar kedua harus JPG, JPEG, PNG, atau WEBP.',
            'about_image_secondary.max' => 'Ukuran gambar kedua maksimal 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'full_story' => 'cerita bisnis',
            'about_image' => 'gambar tentang bisnis',
            'about_image_secondary' => 'gambar kedua tentang bisnis'
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => 'Data yang dimasukkan tidak valid.',
                'errors' => $validator->errors()
            ], 422);

            throw new \Illuminate\Validation\ValidationException($validator, $response);
        }

        parent::failedValidation($validator);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean text fields
        if ($this->has('full_story')) {
            $cleanStory = $this->cleanHtmlContent($this->full_story);
            $this->merge([
                'full_story' => $cleanStory
            ]);
        }
    }

    /**
     * Clean HTML content from CKEditor.
     */
    private function cleanHtmlContent(?string $content): ?string
    {
        if (empty($content)) {
            return null;
        }

        // Trim whitespace
        $content = trim($content);

        // If content is empty after trimming, return null
        if (empty($content)) {
            return null;
        }

        // Remove empty paragraphs that CKEditor sometimes creates
        $content = preg_replace('/<p[^>]*>(\s|&nbsp;)*<\/p>/', '', $content);

        // Clean up multiple line breaks
        $content = preg_replace('/(<br\s*\/?>){3,}/', '<br><br>', $content);

        // Trim again after cleaning
        $content = trim($content);

        return empty($content) ? null : $content;
    }

    /**
     * Get the validated data with cleaned values.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Additional processing for full_story
        if (isset($validated['full_story'])) {
            $validated['full_story'] = $this->cleanHtmlContent($validated['full_story']);
        }

        return $key ? ($validated[$key] ?? $default) : $validated;
    }

    /**
     * Check if the request contains image upload.
     */
    public function hasImageUpload(): bool
    {
        return $this->hasFile('about_image') && $this->file('about_image')->isValid();
    }

    /**
     * Get image file info for processing.
     */
    public function getImageInfo(): ?array
    {
        if (!$this->hasImageUpload()) {
            return null;
        }

        $file = $this->file('about_image');

        return [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'is_valid' => $file->isValid()
        ];
    }

    /**
     * Check if story content is meaningful (not just empty HTML).
     */
    public function hasMeaningfulStory(): bool
    {
        $story = $this->input('full_story');

        if (empty($story)) {
            return false;
        }

        // Strip HTML tags and check if there's actual content
        $plainText = strip_tags($story);
        $plainText = trim(preg_replace('/\s+/', ' ', $plainText));

        return strlen($plainText) >= 10; // At least 10 characters of meaningful content
    }

    /**
     * Get story word count (excluding HTML).
     */
    public function getStoryWordCount(): int
    {
        $story = $this->input('full_story');

        if (empty($story)) {
            return 0;
        }

        $plainText = strip_tags($story);
        $words = preg_split('/\s+/', trim($plainText), -1, PREG_SPLIT_NO_EMPTY);

        return count($words);
    }

    /**
     * Get story character count (excluding HTML).
     */
    public function getStoryCharCount(): int
    {
        $story = $this->input('full_story');

        if (empty($story)) {
            return 0;
        }

        $plainText = strip_tags($story);
        return strlen(trim($plainText));
    }
    public function hasSecondaryImageUpload(): bool
    {
        return $this->hasFile('about_image_secondary') && $this->file('about_image_secondary')->isValid();
    }
}