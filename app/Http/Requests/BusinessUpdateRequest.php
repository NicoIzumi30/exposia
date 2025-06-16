<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User can only update their own business
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
            'business_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('businesses', 'business_name')->ignore($this->user()->business->id)
            ],
            'main_address' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            'main_operational_hours' => [
                'required',
                'string',
                'min:5',
                'max:255'
            ],
            'google_maps_link' => [
                'nullable',
                'url',
                'max:500',
                'regex:/^https?:\/\/(www\.)?(google\.com\/maps|maps\.google\.com|goo\.gl)/i'
            ],
            'short_description' => [
                'required',
                'string',
                'min:20',
                'max:160'
            ],
            'full_description' => [
                'required',
                'string',
                'min:50',
                'max:5000'
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            'public_url' => [
                'nullable',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('businesses', 'public_url')->ignore($this->user()->business->id)
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
            'business_name.required' => 'Nama usaha harus diisi.',
            'business_name.min' => 'Nama usaha minimal 2 karakter.',
            'business_name.max' => 'Nama usaha maksimal 255 karakter.',
            'business_name.unique' => 'Nama usaha sudah digunakan.',
            
            'main_address.required' => 'Alamat utama harus diisi.',
            'main_address.min' => 'Alamat utama minimal 10 karakter.',
            'main_address.max' => 'Alamat utama maksimal 500 karakter.',
            
            'main_operational_hours.required' => 'Jam operasional harus diisi.',
            'main_operational_hours.min' => 'Jam operasional minimal 5 karakter.',
            'main_operational_hours.max' => 'Jam operasional maksimal 255 karakter.',
            
            'google_maps_link.url' => 'Link Google Maps harus berupa URL yang valid.',
            'google_maps_link.regex' => 'Link harus berupa URL Google Maps yang valid.',
            'google_maps_link.max' => 'Link Google Maps terlalu panjang.',
            
            'short_description.required' => 'Deskripsi singkat harus diisi.',
            'short_description.min' => 'Deskripsi singkat minimal 20 karakter.',
            'short_description.max' => 'Deskripsi singkat maksimal 160 karakter.',
            
            'full_description.required' => 'Deskripsi lengkap harus diisi.',
            'full_description.min' => 'Deskripsi lengkap minimal 50 karakter.',
            'full_description.max' => 'Deskripsi lengkap terlalu panjang.',
            
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPEG, JPG, PNG, atau WebP.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'logo.dimensions' => 'Dimensi logo minimal 100x100 pixel dan maksimal 2000x2000 pixel.',
            
            'public_url.min' => 'URL website minimal 3 karakter.',
            'public_url.max' => 'URL website maksimal 100 karakter.',
            'public_url.regex' => 'URL website hanya boleh mengandung huruf kecil, angka, dan tanda hubung.',
            'public_url.unique' => 'URL website sudah digunakan.'
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
            'business_name' => 'nama usaha',
            'main_address' => 'alamat utama',
            'main_operational_hours' => 'jam operasional',
            'google_maps_link' => 'link Google Maps',
            'short_description' => 'deskripsi singkat',
            'full_description' => 'deskripsi lengkap',
            'logo' => 'logo',
            'public_url' => 'URL website'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and format the data before validation
        $this->merge([
            'business_name' => trim($this->business_name),
            'main_address' => trim($this->main_address),
            'main_operational_hours' => trim($this->main_operational_hours),
            'short_description' => trim($this->short_description),
            'full_description' => trim($this->full_description),
            'google_maps_link' => $this->google_maps_link ? trim($this->google_maps_link) : null,
        ]);
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Log validation failures for debugging
        \Illuminate\Support\Facades\Log::info('Business update validation failed', [
            'user_id' => $this->user()?->id,
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['logo', '_token'])
        ]);

        parent::failedValidation($validator);
    }
}