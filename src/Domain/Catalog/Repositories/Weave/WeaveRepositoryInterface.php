<?php

namespace Domain\Catalog\Repositories\Weave;

use Domain\Catalog\Models\Weave;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface WeaveRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|Weave;
}
