<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id')->constrained()->onDelete('cascade');
            $table->string('contact_type'); // instagram, shopee, maps, whatsapp, dll
            $table->string('contact_title'); // "Kunjungi Shopee"
            $table->string('contact_description')->nullable(); // "Lihat produk lain"
            $table->string('contact_value'); // URL/nomor telepon/username
            $table->string('contact_icon')->nullable(); // ikon untuk kontak
            $table->integer('order')->default(0); // urutan tampilan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_contacts');
    }
};