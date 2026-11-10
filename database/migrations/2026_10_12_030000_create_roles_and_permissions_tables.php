<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->string('name', 50)->unique()
                ->comment('Nama unik role (contoh: admin_tu, guru, pimpinan)');

            $table->string('display_name', 100)
                ->comment('Nama tampilan role untuk UI');

            $table->string('deskripsi', 255)->nullable()
                ->comment('Penjelasan singkat tentang role');

            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->string('name', 100)->unique()
                ->comment('Nama unik permission (contoh: pengajuan.create)');

            $table->string('display_name', 150)
                ->comment('Nama tampilan permission untuk UI');

            $table->string('grup', 50)->nullable()
                ->comment('Grup permission (contoh: pengajuan, surat_masuk, master)');

            $table->string('deskripsi', 255)->nullable()
                ->comment('Penjelasan singkat tentang permission');

            $table->timestamps();

            $table->index('grup');
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete()
                ->comment('ID role yang memiliki permission');

            $table->foreignId('permission_id')
                ->constrained('permissions')
                ->cascadeOnDelete()
                ->comment('ID permission yang dimiliki role');

            $table->primary(['role_id', 'permission_id']);
        });

        $hasRoleId = Schema::hasColumn('users', 'role_id');
        $hasIsActive = Schema::hasColumn('users', 'is_active');

        if (! $hasRoleId || ! $hasIsActive) {
            Schema::table('users', function (Blueprint $table) use ($hasRoleId, $hasIsActive) {
                if (! $hasRoleId) {
                    $table->foreignId('role_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('roles')
                        ->restrictOnDelete()
                        ->comment('Peran pengguna dalam sistem (restrictOnDelete: role tidak bisa dihapus jika masih dipakai)');
                }

                if (! $hasIsActive) {
                    $table->boolean('is_active')
                        ->default(true)
                        ->after('role_id')
                        ->comment('Status aktif akun (false = tidak bisa login)');

                    $table->index('is_active');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn('is_active');
        });

        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
