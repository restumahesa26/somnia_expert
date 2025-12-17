<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\PenyakitGejalaController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;

Route::get('/', function () {
    return view('pages.home');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin only routes
    Route::middleware('admin')->group(function () {
        Route::resource('gejala', GejalaController::class);
        Route::resource('penyakit', PenyakitController::class);
        Route::resource('penyakit-gejala', PenyakitGejalaController::class)->except(['show']);
        Route::resource('pengguna', PenggunaController::class);
    });

    Route::get('konsultasi', [DiagnosaController::class,'form'])->name('diagnosa.form');
    Route::post('konsultasi', [DiagnosaController::class,'proses'])->name('diagnosa.proses');
    Route::get('/diagnosa/riwayat', [DiagnosaController::class, 'riwayat'])->name('diagnosa.riwayat');
    Route::get('/diagnosa/detail/{konsultasi}', [DiagnosaController::class, 'show'])->name('diagnosa.show');
    Route::delete('/diagnosa/hapus/{konsultasi}', [DiagnosaController::class, 'destroy'])->name('diagnosa.destroy');
    Route::post('/diagnosa/ajax-start', [DiagnosaController::class, 'ajaxStart'])->name('diagnosa.start');
    Route::post('/diagnosa/ajax-next', [DiagnosaController::class, 'ajaxNext'])->name('diagnosa.next');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API Routes
Route::get('/get-available-gejala/{penyakitId}', [App\Http\Controllers\API\GejalaController::class, 'getAvailableGejala'])
    ->name('api.gejala.available');

require __DIR__.'/auth.php';
