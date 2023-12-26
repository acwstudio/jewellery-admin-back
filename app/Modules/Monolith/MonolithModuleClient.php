<?php

declare(strict_types=1);

namespace App\Modules\Monolith;

use App\Modules\Monolith\Services\ApiService;
use App\Modules\Monolith\Services\CategoryService;
use App\Packages\ModuleClients\MonolithModuleClientInterface;

final class MonolithModuleClient implements MonolithModuleClientInterface
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly ApiService $apiService
    ) {
    }

    public function getCategories(): iterable
    {
        return $this->categoryService->getCategories();
    }

    public function getProductFilters(array $skuArray = []): iterable
    {
        return $this->apiService->getProductFilters($skuArray);
    }

    public function getCollectionProducts(string $name): iterable
    {
        return $this->apiService->getCollectionProducts($name);
    }

    public function getUsers(): iterable
    {
        return $this->apiService->getUsers();
    }
}
