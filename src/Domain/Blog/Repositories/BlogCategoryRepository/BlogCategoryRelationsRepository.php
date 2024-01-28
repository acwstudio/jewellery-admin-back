<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogCategoryRepository;

use Domain\AbstractRelationsRepository;
use Domain\Blog\Models\BlogCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class BlogCategoryRelationsRepository extends AbstractRelationsRepository
{
    public function indexRelations(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return BlogCategory::findOrFail($id)->{$relation}()->simplePaginate($perPage)->appends(data_get($data, 'params'));
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

        data_get($data, 'model') ?? data_set($data, 'model', BlogCategory::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
