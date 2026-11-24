<x-layouts.app :title="$title" eyebrow="Informasi Detail Komoditas">

    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('master.item-komoditas.index') }}" class="text-xs text-paper-500 hover:text-ink-900">&larr; Kembali ke Daftar Item</a>
            </div>
            <h2 class="text-xl sm:text-2xl font-display font-semibold text-ink-900 mt-1">{{ $item->nama }}</h2>
        </div>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('master.item-komoditas.edit', $item) }}" class="flex-1 sm:flex-initial inline-flex justify-center items-center px-3 py-1.5 rounded-lg border border-paper-300 text-xs font-medium hover:bg-paper-50 text-center">
                Edit Item
            </a>
            <a href="{{ route('pendataan.potensi.create', ['redirect_to' => route('master.item-komoditas.show', $item)]) }}" class="flex-1 sm:flex-initial inline-flex justify-center items-center px-3 py-1.5 rounded-lg bg-padi-500 text-ink-950 text-xs font-semibold hover:bg-padi-600 shadow-sm text-center">
                + Tambah Petani Penghasil
            </a>
        </div>
    </div>

    {{-- Kartu Ringkasan Hierarki --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="p-4 rounded-xl bg-white border border-paper-200 shadow-sm">
            <span class="text-[10px] font-mono uppercase tracking-wider text-paper-400">1. Kategori</span>
            <p class="text-base font-display font-bold text-ink-900 mt-0.5">{{ $item->masterKomoditas?->kategori?->nama ?? '-' }}</p>
            <p class="text-[11px] text-paper-500 font-mono">Kode: {{ $item->masterKomoditas?->kategori?->kode }}</p>
        </div>

        <div class="p-4 rounded-xl bg-white border border-paper-200 shadow-sm">
            <span class="text-[10px] font-mono uppercase tracking-wider text-paper-400">2. Master Komoditas</span>
            <p class="text-base font-display font-bold text-ink-900 mt-0.5">{{ $item->masterKomoditas?->nama ?? '-' }}</p>
            <p class="text-[11px] text-paper-500 font-mono">Kode: {{ $item->masterKomoditas?->kode }}</p>
        </div>

        <div class="p-4 rounded-xl bg-white border border-paper-200 shadow-sm">
            <span class="text-[10px] font-mono uppercase tracking-wider text-paper-400">3. Item / Subjenis</span>
            <p class="text-base font-display font-bold text-blue-700 mt-0.5">{{ $item->nama }}</p>
            <p class="text-[11px] text-paper-500 font-mono">Satuan: {{ $item->satuan_standar }}</p>
        </div>

        <div class="p-4 rounded-xl bg-white border border-paper-200 shadow-sm">
            <span class="text-[10px] font-mono uppercase tracking-wider text-paper-400">Total Produsen</span>
            <p class="text-base font-display font-bold text-emerald-700 mt-0.5">{{ $daftarPetani->count() }} Petani</p>
            <p class="text-[11px] text-paper-500">di {{ count($desaPenghasil) }} Desa</p>
        </div>
    </div>

    {{-- 4. Daftar Desa Penghasil --}}
    <div class="mb-6 bg-white p-5 rounded-xl border border-paper-200 shadow-sm">
        <h3 class="text-sm font-display font-semibold text-ink-900 uppercase tracking-wide mb-3 flex items-center gap-2">
            4. Desa Penghasil Komoditas Ini
        </h3>

        @if(empty($desaPenghasil))
            <p class="text-xs text-paper-400">Belum ada data desa yang tercatat memproduksi komoditas ini.</p>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($desaPenghasil as $dp)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs bg-sawah-50 text-sawah-900 border border-sawah-200 font-medium">
                        <strong>{{ $dp['desa'] }}</strong> (Kec. {{ $dp['kecamatan'] ?? '-' }}, Kab. {{ $dp['kabupaten'] ?? 'Pati' }})
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 5. Daftar Petani yang Menghasilkan Item Tersebut --}}
    <div class="bg-white rounded-xl border border-paper-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-paper-200 bg-paper-50/50 flex items-center justify-between">
            <h3 class="text-sm font-display font-semibold text-ink-900 uppercase tracking-wide flex items-center gap-2">
                5. Daftar Petani / Produsen Penghasil
            </h3>
            <span class="text-xs text-paper-500">{{ $daftarPetani->count() }} Petani Terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="border-b border-paper-200 bg-paper-50 font-mono text-[11px] uppercase tracking-wide text-ink-600">
                <tr>
                    <th class="px-5 py-3">Nama Petani</th>
                    <th class="px-5 py-3">Kontak (HP)</th>
                    <th class="px-5 py-3">Wilayah (Desa, Kec)</th>
                    <th class="px-5 py-3">Estimasi Panen</th>
                    <th class="px-5 py-3">Harga Satuan</th>
                    <th class="px-5 py-3">Total Nilai</th>
                    <th class="px-5 py-3">Siklus Panen</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-paper-100">
                @forelse($daftarPetani as $p)
                    <tr class="hover:bg-paper-50/60">
                        <td class="px-5 py-3 font-semibold text-ink-900">
                            <a href="{{ route('pendataan.petani.show', $p) }}" class="text-blue-700 hover:underline">
                                {{ $p->nama_lengkap }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-xs text-paper-700 font-mono">{{ $p->nomor_hp ?: '-' }}</td>
                        <td class="px-5 py-3 text-xs text-paper-700">
                            {{ $p->desa ?: $p->wilayah?->nama }}, Kec. {{ $p->kecamatan ?: $p->wilayah?->parent?->nama }}
                        </td>
                        <td class="px-5 py-3 text-xs font-semibold text-sawah-900 font-mono">
                            {{ number_format($p->pivot->estimasi_volume_panen) }} {{ $p->pivot->satuan }}
                        </td>
                        <td class="px-5 py-3 text-xs font-mono font-medium text-emerald-800">
                            @if($p->pivot->harga_satuan)
                                Rp {{ number_format($p->pivot->harga_satuan, 0, ',', '.') }}<span class="text-[10px] text-paper-500 font-sans">/{{ $p->pivot->satuan }}</span>
                            @else
                                <span class="text-paper-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs font-mono font-bold text-emerald-700">
                            @if($p->pivot->total_nilai)
                                Rp {{ number_format($p->pivot->total_nilai, 0, ',', '.') }}
                            @else
                                <span class="text-paper-400 font-normal">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs text-paper-600 font-medium">
                            {{ $p->pivot->periode_panen ?: '-' }}
                        </td>
                        <td class="px-5 py-3 text-right text-xs">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('pendataan.potensi.edit', ['potensi' => $p->pivot->id, 'redirect_to' => route('master.item-komoditas.show', $item)]) }}" class="text-amber-600 hover:underline font-medium">
                                    Edit Produksi
                                </a>
                                <a href="{{ route('pendataan.petani.show', $p) }}" class="text-blue-600 hover:underline">
                                    Profil Petani &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-sm text-paper-400">
                            Belum ada petani yang terhubung menghasilkan item komoditas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

</x-layouts.app>

