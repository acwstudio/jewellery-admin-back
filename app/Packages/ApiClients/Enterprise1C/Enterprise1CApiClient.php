<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Enterprise1C;

use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount\ProductsGetStockRequestData;
use App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost\DeliveryGetCostResponseData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductsGetStockResponseData;
use Illuminate\Support\Facades\Http;

class Enterprise1CApiClient implements Enterprise1CApiClientContract
{
    public function deliveryGetCost(string $fias, int $zipCode): DeliveryGetCostResponseData
    {
        $body = [
            'fias' => $fias,
            'zipCode' => $zipCode,
        ];

        $response = Http::enterprise1C()->post(
            '/hs/delivery/getCost',
            $body
        );

        return DeliveryGetCostResponseData::from($response->json());
    }

    public function productsGetStock(ProductsGetStockRequestData $requestData): ProductsGetStockResponseData
    {
        $body = $requestData->products->all();

        $response = Http::enterprise1C()->post(
            "/hs/products/getCount/a/b",
            $body
        );

        $data = $response->json();

        if (empty($data['Products'])) {
            $data['Products'] = [];
        }

        return ProductsGetStockResponseData::from($data);
    }
}
