<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Repositories\ProductOfferRepository;
use App\Modules\Catalog\Repositories\ProductRepository;
use App\Modules\Catalog\Support\Blueprints\ProductOfferBlueprint;
use App\Packages\Events\ProductOfferCreated;

class ProductOfferService
{
    public function __construct(
        private readonly ProductOfferRepository $productOfferRepository,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function getProductOffer(int $id): ?ProductOffer
    {
        return $this->productOfferRepository->getById($id);
    }

    public function createProductOffer(
        ProductOfferBlueprint $productOfferBlueprint,
        Product|int $product
    ): ProductOffer {
        if (is_int($product)) {
            $product = $this->productRepository->getById($product, true);
        }

        $productOffer = $this->productOfferRepository->create($productOfferBlueprint, $product);

        ProductOfferCreated::dispatch($productOffer->getKey());

        return $productOffer;
    }

    public function deleteProductOffer(int $id): void
    {
        $productOffer = $this->productOfferRepository->getById($id, true);
        $this->productOfferRepository->delete($productOffer);
    }
}
