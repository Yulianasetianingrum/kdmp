<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Tenant\Entitas;
use App\Models\Tenant\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DataAwalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Super Admin
        $superAdmin = Pengguna::updateOrCreate(
            ['email' => 'superadmin@ngobar.id'],
            [
                'id_entitas' => null,
                'nama' => 'Super Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $idPeranSuperAdmin = Peran::where('kode', 'super_admin')->value('id_peran');
        if ($idPeranSuperAdmin) {
            DB::table('pengguna_peran')->updateOrInsert([
                'id_pengguna' => $superAdmin->id,
                'id_peran' => $idPeranSuperAdmin,
            ]);
        }

        // 2. Hierarki Wilayah Sampel (Kabupaten Pati -> Kecamatan -> Desa)
        $kabPati = Wilayah::firstOrCreate(
            ['nama' => 'Pati', 'tingkat' => 'kab'],
            ['parent_id' => null]
        );

        $kecBatangan = Wilayah::firstOrCreate(
            ['nama' => 'Batangan', 'tingkat' => 'kec', 'parent_id' => $kabPati->id]
        );

        $kecJuwana = Wilayah::firstOrCreate(
            ['nama' => 'Juwana', 'tingkat' => 'kec', 'parent_id' => $kabPati->id]
        );

        $desaContoh = [
            ['kode' => 'KDMP-A', 'nama' => 'Koperasi Desa Merah Putih Mekar Jaya', 'wilayah' => 'Desa Mekar Jaya', 'kec' => $kecBatangan->id],
            ['kode' => 'KDMP-B', 'nama' => 'Koperasi Desa Merah Putih Sukamaju', 'wilayah' => 'Desa Sukamaju', 'kec' => $kecJuwana->id],
        ];

        $idPeranManajer = Peran::where('kode', 'manajer')->value('id_peran');

        foreach ($desaContoh as $desa) {
            $wilayah = Wilayah::firstOrCreate(
                ['nama' => $desa['wilayah']],
                ['tingkat' => 'desa', 'parent_id' => $desa['kec']]
            );

            $entitas = Entitas::updateOrCreate(
                ['kode_entitas' => $desa['kode']],
                [
                    'nama_entitas' => $desa['nama'],
                    'id_wilayah' => $wilayah->id,
                    'tahun_buku_awal' => 2026,
                    'is_active' => true,
                ]
            );

            $userSlug = strtolower($desa['kode']);
            $manajer = Pengguna::updateOrCreate(
                ['email' => "manajer@{$userSlug}.test"],
                [
                    'id_entitas' => $entitas->id_entitas,
                    'nama' => "Manajer {$desa['wilayah']}",
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );

            if ($idPeranManajer) {
                DB::table('pengguna_peran')->updateOrInsert([
                    'id_pengguna' => $manajer->id,
                    'id_peran' => $idPeranManajer,
                ]);
            }
        }
    }
}
