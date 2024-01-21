<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogCategoryRepository;

use Domain\AbstractCachedRepository;
use Domain\Blog\Models\BlogCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class BlogCategoryCachedRepository extends AbstractCachedRepository implements BlogCategoryRepositoryInterface
{
    public function __construct(
        public BlogCategoryRepositoryInterface $blogCategoryRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->blogCategoryRepositoryInterface->index($data);
        });
    }

    public function show(int $id, array $data): Model|BlogCategory
    {
        return Cache::remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->blogCategoryRepositoryInterface->show($id, $data);
            });
    }
}
