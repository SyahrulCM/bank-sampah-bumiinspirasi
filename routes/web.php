<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\PengepulController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\LaporanSaldoController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\EdukasiController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    if (Session::has('login')) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Route::get('/transaksi/export-manual', [TransaksiController::class, 'exportManualExcel'])
    ->name('transaksi.exportManual');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['cekLogin'])->group(function () {
    Route::get('/dashboard',[HomeController::class,'dashboard'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Route Sampah
    Route::get('/sampah',[SampahController::class,'sampah']);
    Route::get('/sampah', [SampahController::class, 'getSampah'])->name('sampah.index');
    Route::post('/sampah/input', [SampahController::class, 'inputSampah']);
    Route::get('/sampah/edit/{id_sampah}', [SampahController::class, 'editSampah'])->name('sampah.edit');
    Route::post('/sampah/update/{id_sampah}', [SampahController::class, 'updateSampah'])->name('sampah.update');
    Route::get('/sampah/hapus/{id_sampah}', [SampahController::class, 'hapusSampah'])->name('sampah.hapus');

    Route::get('/role',[RoleController::class,'role']);
    Route::get('/role', [RoleController::class, 'getRole'])->name('role.index');
    Route::post('/role/input', [RoleController::class, 'inputRole']);

    Route::get('/registrasi',[RegistrasiController::class,'registrasi']);
    Route::get('/registrasi', [RegistrasiController::class, 'getRegistrasi'])->name('registrasi.index');
    Route::post('/registrasi/input', [RegistrasiController::class, 'inputRegistrasi'])->name('registrasi.input');
    Route::get('/registrasi/edit/{id_registrasi}', [RegistrasiController::class, 'editRegistrasi'])->name('registrasi.edit');
    Route::post('/registrasi/update/{id_registrasi}', [RegistrasiController::class, 'updateRegistrasi'])->name('registrasi.update');
    Route::get('/registrasi/hapus/{id_registrasi}', [RegistrasiController::class, 'hapusRegistrasi'])->name('registrasi.hapus');
    Route::post('/registrasi/import', [RegistrasiController::class, 'importExcel'])->name('registrasi.import');

    Route::get('/petugas',[PetugasController::class,'petugas']);
    Route::get('/petugas', [PetugasController::class, 'getPetugas'])->name('petugas.index');
    Route::post('/petugas/input', [PetugasController::class, 'inputPetugas'])->name('petugas.input');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('layout.transaksi');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi/simpan', [TransaksiController::class, 'simpanTransaksi'])->name('transaksi.simpan');
    Route::get('/transaksi/{id_transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/simpan', [TransaksiController::class, 'simpanTransaksi'])->name('transaksi.simpan');
    Route::post('/transaksi/hapus/{id}', [TransaksiController::class, 'hapusTransaksi'])->name('transaksi.hapus');

    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');

    Route::get('/pengepul', [PengepulController::class, 'index'])->name('pengepul.index');
    Route::post('/pengepul/store', [PengepulController::class, 'store'])->name('pengepul.store');
    Route::get('/pengepul/edit/{id}', [PengepulController::class, 'edit'])->name('pengepul.edit');
    Route::post('/pengepul/update/{id}', [PengepulController::class, 'update'])->name('pengepul.update');
    Route::get('/pengepul/hapus/{id}', [PengepulController::class, 'destroy'])->name('pengepul.hapus');

    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualans.index');
    Route::post('/penjualan/simpan', [PenjualanController::class, 'simpan'])->name('penjualans.simpan');
    Route::get('/penjualan/{id}/detail', [PenjualanController::class, 'detail'])->name('penjualans.detail');
    Route::get('/penjualan/{id}/hapus', [PenjualanController::class, 'hapus'])->name('penjualans.hapus');

    Route::get('/mutasi', [MutasiController::class, 'index'])->name('mutasi.index');
    Route::post('/mutasi/store', [MutasiController::class, 'store'])->name('mutasi.store');
    Route::delete('/mutasi/{id}', [MutasiController::class, 'destroy'])->name('mutasi.destroy');
    Route::get('/stok/sampah/{id}', function ($id) {
    $stok = \App\Models\Stok::where('id_sampah', $id)->first();
    return response()->json(['jumlah' => $stok->jumlah ?? 0]);
    });

    Route::get('/penarikan', [PenarikanController::class, 'index'])->name('penarikan.index');
    Route::post('/penarikan/simpan', [PenarikanController::class, 'store'])->name('penarikan.store');

    Route::get('/laporan/saldo', [LaporanSaldoController::class, 'index'])->name('laporan.saldo');
    Route::get('/laporan-saldo/export-manual', [LaporanSaldoController::class, 'exportManualExcel'])->name('laporan-saldo.export-manual');


    Route::get('/edukasi', [EdukasiController::class, 'index'])->name('edukasi.index');
    Route::post('/edukasi/simpan', [EdukasiController::class, 'store'])->name('edukasi.store');
    Route::get('/edukasi/edit/{id}', [EdukasiController::class, 'edit'])->name('edukasi.edit');
    Route::post('/edukasi/update/{id}', [EdukasiController::class, 'update'])->name('edukasi.update');
    Route::delete('/edukasi/hapus/{id}', [EdukasiController::class, 'destroy'])->name('edukasi.destroy');

    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman/store', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/pengumuman/edit/{id_pengumuman}', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::post('/pengumuman/update/{id_pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/hapus/{id_pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');


});


