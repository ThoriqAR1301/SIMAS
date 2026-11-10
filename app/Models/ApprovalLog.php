<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalLog extends Model
{
    protected $table = 'approval_log';

    protected $fillable = [
        'pengajuan_id',
        'step',
        'approver_id',
        'aksi',
        'catatan',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
        'step' => 'integer',
    ];

    protected $appends = [
        'label_step',
        'label_aksi',
        'warna_aksi',
        'badge_class',
        'icon_aksi',
        'waktu_format',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getLabelStepAttribute(): string
    {
        return match ($this->step) {
            1 => 'Admin TU',
            2 => 'Pimpinan',
            default => "Step {$this->step}",
        };
    }

    public function getLabelAksiAttribute(): string
    {
        return match ($this->aksi) {
            'approve' => 'Disetujui',
            'reject' => 'Ditolak',
            'revisi' => 'Diminta Revisi',
            default => ucfirst($this->aksi),
        };
    }

    public function getWarnaAksiAttribute(): string
    {
        return match ($this->aksi) {
            'approve' => 'emerald',
            'reject' => 'red',
            'revisi' => 'amber',
            default => 'slate',
        };
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->aksi) {
            'approve' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            'reject' => 'bg-red-500/10 text-red-600 border-red-500/20',
            'revisi' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
            default => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
        };
    }

    public function getIconAksiAttribute(): string
    {
        return match ($this->aksi) {
            'approve' => 'check-circle',
            'reject' => 'x-circle',
            'revisi' => 'pencil-square',
            default => 'information-circle',
        };
    }

    public function getWaktuFormatAttribute(): string
    {
        return $this->acted_at ? $this->acted_at->translatedFormat('d M Y, H:i') : '-';
    }

    public function scopeApprove(Builder $query): Builder
    {
        return $query->where('aksi', 'approve');
    }

    public function scopeReject(Builder $query): Builder
    {
        return $query->where('aksi', 'reject');
    }

    public function scopeRevisi(Builder $query): Builder
    {
        return $query->where('aksi', 'revisi');
    }
}
