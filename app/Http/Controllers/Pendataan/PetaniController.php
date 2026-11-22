<?php

namespace App\Http\Controllers\Pendataan;

use App\Http\Controllers\Concerns\ResolvesEntitasAktif;
use App\Http\Controllers\Controller;
use App\Models\Master\ItemKomoditas;
use App\Models\Pendataan\Petani;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PetaniController extends Controller
{
    use ResolvesEntitasAktif;

    public function index(Request $request): View
    {
        $desaAktif = $this->desaAktif();
        $search = trim((string) $request->query('q', ''));

        $query = Petani::with(['items.masterKomoditas.kategori'])->withCount('items');

        if ($desaAktif) {
            $query->where('id_wilayah', $desaAktif->id);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        return view('pendataan.petani.index', [
            'title' => 'Data Petani: ' . ($desaAktif?->nama ?? 'Desa'),
            'items' => $items,
            'desaAktif' => $desaAktif,
            'search' => $search,
        ]);
    }

    public function show(Petani $petani): View
    {
        $petani->load(['wilayah.parent.parent', 'items.masterKomoditas.kategori']);

        return view('pendataan.petani.show', [
            'title' => 'Detail Petani: ' . $petani->nama_lengkap,
            'petani' => $petani,
            'komoditasList' => $petani->items,
        ]);
    }

    public function create(): View
    {
        $desaAktif = $this->desaAktif();
        $itemList = ItemKomoditas::with('masterKomoditas.kategori')->where('is_active', true)->orderBy('nama')->get();

        return view('pendataan.petani.create', [
            'title' => 'Tambah Petani: ' . ($desaAktif?->nama ?? 'Desa'),
            'desaAktif' => $desaAktif,
            'itemList' => $itemList,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $desaAktif = $this->desaAktif();

        if (! $desaAktif) {
            return back()->with('error', 'Desa aktif tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nomor_hp' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'item_komoditas_id' => ['nullable', 'exists:item_komoditas,id'],
            'luas_lahan_ha' => ['nullable', 'numeric', 'min:0'],
            'estimasi_volume_panen' => ['nullable', 'numeric', 'min:0'],
            'satuan' => ['nullable', 'string', 'max:30'],
            'periode_panen' => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($validated, $desaAktif) {
            $petani = Petani::create([
                'id_wilayah' => $desaAktif->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'nomor_hp' => $validated['nomor_hp'],
                'alamat' => $validated['alamat'],
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'desa' => $desaAktif->nama,
                'kecamatan' => $desaAktif->parent?->nama ?? 'Juwana',
                'kabupaten' => $desaAktif->parent?->parent?->nama ?? 'Pati',
            ]);

            if (!empty($validated['item_komoditas_id']) && !empty($validated['estimasi_volume_panen'])) {
                $petani->items()->attach($validated['item_komoditas_id'], [
                    'luas_lahan_ha' => $validated['luas_lahan_ha'] ?? 0,
                    'estimasi_volume_panen' => $validated['estimasi_volume_panen'],
                    'satuan' => $validated['satuan'] ?? 'kg',
                    'periode_panen' => $validated['periode_panen'] ?? 'Per Musim',
                ]);
            }
        });

        return redirect()->route('pendataan.petani.index')->with('success', "Data petani di {$desaAktif->nama} berhasil didaftarkan!");
    }

    public function edit(Petani $petani): View
    {
        $desaAktif = $this->desaAktif();

        return view('pendataan.petani.edit', [
            'title' => 'Edit Petani: ' . $petani->nama_lengkap,
            'petani' => $petani,
            'desaAktif' => $desaAktif,
        ]);
    }

    public function update(Request $request, Petani $petani): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nomor_hp' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
        ]);

        $petani->update($validated);

        return redirect()->route('pendataan.petani.index')->with('success', 'Data petani berhasil diperbarui!');
    }

    public function destroy(Petani $petani): RedirectResponse
    {
        $petani->items()->detach();
        $petani->delete();

        return redirect()->route('pendataan.petani.index')->with('success', 'Data petani berhasil dihapus!');
    }
}
