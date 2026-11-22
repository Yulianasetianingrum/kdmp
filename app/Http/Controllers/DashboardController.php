<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesEntitasAktif;
use App\Models\Master\ItemKomoditas;
use App\Models\Master\KategoriKomoditas;
use App\Models\Master\MasterKomoditas;
use App\Models\Pendataan\PendudukDesa;
use App\Models\Pendataan\Petani;
use App\Models\Pendataan\PotensiProduksiPetani;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use ResolvesEntitasAktif;

    public function index(): View
    {
        $desaAktif = $this->desaAktif();

        // 1. Total Penduduk & 5 Kelompok Umur untuk Desa Ini
        $pendudukAggQuery = PendudukDesa::selectRaw('
            SUM(balita) as total_balita,
            SUM(anak) as total_anak,
            SUM(remaja) as total_remaja,
            SUM(dewasa) as total_dewasa,
            SUM(lansia) as total_lansia,
            SUM(total_penduduk) as total_semua
        ');

        if ($desaAktif) {
            $pendudukAggQuery->where('id_wilayah', $desaAktif->id);
        }

        $pendudukAgg = $pendudukAggQuery->first();

        $demografi = [
            'total_penduduk' => (int)($pendudukAgg->total_semua ?? 0),
            'balita' => (int)($pendudukAgg->total_balita ?? 0),
            'anak' => (int)($pendudukAgg->total_anak ?? 0),
            'remaja' => (int)($pendudukAgg->total_remaja ?? 0),
            'dewasa' => (int)($pendudukAgg->total_dewasa ?? 0),
            'lansia' => (int)($pendudukAgg->total_lansia ?? 0),
        ];

        // 2. Metrik Komoditas & Petani Desa Ini
        $jumlahPetani = $desaAktif ? Petani::where('id_wilayah', $desaAktif->id)->count() : Petani::count();
        $jumlahKategori = KategoriKomoditas::where('is_active', true)->count();
        $jumlahMaster = MasterKomoditas::where('is_active', true)->count();
        $jumlahItem = ItemKomoditas::where('is_active', true)->count();

        // 3. Komoditas yang Dihasilkan di Desa Ini
        $komoditasDesaQuery = PotensiProduksiPetani::join('petani', 'potensi_produksi_petani.petani_id', '=', 'petani.id')
            ->join('item_komoditas', 'potensi_produksi_petani.item_komoditas_id', '=', 'item_komoditas.id')
            ->join('master_komoditas', 'item_komoditas.id_master_komoditas', '=', 'master_komoditas.id')
            ->join('kategori_komoditas', 'master_komoditas.id_kategori', '=', 'kategori_komoditas.id');

        if ($desaAktif) {
            $komoditasDesaQuery->where('petani.id_wilayah', $desaAktif->id);
        }

        $daftarKomoditasUnggulan = $komoditasDesaQuery->select(
            'item_komoditas.nama as nama_item',
            'master_komoditas.nama as nama_master',
            'kategori_komoditas.nama as nama_kategori',
            DB::raw('COUNT(DISTINCT petani.id) as total_petani'),
            DB::raw('SUM(potensi_produksi_petani.estimasi_volume_panen) as total_volume'),
            'potensi_produksi_petani.satuan'
        )
        ->groupBy('item_komoditas.id', 'item_komoditas.nama', 'master_komoditas.nama', 'kategori_komoditas.nama', 'potensi_produksi_petani.satuan')
        ->orderByDesc('total_volume')
        ->get();

        // 4. Sebaran Kategori Komoditas
        $sebaranKategori = KategoriKomoditas::withCount('masterKomoditas')
            ->where('is_active', true)
            ->get();

        return view('dashboard', compact(
            'desaAktif',
            'demografi',
            'jumlahPetani',
            'jumlahKategori',
            'jumlahMaster',
            'jumlahItem',
            'daftarKomoditasUnggulan',
            'sebaranKategori'
        ));
    }
}
