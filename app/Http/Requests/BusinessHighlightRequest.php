<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessHighlightRequest extends FormRequest
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
            'icon' => [
                'required',
                'string',
                'max:100'
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'icon.required' => 'Icon wajib dipilih.',
            'icon.string' => 'Icon harus berupa teks.',
            'icon.max' => 'Icon maksimal 100 karakter.',

            'title.required' => 'Judul highlight wajib diisi.',
            'title.string' => 'Judul highlight harus berupa teks.',
            'title.max' => 'Judul highlight maksimal 255 karakter.',
            'title.min' => 'Judul highlight minimal 2 karakter.',

            'description.required' => 'Deskripsi highlight wajib diisi.',
            'description.string' => 'Deskripsi highlight harus berupa teks.',
            'description.min' => 'Deskripsi highlight minimal 10 karakter.',
            'description.max' => 'Deskripsi highlight maksimal 500 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'icon' => 'icon',
            'title' => 'judul highlight',
            'description' => 'deskripsi highlight'
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
        if ($this->has('title')) {
            $this->merge([
                'title' => trim($this->title)
            ]);
        }

        if ($this->has('description')) {
            $this->merge([
                'description' => trim($this->description)
            ]);
        }

        // Ensure icon has proper Font Awesome format
        if ($this->has('icon') && !empty($this->icon)) {
            $icon = trim($this->icon);
            
            // Add 'fas fa-' prefix if not already present
            if (!str_starts_with($icon, 'fa')) {
                $icon = 'fas fa-' . $icon;
            }
            
            $this->merge(['icon' => $icon]);
        }
    }

    /**
     * Get the validated data with cleaned values.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Additional validation for icon format
        if (isset($validated['icon'])) {
            $validated['icon'] = $this->validateIconFormat($validated['icon']);
        }
        
        return $key ? ($validated[$key] ?? $default) : $validated;
    }

    /**
     * Validate and format icon string.
     */
    private function validateIconFormat(string $icon): string
    {
        // List of valid Font Awesome prefixes
        $validPrefixes = ['fas', 'far', 'fab', 'fal', 'fad'];
        
        $iconParts = explode(' ', trim($icon));
        
        // If icon doesn't have prefix, add 'fas'
        if (count($iconParts) === 1) {
            return 'fas fa-' . $iconParts[0];
        }
        
        // If icon has prefix, validate it
        if (count($iconParts) >= 2) {
            $prefix = $iconParts[0];
            $iconName = $iconParts[1];
            
            // Ensure prefix is valid
            if (!in_array($prefix, $validPrefixes)) {
                $prefix = 'fas';
            }
            
            // Ensure icon name starts with 'fa-'
            if (!str_starts_with($iconName, 'fa-')) {
                $iconName = 'fa-' . ltrim($iconName, 'fa-');
            }
            
            return $prefix . ' ' . $iconName;
        }
        
        return 'fas fa-star'; // Default fallback
    }
}