<x-layouts.app :title="$title" eyebrow="Modul Pendataan">

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-padi-100 text-padi-800">
                    🏡 {{ $desaAktif?->nama ?? 'Desa' }}
                </span>
                <span class="text-xs text-paper-500 font-mono">
                    Kec. {{ $desaAktif?->parent?->nama ?? 'Juwana' }}, Kab. {{ $desaAktif?->parent?->parent?->nama ?? 'Pati' }}
                </span>
            </div>
            <h2 class="text-xl font-display font-semibold text-ink-900 mt-1">Data Petani & Produsen: {{ $desaAktif?->nama ?? 'Desa' }}</h2>
            <p class="text-xs text-paper-500">Identitas produsen lokal desa beserta komoditas yang dihasilkannya.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama / alamat / HP..."
                       class="w-48 rounded-lg border border-paper-300 bg-white px-3 py-1.5 text-xs focus:outline-none">
                <button class="rounded-lg bg-ink-900 text-white px-3 py-1.5 text-xs font-medium hover:bg-ink-800">Cari</button>
                @if($search)
                    <a href="{{ route('pendataan.petani.index') }}" class="text-xs text-paper-500 hover:text-ink-900">Reset</a>
                @endif
            </form>
            <a href="{{ route('pendataan.petani.create') }}" class="rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold px-4 py-2 text-xs shadow-sm transition">
                + Tambah Petani
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-paper-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-paper-200 bg-paper-50 font-mono text-[11px] uppercase tracking-wide text-ink-600">
                <tr>
                    <th class="px-5 py-3">Nama Petani</th>
                    <th class="px-5 py-3">Kontak (HP)</th>
                    <th class="px-5 py-3">Alamat Domisili</th>
                    <th class="px-5 py-3">Komoditas yang Dihasilkan</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-paper-100">
                @forelse($items as $p)
                    <tr class="hover:bg-paper-50/60">
                        <td class="px-5 py-3 font-semibold text-ink-900">
                            <a href="{{ route('pendataan.petani.show', $p) }}" class="text-blue-700 hover:underline">
                                {{ $p->nama_lengkap }}
                            </a>
                        </td>
                        <td class="px-5 py-3 font-mono text-xs text-paper-700">{{ $p->nomor_hp ?: '-' }}</td>
                        <td class="px-5 py-3 text-xs text-paper-600 max-w-xs">
                            {{ $p->alamat ?: '-' }} (RT {{ $p->rt ?: '00' }} / RW {{ $p->rw ?: '00' }})
                        </td>
                        <td class="px-5 py-3">
                            @if($p->items->isEmpty())
                                <span class="text-xs text-paper-400 italic">Belum ada komoditas</span>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @foreach($p->items as $it)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] bg-sawah-50 text-sawah-900 border border-sawah-200">
                                            {{ $it->nama }} ({{ number_format($it->pivot->estimasi_volume_panen) }} {{ $it->pivot->satuan }})
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2 text-xs">
                                <a href="{{ route('pendataan.petani.show', $p) }}" class="text-paper-600 hover:text-ink-900">Detail</a>
                                <a href="{{ route('pendataan.petani.edit', $p) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('pendataan.petani.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus petani ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-paper-400 text-xs">
                            Belum ada data petani terdaftar untuk desa ini. Silakan klik <strong>+ Tambah Petani</strong>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>

</x-layouts.app>
