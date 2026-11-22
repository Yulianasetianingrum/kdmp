<?php

namespace App\Http\Controllers\Pendataan;

use App\Http\Controllers\Concerns\ResolvesEntitasAktif;
use App\Http\Controllers\Controller;
use App\Models\Master\ItemKomoditas;
use App\Models\Master\KategoriKomoditas;
use App\Models\Pendataan\Petani;
use App\Models\Pendataan\PotensiProduksiPetani;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PotensiKomoditasController extends Controller
{
    use ResolvesEntitasAktif;

    public function index(Request $request): View
    {
        $desaAktif = $this->desaAktif();
        $kategoriId = $request->query('kategori_id');
        $itemId = $request->query('item_id');
        $search = trim((string) $request->query('q', ''));

        $query = PotensiProduksiPetani::with(['petani.wilayah.parent.parent', 'item.masterKomoditas.kategori']);

        if ($desaAktif) {
            $query->whereHas('petani', function ($q) use ($desaAktif) {
                $q->where('id_wilayah', $desaAktif->id);
            });
        }

        if ($itemId) {
            $query->where('item_komoditas_id', $itemId);
        } elseif ($kategoriId) {
            $query->whereHas('item.masterKomoditas', function ($q) use ($kategoriId) {
                $q->where('id_kategori_komoditas', $kategoriId);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('petani', function ($qp) use ($search) {
                    $qp->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhereHas('item', function ($qi) use ($search) {
                    $qi->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $items = $query->latest()->paginate(15)->withQueryString();

        $kategoriList = KategoriKomoditas::where('is_active', true)->orderBy('nama')->get();
        $itemList = ItemKomoditas::where('is_active', true)->orderBy('nama')->get();

        return view('pendataan.potensi.index', [
            'title' => 'Potensi Komoditas: ' . ($desaAktif?->nama ?? 'Desa'),
            'items' => $items,
            'desaAktif' => $desaAktif,
            'kategoriList' => $kategoriList,
            'itemList' => $itemList,
            'kategoriId' => $kategoriId,
            'itemId' => $itemId,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        $desaAktif = $this->desaAktif();
        $petaniList = $desaAktif ? Petani::where('id_wilayah', $desaAktif->id)->orderBy('nama_lengkap')->get() : collect();
        $itemList = ItemKomoditas::with('masterKomoditas.kategori')->where('is_active', true)->orderBy('nama')->get();

        return view('pendataan.potensi.create', [
            'title' => 'Tambah Potensi Komoditas Petani: ' . ($desaAktif?->nama ?? 'Desa'),
            'desaAktif' => $desaAktif,
            'petaniList' => $petaniList,
            'itemList' => $itemList,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'petani_id' => ['required', 'exists:petani,id'],
            'item_komoditas_id' => ['required', 'exists:item_komoditas,id'],
            'luas_lahan_ha' => ['nullable', 'numeric', 'min:0'],
            'estimasi_volume_panen' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:30'],
            'periode_panen' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string'],
        ]);

        PotensiProduksiPetani::updateOrCreate(
            [
                'petani_id' => $validated['petani_id'],
                'item_komoditas_id' => $validated['item_komoditas_id'],
            ],
            $validated
        );

        return redirect()->route('pendataan.potensi.index')->with('success', 'Data potensi komoditas petani berhasil dicatat!');
    }

    public function destroy(PotensiProduksiPetani $potensi): RedirectResponse
    {
        $potensi->delete();

        return redirect()->route('pendataan.potensi.index')->with('success', 'Data potensi komoditas berhasil dihapus!');
    }
}
