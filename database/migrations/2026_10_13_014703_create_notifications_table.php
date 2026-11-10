<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->uuid('id')->primary()
                ->comment('UUID unik notifikasi');

            $table->string('type', 100)
                ->comment('Class Notification yang dikirim (contoh: App\Notifications\PengajuanBaru)');

            $table->string('notifiable_type', 100)
                ->comment('Model penerima notifikasi (contoh: App\Models\User)');

            $table->unsignedBigInteger('notifiable_id')
                ->comment('ID penerima notifikasi');

            $table->text('data')
                ->comment('Payload data notifikasi (format JSON)');

            $table->timestamp('read_at')->nullable()
                ->comment('Waktu notifikasi dibaca (null = belum dibaca)');

            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_index');

            $table->index(
                ['notifiable_type', 'notifiable_id', 'read_at'],
                'notifications_unread_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
