<?php

declare(strict_types=1);

namespace Tests\Unit\Packages\ApiClients;

use App\Packages\ApiClients\Recaptcha\RecaptchaApiClient;
use App\Packages\ApiClients\Recaptcha\Responses\RecaptchaSiteVerifyResponseData;
use Mockery\MockInterface;
use Tests\TestCase;

class RecaptchaClientTest extends TestCase
{
    private RecaptchaApiClient $recaptchaApiClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockRecaptchaApiClient();
        $this->recaptchaApiClient = app(RecaptchaApiClient::class);
    }

    public function testSuccessfulSiteVerify()
    {
        $result = $this->recaptchaApiClient->siteVerify('asdasdasdasd');

        self::assertInstanceOf(RecaptchaSiteVerifyResponseData::class, $result);
    }

    private function mockRecaptchaApiClient(): void
    {
        $this->mock(RecaptchaApiClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('siteVerify')->andReturn(
                RecaptchaSiteVerifyResponseData::from(['success' => true, 'error-codes' => []])
            );
        });
    }
}
