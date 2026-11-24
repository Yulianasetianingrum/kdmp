<x-layouts.app :title="$title" eyebrow="Master Data Komoditas">

    <div class="max-w-4xl bg-white p-4 sm:p-6 rounded-xl border border-paper-200 shadow-sm">
        <h2 class="text-lg font-display font-semibold text-ink-900 mb-4">Tambah Item / Subjenis Komoditas Baru</h2>

        <form action="{{ route('master.item-komoditas.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-ink-800 border-b border-paper-200 pb-2">Informasi Komoditas</h3>

                <div>
                    <label class="block text-xs font-medium text-paper-700 mb-1">Master Komoditas Induk <span class="text-red-500">*</span></label>
                    <select name="id_master_komoditas" id="id_master_komoditas" required class="w-full rounded-lg border border-paper-300 p-2 text-sm focus:border-padi-500 focus:outline-none bg-white">
                        <option value="">Pilih Master Komoditas...</option>
                        @foreach($masterList as $m)
                            @php
                                $isEkraf = str_contains(strtolower($m->kategori?->kode ?? ''), 'ekraf') || str_contains(strtolower($m->kategori?->nama ?? ''), 'ekraf');
                            @endphp
                            <option value="{{ $m->id }}" data-is-ekraf="{{ $isEkraf ? '1' : '0' }}" @selected(old('id_master_komoditas') == $m->id)>
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
                        placeholder="Contoh: BERAS-MENTIK, CABAI-RAWIT"
                        :required="true"
                        :value="old('kode')"
                        autoformat="KODE"
                    />
                </div>

                <div>
                    <x-speech-input
                        name="nama"
                        label="Nama Subjenis / Item Komoditas"
                        placeholder="Contoh: Beras Mentik Wangi, Cabai Rawit Merah"
                        :required="true"
                        :value="old('nama')"
                    />
                </div>

                <div>
                    <label class="block text-xs font-medium text-paper-700 mb-1">Satuan Standar <span class="text-red-500">*</span></label>
                    <select name="satuan_standar" id="satuan_standar" required class="w-full rounded-lg border border-paper-300 p-2 text-sm font-mono focus:border-padi-500 focus:outline-none bg-white">
                        <option value="kg" @selected(old('satuan_standar', 'kg') === 'kg')>kg (Kilogram &mdash; Pertanian, Peternakan, Perikanan)</option>
                        <option value="unit" @selected(old('satuan_standar') === 'unit')>unit (Unit &mdash; Ekonomi Kreatif)</option>
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
                        placeholder="Karakteristik atau spesifikasi komoditas..."
                        :value="old('deskripsi')"
                    />
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-paper-300 text-padi-600 focus:ring-padi-500">
                    <label for="is_active" class="text-sm text-ink-800">Item Komoditas Aktif</label>
                </div>
            </div>

            {{-- Hubungkan Petani / Produsen Awal (Opsional) --}}
            <div class="space-y-4 pt-4 border-t border-paper-200" x-data="{
                modeBaru: false,
                satuan: '{{ old('satuan_standar', 'kg') }}',
                volume: '{{ old('tambah_estimasi_volume_panen', '') }}',
                hargaSatuan: '{{ old('tambah_harga_satuan', '') }}',
                totalNilai: '{{ old('tambah_total_nilai', '') }}',
                init() {
                    const satuanEl = document.getElementById('satuan_standar');
                    if (satuanEl) {
                        this.satuan = satuanEl.value;
                        satuanEl.addEventListener('change', () => {
                            this.satuan = satuanEl.value;
                        });
                    }
                    const masterEl = document.getElementById('id_master_komoditas');
                    if (masterEl) {
                        masterEl.addEventListener('change', () => {
                            const opt = masterEl.options[masterEl.selectedIndex];
                            const isEkraf = opt?.getAttribute('data-is-ekraf') === '1';
                            if (satuanEl) {
                                satuanEl.value = isEkraf ? 'unit' : 'kg';
                                this.satuan = satuanEl.value;
                            }
                        });
                    }
                },
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
                <div class="p-4 rounded-xl bg-paper-50 border border-paper-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-semibold text-ink-900 uppercase tracking-wide">
                                + Hubungkan Petani / Produsen Awal (Opsional)
                            </h4>
                            <p class="text-[11px] text-paper-500">Anda dapat langsung mendaftarkan petani yang memproduksi komoditas ini sekarang, atau melakukannya nanti.</p>
                        </div>
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
                    Simpan Item Komoditas
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
