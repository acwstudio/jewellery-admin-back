<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogPostRepository;

use Domain\AbstractRelationsRepository;
use Domain\Blog\Models\BlogPost;
use Illuminate\Contracts\Pagination\Paginator;

final class BlogPostRelationsRepository extends AbstractRelationsRepository
{
    public function indexRelations(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return BlogPost::findOrFail($id)->{$relation}()->simplePaginate($perPage)->appends(data_get($data, 'params'));
    }

    public function updateRelations(array $data): void
    {
        /**
         * HasOne, HasMany, MorphOne, MorphMany
         * BelongsTo, MorphTo
         * BelongsToMany, MorphedToMany, MorphedByMany
         */

            data_get($data, 'model') ?? data_set($data, 'model', BlogPost::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
