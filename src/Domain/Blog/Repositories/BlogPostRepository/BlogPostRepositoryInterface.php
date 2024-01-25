<?php

namespace Domain\Blog\Repositories\BlogPostRepository;

use Domain\Blog\Models\BlogPost;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface BlogPostRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|BlogPost;
}
