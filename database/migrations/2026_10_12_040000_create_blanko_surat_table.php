<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blanko_surat', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->string('kode', 20)->unique()
                ->comment('Kode unik blanko (contoh: ST, IZIN-SAKIT)');

            $table->string('nama', 100)
                ->comment('Nama tampilan blanko surat');

            $table->text('deskripsi')->nullable()
                ->comment('Penjelasan detail jenis blanko');

            $table->enum('jenis', [
                'tugas',
                'undangan',
                'keterangan',
                'izin_sakit',
                'izin_non_sakit',
                'keperluan_lain',
                'lainnya',
            ])->comment('Kategori jenis surat');

            $table->string('template_path', 255)->nullable()
                ->comment('Path file template surat di storage');

            $table->boolean('requires_lampiran')->default(false)
                ->comment('Apakah wajib upload lampiran saat pengajuan?');

            $table->boolean('requires_surat_awalan')->default(false)
                ->comment('Apakah wajib download & upload surat awalan?');

            $table->boolean('is_active')->default(true)
                ->comment('Status aktif blanko (false = tidak muncul di form)');

            $table->timestamps();

            $table->index('jenis');

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blanko_surat');
    }
};
