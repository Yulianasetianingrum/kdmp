<?php

namespace Database\Seeders;

use App\Models\Master\ItemKomoditas;
use App\Models\Master\KategoriKomoditas;
use App\Models\Master\MasterKomoditas;
use App\Models\Pendataan\PendudukDesa;
use App\Models\Pendataan\Petani;
use App\Models\Tenant\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomoditasHierarkiSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriData = [
            [
                'kode' => 'PERTANIAN',
                'nama' => 'Pertanian',
                'deskripsi' => 'Komoditas tanaman pangan dan hortikultura',
                'masters' => [
                    [
                        'kode' => 'BERAS',
                        'nama' => 'Beras',
                        'items' => [
                            ['kode' => 'BERAS-MENTIK', 'nama' => 'Beras Mentik', 'satuan' => 'kg'],
                            ['kode' => 'BERAS-LELE', 'nama' => 'Beras Lele', 'satuan' => 'kg'],
                            ['kode' => 'BERAS-IR64', 'nama' => 'Beras IR64', 'satuan' => 'kg'],
                        ]
                    ],
                    [
                        'kode' => 'CABAI',
                        'nama' => 'Cabai',
                        'items' => [
                            ['kode' => 'CABAI-RAWIT', 'nama' => 'Cabai Rawit', 'satuan' => 'kg'],
                            ['kode' => 'CABAI-KERITING', 'nama' => 'Cabai Merah Keriting', 'satuan' => 'kg'],
                        ]
                    ],
                    [
                        'kode' => 'BAWANG',
                        'nama' => 'Bawang',
                        'items' => [
                            ['kode' => 'BAWANG-MERAH', 'nama' => 'Bawang Merah', 'satuan' => 'kg'],
                            ['kode' => 'BAWANG-PUTIH', 'nama' => 'Bawang Putih', 'satuan' => 'kg'],
                        ]
                    ],
                    [
                        'kode' => 'KEDELAI',
                        'nama' => 'Kedelai',
                        'items' => [
                            ['kode' => 'KEDELAI-KUNING', 'nama' => 'Kedelai Kuning', 'satuan' => 'kg'],
                        ]
                    ],
                ]
            ],
            [
                'kode' => 'PERKEBUNAN',
                'nama' => 'Perkebunan',
                'deskripsi' => 'Komoditas perkebunan rakyat',
                'masters' => [
                    [
                        'kode' => 'KOPI',
                        'nama' => 'Kopi',
                        'items' => [
                            ['kode' => 'KOPI-ROBUSTA', 'nama' => 'Kopi Robusta', 'satuan' => 'kg'],
                            ['kode' => 'KOPI-ARABIKA', 'nama' => 'Kopi Arabika', 'satuan' => 'kg'],
                        ]
                    ],
                    [
                        'kode' => 'GULA',
                        'nama' => 'Gula',
                        'items' => [
                            ['kode' => 'GULA-TEBU', 'nama' => 'Gula Tebu', 'satuan' => 'kg'],
                            ['kode' => 'GULA-KELAPA', 'nama' => 'Gula Kelapa / Semut', 'satuan' => 'kg'],
                        ]
                    ],
                ]
            ],
            [
                'kode' => 'PETERNAKAN',
                'nama' => 'Peternakan',
                'deskripsi' => 'Komoditas peternakan unggas dan ternak',
                'masters' => [
                    [
                        'kode' => 'AYAM',
                        'nama' => 'Ayam',
                        'items' => [
                            ['kode' => 'AYAM-BROILER', 'nama' => 'Ayam Broiler', 'satuan' => 'ekor'],
                            ['kode' => 'AYAM-KAMPUNG', 'nama' => 'Ayam Kampung', 'satuan' => 'ekor'],
                        ]
                    ],
                    [
                        'kode' => 'TELUR',
                        'nama' => 'Telur',
                        'items' => [
                            ['kode' => 'TELUR-RAS', 'nama' => 'Telur Ayam Ras', 'satuan' => 'kg'],
                            ['kode' => 'TELUR-KAMPUNG', 'nama' => 'Telur Ayam Kampung', 'satuan' => 'butir'],
                        ]
                    ],
                ]
            ],
            [
                'kode' => 'PERIKANAN',
                'nama' => 'Perikanan dan Kelautan',
                'deskripsi' => 'Hasil perikanan budidaya dan laut',
                'masters' => [
                    [
                        'kode' => 'GARAM',
                        'nama' => 'Garam',
                        'items' => [
                            ['kode' => 'GARAM-KONSUMSI', 'nama' => 'Garam Konsumsi Beryodium', 'satuan' => 'kg'],
                            ['kode' => 'GARAM-KROSOK', 'nama' => 'Garam Krosok Industri', 'satuan' => 'kg'],
                        ]
                    ],
                    [
                        'kode' => 'IKAN',
                        'nama' => 'Ikan',
                        'items' => [
                            ['kode' => 'IKAN-NILA', 'nama' => 'Ikan Nila', 'satuan' => 'kg'],
                            ['kode' => 'IKAN-LELE', 'nama' => 'Ikan Lele', 'satuan' => 'kg'],
                            ['kode' => 'IKAN-BANDENG', 'nama' => 'Ikan Bandeng', 'satuan' => 'kg'],
                        ]
                    ],
                ]
            ],
            [
                'kode' => 'EKRAF_PANGAN',
                'nama' => 'Ekonomi Kreatif Pangan',
                'deskripsi' => 'Produk olahan pangan berbasis kearifan lokal',
                'masters' => [
                    [
                        'kode' => 'OLAHAN_PANGAN',
                        'nama' => 'Olahan Pangan Desa',
                        'items' => [
                            ['kode' => 'KERIPIK-SINGKONG', 'nama' => 'Keripik Singkong Balado', 'satuan' => 'bungkus'],
                            ['kode' => 'TAPE-KETAN', 'nama' => 'Tape Ketan Hijau', 'satuan' => 'kemasan'],
                        ]
                    ]
                ]
            ],
            [
                'kode' => 'EKRAF_NON_PANGAN',
                'nama' => 'Ekonomi Kreatif Non-Pangan',
                'deskripsi' => 'Produk kerajinan, anyaman, dan kriya desa',
                'masters' => [
                    [
                        'kode' => 'KERAJINAN',
                        'nama' => 'Kerajinan Desa',
                        'items' => [
                            ['kode' => 'ANYAMAN-BAMBU', 'nama' => 'Anyaman Bambu / Besek', 'satuan' => 'buah'],
                            ['kode' => 'KRIYA-KUNINGAN', 'nama' => 'Kerajinan Kriya Logam', 'satuan' => 'buah'],
                        ]
                    ]
                ]
            ]
        ];

        foreach ($kategoriData as $kat) {
            $kategori = KategoriKomoditas::updateOrCreate(
                ['kode' => $kat['kode']],
                ['nama' => $kat['nama'], 'deskripsi' => $kat['deskripsi'], 'is_active' => true]
            );

            foreach ($kat['masters'] as $m) {
                $master = MasterKomoditas::updateOrCreate(
                    ['id_kategori' => $kategori->id, 'kode' => $m['kode']],
                    ['nama' => $m['nama'], 'is_active' => true]
                );

                foreach ($m['items'] as $item) {
                    ItemKomoditas::updateOrCreate(
                        ['id_master_komoditas' => $master->id, 'kode' => $item['kode']],
                        ['nama' => $item['nama'], 'satuan_standar' => $item['satuan'], 'is_active' => true]
                    );
                }
            }
        }

        // Ambil desa sampel dari wilayah
        $desaSamples = Wilayah::where('tingkat', 'desa')->limit(3)->get();
        if ($desaSamples->count() > 0) {
            $desa1 = $desaSamples->first();
            $kec1 = $desa1->parent?->nama ?? 'Batangan';
            $kab1 = $desa1->parent?->parent?->nama ?? 'Pati';

            // Seed Petani
            $petani1 = Petani::updateOrCreate(
                ['nama_lengkap' => 'Sutrisno Hartono', 'id_wilayah' => $desa1->id],
                [
                    'nomor_hp' => '081234567890',
                    'alamat' => 'Dusun Krajan RT 02 RW 01',
                    'rt' => '02',
                    'rw' => '01',
                    'desa' => $desa1->nama,
                    'kecamatan' => $kec1,
                    'kabupaten' => $kab1,
                ]
            );

            $petani2 = Petani::updateOrCreate(
                ['nama_lengkap' => 'Bambang Triyono', 'id_wilayah' => $desa1->id],
                [
                    'nomor_hp' => '082198765432',
                    'alamat' => 'Dusun Sawah Baru RT 04 RW 02',
                    'rt' => '04',
                    'rw' => '02',
                    'desa' => $desa1->nama,
                    'kecamatan' => $kec1,
                    'kabupaten' => $kab1,
                ]
            );

            // Relasi Petani ke Item Komoditas
            $itemBerasMentik = ItemKomoditas::where('kode', 'BERAS-MENTIK')->first();
            $itemCabaiRawit = ItemKomoditas::where('kode', 'CABAI-RAWIT')->first();
            $itemBawangMerah = ItemKomoditas::where('kode', 'BAWANG-MERAH')->first();

            if ($itemBerasMentik) {
                $petani1->items()->syncWithoutDetaching([
                    $itemBerasMentik->id => [
                        'luas_lahan_ha' => 1.5,
                        'estimasi_volume_panen' => 6500,
                        'satuan' => 'kg',
                        'periode_panen' => 'Musiman (4 bulan)',
                        'keterangan' => 'Sawah irigasi teknis'
                    ]
                ]);
            }

            if ($itemCabaiRawit) {
                $petani1->items()->syncWithoutDetaching([
                    $itemCabaiRawit->id => [
                        'luas_lahan_ha' => 0.5,
                        'estimasi_volume_panen' => 1200,
                        'satuan' => 'kg',
                        'periode_panen' => 'Musiman (3 bulan)',
                        'keterangan' => 'Tumpang sari'
                    ]
                ]);

                $petani2->items()->syncWithoutDetaching([
                    $itemCabaiRawit->id => [
                        'luas_lahan_ha' => 0.8,
                        'estimasi_volume_panen' => 2000,
                        'satuan' => 'kg',
                        'periode_panen' => 'Musiman (3 bulan)',
                        'keterangan' => 'Lahan tegalan'
                    ]
                ]);
            }

            if ($itemBawangMerah) {
                $petani2->items()->syncWithoutDetaching([
                    $itemBawangMerah->id => [
                        'luas_lahan_ha' => 1.0,
                        'estimasi_volume_panen' => 3500,
                        'satuan' => 'kg',
                        'periode_panen' => 'Musiman (3 bulan)',
                        'keterangan' => 'Bawang merah varietas lokal'
                    ]
                ]);
            }

            // Seed Penduduk Desa
            PendudukDesa::updateOrCreate(
                ['id_wilayah' => $desa1->id, 'tahun' => date('Y')],
                [
                    'balita' => 120,
                    'anak' => 240,
                    'remaja' => 380,
                    'dewasa' => 1150,
                    'lansia' => 210,
                    'sumber_data' => 'Input Admin',
                    'keterangan' => 'Data sensus potensi desa tahun berjalan',
                ]
            );
        }
    }
}

