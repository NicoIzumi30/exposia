<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
            'testimonial_name' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'testimonial_content' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'testimonial_position' => [
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'testimonial_name.required' => 'Nama pemberi testimoni wajib diisi.',
            'testimonial_name.string' => 'Nama pemberi testimoni harus berupa teks.',
            'testimonial_name.max' => 'Nama pemberi testimoni maksimal 255 karakter.',
            'testimonial_name.min' => 'Nama pemberi testimoni minimal 2 karakter.',

            'testimonial_content.required' => 'Isi testimoni wajib diisi.',
            'testimonial_content.string' => 'Isi testimoni harus berupa teks.',
            'testimonial_content.min' => 'Isi testimoni minimal 10 karakter.',
            'testimonial_content.max' => 'Isi testimoni maksimal 1000 karakter.',

            'testimonial_position.string' => 'Posisi/jabatan harus berupa teks.',
            'testimonial_position.max' => 'Posisi/jabatan maksimal 255 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'testimonial_name' => 'nama pemberi testimoni',
            'testimonial_content' => 'isi testimoni',
            'testimonial_position' => 'posisi/jabatan'
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
        if ($this->has('testimonial_name')) {
            $this->merge([
                'testimonial_name' => trim($this->testimonial_name)
            ]);
        }

        if ($this->has('testimonial_content')) {
            $this->merge([
                'testimonial_content' => trim($this->testimonial_content)
            ]);
        }

        if ($this->has('testimonial_position')) {
            $this->merge([
                'testimonial_position' => trim($this->testimonial_position)
            ]);
        }
    }
}