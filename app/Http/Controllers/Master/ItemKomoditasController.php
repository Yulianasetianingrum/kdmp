<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ItemKomoditas;
use App\Models\Master\MasterKomoditas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemKomoditasController extends Controller
{
    public function index(Request $request): View
    {
        $masterId = $request->query('master_id');
        $search = trim((string) $request->query('q', ''));

        $query = ItemKomoditas::with(['masterKomoditas.kategori'])->withCount('petani');

        if ($masterId) {
            $query->where('id_master_komoditas', $masterId);
        }

        if ($search !== '') {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
        }

        $items = $query->orderBy('nama')->paginate(15)->withQueryString();
        $masterList = MasterKomoditas::with('kategori')->where('is_active', true)->orderBy('nama')->get();

        return view('master.item_komoditas.index', [
            'title' => 'Item / Subjenis Komoditas',
            'items' => $items,
            'masterList' => $masterList,
            'masterId' => $masterId,
            'search' => $search,
        ]);
    }

    public function show(ItemKomoditas $itemKomoditas): View
    {
        $itemKomoditas->load(['masterKomoditas.kategori', 'petani.wilayah']);

        // Mengambil desa-desa unik penghasil komoditas ini
        $desaPenghasil = $itemKomoditas->petani->map(function ($p) {
            return [
                'desa' => $p->desa ?: $p->wilayah?->nama,
                'kecamatan' => $p->kecamatan ?: $p->wilayah?->parent?->nama,
                'kabupaten' => $p->kabupaten ?: $p->wilayah?->parent?->parent?->nama,
            ];
        })->unique(fn($d) => $d['desa'] . '-' . $d['kecamatan'])->values();

        return view('master.item_komoditas.show', [
            'title' => 'Detail Komoditas: ' . $itemKomoditas->nama,
            'item' => $itemKomoditas,
            'desaPenghasil' => $desaPenghasil,
            'daftarPetani' => $itemKomoditas->petani,
        ]);
    }

    public function create(): View
    {
        $masterList = MasterKomoditas::with('kategori')->where('is_active', true)->orderBy('nama')->get();

        return view('master.item_komoditas.create', [
            'title' => 'Tambah Item Komoditas',
            'masterList' => $masterList,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_master_komoditas' => ['required', 'exists:master_komoditas,id'],
            'kode' => ['required', 'string', 'max:30', 'unique:item_komoditas,kode'],
            'nama' => ['required', 'string', 'max:150'],
            'satuan_standar' => ['required', 'string', 'max:30'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        ItemKomoditas::create($validated);

        return redirect()->route('master.item-komoditas.index')->with('success', 'Item komoditas berhasil ditambahkan.');
    }

    public function edit(ItemKomoditas $itemKomoditas): View
    {
        $masterList = MasterKomoditas::with('kategori')->where('is_active', true)->orderBy('nama')->get();

        return view('master.item_komoditas.edit', [
            'title' => 'Edit Item Komoditas',
            'item' => $itemKomoditas,
            'masterList' => $masterList,
        ]);
    }

    public function update(Request $request, ItemKomoditas $itemKomoditas): RedirectResponse
    {
        $validated = $request->validate([
            'id_master_komoditas' => ['required', 'exists:master_komoditas,id'],
            'kode' => ['required', 'string', 'max:30', 'unique:item_komoditas,kode,' . $itemKomoditas->id],
            'nama' => ['required', 'string', 'max:150'],
            'satuan_standar' => ['required', 'string', 'max:30'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $itemKomoditas->update($validated);

        return redirect()->route('master.item-komoditas.index')->with('success', 'Item komoditas berhasil diperbarui.');
    }

    public function destroy(ItemKomoditas $itemKomoditas): RedirectResponse
    {
        if ($itemKomoditas->petani()->count() > 0) {
            return back()->with('error', 'Item komoditas tidak dapat dihapus karena sudah terhubung dengan data petani/produksi.');
        }

        $itemKomoditas->delete();
        return redirect()->route('master.item-komoditas.index')->with('success', 'Item komoditas berhasil dihapus.');
    }
}

