<x-layouts.app :title="$title" eyebrow="Informasi Detail Komoditas">

    <div class="mb-5 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('master.item-komoditas.index') }}" class="text-xs text-paper-500 hover:text-ink-900">&larr; Kembali ke Daftar Item</a>
            </div>
            <h2 class="text-2xl font-display font-semibold text-ink-900 mt-1">{{ $item->nama }}</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('master.item-komoditas.edit', $item) }}" class="px-3 py-1.5 rounded-lg border border-paper-300 text-xs font-medium hover:bg-paper-50">
                Edit Item
            </a>
            <a href="{{ route('pendataan.potensi.create') }}" class="px-3 py-1.5 rounded-lg bg-padi-500 text-ink-950 text-xs font-semibold hover:bg-padi-600 shadow-sm">
                + Tambah Petani Penghasil
            </a>
        </div>
    </div>

    {{-- Kartu Ringkasan Hierarki --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
            <span>🏘️</span> 4. Desa Penghasil Komoditas Ini
        </h3>

        @if(empty($desaPenghasil))
            <p class="text-xs text-paper-400">Belum ada data desa yang tercatat memproduksi komoditas ini.</p>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($desaPenghasil as $dp)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs bg-sawah-50 text-sawah-900 border border-sawah-200 font-medium">
                        📍 <strong>{{ $dp['desa'] }}</strong> (Kec. {{ $dp['kecamatan'] ?? '-' }}, Kab. {{ $dp['kabupaten'] ?? 'Pati' }})
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 5. Daftar Petani yang Menghasilkan Item Tersebut --}}
    <div class="bg-white rounded-xl border border-paper-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-paper-200 bg-paper-50/50 flex items-center justify-between">
            <h3 class="text-sm font-display font-semibold text-ink-900 uppercase tracking-wide flex items-center gap-2">
                <span>👨‍🌾</span> 5. Daftar Petani / Produsen Penghasil
            </h3>
            <span class="text-xs text-paper-500">{{ $daftarPetani->count() }} Petani Terdaftar</span>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="border-b border-paper-200 bg-paper-50 font-mono text-[11px] uppercase tracking-wide text-ink-600">
                <tr>
                    <th class="px-5 py-3">Nama Petani</th>
                    <th class="px-5 py-3">Kontak (HP)</th>
                    <th class="px-5 py-3">Wilayah (Desa, Kec)</th>
                    <th class="px-5 py-3">Luas Lahan</th>
                    <th class="px-5 py-3">Estimasi Panen</th>
                    <th class="px-5 py-3">Periode Panen</th>
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
                        <td class="px-5 py-3 text-xs font-mono text-paper-700">
                            {{ $p->pivot->luas_lahan_ha ? $p->pivot->luas_lahan_ha . ' Ha' : '-' }}
                        </td>
                        <td class="px-5 py-3 text-xs font-semibold text-sawah-900 font-mono">
                            {{ number_format($p->pivot->estimasi_volume_panen) }} {{ $p->pivot->satuan }}
                        </td>
                        <td class="px-5 py-3 text-xs text-paper-600">
                            {{ $p->pivot->periode_panen ?: 'Musiman' }}
                        </td>
                        <td class="px-5 py-3 text-right text-xs">
                            <a href="{{ route('pendataan.petani.show', $p) }}" class="text-blue-600 hover:underline">
                                Profil Petani &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-paper-400">
                            Belum ada petani yang terhubung menghasilkan item komoditas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.app>

