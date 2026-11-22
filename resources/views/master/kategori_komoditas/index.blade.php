<x-layouts.app :title="$title" eyebrow="Tingkat 1 Hierarki Komoditas">

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
            <h2 class="text-xl font-display font-semibold text-ink-900">Kategori Komoditas</h2>
            <p class="text-xs text-paper-500 mt-0.5">Kelompok besar komoditas (Pertanian, Peternakan, Perikanan, Ekraf).</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
            <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari kategori..."
                       class="w-full sm:w-48 rounded-lg border border-paper-300 bg-white px-3 py-1.5 text-xs focus:outline-none">
                <button class="rounded-lg bg-ink-900 text-white px-3 py-1.5 text-xs font-medium hover:bg-ink-800">Cari</button>
            </form>
            <a href="{{ route('master.kategori-komoditas.create') }}" class="inline-flex justify-center items-center rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold px-4 py-2 text-xs shadow-sm transition">
                + Tambah Kategori
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-paper-200 bg-white shadow-sm">
        <table class="w-full min-w-[600px] text-left text-sm">
            <thead class="border-b border-paper-200 bg-paper-50 font-mono text-[11px] uppercase tracking-wide text-ink-600">
                <tr>
                    <th class="px-5 py-3">Kode</th>
                    <th class="px-5 py-3">Nama Kategori</th>
                    <th class="px-5 py-3">Deskripsi</th>
                    <th class="px-5 py-3 text-center">Jumlah Master</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-paper-100">
                @forelse($items as $kat)
                    <tr class="hover:bg-paper-50/60">
                        <td class="px-5 py-3 font-mono text-xs text-paper-500">{{ $kat->kode }}</td>
                        <td class="px-5 py-3 font-semibold text-ink-900">{{ $kat->nama }}</td>
                        <td class="px-5 py-3 text-xs text-paper-600">{{ $kat->deskripsi ?: '-' }}</td>
                        <td class="px-5 py-3 text-center">
                            <a href="{{ route('master.master-komoditas.index', ['kategori_id' => $kat->id]) }}" class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-paper-100 text-paper-800 hover:bg-paper-200">
                                {{ $kat->master_komoditas_count }} Master
                            </a>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold {{ $kat->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                {{ $kat->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right text-xs space-x-2">
                            <a href="{{ route('master.kategori-komoditas.edit', $kat) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('master.kategori-komoditas.destroy', $kat) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-paper-400">Belum ada kategori komoditas.</td>
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

