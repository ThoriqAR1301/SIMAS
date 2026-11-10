<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $random_code
 * @property string $nama_surat
 * @property string|null $kategori_folder
 * @property string|null $asal_surat
 * @property Carbon|null $tanggal_diterima
 * @property string|null $deskripsi
 * @property string|null $file_surat
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'random_code',
        'nama_surat',
        'kategori_folder',
        'asal_surat',
        'tanggal_diterima',
        'deskripsi',
        'file_surat',
        'user_id',
    ];

    protected $casts = [
        'tanggal_diterima' => 'date',
    ];

    protected $appends = [
        'tanggal_format',
        'file_url',
        'kategori_ui_color',
        'kategori_icon',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTanggalFormatAttribute(): string
    {
        return $this->tanggal_diterima
            ? $this->tanggal_diterima->translatedFormat('d M Y')
            : '-';
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->hasFile()) {
            /** @var FilesystemAdapter $disk */
            $disk = Storage::disk('public');

            return $disk->url($this->file_surat);
        }

        return null;
    }

    public function getKategoriUiColorAttribute(): string
    {
        $kategori = strtolower($this->kategori_folder ?? '');

        if (str_contains($kategori, 'industri') || str_contains($kategori, 'mitra')) {
            return 'emerald';
        }
        if (str_contains($kategori, 'dinas') || str_contains($kategori, 'pemerintah')) {
            return 'blue';
        }
        if (str_contains($kategori, 'undangan')) {
            return 'amber';
        }
        if (str_contains($kategori, 'keuangan')) {
            return 'rose';
        }

        return 'slate';
    }

    public function getKategoriIconAttribute(): string
    {
        return match ($this->kategori_ui_color) {
            'emerald' => 'briefcase',
            'blue' => 'building-office',
            'amber' => 'envelope',
            'rose' => 'banknotes',
            default => 'folder',
        };
    }

    public function hasFile(): bool
    {
        return ! empty($this->file_surat) && Storage::disk('public')->exists($this->file_surat);
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_surat', 'like', "%{$keyword}%")
                ->orWhere('random_code', 'like', "%{$keyword}%")
                ->orWhere('asal_surat', 'like', "%{$keyword}%")
                ->orWhere('deskripsi', 'like', "%{$keyword}%");
        });
    }

    public function scopeByKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori_folder', $kategori);
    }
}
