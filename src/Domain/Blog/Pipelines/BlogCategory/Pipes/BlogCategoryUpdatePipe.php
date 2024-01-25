<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogCategory\Pipes;

use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryRepository;

final class BlogCategoryUpdatePipe
{
    public function __construct(public BlogCategoryRepository $blogCategoryRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->blogCategoryRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
