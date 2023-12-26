<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\Auth;

use App\Modules\Users\Models\User;
use App\Packages\ApiClients\OAuth\Contracts\OAuthApiClientContract;
use App\Packages\ApiClients\OAuth\Enums\OAuthTypeEnum;
use App\Packages\ApiClients\OAuth\Responses\Yandex\YandexInfoResponseData;
use App\Packages\Support\PhoneNumber;
use Laravel\Sanctum\Sanctum;
use libphonenumber\PhoneNumberUtil;
use Mockery\MockInterface;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class AuthControllerOAuthTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/auth/oauth';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser();
    }

    public function testSuccessful()
    {
        $this->mockOAuthApiClientYandexInfo(['default_phone' => null, 'default_email' => 'test@yandex.ru']);
        $this->user->update(['email' => 'test@yandex.ru']);

        $response = $this->post(self::METHOD, [
            'token' => 'yandexToken',
            'type' => OAuthTypeEnum::YANDEX->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('auth', $content);
        self::assertIsArray($content['auth']);
        self::assertNotEmpty($content['auth']);
        self::assertArrayHasKey('access_token', $content['auth']);
    }

    public function testSuccessfulByPhone()
    {
        $this->mockOAuthApiClientYandexInfo([
            'default_phone' => [
                'number' => '+79037659418'
            ]
        ]);

        $phone = PhoneNumberUtil::getInstance()->parse(
            '+79037659418',
            'RU',
            new PhoneNumber()
        );

        $this->user->update(['phone' => $phone]);

        $response = $this->post(self::METHOD, [
            'token' => 'yandexToken',
            'type' => OAuthTypeEnum::YANDEX->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('auth', $content);
        self::assertIsArray($content['auth']);
        self::assertNotEmpty($content['auth']);
        self::assertArrayHasKey('access_token', $content['auth']);
    }

    public function testSuccessfulRegisterByPhone()
    {
        $this->mockOAuthApiClientYandexInfo([
            'default_phone' => [
                'number' => '+79037659418'
            ]
        ]);

        $phone = PhoneNumberUtil::getInstance()->parse(
            '+79099998888',
            'RU',
            new PhoneNumber()
        );

        $this->user->update(['phone' => $phone]);

        $response = $this->post(self::METHOD, [
            'token' => 'yandexToken',
            'type' => OAuthTypeEnum::YANDEX->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('auth', $content);
        self::assertIsArray($content['auth']);
        self::assertNotEmpty($content['auth']);
        self::assertArrayHasKey('access_token', $content['auth']);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureNotFoundByEmail()
    {
        $this->mockOAuthApiClientYandexInfo(['default_phone' => null, 'default_email' => 'notfound@yandex.ru']);

        $user1 = $this->getUser();
        $user2 = $this->getUser();

        $response = $this->post(self::METHOD, [
            'token' => 'yandexToken',
            'type' => OAuthTypeEnum::YANDEX->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAuth()
    {
        Sanctum::actingAs($this->user);

        $response = $this->post(self::METHOD, [
            'token' => 'yandexToken',
            'type' => OAuthTypeEnum::YANDEX->value
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function mockOAuthApiClientYandexInfo(array $replacements = []): void
    {
        $this->mock(OAuthApiClientContract::class, function (MockInterface $mock) use ($replacements) {
            $oauthYandexInfoJson = $this->getTestResources('oauth_yandex_info.json');
            $response = json_decode(file_get_contents($oauthYandexInfoJson), true);
            $response = array_replace_recursive($response, $replacements);

            $mock->shouldReceive('yandexInfo')->andReturn(
                YandexInfoResponseData::from($response)
            );
        });
    }
}
