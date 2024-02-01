<?php

declare(strict_types=1);

namespace Domain\Blog\Services\BlogCategory;

use Domain\AbstractCRUDService;
use Domain\Blog\Models\BlogCategory;
use Domain\Blog\Repositories\BlogCategory\BlogCategoryRepositoryInterface;
use Domain\Blog\Pipelines\BlogCategory\BlogCategoryPipeline;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class BlogCategoryService extends AbstractCRUDService
{
    public function __construct(
        public BlogCategoryRepositoryInterface $blogCategoryRepositoryInterface,
        public BlogCategoryPipeline $blogCategoryPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->blogCategoryRepositoryInterface->index($data);
    }

    /**
     * @throws \Throwable
     */
    public function store(array $data): Model
    {
        return $this->blogCategoryPipeline->store($data);
    }

    public function show(int $id, array $data): Model|BlogCategory
    {
        return $this->blogCategoryRepositoryInterface->show($id, $data);
    }

    /**
     * @throws \Throwable
     */
    public function update(array $data): void
    {
        $this->blogCategoryPipeline->update($data);
    }

    /**
     * @throws \Throwable
     */
    public function destroy(int $id): void
    {
        $this->blogCategoryPipeline->destroy($id);
    }
}
