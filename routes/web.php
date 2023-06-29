<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesertaController;
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

    // Kelola Akun/Profile
    Route::get('/profil/{username}', [PesertaController::class, 'create'])->name('peserta.create');
    Route::post('/profil/{username}/store', [PesertaController::class, 'store'])->name('peserta.store');
    Route::get('/profil/{username}/show', [PesertaController::class, 'show'])->name('peserta.show');
});
