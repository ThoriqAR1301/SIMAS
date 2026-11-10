<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasNip = Schema::hasColumn('users', 'nip');
        $hasJabatan = Schema::hasColumn('users', 'jabatan');

        if (! $hasNip || ! $hasJabatan) {
            Schema::table('users', function (Blueprint $table) use ($hasNip, $hasJabatan) {
                if (! $hasNip) {
                    $table->string('nip', 50)
                        ->nullable()
                        ->after('password')
                        ->comment('Nomor Induk Pegawai / NUPTK');

                    $table->index('nip');
                }

                if (! $hasJabatan) {
                    $table->string('jabatan', 100)
                        ->nullable()
                        ->after('nip')
                        ->comment('Jabatan fungsional atau struktural pengguna');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'nip')) {
                $table->dropIndex(['nip']);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'nip')) {
                $columnsToDrop[] = 'nip';
            }
            if (Schema::hasColumn('users', 'jabatan')) {
                $columnsToDrop[] = 'jabatan';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
