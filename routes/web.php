<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenandatanganController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
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
});
