<?php

declare(strict_types=1);

namespace Domain\Shared\Observers\RedisCache;

trait RedisCacheable
{
    public static function bootRedisCacheable(): void
    {
        if (config('services.cache.enabled')) {
            static::observe(RedisCacheObserver::class);
        }
    }

    public function cache()
    {

    }
}
