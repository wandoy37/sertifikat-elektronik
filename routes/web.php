<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\NarasumberController;
use App\Http\Controllers\PenandatanganController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SertifikatPdfController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Mail\NotifySertifikat;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::name('home.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::get('/sertifikat/find', [HomeController::class, 'find'])->name('sertifikat.find');

    Route::get('/sertifikat/{id}', [HomeController::class, 'show'])->name('show');
    // Cetak
    Route::get('/sertifikat/{id}/download', [SertifikatPdfController::class, 'terbitkanCertificate'])->name('sertifikat.download');
    // Preview
    Route::get('/sertifikat/{id}/preview', [HomeController::class, 'preview'])->name('sertifikat.preview');
});


Route::middleware(['auth'])->prefix('operator')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Kelola Pengguna
    Route::get('pengguna', [UserController::class, 'index'])->name('user.index');
    Route::get('pengguna/tambah', [UserController::class, 'create'])->name('user.create');
    Route::post('pengguna/store', [UserController::class, 'store'])->name('user.store');
    Route::get('pengguna/{username}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('pengguna/{username}/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('pengguna/{username}/delete', [UserController::class, 'destroy'])->name('user.delete');

    // Kelola Akun/Profile
    Route::get('/profil/{username}', [PesertaController::class, 'create'])->name('peserta.create');
    Route::post('/profil/{username}/store', [PesertaController::class, 'store'])->name('peserta.store');
    Route::get('/profil/{username}/show', [PesertaController::class, 'show'])->name('peserta.show');
    Route::get('/profil/{username}/edit', [PesertaController::class, 'edit'])->name('peserta.edit');
    Route::patch('/profil/{username}/update', [PesertaController::class, 'update'])->name('peserta.update');
    Route::delete('/profil/{username}/delete', [PesertaController::class, 'destroy'])->name('peserta.delete');


    // Kelola Peserta
    Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta.index');

    // Kelola Penandanganan
    Route::get('/penandatangan', [PenandatanganController::class, 'index'])->name('penandatangan.index');
    Route::get('/penandatangan/create', [PenandatanganController::class, 'create'])->name('penandatangan.create');
    Route::post('/penandatangan/store', [PenandatanganController::class, 'store'])->name('penandatangan.store');
    Route::get('/penandatangan/{id}/edit', [PenandatanganController::class, 'edit'])->name('penandatangan.edit');
    Route::patch('/penandatangan/{id}/update', [PenandatanganController::class, 'update'])->name('penandatangan.update');
    Route::delete('/penandatangan/{id}/delete', [PenandatanganController::class, 'destroy'])->name('penandatangan.delete');

    // Kelola Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/show', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::patch('/kategori/{id}/update', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}/delete', [KategoriController::class, 'destroy'])->name('kategori.delete');

    // Kelola Siswa
    Route::get('siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('siswa/store', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('siswa/edit/{id}', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::patch('siswa/update/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('siswa/delete/{id}', [SiswaController::class, 'destroy'])->name('siswa.delete');

    // Kelola Narasumber
    Route::get('narasumber', [NarasumberController::class, 'index'])->name('narasumber.index');
    Route::get('narasumber/create', [NarasumberController::class, 'create'])->name('narasumber.create');
    Route::post('narasumber/store', [NarasumberController::class, 'store'])->name('narasumber.store');
    Route::get('narasumber/edit/{id}', [NarasumberController::class, 'edit'])->name('narasumber.edit');
    Route::patch('narasumber/update/{id}', [NarasumberController::class, 'update'])->name('narasumber.update');
    Route::delete('narasumber/delete/{id}', [NarasumberController::class, 'destroy'])->name('narasumber.delete');

    // Kelola Kegiatan
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
    Route::post('/kegiatan/store', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/edit/{id}', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::patch('/kegiatan/update/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/delete/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.delete');
    // Cetak Kegiatan Tanpa Identitas Peserta
    Route::get('/kegiatan/print/{id}', [KegiatanController::class, 'print'])->name('kegiatan.print');

    // Kelola Sertifikat
    Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/create/{id}', [SertifikatController::class, 'createPeserta'])->name('sertifikat.create.peserta');
    Route::post('/sertifikat/store', [SertifikatController::class, 'store'])->name('sertifikat.store');
    // Tambah Narasumber pada tabel sertifikatas
    Route::post('/sertifikat/narasumber/store', [SertifikatController::class, 'storeNarasumber'])->name('sertifikat.narasumber.store');
    // Hapus dari tampilan Kegiatan Tambah Peserta
    Route::delete('/sertifikat/peserta/delete/{id}', [SertifikatController::class, 'deletePeserta'])->name('sertifikat.peserta.delete');
    // Hapus dari tampilan sertifikat index
    Route::delete('/sertifikat/delete/{id}', [SertifikatController::class, 'deleteSertifikat'])->name('sertifikat.delete');

    // Buat Sertifikat
    Route::get('/sertifikat/peserta/{id}', [SertifikatPdfController::class, 'generateCertificate'])->name('sertifikat.peserta.generate');
    Route::get('/sertifikat/cetak-all/{id}', [SertifikatPdfController::class, 'generateAllCertificate'])->name('sertifikat.all.generate');
    Route::get('/sertifikat/cetak-all/parts/{id}', [SertifikatPdfController::class, 'generateAllPartsCertificate'])->name('sertifikat.all.parts.generate');
    // Terbitkan Sertifikat
    Route::get('/terbitkan/sertifikat/peserta/{id}', [SertifikatPdfController::class, 'terbitkanCertificate'])->name('sertifikat.peserta.terbitkan');
    // Cetak Sertifikat Narasumber
    Route::get('/sertifikat/narasumber/{id}', [SertifikatPdfController::class, 'generateCertificateNarasumber'])->name('sertifikat.narasumber.generate');
    Route::get('/sertifikat/narasumber/{id}/download', [SertifikatPdfController::class, 'generateCertificateNarasumberDownload'])->name('sertifikat.narasumber.download');

    // Cetak Sertifikat
    Route::get('/sertifikat/download/{id}', [SertifikatController::class, 'download'])->name('sertifikat.download');
});
