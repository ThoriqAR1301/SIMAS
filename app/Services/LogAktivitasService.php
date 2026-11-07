<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogAktivitasService
{
    public function catat(
        string $aksi,
        ?Model $model = null,
        ?array $before = null,
        ?array $after = null,
    ): void {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => $aksi,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->getKey(),
            'perubahan' => [
                'before' => $before,
                'after' => $after,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
