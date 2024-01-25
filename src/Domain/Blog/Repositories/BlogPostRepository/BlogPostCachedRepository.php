<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogPostRepository;

use Domain\AbstractCachedRepository;
use Domain\Blog\Models\BlogPost;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class BlogPostCachedRepository extends AbstractCachedRepository implements BlogPostRepositoryInterface
{
    public function __construct(
        public BlogPostRepositoryInterface $blogPostRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->blogPostRepositoryInterface->index($data);
            });
    }

    public function show(int $id, array $data): Model|BlogPost
    {
        return Cache::remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->blogPostRepositoryInterface->show($id, $data);
            });
    }
}
