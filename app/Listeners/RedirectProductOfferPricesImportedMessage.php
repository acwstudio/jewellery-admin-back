<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\Sync\ProductOfferPricesImported;
use App\Packages\ModuleClients\AMQPModuleClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class RedirectProductOfferPricesImportedMessage
{
    public function __construct(
        private readonly AMQPModuleClientInterface $AMQPModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handle(ProductOfferPricesImported $event): void
    {
        try {
            $this->AMQPModuleClient->publish('ProductOfferPrices_Redirect', $event->message);
            $this->logger->info('[ImportProductOfferPrices] Successfully redirected message');
        } catch (Throwable $e) {
            $this->logger->alert('[ImportProductOfferPrices] Failed to redirect message', [
                'exception' => $e
            ]);
        }
    }
}
