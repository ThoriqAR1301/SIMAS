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
 * @property string $nama
 * @property string $jabatan
 * @property Carbon $tanggal_surat
 * @property Carbon $tanggal_izin
 * @property string $alasan
 * @property string|null $tanda_tangan
 * @property string $status
 * @property string|null $catatan_admin
 * @property int|null $diproses_oleh
 * @property Carbon|null $diproses_pada
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SuratIzin extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'jabatan',
        'tanggal_surat',
        'tanggal_izin',
        'alasan',
        'tanda_tangan',
        'status',
        'catatan_admin',
        'diproses_oleh',
        'diproses_pada',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_izin' => 'date',
        'diproses_pada' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'ui_badge_class',
        'ui_icon',
        'tanggal_izin_format',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pemroses(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getUiBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
            'disetujui' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            'ditolak' => 'bg-red-500/10 text-red-600 border-red-500/20',
            default => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
        };
    }

    public function getUiIconAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'clock',
            'disetujui' => 'check-circle',
            'ditolak' => 'x-circle',
            default => 'information-circle',
        };
    }

    public function getTanggalIzinFormatAttribute(): string
    {
        return $this->tanggal_izin ? $this->tanggal_izin->translatedFormat('d F Y') : '-';
    }

    public function isPending(): bool
    {
        return $this->status === 'diajukan';
    }

    public function isApproved(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isRejected(): bool
    {
        return $this->status === 'ditolak';
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'diajukan');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    protected static function booted(): void
    {
        static::created(function (SuratIzin $s) {
            try {
                $adminIds = User::whereHas('role', fn ($q) => $q->whereIn('name', ['admin_tu', 'pimpinan']))
                    ->where('id', '!=', $s->user_id)
                    ->pluck('id');

                foreach ($adminIds as $id) {
                    IzinNotifikasi::kirim(
                        $id,
                        $s->id,
                        'Surat izin baru',
                        $s->nama.' mengajukan surat izin untuk tanggal '.$s->tanggal_izin->format('d/m/Y').'.'
                    );
                }
            } catch (\Throwable $e) {
                report($e);
            }
        });

        static::updated(function (SuratIzin $s) {
            if (! $s->wasChanged('status')) {
                return;
            }

            if (! in_array($s->status, ['disetujui', 'ditolak'], true)) {
                return;
            }

            try {
                $setuju = $s->status === 'disetujui';

                IzinNotifikasi::kirim(
                    $s->user_id,
                    $s->id,
                    $setuju ? 'Surat izin disetujui' : 'Surat izin ditolak',
                    'Surat izin untuk tanggal '.$s->tanggal_izin->format('d/m/Y').' telah '.($setuju ? 'disetujui.' : 'ditolak.')
                );
            } catch (\Throwable $e) {
                report($e);
            }
        });
    }
}
