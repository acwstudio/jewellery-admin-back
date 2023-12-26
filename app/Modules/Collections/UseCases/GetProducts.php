<?php

declare(strict_types=1);

namespace App\Modules\Collections\UseCases;

use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class GetProducts
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    public function __invoke(ProductGetListData $data): ProductListData
    {
        return $this->catalogModuleClient->getProducts($data);
    }
}
