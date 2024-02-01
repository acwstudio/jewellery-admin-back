<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogCategory\Pipes;

use Domain\Blog\Repositories\BlogCategory\BlogCategoryRelationsRepository;

final class BlogCategoryChildrenUpdateRelationshipsPipe
{
    public function __construct(public BlogCategoryRelationsRepository $blogCategoryRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.children');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'children');

            $this->blogCategoryRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
