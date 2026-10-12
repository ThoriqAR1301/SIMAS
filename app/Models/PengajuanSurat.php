<?php

namespace App\Models;

use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'nomor_pengajuan', 'nomor_surat', 'user_id', 'blanko_id',
        'tanggal_pelaksanaan', 'waktu_mulai', 'waktu_selesai',
        'tempat', 'nama_kegiatan', 'jenis_surat', 'keterangan',
        'status', 'current_step', 'reviewed_by', 'reviewed_at',
        'catatan_reviewer', 'qr_token', 'qr_generated_at',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
        'reviewed_at'  => 'datetime',
        'qr_generated_at' => 'datetime',
        'status' => StatusPengajuan::class,
        'current_step' => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blanko()
    {
        return $this->belongsTo(BlankoSurat::class, 'blanko_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class, 'pengajuan_id')->orderBy('step');
    }

    public function lampiran()
    {
        return $this->morphMany(Lampiran::class, 'attachable');
    }

    public function suratIzin()
    {
        return $this->hasOne(SuratIzin::class, 'pengajuan_id');
    }

    public function scopeMilik($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeStatus($query, StatusPengajuan|string $status)
    {
        $value = $status instanceof StatusPengajuan ? $status->value : $status;
        return $query->where('status', $value);
    }

    public function scopeMenungguApproval($query, int $step)
    {
        return $query->where('current_step', $step)
            ->whereIn('status', [
                StatusPengajuan::DIAJUKAN->value,
                StatusPengajuan::DIPROSES->value,
            ]);
    }

    public function scopeCari($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nomor_pengajuan', 'like', "%{$keyword}%")
              ->orWhere('nomor_surat', 'like', "%{$keyword}%")
              ->orWhere('nama_kegiatan', 'like', "%{$keyword}%")
              ->orWhere('jenis_surat', 'like', "%{$keyword}%");
        });
    }

    public function scopeRentangTanggal($query, ?string $dari, ?string $sampai)
    {
        return $query->when($dari, fn ($q) => $q->whereDate('tanggal_pelaksanaan', '>=', $dari))->when($sampai, fn ($q) => $q->whereDate('tanggal_pelaksanaan', '<=', $sampai));
    }

    public function bisaDiedit(): bool
    {
        return $this->status->bisaDiedit() && $this->user_id === auth()->id();
    }

    public function bisaDiajukan(): bool
    {
        return $this->status->bisaDiajukan() && $this->user_id === auth()->id();
    }

    public function bisaDihapus(): bool
    {
        return $this->status->bisaDihapus() && $this->user_id === auth()->id();
    }

    public function sudahDisetujui(): bool
    {
        return in_array($this->status, [
            StatusPengajuan::DISETUJUI,
            StatusPengajuan::SELESAI,
        ]);
    }

    public function sedangMenungguSiapa(): string
    {
        return match ($this->current_step) {
            1 => 'Admin TU',
            2 => 'Pimpinan',
            default => '-',
        };
    }
}