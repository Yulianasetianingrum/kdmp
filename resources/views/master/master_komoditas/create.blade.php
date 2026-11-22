<x-layouts.app :title="$title" eyebrow="Master Data Komoditas">

    <div class="max-w-2xl bg-white p-6 rounded-xl border border-paper-200 shadow-sm">
        <h2 class="text-lg font-display font-semibold text-ink-900 mb-4">Tambah Master Komoditas Baru</h2>

        <form action="{{ route('master.master-komoditas.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Kategori Induk <span class="text-red-500">*</span></label>
                <select name="id_kategori" required class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">
                    <option value="">Pilih Kategori...</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" @selected(old('id_kategori') == $kat->id)>{{ $kat->nama }}</option>
                    @endforeach
                </select>
                @error('id_kategori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Kode Master <span class="text-red-500">*</span></label>
                <input type="text" name="kode" value="{{ old('kode') }}" placeholder="Contoh: BERAS, CABAI, BAWANG" required
                       class="w-full rounded-lg border border-paper-300 p-2 text-sm uppercase font-mono focus:border-padi-500 focus:outline-none">
                @error('kode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Nama Master Komoditas <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Beras, Cabai, Ikan" required
                       class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">
                @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Keterangan singkat..."
                          class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-paper-300 text-padi-600 focus:ring-padi-500">
                <label for="is_active" class="text-sm text-ink-800">Master Komoditas Aktif</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-paper-200">
                <button type="submit" class="px-5 py-2 rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold text-xs shadow-sm">
                    Simpan Master
                </button>
                <a href="{{ route('master.master-komoditas.index') }}" class="px-4 py-2 rounded-lg border border-paper-300 text-xs text-paper-600 hover:bg-paper-50">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-layouts.app>

