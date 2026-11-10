<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $surat_izin_id
 * @property string $judul
 * @property string $pesan
 * @property Carbon|null $dibaca_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class IzinNotifikasi extends Model
{
    protected $table = 'izin_notifikasis';

    protected $fillable = [
        'user_id',
        'surat_izin_id',
        'judul',
        'pesan',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    protected $appends = [
        'is_read',
        'waktu_terbaca',
        'ui_color',
        'ui_icon',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function suratIzin(): BelongsTo
    {
        return $this->belongsTo(SuratIzin::class, 'surat_izin_id');
    }

    public static function kirim(int $userId, ?int $suratId, string $judul, string $pesan): self
    {
        return static::create([
            'user_id' => $userId,
            'surat_izin_id' => $suratId,
            'judul' => $judul,
            'pesan' => $pesan,
        ]);
    }

    public function markAsRead(): bool
    {
        if ($this->dibaca_at !== null) {
            return false; // Sudah dibaca sebelumnya
        }

        return $this->update(['dibaca_at' => now()]);
    }

    public function getIsReadAttribute(): bool
    {
        return $this->dibaca_at !== null;
    }

    public function getWaktuTerbacaAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getUiColorAttribute(): string
    {
        $judul = strtolower($this->judul);

        if (str_contains($judul, 'disetujui') || str_contains($judul, 'diterima')) {
            return 'emerald';
        }
        if (str_contains($judul, 'ditolak')) {
            return 'red';
        }
        if (str_contains($judul, 'revisi')) {
            return 'orange';
        }

        return 'blue';
    }

    public function getUiIconAttribute(): string
    {
        return match ($this->ui_color) {
            'emerald' => 'check-circle',
            'red' => 'x-circle',
            'orange' => 'pencil-square',
            default => 'information-circle',
        };
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('dibaca_at');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('dibaca_at');
    }
}
