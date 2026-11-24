<x-layouts.app :title="$title" eyebrow="Master Data Komoditas">

    <div class="max-w-4xl bg-white p-4 sm:p-6 rounded-xl border border-paper-200 shadow-sm">
        <div class="mb-4">
            <a href="{{ route('master.item-komoditas.index') }}" class="text-xs text-paper-500 hover:text-ink-900">&larr; Kembali ke Daftar Item Komoditas</a>
            <h2 class="text-lg font-display font-semibold text-ink-900 mt-1">Edit Item Komoditas: {{ $item->nama }}</h2>
            <p class="text-xs text-paper-500">Pembaruan spesifikasi item komoditas beserta pengelolaan petani produsen terkait.</p>
        </div>

        <form action="{{ route('master.item-komoditas.update', $item) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Master Komoditas Induk <span class="text-red-500">*</span></label>
                <select name="id_master_komoditas" id="id_master_komoditas" required class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none">
                    <option value="">Pilih Master Komoditas...</option>
                    @foreach($masterList as $m)
                        @php
                            $isEkraf = str_contains(strtolower($m->kategori?->kode ?? ''), 'ekraf') || str_contains(strtolower($m->kategori?->nama ?? ''), 'ekraf');
                        @endphp
                        <option value="{{ $m->id }}" data-is-ekraf="{{ $isEkraf ? '1' : '0' }}" @selected(old('id_master_komoditas', $item->id_master_komoditas) == $m->id)>
                            {{ $m->nama }} (Kategori: {{ $m->kategori?->nama }})
                        </option>
                    @endforeach
                </select>
                @error('id_master_komoditas') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-speech-input
                    name="kode"
                    label="Kode Item"
                    :required="true"
                    :value="old('kode', $item->kode)"
                    autoformat="KODE"
                />
            </div>

            <div>
                <x-speech-input
                    name="nama"
                    label="Nama Subjenis / Item Komoditas"
                    :required="true"
                    :value="old('nama', $item->nama)"
                />
            </div>

            <div>
                <label class="block text-xs font-medium text-paper-700 mb-1">Satuan Standar <span class="text-red-500">*</span></label>
                <select name="satuan_standar" id="satuan_standar" required class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono focus:border-padi-500 focus:outline-none bg-white">
                    <option value="kg" @selected(old('satuan_standar', $item->satuan_standar) === 'kg')>kg (Kilogram &mdash; Pertanian, Peternakan, Perikanan)</option>
                    <option value="unit" @selected(old('satuan_standar', $item->satuan_standar) === 'unit')>unit (Unit &mdash; Ekonomi Kreatif)</option>
                </select>
                <p class="text-[11px] text-paper-500 mt-1">Otomatis: Kategori Ekraf menggunakan satuan <strong>unit</strong>, sedangkan selain Ekraf menggunakan satuan <strong>kg</strong>.</p>
                @error('satuan_standar') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-speech-input
                    name="deskripsi"
                    type="textarea"
                    :rows="3"
                    label="Deskripsi Spesifikasi"
                    :value="old('deskripsi', $item->deskripsi)"
                />
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $item->is_active)) class="rounded border-paper-300 text-padi-600 focus:ring-padi-500">
                <label for="is_active" class="text-sm text-ink-800">Item Komoditas Aktif</label>
            </div>

            {{-- DAFTAR PETANI / PRODUSEN TERKAIT KOMODITAS INI --}}
            <div class="border-t border-paper-200 pt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-ink-900">Daftar Petani / Produsen Komoditas Ini</h3>
                        <p class="text-xs text-paper-500">Petani dan produsen desa yang memproduksi {{ $item->nama }}.</p>
                    </div>
                    <span class="text-xs font-mono font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ $item->petani->count() }} Petani Terdaftar
                    </span>
                </div>

                @if($item->petani->isEmpty())
                    <p class="text-xs text-paper-400 bg-paper-50 p-3 rounded-lg border border-paper-200">
                        Belum ada petani yang terhubung menghasilkan komoditas ini. Anda dapat menghubungkannya melalui form di bawah.
                    </p>
                @else
                    <div class="overflow-x-auto rounded-lg border border-paper-200 mb-4">
                        <table class="w-full min-w-[640px] text-left text-xs">
                            <thead class="bg-paper-50 border-b border-paper-200 font-mono text-[11px] text-paper-600">
                                <tr>
                                    <th class="px-3 py-2">Nama Petani</th>
                                    <th class="px-3 py-2">Kontak (HP)</th>
                                    <th class="px-3 py-2">Dusun / RT-RW</th>
                                    <th class="px-3 py-2">Estimasi Panen</th>
                                    <th class="px-3 py-2">Harga Satuan</th>
                                    <th class="px-3 py-2">Total Nilai</th>
                                    <th class="px-3 py-2">Siklus</th>
                                    <th class="px-3 py-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-paper-100">
                                @foreach($item->petani as $p)
                                    <tr class="hover:bg-paper-50/50">
                                        <td class="px-3 py-2 font-medium text-ink-900">
                                            <a href="{{ route('pendataan.petani.show', $p) }}" class="text-blue-700 hover:underline">
                                                {{ $p->nama_lengkap }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-2 font-mono text-paper-700">{{ $p->nomor_hp ?: '-' }}</td>
                                        <td class="px-3 py-2 text-paper-600">
                                            {{ $p->dusun ? 'Dsn. ' . $p->dusun : '' }}
                                            @if($p->rt || $p->rw)
                                                <span class="font-mono text-[10px] px-1 py-0.5 rounded bg-paper-100">RT {{ $p->rt }}/RW {{ $p->rw }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 font-mono font-semibold text-sawah-900">
                                            {{ number_format($p->pivot->estimasi_volume_panen) }} {{ $p->pivot->satuan }}
                                        </td>
                                        <td class="px-3 py-2 font-mono text-emerald-800 font-medium">
                                            @if($p->pivot->harga_satuan)
                                                Rp {{ number_format($p->pivot->harga_satuan, 0, ',', '.') }}<span class="text-[10px] text-paper-500 font-sans">/{{ $p->pivot->satuan }}</span>
                                            @else
                                                <span class="text-paper-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 font-mono text-emerald-700 font-bold">
                                            @if($p->pivot->total_nilai)
                                                Rp {{ number_format($p->pivot->total_nilai, 0, ',', '.') }}
                                            @else
                                                <span class="text-paper-400 font-normal">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-paper-600 font-medium">{{ $p->pivot->periode_panen ?: '-' }}</td>
                                        <td class="px-3 py-2 text-right">
                                            <a href="{{ route('pendataan.potensi.edit', ['potensi' => $p->pivot->id, 'redirect_to' => route('master.item-komoditas.edit', $item)]) }}" class="text-blue-600 hover:underline mr-2 font-medium">
                                                Edit
                                            </a>
                                            <a href="{{ route('pendataan.potensi.destroy', $p->pivot->id) }}"
                                               onclick="event.preventDefault(); if(confirm('Lepas petani {{ $p->nama_lengkap }} dari komoditas ini?')) { document.getElementById('del-petani-{{ $p->pivot->id }}').submit(); }"
                                               class="text-red-600 hover:underline">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Tambah / Hubungkan Petani Baru Sekaligus (Opsional) --}}
                <div class="mt-4 p-4 rounded-xl bg-paper-50 border border-paper-200 space-y-3" x-data="{
                    modeBaru: false,
                    volume: '{{ old('tambah_estimasi_volume_panen', '') }}',
                    hargaSatuan: '{{ old('tambah_harga_satuan', '') }}',
                    totalNilai: '{{ old('tambah_total_nilai', '') }}',
                    satuan: '{{ $item->satuan_standar }}',
                    formatRupiah(num) {
                        if (!num || isNaN(num)) return '0';
                        return new Intl.NumberFormat('id-ID').format(Math.round(num));
                    },
                    updateFromSatuan() {
                        let v = parseFloat(this.volume);
                        let s = parseFloat(this.hargaSatuan);
                        if (!isNaN(v) && v > 0 && !isNaN(s) && s >= 0) {
                            this.totalNilai = Math.round(v * s);
                        } else if (!this.hargaSatuan) {
                            this.totalNilai = '';
                        }
                    },
                    updateFromTotal() {
                        let v = parseFloat(this.volume);
                        let t = parseFloat(this.totalNilai);
                        if (!isNaN(v) && v > 0 && !isNaN(t) && t >= 0) {
                            this.hargaSatuan = Math.round(t / v);
                        } else if (!this.totalNilai) {
                            this.hargaSatuan = '';
                        }
                    },
                    onVolumeChange() {
                        let v = parseFloat(this.volume);
                        if (!isNaN(v) && v > 0) {
                            if (this.hargaSatuan && !isNaN(parseFloat(this.hargaSatuan))) {
                                this.updateFromSatuan();
                            } else if (this.totalNilai && !isNaN(parseFloat(this.totalNilai))) {
                                this.updateFromTotal();
                            }
                        }
                    }
                }">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-semibold text-ink-900 uppercase tracking-wide">
                            + Hubungkan Petani / Produsen Baru ke Komoditas Ini (Opsional)
                        </h4>
                        <button type="button" @click="modeBaru = !modeBaru" class="text-xs text-blue-700 hover:underline font-medium">
                            <span x-show="!modeBaru">+ Daftarkan Petani Baru Jika Belum Ada</span>
                            <span x-show="modeBaru">&larr; Pilih Dari Petani Yang Sudah Ada</span>
                        </button>
                    </div>

                    {{-- Mode 1: Pilih Petani Terdaftar --}}
                    <div x-show="!modeBaru">
                        <label class="block text-xs font-medium text-paper-700 mb-1">Pilih Petani Terdaftar</label>
                        <select name="tambah_petani_id" class="w-full rounded-lg border border-paper-300 p-2 text-sm bg-white focus:border-padi-500 focus:outline-none">
                            <option value="">Pilih Petani jika ingin menghubungkan...</option>
                            @foreach($petaniList as $pt)
                                <option value="{{ $pt->id }}" @selected(old('tambah_petani_id') == $pt->id)>
                                    {{ $pt->nama_lengkap }} ({{ $pt->nomor_hp ?: 'Tanpa HP' }} - Dusun {{ $pt->dusun ?: '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mode 2: Input Petani Baru --}}
                    <div x-show="modeBaru" class="space-y-3 p-3 bg-white rounded-lg border border-paper-200" x-cloak>
                        <h5 class="text-xs font-semibold text-ink-800">Biodata Petani Baru:</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <x-speech-input
                                    name="tambah_nama_petani"
                                    label="Nama Lengkap Petani"
                                    placeholder="Contoh: Pak Slamet"
                                    :value="old('tambah_nama_petani')"
                                />
                            </div>
                            <div>
                                <x-speech-input
                                    name="tambah_nomor_hp"
                                    type="tel"
                                    label="Nomor WhatsApp / HP"
                                    placeholder="Contoh: 081234567890"
                                    :value="old('tambah_nomor_hp')"
                                />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <x-speech-input
                                    name="tambah_dusun"
                                    label="Dusun / Dukuh"
                                    placeholder="Contoh: Krajan"
                                    :value="old('tambah_dusun')"
                                />
                            </div>
                            <div>
                                <x-speech-input
                                    name="tambah_rt"
                                    label="RT"
                                    autoformat="RT"
                                    placeholder="01"
                                    :value="old('tambah_rt')"
                                />
                            </div>
                            <div>
                                <x-speech-input
                                    name="tambah_rw"
                                    label="RW"
                                    autoformat="RW"
                                    placeholder="02"
                                    :value="old('tambah_rw')"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Form Produksi & Harga --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div>
                            <x-speech-input
                                name="tambah_estimasi_volume_panen"
                                type="number"
                                step="0.1"
                                label="Estimasi Volume Panen"
                                placeholder="Contoh: 1000"
                                xmodel="volume"
                                @input="onVolumeChange()"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-paper-700 mb-1">Satuan</label>
                            <input type="text" name="tambah_satuan" :value="satuan" readonly class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono bg-paper-100 text-paper-600 cursor-not-allowed">
                        </div>

                        <div>
                            <x-speech-input
                                name="tambah_periode_panen"
                                label="Siklus Panen"
                                autoformat="BULAN"
                                placeholder="Contoh: 3 - 4 bulan"
                                :value="old('tambah_periode_panen')"
                            />
                        </div>
                    </div>

                    <!-- Konversi Harga Real-time (2 Arah) -->
                    <div class="rounded-xl border border-emerald-200 bg-white/80 p-3.5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-emerald-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Penetapan Harga Komoditas (Otomatis Konversi 2 Arah)
                            </span>
                            <span class="text-[11px] text-emerald-800 font-mono font-medium">
                                Satuan: <strong x-text="satuan"></strong>
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <x-speech-input
                                    name="tambah_harga_satuan"
                                    type="number"
                                    step="1"
                                    label="Harga Satuan (Rp)"
                                    placeholder="Contoh: 35000"
                                    :value="old('tambah_harga_satuan')"
                                    xmodel="hargaSatuan"
                                    @input="updateFromSatuan()"
                                />
                                <template x-if="hargaSatuan && !isNaN(hargaSatuan)">
                                    <p class="text-[11px] text-emerald-800 mt-1 font-mono">
                                        Rp <span x-text="formatRupiah(hargaSatuan)"></span> / <span x-text="satuan"></span>
                                    </p>
                                </template>
                            </div>
                            <div>
                                <x-speech-input
                                    name="tambah_total_nilai"
                                    type="number"
                                    step="1"
                                    label="Total Nilai Panen (Rp)"
                                    placeholder="Contoh: 70000000"
                                    :value="old('tambah_total_nilai')"
                                    xmodel="totalNilai"
                                    @input="updateFromTotal()"
                                />
                                <template x-if="totalNilai && !isNaN(totalNilai)">
                                    <p class="text-[11px] text-emerald-800 mt-1 font-mono font-semibold">
                                        Total: Rp <span x-text="formatRupiah(totalNilai)"></span>
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:items-center gap-2 sm:gap-3 pt-4 border-t border-paper-200">
                <a href="{{ route('master.item-komoditas.index') }}" class="w-full sm:w-auto text-center px-4 py-2 rounded-lg border border-paper-300 text-xs text-paper-600 hover:bg-paper-50">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto text-center px-5 py-2 rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold text-xs shadow-sm">
                    Perbarui Item & Hubungan Petani
                </button>
            </div>
        </form>

        {{-- Form Hapus Relasi Petani Terkait --}}
        @foreach($item->petani as $p)
            <form id="del-petani-{{ $p->pivot->id }}" action="{{ route('pendataan.potensi.destroy', $p->pivot->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>

    <script>
        document.getElementById('id_master_komoditas')?.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const isEkraf = selected?.getAttribute('data-is-ekraf') === '1';
            const satuanSelect = document.getElementById('satuan_standar');
            if (satuanSelect) {
                satuanSelect.value = isEkraf ? 'unit' : 'kg';
            }
        });
    </script>

</x-layouts.app>
