<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogCategory\Pipes;

use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryRelationsRepository;

final class BlogCategoryBlogPostsStoreUpdateRelationshipsPipe
{
    public function __construct(public BlogCategoryRelationsRepository $blogCategoryRelationsRepository)
    {
    }

    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.blogPosts');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'blogPosts');

            $this->blogCategoryRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
