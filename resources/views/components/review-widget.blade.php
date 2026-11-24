@php
    $isSecretPage = request()->is('secret*');
    $shouldShowWelcomeToast = request()->is('/') || request()->is('dashboard');
@endphp

@if(!$isSecretPage)
<div id="review-system-root" x-data="reviewSystem({{ $shouldShowWelcomeToast ? 'true' : 'false' }})" x-init="init()" class="print:hidden select-none relative z-[9999990]">

    <!-- 1. POPUP PERINGATAN 7 DETIK AWAL (HANYA DI LANDING PAGE '/' & '/dashboard') -->
    <div x-show="showWelcomeToast"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-y-8 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-8 opacity-0"
         style="z-index: 9999999 !important;"
         class="fixed top-5 left-1/2 -translate-x-1/2 w-[92vw] max-w-lg bg-[#0f172a] text-white px-5 py-4 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] border-2 border-amber-400 flex flex-col gap-2.5 z-[9999999]"
         x-cloak>
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3 min-w-0">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-400 text-slate-950 font-bold shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-400">Petunjuk Saran</p>
                    <p class="text-sm font-semibold text-white leading-snug mt-0.5">
                        Tap / Klik 2x pada layar untuk menambahkan saran, atau gunakan tombol Pena untuk mencoret halaman.
                    </p>
                </div>
            </div>
            <button type="button" @click="showWelcomeToast = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <!-- Progress Bar 7 Detik -->
        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
            <div class="bg-amber-400 h-1.5 transition-all duration-100 ease-linear rounded-full" :style="`width: ${toastProgress}%`"></div>
        </div>
    </div>

    <!-- 1B. BANNER DEMO HASIL REVIEW INTERAKTIF (PRESISI LIVE DOM PADA HALAMAN NYATA) -->
    <div x-show="isDemoReviewMode && demoReviewData"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-full opacity-0"
         style="z-index: 99999999 !important;"
         class="fixed top-0 inset-x-0 bg-slate-950 text-white border-b-2 border-amber-400 shadow-2xl px-3 sm:px-6 py-2.5 flex items-center justify-between gap-3 text-xs"
         x-cloak>
        <div class="flex items-center gap-3 min-w-0">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-400 text-slate-950 font-bold shadow animate-pulse">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2 py-0.5 rounded-full bg-amber-400 text-slate-950 font-mono text-[10px] font-bold uppercase tracking-wider">
                        Demo Review #<span x-text="demoReviewData?.id"></span>
                    </span>
                    <button type="button" 
                            @click="focusDemoTarget()" 
                            class="text-[11px] font-bold text-amber-300 hover:text-white underline flex items-center gap-1 cursor-pointer">
                        <span x-text="demoReviewData?.bagian_elemen || 'Lihat Lokasi Sasaran'"></span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </button>
                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold"
                          :class="demoReviewData?.sumber_area === 'dropdown_sidebar' ? 'bg-purple-900/80 text-purple-200 border border-purple-500' : (demoReviewData?.sumber_area === 'sidebar' ? 'bg-sky-900/80 text-sky-200 border border-sky-500' : 'bg-emerald-900/80 text-emerald-200 border border-emerald-500')"
                          x-text="demoReviewData?.sumber_area === 'dropdown_sidebar' ? 'Dropdown Sidebar' : (demoReviewData?.sumber_area === 'sidebar' ? 'Sidebar Navigasi' : 'Komponen Luar Sidebar')">
                    </span>
                </div>
                <p class="text-slate-200 text-xs font-semibold truncate mt-0.5" x-text="`Komentar: &ldquo;${demoReviewData?.komentar}&rdquo;`"></p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="button" 
                    @click="focusDemoTarget()" 
                    title="Sorot dan tuju langsung posisi komponen di halaman"
                    class="px-2.5 py-1.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-bold shadow flex items-center gap-1 transition-all active:scale-95 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                <span>Tunjukkan Letak Presisi</span>
            </button>
            <template x-if="demoReviewData?.coretan_gambar">
                <button type="button" 
                        @click="showDemoPip = !showDemoPip" 
                        title="Buka / Tutup Panel Bukti Snapshot Coretan Asli"
                        class="px-2.5 py-1.5 rounded-xl border text-xs font-semibold transition-colors flex items-center gap-1 cursor-pointer"
                        :class="showDemoPip ? 'bg-amber-400 text-slate-950 border-amber-300 font-bold shadow' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span x-text="showDemoPip ? 'Sembunyikan Bukti Coretan' : 'Lihat Bukti Coretan'"></span>
                </button>
            </template>
            <template x-if="demoReviewData?.coretan_gambar">
                <button type="button" 
                        @click="showDemoOverlay = !showDemoOverlay" 
                        title="Tampilkan hamparan snapshot transparan di layar penuh"
                        class="px-2 py-1.5 rounded-xl border text-xs font-semibold transition-colors flex items-center gap-1 cursor-pointer"
                        :class="showDemoOverlay ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <span class="hidden sm:inline" x-text="showDemoOverlay ? 'Tutup Hamparan' : 'Hamparan Layar'"></span>
                </button>
            </template>
            <a href="/secret" 
               class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="hidden sm:inline">Panel Secret</span>
            </a>
            <button type="button" 
                    @click="closeDemoReviewMode()" 
                    title="Tutup Mode Demo"
                    class="p-1.5 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-300 hover:text-white transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    <!-- 1C. SPOTLIGHT FRAME & PIN DEMO PRESISI DI LIVE DOM -->
    <template x-if="isDemoReviewMode && demoPin">
        <div>
            <!-- Spotlight Frame Menyala di Sekitar Komponen Target -->
            <div class="fixed pointer-events-none z-[9999997] transition-all duration-300"
                 :style="demoSpotlightBoxStyle">
                <div class="w-full h-full rounded-xl border-4 border-amber-400 bg-amber-300/30 shadow-[0_0_40px_rgba(251,191,36,0.9)] ring-4 ring-amber-400/50 animate-pulse"></div>
            </div>

            <!-- Balon Pin Demo Mengambang -->
            <div class="fixed pointer-events-none z-[9999999] transition-all duration-150"
                 :style="demoPinScreenStyle">
                <div class="relative flex flex-col items-center pointer-events-auto select-none">
                    <!-- Balon Pin Demo -->
                    <div class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold px-3.5 py-1.5 rounded-xl shadow-[0_10px_35px_rgba(251,191,36,0.85)] border-2 border-slate-950 flex items-center gap-2 text-xs animate-bounce cursor-pointer"
                         @click="focusDemoTarget()"
                         title="Klik untuk memfokuskan kembali sasaran ini">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-ping"></span>
                        <span class="font-extrabold" x-text="`Saran #${demoReviewData?.id}`"></span>
                        <span class="text-slate-900 font-semibold max-w-xs truncate" x-text="demoReviewData?.komentar"></span>
                    </div>
                    <!-- Ujung Jarum -->
                    <div class="w-0 h-0 border-x-[6px] border-x-transparent border-t-[8px] border-t-amber-400 -mt-[1px]"></div>
                    <!-- Titik Target Merah Menyala -->
                    <div class="w-4 h-4 rounded-full bg-red-600 ring-4 ring-amber-400 ring-offset-2 -mt-1 shadow-lg animate-pulse"></div>
                </div>
            </div>
        </div>
    </template>

    <!-- 1D. FLOATING PICTURE-IN-PICTURE (PIP) BUKTI CORETAN & SNAPSHOT ASLI -->
    <template x-if="isDemoReviewMode && demoReviewData?.coretan_gambar && showDemoPip">
        <div class="fixed bottom-6 right-6 z-[9999998] w-80 sm:w-96 bg-slate-900/95 backdrop-blur-md rounded-2xl border-2 border-amber-400 shadow-2xl overflow-hidden flex flex-col transition-all duration-300"
             :class="isPipMinimized ? 'h-11 w-64' : 'max-h-[80vh]'">
            <div class="px-3 py-2 bg-slate-950 border-b border-slate-800 flex items-center justify-between gap-2 select-none">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                    <span class="font-bold text-xs text-amber-300 truncate">Bukti Coretan Asli #<span x-text="demoReviewData?.id"></span></span>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" 
                            @click="isPipMinimized = !isPipMinimized"
                            :title="isPipMinimized ? 'Perbesar Pratinjau' : 'Kecilkan'"
                            class="p-1 rounded text-slate-400 hover:text-white hover:bg-slate-800">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isPipMinimized ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            @click="showDemoPip = false"
                            title="Tutup Pratinjau Snapshot"
                            class="p-1 rounded text-slate-400 hover:text-rose-400 hover:bg-slate-800">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            <div x-show="!isPipMinimized" class="p-2 flex flex-col gap-2">
                <div class="relative bg-slate-950 rounded-xl overflow-hidden border border-slate-800 group">
                    <img :src="demoReviewData.coretan_gambar" class="w-full h-auto max-h-60 object-contain rounded-lg" alt="Snapshot Bukti Review">
                    <a :href="demoReviewData.coretan_gambar" target="_blank" class="absolute inset-0 bg-slate-950/50 opacity-0 group-hover:opacity-100 flex items-center justify-center text-xs font-bold text-white transition-opacity">
                        <span>Buka Ukuran Penuh</span>
                    </a>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 px-1">
                    <span class="truncate max-w-[180px]" x-text="demoReviewData?.bagian_elemen"></span>
                    <button type="button" @click="focusDemoTarget()" class="text-amber-400 hover:text-amber-300 font-semibold underline shrink-0 cursor-pointer">
                        Sorot Komponen
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- 1E. OVERLAY CORETAN ASLI PADA MODE DEMO (OPTIONAL LAYER) -->
    <template x-if="isDemoReviewMode && showDemoOverlay && demoReviewData?.coretan_gambar">
        <div class="fixed inset-0 pointer-events-none z-[9999990] flex items-center justify-center bg-black/50">
            <div class="relative w-full h-full flex items-center justify-center p-4">
                <img :src="demoReviewData.coretan_gambar" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl border-2 border-amber-400" alt="Coretan Demo Presisi">
            </div>
        </div>
    </template>

    <!-- 2A. LAYER ANOTASI DOKUMEN UTAMA (TERISOLASI DALAM KOLOM UTAMA, STACKED DI BAWAH BACKDROP z-40 & SIDEBAR z-50) -->
    <template x-teleport="#main-annotation-mount">
        <div id="main-annotation-layer" 
             class="absolute inset-0 pointer-events-none overflow-hidden" 
             style="z-index: 20 !important;">
            
            <canvas id="main-drawing-canvas" 
                    class="absolute inset-0 w-full h-full"
                    style="z-index: 21 !important;"
                    :style="(isDrawingMode && !isPanMode && !isInteractMode && !isPinTargetMode) ? 'pointer-events: auto !important;' : 'pointer-events: none !important;'"
                    :class="(isDrawingMode && !isPanMode && !isInteractMode && !isPinTargetMode) ? (isEraserMode ? 'cursor-cell' : 'cursor-crosshair') : ''"
                    @mousedown="handleMouseDown($event, 'main_document')"
                    @mousemove="handleMouseMove($event, 'main_document')"
                    @mouseup="handleMouseUp($event, 'main_document')"
                    @mouseleave="handleMouseUp($event, 'main_document')"
                    @touchstart="handleTouchStart($event, 'main_document')"
                    @touchmove="handleTouchMove($event, 'main_document')"
                    @touchend="handleTouchEnd($event, 'main_document')"
                    @wheel="handleWheel($event, 'main_document')"></canvas>

            <!-- Pinpoint Markers Dokumen Utama -->
            <template x-for="p in mainSaranList" :key="p.id">
                <div style="z-index: 25 !important;"
                     class="review-pinpoint-marker absolute -translate-x-1/2 -translate-y-full cursor-pointer pointer-events-auto select-none group"
                     :data-pin-index="p.originalIndex"
                     :style="scrollTick >= 0 ? getMainPinStyle(p) : ''"
                     @click.stop="editExistingSaran(p.originalIndex)"
                     :title="`Saran #${p.originalIndex+1}: ${p.text}`">
                    <div class="relative flex flex-col items-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none absolute bottom-full mb-2 bg-slate-900 text-white text-[11px] py-1.5 px-3 rounded-xl shadow-2xl border border-slate-700 whitespace-nowrap max-w-xs truncate z-50">
                            <span class="font-bold text-amber-400 block truncate" x-text="p.elementSnippet || 'Bagan Layar'"></span>
                            <span class="text-slate-300 block truncate" x-text="p.text || 'Klik untuk lihat/ubah saran'"></span>
                        </div>
                        <div class="bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold px-2.5 py-1 rounded-xl shadow-2xl border-2 border-white flex items-center gap-1.5 transition-transform group-hover:scale-105 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span x-text="`Saran #${p.originalIndex+1}`"></span>
                        </div>
                        <div class="w-0 h-0 border-x-4 border-x-transparent border-t-[6px] border-t-red-600 -mt-[1px]"></div>
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-3 h-3 flex items-center justify-center pointer-events-none">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600 ring-2 ring-white"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <!-- 2B. LAYER ANOTASI SIDEBAR (TERISOLASI DI DALAM ELEMEN ASIDE, MENGIKUTI TRANSLASI & SIKLUS ASIDE) -->
    <template x-if="hasSidebarMount" x-teleport="#sidebar-annotation-mount">
        <div id="sidebar-annotation-layer" 
             class="absolute inset-0 pointer-events-none overflow-hidden" 
             style="z-index: 30 !important;">
            
            <canvas id="sidebar-drawing-canvas" 
                    class="absolute inset-0 w-full h-full"
                    style="z-index: 31 !important;"
                    :style="(isDrawingMode && !isPanMode && !isInteractMode && !isPinTargetMode) ? 'pointer-events: auto !important;' : 'pointer-events: none !important;'"
                    :class="(isDrawingMode && !isPanMode && !isInteractMode && !isPinTargetMode) ? (isEraserMode ? 'cursor-cell' : 'cursor-crosshair') : ''"
                    @mousedown="handleMouseDown($event, 'sidebar')"
                    @mousemove="handleMouseMove($event, 'sidebar')"
                    @mouseup="handleMouseUp($event, 'sidebar')"
                    @mouseleave="handleMouseUp($event, 'sidebar')"
                    @touchstart="handleTouchStart($event, 'sidebar')"
                    @touchmove="handleTouchMove($event, 'sidebar')"
                    @touchend="handleTouchEnd($event, 'sidebar')"
                    @wheel="handleWheel($event, 'sidebar')"></canvas>

            <!-- Pinpoint Markers Sidebar & Dropdown -->
            <template x-for="p in sidebarSaranList" :key="p.id">
                <div style="z-index: 35 !important;"
                     class="review-pinpoint-marker absolute -translate-x-1/2 -translate-y-full cursor-pointer pointer-events-auto select-none group"
                     :data-pin-index="p.originalIndex"
                     :style="scrollTick >= 0 ? getSidebarPinStyle(p) : ''"
                     @click.stop="editExistingSaran(p.originalIndex)"
                     :title="`Saran #${p.originalIndex+1}: ${p.text}`">
                    <div class="relative flex flex-col items-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none absolute bottom-full mb-2 bg-slate-900 text-white text-[11px] py-1.5 px-3 rounded-xl shadow-2xl border border-slate-700 whitespace-nowrap max-w-xs truncate z-50">
                            <span class="font-bold text-amber-400 block truncate" x-text="p.elementSnippet || 'Bagan Sidebar'"></span>
                            <span class="text-slate-300 block truncate" x-text="p.text || 'Klik untuk lihat/ubah saran'"></span>
                        </div>
                        <div class="bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold px-2.5 py-1 rounded-xl shadow-2xl border-2 border-white flex items-center gap-1.5 transition-transform group-hover:scale-105 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span x-text="`Saran #${p.originalIndex+1}`"></span>
                        </div>
                        <div class="w-0 h-0 border-x-4 border-x-transparent border-t-[6px] border-t-red-600 -mt-[1px]"></div>
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-3 h-3 flex items-center justify-center pointer-events-none">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600 ring-2 ring-white"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <!-- 2C. MODAL DIALOG INPUT SARAN & KOMENTAR (UNIVERSAL, TOP-LEVEL z-[99999999], BEBAS DARI OVERLAP TOOLBAR) -->
    <div x-show="showCommentModal"
         style="z-index: 99999999 !important;"
         class="fixed inset-0 flex items-center justify-center p-3 sm:p-4 bg-slate-950/75 backdrop-blur-sm overflow-y-auto pointer-events-auto select-none"
         @click.self="closeCommentModal()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        
        <div class="review-comment-popup bg-slate-900 border-2 border-slate-700 text-white rounded-2xl shadow-2xl w-full max-w-lg p-5 sm:p-6 space-y-4 my-auto relative pointer-events-auto select-text"
             @click.stop
             @mousedown.stop
             @touchstart.stop>
            
            <!-- Header Modal -->
            <div class="flex items-start justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-400 text-slate-950 font-bold text-base shadow">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-mono uppercase tracking-wider block font-bold"
                              :class="currentPin?.annotation_scope === 'dropdown_sidebar' ? 'text-amber-400' : (currentPin?.annotation_scope === 'sidebar' ? 'text-amber-300' : 'text-sky-400')"
                              x-text="currentPin?.annotation_scope === 'dropdown_sidebar' ? 'Saran Dropdown Sidebar' : (currentPin?.annotation_scope === 'sidebar' ? 'Saran Menu Sidebar' : 'Saran Dokumen Utama')"></span>
                        <h3 class="text-sm sm:text-base font-bold text-white truncate leading-tight mt-0.5"
                            x-text="currentPin?.detail_sumber || currentPin?.elementSnippet || 'Bagan Aplikasi'"></h3>
                    </div>
                </div>
                <button type="button" @click="closeCommentModal()" class="text-slate-400 hover:text-white p-1.5 rounded-xl hover:bg-slate-800 transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Form Saran -->
            <div class="space-y-3 text-xs">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="font-semibold text-slate-200 text-xs">Catatan / Masukan Saran <span class="text-amber-400">*</span></label>
                        
                        <button type="button" 
                                @click="toggleSpeechAndRecording()" 
                                :class="isListening ? 'bg-red-600 text-white animate-pulse shadow-md ring-2 ring-red-400' : 'bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700'"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold transition-all">
                            <svg class="w-3.5 h-3.5" :class="isListening ? 'text-white' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                            <span x-text="isListening ? 'Sedang Bicara...' : 'Dikte Suara'"></span>
                        </button>
                    </div>

                    <textarea x-model="modalComment" 
                              rows="4" 
                              placeholder="Tuliskan masukan atau gunakan tombol Dikte Suara untuk berbicara..."
                              class="w-full rounded-xl border border-slate-700 bg-slate-950 p-3 text-xs text-white placeholder-slate-500 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 focus:outline-none leading-relaxed resize-none pointer-events-auto select-text shadow-inner"></textarea>
                    
                    <div class="mt-1 flex items-center justify-between text-[11px] text-slate-400">
                        <span>Ketik masukan atau rekam suara Anda.</span>
                        <span x-show="hasRecordedAudio" class="text-emerald-400 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Audio terekam
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Modal -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800">
                <div>
                    <template x-if="editingIndex !== null">
                        <button type="button" @click="deleteCurrentSaran()" class="text-xs text-rose-400 hover:text-rose-300 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Saran Ini</span>
                        </button>
                    </template>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="closeCommentModal()" class="px-4 py-2 rounded-xl border border-slate-700 text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                        Batal
                    </button>
                    <button type="button" 
                            @click="saveCurrentSaranToSession()" 
                            class="px-5 py-2 rounded-xl text-xs font-bold bg-amber-400 hover:bg-amber-300 text-slate-950 shadow-lg transition-transform active:scale-95">
                        Simpan Saran
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- 3. TOOLBAR KETIKA MODE PENA / PENGHAPUS AKTIF (COMPACT, RESPONSIVE UNTUK HP, TABLET, DAN DESKTOP) -->
    <div x-show="isDrawingMode && !showCommentModal" 
         id="review-drawing-toolbar"
         style="z-index: 9999999 !important;" 
         class="fixed bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 z-[9999999] bg-slate-900/95 backdrop-blur-md text-white shadow-[0_15px_40px_rgba(0,0,0,0.6)] border border-slate-700/80 rounded-2xl select-none transition-all duration-200 max-w-[calc(100vw-16px)]"
         :style="getToolbarStyle()"
         x-cloak>

        <!-- A. TAMPILAN MINIMIZE / MODE RINGKAS (HANYA PIL MINI FLOATING, SANGAT COCOK UNTUK HP/TABLET SAAT MENCORET) -->
        <template x-if="isToolbarMinimized">
            <div class="flex items-center gap-1.5 px-2.5 py-1.5">
                <!-- Drag Handle -->
                <div @mousedown="startDragToolbar($event)"
                     @touchstart.passive="startDragToolbar($event)"
                     @dblclick="toolbarPos = { x: null, y: null }"
                     title="Tahan & Geser"
                     class="cursor-grab active:cursor-grabbing p-1 text-slate-400 hover:text-white rounded">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                </div>

                <!-- Indikator Alat Aktif & Warna -->
                <div class="flex items-center gap-1 px-1.5 py-0.5 rounded-lg bg-slate-800 text-xs font-semibold text-slate-200">
                    <template x-if="isEraserMode">
                        <span class="flex items-center gap-1 text-amber-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span class="text-[11px]">Hapus</span>
                        </span>
                    </template>
                    <template x-if="!isEraserMode && !isPanMode && !isInteractMode && !isPinTargetMode">
                        <span class="flex items-center gap-1">
                            <span class="w-2.5 h-2.5 rounded-full" :style="`background-color: ${penColor}`"></span>
                            <span class="text-[11px]">Pena</span>
                        </span>
                    </template>
                    <template x-if="isInteractMode">
                        <span class="flex items-center gap-1 text-emerald-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"></path></svg>
                            <span class="text-[11px]">Menu</span>
                        </span>
                    </template>
                    <template x-if="isPinTargetMode">
                        <span class="flex items-center gap-1 text-red-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span class="text-[11px]">Pin</span>
                        </span>
                    </template>
                    <template x-if="isPanMode">
                        <span class="flex items-center gap-1 text-blue-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                            <span class="text-[11px]">Geser</span>
                        </span>
                    </template>
                </div>

                <!-- Tombol Undo Cepat -->
                <button type="button" 
                        @click="undo()" 
                        :disabled="strokes.length === 0"
                        title="Urungkan Perubahan (Undo)"
                        class="p-1 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 disabled:opacity-30 disabled:cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-4-4m4 4l4-4"></path></svg>
                </button>

                <!-- Tombol Selesai Coret Mini -->
                <button type="button" 
                        @click="finishDrawing()" 
                        class="px-2.5 py-1 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs rounded-xl shadow flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span>Selesai</span>
                </button>

                <!-- Tombol Buka Panel Toolbar Lengkap -->
                <button type="button" 
                        @click="isToolbarMinimized = false" 
                        title="Perluas Toolbar Lengkap"
                        class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
        </template>

        <!-- B. TAMPILAN LENGKAP TAPI RAMPING & TIDAK MEMBEBANI LAYAR HP/TABLET/DESKTOP -->
        <template x-if="!isToolbarMinimized">
            <div class="p-1.5 sm:p-2 flex flex-col lg:flex-row items-center gap-1.5 sm:gap-2 max-w-full">
                
                <!-- Baris 1: Drag Handle & Kelompok Mode Alat -->
                <div class="flex items-center justify-between w-full lg:w-auto gap-1">
                    <!-- Drag Handle -->
                    <div @mousedown="startDragToolbar($event)"
                         @touchstart.passive="startDragToolbar($event)"
                         @dblclick="toolbarPos = { x: null, y: null }"
                         title="Tahan & Geser Toolbar (Klik 2x untuk Reset)"
                         class="cursor-grab active:cursor-grabbing p-1 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition-colors shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                    </div>

                    <!-- Kelompok Tombol Mode: Pena, Penghapus, Buka Menu, Tandai Titik, Geser -->
                    <div class="flex items-center gap-0.5 bg-slate-800/90 p-0.5 rounded-xl border border-slate-700/80 shrink-0">
                        <!-- Mode Pena Coret -->
                        <button type="button" 
                                @click="setPenMode()" 
                                title="Mode Pena Coret"
                                class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                                :class="!isEraserMode && !isPanMode && !isInteractMode && !isPinTargetMode ? 'bg-amber-400 text-slate-950 shadow' : 'text-slate-300 hover:text-white hover:bg-slate-700/60'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            <span class="hidden xl:inline">Pena</span>
                        </button>

                        <!-- Mode Penghapus -->
                        <button type="button" 
                                @click="setEraserModeToggle()" 
                                title="Mode Penghapus"
                                class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                                :class="isEraserMode && !isPanMode && !isInteractMode ? 'bg-amber-400 text-slate-950 shadow' : 'text-slate-300 hover:text-white hover:bg-slate-700/60'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span class="hidden xl:inline">Hapus</span>
                        </button>

                        <!-- Mode Buka Menu / Interaksi -->
                        <button type="button" 
                                @click="setInteractMode()" 
                                title="Mode Buka Menu (Klik Menu / Dropdown Sidebar)"
                                class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                                :class="isInteractMode ? 'bg-emerald-500 text-white shadow' : 'text-slate-300 hover:text-white hover:bg-slate-700/60'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"></path></svg>
                            <span class="hidden xl:inline">Menu</span>
                        </button>

                        <!-- Mode Tandai Titik / Pin -->
                        <button type="button" 
                                @click="triggerPinFromDrawingToolbar()" 
                                title="Tandai Titik Bagan"
                                class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                                :class="isPinTargetMode ? 'bg-red-600 text-white shadow' : 'text-slate-300 hover:text-white hover:bg-slate-700/60'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span class="hidden xl:inline">Pin</span>
                        </button>

                        <!-- Mode Geser Layar -->
                        <button type="button" 
                                @click="setPanMode()" 
                                title="Mode Geser Layar (Scroll)"
                                class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                                :class="isPanMode ? 'bg-blue-500 text-white shadow' : 'text-slate-300 hover:text-white hover:bg-slate-700/60'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                            <span class="hidden xl:inline">Geser</span>
                        </button>
                    </div>

                    <!-- Tombol Minimize ke Pill (Sisi Kanan Baris 1) -->
                    <button type="button" 
                            @click="isToolbarMinimized = true" 
                            title="Kecilkan Toolbar Menjadi Pil Mini"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>

                <!-- Baris 2: Warna, Ukuran Dot, Undo/Redo/Trash, Selesai -->
                <div class="flex items-center justify-between w-full lg:w-auto gap-1 sm:gap-2">
                    
                    <!-- Pilihan Warna Pena -->
                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" @click="setPenColor('#ef4444')" title="Warna Merah" class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-red-500 border transition-transform" :class="(!isEraserMode && penColor === '#ef4444') ? 'border-white scale-125 ring-2 ring-red-400' : 'border-transparent opacity-80 hover:opacity-100'"></button>
                        <button type="button" @click="setPenColor('#3b82f6')" title="Warna Biru" class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-blue-500 border transition-transform" :class="(!isEraserMode && penColor === '#3b82f6') ? 'border-white scale-125 ring-2 ring-blue-400' : 'border-transparent opacity-80 hover:opacity-100'"></button>
                        <button type="button" @click="setPenColor('#eab308')" title="Warna Kuning" class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-yellow-500 border transition-transform" :class="(!isEraserMode && penColor === '#eab308') ? 'border-white scale-125 ring-2 ring-yellow-400' : 'border-transparent opacity-80 hover:opacity-100'"></button>
                        <button type="button" @click="setPenColor('#10b981')" title="Warna Hijau" class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-emerald-500 border transition-transform" :class="(!isEraserMode && penColor === '#10b981') ? 'border-white scale-125 ring-2 ring-emerald-400' : 'border-transparent opacity-80 hover:opacity-100'"></button>
                    </div>

                    <div class="h-3.5 w-px bg-slate-700 shrink-0"></div>

                    <!-- Ukuran Garis: Dot Visual Bersih (Tipis, Sedang, Tebal) -->
                    <div class="flex items-center gap-0.5 bg-slate-800/80 p-0.5 rounded-lg border border-slate-700 shrink-0">
                        <button type="button" @click="penSize = 3; eraserSize = 16" title="Garis Tipis" class="w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center rounded transition-colors" :class="(penSize === 3 && !isEraserMode) || (eraserSize === 16 && isEraserMode) ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white'">
                            <span class="w-1 h-1 rounded-full bg-current"></span>
                        </button>
                        <button type="button" @click="penSize = 6; eraserSize = 28" title="Garis Sedang" class="w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center rounded transition-colors" :class="(penSize === 6 && !isEraserMode) || (eraserSize === 28 && isEraserMode) ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white'">
                            <span class="w-2 h-2 rounded-full bg-current"></span>
                        </button>
                        <button type="button" @click="penSize = 12; eraserSize = 48" title="Garis Tebal" class="w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center rounded transition-colors" :class="(penSize === 12 && !isEraserMode) || (eraserSize === 48 && isEraserMode) ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white'">
                            <span class="w-3 h-3 rounded-full bg-current"></span>
                        </button>
                    </div>

                    <div class="h-3.5 w-px bg-slate-700 shrink-0"></div>

                    <!-- Undo, Redo, Hapus Semua -->
                    <div class="flex items-center gap-0.5 shrink-0">
                        <button type="button" 
                                @click="undo()" 
                                :disabled="strokes.length === 0"
                                title="Urungkan Perubahan (Undo)"
                                class="p-1 sm:p-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 disabled:opacity-30 disabled:cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-4-4m4 4l4-4"></path></svg>
                        </button>

                        <button type="button" 
                                @click="redo()" 
                                :disabled="redoStack.length === 0"
                                title="Ulangi Perubahan (Redo)"
                                class="p-1 sm:p-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 disabled:opacity-30 disabled:cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a5 5 0 00-5 5v2m0 0l4-4m-4 4l-4-4"></path></svg>
                        </button>

                        <button type="button" 
                                @click="clearCanvas()" 
                                title="Hapus Seluruh Coretan Layar"
                                class="p-1 sm:p-1.5 rounded-lg text-rose-400 hover:text-rose-300 hover:bg-slate-800">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>

                    <!-- Tombol Selesai Coret -->
                    <button type="button" 
                            @click="finishDrawing()" 
                            class="px-2 sm:px-3 py-1 sm:py-1.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs rounded-xl shadow flex items-center gap-1 shrink-0 transition-transform active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <span>Selesai</span>
                    </button>
                </div>

            </div>
        </template>
    </div>

    <!-- BANNER MODE TANDAI TITIK AKTIF -->
    <div x-show="isPinTargetMode && !showCommentModal"
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="-translate-y-8 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-150 transform"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-8 opacity-0"
         style="z-index: 9999999 !important;"
         class="fixed top-4 left-1/2 -translate-x-1/2 bg-red-600 text-white px-4 py-2.5 rounded-2xl shadow-2xl border-2 border-white flex items-center gap-3 text-xs font-semibold"
         x-cloak>
        <span class="flex h-2.5 w-2.5 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
        </span>
        <span>Mode Tandai Titik: Klik elemen mana saja (menu sidebar, tombol, tabel) untuk menaruh saran.</span>
        <button type="button" @click="isPinTargetMode = false" class="bg-red-800 hover:bg-red-900 text-white px-2.5 py-1 rounded-xl text-[11px] font-bold">Batal</button>
    </div>

    <!-- 5. FLOATING TRIGGER BAR BIASA (JIKA SESI BELUM MEMILIKI SARAN/CORETAN DI HALAMAN INI MAUPUN HALAMAN LAIN) -->
    <div x-show="sessionSaranList.length === 0 && !hasDrawing && otherPagesWithReview.length === 0 && !isDrawingMode && !showCommentModal"
         style="z-index: 9999998 !important;" 
         class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[9999998] flex items-center gap-2 bg-slate-900/95 backdrop-blur-md p-2 rounded-2xl shadow-2xl border-2 border-slate-700 transition-all hover:border-slate-500">
        
        <button type="button" 
                @click="startDrawing()" 
                title="Coret-coret Halaman (Pena)"
                class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-amber-400 hover:bg-amber-300 text-slate-950 transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            <span class="hidden sm:inline">Pena Coret</span>
        </button>

        <button type="button" 
                @click="togglePinTargetMode()" 
                title="Tandai Titik Bagan (Klik pada menu sidebar, tombol, atau tabel)"
                class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md"
                :class="isPinTargetMode ? 'bg-red-600 text-white ring-2 ring-red-400' : 'bg-red-500 hover:bg-red-600 text-white'">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            <span x-text="isPinTargetMode ? 'Batal Tandai' : 'Tandai Titik'"></span>
        </button>

        <button type="button" 
                @click="openNewCommentModal(null)" 
                title="Tulis Saran"
                class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-white hover:bg-slate-100 text-slate-950 transition-all shadow-md">
            <svg class="w-4 h-4 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <span>Saran</span>
        </button>
    </div>

    <!-- 6. TOMBOL MENCOLOK UNTUK KIRIM SARAN (TERSIMPAN & PERSISTEN MESKI REFRESH ATAU PINDAH-PINDAH HALAMAN) -->
    <div x-show="(sessionSaranList.length > 0 || hasDrawing || otherPagesWithReview.length > 0) && !isDrawingMode && !showCommentModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-8 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         style="z-index: 9999998 !important;"
         class="fixed bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 z-[9999998] w-[96vw] max-w-lg bg-slate-950/95 backdrop-blur-md text-white p-2 sm:p-3 rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.6)] border border-amber-400/90 flex flex-wrap items-center justify-between gap-2 select-none"
         x-cloak>
        
        <!-- Info Sesi Aktif -->
        <div class="flex items-center gap-2 min-w-0">
            <div class="flex h-7 w-7 sm:h-8 sm:w-8 shrink-0 items-center justify-center rounded-lg bg-amber-400 text-slate-950 font-bold shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs font-bold text-amber-400 flex items-center gap-1 leading-tight">
                    <span>Sesi Review Aktif</span>
                    <span class="text-[9px] bg-amber-400/20 text-amber-300 font-mono px-1 rounded">Tersimpan</span>
                </p>
                <p class="text-[10px] sm:text-[11px] text-slate-300 truncate font-medium mt-0.5">
                    <span x-text="totalSaranAllPages > 0 ? `${totalSaranAllPages} Catatan Masukan` : 'Coretan Halaman'"></span>
                    <span x-show="hasDrawing || otherPagesWithReview.length > 0" class="text-amber-300">&bull; Coretan</span>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-1 sm:gap-1.5 shrink-0">
            
            <button type="button" 
                    @click="startDrawing()" 
                    title="Tambah / Lanjutkan Coretan"
                    class="p-1.5 sm:px-2.5 sm:py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                <span class="hidden sm:inline">Pena</span>
            </button>

            <button type="button" 
                    @click="togglePinTargetMode()" 
                    title="Tandai Titik Bagan"
                    class="p-1.5 sm:px-2.5 sm:py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1 border"
                    :class="isPinTargetMode ? 'bg-red-600 text-white border-red-500 ring-1 ring-red-400' : 'bg-slate-800 hover:bg-slate-700 text-red-300 border-slate-700'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                <span class="hidden sm:inline" x-text="isPinTargetMode ? 'Batal' : 'Tandai'"></span>
            </button>

            <button type="button" 
                    @click="openNewCommentModal(null)" 
                    title="Tambah Catatan Saran"
                    class="p-1.5 sm:px-2.5 sm:py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="hidden sm:inline">Saran</span>
            </button>

            <button type="button" 
                    @click="resetEntireSession()" 
                    title="Batalkan Seluruh Sesi Review"
                    class="p-1.5 rounded-lg text-xs text-rose-400 hover:text-rose-300 hover:bg-rose-950/40 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>

            <!-- TOMBOL KIRIM SARAN (MENCOLOK & UTAMA) -->
            <button type="button" 
                    @click="submitFullSession()" 
                    :disabled="isSubmitting"
                    class="px-3 sm:px-4 py-1.5 rounded-lg sm:rounded-xl text-xs font-bold bg-amber-400 hover:bg-amber-300 text-slate-950 shadow-[0_0_15px_rgba(251,191,36,0.3)] transition-all flex items-center gap-1 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                <template x-if="!isSubmitting">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        <span class="font-bold">Kirim Semua</span>
                    </div>
                </template>
                <template x-if="isSubmitting">
                    <div class="flex items-center gap-1.5">
                        <svg class="animate-spin w-3.5 h-3.5 text-slate-950" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="font-bold">Mengirim...</span>
                    </div>
                </template>
            </button>
        </div>
    </div>

    <!-- 8. TOAST NOTIFIKASI SUKSES SETELAH KIRIM SARAN -->
    <div x-show="showSuccessToast"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-8 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-8 opacity-0"
         style="z-index: 9999999 !important;"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-emerald-950 text-white px-5 py-3.5 rounded-2xl shadow-2xl border-2 border-emerald-400 flex items-center gap-3 text-xs font-semibold"
         x-cloak>
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        <span x-text="successMessage || 'Seluruh saran dan coretan sesi ini berhasil dikirim. Terima kasih!'"></span>
        <button type="button" @click="showSuccessToast = false" class="text-emerald-400 hover:text-white ml-2 font-bold text-base">&times;</button>
    </div>

