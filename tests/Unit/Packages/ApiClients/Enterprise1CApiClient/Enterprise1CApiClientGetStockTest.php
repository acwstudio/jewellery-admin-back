<?php

declare(strict_types=1);

namespace Tests\Unit\Packages\ApiClients\Enterprise1CApiClient;

use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount\ProductData;
use App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount\ProductsGetStockRequestData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductsGetStockResponseData;
use Mockery\MockInterface;
use Tests\TestCase;

class Enterprise1CApiClientGetStockTest extends TestCase
{
    public Enterprise1CApiClientContract $apiClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockEnterprise1CApiClient();
        $this->apiClient = app(Enterprise1CApiClientContract::class);
    }

    public function testSuccessful()
    {
        $response = $this->apiClient->productsGetStock(
            new ProductsGetStockRequestData(
                ProductData::collection([])
            )
        );

        self::assertNotEmpty($response);
        self::assertInstanceOf(ProductsGetStockResponseData::class, $response);
        self::assertTrue($response->result);
    }

    public function mockEnterprise1CApiClient(): void
    {
        $this->mock(Enterprise1CApiClientContract::class, function (MockInterface $mock) {
            $data = json_decode(
                file_get_contents($this->getTestResources('Enterprise1CApiClient/products_get_count.json')),
                true
            );
            $response = ProductsGetStockResponseData::from($data);
            $mock->shouldReceive('productsGetStock')->andReturn($response);
        });
    }
}
