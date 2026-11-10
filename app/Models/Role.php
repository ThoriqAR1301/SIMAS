<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $deskripsi
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'deskripsi',
    ];

    protected $appends = [
        'ui_badge_class',
        'ui_icon',
        'user_count',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function getUiBadgeClassAttribute(): string
    {
        return match ($this->name) {
            'admin_tu' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',
            'pimpinan' => 'bg-purple-500/10 text-purple-600 border-purple-500/20',
            'guru' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            default => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
        };
    }

    public function getUiIconAttribute(): string
    {
        return match ($this->name) {
            'admin_tu' => 'briefcase',
            'pimpinan' => 'star',
            'guru' => 'academic-cap',
            default => 'user-circle',
        };
    }

    public function getUserCountAttribute(): int
    {
        return $this->users()->count();
    }

    public function hasPermission(string $name): bool
    {
        $this->loadMissing('permissions');

        return $this->permissions->contains('name', $name);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        $this->loadMissing('permissions');

        return $this->permissions->whereIn('name', $permissions)->isNotEmpty();
    }

    public function isAdminTu(): bool
    {
        return $this->name === 'admin_tu';
    }

    public function isPimpinan(): bool
    {
        return $this->name === 'pimpinan';
    }

    public function isGuru(): bool
    {
        return $this->name === 'guru';
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('display_name', 'like', "%{$keyword}%");
        });
    }
}
