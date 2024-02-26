<?php

declare(strict_types=1);

namespace Domain\Shared\Observers\RedisCache;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use ReflectionMethod;

final class RedisCacheObserver
{
    public function __construct()
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function saved(Model $model): void
    {
        $tags = $this->defineTags($model);
        Cache::tags($tags)->flush();
    }

    /**
     * @throws \ReflectionException
     */
    public function deleted(Model $model): void
    {
        $tags = $this->defineTags($model);
        Cache::tags($tags)->flush();
    }

    /**
     * @throws \ReflectionException
     */
    private function defineTags(Model $model): array
    {
        $modelName = $model::class;
        $reflector = new ReflectionClass($modelName);
        $relations = [];
        $tags = [];
        foreach ($reflector->getMethods() as $reflectionMethod) {
            $returnType = $reflectionMethod->getReturnType();
            if ($returnType) {
                if (in_array(class_basename($returnType->getName()), [
                    'HasOne', 'HasMany', 'BelongsTo', 'BelongsToMany', 'MorphToMany', 'MorphTo'
                ])) {
                    $relations[] = $reflectionMethod;
                }
            }
        }
        $tags[] = $modelName;
        foreach ($relations as $relation) {
            $relModel = $model->{$relation->name}();
            $tags[] = get_class($relModel->getRelated());
        }

        return $tags;
    }
}
