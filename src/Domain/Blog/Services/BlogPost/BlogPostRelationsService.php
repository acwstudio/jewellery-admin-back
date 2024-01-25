<?php

declare(strict_types=1);

namespace Domain\Blog\Services\BlogPost;

use Domain\Blog\Repositories\BlogPostRepository\BlogPostRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class BlogPostRelationsService
{
    public function __construct(public BlogPostRelationsRepository $blogPostRelationsRepository)
    {
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->blogPostRelationsRepository->indexRelations($data);
    }

    public function updateRelations(array $data): void
    {
        $this->blogPostRelationsRepository->updateRelations($data);
    }
}