</div>

<script>
function reviewSystem(allowWelcomeToast = false) {
    return {
        // State
        showWelcomeToast: false,
        toastProgress: 100,
        toastTimer: null,
        
        isDrawingMode: false,
        isEraserMode: false,
        isPanMode: false,
        isInteractMode: false,
        isPinTargetMode: false,
        isToolbarMinimized: false,
        hasDrawing: false,

        // Posisi & Dragging Toolbar
        toolbarPos: { x: null, y: null },
        isDraggingToolbar: false,
        dragOffset: { x: 0, y: 0 },

        // Deteksi Tap 2x / Double Click pada Canvas Coretan
        lastCanvasClickTime: 0,
        lastCanvasClickPos: { x: 0, y: 0 },
        touchMouseDownStartPos: { x: 0, y: 0 },
        touchMouseDownStartTime: 0,
        hasMovedBeyondThreshold: false,

        togglePinTargetMode() {
            this.isPinTargetMode = !this.isPinTargetMode;
        },

        startDragToolbar(e) {
            const isTouch = !!e.touches;
            const clientX = isTouch ? e.touches[0].clientX : e.clientX;
            const clientY = isTouch ? e.touches[0].clientY : e.clientY;
            
            const toolbar = document.getElementById('review-drawing-toolbar');
            if (!toolbar) return;
            const rect = toolbar.getBoundingClientRect();

            this.isDraggingToolbar = true;
            this.dragOffset = {
                x: clientX - rect.left,
                y: clientY - rect.top
            };

            const onMove = (moveEvt) => {
                if (!this.isDraggingToolbar) return;
                const curX = moveEvt.touches ? moveEvt.touches[0].clientX : moveEvt.clientX;
                const curY = moveEvt.touches ? moveEvt.touches[0].clientY : moveEvt.clientY;

                let nextX = curX - this.dragOffset.x;
                let nextY = curY - this.dragOffset.y;

                const margin = 8;
                const maxX = window.innerWidth - rect.width - margin;
                const maxY = window.innerHeight - rect.height - margin;

                nextX = Math.max(margin, Math.min(maxX, nextX));
                nextY = Math.max(margin, Math.min(maxY, nextY));

                this.toolbarPos = { x: nextX, y: nextY };
                if (moveEvt.cancelable) moveEvt.preventDefault();
            };

            const onEnd = () => {
                this.isDraggingToolbar = false;
                window.removeEventListener('mousemove', onMove);
                window.removeEventListener('mouseup', onEnd);
                window.removeEventListener('touchmove', onMove);
                window.removeEventListener('touchend', onEnd);
            };

            window.addEventListener('mousemove', onMove, { passive: false });
            window.addEventListener('mouseup', onEnd);
            window.addEventListener('touchmove', onMove, { passive: false });
            window.addEventListener('touchend', onEnd);
        },

        getToolbarStyle() {
            let base = 'z-index: 9999999 !important;';
            if (this.toolbarPos.x !== null && this.toolbarPos.y !== null) {
                return `${base} left: ${this.toolbarPos.x}px !important; top: ${this.toolbarPos.y}px !important; bottom: auto !important; right: auto !important; transform: none !important;`;
            }
            return base;
        },

        setInteractMode() {
            this.isInteractMode = !this.isInteractMode;
            this.isPanMode = false;
            this.isEraserMode = false;
            this.isPinTargetMode = false;
        },

        triggerPinFromDrawingToolbar() {
            this.isPinTargetMode = !this.isPinTargetMode;
            this.isInteractMode = false;
            this.isPanMode = false;
        },

        hasSidebarMount: false,
        mainCanvasCtx: null,
        sidebarCanvasCtx: null,

        get mainSaranList() {
            this.scrollTick;
            return this.sessionSaranList
                .map((p, idx) => ({ ...p, originalIndex: idx }))
                .filter(p => !(p.annotation_scope === 'sidebar' || p.annotation_scope === 'dropdown_sidebar' || p.isSidebar || p.sumber_area === 'sidebar' || p.sumber_area === 'dropdown_sidebar'));
        },

        get sidebarSaranList() {
            this.scrollTick;
            return this.sessionSaranList
                .map((p, idx) => ({ ...p, originalIndex: idx }))
                .filter(p => (p.annotation_scope === 'sidebar' || p.annotation_scope === 'dropdown_sidebar' || p.isSidebar || p.sumber_area === 'sidebar' || p.sumber_area === 'dropdown_sidebar'));
        },

        get totalSaranAllPages() {
            this.scrollTick;
            const all = this.getAllSessions();
            let count = this.sessionSaranList.length;
            for (const path in all) {
                if (path === this.halamanPath || path === '__GLOBAL_SIDEBAR__') continue;
                if (all[path]?.sessionSaranList) {
                    count += all[path].sessionSaranList.length;
                }
            }
            return count;
        },

        resolveTargetElement(p, container = null) {
            if (p.targetEl && p.targetEl.isConnected && p.targetEl.id !== 'main-drawing-canvas' && p.targetEl.id !== 'sidebar-drawing-canvas' && p.targetEl.id !== 'main-annotation-layer' && p.targetEl.id !== 'sidebar-annotation-layer') {
                return p.targetEl;
            }
            const root = container || document;

            // 1. Target selector spesifik
            if (p.targetSelector && !p.targetSelector.includes('drawing-canvas') && !p.targetSelector.includes('annotation-layer')) {
                try {
                    const found = root.querySelector(p.targetSelector);
                    if (found && found.isConnected && found.id !== 'main-drawing-canvas' && found.id !== 'sidebar-drawing-canvas') {
                        p.targetEl = found;
                        return found;
                    }
                } catch (e) {}
            }

            // 2. Pencarian cerdas berdasarkan kata kunci teks snippet (contoh: Tabel Kolom "ANAK (5-9)" -> "ANAK (5-9)")
            const snippet = p.elementSnippet || p.bagian_elemen || p.detail_sumber || '';
            if (snippet) {
                let searchWord = null;
                const match = snippet.match(/"([^"]+)"/);
                if (match && match[1]) {
                    searchWord = match[1].trim();
                } else if (snippet.length > 3 && !snippet.startsWith('Elemen <') && !snippet.startsWith('Bagan ')) {
                    searchWord = snippet.trim();
                }

                if (searchWord && searchWord.length >= 2) {
                    const normSearch = searchWord.toLowerCase().replace(/\s+/g, ' ');
                    try {
                        // Prioritaskan th dan td jika snippet menyebut kata Tabel atau Kolom
                        if (snippet.toLowerCase().includes('tabel') || snippet.toLowerCase().includes('kolom')) {
                            const tableCells = root.querySelectorAll('th, td');
                            for (const el of tableCells) {
                                const text = (el.textContent || '').toLowerCase().replace(/\s+/g, ' ');
                                if (text.includes(normSearch) && !el.id?.includes('canvas')) {
                                    p.targetEl = el;
                                    return el;
                                }
                            }
                        }

                        const candidates = root.querySelectorAll('th, td, button, a, label, span, h1, h2, h3, h4, p, div');
                        for (const el of candidates) {
                            const text = (el.textContent || '').toLowerCase().replace(/\s+/g, ' ');
                            if (el.children.length <= 2 && text.includes(normSearch) && !el.id?.includes('canvas') && !el.id?.includes('review-system-root')) {
                                p.targetEl = el;
                                return el;
                            }
                        }
                    } catch (e) {}
                }
            }

            // 3. ID Sidebar Item
            if (p.sidebar_item_id) {
                try {
                    const found = root.querySelector(`[data-sidebar-item="${CSS.escape(p.sidebar_item_id)}"]`);
                    if (found && found.isConnected) { p.targetEl = found; return found; }
                } catch (e) {}
            }

            // 4. Dropdown Container ID
            if (p.dropdown_id) {
                try {
                    const found = root.querySelector(`[data-dropdown-container="${CSS.escape(p.dropdown_id)}"]`);
                    if (found && found.isConnected) { p.targetEl = found; return found; }
                } catch (e) {}
            }

            // 5. Sidebar Group ID
            if (p.sidebar_group_id) {
                try {
                    const found = root.querySelector(`[data-sidebar-group="${CSS.escape(p.sidebar_group_id)}"]`);
                    if (found && found.isConnected) { p.targetEl = found; return found; }
                } catch (e) {}
            }

            // 6. ID Komponen Main
            if (p.component_id && p.component_id.startsWith('main-')) {
                const id = p.component_id.replace(/^main-/, '');
                try {
                    const found = document.getElementById(id);
                    if (found && found.isConnected) { p.targetEl = found; return found; }
                } catch (e) {}
            }

            // 7. Estimasi elemen berdasarkan koordinat persentase
            if (p.xPct !== undefined && p.yPct !== undefined && !isNaN(p.xPct) && !isNaN(p.yPct)) {
                try {
                    const clientX = window.innerWidth * (p.xPct / 100);
                    const clientY = window.innerHeight * (p.yPct / 100);
                    const found = document.elementFromPoint(clientX, clientY);
                    if (found && found !== document.body && found !== document.documentElement && !found.id?.includes('canvas') && !found.id?.includes('layer') && !found.id?.includes('review-system')) {
                        p.targetEl = found;
                        return found;
                    }
                } catch (e) {}
            }

            return null;
        },

        getMainPinStyle(p) {
            this.scrollTick;
            const mainEl = document.querySelector('main');
            const mLayer = document.getElementById('main-annotation-layer');
            const mRect = mLayer ? mLayer.getBoundingClientRect() : (mainEl ? mainEl.getBoundingClientRect() : { left: 0, top: 0 });
            const mainRect = mainEl ? mainEl.getBoundingClientRect() : null;

            const targetEl = this.resolveTargetElement(p);
            let x = 0, y = 0, isVisible = true;
            if (targetEl && targetEl.isConnected) {
                const rect = targetEl.getBoundingClientRect();
                x = (rect.left - mRect.left) + (p.elemOffsetX !== undefined ? p.elemOffsetX : (rect.width * (p.elemOffsetRatioX || 0.5)));
                y = (rect.top - mRect.top) + (p.elemOffsetY !== undefined ? p.elemOffsetY : (rect.height * (p.elemOffsetRatioY || 0.5)));
                if (mainRect) {
                    isVisible = (rect.bottom >= mainRect.top && rect.top <= mainRect.bottom);
                } else {
                    isVisible = (rect.bottom >= 0 && rect.top <= window.innerHeight);
                }
            } else if (p.docX !== undefined && p.docY !== undefined && !isNaN(p.docX) && !isNaN(p.docY)) {
                x = p.docX - (mainEl ? mainEl.scrollLeft : 0);
                y = p.docY - (mainEl ? mainEl.scrollTop : 0);
                if (mainRect) {
                    const topBound = mainRect.top - mRect.top;
                    const bottomBound = mainRect.bottom - mRect.top;
                    isVisible = (y >= topBound && y <= bottomBound);
                }
            } else if (p.xPct !== undefined && p.yPct !== undefined && !isNaN(p.xPct) && !isNaN(p.yPct)) {
                const docW = mRect.width || (mainEl ? mainEl.clientWidth : window.innerWidth);
                const docH = mRect.height || (mainEl ? mainEl.clientHeight : window.innerHeight);
                x = (docW * (p.xPct / 100));
                y = (docH * (p.yPct / 100));
                isVisible = true;
            }

            return `left: ${Math.round(x)}px; top: ${Math.round(y)}px; display: ${isVisible ? 'block' : 'none'};`;
        },

        getSidebarPinStyle(p) {
            this.scrollTick;
            if (!this.isSidebarVisible()) return 'display: none;';

            const aside = document.querySelector('aside');
            if (!aside) return 'display: none;';

            if (p.annotation_scope === 'dropdown_sidebar' && p.dropdown_id) {
                if (!this.isDropdownVisible(p.dropdown_id)) {
                    return 'display: none;';
                }
            }

            const aRect = aside.getBoundingClientRect();
            const nav = aside.querySelector('nav') || aside;
            const navRect = nav ? nav.getBoundingClientRect() : aRect;

            const targetEl = this.resolveTargetElement(p, aside);
            let x = 0, y = 0, isVisible = true;
            if (targetEl && targetEl.isConnected && aside.contains(targetEl)) {
                const rect = targetEl.getBoundingClientRect();
                x = (rect.left - aRect.left) + (p.elemOffsetX !== undefined ? p.elemOffsetX : (rect.width * (p.elemOffsetRatioX || 0.5)));
                y = (rect.top - aRect.top) + (p.elemOffsetY !== undefined ? p.elemOffsetY : (rect.height * (p.elemOffsetRatioY || 0.5)));
                isVisible = (rect.bottom >= navRect.top && rect.top <= navRect.bottom);
            } else if (p.docX !== undefined && p.docY !== undefined && !isNaN(p.docX) && !isNaN(p.docY)) {
                const offsetY = navRect ? (navRect.top - aRect.top) : 0;
                x = p.docX;
                y = offsetY + p.docY - (nav ? nav.scrollTop : 0);
                isVisible = (y >= 0 && y <= aside.clientHeight);
            } else if (p.xPct !== undefined && p.yPct !== undefined && !isNaN(p.xPct) && !isNaN(p.yPct)) {
                x = aRect.width * (p.xPct / 100);
                y = aRect.height * (p.yPct / 100);
                isVisible = true;
            }

            return `left: ${Math.round(x)}px; top: ${Math.round(y)}px; display: ${isVisible ? 'block' : 'none'};`;
        },

        penColor: '#ef4444',
        penSize: 4,
        eraserSize: 28,

        // Sistem Anotasi Vektor (Melekat ke Koordinat Dokumen)
        strokes: [], // [ { id, color, size, isEraser, isMain, points: [ { x, y } ] } ]
        currentStroke: null,
        isDrawingNow: false,
        drawingSessionStartIndex: 0,
        redoStack: [],
        scrollTick: 0,

        compositeScreenshot: null,
        drawingDataUrl: null,
        canvasCtx: null,

        otherPagesWithReview: [],
        storageKey: 'KDMP_MULTI_PAGE_REVIEW_SESSIONS',

        sessionSaranList: [],
        currentPin: null,
        editingIndex: null,
        modalComment: '',

        showCommentModal: false,
        isSubmitting: false,
        showSuccessToast: false,
        successMessage: '',

        halamanPath: window.location.pathname,
        halamanUrl: window.location.href,
        judulHalaman: document.title || 'Halaman Aplikasi',

        // Speech & Secret Audio Recorder
        isListening: false,
        recognition: null,
        mediaRecorder: null,
        audioStream: null,
        audioChunks: [],
        recordedAudioDataUrl: null,
        hasRecordedAudio: false,

        // Mode Demo Interaktif Review (Presisi Live DOM)
        isDemoReviewMode: false,
        demoReviewData: null,
        demoPin: null,
        showDemoOverlay: false,
        showDemoPip: true,
        isPipMinimized: false,
        demoPinScreenStyle: 'display: none;',
        demoSpotlightBoxStyle: 'display: none;',

        init() {
            // 1. Muat sesi yang tersimpan di localStorage (aman dari refresh & navigasi antar halaman)
            this.loadCurrentPageSession();

            // 2. Deteksi apakah halaman dibuka dalam Mode Demo Review (misal via link "Lihat Jelas" di Secret Panel)
            const urlParams = new URLSearchParams(window.location.search);
            const demoId = urlParams.get('review_demo_id');
            if (demoId) {
                this.loadAndActivateDemoReview(demoId);
            }

            // 3. Popup petunjuk hanya muncul pada '/' dan '/dashboard', berdurasi tepat 7 detik
            const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
            const isTargetPage = (currentPath === '/' || currentPath === '/dashboard');

            if (allowWelcomeToast && isTargetPage) {
                this.showWelcomeToast = true;
                let start = Date.now();
                let duration = 7000;
                this.toastTimer = setInterval(() => {
                    let elapsed = Date.now() - start;
                    let remaining = Math.max(0, 100 - (elapsed / duration * 100));
                    this.toastProgress = remaining;
                    if (elapsed >= duration) {
                        clearInterval(this.toastTimer);
                        this.showWelcomeToast = false;
                    }
                }, 50);
            } else {
                this.showWelcomeToast = false;
            }

            this.$nextTick(() => {
                this.initCanvas();
            });

            this.setupScrollSynchronization();
            this.setupDoubleTapListener();
            this.setupSpeech();
            this.setupSidebarMutationObserver();

            window.addEventListener('resize', () => {
                this.initCanvas(true);
                this.redrawAllStrokes();
                this.updatePinElementsDirectly();
                if (this.isDemoReviewMode) {
                    this.updateDemoPinScreenPos();
                }
            });

            // Sinkronisasi status buka-tutup sidebar secara realtime (desktop & mobile)
            window.addEventListener('kdmp-sidebar-toggle', () => {
                this.scrollTick++;
                this.redrawAllStrokes();
                this.updatePinElementsDirectly();
                setTimeout(() => {
                    this.scrollTick++;
                    this.redrawAllStrokes();
                    this.updatePinElementsDirectly();
                }, 120);
                setTimeout(() => {
                    this.scrollTick++;
                    this.redrawAllStrokes();
                    this.updatePinElementsDirectly();
                }, 320);
            });

            // Sinkronisasi status collapse/expand dropdown accordion sidebar
            window.addEventListener('kdmp-dropdown-toggle', () => {
                this.scrollTick++;
                this.redrawAllStrokes();
                this.updatePinElementsDirectly();
                setTimeout(() => {
                    this.scrollTick++;
                    this.redrawAllStrokes();
                    this.updatePinElementsDirectly();
                }, 100);
                setTimeout(() => {
                    this.scrollTick++;
                    this.redrawAllStrokes();
                    this.updatePinElementsDirectly();
                }, 260);
                setTimeout(() => {
                    this.scrollTick++;
                    this.redrawAllStrokes();
                    this.updatePinElementsDirectly();
                }, 400);
            });

            // Simpan status terkini secara otomatis sebelum halaman tertutup/berpindah
            window.addEventListener('beforeunload', () => {
                this.persistCurrentPageSession();
            });
        },

        async loadAndActivateDemoReview(demoId) {
            try {
                const res = await fetch(`/secret/review/${demoId}/demo-data`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                this.demoReviewData = data;
                this.isDemoReviewMode = true;

                // 1. Rekonstruksi data Pin
                let pin = null;
                if (data.data_anotasi && data.data_anotasi.pin) {
                    pin = { ...data.data_anotasi.pin };
                } else {
                    pin = {
                        id: data.id,
                        text: data.komentar,
                        xPct: data.posisi_x,
                        yPct: data.posisi_y,
                        sumber_area: data.sumber_area,
                        annotation_scope: data.sumber_area,
                        detail_sumber: data.detail_sumber,
                        elementSnippet: data.bagian_elemen,
                        audioUrl: data.audio_rekaman
                    };
                }
                this.demoPin = pin;

                // 2. Rekonstruksi goresan vektor presisi jika ada
                if (data.data_anotasi && data.data_anotasi.strokes && data.data_anotasi.strokes.length > 0) {
                    this.strokes = [...data.data_anotasi.strokes];
                    this.hasDrawing = true;
                }

                // 3. Masukkan pin ke daftar aktif agar pin layer merendernya
                const exists = this.sessionSaranList.some(s => s.id === pin.id || s.text === pin.text);
                if (!exists) {
                    this.sessionSaranList.push(pin);
                }

                this.$nextTick(() => {
                    this.initCanvas(true);
                    this.redrawAllStrokes();

                    // 4. Jika target berada di sidebar / dropdown, buka secara otomatis
                    if (data.sumber_area === 'sidebar' || data.sumber_area === 'dropdown_sidebar') {
                        const aside = document.querySelector('aside');
                        if (aside) {
                            window.dispatchEvent(new CustomEvent('kdmp-sidebar-open'));
                            const alpineData = window.Alpine?.$data(document.body);
                            if (alpineData && 'sidebarOpen' in alpineData) {
                                alpineData.sidebarOpen = true;
                            }
                        }

                        const dropdownId = pin.dropdown_id || pin.sidebar_group_id;
                        if (dropdownId) {
                            const dropContainer = document.querySelector(`[data-dropdown-container="${CSS.escape(dropdownId)}"]`);
                            if (dropContainer) {
                                dropContainer.style.display = 'block';
                            }
                        }
                    }

                    // 5. Scroll otomatis secara mulus ke target element & aktifkan spotlight & demo pin
                    setTimeout(() => {
                        this.focusDemoTarget();
                    }, 350);
                });
            } catch (err) {
                console.warn('Gagal memuat demo review:', err);
            }
        },

        closeDemoReviewMode() {
            this.isDemoReviewMode = false;
            this.demoReviewData = null;
            this.demoPin = null;
            this.demoPinScreenStyle = 'display: none;';
            this.demoSpotlightBoxStyle = 'display: none;';
            const url = new URL(window.location.href);
            url.searchParams.delete('review_demo_id');
            window.history.replaceState({}, '', url.toString());
            this.loadCurrentPageSession();
            this.scrollTick++;
            this.redrawAllStrokes();
            this.updatePinElementsDirectly();
        },

        updateDemoPinScreenPos() {
            if (!this.isDemoReviewMode || !this.demoPin) {
                this.demoPinScreenStyle = 'display: none;';
                this.demoSpotlightBoxStyle = 'display: none;';
                return;
            }
            const pin = this.demoPin;
            const targetEl = this.resolveTargetElement(pin);
            let screenX = 0;
            let screenY = 0;
            let hasTarget = false;
            let tRect = null;

            if (targetEl && targetEl.isConnected) {
                tRect = targetEl.getBoundingClientRect();
                const offX = pin.elemOffsetX !== undefined ? pin.elemOffsetX : (tRect.width * (pin.elemOffsetRatioX || 0.5));
                const offY = pin.elemOffsetY !== undefined ? pin.elemOffsetY : 0;
                screenX = tRect.left + offX;
                screenY = tRect.top + offY;
                hasTarget = true;
            } else if (pin.docX !== undefined && pin.docY !== undefined && !isNaN(pin.docX) && !isNaN(pin.docY)) {
                const mainEl = document.querySelector('main');
                const scrollLeft = window.pageXOffset || (mainEl ? mainEl.scrollLeft : 0);
                const scrollTop = window.pageYOffset || (mainEl ? mainEl.scrollTop : 0);
                screenX = pin.docX - scrollLeft;
                screenY = pin.docY - scrollTop;
            } else if (pin.xPct !== undefined && pin.yPct !== undefined && !isNaN(pin.xPct) && !isNaN(pin.yPct)) {
                screenX = window.innerWidth * (pin.xPct / 100);
                screenY = window.innerHeight * (pin.yPct / 100);
            }

            const margin = 24;
            const clampedX = Math.max(margin, Math.min(window.innerWidth - margin, screenX));
            const clampedY = Math.max(margin + 50, Math.min(window.innerHeight - margin, screenY));

            this.demoPinScreenStyle = `left: ${Math.round(clampedX)}px; top: ${Math.round(clampedY)}px; transform: translate(-50%, -100%); display: block;`;

            if (hasTarget && tRect) {
                this.demoSpotlightBoxStyle = `left: ${Math.round(tRect.left - 4)}px; top: ${Math.round(tRect.top - 4)}px; width: ${Math.round(tRect.width + 8)}px; height: ${Math.round(tRect.height + 8)}px; display: block;`;
            } else {
                this.demoSpotlightBoxStyle = `left: ${Math.round(clampedX - 40)}px; top: ${Math.round(clampedY - 20)}px; width: 80px; height: 40px; display: block;`;
            }
        },

        focusDemoTarget() {
            if (!this.isDemoReviewMode || !this.demoPin) return;
            const pin = this.demoPin;
            const targetEl = this.resolveTargetElement(pin);

            // Buka sidebar jika target berada di sidebar
            if (pin.sumber_area === 'sidebar' || pin.sumber_area === 'dropdown_sidebar') {
                const aside = document.querySelector('aside');
                if (aside) {
                    window.dispatchEvent(new CustomEvent('kdmp-sidebar-open'));
                    const alpineData = window.Alpine?.$data(document.body);
                    if (alpineData && 'sidebarOpen' in alpineData) {
                        alpineData.sidebarOpen = true;
                    }
                }
                const dropdownId = pin.dropdown_id || pin.sidebar_group_id;
                if (dropdownId) {
                    const dropContainer = document.querySelector(`[data-dropdown-container="${CSS.escape(dropdownId)}"]`);
                    if (dropContainer) {
                        dropContainer.style.display = 'block';
                    }
                }
            }

            if (targetEl && targetEl.isConnected) {
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
                targetEl.classList.add('ring-4', 'ring-amber-400', 'ring-offset-4', 'ring-offset-slate-900', 'bg-amber-300/40', 'transition-all', 'duration-300');
                setTimeout(() => {
                    if (targetEl && targetEl.isConnected) {
                        targetEl.classList.remove('ring-4', 'ring-amber-400', 'ring-offset-4', 'ring-offset-slate-900', 'bg-amber-300/40');
                    }
                }, 8000);
            } else {
                const mainEl = document.querySelector('main');
                if (pin.docY !== undefined && !isNaN(pin.docY)) {
                    if (mainEl && mainEl.scrollHeight > mainEl.clientHeight) {
                        mainEl.scrollTo({ top: Math.max(0, pin.docY - (mainEl.clientHeight / 2)), behavior: 'smooth' });
                    } else {
                        window.scrollTo({ top: Math.max(0, pin.docY - (window.innerHeight / 2)), behavior: 'smooth' });
                    }
                }
            }

            this.updateDemoPinScreenPos();
            setTimeout(() => this.updateDemoPinScreenPos(), 150);
            setTimeout(() => this.updateDemoPinScreenPos(), 400);
            setTimeout(() => this.updateDemoPinScreenPos(), 700);
            setTimeout(() => this.updateDemoPinScreenPos(), 1000);
        },

        setupSidebarMutationObserver() {
            const aside = document.querySelector('aside');
            if (!aside || !aside.parentElement) return;
            let isThrottled = false;
            const observer = new MutationObserver(() => {
                if (isThrottled) return;
                isThrottled = true;
                requestAnimationFrame(() => {
                    this.scrollTick++;
                    if (this.strokes.length > 0) {
                        this.redrawAllStrokes();
                    }
                    isThrottled = false;
                });
            });
            // Hanya amati perubahan class pada pembungkus drawer (bukan subtree dan tidak memantau style)
            observer.observe(aside.parentElement, {
                attributes: true,
                attributeFilter: ['class'],
                subtree: false
            });
        },

        isElementVisibleInDOM(el) {
            if (!el || !el.isConnected) return false;
            if (el.offsetParent === null && el.tagName.toLowerCase() !== 'body') {
                const pos = window.getComputedStyle(el).position;
                if (pos !== 'fixed') return false;
            }
            const rect = el.getBoundingClientRect();
            if (rect.width === 0 && rect.height === 0) return false;
            return true;
        },

        isSidebarVisible() {
            if (typeof window.kdmpSidebarOpen !== 'undefined' && !window.kdmpSidebarOpen) {
                return false;
            }
            const aside = document.querySelector('aside');
            if (!aside) return false;
            if (!this.isElementVisibleInDOM(aside)) return false;
            const rect = aside.getBoundingClientRect();
            return (rect.width > 20 && rect.right > 20);
        },

        isDropdownVisible(dropdownId) {
            if (!dropdownId) return true;
            const container = document.querySelector(`[data-dropdown-container="${CSS.escape(dropdownId)}"]`);
            if (!container) return false;
            return this.isElementVisibleInDOM(container);
        },

        detectComponentScope(targetEl) {
            if (!targetEl || targetEl === document.body || targetEl === document.documentElement) {
                return {
                    annotation_scope: 'main_document',
                    sidebar_group_id: null,
                    dropdown_id: null,
                    sidebar_item_id: null,
                    component_id: 'document-body'
                };
            }

            const aside = targetEl.closest('aside');
            if (aside) {
                const dropdownContainer = targetEl.closest('[data-dropdown-container]');
                const groupEl = targetEl.closest('[data-sidebar-group]');
                const itemEl = targetEl.closest('[data-sidebar-item]');

                const sidebarGroupId = groupEl ? groupEl.getAttribute('data-sidebar-group') : null;
                const dropdownId = dropdownContainer ? dropdownContainer.getAttribute('data-dropdown-container') : null;
                const sidebarItemId = itemEl ? itemEl.getAttribute('data-sidebar-item') : null;

                if (dropdownContainer || dropdownId) {
                    return {
                        annotation_scope: 'dropdown_sidebar',
                        sidebar_group_id: sidebarGroupId || dropdownId,
                        dropdown_id: dropdownId || sidebarGroupId,
                        sidebar_item_id: sidebarItemId,
                        component_id: sidebarItemId ? `item-${sidebarItemId}` : `dropdown-${dropdownId || sidebarGroupId}`
                    };
                }

                return {
                    annotation_scope: 'sidebar',
                    sidebar_group_id: sidebarGroupId,
                    dropdown_id: null,
                    sidebar_item_id: sidebarItemId,
                    component_id: sidebarItemId ? `item-${sidebarItemId}` : (sidebarGroupId ? `group-${sidebarGroupId}` : 'sidebar-nav')
                };
            }

            const mainEl = targetEl.closest('main');
            return {
                annotation_scope: 'main_document',
                sidebar_group_id: null,
                dropdown_id: null,
                sidebar_item_id: null,
                component_id: targetEl.id ? `main-${targetEl.id}` : 'main-content'
            };
        },

        updatePinPositionsNow() {
            const mainEl = document.querySelector('main');
            const mLayer = document.getElementById('main-annotation-layer');
            const mRect = mLayer ? mLayer.getBoundingClientRect() : (mainEl ? mainEl.getBoundingClientRect() : { left: 0, top: 0 });
            const mainRect = mainEl ? mainEl.getBoundingClientRect() : null;

            const aside = document.querySelector('aside');
            const aRect = aside ? aside.getBoundingClientRect() : null;
            const nav = aside ? (aside.querySelector('nav') || aside) : null;
            const navRect = nav ? nav.getBoundingClientRect() : null;
            const isSidebarVis = this.isSidebarVisible();

            const pinEls = document.querySelectorAll('.review-pinpoint-marker');
            pinEls.forEach(pinEl => {
                const idx = parseInt(pinEl.getAttribute('data-pin-index'), 10);
                if (isNaN(idx)) return;
                const p = this.sessionSaranList[idx];
                if (!p) return;

                const isSidebar = (p.isSidebar || p.annotation_scope === 'sidebar' || p.annotation_scope === 'dropdown_sidebar');

                if (isSidebar) {
                    if (!isSidebarVis || !aside || !aRect) {
                        pinEl.style.display = 'none';
                        return;
                    }
                    if (p.annotation_scope === 'dropdown_sidebar' && p.dropdown_id) {
                        if (!this.isDropdownVisible(p.dropdown_id)) {
                            pinEl.style.display = 'none';
                            return;
                        }
                    }

                    const targetEl = this.resolveTargetElement(p, aside);
                    if (targetEl && targetEl.isConnected && aside.contains(targetEl)) {
                        const rect = targetEl.getBoundingClientRect();
                        const x = (rect.left - aRect.left) + (p.elemOffsetX !== undefined ? p.elemOffsetX : (rect.width * (p.elemOffsetRatioX || 0.5)));
                        const y = (rect.top - aRect.top) + (p.elemOffsetY !== undefined ? p.elemOffsetY : (rect.height * (p.elemOffsetRatioY || 0.5)));
                        const topLimit = navRect ? navRect.top : aRect.top;
                        const bottomLimit = navRect ? navRect.bottom : aRect.bottom;
                        const isVisible = (rect.bottom >= topLimit && rect.top <= bottomLimit);
                        pinEl.style.left = Math.round(x) + 'px';
                        pinEl.style.top = Math.round(y) + 'px';
                        pinEl.style.display = isVisible ? 'block' : 'none';
                    } else {
                        const offsetY = navRect ? (navRect.top - aRect.top) : 0;
                        const x = p.docX !== undefined ? p.docX : p.x;
                        const y = offsetY + (p.docY !== undefined ? p.docY : p.y) - (nav ? nav.scrollTop : 0);
                        const isVisible = (y >= 0 && y <= aside.clientHeight);
                        pinEl.style.left = Math.round(x) + 'px';
                        pinEl.style.top = Math.round(y) + 'px';
                        pinEl.style.display = isVisible ? 'block' : 'none';
                    }
                } else {
                    // Dokumen Utama
                    const targetEl = this.resolveTargetElement(p);
                    if (targetEl && targetEl.isConnected) {
                        const rect = targetEl.getBoundingClientRect();
                        const x = (rect.left - mRect.left) + (p.elemOffsetX !== undefined ? p.elemOffsetX : (rect.width * (p.elemOffsetRatioX || 0.5)));
                        const y = (rect.top - mRect.top) + (p.elemOffsetY !== undefined ? p.elemOffsetY : (rect.height * (p.elemOffsetRatioY || 0.5)));
                        const topLimit = mainRect ? mainRect.top : 0;
                        const bottomLimit = mainRect ? mainRect.bottom : window.innerHeight;
                        const isVisible = (rect.bottom >= topLimit && rect.top <= bottomLimit);
                        pinEl.style.left = Math.round(x) + 'px';
                        pinEl.style.top = Math.round(y) + 'px';
                        pinEl.style.display = isVisible ? 'block' : 'none';
                    } else {
                        const x = p.docX - (mainEl ? mainEl.scrollLeft : 0);
                        const y = p.docY - (mainEl ? mainEl.scrollTop : 0);
                        let isVisible = true;
                        if (mainRect) {
                            const topBound = mainRect.top - mRect.top;
                            const bottomBound = mainRect.bottom - mRect.top;
                            isVisible = (y >= topBound && y <= bottomBound);
                        }
                        pinEl.style.left = Math.round(x) + 'px';
                        pinEl.style.top = Math.round(y) + 'px';
                        pinEl.style.display = isVisible ? 'block' : 'none';
                    }
                }
            });

            if (this.isDemoReviewMode) {
                this.updateDemoPinScreenPos();
            }
        },

        setupScrollSynchronization() {
            let ticking = false;
            const onScroll = () => {
                if (!ticking) {
                    ticking = true;
                    requestAnimationFrame(() => {
                        this.scrollTick++;
                        this.updatePinPositionsNow();
                        if (this.strokes.length > 0) {
                            this.redrawAllStrokes();
                        }
                        ticking = false;
                    });
                }
            };
            const mainEl = document.querySelector('main');
            if (mainEl) {
                mainEl.addEventListener('scroll', onScroll, { passive: true });
            }
            const sidebar = document.querySelector('aside');
            if (sidebar) {
                const sidebarNav = sidebar.querySelector('nav') || sidebar;
                sidebarNav.addEventListener('scroll', onScroll, { passive: true });
            }
            window.addEventListener('scroll', onScroll, { passive: true });
        },

        updatePinElementsDirectly() {
            this.scrollTick++;
            this.updatePinPositionsNow();
        },

        getElementSelector(el) {
            if (!el || el === document.body || el === document.documentElement) return '';
            if (el.id) return '#' + CSS.escape(el.id);

            const itemAttr = el.closest('[data-sidebar-item]');
            if (itemAttr) {
                return `[data-sidebar-item="${CSS.escape(itemAttr.getAttribute('data-sidebar-item'))}"]`;
            }
            const dropAttr = el.closest('[data-dropdown-container]');
            if (dropAttr) {
                return `[data-dropdown-container="${CSS.escape(dropAttr.getAttribute('data-dropdown-container'))}"]`;
            }
            const groupAttr = el.closest('[data-sidebar-group]');
            if (groupAttr) {
                return `[data-sidebar-group="${CSS.escape(groupAttr.getAttribute('data-sidebar-group'))}"]`;
            }

            const parentWithId = el.closest('[id]');
            let prefix = '';
            let startEl = el;
            if (parentWithId && parentWithId !== document.body && parentWithId.id) {
                prefix = '#' + CSS.escape(parentWithId.id);
                if (el === parentWithId) return prefix;
                prefix += ' > ';
                startEl = el;
            } else {
                const asideParent = el.closest('aside');
                const mainParent = el.closest('main');
                if (asideParent) {
                    prefix = 'aside > ';
                } else if (mainParent) {
                    prefix = 'main > ';
                }
            }

            const path = [];
            let current = startEl;
            const boundary = parentWithId || el.closest('main') || el.closest('aside') || document.body;
            while (current && current !== boundary && current !== document.body) {
                let tag = current.tagName.toLowerCase();
                let sibling = current;
                let nth = 1;
                while ((sibling = sibling.previousElementSibling)) {
                    if (sibling.tagName && sibling.tagName.toLowerCase() === tag) nth++;
                }
                path.unshift(`${tag}:nth-of-type(${nth})`);
                current = current.parentElement;
            }
            return prefix + path.join(' > ');
        },

        setupDoubleTapListener() {
            let lastTouchEndTime = 0;
            let lastTouchPos = { x: 0, y: 0 };
            let touchStartX = 0;
            let touchStartY = 0;
            let touchMoved = false;

            // Touch tracking khusus mobile/touchscreen untuk deteksi gesture scroll vs tap
            document.addEventListener('touchstart', (e) => {
                if (e.touches.length > 1) {
                    touchMoved = true;
                    return;
                }
                touchMoved = false;
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }, { passive: true });

            document.addEventListener('touchmove', (e) => {
                if (e.touches.length === 1) {
                    const dist = Math.hypot(e.touches[0].clientX - touchStartX, e.touches[0].clientY - touchStartY);
                    if (dist > 12) {
                        touchMoved = true;
                        lastTouchEndTime = 0;
                    }
                }
            }, { passive: true });

            // Mobile / Touchscreen Double-Tap Detection via touchend
            document.addEventListener('touchend', (e) => {
                // 1. Jika pengguna sedang scroll/swipe, abaikan
                if (touchMoved) return;

                // 2. Jika mode drawing atau modal komentar aktif, jangan proses double tap
                if (this.isDrawingMode || this.showCommentModal || window.isReviewModalActive) return;

                // 3. Abaikan elemen review widget dan card popup modal
                if (e.target.closest('#review-system-root') || e.target.closest('.review-comment-popup')) return;

                // 4. Jika mode tandai titik aktif manual, biarkan event click yang menangani
                if (this.isPinTargetMode) return;

                const touch = e.changedTouches[0];
                if (!touch) return;

                const now = Date.now();
                const timeDiff = now - lastTouchEndTime;
                const dist = Math.hypot(touch.clientX - lastTouchPos.x, touch.clientY - lastTouchPos.y);

                // KASUS DOUBLE TAP TOUCHSCREEN (2 ketukan cepat dalam rentang 40ms - 320ms pada titik yang sama)
                if (timeDiff > 40 && timeDiff < 320 && dist < 35) {
                    lastTouchEndTime = 0;
                    if (e.cancelable) e.preventDefault();

                    const targetEl = document.elementFromPoint(touch.clientX, touch.clientY) || e.target;
                    this.handlePinEvent(touch.clientX, touch.clientY, targetEl);
                } else {
                    // Tap pertama biasa: catat waktu dan posisi, JANGAN cegah aksi klik normal aplikasi!
                    lastTouchEndTime = now;
                    lastTouchPos = { x: touch.clientX, y: touch.clientY };
                }
            }, { passive: false });

            // Desktop Native Double Click Listener (Bekerja 100% normal tanpa mengganggu single click)
            document.addEventListener('dblclick', (e) => {
                if (this.isDrawingMode || this.showCommentModal || window.isReviewModalActive || this.isPinTargetMode) return;
                if (e.target.closest('#review-system-root') || e.target.closest('.review-comment-popup')) return;

                e.preventDefault();
                e.stopPropagation();

                const clientX = e.clientX;
                const clientY = e.clientY;
                const targetEl = document.elementFromPoint(clientX, clientY) || e.target;
                this.handlePinEvent(clientX, clientY, targetEl);
            }, true);

            // Click listener HANYA aktif ketika mode tandai titik manual (isPinTargetMode) aktif!
            document.addEventListener('click', (e) => {
                if (!this.isPinTargetMode) return; // Mode normal: biarkan 100% native click lewat tanpa intervensi apapun!
                if (e.target.closest('#review-system-root') || e.target.closest('.review-comment-popup')) return;

                e.preventDefault();
                e.stopPropagation();
                this.isPinTargetMode = false;
                const targetEl = document.elementFromPoint(e.clientX, e.clientY) || e.target;
                this.handlePinEvent(e.clientX, e.clientY, targetEl);
            }, true);
        },

        handlePinEvent(clientX, clientY, targetEl) {
            const mainEl = document.querySelector('main');
            const sidebar = document.querySelector('aside');
            const sidebarNav = sidebar ? (sidebar.querySelector('nav') || sidebar) : null;
            const scopeInfo = this.detectComponentScope(targetEl);
            const meta = this.detectElementSourceMetadata(targetEl);

            let isMain = false;
            let isSidebar = false;
            let annotationScope = scopeInfo.annotation_scope || 'main_document';
            let docX = clientX;
            let docY = clientY;

            if (annotationScope === 'dropdown_sidebar') {
                isSidebar = true;
                const dropContainer = document.querySelector(`[data-dropdown-container="${CSS.escape(scopeInfo.dropdown_id)}"]`) || (targetEl ? targetEl.closest('[data-dropdown-container]') : null);
                if (dropContainer) {
                    const dRect = dropContainer.getBoundingClientRect();
                    docX = clientX - dRect.left;
                    docY = clientY - dRect.top + dropContainer.scrollTop;
                } else if (sidebar) {
                    const sRect = sidebar.getBoundingClientRect();
                    docX = clientX - sRect.left;
                    docY = clientY - sRect.top + (sidebarNav ? sidebarNav.scrollTop : 0);
                }
            } else if (annotationScope === 'sidebar' || (sidebar && (sidebar.contains(targetEl) || targetEl === sidebar))) {
                isSidebar = true;
                annotationScope = 'sidebar';
                if (sidebar) {
                    const sRect = sidebar.getBoundingClientRect();
                    docX = clientX - sRect.left;
                    docY = clientY - sRect.top + (sidebarNav ? sidebarNav.scrollTop : 0);
                }
            } else if (mainEl && (mainEl.contains(targetEl) || targetEl === mainEl)) {
                isMain = true;
                annotationScope = 'main_document';
                const mLayer = document.getElementById('main-annotation-layer');
                const mRect = mLayer ? mLayer.getBoundingClientRect() : mainEl.getBoundingClientRect();
                docX = clientX - mRect.left + mainEl.scrollLeft;
                docY = clientY - mRect.top + mainEl.scrollTop;
            } else {
                isMain = true;
                annotationScope = 'main_document';
                const mLayer = document.getElementById('main-annotation-layer');
                const mRect = mLayer ? mLayer.getBoundingClientRect() : { left: 0, top: 0 };
                docX = clientX - mRect.left + (window.scrollX || 0);
                docY = clientY - mRect.top + (window.scrollY || 0);
            }

            // Hitung offset geometri presisi relatif ke elemen yang diklik
            let elemOffsetX = 0;
            let elemOffsetY = 0;
            let elemOffsetRatioX = 0.5;
            let elemOffsetRatioY = 0.5;
            let targetSelector = '';

            if (targetEl && targetEl !== document.body && targetEl !== document.documentElement) {
                const elemRect = targetEl.getBoundingClientRect();
                elemOffsetX = clientX - elemRect.left;
                elemOffsetY = clientY - elemRect.top;
                elemOffsetRatioX = elemRect.width > 0 ? (elemOffsetX / elemRect.width) : 0.5;
                elemOffsetRatioY = elemRect.height > 0 ? (elemOffsetY / elemRect.height) : 0.5;
                targetSelector = this.getElementSelector(targetEl);
            }

            this.currentPin = {
                id: Date.now(),
                x: clientX,
                y: clientY,
                docX: docX,
                docY: docY,
                isMain: isMain,
                isSidebar: isSidebar,
                annotation_scope: annotationScope,
                sidebar_group_id: scopeInfo.sidebar_group_id || null,
                dropdown_id: scopeInfo.dropdown_id || null,
                sidebar_item_id: scopeInfo.sidebar_item_id || null,
                component_id: scopeInfo.component_id || null,
                targetEl: targetEl,
                targetSelector: targetSelector,
                elemOffsetX: elemOffsetX,
                elemOffsetY: elemOffsetY,
                elemOffsetRatioX: elemOffsetRatioX,
                elemOffsetRatioY: elemOffsetRatioY,
                xPct: (clientX / window.innerWidth) * 100,
                yPct: (clientY / window.innerHeight) * 100,
                sumber_area: meta.sumber_area,
                detail_sumber: meta.detail_sumber,
                elementSnippet: meta.elementSnippet || 'Elemen Layar',
                text: '',
                audioUrl: null
            };

            this.editingIndex = null;
            this.modalComment = '';
            this.recordedAudioDataUrl = null;
            this.hasRecordedAudio = false;
            this.showCommentModal = true;
            window.isReviewModalActive = true;
        },

        getPinScreenPos(p) {
            // Trigger Alpine reactivity dependency on scrollTick
            this.scrollTick;

            // 1. PIN MILIK DROPDOWN SIDEBAR
            if (p.annotation_scope === 'dropdown_sidebar' || p.sumber_area === 'dropdown_sidebar' || (p.dropdown_id && p.dropdown_id.length > 0)) {
                if (!this.isSidebarVisible() || !this.isDropdownVisible(p.dropdown_id)) {
                    return { x: -9999, y: -9999, isVisible: false };
                }

                const sidebar = document.querySelector('aside');
                if (!sidebar) return { x: -9999, y: -9999, isVisible: false };
                const sRect = sidebar.getBoundingClientRect();

                const dropContainer = document.querySelector(`[data-dropdown-container="${CSS.escape(p.dropdown_id)}"]`);
                if (!dropContainer) return { x: -9999, y: -9999, isVisible: false };
                const dRect = dropContainer.getBoundingClientRect();

                let targetEl = p.targetEl;
                if ((!targetEl || !targetEl.isConnected) && p.targetSelector) {
                    try {
                        targetEl = dropContainer.querySelector(p.targetSelector) || sidebar.querySelector(p.targetSelector) || document.querySelector(p.targetSelector);
                        if (targetEl) p.targetEl = targetEl;
                    } catch (e) {}
                }

                if (targetEl && targetEl.isConnected && dropContainer.contains(targetEl)) {
                    const rect = targetEl.getBoundingClientRect();
                    const x = rect.left + (p.elemOffsetX !== undefined ? p.elemOffsetX : (rect.width * (p.elemOffsetRatioX || 0.5)));
                    const y = rect.top + (p.elemOffsetY !== undefined ? p.elemOffsetY : (rect.height * (p.elemOffsetRatioY || 0.5)));
                    const isVisible = rect.bottom >= Math.max(sRect.top, dRect.top) && rect.top <= Math.min(sRect.bottom, dRect.bottom) && rect.right >= sRect.left && rect.left <= sRect.right;
                    return { x, y, isVisible };
                }

                const x = (p.docX !== undefined ? p.docX : p.x) + dRect.left;
                const y = (p.docY !== undefined ? p.docY : p.y) + dRect.top - dropContainer.scrollTop;
                const isVisible = (x >= sRect.left && x <= sRect.right && y >= dRect.top && y <= dRect.bottom);
                return { x, y, isVisible };
            }

            const isSidebarPin = (p.annotation_scope === 'sidebar' || p.isSidebar || p.sumber_area === 'sidebar' || (p.targetSelector && (p.targetSelector.includes('aside') || p.targetSelector.includes('data-sidebar'))));

            // Jika pin milik sidebar
            if (isSidebarPin) {
                // Sembunyikan sepenuhnya saat sidebar tertutup
                if (!this.isSidebarVisible()) {
                    return { x: -9999, y: -9999, isVisible: false };
                }

                const sidebar = document.querySelector('aside');
                if (!sidebar) return { x: -9999, y: -9999, isVisible: false };
                const sRect = sidebar.getBoundingClientRect();

                let targetEl = p.targetEl;
                if ((!targetEl || !targetEl.isConnected) && p.targetSelector) {
                    try {
                        targetEl = sidebar.querySelector(p.targetSelector) || document.querySelector(p.targetSelector);
                        if (targetEl) p.targetEl = targetEl;
                    } catch (e) {}
                }

                if (targetEl && targetEl.isConnected && sidebar.contains(targetEl)) {
                    const rect = targetEl.getBoundingClientRect();
                    const x = rect.left + (p.elemOffsetX !== undefined ? p.elemOffsetX : (rect.width * (p.elemOffsetRatioX || 0.5)));
                    const y = rect.top + (p.elemOffsetY !== undefined ? p.elemOffsetY : (rect.height * (p.elemOffsetRatioY || 0.5)));
                    const isVisible = rect.bottom >= sRect.top && rect.top <= sRect.bottom && rect.right >= sRect.left && rect.left <= sRect.right;
                    return { x, y, isVisible };
                }

                const nav = sidebar.querySelector('nav') || sidebar;
                const x = (p.docX !== undefined ? p.docX : p.x) + sRect.left;
                const y = (p.docY !== undefined ? p.docY : p.y) + sRect.top - (nav ? nav.scrollTop : 0);
                const isVisible = (x >= sRect.left && x <= sRect.right && y >= sRect.top && y <= sRect.bottom);
                return { x, y, isVisible };
            }

            // Pin Dokumen Utama
            let targetEl = p.targetEl;
            if ((!targetEl || !targetEl.isConnected) && p.targetSelector) {
                try {
                    targetEl = document.querySelector(p.targetSelector);
                    if (targetEl) p.targetEl = targetEl;
                } catch (e) {}
            }

            if (targetEl && targetEl.isConnected) {
                const rect = targetEl.getBoundingClientRect();
                const x = rect.left + (p.elemOffsetX !== undefined ? p.elemOffsetX : (rect.width * (p.elemOffsetRatioX || 0.5)));
                const y = rect.top + (p.elemOffsetY !== undefined ? p.elemOffsetY : (rect.height * (p.elemOffsetRatioY || 0.5)));
                const isVisible = rect.bottom >= -20 && rect.top <= window.innerHeight + 20 && rect.right >= -20 && rect.left <= window.innerWidth + 20;
                return { x, y, isVisible };
            }

            // Koordinat dokumen dengan kompensasi scroll kontainer
            const s = this.getScreenCoordinates({ x: p.docX || p.x, y: p.docY || p.y }, p);
            const isVisible = (s.y >= -40 && s.y <= window.innerHeight + 40 && s.x >= -40 && s.x <= window.innerWidth + 40);
            return { x: s.x, y: s.y, isVisible };
        },

        getPinStyle(p) {
            const pos = this.getPinScreenPos(p);
            return `left: ${pos.x}px; top: ${pos.y}px; display: ${pos.isVisible ? 'block' : 'none'};`;
        },

        openNewCommentModal() {
            const clientX = Math.round(window.innerWidth / 2);
            const clientY = Math.round(window.innerHeight / 2);
            const mainEl = document.querySelector('main');
            let isMain = false;
            let docX = clientX;
            let docY = clientY;
            if (mainEl) {
                isMain = true;
                const rect = mainEl.getBoundingClientRect();
                docX = clientX - rect.left + mainEl.scrollLeft;
                docY = clientY - rect.top + mainEl.scrollTop;
            } else {
                isMain = true;
                docX = clientX + (window.scrollX || 0);
                docY = clientY + (window.scrollY || 0);
            }

            this.currentPin = {
                id: Date.now(),
                x: clientX,
                y: clientY,
                docX: docX,
                docY: docY,
                isMain: isMain,
                targetEl: null,
                targetSelector: '',
                elemOffsetX: 0,
                elemOffsetY: 0,
                elemOffsetRatioX: 0.5,
                elemOffsetRatioY: 0.5,
                xPct: 50,
                yPct: 50,
                sumber_area: 'dokumen_utama',
                detail_sumber: 'Halaman Dokumen Utama',
                elementSnippet: 'Umum / Halaman Saat Ini',
                text: '',
                audioUrl: null
            };
            this.editingIndex = null;
            this.modalComment = '';
            this.recordedAudioDataUrl = null;
            this.hasRecordedAudio = false;
            this.showCommentModal = true;
            window.isReviewModalActive = true;
        },

        editExistingSaran(index) {
            let item = this.sessionSaranList[index];
            if (!item) return;
            this.editingIndex = index;
            this.currentPin = item;
            this.modalComment = item.text || '';
            this.recordedAudioDataUrl = item.audioUrl || null;
            this.hasRecordedAudio = !!item.audioUrl;
            this.showCommentModal = true;
            window.isReviewModalActive = true;
        },

        async saveCurrentSaranToSession() {
            let commentText = this.modalComment.trim();
            if (!commentText && this.hasDrawing) {
                commentText = 'Coretan pada layar halaman ini.';
            }
            if (!commentText) return;

            await this.stopSpeechAndRecording();

            if (this.audioChunks.length > 0 && !this.recordedAudioDataUrl) {
                await new Promise(r => setTimeout(r, 120));
            }

            let saranItem = {
                ...this.currentPin,
                text: commentText,
                audioUrl: this.recordedAudioDataUrl
            };

            if (this.editingIndex !== null) {
                this.sessionSaranList[this.editingIndex] = saranItem;
            } else {
                this.sessionSaranList.push(saranItem);
            }

            this.persistCurrentPageSession(this.compositeScreenshot);
            this.closeCommentModal();

            // Tangkap potret nyata halaman bersama coretan dan pin
            setTimeout(async () => {
                const snap = await this.captureCurrentPageSnapshot();
                if (snap) {
                    this.compositeScreenshot = snap;
                    this.persistCurrentPageSession(snap);
                }
            }, 60);
        },

        deleteCurrentSaran() {
            if (this.editingIndex !== null) {
                this.sessionSaranList.splice(this.editingIndex, 1);
            }
            this.persistCurrentPageSession();
            this.closeCommentModal();
        },

        closeCommentModal() {
            this.showCommentModal = false;
            window.isReviewModalActive = false;
            this.stopSpeechAndRecording();
            this.currentPin = null;
            this.editingIndex = null;
            this.modalComment = '';
        },

        // --- SISTEM PERSISTENSI SESI LINTAS HALAMAN & REFRESH (localStorage) ---
        getAllSessions() {
            try {
                const raw = localStorage.getItem(this.storageKey);
                return raw ? JSON.parse(raw) : {};
            } catch (e) {
                return {};
            }
        },

        updateOtherPagesInfo(sessions) {
            const all = sessions || this.getAllSessions();
            this.otherPagesWithReview = Object.keys(all).filter(path => {
                if (path === this.halamanPath || path === '__GLOBAL_SIDEBAR__') return false;
                const item = all[path];
                return (item && ((item.sessionSaranList && item.sessionSaranList.length > 0) || item.hasDrawing));
            });
        },

        loadCurrentPageSession() {
            const sessions = this.getAllSessions();
            this.updateOtherPagesInfo(sessions);

            const draft = sessions[this.halamanPath];
            const pageSaran = draft?.sessionSaranList || [];

            // Muat saran sidebar global (agar saran sidebar/dropdown selalu ada di setiap halaman)
            const globalSidebar = sessions['__GLOBAL_SIDEBAR__'];
            const sidebarSaran = globalSidebar?.sessionSaranList || [];

            // Gabungkan tanpa duplikasi ID
            const existingIds = new Set(pageSaran.map(s => s.id));
            const mergedSidebar = sidebarSaran.filter(s => !existingIds.has(s.id));

            this.sessionSaranList = [...pageSaran, ...mergedSidebar];
            this.strokes = draft?.strokes || [];
            this.hasDrawing = this.strokes.length > 0 || !!draft?.hasDrawing;
            this.compositeScreenshot = draft?.compositeScreenshot || null;
            this.drawingDataUrl = draft?.drawingDataUrl || null;
            this.$nextTick(() => {
                this.redrawAllStrokes();
                this.updatePinElementsDirectly();
            });
        },

        persistCurrentPageSession(compositeScreenshot = null) {
            try {
                const sessions = this.getAllSessions();

                // Pisahkan saran sidebar dan saran halaman dokumen saat ini
                const sidebarSaran = this.sessionSaranList.filter(s => 
                    s.annotation_scope === 'sidebar' || s.annotation_scope === 'dropdown_sidebar' || s.isSidebar || s.sumber_area === 'sidebar' || s.sumber_area === 'dropdown_sidebar'
                );
                const pageDocSaran = this.sessionSaranList.filter(s => 
                    !(s.annotation_scope === 'sidebar' || s.annotation_scope === 'dropdown_sidebar' || s.isSidebar || s.sumber_area === 'sidebar' || s.sumber_area === 'dropdown_sidebar')
                );

                // 1. Simpan saran sidebar ke session global sidebar agar persisten di seluruh rute
                if (sidebarSaran.length > 0) {
                    sessions['__GLOBAL_SIDEBAR__'] = {
                        halamanPath: '__GLOBAL_SIDEBAR__',
                        sessionSaranList: sidebarSaran,
                        updatedAt: Date.now()
                    };
                } else {
                    delete sessions['__GLOBAL_SIDEBAR__'];
                }

                // 2. Simpan saran dokumen & coretan untuk rute halaman saat ini
                if (pageDocSaran.length > 0 || this.hasDrawing || this.strokes.length > 0) {
                    const prev = sessions[this.halamanPath] || {};
                    sessions[this.halamanPath] = {
                        halamanPath: this.halamanPath,
                        halamanUrl: window.location.href,
                        judulHalaman: document.title || 'Halaman Aplikasi',
                        sessionSaranList: pageDocSaran,
                        strokes: this.strokes,
                        drawingDataUrl: this.drawingDataUrl,
                        hasDrawing: this.hasDrawing || this.strokes.length > 0,
                        compositeScreenshot: compositeScreenshot || this.compositeScreenshot || prev.compositeScreenshot || null,
                        updatedAt: Date.now()
                    };
                } else {
                    delete sessions[this.halamanPath];
                }

                if (Object.keys(sessions).length === 0) {
                    try {
                        localStorage.removeItem(this.storageKey);
                    } catch (e) {}
                    this.updateOtherPagesInfo({});
                    return;
                }
                
                try {
                    localStorage.setItem(this.storageKey, JSON.stringify(sessions));
                } catch (quotaErr) {
                    console.warn('LocalStorage quota limit reached, saving lightweight draft:', quotaErr);
                    const lightSessions = JSON.parse(JSON.stringify(sessions));
                    for (const p in lightSessions) {
                        delete lightSessions[p].compositeScreenshot;
                        delete lightSessions[p].drawingDataUrl;
                        if (lightSessions[p].sessionSaranList) {
                            lightSessions[p].sessionSaranList.forEach(s => { delete s.audioUrl; });
                        }
                    }
                    localStorage.setItem(this.storageKey, JSON.stringify(lightSessions));
                }

                this.updateOtherPagesInfo(sessions);
            } catch (err) {
                console.warn('localStorage save failed:', err);
            }
        },

        startDrawing() {
            this.drawingSessionStartIndex = this.strokes.length;
            this.isDrawingMode = true;
            this.isEraserMode = false;
            this.isPanMode = false;
            this.isInteractMode = false;
            this.isPinTargetMode = false;
            this.isToolbarMinimized = false;
            window.isReviewDrawingActive = true;
            this.$nextTick(() => {
                this.initCanvas(false);
                this.redrawAllStrokes();
            });
        },

        setPenMode() {
            this.isEraserMode = false;
            this.isPanMode = false;
            this.isInteractMode = false;
            this.isPinTargetMode = false;
            this.isDrawingMode = true;
        },

        setEraserModeToggle() {
            this.isEraserMode = !this.isEraserMode;
            this.isPanMode = false;
            this.isInteractMode = false;
            this.isPinTargetMode = false;
            this.isDrawingMode = true;
        },

        setPanMode() {
            this.isPanMode = true;
            this.isEraserMode = false;
            this.isInteractMode = false;
            this.isPinTargetMode = false;
        },

        setPenColor(color) {
            this.penColor = color;
            this.isEraserMode = false;
            this.isPanMode = false;
            this.isInteractMode = false;
            this.isPinTargetMode = false;
            this.isDrawingMode = true;
        },

        async finishDrawing() {
            // 1. Tangkap potret nyata halaman bersama coretan yang baru dibuat
            try {
                const snap = await this.captureCurrentPageSnapshot();
                if (snap) {
                    this.compositeScreenshot = snap;
                }
            } catch (err) {
                console.warn('Gagal ambil snapshot:', err);
            }

            this.isDrawingMode = false;
            this.isEraserMode = false;
            this.isPanMode = false;
            this.isInteractMode = false;
            this.isPinTargetMode = false;
            window.isReviewDrawingActive = false;

            this.persistCurrentPageSession(this.compositeScreenshot);
            this.openCommentModalForDrawing();
        },

        openCommentModalForDrawing() {
            // 1. Ambil goresan yang baru saja dibuat pada sesi coretan ini
            let recentStrokes = [];
            const startIndex = (this.drawingSessionStartIndex !== undefined && this.drawingSessionStartIndex !== null)
                ? this.drawingSessionStartIndex
                : (this.strokes.length - 1);

            if (this.strokes && this.strokes.length > 0) {
                recentStrokes = this.strokes.slice(Math.max(0, startIndex));
                if (recentStrokes.length === 0) {
                    recentStrokes = [this.strokes[this.strokes.length - 1]];
                }
            }

            let clientX = Math.round(window.innerWidth / 2);
            let clientY = Math.round(window.innerHeight / 2);
            let docX = clientX;
            let docY = clientY;
            let isSidebar = false;
            let isMain = true;
            let annotationScope = 'main_document';
            let dropdownId = null;
            let sidebarGroupId = null;
            let sidebarItemId = null;
            let componentId = null;
            let targetEl = null;

            if (recentStrokes.length > 0) {
                // Kumpulkan seluruh titik goresan untuk menemukan pusat geometris coretan
                let minClientX = Infinity, maxClientX = -Infinity;
                let minClientY = Infinity, maxClientY = -Infinity;
                let minDocX = Infinity, maxDocX = -Infinity;
                let minDocY = Infinity, maxDocY = -Infinity;
                let pointCount = 0;

                for (const stroke of recentStrokes) {
                    if (!stroke.points || stroke.points.length === 0) continue;
                    for (const pt of stroke.points) {
                        const cx = pt.clientX !== undefined ? pt.clientX : (pt.x || 0);
                        const cy = pt.clientY !== undefined ? pt.clientY : (pt.y || 0);
                        const dx = pt.docX !== undefined ? pt.docX : (pt.x || 0);
                        const dy = pt.docY !== undefined ? pt.docY : (pt.y || 0);

                        if (cx < minClientX) minClientX = cx;
                        if (cx > maxClientX) maxClientX = cx;
                        if (cy < minClientY) minClientY = cy;
                        if (cy > maxClientY) maxClientY = cy;

                        if (dx < minDocX) minDocX = dx;
                        if (dx > maxDocX) maxDocX = dx;
                        if (dy < minDocY) minDocY = dy;
                        if (dy > maxDocY) maxDocY = dy;
                        pointCount++;
                    }
                }

                const lastStroke = recentStrokes[recentStrokes.length - 1];
                annotationScope = lastStroke.annotation_scope || (lastStroke.isSidebar ? 'sidebar' : 'main_document');
                isSidebar = (annotationScope === 'dropdown_sidebar' || annotationScope === 'sidebar' || lastStroke.isSidebar);
                isMain = !isSidebar;
                dropdownId = lastStroke.dropdown_id || null;
                sidebarGroupId = lastStroke.sidebar_group_id || null;
                componentId = lastStroke.component_id || null;

                if (pointCount > 0 && isFinite(minClientX) && isFinite(minClientY)) {
                    clientX = Math.round((minClientX + maxClientX) / 2);
                    clientY = Math.round((minClientY + maxClientY) / 2);
                }

                // Cari elemen tepat di bawah area coretan (nonaktifkan pointer-events canvas sementara)
                const mainCanvas = document.getElementById('main-drawing-canvas');
                const sidebarCanvas = document.getElementById('sidebar-drawing-canvas');
                const prevMainPE = mainCanvas ? mainCanvas.style.pointerEvents : '';
                const prevSidePE = sidebarCanvas ? sidebarCanvas.style.pointerEvents : '';
                if (mainCanvas) mainCanvas.style.pointerEvents = 'none';
                if (sidebarCanvas) sidebarCanvas.style.pointerEvents = 'none';

                targetEl = document.elementFromPoint(clientX, clientY);

                // Jika targetEl jatuh pada background/wrapper, gunakan elemen target awal goresan
                if (!targetEl || targetEl === document.body || targetEl === document.documentElement || targetEl.id === 'main-annotation-layer' || targetEl.id === 'sidebar-annotation-layer') {
                    for (const s of recentStrokes.slice().reverse()) {
                        if (s.targetEl && s.targetEl.isConnected && s.targetEl !== document.body && s.targetEl !== document.documentElement) {
                            targetEl = s.targetEl;
                            break;
                        }
                    }
                }

                if (mainCanvas) mainCanvas.style.pointerEvents = prevMainPE;
                if (sidebarCanvas) sidebarCanvas.style.pointerEvents = prevSidePE;

                if (targetEl) {
                    const scopeInfo = this.detectComponentScope(targetEl);
                    if (isSidebar) {
                        if (!dropdownId && scopeInfo.dropdown_id) dropdownId = scopeInfo.dropdown_id;
                        if (!sidebarGroupId && scopeInfo.sidebar_group_id) sidebarGroupId = scopeInfo.sidebar_group_id;
                        if (!sidebarItemId && scopeInfo.sidebar_item_id) sidebarItemId = scopeInfo.sidebar_item_id;
                        if (!componentId && scopeInfo.component_id) componentId = scopeInfo.component_id;
                    }
                }
            }

            // Hitung docX dan docY secara presisi
            if (isSidebar) {
                const aside = document.querySelector('aside');
                const aRect = aside ? aside.getBoundingClientRect() : { left: 0, top: 0 };
                const nav = aside ? (aside.querySelector('nav') || aside) : null;
                const navRect = nav ? nav.getBoundingClientRect() : aRect;
                docX = clientX - aRect.left;
                docY = (clientY - navRect.top) + (nav ? nav.scrollTop : 0);
            } else {
                const mainEl = document.querySelector('main');
                const mLayer = document.getElementById('main-annotation-layer');
                const mRect = mLayer ? mLayer.getBoundingClientRect() : (mainEl ? mainEl.getBoundingClientRect() : { left: 0, top: 0 });
                docX = (clientX - mRect.left) + (mainEl ? mainEl.scrollLeft : 0);
                docY = (clientY - mRect.top) + (mainEl ? mainEl.scrollTop : 0);
            }

            let elemOffsetX = 0;
            let elemOffsetY = 0;
            let elemOffsetRatioX = 0.5;
            let elemOffsetRatioY = 0.5;
            let targetSelector = '';

            if (targetEl && targetEl !== document.body && targetEl !== document.documentElement) {
                const elemRect = targetEl.getBoundingClientRect();
                elemOffsetX = clientX - elemRect.left;
                elemOffsetY = clientY - elemRect.top;
                elemOffsetRatioX = elemRect.width > 0 ? (elemOffsetX / elemRect.width) : 0.5;
                elemOffsetRatioY = elemRect.height > 0 ? (elemOffsetY / elemRect.height) : 0.5;
                targetSelector = this.getElementSelector(targetEl);
            }

            const meta = targetEl ? this.detectElementSourceMetadata(targetEl) : this.detectContextFromCurrentReview();

            this.currentPin = {
                id: Date.now(),
                x: clientX,
                y: clientY,
                docX: docX,
                docY: docY,
                isMain: isMain,
                isSidebar: isSidebar,
                annotation_scope: annotationScope,
                dropdown_id: dropdownId,
                sidebar_group_id: sidebarGroupId,
                sidebar_item_id: sidebarItemId,
                component_id: componentId,
                targetEl: targetEl,
                targetSelector: targetSelector,
                elemOffsetX: elemOffsetX,
                elemOffsetY: elemOffsetY,
                elemOffsetRatioX: elemOffsetRatioX,
                elemOffsetRatioY: elemOffsetRatioY,
                xPct: (clientX / window.innerWidth) * 100,
                yPct: (clientY / window.innerHeight) * 100,
                sumber_area: isSidebar ? (annotationScope === 'dropdown_sidebar' ? 'dropdown_sidebar' : 'sidebar') : 'dokumen_utama',
                detail_sumber: meta.detail_sumber || (isSidebar ? 'Sidebar Navigasi' : ('Coretan pada ' + (document.title || this.judulHalaman))),
                elementSnippet: meta.elementSnippet || (isSidebar ? 'Coretan Sidebar' : ('Coretan Layar (' + (document.title || this.judulHalaman) + ')')),
                text: '',
                audioUrl: null
            };
            this.editingIndex = null;
            this.modalComment = '';
            this.recordedAudioDataUrl = null;
            this.hasRecordedAudio = false;
            this.showCommentModal = true;
            window.isReviewModalActive = true;
        },

        initCanvas(forceRecreate = false) {
            this.hasSidebarMount = !!document.getElementById('sidebar-annotation-mount');
            const dpr = window.devicePixelRatio || 1;

            // 1. Inisialisasi Canvas Dokumen Utama
            const mainCanvas = document.getElementById('main-drawing-canvas') || document.getElementById('review-drawing-canvas');
            const mainContainer = document.getElementById('main-annotation-layer') || mainCanvas?.parentElement;
            if (mainCanvas && mainContainer) {
                const w = mainContainer.clientWidth || window.innerWidth;
                const h = mainContainer.clientHeight || window.innerHeight;
                const targetW = Math.round(w * dpr);
                const targetH = Math.round(h * dpr);

                if (forceRecreate || mainCanvas.width !== targetW || mainCanvas.height !== targetH || !this.mainCanvasCtx) {
                    mainCanvas.width = targetW;
                    mainCanvas.height = targetH;
                    mainCanvas.style.width = w + 'px';
                    mainCanvas.style.height = h + 'px';

                    const ctx = mainCanvas.getContext('2d');
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    this.mainCanvasCtx = ctx;
                }
            }

            // 2. Inisialisasi Canvas Sidebar
            const sidebarCanvas = document.getElementById('sidebar-drawing-canvas');
            const aside = document.querySelector('aside');
            if (sidebarCanvas && aside) {
                const w = aside.clientWidth || 288;
                const h = aside.clientHeight || window.innerHeight;
                const targetW = Math.round(w * dpr);
                const targetH = Math.round(h * dpr);

                if (forceRecreate || sidebarCanvas.width !== targetW || sidebarCanvas.height !== targetH || !this.sidebarCanvasCtx) {
                    sidebarCanvas.width = targetW;
                    sidebarCanvas.height = targetH;
                    sidebarCanvas.style.width = w + 'px';
                    sidebarCanvas.style.height = h + 'px';

                    const ctx = sidebarCanvas.getContext('2d');
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    this.sidebarCanvasCtx = ctx;
                }
            }

            this.redrawAllStrokes();
        },

        getScreenCoordinates(pt, isMainOrStroke, isSidebar = false) {
            if (isMainOrStroke && typeof isMainOrStroke === 'object') {
                isSidebar = (isMainOrStroke.annotation_scope === 'sidebar' || isMainOrStroke.annotation_scope === 'dropdown_sidebar' || isMainOrStroke.isSidebar);
            }
            const px = pt.docX !== undefined ? pt.docX : (pt.x !== undefined ? pt.x : (pt.clientX || 0));
            const py = pt.docY !== undefined ? pt.docY : (pt.y !== undefined ? pt.y : (pt.clientY || 0));

            if (isSidebar) {
                const sidebar = document.querySelector('aside');
                if (sidebar) {
                    const sRect = sidebar.getBoundingClientRect();
                    const nav = sidebar.querySelector('nav') || sidebar;
                    const navRect = nav ? nav.getBoundingClientRect() : sRect;
                    const offsetY = navRect ? (navRect.top - sRect.top) : 0;
                    return {
                        x: sRect.left + px,
                        y: sRect.top + offsetY + py - (nav ? nav.scrollTop : 0),
                        isVisible: true
                    };
                }
                return { x: px, y: py, isVisible: true };
            }

            const mainEl = document.querySelector('main');
            const mLayer = document.getElementById('main-annotation-layer');
            const mRect = mLayer ? mLayer.getBoundingClientRect() : (mainEl ? mainEl.getBoundingClientRect() : { left: 0, top: 0 });
            if (mainEl) {
                return {
                    x: mRect.left + px - mainEl.scrollLeft,
                    y: mRect.top + py - mainEl.scrollTop,
                    isVisible: true
                };
            }
            return {
                x: px - (window.scrollX || 0),
                y: py - (window.scrollY || 0),
                isVisible: true
            };
        },

        drawDirectSegment(p1, p2, stroke) {
            const isSidebar = (stroke.annotation_scope === 'sidebar' || stroke.annotation_scope === 'dropdown_sidebar' || stroke.isSidebar);
            const ctx = isSidebar ? this.sidebarCanvasCtx : this.mainCanvasCtx;
            if (!ctx) return;

            const dpr = window.devicePixelRatio || 1;
            ctx.save();
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            if (isSidebar) {
                const aside = document.querySelector('aside');
                const nav = aside ? (aside.querySelector('nav') || aside) : null;
                const navRect = nav ? nav.getBoundingClientRect() : null;
                const aRect = aside ? aside.getBoundingClientRect() : { left: 0, top: 0 };

                // Canvas Clipping Sidebar: Batasi goresan hanya di dalam batas nav yang bergulir
                if (navRect && aRect) {
                    ctx.beginPath();
                    const clipTop = Math.max(0, navRect.top - aRect.top);
                    const clipHeight = nav.clientHeight;
                    const clipWidth = aside.clientWidth;
                    ctx.rect(0, clipTop, clipWidth, clipHeight);
                    ctx.clip();
                }

                if (stroke.annotation_scope === 'dropdown_sidebar' && stroke.dropdown_id) {
                    const dropContainer = document.querySelector(`[data-dropdown-container="${CSS.escape(stroke.dropdown_id)}"]`);
                    if (dropContainer && aside) {
                        const dRect = dropContainer.getBoundingClientRect();
                        ctx.beginPath();
                        ctx.rect(dRect.left - aRect.left, dRect.top - aRect.top, dRect.width, dRect.height);
                        ctx.clip();
                    }
                }

                const x1 = p1.canvasX !== undefined ? p1.canvasX : (p1.clientX - aRect.left);
                const y1 = p1.canvasY !== undefined ? p1.canvasY : (p1.clientY - aRect.top);
                const x2 = p2.canvasX !== undefined ? p2.canvasX : (p2.clientX - aRect.left);
                const y2 = p2.canvasY !== undefined ? p2.canvasY : (p2.clientY - aRect.top);

                ctx.strokeStyle = stroke.color;
                ctx.lineWidth = stroke.size;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.stroke();
            } else {
                const mainEl = document.querySelector('main');
                const mLayer = document.getElementById('main-annotation-layer');
                const mRect = mLayer ? mLayer.getBoundingClientRect() : { left: 0, top: 0 };
                const mainRect = mainEl ? mainEl.getBoundingClientRect() : null;

                // Canvas Clipping Dokumen Utama: Jangan menggambar di atas header sticky
                if (mainRect && mLayer) {
                    ctx.beginPath();
                    const clipTop = Math.max(0, mainRect.top - mRect.top);
                    const clipHeight = mainEl.clientHeight;
                    const clipWidth = mLayer.clientWidth;
                    ctx.rect(0, clipTop, clipWidth, clipHeight);
                    ctx.clip();
                }

                const x1 = p1.canvasX !== undefined ? p1.canvasX : (p1.clientX - mRect.left);
                const y1 = p1.canvasY !== undefined ? p1.canvasY : (p1.clientY - mRect.top);
                const x2 = p2.canvasX !== undefined ? p2.canvasX : (p2.clientX - mRect.left);
                const y2 = p2.canvasY !== undefined ? p2.canvasY : (p2.clientY - mRect.top);

                ctx.strokeStyle = stroke.color;
                ctx.lineWidth = stroke.size;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.stroke();
            }

            ctx.restore();
        },

        redrawAllStrokes() {
            const dpr = window.devicePixelRatio || 1;

            // 1. Redraw Canvas Dokumen Utama
            const mainCanvas = document.getElementById('main-drawing-canvas');
            if (mainCanvas && this.mainCanvasCtx) {
                const ctx = this.mainCanvasCtx;
                ctx.save();
                ctx.setTransform(1, 0, 0, 1, 0, 0);
                ctx.clearRect(0, 0, mainCanvas.width, mainCanvas.height);
                ctx.restore();

                const mainEl = document.querySelector('main');
                const mLayer = document.getElementById('main-annotation-layer');
                const mRect = mLayer ? mLayer.getBoundingClientRect() : { left: 0, top: 0 };
                const mainRect = mainEl ? mainEl.getBoundingClientRect() : null;
                const scrollLeft = mainEl ? mainEl.scrollLeft : 0;
                const scrollTop = mainEl ? mainEl.scrollTop : 0;
                const offsetX = mainRect ? (mainRect.left - mRect.left) : 0;
                const offsetY = mainRect ? (mainRect.top - mRect.top) : 0;

                ctx.save();
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

                // Canvas Clipping: Jangan menggambar melewati batas main
                if (mainRect && mLayer) {
                    ctx.beginPath();
                    const clipTop = Math.max(0, mainRect.top - mRect.top);
                    const clipHeight = mainEl.clientHeight;
                    const clipWidth = mLayer.clientWidth;
                    ctx.rect(0, clipTop, clipWidth, clipHeight);
                    ctx.clip();
                }

                for (const stroke of this.strokes) {
                    if (!stroke.points || stroke.points.length === 0) continue;
                    const isSidebar = (stroke.annotation_scope === 'sidebar' || stroke.annotation_scope === 'dropdown_sidebar' || stroke.isSidebar);
                    if (isSidebar) continue;

                    ctx.strokeStyle = stroke.color;
                    ctx.lineWidth = stroke.size;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    ctx.beginPath();
                    for (let i = 0; i < stroke.points.length; i++) {
                        const pt = stroke.points[i];
                        const x = offsetX + (pt.docX !== undefined ? pt.docX : pt.x) - scrollLeft;
                        const y = offsetY + (pt.docY !== undefined ? pt.docY : pt.y) - scrollTop;
                        if (i === 0) ctx.moveTo(x, y);
                        else ctx.lineTo(x, y);
                    }
                    ctx.stroke();
                }
                ctx.restore();
            }

            // 2. Redraw Canvas Sidebar
            const sidebarCanvas = document.getElementById('sidebar-drawing-canvas');
            if (sidebarCanvas && this.sidebarCanvasCtx) {
                const ctx = this.sidebarCanvasCtx;
                ctx.save();
                ctx.setTransform(1, 0, 0, 1, 0, 0);
                ctx.clearRect(0, 0, sidebarCanvas.width, sidebarCanvas.height);
                ctx.restore();

                const aside = document.querySelector('aside');
                const aRect = aside ? aside.getBoundingClientRect() : { left: 0, top: 0 };
                const nav = aside ? (aside.querySelector('nav') || aside) : null;
                const navRect = nav ? nav.getBoundingClientRect() : null;
                const scrollTop = nav ? nav.scrollTop : 0;
                const offsetY = navRect ? (navRect.top - aRect.top) : 0;

                ctx.save();
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

                if (navRect && aRect) {
                    ctx.beginPath();
                    const clipTop = Math.max(0, navRect.top - aRect.top);
                    const clipHeight = nav.clientHeight;
                    const clipWidth = aside.clientWidth;
                    ctx.rect(0, clipTop, clipWidth, clipHeight);
                    ctx.clip();
                }

                for (const stroke of this.strokes) {
                    if (!stroke.points || stroke.points.length === 0) continue;
                    const isSidebar = (stroke.annotation_scope === 'sidebar' || stroke.annotation_scope === 'dropdown_sidebar' || stroke.isSidebar);
                    if (!isSidebar) continue;

                    if (stroke.annotation_scope === 'dropdown_sidebar' && stroke.dropdown_id) {
                        if (!this.isDropdownVisible(stroke.dropdown_id)) {
                            continue;
                        }
                    }

                    ctx.save();
                    if (stroke.annotation_scope === 'dropdown_sidebar' && stroke.dropdown_id) {
                        const dropContainer = document.querySelector(`[data-dropdown-container="${CSS.escape(stroke.dropdown_id)}"]`);
                        if (dropContainer && aside) {
                            const dRect = dropContainer.getBoundingClientRect();
                            ctx.beginPath();
                            ctx.rect(dRect.left - aRect.left, dRect.top - aRect.top, dRect.width, dRect.height);
                            ctx.clip();
                        }
                    }

                    ctx.strokeStyle = stroke.color;
                    ctx.lineWidth = stroke.size;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    ctx.beginPath();
                    for (let i = 0; i < stroke.points.length; i++) {
                        const pt = stroke.points[i];
                        const x = pt.docX !== undefined ? pt.docX : pt.x;
                        const y = offsetY + (pt.docY !== undefined ? pt.docY : pt.y) - scrollTop;
                        if (i === 0) ctx.moveTo(x, y);
                        else ctx.lineTo(x, y);
                    }
                    ctx.stroke();
                    ctx.restore();
                }
                ctx.restore();
            }
        },

        undo() {
            if (this.strokes.length === 0) return;
            const last = this.strokes.pop();
            this.redoStack.push(last);
            this.hasDrawing = this.strokes.length > 0;
            this.redrawAllStrokes();
            this.persistCurrentPageSession();
        },

        redo() {
            if (this.redoStack.length === 0) return;
            const next = this.redoStack.pop();
            this.strokes.push(next);
            this.hasDrawing = true;
            this.redrawAllStrokes();
            this.persistCurrentPageSession();
        },

        handleMouseDown(e, scope = 'main_document') {
            if (!this.isDrawingMode || this.isPanMode || e.button !== 0) return;
            e.preventDefault();

            this.touchMouseDownStartPos = { x: e.clientX, y: e.clientY };
            this.touchMouseDownStartTime = Date.now();
            this.hasMovedBeyondThreshold = false;

            this.startStroke(e, scope);
        },

        handleMouseMove(e, scope = 'main_document') {
            if (!this.isDrawingMode || this.isPanMode) return;
            if (!this.touchMouseDownStartTime) return;

            const dist = Math.hypot(e.clientX - this.touchMouseDownStartPos.x, e.clientY - this.touchMouseDownStartPos.y);
            if (dist >= 4) {
                this.hasMovedBeyondThreshold = true;
                this.drawStroke(e, scope);
            }
        },

        handleMouseUp(e, scope = 'main_document') {
            if (!this.isDrawingMode || this.isPanMode) return;
            this.touchMouseDownStartTime = 0;
            if (this.isDrawingNow) {
                this.endStroke();
            }
        },

        handleTouchStart(e, scope = 'main_document') {
            if (!this.isDrawingMode || this.isPanMode) return;
            if (e.touches.length > 1) {
                this.isDrawingNow = false;
                return;
            }
            if (e.cancelable) e.preventDefault();
            const touch = e.touches[0];
            this.touchMouseDownStartPos = { x: touch.clientX, y: touch.clientY };
            this.touchMouseDownStartTime = Date.now();
            this.hasMovedBeyondThreshold = false;

            this.startStroke({ clientX: touch.clientX, clientY: touch.clientY }, scope);
        },

        handleTouchMove(e, scope = 'main_document') {
            if (!this.isDrawingMode || this.isPanMode) return;
            if (e.touches.length > 1) {
                this.isDrawingNow = false;
                return;
            }
            const touch = e.touches[0];
            const dist = Math.hypot(touch.clientX - this.touchMouseDownStartPos.x, touch.clientY - this.touchMouseDownStartPos.y);
            if (dist >= 4) {
                this.hasMovedBeyondThreshold = true;
                if (e.cancelable) e.preventDefault();
                this.drawStroke({ clientX: touch.clientX, clientY: touch.clientY }, scope);
            }
        },

        handleTouchEnd(e, scope = 'main_document') {
            if (!this.isDrawingMode || this.isPanMode) return;
            this.touchMouseDownStartTime = 0;
            if (this.isDrawingNow) {
                this.endStroke();
            }
        },

                handleWheel(e) {
            const clientX = e.clientX;
            const clientY = e.clientY;
            const sidebar = document.querySelector('aside');
            if (sidebar) {
                const sRect = sidebar.getBoundingClientRect();
                if (clientX >= sRect.left && clientX <= sRect.right && clientY >= sRect.top && clientY <= sRect.bottom) {
                    const nav = sidebar.querySelector('nav') || sidebar;
                    nav.scrollTop += e.deltaY;
                    nav.scrollLeft += e.deltaX;
                    return;
                }
            }

            const mainEl = document.querySelector('main');
            if (mainEl) {
                mainEl.scrollTop += e.deltaY;
                mainEl.scrollLeft += e.deltaX;
            } else {
                window.scrollBy(e.deltaX, e.deltaY);
            }
        },

        startStroke(e, explicitScope = null) {
            this.isDrawingNow = true;
            const clientX = e.clientX;
            const clientY = e.clientY;

            const aside = document.querySelector('aside');
            const mainEl = document.querySelector('main');

            let underEl = null;
            const targetEl = document.elementFromPoint(clientX, clientY);
            if (targetEl && (targetEl.id === 'main-drawing-canvas' || targetEl.id === 'sidebar-drawing-canvas')) {
                targetEl.style.pointerEvents = 'none';
                underEl = document.elementFromPoint(clientX, clientY);
                targetEl.style.pointerEvents = 'auto';
            } else {
                underEl = targetEl;
            }

            const scopeInfo = this.detectComponentScope(underEl);
            let isSidebar = false;
            let isMain = false;
            let annotationScope = 'main_document';
            let dropdownId = null;
            let sidebarGroupId = null;
            let componentId = null;
            let targetSelector = '';

            if (underEl && underEl !== document.body && underEl !== document.documentElement) {
                targetSelector = this.getElementSelector(underEl);
            }

            let docX = 0;
            let docY = 0;
            let canvasX = 0;
            let canvasY = 0;

            if (explicitScope === 'sidebar' || scopeInfo.annotation_scope === 'dropdown_sidebar' || scopeInfo.annotation_scope === 'sidebar' || (aside && aside.contains(underEl))) {
                isSidebar = true;
                isMain = false;
                annotationScope = scopeInfo.annotation_scope || 'sidebar';
                dropdownId = scopeInfo.dropdown_id || null;
                sidebarGroupId = scopeInfo.sidebar_group_id || null;
                componentId = scopeInfo.component_id || null;

                const aRect = aside ? aside.getBoundingClientRect() : { left: 0, top: 0 };
                const nav = aside ? (aside.querySelector('nav') || aside) : null;
                const navRect = nav ? nav.getBoundingClientRect() : aRect;

                canvasX = clientX - aRect.left;
                canvasY = clientY - aRect.top;
                docX = canvasX;
                docY = (clientY - navRect.top) + (nav ? nav.scrollTop : 0);
            } else {
                isMain = true;
                isSidebar = false;
                annotationScope = 'main_document';
                componentId = scopeInfo.component_id || null;

                const mLayer = document.getElementById('main-annotation-layer');
                const mRect = mLayer ? mLayer.getBoundingClientRect() : (mainEl ? mainEl.getBoundingClientRect() : { left: 0, top: 0 });
                const mainRect = mainEl ? mainEl.getBoundingClientRect() : mRect;

                canvasX = clientX - mRect.left;
                canvasY = clientY - mRect.top;
                docX = (clientX - mainRect.left) + (mainEl ? mainEl.scrollLeft : 0);
                docY = (clientY - mainRect.top) + (mainEl ? mainEl.scrollTop : 0);
            }

            if (this.isEraserMode) {
                this.eraseAtPoint(clientX, clientY, annotationScope);
                return;
            }

            this.currentStroke = {
                id: Date.now(),
                color: this.penColor,
                size: this.penSize,
                isEraser: false,
                isMain: isMain,
                isSidebar: isSidebar,
                annotation_scope: annotationScope,
                dropdown_id: dropdownId,
                sidebar_group_id: sidebarGroupId,
                component_id: componentId,
                targetSelector: targetSelector,
                targetEl: underEl,
                points: [{ x: docX, y: docY, docX: docX, docY: docY, canvasX: canvasX, canvasY: canvasY, clientX: clientX, clientY: clientY }]
            };
        },

        drawStroke(e, explicitScope = null) {
            if (!this.isDrawingNow || !this.currentStroke) return;
            const clientX = e.clientX;
            const clientY = e.clientY;

            if (this.isEraserMode) {
                this.eraseAtPoint(clientX, clientY, this.currentStroke.annotation_scope);
                return;
            }

            let docX = 0;
            let docY = 0;
            let canvasX = 0;
            let canvasY = 0;

            if (this.currentStroke.isSidebar) {
                const aside = document.querySelector('aside');
                if (aside) {
                    const aRect = aside.getBoundingClientRect();
                    // Cegah goresan merembet keluar dari sidebar ke dokumen utama
                    if (clientX < aRect.left || clientX > aRect.right || clientY < aRect.top || clientY > aRect.bottom) {
                        this.endStroke();
                        return;
                    }
                }
                const aRect = aside ? aside.getBoundingClientRect() : { left: 0, top: 0 };
                const nav = aside ? (aside.querySelector('nav') || aside) : null;
                const navRect = nav ? nav.getBoundingClientRect() : aRect;

                canvasX = clientX - aRect.left;
                canvasY = clientY - aRect.top;
                docX = canvasX;
                docY = (clientY - navRect.top) + (nav ? nav.scrollTop : 0);
            } else {
                const aside = document.querySelector('aside');
                // Cegah goresan dokumen utama merembet ke atas sidebar saat sidebar terbuka
                if (aside && this.isSidebarVisible()) {
                    const aRect = aside.getBoundingClientRect();
                    if (clientX >= aRect.left && clientX <= aRect.right && clientY >= aRect.top && clientY <= aRect.bottom) {
                        this.endStroke();
                        return;
                    }
                }
                const mainEl = document.querySelector('main');
                const mLayer = document.getElementById('main-annotation-layer');
                const mRect = mLayer ? mLayer.getBoundingClientRect() : (mainEl ? mainEl.getBoundingClientRect() : { left: 0, top: 0 });
                const mainRect = mainEl ? mainEl.getBoundingClientRect() : mRect;

                canvasX = clientX - mRect.left;
                canvasY = clientY - mRect.top;
                docX = (clientX - mainRect.left) + (mainEl ? mainEl.scrollLeft : 0);
                docY = (clientY - mainRect.top) + (mainEl ? mainEl.scrollTop : 0);
            }

            const prevPt = this.currentStroke.points[this.currentStroke.points.length - 1];
            const dx = clientX - prevPt.clientX;
            const dy = clientY - prevPt.clientY;
            if (dx * dx + dy * dy >= 4) {
                const newPt = { x: docX, y: docY, docX: docX, docY: docY, canvasX: canvasX, canvasY: canvasY, clientX: clientX, clientY: clientY };
                this.currentStroke.points.push(newPt);
                this.drawDirectSegment(prevPt, newPt, this.currentStroke);
                this.hasDrawing = true;
            }
        },

        endStroke() {
            if (this.isDrawingNow) {
                this.isDrawingNow = false;
                if (this.currentStroke && this.currentStroke.points.length > 1) {
                    this.strokes.push(this.currentStroke);
                    this.redoStack = [];
                    this.hasDrawing = true;
                    this.persistCurrentPageSession();
                }
                this.currentStroke = null;
            }
        },

        eraseAtPoint(clientX, clientY, scope) {
            const radius = (this.eraserSize || 28) / 2;
            let modified = false;

            const aside = document.querySelector('aside');
            const mainEl = document.querySelector('main');
            const isSidebar = (scope === 'sidebar' || scope === 'dropdown_sidebar');

            this.strokes = this.strokes.filter(stroke => {
                const strokeIsSidebar = (stroke.annotation_scope === 'sidebar' || stroke.annotation_scope === 'dropdown_sidebar' || stroke.isSidebar);
                if (strokeIsSidebar !== isSidebar) return true;

                if (stroke.annotation_scope === 'dropdown_sidebar' && !this.isDropdownVisible(stroke.dropdown_id)) {
                    return true;
                }

                for (const pt of stroke.points) {
                    let screenX, screenY;
                    const px = pt.docX !== undefined ? pt.docX : (pt.x || 0);
                    const py = pt.docY !== undefined ? pt.docY : (pt.y || 0);
                    if (strokeIsSidebar) {
                        const aRect = aside ? aside.getBoundingClientRect() : { left: 0, top: 0 };
                        const nav = aside ? (aside.querySelector('nav') || aside) : null;
                        const navRect = nav ? nav.getBoundingClientRect() : aRect;
                        const offsetY = navRect ? (navRect.top - aRect.top) : 0;
                        screenX = aRect.left + px;
                        screenY = aRect.top + offsetY + py - (nav ? nav.scrollTop : 0);
                    } else {
                        const mLayer = document.getElementById('main-annotation-layer');
                        const mRect = mLayer ? mLayer.getBoundingClientRect() : (mainEl ? mainEl.getBoundingClientRect() : { left: 0, top: 0 });
                        screenX = mRect.left + px - (mainEl ? mainEl.scrollLeft : 0);
                        screenY = mRect.top + py - (mainEl ? mainEl.scrollTop : 0);
                    }

                    const distSq = (screenX - clientX) * (screenX - clientX) + (screenY - clientY) * (screenY - clientY);
                    if (distSq <= radius * radius) {
                        modified = true;
                        return false;
                    }
                }
                return true;
            });

            if (modified) {
                this.hasDrawing = this.strokes.length > 0;
                this.redrawAllStrokes();
                this.persistCurrentPageSession();
            }
        },

                clearCanvas() {
            this.strokes = [];
            this.redoStack = [];
            this.hasDrawing = false;
            this.drawingDataUrl = null;
            this.compositeScreenshot = null;
            this.redrawAllStrokes();

            this.isEraserMode = false;
            this.isPanMode = false;
            this.isInteractMode = false;
            this.isPinTargetMode = false;
            this.isDrawingMode = true;
            this.persistCurrentPageSession();
        },

        resetEntireSession(showConfirm = true) {
            let count = this.otherPagesWithReview.length + (this.sessionSaranList.length > 0 || this.hasDrawing ? 1 : 0);
            let msg = count > 1 
                ? `Batalkan dan bersihkan seluruh catatan saran dan coretan pada sesi ini (${count} halaman)?` 
                : 'Batalkan dan bersihkan seluruh catatan saran dan coretan pada sesi halaman ini?';

            if (!showConfirm || confirm(msg)) {
                // 1. Bersihkan seluruh penyimpanan lokal multi-halaman
                try {
                    localStorage.removeItem(this.storageKey);
                } catch (e) {}

                // 2. Kosongkan seluruh state catatan dan coretan aktif
                this.sessionSaranList = [];
                this.strokes = [];
                this.redoStack = [];
                this.hasDrawing = false;
                this.drawingDataUrl = null;
                this.compositeScreenshot = null;
                this.otherPagesWithReview = [];
                this.isDrawingMode = false;
                this.isEraserMode = false;
                this.isPanMode = false;
                this.isInteractMode = false;
                this.isPinTargetMode = false;
                window.isReviewDrawingActive = false;

                // 3. Bersihkan fisik piksel kanvas main dan sidebar
                const mainC = document.getElementById('main-drawing-canvas');
                if (mainC && this.mainCanvasCtx) {
                    this.mainCanvasCtx.save();
                    this.mainCanvasCtx.setTransform(1, 0, 0, 1, 0, 0);
                    this.mainCanvasCtx.clearRect(0, 0, mainC.width, mainC.height);
                    this.mainCanvasCtx.restore();
                }
                const sideC = document.getElementById('sidebar-drawing-canvas');
                if (sideC && this.sidebarCanvasCtx) {
                    this.sidebarCanvasCtx.save();
                    this.sidebarCanvasCtx.setTransform(1, 0, 0, 1, 0, 0);
                    this.sidebarCanvasCtx.clearRect(0, 0, sideC.width, sideC.height);
                    this.sidebarCanvasCtx.restore();
                }

                // 4. Tutup dialog komentar dan perbarui tampilan DOM
                this.closeCommentModal();
                this.scrollTick++;
                this.$nextTick(() => {
                    this.updatePinElementsDirectly();
                });
            }
        },

        // --- SISTEM SUARA & RAHASIA AUDIO RECORDING ---
        setupSpeech() {
            const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRec) return;

            this.recognition = new SpeechRec();
            this.recognition.lang = 'id-ID';
            this.recognition.continuous = true;
            this.recognition.interimResults = true;

            let finalTranscript = '';

            this.recognition.onresult = (e) => {
                let interim = '';
                for (let i = e.resultIndex; i < e.results.length; ++i) {
                    if (e.results[i].isFinal) {
                        finalTranscript += e.results[i][0].transcript + ' ';
                    } else {
                        interim += e.results[i][0].transcript;
                    }
                }
                let full = (finalTranscript + interim).trim();
                if (full) {
                    this.modalComment = full;
                }
            };

            this.recognition.onerror = (e) => {
                if (e.error !== 'no-speech') {
                    this.isListening = false;
                }
            };

            this.recognition.onend = () => {
                this.isListening = false;
            };
        },

        async toggleSpeechAndRecording() {
            if (this.isListening) {
                await this.stopSpeechAndRecording();
            } else {
                await this.startSpeechAndRecording();
            }
        },

        async startSpeechAndRecording() {
            try {
                this.audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.mediaRecorder = new MediaRecorder(this.audioStream);
                this.audioChunks = [];

                this.mediaRecorder.ondataavailable = (e) => {
                    if (e.data.size > 0) this.audioChunks.push(e.data);
                };

                this.mediaRecorder.onstop = () => {
                    if (this.audioChunks.length > 0) {
                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                        const reader = new FileReader();
                        reader.onloadend = () => {
                            this.recordedAudioDataUrl = reader.result;
                            this.hasRecordedAudio = true;
                        };
                        reader.readAsDataURL(audioBlob);
                    }
                };

                this.mediaRecorder.start();
            } catch (err) {
                console.warn('Microphone stream error:', err);
            }

            if (this.recognition) {
                try {
                    this.recognition.start();
                    this.isListening = true;
                } catch (e) {
                    this.isListening = false;
                }
            } else {
                this.isListening = true;
            }
        },

        async stopSpeechAndRecording() {
            return new Promise((resolve) => {
                this.isListening = false;

                if (this.recognition) {
                    try { this.recognition.stop(); } catch (e) {}
                }

                if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
                    const prevOnStop = this.mediaRecorder.onstop;
                    this.mediaRecorder.onstop = (e) => {
                        if (prevOnStop) prevOnStop(e);
                        resolve();
                    };
                    try {
                        this.mediaRecorder.stop();
                    } catch (e) {
                        resolve();
                    }
                } else {
                    resolve();
                }

                if (this.audioStream) {
                    this.audioStream.getTracks().forEach(track => track.stop());
                    this.audioStream = null;
                }
            });
        },

        getDeviceInfo() {
            let w = window.innerWidth;
            let type = 'Desktop';
            if (w < 768) type = 'HP / Smartphone';
            else if (w < 1024) type = 'Tablet';
            return `${type} (${w}x${window.innerHeight}px)`;
        },

        detectContextFromCurrentReview() {
            let targetEl = null;

            const hideCanvases = () => {
                const mc = document.getElementById('main-drawing-canvas');
                const sc = document.getElementById('sidebar-drawing-canvas');
                const prevM = mc ? mc.style.pointerEvents : '';
                const prevS = sc ? sc.style.pointerEvents : '';
                if (mc) mc.style.pointerEvents = 'none';
                if (sc) sc.style.pointerEvents = 'none';
                return () => {
                    if (mc) mc.style.pointerEvents = prevM;
                    if (sc) sc.style.pointerEvents = prevS;
                };
            };

            if (this.currentPin && this.currentPin.x && this.currentPin.y) {
                const restore = hideCanvases();
                targetEl = document.elementFromPoint(this.currentPin.x, this.currentPin.y);
                restore();
            }

            if (!targetEl && this.strokes.length > 0) {
                const lastStroke = this.strokes[this.strokes.length - 1];
                const isSidebar = (lastStroke.annotation_scope === 'sidebar' || lastStroke.annotation_scope === 'dropdown_sidebar' || lastStroke.isSidebar);
                const sidebar = document.querySelector('aside');
                if (isSidebar && sidebar && this.isSidebarVisible()) {
                    return {
                        sumber_area: lastStroke.annotation_scope === 'dropdown_sidebar' ? 'dropdown_sidebar' : 'sidebar',
                        detail_sumber: 'Coretan pada Area Sidebar',
                        elementSnippet: 'Sidebar Navigasi'
                    };
                }

                let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
                for (const pt of lastStroke.points) {
                    const spt = this.getScreenCoordinates(pt, lastStroke);
                    if (spt.x < minX) minX = spt.x;
                    if (spt.x > maxX) maxX = spt.x;
                    if (spt.y < minY) minY = spt.y;
                    if (spt.y > maxY) maxY = spt.y;
                }
                if (minX !== Infinity) {
                    const cx = Math.max(10, Math.min(window.innerWidth - 10, (minX + maxX) / 2));
                    const cy = Math.max(10, Math.min(window.innerHeight - 10, (minY + maxY) / 2));
                    const restore = hideCanvases();
                    targetEl = document.elementFromPoint(cx, cy);
                    restore();
                }
            }

            return this.detectElementSourceMetadata(targetEl);
        },

        detectElementSourceMetadata(targetEl) {
            if (!targetEl || targetEl === document.body || targetEl === document.documentElement) {
                return {
                    sumber_area: 'dokumen_utama',
                    detail_sumber: 'Area Halaman Utama',
                    elementSnippet: 'Halaman (' + (document.title || window.location.pathname) + ')'
                };
            }

            // 1. Sidebar dan Dropdown
            const sidebar = targetEl.closest('aside');
            if (sidebar) {
                const link = targetEl.closest('a, button');
                const groupLi = targetEl.closest('li[x-data]');
                let groupTitle = '';
                if (groupLi) {
                    const groupBtn = groupLi.querySelector('button');
                    if (groupBtn) {
                        groupTitle = (groupBtn.innerText || '').trim().replace(/\s+/g, ' ');
                    }
                }

                // Cek apakah di dalam list dropdown accordion
                const inDropdownList = targetEl.closest('ul') && groupLi;
                if (link) {
                    let linkText = (link.innerText || link.title || link.getAttribute('aria-label') || '').trim().replace(/\s+/g, ' ');
                    if (linkText.length > 35) linkText = linkText.substring(0, 35) + '...';

                    const isDropdownToggle = link.tagName.toLowerCase() === 'button' && groupLi;
                    if (isDropdownToggle) {
                        return {
                            sumber_area: 'dropdown_sidebar',
                            detail_sumber: `Tombol Dropdown Sidebar "${linkText}"`,
                            elementSnippet: `Tombol Dropdown Sidebar "${linkText}"`
                        };
                    }

                    if (inDropdownList || groupTitle) {
                        return {
                            sumber_area: 'dropdown_sidebar',
                            detail_sumber: `Item Dropdown Sidebar "${linkText}"${groupTitle ? ' (Grup: ' + groupTitle + ')' : ''}`,
                            elementSnippet: `Item Dropdown Sidebar "${linkText}"`
                        };
                    }

                    return {
                        sumber_area: 'sidebar',
                        detail_sumber: `Menu Sidebar "${linkText}"`,
                        elementSnippet: `Menu Sidebar "${linkText}"`
                    };
                }

                if (inDropdownList && groupTitle) {
                    return {
                        sumber_area: 'dropdown_sidebar',
                        detail_sumber: `Area Dropdown Sidebar (Grup: ${groupTitle})`,
                        elementSnippet: `Area Dropdown Sidebar (${groupTitle})`
                    };
                }

                return {
                    sumber_area: 'sidebar',
                    detail_sumber: 'Sidebar Navigasi',
                    elementSnippet: 'Sidebar Navigasi'
                };
            }

            // 2. Header
            const header = targetEl.closest('header');
            if (header) {
                const btn = targetEl.closest('button, a, form');
                let btnText = btn ? (btn.innerText || btn.title || btn.getAttribute('aria-label') || '').trim().replace(/\s+/g, ' ') : '';
                if (btnText.length > 25) btnText = btnText.substring(0, 25) + '...';
                return {
                    sumber_area: 'dokumen_utama',
                    detail_sumber: `Header Navigasi Atas${btnText ? ' "' + btnText + '"' : ''}`,
                    elementSnippet: `Header "${btnText || 'Navigasi'}"`
                };
            }

            // 3. Elemen Konten Dokumen Utama
            const snippet = this.buildElementDescription(targetEl);
            return {
                sumber_area: 'dokumen_utama',
                detail_sumber: snippet,
                elementSnippet: snippet
            };
        },

        buildElementDescription(targetEl) {
            if (!targetEl || targetEl === document.body || targetEl === document.documentElement) {
                return 'Halaman (' + (document.title || window.location.pathname) + ')';
            }

            // 0. Khusus Sidebar Navigasi
            const sidebar = targetEl.closest('aside');
            if (sidebar) {
                const link = targetEl.closest('a, button');
                if (link) {
                    let linkText = (link.innerText || link.title || link.getAttribute('aria-label') || '').trim().replace(/\s+/g, ' ');
                    if (linkText.length > 35) linkText = linkText.substring(0, 35) + '...';

                    // Cek apakah item ini berada di dalam grup accordion / dropdown
                    const groupLi = link.closest('li[x-data]');
                    let groupTitle = '';
                    if (groupLi) {
                        const groupBtn = groupLi.querySelector('button');
                        if (groupBtn && groupBtn !== link) {
                            groupTitle = (groupBtn.innerText || '').trim().replace(/\s+/g, ' ');
                        }
                    }

                    // Cek apakah ini tombol pembuka accordion vs item link di dalam dropdown
                    const isDropdownToggle = link.tagName.toLowerCase() === 'button' && groupLi && link.querySelector('svg');
                    if (isDropdownToggle) {
                        return `Tombol Dropdown Sidebar "${linkText}"`;
                    }

                    if (groupTitle) {
                        return `Item Dropdown Sidebar "${linkText}" (Grup: ${groupTitle})`;
                    }
                    return `Menu Sidebar "${linkText}"`;
                }
                const headerTitle = sidebar.querySelector('p.font-display, p');
                return `Sidebar Navigasi (${(headerTitle?.innerText || 'Aplikasi').trim()})`;
            }

            // Khusus Header / Navbar Atas
            const header = targetEl.closest('header');
            if (header) {
                const btn = targetEl.closest('button, a, form');
                if (btn) {
                    let btnText = (btn.innerText || btn.title || btn.getAttribute('aria-label') || '').trim().replace(/\s+/g, ' ');
                    if (btnText.length > 25) btnText = btnText.substring(0, 25) + '...';
                    return `Header Navigasi "${btnText || 'Tombol'}"`;
                }
                return 'Header / Navbar Atas';
            }

            // 1. Table cell / row
            const cell = targetEl.closest('td, th');
            if (cell) {
                const table = targetEl.closest('table');
                const colIdx = cell.cellIndex;
                let colHeader = '';
                if (table && colIdx !== undefined) {
                    const headerCell = table.querySelector(`th:nth-child(${colIdx + 1})`);
                    if (headerCell) colHeader = (headerCell.innerText || '').trim();
                }
                const row = targetEl.closest('tr');
                const rowIdx = row ? row.rowIndex : '';
                let cellText = (cell.innerText || '').trim().replace(/\s+/g, ' ');
                if (cellText.length > 25) cellText = cellText.substring(0, 25) + '...';
                return `Tabel${colHeader ? ' Kolom "' + colHeader + '"' : ''}${rowIdx ? ' Baris ' + rowIdx : ''}${cellText ? ' (' + cellText + ')' : ''}`;
            }

            // 2. Button or interactive link
            const btn = targetEl.closest('button, a.btn, [role="button"]');
            if (btn) {
                let btnText = (btn.innerText || btn.title || btn.getAttribute('aria-label') || '').trim().replace(/\s+/g, ' ');
                if (btnText.length > 25) btnText = btnText.substring(0, 25) + '...';
                return `Tombol "${btnText || 'Aksi'}"`;
            }

            // 3. Form input / select
            const input = targetEl.closest('input, select, textarea');
            if (input) {
                const label = document.querySelector(`label[for="${input.id}"]`) || input.closest('label');
                let labelText = label ? label.innerText.trim() : (input.placeholder || input.name || '');
                return `Kolom Input "${labelText}"`;
            }

            // 4. Headings
            const heading = targetEl.closest('h1, h2, h3, h4, h5, .font-display');
            if (heading) {
                let hText = heading.innerText.trim().replace(/\s+/g, ' ');
                if (hText.length > 30) hText = hText.substring(0, 30) + '...';
                return `Judul "${hText}"`;
            }

            // 5. General element with text
            let text = (targetEl.innerText || targetEl.textContent || '').trim().replace(/\s+/g, ' ');
            if (text.length > 25) text = text.substring(0, 25) + '...';
            let tag = targetEl.tagName.toLowerCase();
            if (targetEl.id) tag += '#' + targetEl.id;

            return text ? `${tag} ("${text}")` : `Elemen <${tag}>`;
        },

        renderStrokesOnContext(vctx, strokeList, isSidebarScope, isSidebarVisible, sRect) {
            if (!strokeList || strokeList.length === 0) return;
            for (const stroke of strokeList) {
                if (!stroke.points || stroke.points.length === 0) continue;
                const isSidebarStroke = (stroke.annotation_scope === 'sidebar' || stroke.annotation_scope === 'dropdown_sidebar' || stroke.isSidebar);

                // Filter ketat berdasarkan scope agar tidak numpuk/saling merembet
                if (isSidebarScope && !isSidebarStroke) continue;
                if (!isSidebarScope && isSidebarStroke) continue;
                if (isSidebarStroke && !isSidebarVisible) continue;

                vctx.save();
                if (isSidebarStroke && sRect) {
                    vctx.beginPath();
                    vctx.rect(sRect.left, sRect.top, sRect.width, sRect.height);
                    vctx.clip();
                }
                vctx.lineCap = 'round';
                vctx.lineJoin = 'round';
                vctx.strokeStyle = stroke.color;
                vctx.lineWidth = stroke.size;
                vctx.beginPath();
                for (let i = 0; i < stroke.points.length; i++) {
                    const spt = this.getScreenCoordinates(stroke.points[i], stroke.isMain, isSidebarStroke);
                    if (i === 0) vctx.moveTo(spt.x, spt.y);
                    else vctx.lineTo(spt.x, spt.y);
                }
                vctx.stroke();
                vctx.restore();
            }
        },

        renderPinOnContext(vctx, pin, badgePrefix = null) {
            const isSidebarPin = (pin.annotation_scope === 'sidebar' || pin.annotation_scope === 'dropdown_sidebar' || pin.isSidebar);
            const s = this.getPinScreenPos(pin);
            if (!s || !s.isVisible) return;

            vctx.save();
            // 1. Titik Target Koordinat Presisi (Anchor Dot & Ring)
            vctx.beginPath();
            vctx.arc(s.x, s.y, 5, 0, Math.PI * 2);
            vctx.fillStyle = '#dc2626';
            vctx.fill();
            vctx.lineWidth = 2.5;
            vctx.strokeStyle = '#ffffff';
            vctx.stroke();

            // 2. Balon Pin Penanda Terpisah
            const origIdx = (pin.originalIndex !== undefined) ? pin.originalIndex : this.sessionSaranList.indexOf(pin);
            const num = origIdx >= 0 ? (origIdx + 1) : 1;
            const prefix = badgePrefix || (isSidebarPin ? (pin.annotation_scope === 'dropdown_sidebar' ? 'Dropdown' : 'Sidebar') : 'Dokumen');
            const badgeText = `Saran #${num} (${prefix})`;

            vctx.font = 'bold 11px sans-serif';
            const textMetrics = vctx.measureText(badgeText);
            const badgeW = Math.max(textMetrics.width + 16, 75);
            const badgeH = 22;
            const badgeX = s.x - (badgeW / 2);
            const badgeY = s.y - badgeH - 7;

            // Kotak badge beradius
            const bgColor = isSidebarPin ? (pin.annotation_scope === 'dropdown_sidebar' ? '#7e22ce' : '#0284c7') : '#dc2626';
            vctx.fillStyle = bgColor;
            vctx.beginPath();
            if (vctx.roundRect) {
                vctx.roundRect(badgeX, badgeY, badgeW, badgeH, 6);
            } else {
                vctx.rect(badgeX, badgeY, badgeW, badgeH);
            }
            vctx.fill();
            vctx.lineWidth = 2;
            vctx.strokeStyle = '#ffffff';
            vctx.stroke();

            // Ujung Jarum Penunjuk ke Bawah
            vctx.beginPath();
            vctx.moveTo(s.x - 5, badgeY + badgeH);
            vctx.lineTo(s.x + 5, badgeY + badgeH);
            vctx.lineTo(s.x, s.y);
            vctx.closePath();
            vctx.fillStyle = bgColor;
            vctx.fill();

            // Teks Nomor Saran
            vctx.fillStyle = '#ffffff';
            vctx.textAlign = 'center';
            vctx.textBaseline = 'middle';
            vctx.fillText(badgeText, s.x, badgeY + (badgeH / 2));
            vctx.restore();
        },

        async capturePageSnapshotScoped(targetScope = null, targetItem = null) {
            if (typeof html2canvas === 'undefined') return null;
            const root = document.getElementById('review-system-root');
            const hideEls = root ? root.querySelectorAll(':scope > div:not(#review-canvas-container)') : [];
            const aside = document.querySelector('aside');
            const backdrop = document.querySelector('[x-show*="sidebarOpen"]');

            let asidePrevDisplay = null;
            let asidePrevTransform = null;
            let backdropPrevDisplay = null;
            let openedDropdowns = [];

            try {
                hideEls.forEach(el => el.style.visibility = 'hidden');

                const isMainScope = (targetScope === 'dokumen_utama');
                const isSidebarScope = (targetScope === 'sidebar' || targetScope === 'dropdown_sidebar');

                // 1. Jika scope adalah dokumen_utama, sembunyikan drawer sidebar & backdrop agar halaman utama terlihat bersih
                if (isMainScope && aside) {
                    asidePrevDisplay = aside.style.display;
                    aside.style.display = 'none';
                    if (backdrop) {
                        backdropPrevDisplay = backdrop.style.display;
                        backdrop.style.display = 'none';
                    }
                }

                // 2. Jika scope adalah sidebar / dropdown_sidebar, pastikan sidebar terlihat dan dropdown terkait dibuka
                if (isSidebarScope && aside) {
                    asidePrevDisplay = aside.style.display;
                    asidePrevTransform = aside.style.transform;
                    aside.style.display = 'block';
                    aside.style.transform = 'none';

                    const targetDropdownId = targetItem?.dropdown_id || targetItem?.sidebar_group_id;
                    if (targetDropdownId) {
                        const dropEl = aside.querySelector(`[data-dropdown-container="${CSS.escape(targetDropdownId)}"]`);
                        if (dropEl && (dropEl.style.display === 'none' || dropEl.offsetParent === null)) {
                            dropEl.style.display = 'block';
                            openedDropdowns.push(dropEl);
                        }
                    }
                }

                const pageCanvas = await html2canvas(document.body, {
                    useCORS: true,
                    allowTaint: false,
                    logging: false,
                    scale: 1,
                    backgroundColor: '#ffffff',
                    ignoreElements: (el) => el.id === 'review-system-root',
                    onclone: (clonedDoc) => {
                        clonedDoc.querySelectorAll('[x-cloak]').forEach(el => el.removeAttribute('x-cloak'));
                        const origEls = document.querySelectorAll('*');
                        const cloneEls = clonedDoc.querySelectorAll('*');
                        for (let i = 0; i < origEls.length; i++) {
                            if (origEls[i].scrollTop > 0 && cloneEls[i]) cloneEls[i].scrollTop = origEls[i].scrollTop;
                            if (origEls[i].scrollLeft > 0 && cloneEls[i]) cloneEls[i].scrollLeft = origEls[i].scrollLeft;
                        }
                    }
                });

                const viewCanvas = document.createElement('canvas');
                viewCanvas.width = window.innerWidth;
                viewCanvas.height = window.innerHeight;
                const vctx = viewCanvas.getContext('2d');
                vctx.drawImage(pageCanvas, 0, 0, viewCanvas.width, viewCanvas.height);

                const isSidebarVisible = isSidebarScope ? true : (isMainScope ? false : this.isSidebarVisible());
                const sRect = aside ? aside.getBoundingClientRect() : null;

                // Render coretan sesuai scope terisolasi
                this.renderStrokesOnContext(vctx, this.strokes, isSidebarScope, isSidebarVisible, sRect);

                // Render pinpoint: HANYA targetItem yang bersangkutan agar tidak saling menumpuk!
                if (targetItem) {
                    this.renderPinOnContext(vctx, targetItem);
                } else {
                    const pins = this.sessionSaranList.filter(pin => {
                        const isSide = (pin.annotation_scope === 'sidebar' || pin.annotation_scope === 'dropdown_sidebar' || pin.isSidebar);
                        return isSidebarScope ? isSide : !isSide;
                    });
                    pins.forEach(p => this.renderPinOnContext(vctx, p));
                }

                return viewCanvas.toDataURL('image/jpeg', 0.85);
            } catch (err) {
                console.warn('Screenshot capture error:', err);
                return null;
            } finally {
                hideEls.forEach(el => el.style.visibility = '');
                if (aside && asidePrevDisplay !== null) aside.style.display = asidePrevDisplay;
                if (aside && asidePrevTransform !== null) aside.style.transform = asidePrevTransform;
                if (backdrop && backdropPrevDisplay !== null) backdrop.style.display = backdropPrevDisplay;
                openedDropdowns.forEach(el => el.style.display = 'none');
            }
        },

        async captureCurrentPageSnapshot() {
            return await this.capturePageSnapshotScoped(null, null);
        },

        // Kirim Seluruh Sesi (Semua Halaman yang Direview pada Sesi Ini)
        async submitFullSession() {
            await this.stopSpeechAndRecording();
            this.isSubmitting = true;

            // 1. Ambil screenshot awal jika belum ada
            let currentComposite = null;
            if (this.sessionSaranList.length > 0 || this.hasDrawing || this.strokes.length > 0) {
                currentComposite = await this.captureCurrentPageSnapshot();
                if (currentComposite) {
                    this.compositeScreenshot = currentComposite;
                }
            }

            // 2. Kumpulkan seluruh halaman dari localStorage yang memiliki catatan/coretan
            const allSessions = this.getAllSessions();
            if (allSessions[this.halamanPath]) {
                allSessions[this.halamanPath].compositeScreenshot = currentComposite || this.compositeScreenshot || this.drawingDataUrl;
                allSessions[this.halamanPath].strokes = this.strokes;
                allSessions[this.halamanPath].sessionSaranList = this.sessionSaranList;
            } else if (this.sessionSaranList.length > 0 || this.hasDrawing || this.strokes.length > 0) {
                allSessions[this.halamanPath] = {
                    halamanPath: this.halamanPath,
                    halamanUrl: window.location.href,
                    judulHalaman: document.title || 'Halaman Aplikasi',
                    sessionSaranList: this.sessionSaranList,
                    strokes: this.strokes,
                    drawingDataUrl: this.drawingDataUrl,
                    hasDrawing: this.hasDrawing || this.strokes.length > 0,
                    compositeScreenshot: currentComposite || this.compositeScreenshot || this.drawingDataUrl
                };
            }

            // Jika ada saran sidebar di __GLOBAL_SIDEBAR__, gabungkan ke sesi halaman aktif saat kirim
            if (allSessions['__GLOBAL_SIDEBAR__'] && allSessions['__GLOBAL_SIDEBAR__'].sessionSaranList) {
                const sList = allSessions['__GLOBAL_SIDEBAR__'].sessionSaranList;
                if (!allSessions[this.halamanPath]) {
                    allSessions[this.halamanPath] = {
                        halamanPath: this.halamanPath,
                        halamanUrl: window.location.href,
                        judulHalaman: document.title || 'Halaman Aplikasi',
                        sessionSaranList: sList,
                        strokes: [],
                        hasDrawing: false,
                        compositeScreenshot: currentComposite || this.compositeScreenshot
                    };
                } else {
                    const existIds = new Set((allSessions[this.halamanPath].sessionSaranList || []).map(s => s.id));
                    const missing = sList.filter(s => !existIds.has(s.id));
                    allSessions[this.halamanPath].sessionSaranList = [...(allSessions[this.halamanPath].sessionSaranList || []), ...missing];
                }
                delete allSessions['__GLOBAL_SIDEBAR__'];
            }

            const pagesToSubmit = Object.values(allSessions).filter(page => 
                (page.sessionSaranList && page.sessionSaranList.length > 0) || page.hasDrawing || (page.strokes && page.strokes.length > 0)
            );

            if (pagesToSubmit.length === 0) {
                this.isSubmitting = false;
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            let successCount = 0;

            for (const page of pagesToSubmit) {
                const saranList = page.sessionSaranList || [];

                if (saranList.length === 0) {
                    // Hanya ada coretan visual tanpa teks pin spesifik
                    const hasDropdownStroke = page.strokes && page.strokes.some(st => st.annotation_scope === 'dropdown_sidebar');
                    const hasSidebarStroke = page.strokes && page.strokes.some(st => (st.annotation_scope === 'sidebar' || st.isSidebar));
                    const hasMainStroke = page.strokes && page.strokes.some(st => (st.annotation_scope === 'main_document' || st.isMain));

                    // Kirim terpisah untuk coretan sidebar/dropdown
                    if (hasDropdownStroke || hasSidebarStroke) {
                        const scope = hasDropdownStroke ? 'dropdown_sidebar' : 'sidebar';
                        const detailSumber = hasDropdownStroke ? 'Coretan pada Dropdown Sidebar' : 'Coretan pada Menu Sidebar';
                        let snap = null;
                        if (page.halamanPath === this.halamanPath) {
                            snap = await this.capturePageSnapshotScoped(scope, null);
                        } else {
                            snap = page.compositeScreenshot || page.drawingDataUrl || null;
                        }

                        const payload = {
                            nama_reviewer: 'Reviewer',
                            halaman_url: page.halamanUrl || window.location.href,
                            halaman_path: page.halamanPath || window.location.pathname,
                            judul_halaman: page.judulHalaman || 'Halaman Aplikasi',
                            bagian_elemen: detailSumber,
                            sumber_area: scope,
                            detail_sumber: detailSumber,
                            posisi_x: 25,
                            posisi_y: 50,
                            kategori: 'saran',
                            komentar: 'Coretan pada area sidebar.',
                            coretan_gambar: snap,
                            audio_rekaman: null,
                            device_info: this.getDeviceInfo(),
                        };

                        try {
                            const res = await fetch('/secret/masukan', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                                body: JSON.stringify(payload)
                            });
                            if (res.ok) successCount++;
                        } catch (e) {
                            console.error('Submit review coretan sidebar failed:', e);
                        }
                    }

                    // Kirim terpisah untuk coretan dokumen utama
                    if (hasMainStroke) {
                        let snap = null;
                        if (page.halamanPath === this.halamanPath) {
                            snap = await this.capturePageSnapshotScoped('dokumen_utama', null);
                        } else {
                            snap = page.compositeScreenshot || page.drawingDataUrl || null;
                        }

                        const payload = {
                            nama_reviewer: 'Reviewer',
                            halaman_url: page.halamanUrl || window.location.href,
                            halaman_path: page.halamanPath || window.location.pathname,
                            judul_halaman: page.judulHalaman || 'Halaman Aplikasi',
                            bagian_elemen: 'Coretan Layar Dokumen',
                            sumber_area: 'dokumen_utama',
                            detail_sumber: 'Coretan Layar Dokumen Utama',
                            posisi_x: 50,
                            posisi_y: 50,
                            kategori: 'saran',
                            komentar: 'Coretan pada layar dokumen utama.',
                            coretan_gambar: snap,
                            audio_rekaman: null,
                            device_info: this.getDeviceInfo(),
                        };

                        try {
                            const res = await fetch('/secret/masukan', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                                body: JSON.stringify(payload)
                            });
                            if (res.ok) successCount++;
                        } catch (e) {
                            console.error('Submit review coretan dokumen failed:', e);
                        }
                    }
                } else {
                    // Kirim masing-masing catatan saran secara presisi per komponen terpisah
                    for (const item of saranList) {
                        const sumberArea = item.sumber_area || (item.annotation_scope === 'dropdown_sidebar' ? 'dropdown_sidebar' : (item.annotation_scope === 'sidebar' ? 'sidebar' : 'dokumen_utama'));
                        const bagianElemen = item.elementSnippet || 'Bagan Komponen';
                        const detailSumber = item.detail_sumber || bagianElemen;
                        const posX = (item.xPct !== null && item.xPct !== undefined && !isNaN(item.xPct)) ? Math.round(item.xPct) : 50;
                        const posY = (item.yPct !== null && item.yPct !== undefined && !isNaN(item.yPct)) ? Math.round(item.yPct) : 50;

                        let itemSnapshot = null;
                        if (page.halamanPath === this.halamanPath) {
                            // Ambil snapshot terisolasi HANYA untuk item ini dan area-nya tanpa menumpuk dengan pin/coretan lain
                            itemSnapshot = await this.capturePageSnapshotScoped(sumberArea, item);
                        } else {
                            itemSnapshot = page.compositeScreenshot || page.drawingDataUrl || null;
                        }

                        const scopedStrokes = page.strokes ? page.strokes.filter(st => {
                            const isSide = (st.annotation_scope === 'sidebar' || st.annotation_scope === 'dropdown_sidebar' || st.isSidebar);
                            return (sumberArea === 'dokumen_utama') ? !isSide : isSide;
                        }) : [];

                        const payload = {
                            nama_reviewer: 'Reviewer',
                            halaman_url: page.halamanUrl || window.location.href,
                            halaman_path: page.halamanPath || window.location.pathname,
                            judul_halaman: page.judulHalaman || 'Halaman Aplikasi',
                            bagian_elemen: bagianElemen,
                            sumber_area: sumberArea,
                            detail_sumber: detailSumber,
                            posisi_x: posX,
                            posisi_y: posY,
                            kategori: 'saran',
                            komentar: item.text || 'Catatan pada komponen ini.',
                            coretan_gambar: itemSnapshot,
                            audio_rekaman: item.audioUrl || null,
                            data_anotasi: {
                                pin: item,
                                strokes: scopedStrokes
                            },
                            device_info: this.getDeviceInfo(),
                        };

                        try {
                            const res = await fetch('/secret/masukan', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify(payload)
                            });
                            if (res.ok) successCount++;
                        } catch (e) {
                            console.error('Submit review item failed:', e);
                        }
                    }
                }
            }

            if (successCount > 0) {
                const totalPages = pagesToSubmit.length;
                this.successMessage = totalPages > 1 
                    ? `Berhasil mengirim seluruh masukan dan coretan dari ${totalPages} halaman sekaligus ke Secret Panel!`
                    : 'Seluruh saran dan coretan sesi ini berhasil dikirim ke Secret Panel!';
                this.resetEntireSession(false);
                this.showSuccessToast = true;
                setTimeout(() => {
                    this.showSuccessToast = false;
                }, 5000);
            }

            this.isSubmitting = false;
        },
    };
}
</script>
@endif
