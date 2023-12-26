<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferReservation;
use App\Modules\Catalog\Repositories\ProductOfferRepository;
use App\Modules\Catalog\Repositories\ProductOfferReservationRepository;
use App\Modules\Catalog\Support\Blueprints\ProductOfferReservationBlueprint;
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use App\Packages\Events\ProductOfferReservationStatusChanged;

class ProductOfferReservationService
{
    public function __construct(
        private readonly ProductOfferReservationRepository $productOfferReservationRepository,
        private readonly ProductOfferRepository $productOfferRepository
    ) {
    }

    public function createProductOfferReservation(
        ProductOfferReservationBlueprint $productOfferReservationBlueprint,
        ProductOffer|int $productOffer
    ): ProductOfferReservation {
        if (is_int($productOffer)) {
            $productOffer = $this->productOfferRepository->getById($productOffer, true);
        }

        return $this->productOfferReservationRepository->create($productOfferReservationBlueprint, $productOffer);
    }

    public function changeProductOfferReservationStatus(
        ProductOfferReservation|int $productOfferReservation,
        OfferReservationStatusEnum $status
    ): ProductOfferReservation {
        if (is_int($productOfferReservation)) {
            $productOfferReservation = $this->productOfferReservationRepository->getById(
                $productOfferReservation,
                true
            );
        }

        $oldStatus = $productOfferReservation->status;
        $newStatus = $status;

        if ($oldStatus === $newStatus) {
            return $productOfferReservation;
        }

        $this->productOfferReservationRepository->changeStatus(
            $productOfferReservation,
            $newStatus
        );

        ProductOfferReservationStatusChanged::dispatch(
            $productOfferReservation->product_offer_id,
            $productOfferReservation->count,
            $oldStatus,
            $newStatus
        );

        return $productOfferReservation->refresh();
    }

    public function getProductOfferStockAvailable(ProductOffer|int $productOffer): int
    {
        if (is_int($productOffer)) {
            $productOffer = $this->productOfferRepository->getById($productOffer, true);
        }

        return $this->productOfferReservationRepository->getProductOfferStockAvailable($productOffer);
    }
}
