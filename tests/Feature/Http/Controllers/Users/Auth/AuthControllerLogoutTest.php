<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\Auth;

use App\Modules\Users\Enums\AuthTokenNameEnum;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class AuthControllerLogoutTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/auth/logout';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser(RoleEnum::USER);
    }

    public function testSuccessful()
    {
        Sanctum::actingAs($this->user);

        $response = $this->post(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    public function testSuccessfulWithToken()
    {
        $token = $this->user->createToken(AuthTokenNameEnum::ACCESS_TOKEN->value)->plainTextToken;

        $response = $this->withToken($token)->post(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureWithTokenIncorrect()
    {
        $token = $this->user->createToken(AuthTokenNameEnum::ACCESS_TOKEN->value)->plainTextToken;

        $response = $this->withToken($token . '1')->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
