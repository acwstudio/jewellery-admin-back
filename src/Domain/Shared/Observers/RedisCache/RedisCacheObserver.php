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
        dd(Cache::get('80e083245786c52b504cea8559a4edf2014addda:3e73a28f84e520d3e929c8f7ce98a0ed5cd4963ce97488005313130b998209c1'));
        Cache::tags([$model::class])->flush();
    }

    public function delete($model): void
    {
        Cache::tags([$model::class])->flush();
    }
}
