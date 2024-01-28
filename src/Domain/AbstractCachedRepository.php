<?php

declare(strict_types=1);

namespace Domain;

use phpDocumentor\Reflection\Types\Self_;

abstract class AbstractCachedRepository
{
    protected const CACHE_TTL_SECONDS = 2000;

    protected function getCacheKey(array $params): string
    {
        $type = self::class;

        $query = $type . json_encode($params);

        return hash('sha256', $query);
    }

    protected function getTtl(): int
    {
        return self::CACHE_TTL_SECONDS;
    }

    protected function getShortClassName(): bool|string
    {
        $explodedClassName = explode('\\', static::class);

        return end($explodedClassName);
    }
}
