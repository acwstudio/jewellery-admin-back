<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogPostRepository;

use Domain\AbstractRelationsRepository;
use Domain\Blog\Models\BlogPost;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class BlogPostRelationsRepository extends AbstractRelationsRepository
{
    public function indexRelations(array $data): Paginator|Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');
//        dd(BlogPost::findOrFail($id)->{$relation});
        return BlogPost::findOrFail($id)->{$relation};
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
