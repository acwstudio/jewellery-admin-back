<?php

declare(strict_types=1);

namespace Domain\Blog\Services\BlogPost;

use Domain\AbstractCRUDService;
use Domain\Blog\Repositories\BlogPostRepository\BlogPostRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class BlogPostService extends AbstractCRUDService
{
    public function __construct(
        public BlogPostRepositoryInterface $blogPostRepositoryInterface,
//        public BlogPostPipeline $blogPostPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->blogPostRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        // TODO: Implement store() method.
    }

    public function show(int $id, array $data): Model
    {
        return $this->blogPostRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id): void
    {
        // TODO: Implement destroy() method.
    }
}
