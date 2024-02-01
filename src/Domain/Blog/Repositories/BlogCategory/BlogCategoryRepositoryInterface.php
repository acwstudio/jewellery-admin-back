<?php

namespace Domain\Blog\Repositories\BlogCategory;

use Domain\Blog\Models\BlogCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface BlogCategoryRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|BlogCategory;
}
