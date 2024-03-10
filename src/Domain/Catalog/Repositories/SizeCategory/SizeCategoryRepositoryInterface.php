<?php

namespace Domain\Catalog\Repositories\SizeCategory;

use Domain\Catalog\Models\SizeCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface SizeCategoryRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|SizeCategory;
}
