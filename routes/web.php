<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\ObjekPajakController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpptController;
use App\Http\Controllers\SubjekPajakController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('subjek-pajak', SubjekPajakController::class)->except(['show']);
    Route::post('subjek-pajak/import', [SubjekPajakController::class, 'import'])->name('subjek-pajak.import');
    Route::get('subjek-pajak/export', [SubjekPajakController::class, 'export'])->name('subjek-pajak.export');
    Route::get('subjek-pajak/pdf', [SubjekPajakController::class, 'pdf'])->name('subjek-pajak.pdf');

    Route::resource('objek-pajak', ObjekPajakController::class)->except(['show']);
    Route::post('objek-pajak/import', [ObjekPajakController::class, 'import'])->name('objek-pajak.import');
    Route::get('objek-pajak/export', [ObjekPajakController::class, 'export'])->name('objek-pajak.export');
    Route::get('objek-pajak/pdf', [ObjekPajakController::class, 'pdf'])->name('objek-pajak.pdf');

    Route::resource('sppt', SpptController::class)->except(['show']);
    Route::post('sppt/import', [SpptController::class, 'import'])->name('sppt.import');
    Route::get('sppt/export', [SpptController::class, 'export'])->name('sppt.export');
    Route::get('sppt/pdf', [SpptController::class, 'pdf'])->name('sppt.pdf');

    Route::resource('mutasi', MutasiController::class)->except(['show']);
    Route::get('pembayaran/collective', [PembayaranController::class, 'collective'])->name('pembayaran.collective');
    Route::post('pembayaran/collective', [PembayaranController::class, 'storeCollective'])->name('pembayaran.collective.store');
    Route::resource('pembayaran', PembayaranController::class)->except(['show']);
});

require __DIR__ . '/auth.php';
