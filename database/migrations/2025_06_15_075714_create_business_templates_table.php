<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('business_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('template_id')->constrained()->onDelete('cascade');
            $table->json('color_palette')->nullable();
            $table->json('hero_section')->nullable();
            $table->json('about_section')->nullable();
            $table->json('products_section')->nullable();
            $table->json('gallery_section')->nullable();
            $table->json('testimonial_section')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_templates');
    }
};
