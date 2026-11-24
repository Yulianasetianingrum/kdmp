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
        Schema::create('review_masukan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_reviewer', 100)->default('Dosen Pembimbing');
            $table->text('halaman_url');
            $table->string('halaman_path', 255)->index();
            $table->string('judul_halaman', 255)->nullable();
            $table->string('bagian_elemen', 255)->nullable();
            $table->decimal('posisi_x', 5, 2)->nullable();
            $table->decimal('posisi_y', 5, 2)->nullable();
            $table->string('kategori', 50)->default('umum')->index();
            $table->text('komentar');
            $table->longText('coretan_gambar')->nullable();
            $table->string('device_info', 150)->nullable();
            $table->enum('status', ['pending', 'dikerjakan', 'selesai'])->default('pending')->index();
            $table->text('tanggapan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_masukan');
    }
};
