<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} &mdash; Ngobar Monitor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-[#f8f7f0] font-sans text-ink-900 antialiased"
      x-data="{ 
          sidebarOpen: window.innerWidth >= 1024,
          isMobile: window.innerWidth < 1024,
          lightboxImage: null,
          openLightbox(src) {
              this.lightboxImage = src;
          },
          closeLightbox() {
              this.lightboxImage = null;
          }
      }"
      x-init="
          window.addEventListener('resize', () => {
              isMobile = window.innerWidth < 1024;
              if (!isMobile) sidebarOpen = true;
          });
      ">

    <div class="flex h-full h-[100dvh] overflow-hidden relative">

        <!-- Mobile Backdrop -->
        <div x-cloak
             x-show="sidebarOpen && isMobile"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-ink-950/60 backdrop-blur-sm lg:hidden"></div>

        <!-- SIDEBAR MONITOR REVIEW -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 sm:w-80 bg-white border-r border-paper-200 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shrink-0 shadow-lg lg:shadow-none"
               :class="{
                   '-translate-x-full': isMobile && !sidebarOpen,
                   'translate-x-0': !isMobile || sidebarOpen
               }">
            
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-paper-200 flex items-center justify-between bg-paper-50/70">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-ink-950 text-amber-400 font-bold text-base shadow">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </span>
                    <div class="min-w-0">
                        <h2 class="text-sm font-display font-bold text-ink-900 truncate">Secret Review Panel</h2>
                        <p class="text-[10px] font-mono text-paper-500 uppercase tracking-wider">Pantauan Saran & Review</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-paper-500 hover:text-ink-900 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Stats Ringkasan Mini -->
            <div class="p-3 border-b border-paper-200 bg-white">
                <div class="grid grid-cols-2 gap-2 text-center text-xs">
                    <div class="p-2 rounded-xl bg-paper-50 border border-paper-200">
                        <span class="block font-mono text-base font-bold text-ink-900">{{ $stats['total'] }}</span>
                        <span class="text-[10px] text-paper-500 uppercase font-medium">Total Saran</span>
                    </div>
                    <div class="p-2 rounded-xl bg-rose-50 border border-rose-200">
                        <span class="block font-mono text-base font-bold text-rose-700">{{ $stats['pending'] }}</span>
                        <span class="text-[10px] text-rose-600 uppercase font-medium">Perlu Dikerjakan</span>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-50 border border-amber-200">
                        <span class="block font-mono text-base font-bold text-amber-700">{{ $stats['dikerjakan'] }}</span>
                        <span class="text-[10px] text-amber-600 uppercase font-medium">Diproses</span>
                    </div>
                    <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-200">
                        <span class="block font-mono text-base font-bold text-emerald-700">{{ $stats['selesai'] }}</span>
                        <span class="text-[10px] text-emerald-600 uppercase font-medium">Selesai</span>
                    </div>
                </div>
            </div>

            <!-- Scrollable Nav Links -->
            <div class="flex-1 overflow-y-auto p-3 space-y-4">
                <!-- Filter Status -->
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-paper-500 px-2 mb-1.5">Filter Status</p>
                    <div class="space-y-1">
                        <a href="{{ route('secret.index', array_merge(request()->except('status', 'page'))) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ !$selectedStatus ? 'bg-padi-500 text-ink-950 font-semibold shadow-sm' : 'text-paper-700 hover:bg-paper-100' }}">
                            <span>Semua Status</span>
                            <span class="font-mono text-[11px]">{{ $stats['total'] }}</span>
                        </a>
                        <a href="{{ route('secret.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ $selectedStatus === 'pending' ? 'bg-rose-500 text-white font-semibold shadow-sm' : 'text-rose-700 hover:bg-rose-50' }}">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500 {{ $selectedStatus === 'pending' ? 'bg-white' : '' }}"></span>
                                Belum Dikerjakan
                            </span>
                            <span class="font-mono text-[11px]">{{ $stats['pending'] }}</span>
                        </a>
                        <a href="{{ route('secret.index', array_merge(request()->except('status', 'page'), ['status' => 'dikerjakan'])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ $selectedStatus === 'dikerjakan' ? 'bg-amber-500 text-white font-semibold shadow-sm' : 'text-amber-700 hover:bg-amber-50' }}">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500 {{ $selectedStatus === 'dikerjakan' ? 'bg-white' : '' }}"></span>
                                Sedang Dikerjakan
                            </span>
                            <span class="font-mono text-[11px]">{{ $stats['dikerjakan'] }}</span>
                        </a>
                        <a href="{{ route('secret.index', array_merge(request()->except('status', 'page'), ['status' => 'selesai'])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ $selectedStatus === 'selesai' ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-emerald-700 hover:bg-emerald-50' }}">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 {{ $selectedStatus === 'selesai' ? 'bg-white' : '' }}"></span>
                                Sudah Selesai
                            </span>
                            <span class="font-mono text-[11px]">{{ $stats['selesai'] }}</span>
                        </a>
                        <a href="{{ route('secret.index', array_merge(request()->except('has_coretan', 'page'), ['has_coretan' => request('has_coretan') ? null : 1])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ request('has_coretan') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-blue-700 hover:bg-blue-50' }}">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Ada Coretan Gambar
                            </span>
                            <span class="font-mono text-[11px]">{{ $stats['coretan'] }}</span>
                        </a>
                    </div>
                </div>

                <!-- Filter Sumber Area Masukan -->
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-paper-500 px-2 mb-1.5">Sumber Masukan</p>
                    <div class="space-y-1">
                        <a href="{{ route('secret.index', array_merge(request()->except('sumber', 'page'))) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ !$selectedSumber ? 'bg-padi-500 text-ink-950 font-semibold shadow-sm' : 'text-paper-700 hover:bg-paper-100' }}">
                            <span>Semua Sumber</span>
                            <span class="font-mono text-[11px]">{{ $stats['total'] }}</span>
                        </a>
                        <a href="{{ route('secret.index', array_merge(request()->except('sumber', 'page'), ['sumber' => 'dokumen_utama'])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ $selectedSumber === 'dokumen_utama' ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-emerald-800 hover:bg-emerald-50' }}">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 {{ $selectedSumber === 'dokumen_utama' ? 'bg-white' : '' }}"></span>
                                Dokumen Utama
                            </span>
                            <span class="font-mono text-[11px]">{{ $stats['dokumen_utama'] }}</span>
                        </a>
                        <a href="{{ route('secret.index', array_merge(request()->except('sumber', 'page'), ['sumber' => 'dropdown_sidebar'])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ $selectedSumber === 'dropdown_sidebar' ? 'bg-purple-600 text-white font-semibold shadow-sm' : 'text-purple-800 hover:bg-purple-50' }}">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-purple-500 {{ $selectedSumber === 'dropdown_sidebar' ? 'bg-white' : '' }}"></span>
                                Dropdown Sidebar
                            </span>
                            <span class="font-mono text-[11px]">{{ $stats['dropdown_sidebar'] }}</span>
                        </a>
                        <a href="{{ route('secret.index', array_merge(request()->except('sumber', 'page'), ['sumber' => 'sidebar'])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-colors {{ $selectedSumber === 'sidebar' ? 'bg-sky-600 text-white font-semibold shadow-sm' : 'text-sky-800 hover:bg-sky-50' }}">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-sky-500 {{ $selectedSumber === 'sidebar' ? 'bg-white' : '' }}"></span>
                                Sidebar Navigasi
                            </span>
                            <span class="font-mono text-[11px]">{{ $stats['sidebar_nav'] }}</span>
                        </a>
                    </div>
                </div>

                <!-- Filter Berdasarkan Halaman / Bagan yang Direview -->
                <div>
                    <div class="flex items-center justify-between px-2 mb-1.5">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-paper-500">Halaman Di-review</p>
                        @if($selectedPath)
                            <a href="{{ route('secret.index', request()->except('path', 'page')) }}" class="text-[10px] text-blue-600 hover:underline">Reset</a>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('secret.index', request()->except('path', 'page')) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors {{ !$selectedPath ? 'bg-paper-200 text-ink-950 font-semibold' : 'text-paper-700 hover:bg-paper-100' }}">
                            <span class="truncate">Semua Halaman</span>
                            <span class="font-mono text-[10px] text-paper-500">{{ $stats['total'] }}</span>
                        </a>
                        @forelse($pagesList as $p)
                            <a href="{{ route('secret.index', array_merge(request()->except('path', 'page'), ['path' => $p->halaman_path])) }}" 
                               title="{{ $p->judul ?: $p->halaman_path }}"
                               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors {{ $selectedPath === $p->halaman_path ? 'bg-padi-500 text-ink-950 font-semibold shadow-sm' : 'text-paper-700 hover:bg-paper-100' }}">
                                <div class="min-w-0 pr-2">
                                    <p class="truncate font-medium {{ $selectedPath === $p->halaman_path ? 'text-ink-950' : 'text-ink-900' }}">
                                        {{ $p->judul ?: $p->halaman_path }}
                                    </p>
                                    <p class="font-mono text-[10px] text-paper-500 truncate">{{ $p->halaman_path }}</p>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    @if($p->pending_count > 0)
                                        <span class="px-1.5 py-0.5 rounded-full bg-rose-500 text-white font-mono text-[10px] font-bold" title="{{ $p->pending_count }} belum selesai">
                                            {{ $p->pending_count }}
                                        </span>
                                    @endif
                                    <span class="font-mono text-[10px] text-paper-500">({{ $p->total_count }})</span>
                                </div>
                            </a>
                        @empty
                            <p class="px-3 py-2 text-xs text-paper-400 italic">Belum ada halaman yang di-review.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="p-3 border-t border-paper-200 bg-paper-50/50">
                <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl border border-paper-300 bg-white text-xs font-semibold text-ink-800 hover:bg-paper-100 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-paper-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Aplikasi</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-paper-200 bg-white px-3 sm:px-6 py-2.5 sm:py-3 shadow-sm">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="p-2 text-paper-600 hover:text-ink-900 rounded-lg hover:bg-paper-100 lg:hidden shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div>
                        <h1 class="font-display text-sm sm:text-lg font-semibold text-ink-900 leading-tight truncate">
                            Daftar Saran Review
                        </h1>
                        <p class="text-[11px] text-paper-500">
                            @if($selectedPath)
                                Filter Halaman: <code class="font-mono bg-paper-100 px-1 py-0.5 rounded text-ink-800">{{ $selectedPath }}</code>
                            @else
                                Seluruh saran, rekaman suara, komentar bagan, dan coretan layar
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Search Input -->
                <form action="{{ route('secret.index') }}" method="GET" class="flex items-center gap-2">
                    @if($selectedPath) <input type="hidden" name="path" value="{{ $selectedPath }}"> @endif
                    @if($selectedStatus) <input type="hidden" name="status" value="{{ $selectedStatus }}"> @endif
                    <div class="relative hidden sm:block">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}"
                               placeholder="Cari masukan / reviewer..." 
                               class="w-48 md:w-64 rounded-xl border border-paper-300 pl-8 pr-3 py-1.5 text-xs text-ink-900 focus:border-padi-500 focus:outline-none bg-paper-50/50">
                        <svg class="w-4 h-4 text-paper-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <button type="button" onclick="window.location.reload()" title="Refresh" class="p-2 rounded-xl border border-paper-300 text-paper-600 hover:bg-paper-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </form>
            </header>

            <!-- Alerts -->
            @if (session('status'))
                <div class="m-3 sm:m-6 mb-0 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2.5 text-xs text-emerald-800 flex items-center justify-between">
                    <span>{{ session('status') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 font-bold">&times;</button>
                </div>
            @endif

            <!-- Content Area (Card Feed) -->
            <main class="flex-1 overflow-y-auto px-3 sm:px-6 py-4 sm:py-6 space-y-4">
                
                <!-- TABS SEGMENTASI SUMBER AREA (DROPDOWN SIDEBAR vs SIDEBAR NAVIGASI vs LUAR SIDEBAR) -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold select-none">
                    <a href="{{ route('secret.index', request()->except('sumber', 'page')) }}" 
                       class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 shrink-0 {{ !$selectedSumber ? 'bg-ink-950 text-white shadow-sm ring-1 ring-ink-900' : 'bg-white text-paper-700 hover:bg-paper-100 border border-paper-200' }}">
                        <span>Semua Masukan</span>
                        <span class="font-mono text-[11px] px-1.5 py-0.5 rounded-md {{ !$selectedSumber ? 'bg-ink-800 text-white' : 'bg-paper-100 text-paper-600' }}">{{ $stats['total'] }}</span>
                    </a>
                    <a href="{{ route('secret.index', array_merge(request()->except('sumber', 'page'), ['sumber' => 'dropdown_sidebar'])) }}" 
                       class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 shrink-0 {{ $selectedSumber === 'dropdown_sidebar' ? 'bg-purple-600 text-white shadow-sm ring-2 ring-purple-300' : 'bg-white text-purple-700 hover:bg-purple-50 border border-purple-200' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-500 {{ $selectedSumber === 'dropdown_sidebar' ? 'bg-white' : '' }}"></span>
                        <span>Dropdown Sidebar</span>
                        <span class="font-mono text-[11px] px-1.5 py-0.5 rounded-md {{ $selectedSumber === 'dropdown_sidebar' ? 'bg-purple-700 text-white' : 'bg-purple-100 text-purple-700' }}">{{ $stats['dropdown_sidebar'] }}</span>
                    </a>
                    <a href="{{ route('secret.index', array_merge(request()->except('sumber', 'page'), ['sumber' => 'sidebar'])) }}" 
                       class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 shrink-0 {{ $selectedSumber === 'sidebar' ? 'bg-sky-600 text-white shadow-sm ring-2 ring-sky-300' : 'bg-white text-sky-700 hover:bg-sky-50 border border-sky-200' }}">
                        <span class="w-2 h-2 rounded-full bg-sky-500 {{ $selectedSumber === 'sidebar' ? 'bg-white' : '' }}"></span>
                        <span>Sidebar Navigasi</span>
                        <span class="font-mono text-[11px] px-1.5 py-0.5 rounded-md {{ $selectedSumber === 'sidebar' ? 'bg-sky-700 text-white' : 'bg-sky-100 text-sky-700' }}">{{ $stats['sidebar_nav'] }}</span>
                    </a>
                    <a href="{{ route('secret.index', array_merge(request()->except('sumber', 'page'), ['sumber' => 'dokumen_utama'])) }}" 
                       class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 shrink-0 {{ $selectedSumber === 'dokumen_utama' ? 'bg-emerald-600 text-white shadow-sm ring-2 ring-emerald-300' : 'bg-white text-emerald-700 hover:bg-emerald-50 border border-emerald-200' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 {{ $selectedSumber === 'dokumen_utama' ? 'bg-white' : '' }}"></span>
                        <span>Komponen Luar Sidebar (Dokumen Utama)</span>
                        <span class="font-mono text-[11px] px-1.5 py-0.5 rounded-md {{ $selectedSumber === 'dokumen_utama' ? 'bg-emerald-700 text-white' : 'bg-emerald-100 text-emerald-700' }}">{{ $stats['dokumen_utama'] }}</span>
                    </a>
                </div>
                
                @if($masukanList->isEmpty())
                    <div class="bg-white rounded-2xl border border-paper-200 p-8 sm:p-12 text-center max-w-xl mx-auto space-y-4 my-8">
                        <div class="w-16 h-16 bg-padi-100 text-padi-700 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                            <svg class="w-8 h-8 text-padi-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-display font-semibold text-ink-900">Belum Ada Saran Review</h3>
                            <p class="text-xs text-paper-500 max-w-md mx-auto mt-1 leading-relaxed">
                                Reviewer dapat membuka halaman mana saja di aplikasi, lalu melakukan <strong>tap 2x</strong> pada bagian yang ingin diberi saran, atau menggunakan tombol <strong>Pena</strong> untuk mencoret-coret layar.
                            </p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold text-xs shadow-sm transition-all">
                                <span>Mulai Buka Aplikasi & Coba Fitur Review</span>
                                &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    <div x-data="{ 
                            selectedIds: [], 
                            allIds: [{{ $masukanList->pluck('id')->implode(',') }}],
                            toggleAll() {
                                if (this.selectedIds.length === this.allIds.length) {
                                    this.selectedIds = [];
                                } else {
                                    this.selectedIds = [...this.allIds];
                                }
                            }
                         }"
                         class="space-y-4">

                        <!-- TOOLBAR AKSI SERENTAK / HAPUS MASSAL -->
                        <div class="bg-white rounded-xl border border-paper-200 px-3 sm:px-4 py-2.5 flex flex-wrap items-center justify-between gap-2 shadow-sm text-xs">
                            <div class="flex items-center gap-2.5">
                                <label class="flex items-center gap-2 cursor-pointer font-semibold text-ink-900 select-none">
                                    <input type="checkbox" 
                                           :checked="selectedIds.length === allIds.length && allIds.length > 0"
                                           @change="toggleAll()"
                                           class="w-4 h-4 rounded border-paper-300 text-padi-600 focus:ring-padi-500 cursor-pointer">
                                    <span>Pilih Semua di Halaman Ini</span>
                                </label>
                                <span x-show="selectedIds.length > 0" 
                                      x-text="`(${selectedIds.length} dipilih)`"
                                      class="font-mono text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200"></span>
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Tombol Hapus Terpilih -->
                                <form action="{{ route('secret.bulk_destroy') }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Hapus seluruh masukan yang dicentang secara serentak?')"
                                      x-show="selectedIds.length > 0"
                                      class="inline">
                                    @csrf
                                    <template x-for="id in selectedIds" :key="id">
                                        <input type="hidden" name="ids[]" :value="id">
                                    </template>
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold shadow flex items-center gap-1.5 transition-all active:scale-95 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Hapus Terpilih (<span x-text="selectedIds.length"></span>)</span>
                                    </button>
                                </form>

                                <!-- Tombol Kosongkan Semua Masukan -->
                                <form action="{{ route('secret.destroy_all') }}" 
                                      method="POST" 
                                      onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin mengosongkan dan menghapus SELURUH masukan dan coretan review sekaligus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-300 font-bold transition-all flex items-center gap-1.5 cursor-pointer active:scale-95">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Kosongkan Semua Masukan ({{ $stats['total'] }})</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($masukanList as $item)
                                <div class="bg-white rounded-2xl border border-paper-200 shadow-sm hover:shadow-md transition-shadow p-4 sm:p-6 space-y-3.5"
                                     x-data="{ 
                                         editTanggapan: false,
                                         tanggapanText: '{{ addslashes($item->tanggapan ?? '') }}',
                                         currentStatus: '{{ $item->status }}'
                                     }">
                                    
                                    <!-- Header Penegas Segmentasi Area (Dropdown vs Sidebar vs Luar Sidebar) -->
                                    @if($item->sumber_area === 'dropdown_sidebar')
                                        <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-purple-50 border border-purple-200 text-xs">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <input type="checkbox" 
                                                       :value="{{ $item->id }}" 
                                                       x-model="selectedIds" 
                                                       class="w-4 h-4 rounded border-purple-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
                                                <span class="px-2 py-0.5 rounded-md bg-purple-600 text-white font-bold text-[10px] tracking-wider uppercase shrink-0">Dropdown Sidebar</span>
                                                <span class="font-semibold text-purple-900 truncate">{{ $item->detail_sumber ?: 'Menu di dalam Dropdown' }}</span>
                                            </div>
                                            <span class="text-[10px] font-mono font-medium text-purple-600 shrink-0 hidden sm:inline">Terisolasi: Dropdown</span>
                                        </div>
                                    @elseif($item->sumber_area === 'sidebar')
                                        <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-sky-50 border border-sky-200 text-xs">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <input type="checkbox" 
                                                       :value="{{ $item->id }}" 
                                                       x-model="selectedIds" 
                                                       class="w-4 h-4 rounded border-sky-300 text-sky-600 focus:ring-sky-500 cursor-pointer">
                                                <span class="px-2 py-0.5 rounded-md bg-sky-600 text-white font-bold text-[10px] tracking-wider uppercase shrink-0">Sidebar Navigasi</span>
                                                <span class="font-semibold text-sky-900 truncate">{{ $item->detail_sumber ?: 'Navigasi Menu Sidebar' }}</span>
                                            </div>
                                            <span class="text-[10px] font-mono font-medium text-sky-600 shrink-0 hidden sm:inline">Terisolasi: Sidebar</span>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-xs">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <input type="checkbox" 
                                                       :value="{{ $item->id }}" 
                                                       x-model="selectedIds" 
                                                       class="w-4 h-4 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                                <span class="px-2 py-0.5 rounded-md bg-emerald-600 text-white font-bold text-[10px] tracking-wider uppercase shrink-0">Komponen Luar Sidebar</span>
                                                <span class="font-semibold text-emerald-900 truncate">{{ $item->bagian_elemen ?: 'Halaman Dokumen Utama' }}</span>
                                            </div>
                                            <span class="text-[10px] font-mono font-medium text-emerald-600 shrink-0 hidden sm:inline">Terisolasi: Dokumen Utama</span>
                                        </div>
                                    @endif

                                <!-- Card Header -->
                                <div class="flex flex-wrap items-start justify-between gap-2 border-b border-paper-100 pb-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-full bg-paper-100 border border-paper-300 flex items-center justify-center text-sm font-bold text-ink-800">
                                            {{ strtoupper(substr($item->nama_reviewer, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h4 class="text-xs sm:text-sm font-semibold text-ink-900">{{ $item->nama_reviewer }}</h4>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $item->sumber_area_badge['bg'] }} {{ $item->sumber_area_badge['text'] }} {{ $item->sumber_area_badge['border'] }}">
                                                    {{ $item->sumber_area_badge['label'] }}
                                                </span>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $item->kategori_badge['bg'] }} {{ $item->kategori_badge['text'] }} {{ $item->kategori_badge['border'] }}">
                                                    {{ $item->kategori_badge['label'] }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-paper-500 flex items-center gap-1.5 flex-wrap mt-0.5">
                                                <span title="{{ $item->created_at->format('d M Y H:i:s') }}">{{ $item->created_at->diffForHumans() }}</span>
                                                <span>&bull;</span>
                                                <span class="font-mono text-[10px] px-1.5 py-0.5 bg-paper-100 rounded text-paper-600">
                                                    {{ $item->device_info ?: 'Perangkat Umum' }}
                                                </span>
                                                @if($item->detail_sumber)
                                                    <span>&bull;</span>
                                                    <span class="text-purple-700 font-semibold">
                                                        {{ $item->detail_sumber }}
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Status Badge & Dropdown Action -->
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('secret.update_status', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" 
                                                    class="text-xs font-semibold rounded-xl border px-2.5 py-1 focus:outline-none transition-colors cursor-pointer {{ $item->status_badge['bg'] }} {{ $item->status_badge['text'] }} {{ $item->status_badge['border'] }}">
                                                <option value="pending" @selected($item->status === 'pending')>Belum Dikerjakan</option>
                                                <option value="dikerjakan" @selected($item->status === 'dikerjakan')>Sedang Dikerjakan</option>
                                                <option value="selesai" @selected($item->status === 'selesai')>Sudah Selesai</option>
                                            </select>
                                        </form>

                                        <form action="{{ route('secret.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus masukan ini secara permanen?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Masukan" class="p-1.5 text-paper-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Page Target & Element Information -->
                                <div class="flex flex-wrap items-center justify-between gap-2 p-2.5 rounded-xl bg-paper-50 border border-paper-200 text-xs">
                                    <div class="flex items-center gap-2 min-w-0 flex-wrap">
                                        <span class="text-paper-500 font-medium">Halaman:</span>
                                        <a href="{{ $item->halaman_url }}" target="_blank" class="font-mono text-blue-700 hover:underline truncate max-w-xs sm:max-w-md flex items-center gap-1 font-semibold" title="Buka Halaman Ini di Tab Baru">
                                            <span>{{ $item->judul_halaman ?: $item->halaman_path }}</span>
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $item->sumber_area === 'dropdown_sidebar' ? 'bg-purple-100 text-purple-800' : ($item->sumber_area === 'sidebar' ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            Area: {{ $item->sumber_area_badge['label'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] text-paper-500 flex-wrap">
                                        @if($item->bagian_elemen)
                                            <span class="bg-white border border-paper-200 px-2 py-0.5 rounded-md truncate max-w-xs" title="{{ $item->bagian_elemen }}">
                                                Bagan: <strong class="text-ink-800">{{ $item->bagian_elemen }}</strong>
                                            </span>
                                        @endif
                                        @if($item->posisi_x !== null && $item->posisi_y !== null)
                                            <span class="bg-red-50 border border-red-200 text-red-700 px-2 py-0.5 rounded font-mono font-medium flex items-center gap-1">
                                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                Koordinat: {{ round($item->posisi_x) }}% x {{ round($item->posisi_y) }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Reviewer Comment Text -->
                                <div class="text-xs sm:text-sm text-ink-900 leading-relaxed bg-white p-3.5 rounded-xl border border-paper-200">
                                    <p class="whitespace-pre-line font-medium">{{ $item->komentar }}</p>
                                </div>

                                <!-- Audio Rekaman Suara Asli jika ada -->
                                @if($item->audio_rekaman)
                                    <div class="space-y-1.5 pt-1 bg-slate-50 border border-slate-200 rounded-xl p-3">
                                        <div class="flex items-center justify-between text-xs font-semibold text-slate-800">
                                            <span class="flex items-center gap-1.5 text-emerald-700">
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                                Rekaman Suara Asli:
                                            </span>
                                            <span class="text-[11px] text-slate-500 font-mono">Audio Sinkronisasi</span>
                                        </div>
                                        <audio controls class="w-full h-8 mt-1 rounded-lg" src="{{ $item->audio_rekaman }}"></audio>
                                    </div>
                                @endif

                                <!-- Tangkapan Layar & Coretan / Potret Halaman Asli -->
                                @if($item->coretan_gambar)
                                    <div class="space-y-2 pt-1">
                                        <div class="flex items-center justify-between text-xs font-semibold text-ink-800">
                                            <span class="flex items-center gap-1.5 {{ $item->sumber_area === 'dropdown_sidebar' ? 'text-purple-700' : ($item->sumber_area === 'sidebar' ? 'text-sky-700' : 'text-emerald-700') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                <span>Tangkapan Layar Fokus Terpisah: <strong class="underline decoration-2">{{ $item->sumber_area_badge['label'] }}</strong></span>
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ $item->demo_url }}" target="_blank" class="text-amber-600 hover:text-amber-800 text-[11px] font-bold flex items-center gap-1 bg-amber-50 hover:bg-amber-100 px-2 py-0.5 rounded-md border border-amber-300 transition-colors shadow-sm">
                                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                    <span>Lihat Demo Presisi</span>
                                                </a>
                                                <button type="button" 
                                                        @click="openLightbox('{{ $item->coretan_gambar }}')" 
                                                        class="text-paper-500 hover:text-ink-800 text-[11px] font-medium flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                    <span>Gambar</span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Mock Browser Frame dengan Link Auto Redirect ke Demo Interaktif -->
                                        <div class="rounded-xl border border-slate-300 bg-white shadow-sm overflow-hidden">
                                            <!-- Browser Frame Header -->
                                            <div class="bg-slate-100 border-b border-slate-200 px-3 py-2 flex items-center justify-between gap-3 text-xs">
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>
                                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                                                </div>
                                                <div class="flex-1 bg-white border border-slate-200 rounded-md px-2.5 py-0.5 text-[11px] font-mono text-slate-600 truncate text-center">
                                                    {{ $item->halaman_url }}
                                                </div>
                                                <a href="{{ $item->demo_url }}" 
                                                   target="_blank"
                                                   title="Buka Demo Interaktif Hasil Coretan &amp; Komentar di Halaman Nyata"
                                                   class="px-2.5 py-0.5 rounded-md bg-amber-400 hover:bg-amber-300 text-slate-950 text-[11px] font-bold flex items-center gap-1 shadow transition-all active:scale-95 shrink-0">
                                                    <span>Lihat Jelas (Demo)</span>
                                                    <svg class="w-3 h-3 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            </div>

                                             <!-- Potret Area Preview (Klik Langsung Buka Demo Halaman Nyata) -->
                                            <a href="{{ $item->demo_url }}" 
                                               target="_blank"
                                               title="Klik untuk membuka demo interaktif hasil coretan &amp; komentar di halaman asli"
                                               class="relative bg-slate-100 flex items-center justify-center max-h-[500px] overflow-hidden cursor-pointer group p-2.5 sm:p-3 block">
                                                <img src="{{ $item->coretan_gambar }}" 
                                                     alt="Potret Halaman Asli & Coretan" 
                                                     class="w-full max-h-[460px] object-contain rounded-lg border border-slate-200 bg-white shadow-sm transition-transform group-hover:scale-[1.01]">
                                                <div class="absolute inset-0 bg-slate-950/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-xs font-semibold rounded-lg gap-2 backdrop-blur-[1px] p-4 text-center">
                                                    <div class="p-3 rounded-full bg-amber-400 text-slate-950 font-bold shadow-xl">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                    </div>
                                                    <span class="text-sm font-bold text-amber-300">Klik untuk Buka Demo Interaktif Langsung di Halaman Asli</span>
                                                    <span class="text-[11px] text-slate-200 font-mono">Letak presisi coretan &amp; pin komentar akan ditampilkan aktif di struktur DOM aplikasi</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <!-- Tanggapan / Catatan Mahasiswa -->
                                <div class="pt-2 border-t border-paper-100">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-semibold text-paper-700 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-paper-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Tindak Lanjut / Catatan Perbaikan Mahasiswa:
                                        </span>
                                        <button type="button" @click="editTanggapan = !editTanggapan" class="text-blue-700 hover:underline text-[11px] font-medium">
                                            <span x-show="!editTanggapan">{{ $item->tanggapan ? 'Ubah Catatan' : '+ Tambah Catatan Tindak Lanjut' }}</span>
                                            <span x-show="editTanggapan">Batal</span>
                                        </button>
                                    </div>

                                    <!-- Tampilan Tanggapan jika sudah ada -->
                                    <template x-if="!editTanggapan && tanggapanText">
                                        <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-2.5 text-xs text-emerald-900 leading-snug">
                                            <p class="whitespace-pre-line" x-text="tanggapanText"></p>
                                        </div>
                                    </template>
                                    <template x-if="!editTanggapan && !tanggapanText">
                                        <p class="text-[11px] text-paper-400 italic">Belum ada catatan tindak lanjut dari mahasiswa.</p>
                                    </template>

                                    <!-- Form Edit Tanggapan -->
                                    <form x-show="editTanggapan" action="{{ route('secret.update_tanggapan', $item) }}" method="POST" class="space-y-2 mt-2">
                                        @csrf
                                        <textarea name="tanggapan" rows="2" 
                                                  x-model="tanggapanText"
                                                  placeholder="Tuliskan aksi yang sudah dilakukan (misal: 'Sudah diperbaiki format rupiah dan responsive table di commit x')..."
                                                  class="w-full rounded-xl border border-paper-300 p-2 text-xs text-ink-900 focus:border-padi-500 focus:outline-none bg-white"></textarea>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="editTanggapan = false" class="px-3 py-1.5 rounded-lg border border-paper-300 text-xs text-paper-600 hover:bg-paper-50">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-4 py-1.5 rounded-lg bg-padi-500 hover:bg-padi-600 text-ink-950 font-semibold text-xs shadow-sm">
                                                Simpan Catatan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="pt-4">
                            {{ $masukanList->links() }}
                        </div>
                    </div>
                </div>
                @endif

            </main>
        </div>
    </div>

    <!-- LIGHTBOX MODAL UNTUK PREVIEW CORETAN FULLSIZE (Z-INDEX TERTINGGI) -->
    <div x-show="lightboxImage" 
         style="z-index: 9999999 !important;"
         class="fixed inset-0 bg-slate-950/90 backdrop-blur-md flex flex-col items-center justify-center p-3 sm:p-6"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="closeLightbox()"
         x-cloak>
        
        <div class="w-full max-w-6xl flex items-center justify-between pb-3 text-white">
            <span class="text-xs sm:text-sm font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Detail Potret Halaman &amp; Coretan Saran
            </span>
            <div class="flex items-center gap-2">
                <a :href="lightboxImage" target="_blank" download="potret-saran.jpg" class="text-xs text-white hover:text-amber-300 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Unduh Gambar</span>
                </a>
                <button type="button" @click="closeLightbox()" class="text-white hover:text-slate-300 p-1.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <div class="relative w-full max-w-6xl max-h-[85vh] overflow-y-auto bg-slate-900 rounded-2xl border border-white/20 p-2 sm:p-4 flex justify-center">
            <img :src="lightboxImage" alt="Potret Halaman Penuh &amp; Coretan" class="w-auto max-w-full rounded-lg shadow-2xl bg-white">
        </div>
    </div>

</body>
</html>
