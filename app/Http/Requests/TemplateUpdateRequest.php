<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User can only update their own business template
        return $this->user() && $this->user()->business;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'template_id' => [
                'nullable',
                'exists:templates,id'
            ],
            'primary_color' => [
                'nullable',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            ],
            'secondary_color' => [
                'nullable',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            ],
            'accent_color' => [
                'nullable',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            ],
            'highlight_color' => [
            'nullable',
            'string',
            'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
        ],
            'hero_image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
                'dimensions:min_width=800,min_height=400,max_width=3000,max_height=2000'
            ],
            'hero_image_secondary' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
                'dimensions:min_width=800,min_height=400,max_width=3000,max_height=2000'
            ],
            'sections' => [
                'nullable',
                'array'
            ],
            'sections.*' => [
                'in:navbar,hero,about,branches,produk,galeri,testimoni,footer'
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'template_id.exists' => 'Template yang dipilih tidak valid.',

            'primary_color.regex' => 'Format warna utama tidak valid. Gunakan format hex (#FFFFFF).',
            'secondary_color.regex' => 'Format warna sekunder tidak valid. Gunakan format hex (#FFFFFF).',
            'accent_color.regex' => 'Format warna aksen tidak valid. Gunakan format hex (#FFFFFF).',
            'highlight_color.regex' => 'Format warna highlight tidak valid. Gunakan format hex (#FFFFFF).',

            'hero_image.image' => 'File harus berupa gambar.',
            'hero_image.mimes' => 'Gambar harus berformat JPEG, PNG, JPG, GIF, atau WebP.',
            'hero_image.max' => 'Ukuran gambar maksimal 2MB.',
            'hero_image.dimensions' => 'Dimensi gambar minimal 800x400 pixel dan maksimal 3000x2000 pixel.',

            'hero_image_secondary.image' => 'File gambar kedua harus berupa gambar.',
            'hero_image_secondary.mimes' => 'Gambar kedua harus berformat JPEG, PNG, JPG, GIF, atau WebP.',
            'hero_image_secondary.max' => 'Ukuran gambar kedua maksimal 2MB.',
            'hero_image_secondary.dimensions' => 'Dimensi gambar kedua minimal 800x400 pixel dan maksimal 3000x2000 pixel.',

            'sections.array' => 'Data bagian tidak valid.',
            'sections.*.in' => 'Bagian yang dipilih tidak valid.'
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'template_id' => 'template',
            'primary_color' => 'warna utama',
            'secondary_color' => 'warna sekunder',
            'accent_color' => 'warna aksen',
            'highlight_color' => 'warna highlight',
            'hero_image' => 'gambar hero',
            'hero_image_secondary' => 'gambar hero kedua',
            'sections' => 'bagian website',
            'sections.*' => 'bagian'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and format color values
        if ($this->primary_color) {
            $this->merge(['primary_color' => $this->cleanColorValue($this->primary_color)]);
        }

        if ($this->secondary_color) {
            $this->merge(['secondary_color' => $this->cleanColorValue($this->secondary_color)]);
        }

        if ($this->accent_color) {
            $this->merge(['accent_color' => $this->cleanColorValue($this->accent_color)]);
        }
        if ($this->highlight_color) {
            $this->merge(['highlight_color' => $this->cleanColorValue($this->highlight_color)]);
        }
    }

    /**
     * Clean color value - ensure # prefix and proper format
     */
    private function cleanColorValue($color): string
    {
        // Remove whitespace and ensure # prefix
        $color = trim($color);
        if (!str_starts_with($color, '#')) {
            $color = '#' . $color;
        }
        return strtoupper($color);
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Log validation failures for debugging
        \Illuminate\Support\Facades\Log::info('Template update validation failed', [
            'user_id' => $this->user()?->id,
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['hero_image', '_token'])
        ]);

        parent::failedValidation($validator);
    }
}