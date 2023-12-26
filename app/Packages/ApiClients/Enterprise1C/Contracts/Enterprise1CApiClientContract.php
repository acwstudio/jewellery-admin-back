<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Enterprise1C\Contracts;

use App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount\ProductsGetStockRequestData;
use App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost\DeliveryGetCostResponseData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductsGetStockResponseData;

interface Enterprise1CApiClientContract
{
    public function deliveryGetCost(string $fias, int $zipCode): DeliveryGetCostResponseData;
    public function productsGetStock(ProductsGetStockRequestData $requestData): ProductsGetStockResponseData;
}
