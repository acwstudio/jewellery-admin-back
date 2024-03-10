<?php

namespace Domain\Catalog\Repositories\Size;

use Domain\Catalog\Models\Size;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface SizeRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|Size;
}
