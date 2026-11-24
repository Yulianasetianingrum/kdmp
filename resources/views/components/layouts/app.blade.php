<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} &mdash; Ngobar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-[#f6f5ec] font-sans text-ink-900 antialiased" 
      x-data="{ 
          sidebarOpen: window.innerWidth >= 1024,
          isMobile: window.innerWidth < 1024
      }"
      x-init="
          window.kdmpSidebarOpen = sidebarOpen;
          $watch('sidebarOpen', val => {
              window.kdmpSidebarOpen = val;
              window.dispatchEvent(new CustomEvent('kdmp-sidebar-toggle', { detail: { open: val } }));
          });
          window.addEventListener('resize', () => {
              isMobile = window.innerWidth < 1024;
              if (!isMobile && !sidebarOpen) {
                  sidebarOpen = true;
                  window.kdmpSidebarOpen = true;
              }
          });
          window.addEventListener('kdmp-sidebar-open', () => {
              sidebarOpen = true;
              window.kdmpSidebarOpen = true;
          });
      ">
    <div class="flex h-full h-[100dvh] overflow-hidden relative">
        
        {{-- Mobile Backdrop Overlay --}}
        <div x-cloak
             x-show="sidebarOpen && isMobile"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="if (!window.isReviewDrawingActive && !window.isReviewModalActive) sidebarOpen = false"
             :style="(window.isReviewDrawingActive || window.isReviewModalActive) ? 'pointer-events: none !important;' : ''"
             class="fixed inset-0 z-40 bg-ink-950/60 backdrop-blur-sm lg:hidden print:hidden"></div>

        {{-- Sidebar Component --}}
        <div class="fixed inset-y-0 left-0 z-50 -translate-x-full lg:static lg:translate-x-0 transition-transform duration-300 ease-in-out shrink-0 print:hidden"
             :class="{
                'fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 ease-in-out lg:static lg:translate-x-0': isMobile,
                '-translate-x-full': isMobile && !sidebarOpen,
                'translate-x-0': isMobile && sidebarOpen,
                'hidden': !isMobile && !sidebarOpen,
                'shrink-0': !isMobile
             }">
            <x-sidebar />
        </div>

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden relative">
            {{-- Top Navbar --}}
            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-paper-200 bg-white px-3 sm:px-6 py-2 sm:py-3 shadow-sm print:hidden">
                <div class="flex items-center gap-2 sm:gap-4 min-w-0 flex-1 mr-2">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="p-2 -ml-1 text-paper-600 hover:text-ink-900 rounded-lg hover:bg-paper-100 focus:outline-none shrink-0"
                            aria-label="Toggle menu navigasi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0 flex-1">
                        <h1 class="font-display text-sm sm:text-lg font-semibold text-ink-900 leading-tight truncate">{{ $title ?? 'Ngobar' }}</h1>
                        @if(isset($eyebrow))
                        <p class="font-mono text-[9px] sm:text-[10px] uppercase tracking-widest text-paper-500 truncate mt-0.5">{{ $eyebrow }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                    @auth
                        @if(auth()->user()->id_entitas === null)
                            <form action="{{ route('admin.set_entitas') }}" method="POST" class="hidden md:flex items-center gap-2 mr-2">
                                @csrf
                                <select name="id_entitas" onchange="this.form.submit()" class="text-xs border-paper-300 rounded-lg py-1.5 pl-3 pr-8 focus:ring-padi-500 focus:border-padi-500 shadow-sm">
                                    <option value="">Semua Entitas (Global)</option>
                                    @foreach(\App\Models\Tenant\Entitas::where('status', 'APPROVED')->get() as $ent)
                                        <option value="{{ $ent->id_entitas }}" {{ session('entitas_aktif_pilihan') == $ent->id_entitas ? 'selected' : '' }}>
                                            {{ $ent->nama_entitas }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif
                        <div class="text-right leading-tight hidden sm:block max-w-[140px] md:max-w-xs">
                            <p class="text-xs sm:text-sm font-semibold text-ink-900 truncate">{{ auth()->user()->nama }}</p>
                            <p class="text-[10px] sm:text-[11px] text-paper-500 uppercase font-medium truncate">
                                @if(auth()->user()->id_entitas === null)
                                    Super Admin {{ session('entitas_aktif_pilihan') ? '(Mode Entitas)' : '(Global)' }}
                                @else
                                    {{ auth()->user()->entitas?->nama_entitas }}
                                @endif
                            </p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-lg border border-paper-300 bg-white px-2.5 sm:px-3 py-1.5 text-xs font-medium text-ink-700 hover:border-merah-500 hover:text-merah-600 hover:bg-merah-50 transition-colors shadow-sm">
                                Keluar
                            </button>
                        </form>
                    @endauth
                </div>
            </header>

            {{-- Main Content Container --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden min-w-0 px-3.5 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8 overscroll-contain">
                @if (session('info'))
                    <div class="mb-4 sm:mb-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 shadow-sm">
                        {{ session('info') }}
                    </div>
                @endif
                @if (session('status'))
                    <div class="mb-4 sm:mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
            {{-- Mount Point Layer Anotasi Dokumen Utama --}}
            <div id="main-annotation-mount" class="pointer-events-none"></div>
        </div>
    </div>
    <x-review-widget />
    @stack('scripts')
</body>
</html>
