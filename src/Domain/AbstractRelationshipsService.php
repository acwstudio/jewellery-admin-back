<?php

declare(strict_types=1);

namespace Domain;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRelationshipsService
{
    abstract public function index(array $params): LengthAwarePaginator|Model;

    abstract public function update(array $data): void;
}
