<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property Carbon $tanggal_mulai
 * @property Carbon $tanggal_selesai
 * @property string $alasan
 * @property string $status
 * @property string|null $catatan_tu
 * @property string|null $surat_dokter_path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable(['tanggal_mulai', 'tanggal_selesai', 'alasan', 'user_id', 'status', 'catatan_tu', 'surat_dokter_path'])]
class SuratPerizinanSakit extends Model
{
    protected $table = 'surat_perizinan_sakit';

    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_PERLU_REVISI = 'perlu_revisi';

    protected $appends = [
        'status_label',
        'ui_badge_class',
        'ui_icon',
        'durasi_hari',
        'tanggal_format',
    ];

    /**
     * @return list<string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_MENUNGGU,
            self::STATUS_DISETUJUI,
            self::STATUS_DITOLAK,
            self::STATUS_PERLU_REVISI,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => 'Menunggu Verifikasi',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_PERLU_REVISI => 'Perlu Revisi',
            default => 'Tidak Diketahui',
        };
    }

    public function getUiBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
            self::STATUS_DISETUJUI => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            self::STATUS_DITOLAK => 'bg-red-500/10 text-red-600 border-red-500/20',
            self::STATUS_PERLU_REVISI => 'bg-orange-500/10 text-orange-600 border-orange-500/20',
            default => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
        };
    }

    public function getUiIconAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => 'clock',
            self::STATUS_DISETUJUI => 'check-circle',
            self::STATUS_DITOLAK => 'x-circle',
            self::STATUS_PERLU_REVISI => 'pencil-square',
            default => 'information-circle',
        };
    }

    public function getDurasiHariAttribute(): int
    {
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return 0;
        }

        return (int) $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }

    public function getTanggalFormatAttribute(): string
    {
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return '-';
        }

        return $this->tanggal_mulai->translatedFormat('d M Y').' - '.$this->tanggal_selesai->translatedFormat('d M Y');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    public function needsRevision(): bool
    {
        return $this->status === self::STATUS_PERLU_REVISI;
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_MENUNGGU);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }
}
