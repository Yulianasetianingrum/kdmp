<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $pertanian = DB::table('kategori_komoditas')->where('kode', 'PERTANIAN')->first();
        $perkebunan = DB::table('kategori_komoditas')->where('kode', 'PERKEBUNAN')->first();

        if ($pertanian) {
            DB::table('kategori_komoditas')
                ->where('id', $pertanian->id)
                ->update([
                    'deskripsi' => 'Komoditas tanaman pangan, hortikultura, dan perkebunan'
                ]);

            if ($perkebunan) {
                // Alihkan master_komoditas dari Perkebunan ke Pertanian
                DB::table('master_komoditas')
                    ->where('id_kategori', $perkebunan->id)
                    ->update(['id_kategori' => $pertanian->id]);

                // Alihkan pertanyaan jika ada yang mengarah ke perkebunan
                if (Schema::hasTable('pertanyaan') && Schema::hasColumn('pertanyaan', 'id_kategori_komoditas')) {
                    DB::table('pertanyaan')
                        ->where('id_kategori_komoditas', $perkebunan->id)
                        ->update(['id_kategori_komoditas' => $pertanian->id]);
                }

                // Hapus kategori Perkebunan
                DB::table('kategori_komoditas')
                    ->where('id', $perkebunan->id)
                    ->delete();
            }
        }

        // Update di tabel komoditas jika ada kategori 'perkebunan' atau 'Perkebunan'
        if (Schema::hasTable('komoditas') && Schema::hasColumn('komoditas', 'kategori')) {
            DB::table('komoditas')
                ->whereRaw('LOWER(kategori) = ?', ['perkebunan'])
                ->update(['kategori' => 'pertanian']);
        }

        // Update di tabel produksi_produsen jika ada
        if (Schema::hasTable('produksi_produsen') && Schema::hasColumn('produksi_produsen', 'sub_kategori')) {
            DB::table('produksi_produsen')
                ->whereRaw('LOWER(sub_kategori) = ?', ['perkebunan'])
                ->update(['sub_kategori' => 'Pertanian']);
        }
    }

    public function down(): void
    {
        $perkebunanId = DB::table('kategori_komoditas')->insertGetId([
            'kode' => 'PERKEBUNAN',
            'nama' => 'Perkebunan',
            'deskripsi' => 'Komoditas perkebunan rakyat',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('master_komoditas')
            ->whereIn('kode', ['KOPI', 'GULA'])
            ->update(['id_kategori' => $perkebunanId]);
    }
};
