<?php

use App\Http\Controllers\SuratPerizinanSakitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/surat-perizinan-sakit/create', [SuratPerizinanSakitController::class, 'create'])
    ->name('surat-perizinan-sakit.create');

Route::get('/surat-perizinan-sakit', [SuratPerizinanSakitController::class, 'index'])
    ->middleware('auth')
    ->name('surat-perizinan-sakit.index');

Route::post('/surat-perizinan-sakit', [SuratPerizinanSakitController::class, 'store'])
    ->middleware('auth')
    ->name('surat-perizinan-sakit.store');

Route::get('/surat-perizinan-sakit/{suratPerizinanSakit}', [SuratPerizinanSakitController::class, 'show'])
    ->middleware('auth')
    ->name('surat-perizinan-sakit.show');
