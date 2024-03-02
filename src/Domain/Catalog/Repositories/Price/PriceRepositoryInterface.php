<?php

namespace Domain\Catalog\Repositories\Price;

use Domain\Catalog\Models\Price;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface PriceRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|Price;
}
