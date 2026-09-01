<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Agency-wide key/value settings (see the Super Admin → Settings screen
 * and the "new-tenant defaults" toggles). Read through the cache; any
 * write clears it.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private const CACHE_KEY = 'settings.map';

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /** @return array<string, string|null> */
    public static function map(): array
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            fn () => static::query()->pluck('value', 'key')->all()
        );
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::map()[$key] ?? $default;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::get($key);

        return $value === null ? $default : filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }
}
