<x-layouts.app :title="$title" eyebrow="Modul Pendataan">

    <div class="max-w-3xl bg-white p-6 rounded-xl border border-paper-200 shadow-sm">
        <div class="mb-4">
            <a href="{{ route('pendataan.petani.index') }}" class="text-xs text-paper-500 hover:text-ink-900">&larr; Kembali ke Data Petani</a>
            <h2 class="text-lg font-display font-semibold text-ink-900 mt-1">Tambah Petani: {{ $desaAktif?->nama ?? 'Desa' }}</h2>
            <p class="text-xs text-paper-500">Mendaftarkan identitas produsen komoditas warga desa ini.</p>
        </div>

        <form action="{{ route('pendataan.petani.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Info Desa Aktor --}}
            <div class="p-3 bg-padi-50 border border-padi-200 rounded-lg flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-mono tracking-wider text-padi-700 font-bold">Desa Domisili Petani</span>
                    <p class="text-base font-display font-bold text-ink-900 mt-0.5">{{ $desaAktif?->nama ?? '-' }}</p>
                    <p class="text-xs text-paper-500 font-mono">Kec. {{ $desaAktif?->parent?->nama ?? 'Juwana' }}, Kab. {{ $desaAktif?->parent?->parent?->nama ?? 'Pati' }}</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded bg-padi-200 text-padi-900 font-medium">Otomatis Terkunci ke Desa Aktif</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-paper-700 mb-1">Nama Lengkap Petani <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Contoh: Sutrisno Hartono" required
                           class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">
                    @error('nama_lengkap') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-paper-700 mb-1">Nomor HP / WhatsApp</label>
                    <input type="text" name="nomor_hp" value="{{ old('nomor_hp') }}" placeholder="Contoh: 081234567890"
                           class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono focus:border-padi-500 focus:outline-none">
                    @error('nomor_hp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-paper-700 mb-1">Alamat Dusun / Jalan</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Nama Dusun / Jalan..."
                           class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-paper-700 mb-1">RT</label>
                        <input type="text" name="rt" value="{{ old('rt') }}" placeholder="01"
                               class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono focus:border-padi-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-paper-700 mb-1">RW</label>
                        <input type="text" name="rw" value="{{ old('rw') }}" placeholder="02"
                               class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono focus:border-padi-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- Pemilihan Komoditas yang Dihasilkan (Opsional jika ingin langsung dihubungkan) --}}
            <div class="p-4 rounded-xl bg-paper-50 border border-paper-200 space-y-3">
                <h3 class="text-xs font-mono uppercase tracking-wider text-paper-700 font-semibold">
                    Komoditas Utama yang Dihasilkan (Opsional)
                </h3>

                <div>
                    <label class="block text-xs font-medium text-paper-700 mb-1">Item / Subjenis Komoditas</label>
                    <select name="item_komoditas_id" class="w-full rounded-lg border border-paper-300 p-2 text-sm bg-white focus:border-padi-500 focus:outline-none">
                        <option value="">Pilih Komoditas...</option>
                        @foreach($itemList as $it)
                            <option value="{{ $it->id }}">
                                {{ $it->masterKomoditas?->kategori?->nama }} &rarr; {{ $it->masterKomoditas?->nama }} &rarr; {{ $it->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-paper-700 mb-1">Estimasi Volume Panen</label>
                        <input type="number" step="0.01" name="estimasi_volume_panen" value="{{ old('estimasi_volume_panen') }}" placeholder="1000"
                               class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono bg-white focus:border-padi-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-paper-700 mb-1">Satuan</label>
                        <input type="text" name="satuan" value="kg"
                               class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono bg-white focus:border-padi-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-paper-700 mb-1">Periode Panen</label>
                        <input type="text" name="periode_panen" value="Per Musim"
                               class="w-full rounded-lg border border-paper-300 p-2 text-sm bg-white focus:border-padi-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('pendataan.petani.index') }}" class="px-4 py-2 rounded-lg border border-paper-300 text-xs font-medium hover:bg-paper-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-padi-500 text-ink-950 text-xs font-semibold hover:bg-padi-600 shadow-sm">
                    Simpan Petani
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
