<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->string('random_code', 50)->unique()
                ->comment('Kode acak unik untuk pencarian cepat (contoh: SM-A1B2C3)');

            $table->string('nama_surat', 255)
                ->comment('Nama atau perihal surat masuk');

            $table->string('kategori_folder', 100)
                ->comment('Kategori folder surat (contoh: Surat Industri, Surat Dinas)');

            $table->string('asal_surat', 255)
                ->comment('Nama instansi atau perusahaan pengirim surat');

            $table->date('tanggal_diterima')
                ->comment('Tanggal surat diterima oleh sekolah');

            $table->text('deskripsi')->nullable()
                ->comment('Catatan tambahan terkait isi surat');

            $table->string('file_surat', 255)->nullable()
                ->comment('Path file scan surat di storage');

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('ID Admin TU yang menginput surat masuk');

            $table->timestamps();

            $table->softDeletes();

            $table->index('kategori_folder');

            $table->index('tanggal_diterima');

            $table->index(['kategori_folder', 'tanggal_diterima'], 'surat_masuk_kategori_tanggal_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
