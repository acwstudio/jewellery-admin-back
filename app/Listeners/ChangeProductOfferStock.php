<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\DataObjects\Catalog\ProductOffer\Stock\CreateProductOfferStockData;
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use App\Packages\Events\ProductOfferReservationStatusChanged;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class ChangeProductOfferStock
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    public function handle(ProductOfferReservationStatusChanged $event)
    {
        if (
            $event->getNewStatus() === $event->getOldStatus()
            || $event->getNewStatus() !== OfferReservationStatusEnum::PURCHASED
        ) {
            return;
        }

        $productOffer = $this->catalogModuleClient->getProductOffer(
            $event->getProductOfferId()
        );

        $newCount = $productOffer->count - $event->getReservationCount();

        $data = new CreateProductOfferStockData(
            $event->getProductOfferId(),
            $newCount
        );

        $this->catalogModuleClient->createProductOfferStock($data, OfferStockReasonEnum::RESERVATION);
    }
}
