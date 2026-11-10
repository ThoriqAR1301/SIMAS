<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            try {
                $table->dropUnique(['qr_token']);
            } catch (Throwable $e) {
            }

            $columnsToDrop = [];
            if (Schema::hasColumn('pengajuan_surat', 'qr_token')) {
                $columnsToDrop[] = 'qr_token';
            }
            if (Schema::hasColumn('pengajuan_surat', 'qr_generated_at')) {
                $columnsToDrop[] = 'qr_generated_at';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            if (! Schema::hasColumn('pengajuan_surat', 'qr_token')) {
                $table->char('qr_token', 36)
                    ->nullable()
                    ->unique()
                    ->comment('UUID unik untuk verifikasi publik (dihapus)');
            }

            if (! Schema::hasColumn('pengajuan_surat', 'qr_generated_at')) {
                $table->timestamp('qr_generated_at')
                    ->nullable()
                    ->comment('Waktu QR Code dibuat (dihapus)');
            }
        });
    }
};
