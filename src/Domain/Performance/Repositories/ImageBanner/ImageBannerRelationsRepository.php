<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\ImageBanner;

use Domain\AbstractRelationsRepository;
use Domain\Performance\Models\ImageBanner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class ImageBannerRelationsRepository extends AbstractRelationsRepository
{
    public function indexRelations(array $data): Paginator|Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        if (in_array(ImageBanner::findOrFail($id)->{$relation}()::class, config('api-settings.to-one'))) {
            return ImageBanner::findOrFail($id)->{$relation}()->firstOrFail();
        }
        return ImageBanner::findOrFail($id)->{$relation}()->paginate($perPage)->appends(data_get($data, 'params'));
    }

    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        /**
         * HasOne, HasMany, MorphOne, MorphMany
         * BelongsTo, MorphTo
         * BelongsToMany, MorphedToMany, MorphedByMany
         */

            data_get($data, 'model') ?? data_set($data, 'model', ImageBanner::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
