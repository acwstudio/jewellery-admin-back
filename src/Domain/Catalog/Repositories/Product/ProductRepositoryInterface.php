<?php

namespace Domain\Catalog\Repositories\Product;

use Domain\Catalog\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|Product;
}
