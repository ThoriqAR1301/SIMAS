<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_surat')->cascadeOnDelete();
            $table->tinyInteger('step');
            $table->foreignId('approver_id')->constrained('users')->restrictOnDelete();
            $table->enum('aksi', ['approve', 'reject', 'revisi']);
            $table->text('catatan')->nullable();
            $table->timestamp('acted_at');

            $table->timestamps();

            $table->index('pengajuan_id');
            $table->index(['pengajuan_id', 'step']);
            $table->index('approver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_log');
    }
};
