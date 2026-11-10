<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('izin_notifikasis', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('ID penerima notifikasi (dihapus otomatis jika user dihapus)');

            $table->foreignId('surat_izin_id')
                ->nullable()
                ->constrained('surat_izins')
                ->nullOnDelete()
                ->comment('ID surat izin terkait (null jika surat dihapus)');

            $table->string('judul', 150)
                ->comment('Judul notifikasi (contoh: Surat Izin Disetujui)');

            $table->text('pesan')
                ->comment('Isi lengkap pesan notifikasi');

            $table->timestamp('dibaca_at')->nullable()
                ->comment('Waktu notifikasi dibaca (null = belum dibaca)');

            $table->timestamps();

            $table->softDeletes();

            $table->index('user_id');

            $table->index(['user_id', 'dibaca_at'], 'izin_notif_unread_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_notifikasis');
    }
};
