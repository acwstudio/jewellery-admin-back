<?php

namespace App\Modules\Catalog\Support\DataProvider\Monolith;

use App\Modules\Catalog\Support\DataProvider\DataProviderInterface;
use App\Packages\ModuleClients\MonolithModuleClientInterface;

class CategoryDataProvider implements DataProviderInterface
{
    public function __construct(
        protected MonolithModuleClientInterface $monolithModuleClient
    ) {
    }

    public function getRawData(): iterable
    {
        return $this->monolithModuleClient->getCategories();
    }
}
