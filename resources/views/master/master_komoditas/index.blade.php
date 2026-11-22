<x-layouts.app :title="$title" eyebrow="Tingkat 2 Hierarki Komoditas">

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
            <h2 class="text-xl font-display font-semibold text-ink-900">Master Komoditas</h2>
            <p class="text-xs text-paper-500 mt-0.5">Kelompok komoditas utama (Contoh: Beras, Cabai, Bawang, Kopi, Ayam, Ikan, Garam).</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
            <form method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto">
                <select name="kategori_id" class="w-full sm:w-auto rounded-lg border border-paper-300 bg-white px-3 py-1.5 text-xs text-ink-800 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" @selected($kategoriId == $kat->id)>{{ $kat->nama }}</option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari master komoditas..."
                       class="w-full sm:w-44 rounded-lg border border-paper-300 bg-white px-3 py-1.5 text-xs focus:outline-none">
                <button class="w-full sm:w-auto rounded-lg bg-ink-900 text-white px-3 py-1.5 text-xs font-medium hover:bg-ink-800">Filter</button>
            </form>
            <a href="{{ route('master.master-komoditas.create') }}" class="inline-flex justify-center items-center rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold px-4 py-2 text-xs shadow-sm transition">
                + Tambah Master
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-paper-200 bg-white shadow-sm">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="border-b border-paper-200 bg-paper-50 font-mono text-[11px] uppercase tracking-wide text-ink-600">
                <tr>
                    <th class="px-5 py-3">Kode</th>
                    <th class="px-5 py-3">Master Komoditas</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3 text-center">Subjenis / Item</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-paper-100">
                @forelse($items as $m)
                    <tr class="hover:bg-paper-50/60">
                        <td class="px-5 py-3 font-mono text-xs text-paper-500">{{ $m->kode }}</td>
                        <td class="px-5 py-3 font-semibold text-ink-900">{{ $m->nama }}</td>
                        <td class="px-5 py-3 text-xs text-paper-700">
                            <span class="inline-flex px-2 py-0.5 rounded bg-paper-100 font-medium">
                                {{ $m->kategori?->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <a href="{{ route('master.item-komoditas.index', ['master_id' => $m->id]) }}" class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100">
                                {{ $m->items_count }} Item
                            </a>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold {{ $m->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                {{ $m->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right text-xs space-x-2">
                            <a href="{{ route('master.master-komoditas.edit', $m) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('master.master-komoditas.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Hapus master komoditas ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-paper-400">Belum ada master komoditas.</td>
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

