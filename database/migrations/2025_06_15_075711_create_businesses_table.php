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
        Schema::create('businesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('main_address')->nullable();
            $table->text('main_operational_hours')->nullable();
            $table->string('google_maps_link')->nullable();
            $table->string('logo_url')->nullable();
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->text('full_story')->nullable();
            $table->string('hero_image_url')->nullable();
            $table->string('about_image')->nullable();
            $table->string('public_url')->nullable()->unique();
            $table->boolean('publish_status')->default(false);
            $table->string('qr_code')->nullable();
            $table->integer('progress_completion')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
