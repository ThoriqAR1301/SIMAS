<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampiran', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->string('attachable_type', 100)
                ->comment('Model pemilik lampiran (contoh: App\Models\PengajuanSurat)');

            $table->unsignedBigInteger('attachable_id')
                ->comment('ID pemilik lampiran');

            $table->string('nama_file', 255)
                ->comment('Nama asli file saat diunggah (contoh: surat_dokter.pdf)');

            $table->string('path', 255)
                ->comment('Path file di storage (contoh: lampiran/pengajuan/uuid.pdf)');

            $table->string('mime_type', 100)
                ->comment('Tipe MIME file (contoh: application/pdf, image/png)');

            $table->unsignedBigInteger('ukuran')
                ->comment('Ukuran file dalam bytes');

            $table->timestamps();

            $table->softDeletes();

            $table->index(['attachable_type', 'attachable_id'], 'lampiran_attachable_index');

            $table->index('mime_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran');
    }
};
