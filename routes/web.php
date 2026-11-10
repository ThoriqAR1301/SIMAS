<?php

use App\Http\Controllers\AdminAkunController;
use App\Http\Controllers\AdminSuratIzinController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\IzinNotifikasiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratIzinController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratPerizinanSakitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    Route::controller(NotifikasiController::class)->prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/unread-count', 'unreadCount')->name('unread-count');
        Route::post('/{id}/baca', 'markAsRead')->name('baca');
        Route::post('/baca-semua', 'markAllAsRead')->name('baca-semua');
    });

    Route::controller(IzinNotifikasiController::class)->prefix('notifikasi-izin')->name('izin-notif.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/jumlah', 'jumlah')->name('jumlah');
        Route::post('/baca-semua', 'bacaSemua')->name('baca-semua');
        Route::post('/{notif}/baca', 'baca')->name('baca');
    });

    Route::controller(PengajuanSuratController::class)->prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/{pengajuan}/timeline', 'timeline')->name('timeline');
        Route::get('/{pengajuan}/pdf', 'pdf')->name('pdf');
        Route::post('/{pengajuan}/submit', 'submit')->name('submit');
    });
    Route::resource('pengajuan', PengajuanSuratController::class);

    Route::controller(SuratPerizinanSakitController::class)->prefix('surat-perizinan-sakit')->name('surat-perizinan-sakit.')->group(function () {
        Route::middleware('role:guru')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::post('/{suratPerizinanSakit}/revisi', 'revisi')->name('revisi');
        });

        Route::get('/', 'index')->name('index');
        Route::post('/verify/{suratPerizinanSakit}', 'verify')->middleware('role:admin_tu')->name('verify');
        Route::get('/dokter/{suratPerizinanSakit}', 'dokter')->name('dokter');
        Route::get('/{suratPerizinanSakit}', 'show')->name('show');
    });

    Route::resource('surat-masuk', SuratMasukController::class);

    Route::controller(SuratIzinController::class)->prefix('surat-izin')->name('surat-izin.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/buat', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{suratIzin}', 'show')->name('show');
        Route::get('/{suratIzin}/edit', 'edit')->name('edit');
        Route::put('/{suratIzin}', 'update')->name('update');
        Route::get('/{suratIzin}/pdf', 'pdf')->name('pdf');
    });

    Route::controller(AdminSuratIzinController::class)->prefix('admin/surat-izin')->name('admin.surat-izin.')
        ->middleware('role:admin_tu,pimpinan')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{suratIzin}', 'show')->name('show');
            Route::get('/{suratIzin}/pdf', 'pdf')->name('pdf');
            Route::post('/{suratIzin}/setujui', 'setujui')->name('setujui');
            Route::post('/{suratIzin}/tolak', 'tolak')->name('tolak');
        });

    Route::controller(AdminAkunController::class)->prefix('admin/akun')->name('admin.akun.')
        ->middleware('role:admin_tu')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });

    Route::controller(ApprovalController::class)->prefix('approval')->name('approval.')
        ->middleware('role:admin_tu,pimpinan')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{pengajuan}/verify', 'verify')->name('verify');
            Route::post('/{pengajuan}/approve', 'approve')->name('approve');
            Route::post('/{pengajuan}/reject', 'reject')->name('reject');
            Route::post('/{pengajuan}/revisi', 'mintaRevisi')->name('revisi');
        });
});

require __DIR__.'/auth.php';

Route::match(['get', 'post'], '/register', function () {
    return redirect()->route('login');
})->name('register');
