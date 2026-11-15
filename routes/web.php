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

Route::get('/', LandingController::class)->name('landing');

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
