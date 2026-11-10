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
            $table->json('panitia')
                ->nullable()
                ->after('keterangan')
                ->comment('Menyimpan data susunan panitia kegiatan dalam format JSON');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_surat', 'panitia')) {
                $table->dropColumn('panitia');
            }
        });
    }
};
