<?php

use App\Http\Controllers\AdminAkunController;
use App\Http\Controllers\AdminSuratIzinController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\IzinNotifikasiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengajuanHapusController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratIzinController;
use App\Http\Controllers\VerifikasiSuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Pendaftaran mandiri ditutup: akun hanya dibuat oleh Admin TU
Route::match(['get', 'post'], '/register', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.baca');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.baca-semua');

    // ===== NOTIFIKASI SURAT IZIN =====
    Route::prefix('notifikasi-izin')
        ->name('izin-notif.')
        ->controller(IzinNotifikasiController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/jumlah', 'jumlah')->name('jumlah');
            Route::post('/baca-semua', 'bacaSemua')->name('baca-semua');
            Route::post('/{notif}/baca', 'baca')->name('baca');
        });

    Route::get('/pengajuan/{pengajuan}/timeline', [PengajuanSuratController::class, 'timeline'])->name('pengajuan.timeline');
    Route::get('/pengajuan/{pengajuan}/pdf', [PengajuanSuratController::class, 'pdf'])->name('pengajuan.pdf');
    Route::post('/pengajuan/{pengajuan}/submit', [PengajuanSuratController::class, 'submit'])->name('pengajuan.submit');

    // ===== HAPUS PENGAJUAN (ADMIN) - harus sebelum Route::resource =====
    Route::delete('/pengajuan/{pengajuan}/hapus', [PengajuanHapusController::class, 'destroy'])->name('pengajuan.hapus');
    Route::delete('/pengajuan-hapus-draft', [PengajuanHapusController::class, 'hapusDraft'])->name('pengajuan.hapus-draft');

    Route::resource('pengajuan', PengajuanSuratController::class);

    // ===== SURAT IZIN GURU & PEGAWAI =====
    Route::prefix('surat-izin')
        ->name('surat-izin.')
        ->controller(SuratIzinController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/buat', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{suratIzin}', 'show')->name('show');
            Route::get('/{suratIzin}/edit', 'edit')->name('edit');
            Route::put('/{suratIzin}', 'update')->name('update');
            Route::get('/{suratIzin}/pdf', 'pdf')->name('pdf');
        });

    // ===== SURAT IZIN: ADMIN (BU AESA) =====
    Route::prefix('admin/surat-izin')
        ->name('admin.surat-izin.')
        ->middleware('role:admin_tu,pimpinan')
        ->controller(AdminSuratIzinController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{suratIzin}', 'show')->name('show');
            Route::get('/{suratIzin}/pdf', 'pdf')->name('pdf');
            Route::post('/{suratIzin}/setujui', 'setujui')->name('setujui');
            Route::post('/{suratIzin}/tolak', 'tolak')->name('tolak');
        });

    // ===== KELOLA AKUN GURU (HANYA ADMIN TU) =====
    Route::prefix('admin/akun')
        ->name('admin.akun.')
        ->middleware('role:admin_tu')
        ->controller(AdminAkunController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });

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