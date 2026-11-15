<x-layouts.app title="Dashboard Ngobar" eyebrow="Daftar Menu">
    
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="font-display text-2xl font-semibold text-paper-900">Daftar Menu Akses</h2>
            <p class="text-sm text-paper-500 mt-1">Pilih fitur untuk mengelola data survei dan master entitas Anda.</p>
        </div>
        <div class="text-sm font-medium text-paper-500 bg-white px-4 py-2 rounded-lg border border-paper-200 shadow-sm">
            Tahun: {{ date('Y') }}
        </div>
    </div>

    @php
        $menuCards = [
            // SURVEI
            ['group' => 'Survei', 'title' => 'Dashboard Survei', 'route' => route('survei.dashboard.index'), 'color' => 'bg-indigo-600', 'icon' => '📊'],
            ['group' => 'Survei', 'title' => 'Sesi Survei', 'route' => route('survei.sesi.index'), 'color' => 'bg-indigo-600', 'icon' => '📋'],
            ['group' => 'Survei', 'title' => 'Data RT', 'route' => route('survei.rt.index'), 'color' => 'bg-indigo-600', 'icon' => '🏘️'],
            ['group' => 'Survei', 'title' => 'Produsen Desa', 'route' => route('survei.produsen.index'), 'color' => 'bg-indigo-600', 'icon' => '🌾'],
            ['group' => 'Survei', 'title' => 'Validasi Survei', 'route' => route('survei.validasi.index'), 'color' => 'bg-indigo-600', 'icon' => '✅'],
            ['group' => 'Survei', 'title' => 'Bank Pertanyaan', 'route' => route('survei.pertanyaan.index'), 'color' => 'bg-indigo-600', 'icon' => '❓'],

            // MASTER
            ['group' => 'Master', 'title' => 'Entitas', 'route' => route('master.entitas.index'), 'color' => 'bg-blue-600', 'icon' => '🏢', 'super_admin_only' => true],
            ['group' => 'Master', 'title' => 'Komoditas', 'route' => route('master.komoditas.index'), 'color' => 'bg-blue-600', 'icon' => '🌱'],
            ['group' => 'Master', 'title' => 'Satuan Ukur', 'route' => route('master.satuan.index'), 'color' => 'bg-blue-600', 'icon' => '📏'],
            ['group' => 'Master', 'title' => 'Barang', 'route' => route('master.barang.index'), 'color' => 'bg-blue-600', 'icon' => '📦'],
            ['group' => 'Master', 'title' => 'Pihak / Mitra', 'route' => route('master.pihak.index'), 'color' => 'bg-blue-600', 'icon' => '🤝'],
            ['group' => 'Master', 'title' => 'Gudang', 'route' => route('master.gudang.index'), 'color' => 'bg-blue-600', 'icon' => '🏬'],
            ['group' => 'Master', 'title' => 'Kas & Bank', 'route' => route('master.kas-bank.index'), 'color' => 'bg-blue-600', 'icon' => '🏦'],
            ['group' => 'Master', 'title' => 'Periode Akuntansi', 'route' => route('master.periode.index'), 'color' => 'bg-blue-600', 'icon' => '📅'],
            ['group' => 'Master', 'title' => 'Chart of Accounts', 'route' => route('master.coa.index'), 'color' => 'bg-blue-600', 'icon' => '📜'],
        ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @foreach($menuCards as $menu)
            @continue(! empty($menu['super_admin_only']) && auth()->user()?->id_entitas !== null)
            <a href="{{ $menu['route'] }}" class="group relative flex flex-col items-center justify-center rounded-[14px] {{ $menu['color'] }} p-4 text-center shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg hover:brightness-110 min-h-[140px]">
                <div class="mb-3 flex items-center justify-center text-3xl opacity-90 group-hover:scale-110 transition-transform">
                    {{ $menu['icon'] }}
                </div>
                <h4 class="font-display text-[10px] uppercase tracking-wider text-white/80 font-medium mb-0.5">{{ $menu['group'] }}</h4>
                <h3 class="font-display text-[13px] font-semibold text-white leading-tight">{{ $menu['title'] }}</h3>
            </a>
        @endforeach
    </div>

</x-layouts.app>
