<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $deskripsi
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'deskripsi',
    ];

    protected $appends = [
        'group_name',
        'group_label',
        'ui_icon',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    public function getGroupNameAttribute(): string
    {
        $parts = explode('.', $this->name);

        return count($parts) > 1 ? $parts[0] : 'umum';
    }

    public function getGroupLabelAttribute(): string
    {
        return match ($this->group_name) {
            'pengajuan' => 'Modul Pengajuan',
            'surat_izin' => 'Modul Surat Izin',
            'surat_masuk' => 'Modul Surat Masuk',
            'disposisi' => 'Modul Disposisi',
            'folder' => 'Modul Folder',
            'master' => 'Data Master',
            'laporan' => 'Laporan & Rekap',
            'user' => 'Manajemen Pengguna',
            default => ucfirst($this->group_name),
        };
    }

    public function getUiIconAttribute(): string
    {
        return match ($this->group_name) {
            'pengajuan' => 'document-text',
            'surat_izin' => 'calendar',
            'surat_masuk' => 'inbox-arrow-down',
            'disposisi' => 'arrow-right-circle',
            'folder' => 'folder',
            'master' => 'cog',
            'laporan' => 'chart-bar',
            'user' => 'users',
            default => 'key',
        };
    }

    public function isGroup(string $group): bool
    {
        return $this->group_name === $group;
    }

    public function isCreate(): bool
    {
        return str_ends_with($this->name, '.create');
    }

    public function isRead(): bool
    {
        return str_ends_with($this->name, '.read');
    }

    public function scopeByGroup(Builder $query, string $group): Builder
    {
        return $query->where('name', 'like', "{$group}.%");
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('display_name', 'like', "%{$keyword}%");
        });
    }
}
