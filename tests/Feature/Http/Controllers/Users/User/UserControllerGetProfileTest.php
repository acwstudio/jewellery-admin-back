<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\User;

use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class UserControllerGetProfileTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/user/profile';

    public function testSuccessful()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('phone', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('surname', $content);
        self::assertArrayHasKey('patronymic', $content);
        self::assertArrayHasKey('email', $content);
        self::assertArrayHasKey('sex', $content);
        self::assertArrayHasKey('birth_date', $content);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
