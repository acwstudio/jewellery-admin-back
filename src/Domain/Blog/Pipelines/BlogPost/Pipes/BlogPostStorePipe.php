<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogPost\Pipes;

use Domain\Blog\Repositories\BlogPost\BlogPostRepository;

final class BlogPostStorePipe
{
    public function __construct(public BlogPostRepository $blogPostRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->blogPostRepository->store($data);
        data_set($data, 'model', $model);
        data_set($data, 'id', $model->id);

        return $next($data);
    }
}
