<?php

namespace Packages\ApiClients;

use App\Packages\ApiClients\DaData\Contracts\DaDataApiClientContract;
use App\Packages\ApiClients\DaData\Responses\DataObjects\AddressData;
use App\Packages\ApiClients\DaData\Responses\DataObjects\SuggestAddressData;
use App\Packages\ApiClients\DaData\Responses\SuggestAddressResponseData;
use Mockery\MockInterface;
use Tests\TestCase;

class DaDataClientTest extends TestCase
{
    private DaDataApiClientContract $daDataApiClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockDaDataApiClient();
        $this->daDataApiClient = app(DaDataApiClientContract::class);
    }

    public function testSuccessfulGetSuggestAddress()
    {
        $result = $this->daDataApiClient->getSuggestAddress('Москва, ул Хабаровская, 2');

        self::assertNotEmpty($result);
        self::assertInstanceOf(SuggestAddressResponseData::class, $result);
        self::assertInstanceOf(SuggestAddressData::class, $result->suggestions->first());
        self::assertInstanceOf(AddressData::class, $result->suggestions->first()->data);
    }

    private function mockDaDataApiClient(): void
    {
        $this->mock(DaDataApiClientContract::class, function (MockInterface $mock) {
            $suggestAddressJson = $this->getTestResources('dadata_suggest_address.json');
            $mock->shouldReceive('getSuggestAddress')->andReturn(
                SuggestAddressResponseData::from(json_decode(file_get_contents($suggestAddressJson)))
            );
        });
    }
}
