<?php

declare(strict_types=1);

namespace Domain\Blog\Services\BlogCategory;

use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryRelationsRepository;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class BlogCategoryRelationsService
{
    public function __construct(public BlogCategoryRelationsRepository $blogCategoryRelationsRepository)
    {
    }

    public function indexRelations(array $data): HasMany
    {
        return $this->blogCategoryRelationsRepository->indexRelations($data);
    }

    public function updateRelations(array $data): void
    {
        $this->blogCategoryRelationsRepository->updateRelations($data);
    }
}
