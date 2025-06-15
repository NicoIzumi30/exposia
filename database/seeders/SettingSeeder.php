<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Platform Name
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'platform_name',
            'value' => 'UMKM Website Builder',
            'group' => 'branding',
        ]);

        // Platform Logo
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'platform_logo',
            'value' => 'settings/logo.png',
            'group' => 'branding',
        ]);

        // Platform Description
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'platform_description',
            'value' => 'Platform pembuatan website untuk UMKM dengan fitur lengkap dan mudah digunakan.',
            'group' => 'branding',
        ]);

        // WhatsApp CS
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'whatsapp_cs_link',
            'value' => 'https://wa.me/628123456789',
            'group' => 'support',
        ]);

        // Default Template
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'default_template',
            'value' => '1', // ID template Modern
            'group' => 'template',
        ]);

        // Terms and Conditions
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'terms_conditions',
            'value' => '<h1>Syarat dan Ketentuan</h1><p>Ini adalah syarat dan ketentuan penggunaan platform website builder UMKM.</p>',
            'group' => 'legal',
        ]);

        // Privacy Policy
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'privacy_policy',
            'value' => '<h1>Kebijakan Privasi</h1><p>Ini adalah kebijakan privasi platform website builder UMKM.</p>',
            'group' => 'legal',
        ]);

        // FAQ
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'faq',
            'value' => json_encode([
                [
                    'question' => 'Bagaimana cara membuat website baru?',
                    'answer' => 'Anda dapat membuat website baru dengan mengklik tombol "Buat Website" pada dashboard pengguna.'
                ],
                [
                    'question' => 'Apakah saya bisa menggunakan domain sendiri?',
                    'answer' => 'Saat ini belum tersedia fitur untuk menggunakan domain sendiri. Website Anda akan tersedia di subdomain kami.'
                ],
                [
                    'question' => 'Berapa jumlah produk yang bisa saya tampilkan?',
                    'answer' => 'Anda dapat menampilkan hingga 50 produk dalam website Anda.'
                ],
                [
                    'question' => 'Bagaimana cara mengganti template website?',
                    'answer' => 'Anda dapat mengganti template website melalui menu "Template & Tampilan" pada dashboard pengguna.'
                ],
                [
                    'question' => 'Apakah website yang saya buat mobile-friendly?',
                    'answer' => 'Ya, semua template kami dirancang responsive dan mobile-friendly.'
                ]
            ]),
            'group' => 'support',
        ]);

        // Max Products
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'max_products',
            'value' => '50',
            'group' => 'limits',
        ]);

        // Max Gallery Images
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'max_gallery_images',
            'value' => '20',
            'group' => 'limits',
        ]);

        // Max Testimonials
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'max_testimonials',
            'value' => '10',
            'group' => 'limits',
        ]);

        // Max Highlights
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'max_highlights',
            'value' => '6',
            'group' => 'limits',
        ]);

        // Max Branches
        Setting::create([
            'id' => Str::uuid(),
            'key' => 'max_branches',
            'value' => '5',
            'group' => 'limits',
        ]);
    }
}