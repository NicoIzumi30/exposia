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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('report_code')->unique()->nullable()->after('business_id');
            $table->text('website_url')->nullable()->after('report_content');
            $table->string('evidence_image')->nullable()->after('website_url');
            
            if (Schema::hasColumn('reports', 'handled_by')) {
                $table->dropForeign(['handled_by']);
                $table->dropColumn('handled_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignUuid('handled_by')->nullable()->after('admin_notes');
            $table->foreign('handled_by')->references('id')->on('users')->nullOnDelete();

            $table->dropUnique(['report_code']);
            $table->dropColumn(['report_code', 'website_url', 'evidence_image']);
        });
    }
};
