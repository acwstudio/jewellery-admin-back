<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PvzCacheService
{
    public function get(string $city, \Closure $default): Collection
    {
        return Cache::rememberForever($this->getCacheKey($city), $default);
    }

    public function warm(string $city, Collection $pvz): void
    {
        Cache::forever($this->getCacheKey($city), $pvz);
    }

    private function getCacheKey(string $city): string
    {
        return 'pvz_' . Str::slug(Str::ascii($city));
    }
}
