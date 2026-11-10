<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->string('key', 255)->primary()
                ->comment('Kunci unik cache (hash)');

            $table->mediumText('value')
                ->comment('Payload data yang disimpan (serialized)');

            $table->bigInteger('expiration')->index()
                ->comment('Unix timestamp kapan cache ini kadaluarsa');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->string('key', 255)->primary()
                ->comment('Kunci unik lock (hash)');

            $table->string('owner', 255)
                ->comment('ID proses/worker yang sedang memegang lock');

            $table->bigInteger('expiration')->index()
                ->comment('Unix timestamp kapan lock ini dilepas otomatis');
        });
    }

    public function down(): void
    {

        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};
