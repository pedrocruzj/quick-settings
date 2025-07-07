<?php

namespace Petros\QuickSettings;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Petros\QuickSettings\ValueParser;

class QuickSettings
{
    private ValueParser $parser;

    public function __construct(
        private readonly bool $cacheEnabled = true,
        private readonly int $cacheDurationInMinutes = 60
    ) {
        $this->parser = new ValueParser;
    }

    /**
     * Get cache key with namespace of proejct (quick_settings)
     *
     * @param string|integer|float $key
     *
     * @return string
     */
    private function getCacheKeyWithNamespace(string|int|float $key)
    {
        return "quick_settings_$key";
    }

    /**
     * Verify if key exists
     */
    public function exists($key)
    {
        $response = null;

        $check = fn() => DB::table('quick_settings')->where('key', $key)->exists();

        if ($this->cacheEnabled) {
            return Cache::remember(
                $this->getCacheKeyWithNamespace($key),
                $this->cacheDurationInMinutes,
                fn() => $check()
            );
        } else {
            $response = $check();
        }

        return $response;
    }

    /**
     * Retrieve de value. If it does not exist, the value defined in the $default parameter is returned.
     *
     * @param string|int|float $key
     * @param mixed $default
     *
     * @return string|null
     */
    public function get(string|int|float $key, $default = null): string|null
    {
        $response = null;

        $fetch = function () use ($key, $default) {
            return DB::table('quick_settings')
                ->where('key', $this->parser->parseKey($key))
                ->value('value')
                ?? $default;
        };

        if ($this->cacheEnabled) {
            return Cache::remember(
                $this->getCacheKeyWithNamespace($key),
                $this->cacheDurationInMinutes,
                fn() => $fetch()
            );
        } else {
            $response = $fetch();
        }

        return $response;
    }

    /**
     * Creates a new key-value pair in the database. If a  record  with the passed key already exists,
     * the value is replaced with the new value.
     *
     * Arrays and objects are  automatically  transformed into json. Numeric  and boolean  values ​​are
     * converted to string
     *
     * @param string|int|float $key
     * @param mixed $value
     *
     * @return QuickSettings
     */
    public function set(string|int|float $key, $value): QuickSettings
    {
        $now = now();

        if ($this->exists($key)) {
            DB::table('quick_settings')->where('key', $key)->update(
                [
                    'value' => $this->parser->parse($value),
                    'updated_at' => $now,
                ],
            );
        } else {
            DB::table('quick_settings')->insert(
                [
                    'key' => (string) $key,
                    'value' => $this->parser->parse($value),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }

        $this->forgetCacheIfEnabled($key);

        return $this;
    }

    /**
     * Clear cache for the key if cache mode is active.
     *
     * @param string|int|float $key
     *
     * @return void
     */
    private function forgetCacheIfEnabled(string|int|float $key): void
    {
        if ($this->cacheEnabled) {
            Cache::forget($this->getCacheKeyWithNamespace($key));
        }
    }
}
