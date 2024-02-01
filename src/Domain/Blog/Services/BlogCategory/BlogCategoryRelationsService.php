<?php

declare(strict_types=1);

namespace Domain\Blog\Services\BlogCategory;

use Domain\Blog\Repositories\BlogCategory\BlogCategoryRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class BlogCategoryRelationsService
{
    public function __construct(public BlogCategoryRelationsRepository $blogCategoryRelationsRepository)
    {
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->blogCategoryRelationsRepository->indexRelations($data);
    }

    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->blogCategoryRelationsRepository->updateRelations($data);
    }
}
