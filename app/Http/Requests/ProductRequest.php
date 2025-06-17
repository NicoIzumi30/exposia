<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            'product_name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('products', 'product_name')
                    ->where('business_id', auth()->user()->business?->id)
                    ->ignore($this->route('product')?->id)
            ],
            'product_description' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'product_price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999'
            ],
            'product_image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', 
            ],
            'product_wa_link' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[\+]?[0-9\-\(\)\s]+$/'
            ],
            'is_pinned' => [
                'boolean'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'product_name.required' => 'Nama produk wajib diisi.',
            'product_name.string' => 'Nama produk harus berupa teks.',
            'product_name.max' => 'Nama produk maksimal 255 karakter.',
            'product_name.min' => 'Nama produk minimal 3 karakter.',
            'product_name.unique' => 'Nama produk sudah digunakan untuk bisnis ini.',

            'product_description.required' => 'Deskripsi produk wajib diisi.',
            'product_description.string' => 'Deskripsi produk harus berupa teks.',
            'product_description.min' => 'Deskripsi produk minimal 10 karakter.',
            'product_description.max' => 'Deskripsi produk maksimal 1000 karakter.',

            'product_price.required' => 'Harga produk wajib diisi.',
            'product_price.numeric' => 'Harga produk harus berupa angka.',
            'product_price.min' => 'Harga produk tidak boleh kurang dari 0.',
            'product_price.max' => 'Harga produk terlalu besar.',

            'product_image.image' => 'File harus berupa gambar.',
            'product_image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'product_image.max' => 'Ukuran gambar maksimal 2MB.',
            'product_image.dimensions' => 'Dimensi gambar minimal 100x100px dan maksimal 2000x2000px.',

            'product_wa_link.string' => 'Nomor WhatsApp harus berupa teks.',
            'product_wa_link.max' => 'Nomor WhatsApp maksimal 50 karakter.',
            'product_wa_link.regex' => 'Format nomor WhatsApp tidak valid. Gunakan angka, +, -, (, ), dan spasi.',

            'is_pinned.boolean' => 'Status pin harus berupa true atau false.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'product_name' => 'nama produk',
            'product_description' => 'deskripsi produk',
            'product_price' => 'harga produk',
            'product_image' => 'gambar produk',
            'product_wa_link' => 'nomor WhatsApp',
            'is_pinned' => 'status pin'
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
        // Clean and format phone number for WhatsApp
        if ($this->has('product_wa_link') && !empty($this->product_wa_link)) {
            $phone = preg_replace('/[^\d\+\-\(\)\s]/', '', $this->product_wa_link);
            $this->merge([
                'product_wa_link' => $phone
            ]);
        }

        // Clean text fields
        if ($this->has('product_name')) {
            $this->merge([
                'product_name' => trim($this->product_name)
            ]);
        }

        if ($this->has('product_description')) {
            $this->merge([
                'product_description' => trim($this->product_description)
            ]);
        }

        // Ensure price is numeric
        if ($this->has('product_price')) {
            $price = str_replace(['Rp', '.', ',', ' '], '', $this->product_price);
            $this->merge([
                'product_price' => is_numeric($price) ? (float) $price : $this->product_price
            ]);
        }

        // Handle checkbox for is_pinned
        $this->merge([
            'is_pinned' => $this->boolean('is_pinned')
        ]);
    }

    /**
     * Get the validation rules for bulk actions.
     */
    public static function bulkActionRules(): array
    {
        return [
            'action' => 'required|in:delete,pin,unpin',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id'
        ];
    }

    /**
     * Validate bulk action request.
     */
    public function validateBulkAction(): array
    {
        return $this->validate(self::bulkActionRules());
    }
}