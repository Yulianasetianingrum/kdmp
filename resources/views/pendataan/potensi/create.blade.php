<x-layouts.app :title="$title" eyebrow="Modul Pendataan">

    <div class="max-w-2xl bg-white p-6 rounded-xl border border-paper-200 shadow-sm">
        <div class="mb-4">
            <a href="{{ route('pendataan.potensi.index') }}" class="text-xs text-paper-500 hover:text-ink-900">&larr; Kembali ke Data Potensi</a>
            <h2 class="text-lg font-display font-semibold text-ink-900 mt-1">Hubungkan Potensi Komoditas Petani: {{ $desaAktif?->nama ?? 'Desa' }}</h2>
            <p class="text-xs text-paper-500">Mencatat komoditas dan kapasitas panen untuk petani warga desa ini.</p>
        </div>

        <form action="{{ route('pendataan.potensi.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Info Desa Aktor --}}
            <div class="p-3 bg-padi-50 border border-padi-200 rounded-lg flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-mono tracking-wider text-padi-700 font-bold">Desa Wilayah Aktor</span>
                    <p class="text-base font-display font-bold text-ink-900 mt-0.5">{{ $desaAktif?->nama ?? '-' }}</p>
                    <p class="text-xs text-paper-500 font-mono">Kec. {{ $desaAktif?->parent?->nama ?? 'Juwana' }}</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded bg-padi-200 text-padi-900 font-medium">Petani Warga Desa Aktif</span>
            </div>

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Pilih Petani Warga Desa <span class="text-red-500">*</span></label>
                <select name="petani_id" required class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">
                    <option value="">Pilih Petani...</option>
                    @foreach($petaniList as $p)
                        <option value="{{ $p->id }}" @selected(old('petani_id') == $p->id)>
                            {{ $p->nama_lengkap }} (RT {{ $p->rt ?: '00' }}/RW {{ $p->rw ?: '00' }} - {{ $p->nomor_hp ?: 'No HP (-)' }})
                        </option>
                    @endforeach
                </select>
                @error('petani_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Pilih Item / Subjenis Komoditas <span class="text-red-500">*</span></label>
                <select name="item_komoditas_id" required class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">
                    <option value="">Pilih Item Komoditas...</option>
                    @foreach($itemList as $item)
                        <option value="{{ $item->id }}" @selected(old('item_komoditas_id') == $item->id)>
                            {{ $item->masterKomoditas?->kategori?->nama }} &rarr; {{ $item->masterKomoditas?->nama }} &rarr; {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
                @error('item_komoditas_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-paper-700 mb-1">Estimasi Volume Panen <span class="text-red-500">*</span></label>
                    <input type="number" step="0.1" name="estimasi_volume_panen" value="{{ old('estimasi_volume_panen') }}" placeholder="Contoh: 5000" required
                           class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono focus:border-padi-500 focus:outline-none">
                    @error('estimasi_volume_panen') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-paper-700 mb-1">Satuan Hasil <span class="text-red-500">*</span></label>
                    <input type="text" name="satuan" value="{{ old('satuan', 'kg') }}" placeholder="kg, ton, butir, bungkus" required
                           class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono focus:border-padi-500 focus:outline-none">
                    @error('satuan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

                <div>
                    <label class="block text-xs font-medium text-paper-700 mb-1">Siklus / Periode Panen <span class="text-red-500">*</span></label>
                    <input type="text" name="periode_panen" value="{{ old('periode_panen', 'Per Musim (3-4 Bulan)') }}" placeholder="Musiman / Bulanan" required
                           class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="2" placeholder="Catatan pola tanam, varietas, irigasi..."
                          class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('pendataan.potensi.index') }}" class="px-4 py-2 rounded-lg border border-paper-300 text-xs font-medium hover:bg-paper-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-padi-500 text-ink-950 text-xs font-semibold hover:bg-padi-600 shadow-sm">
                    Simpan Relasi Potensi
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
