<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogCategory\Pipes;

use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryRelationsRepository;

final class BlogCategoriesParentUpdateRelationshipsPipe
{
    public function __construct(public BlogCategoryRelationsRepository $blogCategoryRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.parent');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'parent');

            $this->blogCategoryRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
