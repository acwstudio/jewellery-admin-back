<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogPost\Pipes;

use Domain\Blog\Repositories\BlogPost\BlogPostRepository;

final class BlogPostUpdatePipe
{
    public function __construct(public BlogPostRepository $blogPostRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->blogPostRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
