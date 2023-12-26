<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\Catalog\Repositories\ProductOfferRepository;
use App\Modules\Catalog\Repositories\ProductOfferStockRepository;
use App\Modules\Catalog\Support\Blueprints\ProductOfferStockBlueprint;

class ProductOfferStockService
{
    public function __construct(
        private readonly ProductOfferStockRepository $productOfferStockRepository,
        private readonly ProductOfferRepository $productOfferRepository
    ) {
    }

    public function createProductOfferStock(
        ProductOfferStockBlueprint $productOfferStockBlueprint,
        ProductOffer|int $productOffer
    ): ProductOfferStock {
        if (is_int($productOffer)) {
            $productOffer = $this->productOfferRepository->getById($productOffer, true);
        }

        return $this->productOfferStockRepository->create($productOfferStockBlueprint, $productOffer);
    }

    public function getProductOfferStockCurrent(ProductOffer|int $productOffer): ?ProductOfferStock
    {
        if (is_int($productOffer)) {
            $productOffer = $this->productOfferRepository->getById($productOffer, true);
        }

        return $this->productOfferStockRepository->getCurrent($productOffer);
    }
}
