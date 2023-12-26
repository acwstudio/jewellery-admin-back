<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\UseCases;

use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Psr\Log\LoggerInterface;

class GetProducts
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(ProductGetListData $data): ProductListData
    {
        $productListData = $this->catalogModuleClient->getProducts($data);

        $this->logger->info('XmlFeed::GetProducts', ['pagination' => $productListData->pagination->all()]);

        return $productListData;
    }
}
