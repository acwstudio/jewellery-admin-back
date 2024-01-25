<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogCategory\Pipes;

use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryRepository;

final class BlogCategoryStorePipe
{
    public function __construct(public BlogCategoryRepository $blogCategoryRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->blogCategoryRepository->store($data);
        data_set($data, 'model', $model);
        data_set($data, 'id', $model->id);

        return $next($data);
    }
}
