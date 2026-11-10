<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 */
class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = false;

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = Cache::rememberForever("pengaturan.{$key}", function () use ($key) {
            return static::query()->where('key', $key)->value('value');
        });

        if ($value === null) {
            return $default;
        }

        return match (strtolower(trim((string) $value))) {
            'true', '1' => true,
            'false', '0' => false,
            'null' => null,
            default => is_numeric($value)
                            ? (str_contains((string) $value, '.') ? (float) $value : (int) $value)
                            : $value,
        };
    }

    public static function set(string $key, mixed $value): void
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("pengaturan.{$key}");
    }

    public static function remove(string $key): void
    {
        static::query()->where('key', $key)->delete();
        Cache::forget("pengaturan.{$key}");
    }
}
