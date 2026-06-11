<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KasirController;

/*
|--------------------------------------------------------------------------
| Redirect Root ke Login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (session()->has('user_id')) {
        $role = session('user_role');
        return redirect($role === 'admin' ? '/admin/dashboard' : '/kasir/dashboard');
    }
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| AUTH - Tidak butuh middleware
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK - Tanpa CSRF & Middleware
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [TransaksiController::class, 'callback'])
     ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
     ->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| PROTECTED AREA - Butuh Login
|--------------------------------------------------------------------------
*/
Route::middleware(['ceklogin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA
    |--------------------------------------------------------------------------
    */
    Route::middleware(['ceklogin:admin'])->group(function () {

        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
             ->name('admin.dashboard');

        // PRODUK
        Route::get('/admin/produk', [ProdukController::class, 'index'])
             ->name('admin.produk');
        Route::get('/admin/produk/create', [ProdukController::class, 'create'])
             ->name('admin.produk.create');
        Route::post('/admin/produk', [ProdukController::class, 'store'])
             ->name('admin.produk.store');
        Route::get('/admin/produk/{id}/edit', [ProdukController::class, 'edit'])
             ->name('admin.produk.edit');
        Route::put('/admin/produk/{id}', [ProdukController::class, 'update'])
             ->name('admin.produk.update');
        Route::delete('/admin/produk/{id}', [ProdukController::class, 'destroy'])
             ->name('admin.produk.destroy');

        // KATEGORI
        Route::get('/admin/kategori', [KategoriController::class, 'index'])
             ->name('admin.kategori');
        Route::get('/admin/kategori/create', [KategoriController::class, 'create'])
             ->name('admin.kategori.create');
        Route::post('/admin/kategori', [KategoriController::class, 'store'])
             ->name('admin.kategori.store');
        Route::get('/admin/kategori/{id}/edit', [KategoriController::class, 'edit'])
             ->name('admin.kategori.edit');
        Route::put('/admin/kategori/{id}', [KategoriController::class, 'update'])
             ->name('admin.kategori.update');
        Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])
             ->name('admin.kategori.destroy');

        // LAPORAN ADMIN
        Route::get('/admin/laporan', [LaporanController::class, 'index'])
             ->name('admin.laporan');
        Route::get('/admin/laporan/{id}', [LaporanController::class, 'show'])
             ->name('admin.laporan.show');
    });

    /*
    |--------------------------------------------------------------------------
    | KASIR AREA
    |--------------------------------------------------------------------------
    */
    Route::middleware(['ceklogin:kasir'])->group(function () {

        // DASHBOARD
        Route::get('/kasir/dashboard', [DashboardController::class, 'kasir'])
             ->name('kasir.dashboard');

        // TRANSAKSI
        Route::get('/kasir/transaksi', [TransaksiController::class, 'create'])
             ->name('kasir.transaksi');
        Route::post('/kasir/transaksi', [TransaksiController::class, 'store'])
             ->name('kasir.transaksi.store');

        // CHECK STATUS PEMBAYARAN (Polling QRIS)
        Route::get('/transaksi/status/{invoice}', [TransaksiController::class, 'checkStatus'])
             ->name('transaksi.status');

        // RIWAYAT TRANSAKSI
        Route::get('/kasir/riwayat', [KasirController::class, 'riwayat'])
             ->name('kasir.riwayat');

        // LAPORAN HARIAN
        Route::get('/kasir/laporan', [KasirController::class, 'laporanInput'])
             ->name('kasir.laporan.input');

        Route::post('/kasir/laporan', [KasirController::class, 'laporanStore'])
             ->name('kasir.laporan.store');

        Route::get('/kasir/laporan/riwayat', [KasirController::class, 'laporanRiwayat'])
             ->name('kasir.laporan.riwayat');
    });

});