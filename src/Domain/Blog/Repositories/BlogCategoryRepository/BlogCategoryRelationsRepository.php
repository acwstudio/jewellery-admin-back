<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogCategoryRepository;

use Domain\AbstractRelationsRepository;
use Domain\Blog\Models\BlogCategory;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class BlogCategoryRelationsRepository extends AbstractRelationsRepository
{
    public function indexRelations(array $data): HasMany
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return BlogCategory::findOrFail($id)->{$relation}();
    }

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
