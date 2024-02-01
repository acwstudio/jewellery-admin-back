<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogPost\Pipes;

use Domain\Blog\Repositories\BlogPost\BlogPostRepository;

final class BlogPostDestroyPipe
{
    public function __construct(public BlogPostRepository $blogPostRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->blogPostRepository->destroy($id);

        return $next($id);
    }
}
