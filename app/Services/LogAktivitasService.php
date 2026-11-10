<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogAktivitasService
{
    private array $sensitiveFields = [
        'password',
        'password_confirmation',
        'remember_token',
        'token',
    ];

    public function catat(
        string $aksi,
        ?Model $model = null,
        ?array $before = null,
        ?array $after = null,
    ): void {
        $isConsole = app()->runningInConsole();

        $userId = Auth::id() ?? 0;
        $ipAddress = $isConsole ? '127.0.0.1 (CLI)' : request()->ip();
        $userAgent = $isConsole ? 'System / Artisan CLI' : request()->userAgent();

        LogAktivitas::create([
            'user_id' => $userId,
            'aksi' => $aksi,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->getKey(),
            'perubahan' => [
                'before' => $this->maskSensitiveData($before),
                'after' => $this->maskSensitiveData($after),
            ],
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    public function logCreate(Model $model): void
    {
        $this->catat('create', $model, null, $model->getAttributes());
    }

    public function logUpdate(Model $model, array $before): void
    {
        $after = $model->getChanges();

        if (empty($after)) {
            return;
        }

        $this->catat('update', $model, $before, $after);
    }

    public function logDelete(Model $model): void
    {
        $this->catat('delete', $model, $model->getAttributes(), null);
    }

    private function maskSensitiveData(?array $data): ?array
    {
        if ($data === null) {
            return null;
        }

        foreach ($this->sensitiveFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = '******** (DISENSOR)';
            }
        }

        return $data;
    }
}
