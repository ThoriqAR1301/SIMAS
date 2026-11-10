<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection(): ?string
    {
        return config('telescope.storage.database.connection');
    }

    public function up(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->create('telescope_entries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->bigIncrements('sequence');

            $table->uuid('uuid')
                ->comment('UUID unik untuk setiap entry log');

            $table->uuid('batch_id')
                ->comment('UUID grup batch (mengelompokkan entry dari request yang sama)');

            $table->string('family_hash', 100)->nullable()
                ->comment('Hash untuk mengelompokkan entry serupa (deduplikasi)');

            $table->boolean('should_display_on_index')->default(true)
                ->comment('Apakah entry ditampilkan di halaman utama Telescope?');

            $table->string('type', 20)
                ->comment('Jenis entry (request, query, exception, mail, dll)');

            $table->longText('content')
                ->comment('Payload lengkap entry (format JSON)');

            $table->dateTime('created_at')->nullable()
                ->comment('Waktu entry dibuat');

            $table->unique('uuid');
            $table->index('batch_id');
            $table->index('family_hash');
            $table->index('created_at');
            $table->index(['type', 'should_display_on_index']);

            $table->index(['type', 'created_at'], 'telescope_type_created_index');
        });

        $schema->create('telescope_entries_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->uuid('entry_uuid')
                ->comment('UUID entry yang diberi tag');

            $table->string('tag', 100)
                ->comment('Nama tag (contoh: slow, failed, dll)');

            $table->primary(['entry_uuid', 'tag']);
            $table->index('tag');

            $table->foreign('entry_uuid')
                ->references('uuid')
                ->on('telescope_entries')
                ->cascadeOnDelete()
                ->comment('Tag dihapus otomatis jika entry dihapus');
        });

        $schema->create('telescope_monitoring', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->string('tag', 100)->primary()
                ->comment('Tag yang sedang dipantau (monitoring)');
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->dropIfExists('telescope_entries_tags');
        $schema->dropIfExists('telescope_entries');
        $schema->dropIfExists('telescope_monitoring');
    }
};
