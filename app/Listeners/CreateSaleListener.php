<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\PromotionCreated;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;

class CreateSaleListener
{
    public function __construct(
        private readonly PromotionsModuleClientInterface $promotionsModuleClient
    ) {
    }

    public function handle(PromotionCreated $event): void
    {
        $this->promotionsModuleClient->importSale($event->promotionId);
    }
}
