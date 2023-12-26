<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Delivery\Pvz;

use App\Modules\Delivery\Models\Carrier;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PvzControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/delivery/pvz';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $carrier = Carrier::factory()->create();

        $data = [
            'external_id' => 'ID123',
            'carrier_id' => $carrier->getKey(),
            'latitude' => '10.55',
            'longitude' => '55.1',
            'work_time' => '09:00-21:00',
            'area' => 'Москва',
            'city' => 'Москва',
            'district' => 'Москва',
            'street' => 'ул Богданова',
            'address' => 'г Москва, ул Богданова, д 16',
            'price' => '100000'
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('external_id', $content);
        self::assertArrayHasKey('latitude', $content);
        self::assertArrayHasKey('longitude', $content);
        self::assertArrayHasKey('work_time', $content);
        self::assertArrayHasKey('area', $content);
        self::assertArrayHasKey('city', $content);
        self::assertArrayHasKey('district', $content);
        self::assertArrayHasKey('street', $content);
        self::assertArrayHasKey('carrier', $content);
        self::assertArrayHasKey('price', $content);
        self::assertArrayHasKey('address', $content);
        self::assertArrayHasKey('metro', $content);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        $carrier = Carrier::factory()->create();

        $data = [
            'external_id' => 'ID123',
            'carrier_id' => $carrier->getKey(),
            'latitude' => '10.55',
            'longitude' => '55.1',
            'work_time' => '09:00-21:00',
            'area' => 'Москва',
            'city' => 'Москва',
            'district' => 'Москва',
            'street' => 'ул Богданова',
            'address' => 'г Москва, ул Богданова, д 16',
            'price' => '100000'
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
