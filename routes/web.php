<?php

use App\Http\Controllers\SuratPerizinanSakitController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerifikasiSuratController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Modul Surat Perizinan Sakit
|--------------------------------------------------------------------------
*/

// Form pengajuan baru - khusus Guru.
Route::get(
    '/surat-perizinan-sakit/create',
    [SuratPerizinanSakitController::class, 'create']
)
    ->middleware(['auth', 'role:guru'])
    ->name('surat-perizinan-sakit.create');

// Daftar pengajuan Guru atau Admin TU.
Route::get(
    '/surat-perizinan-sakit',
    [SuratPerizinanSakitController::class, 'index']
)
    ->middleware('auth')
    ->name('surat-perizinan-sakit.index');

// Mengirim pengajuan baru - khusus Guru.
Route::post(
    '/surat-perizinan-sakit',
    [SuratPerizinanSakitController::class, 'store']
)
    ->middleware(['auth', 'role:guru'])
    ->name('surat-perizinan-sakit.store');

// Memproses revisi pengajuan - khusus Guru.
Route::post(
    '/surat-perizinan-sakit/{suratPerizinanSakit}/revisi',
    [SuratPerizinanSakitController::class, 'revisi']
)
    ->middleware(['auth', 'role:guru'])
    ->name('surat-perizinan-sakit.revisi');

// Memproses keputusan pemeriksaan - khusus Admin TU.
Route::post(
    '/surat-perizinan-sakit/verify/{suratPerizinanSakit}',
    [SuratPerizinanSakitController::class, 'verify']
)
    ->middleware(['auth', 'role:admin_tu'])
    ->name('surat-perizinan-sakit.verify');

// Melihat atau mengunduh dokumen surat dokter.
Route::get(
    '/surat-perizinan-sakit/dokter/{suratPerizinanSakit}',
    [SuratPerizinanSakitController::class, 'dokter']
)
    ->middleware('auth')
    ->name('surat-perizinan-sakit.dokter');

// Detail pengajuan.
// Letakkan setelah route khusus agar tidak mengganggu route lainnya.
Route::get(
    '/surat-perizinan-sakit/{suratPerizinanSakit}',
    [SuratPerizinanSakitController::class, 'show']
)
    ->middleware('auth')
    ->name('surat-perizinan-sakit.show');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Route yang Membutuhkan Autentikasi
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Profil pengguna.
    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');


    // Notifikasi.
    Route::get(
        '/notifikasi',
        [NotifikasiController::class, 'index']
    )->name('notifikasi.index');

    Route::get(
        '/notifikasi/unread-count',
        [NotifikasiController::class, 'unreadCount']
    )->name('notifikasi.unread-count');

    Route::post(
        '/notifikasi/{id}/baca',
        [NotifikasiController::class, 'markAsRead']
    )->name('notifikasi.baca');

    Route::post(
        '/notifikasi/baca-semua',
        [NotifikasiController::class, 'markAllAsRead']
    )->name('notifikasi.baca-semua');


    // Pengajuan surat umum.
    Route::get(
        '/pengajuan/{pengajuan}/timeline',
        [PengajuanSuratController::class, 'timeline']
    )->name('pengajuan.timeline');

    Route::get(
        '/pengajuan/{pengajuan}/pdf',
        [PengajuanSuratController::class, 'pdf']
    )->name('pengajuan.pdf');

    Route::post(
        '/pengajuan/{pengajuan}/submit',
        [PengajuanSuratController::class, 'submit']
    )->name('pengajuan.submit');

    Route::resource(
        'pengajuan',
        PengajuanSuratController::class
    );


    // Approval surat umum oleh Admin TU dan Pimpinan.
    Route::prefix('approval')
        ->name('approval.')
        ->middleware('role:admin_tu,pimpinan')
        ->controller(ApprovalController::class)
        ->group(function () {

            Route::get('/', 'index')->name('index');

            Route::post(
                '/{pengajuan}/verify',
                'verify'
            )->name('verify');

            Route::post(
                '/{pengajuan}/approve',
                'approve'
            )->name('approve');

            Route::post(
                '/{pengajuan}/reject',
                'reject'
            )->name('reject');

            Route::post(
                '/{pengajuan}/revisi',
                'mintaRevisi'
            )->name('revisi');
        });
});


/*
|--------------------------------------------------------------------------
| Verifikasi Surat Publik
|--------------------------------------------------------------------------
*/

Route::get(
    '/verifikasi/{token}',
    [VerifikasiSuratController::class, 'show']
)->name('verifikasi.publik');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';