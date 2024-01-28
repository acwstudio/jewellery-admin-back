<?php

declare(strict_types=1);

namespace Domain\Shared\Observers\RedisCache;

use Illuminate\Support\Facades\Cache;

final class RedisCacheObserver
{
    public function __construct()
    {
    }

    public function saved($model): void
    {
        Cache::tags([$model::class])->flush();
    }

    public function delete($model): void
    {
        Cache::tags([$model::class])->flush();
    }
}
