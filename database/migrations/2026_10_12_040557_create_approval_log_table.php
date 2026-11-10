<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->foreignId('pengajuan_id')
                ->constrained('pengajuan_surat')
                ->cascadeOnDelete()
                ->comment('ID pengajuan yang diproses (dihapus otomatis jika pengajuan dihapus)');

            $table->unsignedTinyInteger('step')
                ->comment('Tahap persetujuan (1=Admin TU, 2=Pimpinan)');

            $table->foreignId('approver_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('ID user yang melakukan aksi (tidak bisa dihapus jika punya log)');

            $table->enum('aksi', ['approve', 'reject', 'revisi'])
                ->comment('Tindakan yang diambil (Setujui, Tolak, atau Minta Revisi)');

            $table->text('catatan')->nullable()
                ->comment('Alasan penolakan atau catatan revisi dari approver');

            $table->timestamp('acted_at')->useCurrent()
                ->comment('Waktu tindakan dilakukan');

            $table->timestamps();

            $table->index('pengajuan_id');
            $table->index(['pengajuan_id', 'step']);

            $table->index('approver_id');

            $table->index(['approver_id', 'acted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_log');
    }
};
