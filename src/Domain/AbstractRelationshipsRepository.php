<?php

declare(strict_types=1);

namespace Domain;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class AbstractRelationshipsRepository
{
    abstract public function index(array $params): LengthAwarePaginator;
    abstract public function update(array $data): void;
}
