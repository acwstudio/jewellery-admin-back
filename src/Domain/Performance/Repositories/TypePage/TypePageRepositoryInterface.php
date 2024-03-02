<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\TypePage;

use Domain\Performance\Models\TypePage;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface TypePageRepositoryInterface
{
    public function index(array $data): Paginator;

    public function show(int $id, array $data): Model|TypePage;
}
