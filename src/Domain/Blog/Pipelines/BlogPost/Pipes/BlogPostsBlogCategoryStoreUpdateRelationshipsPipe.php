<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogPost\Pipes;

use Domain\Blog\Repositories\BlogPostRepository\BlogPostRelationsRepository;

final class BlogPostsBlogCategoryStoreUpdateRelationshipsPipe
{
    public function __construct(public BlogPostRelationsRepository $blogPostRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.blogCategory');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'blogCategory');

            $this->blogPostRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
