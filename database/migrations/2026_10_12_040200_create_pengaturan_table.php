<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->string('key', 100)->primary()
                ->comment('Kunci unik pengaturan (contoh: surat.format_nomor)');

            $table->text('value')->nullable()
                ->comment('Nilai dari pengaturan (bisa string, int, bool, atau json)');

            $table->string('grup', 50)->default('umum')
                ->comment('Grup pengaturan (umum, surat, notif, retensi, laporan)');

            $table->enum('tipe', ['string', 'int', 'bool', 'json'])
                ->default('string')
                ->comment('Tipe data nilai (string, int, bool, json)');

            $table->string('deskripsi', 255)->nullable()
                ->comment('Penjelasan singkat tentang pengaturan ini');

            $table->timestamps();

            $table->index('grup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
