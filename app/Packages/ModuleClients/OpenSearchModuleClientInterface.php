<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use Illuminate\Database\Eloquent\Model;

interface OpenSearchModuleClientInterface
{
    public function createIndex(string $indexName): void;
    public function store(Model $model): void;
    public function update(Model $model): void;
    public function delete(Model $model): void;
}
