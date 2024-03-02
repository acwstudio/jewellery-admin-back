<?php

namespace Domain\Catalog\Repositories\PriceCategory;

use Domain\Catalog\Models\PriceCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface PriceCategoryRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|PriceCategory;
}
