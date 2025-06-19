<?php

// Create this migration file:
// php artisan make:migration add_style_variant_to_business_sections_table

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
        Schema::table('business_sections', function (Blueprint $table) {
            $table->string('style_variant', 10)->default('A')->after('section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_sections', function (Blueprint $table) {
            $table->dropColumn('style_variant');
        });
    }
};