<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property string|null $deskripsi
 * @property string $jenis
 * @property string|null $template_path
 * @property bool $requires_lampiran
 * @property bool $requires_surat_awalan
 * @property bool $is_active
 */
class BlankoSurat extends Model
{
    use HasFactory;

    protected $table = 'blanko_surat';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'jenis',
        'template_path',
        'requires_lampiran',
        'requires_surat_awalan',
        'is_active',
    ];

    protected $casts = [
        'requires_lampiran' => 'boolean',
        'requires_surat_awalan' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'ui_badge_class',
        'ui_icon',
        'jenis_label',
    ];

    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class, 'blanko_id');
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'izin_sakit' => 'Izin Sakit',
            'izin_non_sakit' => 'Izin Non-Sakit',
            'keperluan_lain' => 'Keperluan Lain',
            'tugas' => 'Surat Tugas',
            'undangan' => 'Surat Undangan',
            'keterangan' => 'Surat Keterangan',
            default => ucfirst(str_replace('_', ' ', $this->jenis)),
        };
    }

    public function getUiBadgeClassAttribute(): string
    {
        return match ($this->jenis) {
            'izin_sakit' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
            'izin_non_sakit' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
            'keperluan_lain' => 'bg-orange-500/10 text-orange-600 border-orange-500/20',
            'tugas' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',
            'undangan' => 'bg-purple-500/10 text-purple-600 border-purple-500/20',
            'keterangan' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            default => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
        };
    }

    public function getUiIconAttribute(): string
    {
        return match ($this->jenis) {
            'izin_sakit' => 'heart',
            'izin_non_sakit' => 'document-text',
            'keperluan_lain' => 'briefcase',
            'tugas' => 'clipboard-document-list',
            'undangan' => 'envelope',
            'keterangan' => 'identification',
            default => 'document',
        };
    }

    public function hasTemplate(): bool
    {
        return ! empty($this->template_path);
    }

    public function isIzinSakit(): bool
    {
        return $this->jenis === 'izin_sakit';
    }

    public function isIzinNonSakit(): bool
    {
        return $this->jenis === 'izin_non_sakit';
    }

    public function isTugas(): bool
    {
        return $this->jenis === 'tugas';
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByJenis(Builder $query, string $jenis): Builder
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeWithTemplate(Builder $query): Builder
    {
        return $query->whereNotNull('template_path');
    }
}
