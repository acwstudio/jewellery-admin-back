<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Repositories\ProductScoutRepository;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use OpenSearch\ScoutDriverPlus\Paginator;

class ProductScoutService
{
    public function __construct(
        private readonly ProductScoutRepository $productRepository,
    ) {
    }

    public function getProduct(int $id): array
    {
        return $this->productRepository->getById($id);
    }

    public function getProducts(ProductGetListData $data): Paginator
    {
        return $this->productRepository->getList($data);
    }
}
