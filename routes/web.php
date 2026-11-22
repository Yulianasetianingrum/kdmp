<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Master\BarangController;
use App\Http\Controllers\Master\CoaController;
use App\Http\Controllers\Master\EntitasController;
use App\Http\Controllers\Master\GudangController as MasterGudangController;
use App\Http\Controllers\Master\KasBankController;
use App\Http\Controllers\Master\KomoditasController;
use App\Http\Controllers\Master\PeriodeController;
use App\Http\Controllers\Master\PihakController;
use App\Http\Controllers\Master\SatuanController;
use App\Http\Controllers\Master\StokMinimumBarangController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('landing');
Route::get('/landing', LandingController::class)->name('landing.preview');

Route::middleware('auth')->get('/home', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// ===== Modul Survei Lapangan KDMP =====
require __DIR__ . '/survei.php';

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== SUPER ADMIN =====
    Route::middleware('super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/approve/{id}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'approve'])->name('approve');
    });

    // ===== Master Sidebar & Master Data =====
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('kategori-komoditas', \App\Http\Controllers\Master\KategoriKomoditasController::class)->parameters(['kategori-komoditas' => 'kategoriKomoditas']);
        Route::resource('master-komoditas', \App\Http\Controllers\Master\MasterKomoditasController::class)->parameters(['master-komoditas' => 'masterKomoditas']);
        Route::resource('item-komoditas', \App\Http\Controllers\Master\ItemKomoditasController::class)->parameters(['item-komoditas' => 'itemKomoditas']);
        Route::resource('pertanyaan', \App\Http\Controllers\Master\MasterPertanyaanController::class);

        // Daftar seluruh entitas (lintas desa) hanya untuk super admin.
        Route::resource('entitas', EntitasController::class)->middleware('super_admin');
        Route::resource('komoditas', KomoditasController::class);
        Route::resource('satuan', SatuanController::class);
        Route::get('barang/stok-minimum', [StokMinimumBarangController::class, 'index'])
            ->name('barang.stok-minimum.index');
        Route::patch('barang/stok-minimum/{idBarang}', [StokMinimumBarangController::class, 'update'])
            ->name('barang.stok-minimum.update');
        Route::resource('barang', BarangController::class);
        Route::resource('pihak', PihakController::class);
        Route::resource('kas-bank', KasBankController::class);
        Route::resource('gudang', MasterGudangController::class);
        Route::resource('periode', PeriodeController::class)->only(['index', 'store', 'edit', 'update']);
        Route::resource('coa', CoaController::class);
    });

    // ===== PENDATAAN =====
    Route::prefix('pendataan')->name('pendataan.')->group(function () {
        Route::resource('penduduk', \App\Http\Controllers\Pendataan\PendudukDesaController::class);
        Route::resource('petani', \App\Http\Controllers\Pendataan\PetaniController::class);
        Route::resource('potensi', \App\Http\Controllers\Pendataan\PotensiKomoditasController::class);
    });

    // ===== SURVEI FORMULIR BARU =====
    Route::prefix('survei-form')->name('survei.formulir.')->group(function () {
        Route::get('penduduk', [\App\Http\Controllers\Survei\FormulirPendudukController::class, 'create'])->name('penduduk');
        Route::post('penduduk', [\App\Http\Controllers\Survei\FormulirPendudukController::class, 'store'])->name('penduduk.store');
        Route::get('komoditas', [\App\Http\Controllers\Survei\FormulirKomoditasController::class, 'create'])->name('komoditas');
        Route::post('komoditas', [\App\Http\Controllers\Survei\FormulirKomoditasController::class, 'store'])->name('komoditas.store');
    });

    // ===== LAPORAN =====
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('laporan.potensi.index');
        })->name('index');
        Route::get('demografi', [\App\Http\Controllers\Laporan\LaporanDemografiController::class, 'index'])->name('demografi.index');
        Route::get('potensi-desa', [\App\Http\Controllers\Laporan\LaporanPotensiController::class, 'index'])->name('potensi.index');
        Route::get('petani-komoditas', [\App\Http\Controllers\Laporan\LaporanPetaniKomoditasController::class, 'index'])->name('petani-komoditas.index');
        Route::get('analisis-potensi', [\App\Http\Controllers\Laporan\LaporanAnalisisPotensiController::class, 'index'])->name('analisis.index');
    });
});

Route::post('/admin/set-entitas', function (\Illuminate\Http\Request $request) {
    if (auth()->user()->id_entitas === null) {
        if ($request->id_entitas) {
            $request->session()->put('entitas_aktif_pilihan', $request->id_entitas);
        } else {
            $request->session()->forget('entitas_aktif_pilihan');
        }
    }
    return back();
})->name('admin.set_entitas')->middleware(['auth', 'super_admin']);
