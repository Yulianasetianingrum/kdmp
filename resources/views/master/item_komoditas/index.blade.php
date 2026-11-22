<x-layouts.app :title="$title" eyebrow="Tingkat 3 Hierarki Komoditas">

    <div x-data="{
        modalOpen: false,
        selectedItem: '',
        selectedMaster: '',
        selectedDetailUrl: '',
        petaniList: [],
        openPetani(itemNama, masterNama, detailUrl, list) {
            this.selectedItem = itemNama;
            this.selectedMaster = masterNama;
            this.selectedDetailUrl = detailUrl;
            this.petaniList = list;
            this.modalOpen = true;
        }
    }" @keydown.escape.window="modalOpen = false">

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-semibold text-ink-900">Item / Subjenis Komoditas</h2>
                <p class="text-xs text-paper-500 mt-0.5">Varian spesifik komoditas (Contoh: Beras Mentik, Cabai Rawit, Bawang Merah, Kopi Robusta).</p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                <form method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto">
                    <select name="master_id" class="w-full sm:w-auto rounded-lg border border-paper-300 bg-white px-3 py-1.5 text-xs text-ink-800 focus:outline-none">
                        <option value="">Semua Master</option>
                        @foreach($masterList as $m)
                            <option value="{{ $m->id }}" @selected($masterId == $m->id)>{{ $m->nama }} ({{ $m->kategori?->nama }})</option>
                        @endforeach
                    </select>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari item komoditas..."
                           class="w-full sm:w-44 rounded-lg border border-paper-300 bg-white px-3 py-1.5 text-xs focus:outline-none">
                    <button class="w-full sm:w-auto rounded-lg bg-ink-900 text-white px-3 py-1.5 text-xs font-medium hover:bg-ink-800">Filter</button>
                </form>
                <a href="{{ route('master.item-komoditas.create') }}" class="inline-flex justify-center items-center rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold px-4 py-2 text-xs shadow-sm transition">
                    + Tambah Item
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-paper-200 bg-white shadow-sm">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="border-b border-paper-200 bg-paper-50 font-mono text-[11px] uppercase tracking-wide text-ink-600">
                    <tr>
                        <th class="px-5 py-3">Kode</th>
                        <th class="px-5 py-3">Nama Subjenis / Item</th>
                        <th class="px-5 py-3">Master Komoditas</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Satuan</th>
                        <th class="px-5 py-3 text-center">Petani</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paper-100">
                    @forelse($items as $it)
                        <tr class="hover:bg-paper-50/60">
                            <td class="px-5 py-3 font-mono text-xs text-paper-500">{{ $it->kode }}</td>
                            <td class="px-5 py-3">
                                <a href="{{ route('master.item-komoditas.show', $it) }}" class="font-semibold text-blue-700 hover:underline">
                                    {{ $it->nama }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-xs text-paper-800 font-medium">{{ $it->masterKomoditas?->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-xs text-paper-600">
                                <span class="inline-flex px-2 py-0.5 rounded bg-paper-100 text-[11px]">
                                    {{ $it->masterKomoditas?->kategori?->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-xs font-mono text-paper-600">{{ $it->satuan_standar }}</td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $petaniJson = $it->petani->map(function ($p) {
                                        $rtRw = [];
                                        if ($p->rt) $rtRw[] = 'RT ' . $p->rt;
                                        if ($p->rw) $rtRw[] = 'RW ' . $p->rw;
                                        $rtRwStr = !empty($rtRw) ? implode(' / ', $rtRw) : '-';

                                        $wilayahDesa = $p->desa ?: ($p->wilayah?->nama ?: '');
                                        $wilayahKec = $p->kecamatan ?: ($p->wilayah?->parent?->nama ?: '');
                                        $wilayahKab = $p->kabupaten ?: '';

                                        return [
                                            'id' => $p->id,
                                            'nama' => $p->nama_lengkap,
                                            'nomor_hp' => $p->nomor_hp ?: '-',
                                            'alamat' => $p->alamat ?: '-',
                                            'rt_rw' => $rtRwStr,
                                            'desa' => $wilayahDesa ?: '-',
                                            'kecamatan' => $wilayahKec ?: '-',
                                            'kabupaten' => $wilayahKab ?: '-',
                                            'estimasi_panen' => $p->pivot?->estimasi_volume_panen ? number_format($p->pivot->estimasi_volume_panen, 0, ',', '.') . ' ' . ($p->pivot->satuan ?? 'kg') : '-',
                                            'periode' => $p->pivot?->periode_panen ?: '-',
                                            'show_url' => route('pendataan.petani.show', $p),
                                        ];
                                    });
                                @endphp

                                <button type="button"
                                        @click='openPetani(@json($it->nama), @json($it->masterKomoditas?->nama ?? "-"), @json(route("master.item-komoditas.show", $it)), @json($petaniJson))'
                                        class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $it->petani_count > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 hover:border-emerald-300' : 'bg-paper-100 text-paper-600 border border-paper-200 hover:bg-paper-200' }} transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                        title="Klik untuk melihat daftar petani penghasil {{ $it->nama }}">
                                    @if($it->petani_count > 0)
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 group-hover:scale-125 transition-transform"></span>
                                    @endif
                                    <span>{{ $it->petani_count }} Petani</span>
                                </button>
                            </td>
                            <td class="px-5 py-3 text-right text-xs space-x-2">
                                <a href="{{ route('master.item-komoditas.show', $it) }}" class="text-emerald-700 hover:underline font-medium">Detail</a>
                                <a href="{{ route('master.item-komoditas.edit', $it) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('master.item-komoditas.destroy', $it) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item komoditas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-sm text-paper-400">Belum ada item / subjenis komoditas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($items->hasPages())
                <div class="p-4 border-t border-paper-200">
                    {{ $items->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Daftar Petani Produsen --}}
        <div x-cloak
             x-show="modalOpen"
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog"
             aria-modal="true">
            <!-- Backdrop -->
            <div x-show="modalOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalOpen = false"
                 class="fixed inset-0 bg-ink-950/50 backdrop-blur-sm transition-opacity"></div>

            <!-- Dialog Box -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div x-show="modalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all border border-paper-200 flex flex-col max-h-[88vh]">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-paper-200 bg-paper-50 flex items-center justify-between shrink-0">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-display font-semibold text-ink-900">
                                    Daftar Petani Produsen
                                </h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                      x-text="petaniList.length + ' Petani Terdaftar'"></span>
                            </div>
                            <p class="text-xs text-paper-600 mt-1">
                                Komoditas: <span class="font-semibold text-ink-900" x-text="selectedItem"></span>
                                <span class="text-paper-400 mx-1">&bull;</span>
                                Master: <span class="text-paper-700" x-text="selectedMaster"></span>
                            </p>
                        </div>
                        <button type="button"
                                @click="modalOpen = false"
                                class="rounded-lg p-1.5 text-paper-400 hover:text-ink-800 hover:bg-paper-200 transition focus:outline-none"
                                aria-label="Tutup modal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto flex-1">
                        <!-- If Empty -->
                        <template x-if="petaniList.length === 0">
                            <div class="text-center py-12 px-4">
                                <div class="mx-auto w-12 h-12 rounded-full bg-paper-100 flex items-center justify-center text-paper-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-sm font-semibold text-ink-900">Belum Ada Data Petani</h4>
                                <p class="text-xs text-paper-500 mt-1 max-w-sm mx-auto">
                                    Belum ada data petani yang terdaftar memproduksi komoditas ini. Petani dapat dihubungkan melalui menu pendataan potensi produksi petani.
                                </p>
                            </div>
                        </template>

                        <!-- If List Available -->
                        <template x-if="petaniList.length > 0">
                            <div class="overflow-x-auto rounded-xl border border-paper-200">
                                <table class="w-full text-left text-xs">
                                    <thead class="border-b border-paper-200 bg-paper-50 font-mono text-[11px] uppercase tracking-wide text-ink-600">
                                        <tr>
                                            <th class="px-4 py-3 w-10 text-center">No</th>
                                            <th class="px-4 py-3">Nama Petani</th>
                                            <th class="px-4 py-3">No. HP / Kontak</th>
                                            <th class="px-4 py-3">Alamat & RT / RW</th>
                                            <th class="px-4 py-3">Wilayah (Desa, Kec)</th>
                                            <th class="px-4 py-3">Estimasi Produksi</th>
                                            <th class="px-4 py-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-paper-100">
                                        <template x-for="(p, idx) in petaniList" :key="p.id">
                                            <tr class="hover:bg-paper-50/60 transition">
                                                <td class="px-4 py-3 text-center font-mono text-paper-400" x-text="idx + 1"></td>
                                                <td class="px-4 py-3 font-semibold text-ink-900">
                                                    <a :href="p.show_url" class="text-blue-700 hover:underline inline-flex items-center gap-1" x-text="p.nama"></a>
                                                </td>
                                                <td class="px-4 py-3 font-mono">
                                                    <template x-if="p.nomor_hp !== '-'">
                                                        <a :href="'tel:' + p.nomor_hp" class="text-ink-800 hover:text-emerald-700 hover:underline" x-text="p.nomor_hp"></a>
                                                    </template>
                                                    <template x-if="p.nomor_hp === '-'">
                                                        <span class="text-paper-400">-</span>
                                                    </template>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-ink-800" x-text="p.alamat"></div>
                                                    <div class="mt-0.5 inline-flex items-center px-1.5 py-0.5 rounded bg-paper-100 text-[10px] font-mono text-paper-700 border border-paper-200" x-text="p.rt_rw"></div>
                                                </td>
                                                <td class="px-4 py-3 text-paper-700">
                                                    <div class="font-medium" x-text="p.desa"></div>
                                                    <div class="text-[11px] text-paper-500" x-text="'Kec. ' + p.kecamatan + (p.kabupaten !== '-' ? ', ' + p.kabupaten : '')"></div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="font-mono font-medium text-ink-900" x-text="p.estimasi_panen"></div>
                                                    <div class="text-[11px] text-paper-500" x-text="'Siklus: ' + p.periode"></div>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <a :href="p.show_url" class="inline-flex items-center text-xs font-medium text-emerald-700 hover:text-emerald-800 hover:underline">
                                                        Detail Petani &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-3 border-t border-paper-200 bg-paper-50 flex items-center justify-between shrink-0">
                        <a :href="selectedDetailUrl" class="text-xs text-blue-700 hover:underline font-medium inline-flex items-center gap-1">
                            Halaman Detail Komoditas Lengkap &rarr;
                        </a>
                        <button type="button"
                                @click="modalOpen = false"
                                class="rounded-lg border border-paper-300 bg-white px-4 py-1.5 text-xs font-medium text-ink-800 hover:bg-paper-100 transition focus:outline-none">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
