<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->business !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Only for image upload (POST method)
        return [
            'gallery_images' => [
                'required',
                'array',
                'min:1',
                'max:8'
            ],
            'gallery_images.*' => [
                'image',
                'max:5120', // 5MB max per image
            ],
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
            'gallery_images.required' => 'Silakan pilih gambar untuk diupload.',
            'gallery_images.array' => 'Format data gambar tidak valid.',
            'gallery_images.min' => 'Minimal pilih 1 gambar untuk diupload.',
            'gallery_images.max' => 'Maksimal 8 gambar dapat diupload sekaligus.',

            'gallery_images.*.image' => 'Semua file yang diupload harus berupa gambar.',
            'gallery_images.*.max' => 'Ukuran setiap gambar maksimal 5MB.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return \Illuminate\Validation\Validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            $business = $user->business;

            if (!$business) {
                $validator->errors()->add('business', 'Profil bisnis tidak ditemukan. Silakan lengkapi data bisnis terlebih dahulu.');
                return;
            }

            // Check total gallery images limit (TOTAL MAX 8)
            if ($this->has('gallery_images')) {
                $currentCount = $business->galleries()->count();
                $newImagesCount = count($this->file('gallery_images', []));
                $maxGalleryImages = 8; // Total maksimal 8 foto

                if (($currentCount + $newImagesCount) > $maxGalleryImages) {
                    $remaining = $maxGalleryImages - $currentCount;
                    $validator->errors()->add(
                        'gallery_images',
                        "Anda dapat menambahkan maksimal {$remaining} gambar lagi. Total maksimal: {$maxGalleryImages} gambar."
                    );
                }
            }
        });
    }
}