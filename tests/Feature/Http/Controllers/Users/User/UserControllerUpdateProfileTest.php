<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\User;

use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class UserControllerUpdateProfileTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/user/profile';

    public function testSuccessful()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->put(self::METHOD, [
            "phone" => "+79990811470",
            "email" => "user@examp.com",
            "sex" => 2,
            "birth_date" => "2014-01-01",
            "surname" => "Smither",
            "name" => "Jsdn",
            "patronymic" => "Patric",
        ]);
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

        self::assertEquals('Jsdn', $content['name']);
        self::assertEquals('Smither', $content['surname']);
        self::assertEquals('Patric', $content['patronymic']);
        self::assertEquals('user@examp.com', $content['email']);
        self::assertEquals('2', $content['sex']);
    }

    public function testFailure()
    {
        $response = $this->put(self::METHOD, [
            "phone" => "+79990811470",
            "email" => "user@examp.com",
            "sex" => 1,
            "birth_date" => "2014-01-01",
            "surname" => "Smither",
            "name" => "Jsdn"
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureBySex()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->put(self::METHOD, [
            "phone" => "+79990811470",
            "email" => "user@examp.com",
            "sex" => 10,
            "birth_date" => "2014-01-01",
            "surname" => "Smither",
            "name" => "Jsdn"
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
