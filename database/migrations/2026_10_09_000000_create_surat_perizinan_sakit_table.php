<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_perizinan_sakit', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('ID Guru/Pegawai yang mengajukan izin');

            $table->date('tanggal_mulai')
                ->comment('Tanggal pertama tidak masuk');

            $table->date('tanggal_selesai')
                ->comment('Tanggal terakhir tidak masuk');

            $table->text('alasan')
                ->comment('Keterangan sakit dari Guru');

            $table->string('surat_dokter_path', 255)
                ->comment('Path file surat keterangan dokter di storage');

            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'perlu_revisi',
            ])->default('menunggu')
                ->comment('Status pemeriksaan oleh Admin TU');

            $table->text('catatan_tu')
                ->nullable()
                ->comment('Catatan/Alasan dari Admin TU jika ditolak atau revisi');

            $table->timestamps();

            $table->index('user_id');

            $table->index('status');

            $table->index('tanggal_mulai');

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_perizinan_sakit');
    }
};
