<?php

namespace Domain\Catalog\Repositories\ProductCategory;

use Domain\Catalog\Models\ProductCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface ProductCategoryRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|ProductCategory;
}
