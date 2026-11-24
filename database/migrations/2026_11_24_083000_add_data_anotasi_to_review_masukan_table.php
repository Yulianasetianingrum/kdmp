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
            $table->longText('data_anotasi')->nullable()->after('coretan_gambar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_masukan', function (Blueprint $table) {
            $table->dropColumn('data_anotasi');
        });
    }
};
