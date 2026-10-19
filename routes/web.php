<?php

use App\Http\Controllers\SuratPerizinanSakitController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerifikasiSuratController;
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
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.baca');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.baca-semua');

    Route::get('/pengajuan/{pengajuan}/timeline', [PengajuanSuratController::class, 'timeline'])->name('pengajuan.timeline');
    Route::get('/pengajuan/{pengajuan}/pdf', [PengajuanSuratController::class, 'pdf'])->name('pengajuan.pdf');
    Route::post('/pengajuan/{pengajuan}/submit', [PengajuanSuratController::class, 'submit'])->name('pengajuan.submit');
    Route::resource('pengajuan', PengajuanSuratController::class);

    Route::prefix('approval')
        ->name('approval.')
        ->middleware('role:admin_tu,pimpinan')
        ->controller(ApprovalController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{pengajuan}/verify', 'verify')->name('verify');
            Route::post('/{pengajuan}/approve', 'approve')->name('approve');
            Route::post('/{pengajuan}/reject', 'reject')->name('reject');
            Route::post('/{pengajuan}/revisi', 'mintaRevisi')->name('revisi');
        });
});

Route::get('/verifikasi/{token}', [VerifikasiSuratController::class, 'show'])->name('verifikasi.publik');

require __DIR__.'/auth.php';
