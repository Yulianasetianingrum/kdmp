<x-layouts.app :title="$title" eyebrow="Master Data Komoditas">

    <div class="max-w-2xl bg-white p-4 sm:p-6 rounded-xl border border-paper-200 shadow-sm">
        <h2 class="text-lg font-display font-semibold text-ink-900 mb-4">Tambah Kategori Komoditas Baru</h2>

        <form action="{{ route('master.kategori-komoditas.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <x-speech-input
                    name="kode"
                    label="Kode Kategori"
                    placeholder="Contoh: PERTANIAN, PETERNAKAN"
                    :required="true"
                    :value="old('kode')"
                    autoformat="KODE"
                />
            </div>

            <div>
                <x-speech-input
                    name="nama"
                    label="Nama Kategori"
                    placeholder="Contoh: Pertanian"
                    :required="true"
                    :value="old('nama')"
                />
            </div>

            <div>
                <x-speech-input
                    name="deskripsi"
                    type="textarea"
                    :rows="3"
                    label="Deskripsi / Ruang Lingkup"
                    placeholder="Penjelasan kategori..."
                    :value="old('deskripsi')"
                />
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-paper-300 text-padi-600 focus:ring-padi-500">
                <label for="is_active" class="text-sm text-ink-800">Kategori Aktif</label>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:items-center gap-2 sm:gap-3 pt-4 border-t border-paper-200">
                <a href="{{ route('master.kategori-komoditas.index') }}" class="w-full sm:w-auto text-center px-4 py-2 rounded-lg border border-paper-300 text-xs text-paper-600 hover:bg-paper-50">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto text-center px-5 py-2 rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold text-xs shadow-sm">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>

