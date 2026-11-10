<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $aksi
 * @property string|null $model_type
 * @property int|null $model_id
 * @property array|null $perubahan
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id', 'aksi', 'model_type', 'model_id',
        'perubahan', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'perubahan' => 'array',
    ];

    protected $appends = [
        'aksi_label',
        'badge_class',
        'device_type',
        'waktu_format',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAksiLabelAttribute(): string
    {
        return match (strtolower($this->aksi)) {
            'create' => 'Menambahkan Data',
            'update' => 'Memperbarui Data',
            'delete' => 'Menghapus Data',
            'login' => 'Masuk Sistem',
            'logout' => 'Keluar Sistem',
            'approve' => 'Menyetujui',
            'reject' => 'Menolak',
            default => ucfirst(str_replace('_', ' ', $this->aksi)),
        };
    }

    public function getBadgeClassAttribute(): string
    {
        return match (strtolower($this->aksi)) {
            'create' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            'update' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',
            'delete', 'reject' => 'bg-red-500/10 text-red-600 border-red-500/20',
            'login', 'logout' => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
            'approve' => 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20',
            default => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
        };
    }

    public function getDeviceTypeAttribute(): string
    {
        $ua = strtolower($this->user_agent ?? '');

        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'Mobile';
        }

        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'Tablet';
        }

        return 'Desktop';
    }

    public function getWaktuFormatAttribute(): string
    {
        return $this->created_at ? $this->created_at->translatedFormat('d M Y, H:i') : '-';
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAksi(Builder $query, string $aksi): Builder
    {
        return $query->where('aksi', $aksi);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }
}
