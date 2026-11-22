<x-layouts.app :title="$title" eyebrow="Informasi Detail Petani">

    <div class="mb-5 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('pendataan.petani.index') }}" class="text-xs text-paper-500 hover:text-ink-900">&larr; Kembali ke Daftar Petani</a>
            </div>
            <h2 class="text-2xl font-display font-semibold text-ink-900 mt-1">{{ $petani->nama_lengkap }}</h2>
            <p class="text-xs text-paper-500">Petani / Produsen Komoditas Desa {{ $petani->desa ?: $petani->wilayah?->nama }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pendataan.petani.edit', $petani) }}" class="px-3 py-1.5 rounded-lg border border-paper-300 text-xs font-medium hover:bg-paper-50">
                Edit Profil
            </a>
            <a href="{{ route('pendataan.potensi.create') }}" class="px-3 py-1.5 rounded-lg bg-padi-500 text-ink-950 text-xs font-semibold hover:bg-padi-600 shadow-sm">
                + Tambah Komoditas Produksi
            </a>
        </div>
    </div>

    {{-- Profil Petani --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="p-5 rounded-xl bg-white border border-paper-200 shadow-sm md:col-span-1">
            <h3 class="text-xs font-mono uppercase tracking-wider text-paper-400 font-semibold mb-3">Biodata Petani</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-xs text-paper-400 block">Nama Lengkap</span>
                    <strong class="text-ink-900">{{ $petani->nama_lengkap }}</strong>
                </div>
                <div>
                    <span class="text-xs text-paper-400 block">Nomor HP / WhatsApp</span>
                    <span class="font-mono text-ink-800">{{ $petani->nomor_hp ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-paper-400 block">Alamat</span>
                    <p class="text-ink-800">{{ $petani->alamat ?: '-' }}</p>
                    <p class="text-xs text-paper-500">RT {{ $petani->rt ?: '00' }} / RW {{ $petani->rw ?: '00' }}</p>
                </div>
                <div class="pt-2 border-t border-paper-100">
                    <span class="text-xs text-paper-400 block">Wilayah Administrasi</span>
                    <p class="text-ink-900 font-medium">Desa {{ $petani->desa ?: $petani->wilayah?->nama }}</p>
                    <p class="text-xs text-paper-500">Kec. {{ $petani->kecamatan ?: $petani->wilayah?->parent?->nama }}, Kab. {{ $petani->kabupaten ?: 'Pati' }}</p>
                </div>
            </div>
        </div>

        {{-- Daftar Seluruh Komoditas yang Diproduksinya --}}
        <div class="md:col-span-2 bg-white rounded-xl border border-paper-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-paper-200 bg-paper-50/50 flex items-center justify-between">
                <h3 class="text-sm font-display font-semibold text-ink-900 uppercase tracking-wide flex items-center gap-2">
                    <span>🌾</span> Seluruh Komoditas yang Diproduksi Petani Ini
                </h3>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-sawah-100 text-sawah-800">
                    {{ $komoditasList->count() }} Komoditas
                </span>
            </div>

            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-paper-200 bg-paper-50 font-mono text-[11px] uppercase tracking-wide text-ink-600">
                        <tr>
                            <th class="px-5 py-3">Komoditas (Item)</th>
                            <th class="px-5 py-3">Master & Kategori</th>
                            <th class="px-5 py-3">Luas Lahan</th>
                            <th class="px-5 py-3">Estimasi Panen</th>
                            <th class="px-5 py-3">Siklus Panen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-paper-100">
                        @forelse($komoditasList as $item)
                            <tr class="hover:bg-paper-50/60">
                                <td class="px-5 py-3 font-semibold text-ink-900">
                                    <a href="{{ route('master.item-komoditas.show', $item) }}" class="text-blue-700 hover:underline">
                                        {{ $item->nama }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-xs text-paper-700">
                                    <strong>{{ $item->masterKomoditas?->nama }}</strong>
                                    <span class="block text-[10px] text-paper-400">{{ $item->masterKomoditas?->kategori?->nama }}</span>
                                </td>
                                <td class="px-5 py-3 text-xs font-mono text-paper-700">
                                    {{ $item->pivot->luas_lahan_ha ? $item->pivot->luas_lahan_ha . ' Ha' : '-' }}
                                </td>
                                <td class="px-5 py-3 text-xs font-semibold text-sawah-900 font-mono">
                                    {{ number_format($item->pivot->estimasi_volume_panen) }} {{ $item->pivot->satuan }}
                                </td>
                                <td class="px-5 py-3 text-xs text-paper-600">
                                    {{ $item->pivot->periode_panen ?: 'Musiman' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-sm text-paper-400">
                                    Petani ini belum memiliki data komoditas yang diproduksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>

