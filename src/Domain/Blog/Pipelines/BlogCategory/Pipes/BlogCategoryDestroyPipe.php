<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogCategory\Pipes;

use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryRepository;

final class BlogCategoryDestroyPipe
{
    public function __construct(public BlogCategoryRepository $blogCategoryRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->blogCategoryRepository->destroy($id);

        return $next($id);
    }
}
