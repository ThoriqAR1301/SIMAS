<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $role_id
 * @property bool $is_active
 * @property string|null $nip
 * @property string|null $jabatan
 * @property Carbon|null $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Role|null $role
 */
#[Fillable(['name', 'email', 'password', 'role_id', 'is_active', 'nip', 'jabatan'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $appends = [
        'initial',
        'role_badge_class',
        'status_label',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role?->name) {
            'admin_tu' => 'bg-blue-500/10 text-blue-600 border-blue-500/20',
            'pimpinan' => 'bg-purple-500/10 text-purple-600 border-purple-500/20',
            'guru' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            default => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    public function isGuru(): bool
    {
        $this->loadMissing('role');

        return $this->role?->name === 'guru';
    }

    public function isAdminTu(): bool
    {
        $this->loadMissing('role');

        return $this->role?->name === 'admin_tu';
    }

    public function isPimpinan(): bool
    {
        $this->loadMissing('role');

        return $this->role?->name === 'pimpinan';
    }

    public function hasRole(string $role): bool
    {
        $this->loadMissing('role');

        return $this->role?->name === $role;
    }

    public function hasPermission(string $permission): bool
    {
        $this->loadMissing('role.permissions');

        return $this->role?->hasPermission($permission) ?? false;
    }

    public function hasAnyPermission(array $permissions): bool
    {
        $this->loadMissing('role.permissions');

        return $this->role?->hasAnyPermission($permissions) ?? false;
    }

    public function isAktif(): bool
    {
        return $this->is_active === true;
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRole(Builder $query, string $roleName): Builder
    {
        return $query->whereHas('role', fn ($q) => $q->where('name', $roleName));
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('nip', 'like', "%{$keyword}%")
                ->orWhere('jabatan', 'like', "%{$keyword}%");
        });
    }
}
