<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
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
            'branch_name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                Rule::unique('branches', 'branch_name')
                    ->where('business_id', auth()->user()->business?->id)
                    ->ignore($this->route('branch')?->id)
            ],
            'branch_address' => [
                'required',
                'string',
                'max:500',
                'min:10'
            ],
            'branch_operational_hours' => [
                'required',
                'string',
                'max:255',
                'min:5'
            ],
            'branch_google_maps_link' => [
                'nullable',
                'url',
                'max:500',
            ],
            'branch_phone' => [
                'nullable',
                'string',
                'max:20',
                'min:10',
                'regex:/^[\+]?[0-9\-\(\)\s]+$/'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'branch_name.required' => 'Nama cabang wajib diisi.',
            'branch_name.string' => 'Nama cabang harus berupa teks.',
            'branch_name.max' => 'Nama cabang maksimal 255 karakter.',
            'branch_name.min' => 'Nama cabang minimal 2 karakter.',
            'branch_name.unique' => 'Nama cabang sudah digunakan untuk bisnis ini.',

            'branch_address.required' => 'Alamat cabang wajib diisi.',
            'branch_address.string' => 'Alamat cabang harus berupa teks.',
            'branch_address.max' => 'Alamat cabang maksimal 500 karakter.',
            'branch_address.min' => 'Alamat cabang minimal 10 karakter.',

            'branch_operational_hours.required' => 'Jam operasional wajib diisi.',
            'branch_operational_hours.string' => 'Jam operasional harus berupa teks.',
            'branch_operational_hours.max' => 'Jam operasional maksimal 255 karakter.',
            'branch_operational_hours.min' => 'Jam operasional minimal 5 karakter.',

            'branch_google_maps_link.url' => 'Link Google Maps harus berupa URL yang valid.',
            'branch_google_maps_link.max' => 'Link Google Maps maksimal 500 karakter.',
            'branch_google_maps_link.regex' => 'Link harus merupakan URL Google Maps yang valid.',

            'branch_phone.string' => 'Nomor telepon harus berupa teks.',
            'branch_phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'branch_phone.min' => 'Nomor telepon minimal 10 karakter.',
            'branch_phone.regex' => 'Format nomor telepon tidak valid. Gunakan angka, +, -, (, ), dan spasi.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'branch_name' => 'nama cabang',
            'branch_address' => 'alamat cabang',
            'branch_operational_hours' => 'jam operasional',
            'branch_google_maps_link' => 'link Google Maps',
            'branch_phone' => 'nomor telepon'
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
        // Clean and format phone number
        if ($this->has('branch_phone') && !empty($this->branch_phone)) {
            $phone = preg_replace('/[^\d\+\-\(\)\s]/', '', $this->branch_phone);
            $this->merge([
                'branch_phone' => $phone
            ]);
        }

        // Clean Google Maps URL
        if ($this->has('branch_google_maps_link') && !empty($this->branch_google_maps_link)) {
            $mapsUrl = trim($this->branch_google_maps_link);
            $this->merge([
                'branch_google_maps_link' => $mapsUrl
            ]);
        }

        // Clean text fields
        if ($this->has('branch_name')) {
            $this->merge([
                'branch_name' => trim($this->branch_name)
            ]);
        }

        if ($this->has('branch_address')) {
            $this->merge([
                'branch_address' => trim($this->branch_address)
            ]);
        }

        if ($this->has('branch_operational_hours')) {
            $this->merge([
                'branch_operational_hours' => trim($this->branch_operational_hours)
            ]);
        }
    }
}