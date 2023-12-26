<?php

declare(strict_types=1);

namespace Tests\Unit\Packages\ApiClients\OAuthApiClient;

use App\Packages\ApiClients\OAuth\Contracts\OAuthApiClientContract;
use App\Packages\ApiClients\OAuth\Enums\Yandex\SexEnum;
use App\Packages\ApiClients\OAuth\Responses\Yandex\DefaultPhoneData;
use App\Packages\ApiClients\OAuth\Responses\Yandex\YandexInfoResponseData;
use Mockery\MockInterface;
use Tests\TestCase;

class OAuthApiClientYandexInfoTest extends TestCase
{
    public OAuthApiClientContract $oauthApiClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockOAuthApiClientYandexInfo();
        $this->oauthApiClient = app(OAuthApiClientContract::class);
    }

    public function testSuccessful()
    {
        $result = $this->oauthApiClient->yandexInfo('y0_AgadAdasdssIAAorLgAAAADnigf7KpXywE3SQasdassadB8U');

        self::assertNotEmpty($result);
        self::assertInstanceOf(YandexInfoResponseData::class, $result);
        self::assertInstanceOf(DefaultPhoneData::class, $result->default_phone);
        self::assertInstanceOf(SexEnum::class, $result->sex);
    }

    private function mockOAuthApiClientYandexInfo(): void
    {
        $this->mock(OAuthApiClientContract::class, function (MockInterface $mock) {
            $oauthYandexInfoJson = $this->getTestResources('oauth_yandex_info.json');
            $mock->shouldReceive('yandexInfo')->andReturn(
                YandexInfoResponseData::from(json_decode(file_get_contents($oauthYandexInfoJson)))
            );
        });
    }
}
