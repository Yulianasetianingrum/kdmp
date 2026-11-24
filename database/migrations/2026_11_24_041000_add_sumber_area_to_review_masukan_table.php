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
        Schema::table('review_masukan', function (Blueprint $table) {
            $table->string('sumber_area', 50)->default('dokumen_utama')->after('bagian_elemen')->index();
            $table->string('detail_sumber', 255)->nullable()->after('sumber_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_masukan', function (Blueprint $table) {
            $table->dropIndex(['sumber_area']);
            $table->dropColumn(['sumber_area', 'detail_sumber']);
        });
    }
};
