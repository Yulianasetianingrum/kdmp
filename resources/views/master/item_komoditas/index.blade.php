<x-layouts.app :title="$title" eyebrow="Tingkat 3 Hierarki Komoditas">

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

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <select name="master_id" class="rounded-lg border border-paper-300 bg-white px-3 py-1.5 text-xs text-ink-800 focus:outline-none">
                    <option value="">Semua Master</option>
                    @foreach($masterList as $m)
                        <option value="{{ $m->id }}" @selected($masterId == $m->id)>{{ $m->nama }} ({{ $m->kategori?->nama }})</option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari item komoditas..."
                       class="w-44 rounded-lg border border-paper-300 bg-white px-3 py-1.5 text-xs focus:outline-none">
                <button class="rounded-lg bg-ink-900 text-white px-3 py-1.5 text-xs font-medium hover:bg-ink-800">Filter</button>
            </form>
            <a href="{{ route('master.item-komoditas.create') }}" class="rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold px-4 py-2 text-xs shadow-sm transition">
                + Tambah Item
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-paper-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
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
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $it->petani_count }} Petani
                            </span>
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

</x-layouts.app>

