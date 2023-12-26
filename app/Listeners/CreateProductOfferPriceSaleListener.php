<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\PromotionCreated;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;

class CreateProductOfferPriceSaleListener
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    public function handle(PromotionCreated $event): void
    {
        $this->catalogModuleClient->importProductSaleFromPromotion($event->promotionId);
    }
}
