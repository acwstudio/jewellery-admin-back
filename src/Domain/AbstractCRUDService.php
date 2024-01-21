<?php

declare(strict_types=1);

namespace Domain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\Paginator;

abstract class AbstractCRUDService
{
    abstract public function index(array $data): Paginator;

    abstract public function store(array $data): Model;

    abstract public function show(int $id, array $data): Model;

    abstract public function update(array $data): void;

    abstract public function destroy(int $id): void;
}
