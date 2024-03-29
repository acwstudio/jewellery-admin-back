<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogCategory;

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
        return Cache::tags([BlogCategory::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->blogCategoryRepositoryInterface->index($data);
        });
    }

    public function show(int $id, array $data): Model|BlogCategory
    {
        return Cache::tags([BlogCategory::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->blogCategoryRepositoryInterface->show($id, $data);
            });
    }
}
