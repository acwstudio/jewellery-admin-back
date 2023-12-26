<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Exceptions\IsActiveException;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Repositories\ProductOfferPriceRepository;
use App\Modules\Catalog\Repositories\ProductOfferRepository;
use App\Modules\Catalog\Support\Blueprints\ProductOfferPriceBlueprint;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;

class ProductOfferPriceService
{
    public function __construct(
        private readonly ProductOfferPriceRepository $productOfferPriceRepository,
        private readonly ProductOfferRepository $productOfferRepository
    ) {
    }

    public function createProductOfferPrice(
        ProductOfferPriceBlueprint $productOfferPriceBlueprint,
        ProductOffer|int $productOffer
    ): ProductOfferPrice {
        if (is_int($productOffer)) {
            $productOffer = $this->productOfferRepository->getById($productOffer, true);
        }

        return $this->productOfferPriceRepository->create($productOfferPriceBlueprint, $productOffer);
    }

    /**
     * @throws IsActiveException
     */
    public function updateProductOfferPriceIsActive(
        OfferPriceTypeEnum $type,
        bool $isActive,
        ProductOffer|int $productOffer
    ): ProductOfferPrice {
        if ($type == OfferPriceTypeEnum::REGULAR && !$isActive) {
              throw new IsActiveException($type->value);
        }

        if (is_int($productOffer)) {
            $productOffer = $this->productOfferRepository->getById($productOffer, true);
        }

        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = $productOffer->productOfferPrices()
            ->getQuery()
            ->where('is_active', '=', true)
            ->where('type', '=', $type)
            ->firstOrFail();

        $this->productOfferPriceRepository->updateIsActive($productOfferPrice, $isActive);

        return $productOfferPrice->refresh();
    }

    public function updateProductOfferPriceIsActiveByProductIds(
        array $productIds,
        OfferPriceTypeEnum $type,
        bool $isActive,
        ?bool $filterIsActive = null
    ): void {
        $this->productOfferPriceRepository->updateIsActiveByProductIds(
            $productIds,
            $type,
            $isActive,
            $filterIsActive
        );
    }
}
