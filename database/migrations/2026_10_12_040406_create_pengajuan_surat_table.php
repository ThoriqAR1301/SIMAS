<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan', 50)->unique();
            $table->string('nomor_surat', 50)->unique()->nullable();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('blanko_id')->constrained('blanko_surat')->restrictOnDelete();

            $table->date('tanggal_pelaksanaan');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('tempat', 200)->nullable();
            $table->string('nama_kegiatan', 200);
            $table->string('jenis_surat', 50);
            $table->text('keterangan')->nullable();

            $table->enum('status', ['draft', 'diajukan', 'diproses', 'disetujui', 'ditolak', 'selesai'])->default('draft');
            $table->tinyInteger('current_step')->default(0);
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('catatan_reviewer')->nullable();

            $table->char('qr_token', 36)->unique()->nullable();
            $table->timestamp('qr_generated_at')->nullable();

            $table->timestamps();

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
