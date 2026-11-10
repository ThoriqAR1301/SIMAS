<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_izins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('ID Guru/Pegawai pemilik surat izin');

            $table->string('nama', 100)
                ->comment('Nama lengkap Guru/Pegawai saat mengajukan');

            $table->string('jabatan', 100)
                ->comment('Jabatan Guru/Pegawai saat mengajukan');

            $table->date('tanggal_surat')
                ->comment('Tanggal surat izin dibuat');

            $table->date('tanggal_izin')
                ->comment('Tanggal ketidakhadiran yang diajukan');

            $table->text('alasan')
                ->comment('Alasan pengajuan izin');

            $table->mediumText('tanda_tangan')->nullable()
                ->comment('Tanda tangan digital (Base64 PNG)');

            $table->enum('status', ['pending', 'disetujui', 'ditolak'])
                ->default('pending')
                ->comment('Status persetujuan surat izin');

            $table->text('catatan_admin')->nullable()
                ->comment('Catatan dari Admin TU saat memproses');

            $table->foreignId('diproses_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('ID Admin TU yang memproses surat');

            $table->timestamp('diproses_pada')->nullable()
                ->comment('Waktu surat diproses oleh Admin TU');

            $table->timestamps();

            $table->softDeletes();

            $table->index('user_id');

            $table->index('status');

            $table->index('tanggal_izin');

            $table->index('diproses_oleh');

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_izins');
    }
};
