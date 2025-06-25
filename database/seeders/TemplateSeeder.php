<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Template Modern
        Template::create([
            'id' => Str::uuid(),
            'name' => 'Modern',
            'description' => 'Template modern dengan desain minimalis dan tampilan yang bersih. Cocok untuk berbagai jenis UMKM.',
            'thumbnail' => 'img/templates/modern.png',
            'preview_url' => 'https://exposia.com/preview/modern',
            'is_active' => true,
        ]);

        // Template Classic
        Template::create([
            'id' => Str::uuid(),
            'name' => 'Classic',
            'description' => 'Template klasik dengan tampilan yang elegan dan profesional. Cocok untuk UMKM yang ingin menonjolkan kualitas dan kesan tradisional.',
            'thumbnail' => 'img/templates/classic.png',
            'preview_url' => 'https://exposia.com/preview/classic',
            'is_active' => true,
        ]);

        // Template Tech
        Template::create([
            'id' => Str::uuid(),
            'name' => 'Teknologi',
            'description' => 'Template yang dirancang untuk bisnis teknologi dan teknologi. Fokus pada tampilan galeri produk yang detail dan menarik.',
            'thumbnail' => 'img/templates/tech.png',
            'preview_url' => 'https://exposia.com/preview/tech',
            'is_active' => true,
        ]);
    }
}
