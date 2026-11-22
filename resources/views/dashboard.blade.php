<x-layouts.app title="Dashboard Sistem KDMP" eyebrow="Ringkasan Penduduk & Potensi Desa">

    {{-- Header Banner Fokus 1 Desa --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-xl border border-paper-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-padi-100 text-padi-900 border border-padi-300">
                    🏡 WILAYAH OPERASIONAL: {{ strtoupper($desaAktif?->nama ?? 'DESA AKTIF') }}
                </span>
                <span class="text-xs text-paper-500 font-mono">
                    Kec. {{ $desaAktif?->parent?->nama ?? 'Juwana' }}, Kab. {{ $desaAktif?->parent?->parent?->nama ?? 'Pati' }}
                </span>
            </div>
            <h2 class="font-display text-2xl font-semibold text-ink-900">Dashboard Pemetaan Potensi {{ $desaAktif?->nama ?? 'Desa' }}</h2>
            <p class="text-xs text-paper-500 mt-1">Sistem informasi demografi penduduk, komoditas unggulan desa, dan identifikasi potensi barter antar koperasi.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('survei.formulir.komoditas') }}" class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold bg-padi-500 text-ink-950 hover:bg-padi-600 shadow-sm transition">
                + Survei Komoditas
            </a>
            <a href="{{ route('laporan.analisis.index') }}" class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold bg-ink-900 text-white hover:bg-ink-800 shadow-sm transition">
                📊 Analisis Barter Desa
            </a>
        </div>
    </div>

    {{-- Metric Cards Utama --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Penduduk Desa Ini --}}
        <div class="bg-white p-5 rounded-xl border border-paper-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium uppercase tracking-wider text-paper-500">Total Penduduk Desa</span>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-lg text-lg">👥</span>
            </div>
            <p class="text-3xl font-display font-bold text-ink-900">{{ number_format($demografi['total_penduduk']) }}</p>
            <p class="text-xs text-paper-400 mt-1">Jiwa terdata di {{ $desaAktif?->nama ?? 'desa ini' }}</p>
        </div>

        {{-- Petani & Produsen Desa Ini --}}
        <div class="bg-white p-5 rounded-xl border border-paper-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium uppercase tracking-wider text-paper-500">Petani / Produsen</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg text-lg">👨‍🌾</span>
            </div>
            <p class="text-3xl font-display font-bold text-ink-900">{{ number_format($jumlahPetani) }}</p>
            <p class="text-xs text-paper-400 mt-1">Produsen aktif di {{ $desaAktif?->nama ?? 'desa ini' }}</p>
        </div>

        {{-- Master Komoditas --}}
        <div class="bg-white p-5 rounded-xl border border-paper-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium uppercase tracking-wider text-paper-500">Master Komoditas</span>
                <span class="p-2 bg-amber-50 text-amber-600 rounded-lg text-lg">🌾</span>
            </div>
            <p class="text-3xl font-display font-bold text-ink-900">{{ number_format($jumlahMaster) }}</p>
            <p class="text-xs text-paper-400 mt-1">Terbagi dalam {{ $jumlahKategori }} Kategori</p>
        </div>

        {{-- Item / Subjenis --}}
        <div class="bg-white p-5 rounded-xl border border-paper-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium uppercase tracking-wider text-paper-500">Subjenis Komoditas</span>
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg text-lg">📦</span>
            </div>
            <p class="text-3xl font-display font-bold text-ink-900">{{ number_format($jumlahItem) }}</p>
            <p class="text-xs text-paper-400 mt-1">Varian item spesifik terdata</p>
        </div>
    </div>

    {{-- Seksi Distribusi 5 Kelompok Umur --}}
    <div class="mb-6 bg-white p-6 rounded-xl border border-paper-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display text-lg font-semibold text-ink-900">Distribusi Penduduk Berdasarkan 5 Kelompok Umur</h3>
                <p class="text-xs text-paper-500">Klasifikasi kelompok umur desa {{ $desaAktif?->nama ?? '' }} sesuai standar non-overlapping.</p>
            </div>
            <a href="{{ route('pendataan.penduduk.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Kelola Penduduk &rarr;</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
            <div class="p-4 rounded-lg bg-pink-50 border border-pink-100 text-center">
                <p class="text-xs font-medium text-pink-700 uppercase tracking-wide">Balita (0–4 th)</p>
                <p class="text-2xl font-display font-bold text-pink-900 mt-1">{{ number_format($demografi['balita']) }}</p>
                <p class="text-[11px] text-pink-600 mt-0.5">Jiwa</p>
            </div>
            <div class="p-4 rounded-lg bg-amber-50 border border-amber-100 text-center">
                <p class="text-xs font-medium text-amber-700 uppercase tracking-wide">Anak (5–9 th)</p>
                <p class="text-2xl font-display font-bold text-amber-900 mt-1">{{ number_format($demografi['anak']) }}</p>
                <p class="text-[11px] text-amber-600 mt-0.5">Jiwa</p>
            </div>
            <div class="p-4 rounded-lg bg-cyan-50 border border-cyan-100 text-center">
                <p class="text-xs font-medium text-cyan-700 uppercase tracking-wide">Remaja (10–19 th)</p>
                <p class="text-2xl font-display font-bold text-cyan-900 mt-1">{{ number_format($demografi['remaja']) }}</p>
                <p class="text-[11px] text-cyan-600 mt-0.5">Jiwa</p>
            </div>
            <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-100 text-center">
                <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Dewasa (20–59 th)</p>
                <p class="text-2xl font-display font-bold text-emerald-900 mt-1">{{ number_format($demografi['dewasa']) }}</p>
                <p class="text-[11px] text-emerald-600 mt-0.5">Jiwa</p>
            </div>
            <div class="p-4 rounded-lg bg-purple-50 border border-purple-100 text-center">
                <p class="text-xs font-medium text-purple-700 uppercase tracking-wide">Lansia (60+ th)</p>
                <p class="text-2xl font-display font-bold text-purple-900 mt-1">{{ number_format($demografi['lansia']) }}</p>
                <p class="text-[11px] text-purple-600 mt-0.5">Jiwa</p>
            </div>
        </div>
    </div>

    {{-- Grid 2 Kolom: Komoditas Unggulan Desa & Hierarki Kategori --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Komoditas Unggulan Desa Ini --}}
        <div class="bg-white p-6 rounded-xl border border-paper-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-display text-lg font-semibold text-ink-900">Komoditas Unggulan: {{ $desaAktif?->nama ?? 'Desa' }}</h3>
                    <p class="text-xs text-paper-500">Estimasi volume panen komoditas yang diproduksi petani desa.</p>
                </div>
                <a href="{{ route('pendataan.potensi.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua &rarr;</a>
            </div>

            @if($daftarKomoditasUnggulan->isEmpty())
                <p class="text-sm text-paper-400 py-6 text-center">Belum ada data produksi komoditas di desa ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-paper-200 text-xs font-mono uppercase text-paper-500">
                            <tr>
                                <th class="pb-2">Subjenis Komoditas</th>
                                <th class="pb-2">Kategori</th>
                                <th class="pb-2 text-center">Petani</th>
                                <th class="pb-2 text-right">Est. Hasil Panen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-paper-100">
                            @foreach($daftarKomoditasUnggulan as $row)
                                <tr class="hover:bg-paper-50 text-xs">
                                    <td class="py-2.5 font-medium text-ink-900">
                                        {{ $row->nama_item }}
                                        <span class="block text-[10px] text-paper-400 font-normal">Master: {{ $row->nama_master }}</span>
                                    </td>
                                    <td class="py-2.5 text-paper-600">
                                        <span class="px-2 py-0.5 rounded text-[10px] bg-paper-100 font-mono">{{ $row->nama_kategori }}</span>
                                    </td>
                                    <td class="py-2.5 text-center font-mono font-medium">{{ $row->total_petani }} org</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-emerald-800">
                                        {{ number_format($row->total_volume) }} {{ $row->satuan }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Sebaran Kategori Komoditas --}}
        <div class="bg-white p-6 rounded-xl border border-paper-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-display text-lg font-semibold text-ink-900">Kategori & Master Komoditas</h3>
                    <p class="text-xs text-paper-500">Struktur hierarki komoditas yang terdaftar pada sistem.</p>
                </div>
                <a href="{{ route('master.kategori-komoditas.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Master Kategori &rarr;</a>
            </div>

            <div class="space-y-3">
                @foreach($sebaranKategori as $kat)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-paper-50 border border-paper-200/80">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-padi-100 text-padi-800 flex items-center justify-center font-display font-semibold text-xs">
                                {{ substr($kat->kode, 0, 2) }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-ink-900">{{ $kat->nama }}</p>
                                <p class="text-xs text-paper-400">{{ $kat->deskripsi ?: 'Kategori komoditas' }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-white border border-paper-300 text-paper-700">
                            {{ $kat->master_komoditas_count }} Master
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</x-layouts.app>
