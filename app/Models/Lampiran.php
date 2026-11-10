<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $attachable_type
 * @property int $attachable_id
 * @property string $nama_file
 * @property string $path
 * @property string $mime_type
 * @property int $ukuran
 */
class Lampiran extends Model
{
    protected $table = 'lampiran';

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'nama_file',
        'path',
        'mime_type',
        'ukuran',
    ];

    protected $appends = [
        'url',
        'ukuran_format',
        'extension',
        'is_image',
        'is_pdf',
        'icon_name',
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function url(): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->path);
    }

    public function ukuranFormat(): string
    {
        $bytes = $this->ukuran;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function getUrlAttribute(): string
    {
        return $this->url();
    }

    public function getUkuranFormatAttribute(): string
    {
        return $this->ukuranFormat();
    }

    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->path, PATHINFO_EXTENSION));
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/')
            || in_array($this->extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    public function getIsImageAttribute(): bool
    {
        return $this->isImage();
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf' || $this->extension === 'pdf';
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->isPdf();
    }

    public function getIconNameAttribute(): string
    {
        if ($this->isImage()) {
            return 'photo';
        }
        if ($this->isPdf()) {
            return 'document-text';
        }

        return 'document';
    }

    public function scopeImages(Builder $query): Builder
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopePdfs(Builder $query): Builder
    {
        return $query->where('mime_type', 'application/pdf');
    }
}
