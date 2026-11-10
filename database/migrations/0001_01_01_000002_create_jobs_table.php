<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('queue', 100)->index()
                ->comment('Nama antrean (default: default)');

            $table->longText('payload')
                ->comment('Data pekerjaan (serialized object)');

            $table->unsignedSmallInteger('attempts')
                ->comment('Berapa kali pekerjaan ini sudah dicoba');

            $table->unsignedInteger('reserved_at')->nullable()
                ->comment('Unix timestamp saat pekerjaan mulai diproses');

            $table->unsignedInteger('available_at')
                ->comment('Unix timestamp kapan pekerjaan bisa dijalankan');

            $table->unsignedInteger('created_at')
                ->comment('Unix timestamp saat pekerjaan masuk antrean');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->string('id', 36)->primary()
                ->comment('UUID batch');

            $table->string('name', 100)
                ->comment('Nama batch');

            $table->integer('total_jobs')
                ->comment('Total pekerjaan dalam batch');

            $table->integer('pending_jobs')
                ->comment('Sisa pekerjaan yang belum selesai');

            $table->integer('failed_jobs')
                ->comment('Jumlah pekerjaan yang gagal');

            $table->longText('failed_job_ids')
                ->comment('Daftar ID pekerjaan yang gagal');

            $table->mediumText('options')->nullable()
                ->comment('Opsi kustom tambahan');

            $table->integer('cancelled_at')->nullable()
                ->comment('Unix timestamp saat batch dibatalkan');

            $table->integer('created_at')
                ->comment('Unix timestamp saat batch dibuat');

            $table->integer('finished_at')->nullable()
                ->comment('Unix timestamp saat batch selesai');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('uuid', 36)->unique()
                ->comment('UUID pekerjaan yang gagal');

            $table->string('connection', 100)
                ->comment('Koneksi queue (database/redis)');

            $table->string('queue', 100)
                ->comment('Nama antrean');

            $table->longText('payload')
                ->comment('Data pekerjaan yang gagal');

            $table->longText('exception')
                ->comment('Detail error/exception');

            $table->timestamp('failed_at')->useCurrent()
                ->comment('Waktu kegagalan terjadi');

            $table->index(['connection', 'queue', 'failed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
    }
};
