<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->string('nomor_pengajuan', 50)->unique()
                ->comment('Nomor internal pengajuan (contoh: PGJ/2025/10/0001)');

            $table->string('nomor_surat', 50)->unique()->nullable()
                ->comment('Nomor surat resmi (di-generate saat disetujui)');

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('ID Guru/Pegawai yang mengajukan');

            $table->foreignId('blanko_id')
                ->constrained('blanko_surat')
                ->restrictOnDelete()
                ->comment('ID Blanko Surat yang digunakan');

            $table->date('tanggal_pelaksanaan')
                ->comment('Tanggal kegiatan akan dilaksanakan');

            $table->time('waktu_mulai')->nullable()
                ->comment('Jam mulai kegiatan');

            $table->time('waktu_selesai')->nullable()
                ->comment('Jam selesai kegiatan');

            $table->string('tempat', 200)->nullable()
                ->comment('Tempat pelaksanaan kegiatan');

            $table->string('nama_kegiatan', 200)
                ->comment('Nama kegiatan yang diajukan');

            $table->string('jenis_surat', 50)
                ->comment('Jenis surat yang diajukan');

            $table->text('keterangan')->nullable()
                ->comment('Catatan tambahan dari pengaju');

            $table->enum('status', [
                'draft', 'diajukan', 'diproses', 'disetujui', 'ditolak', 'selesai',
            ])->default('draft')
                ->comment('Status alur persetujuan');

            $table->tinyInteger('current_step')->default(0)
                ->comment('Tahap persetujuan (0=Draft, 1=Admin, 2=Pimpinan, 3=Selesai)');

            $table->foreignId('reviewed_by')->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('ID User yang terakhir memproses');

            $table->timestamp('reviewed_at')->nullable()
                ->comment('Waktu terakhir diproses');

            $table->text('catatan_reviewer')->nullable()
                ->comment('Catatan dari Admin/Pimpinan');

            $table->char('qr_token', 36)->unique()->nullable()
                ->comment('UUID unik untuk verifikasi publik');

            $table->timestamp('qr_generated_at')->nullable()
                ->comment('Waktu QR Code dibuat');

            $table->timestamps();

            $table->softDeletes();

            $table->index('user_id');
            $table->index('blanko_id');
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'current_step']);
            $table->index('tanggal_pelaksanaan');
            $table->index('reviewed_by');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE pengajuan_surat
                ADD CONSTRAINT chk_pengajuan_nomor_surat
                CHECK (
                    (status IN ('disetujui','selesai') AND nomor_surat IS NOT NULL)
                    OR status NOT IN ('disetujui','selesai')
                )
            ");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
