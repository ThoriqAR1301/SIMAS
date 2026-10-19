<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    protected $table = 'approval_log';

    protected $fillable = [
        'pengajuan_id', 'step', 'approver_id',
        'aksi', 'catatan', 'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
        'step' => 'integer',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function labelStep(): string
    {
        return match ($this->step) {
            1 => 'Admin TU',
            2 => 'Pimpinan',
            default => "Step {$this->step}",
        };
    }

    public function labelAksi(): string
    {
        return match ($this->aksi) {
            'approve' => 'Disetujui',
            'reject' => 'Ditolak',
            'revisi' => 'Diminta Revisi',
            default => $this->aksi,
        };
    }

    public function warnaAksi(): string
    {
        return match ($this->aksi) {
            'approve' => 'green',
            'reject' => 'red',
            'revisi' => 'yellow',
            default => 'gray',
        };
    }
}
