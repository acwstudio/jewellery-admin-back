<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\Sync\CollectionsImported;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class AddProductFeatureCollectionListener
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    public function handle(CollectionsImported $event): void
    {
        $this->catalogModuleClient->addProductFeatureCollection($event->collectionId);
    }
}
