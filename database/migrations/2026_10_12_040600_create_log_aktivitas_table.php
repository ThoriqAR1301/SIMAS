<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('ID pengguna yang melakukan aksi (null jika user dihapus permanen)');

            $table->string('aksi', 50)
                ->comment('Jenis aksi yang dilakukan (contoh: create_pengajuan, login)');

            $table->nullableMorphs('model');

            $table->json('perubahan')->nullable()
                ->comment('Data sebelum dan sesudah perubahan (format JSON)');

            $table->string('ip_address', 45)->nullable()
                ->comment('Alamat IP pengguna (mendukung IPv6)');

            $table->text('user_agent')->nullable()
                ->comment('Informasi browser/perangkat pengguna');

            $table->timestamps();

            $table->index(['aksi', 'created_at']);

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
