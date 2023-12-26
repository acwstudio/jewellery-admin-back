<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

interface MonolithModuleClientInterface
{
    public function getCategories(): iterable;

    public function getProductFilters(array $skuArray = []): iterable;

    public function getCollectionProducts(string $name): iterable;

    public function getUsers(): iterable;
}
