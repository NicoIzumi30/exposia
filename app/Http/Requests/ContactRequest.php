<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\BusinessContact;

class ContactRequest extends FormRequest
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
        $availableTypes = array_keys(BusinessContact::getAvailableTypes());
        
        return [
            'contact_type' => [
                'required',
                'string',
                'in:' . implode(',', $availableTypes)
            ],
            'contact_title' => [
                'required',
                'string',
                'max:100'
            ],
            'contact_description' => [
                'nullable',
                'string',
                'max:200'
            ],
            'contact_value' => [
                'required',
                'string',
                'max:255'
            ],
            'contact_icon' => [
                'nullable',
                'string',
                'max:100'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'contact_type.required' => 'Jenis kontak harus dipilih.',
            'contact_type.in' => 'Jenis kontak tidak valid.',
            'contact_title.required' => 'Judul kontak harus diisi.',
            'contact_title.max' => 'Judul kontak maksimal 100 karakter.',
            'contact_description.max' => 'Deskripsi kontak maksimal 200 karakter.',
            'contact_value.required' => 'Nilai kontak harus diisi.',
            'contact_value.max' => 'Nilai kontak maksimal 255 karakter.',
            'contact_icon.max' => 'Ikon kontak maksimal 100 karakter.',
        ];
    }
}