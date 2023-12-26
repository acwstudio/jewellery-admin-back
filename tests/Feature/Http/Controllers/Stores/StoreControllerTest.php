<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Stores;

use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Models\StoreType;
use App\Modules\Stores\Models\StoreWorkTime;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use JsonException;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    /**
     * @throws JsonException
     */
    protected function setUp(): void
    {
        parent::setUp();
        Http::fake([
            'dadata.ru*' => Http::response(json_encode([], JSON_THROW_ON_ERROR))
        ]);
    }

    public function testGetStore(): void
    {
        /** @var Store $store */
        $store = Store::factory()->create();
        StoreWorkTime::factory()->create(['store_id' => $store->getKey()]);

        $response = $this->get(route('api.v1.stores.shop.show', $store->getKey()));

        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($store) {
                $json
                    ->hasAll(['id', 'name', 'description', 'address', 'phone', 'latitude', 'longitude',
                        'isWorkWeekdays', 'isWorkSaturday', 'isWorkSunday', 'types', 'work_times', 'subways'])
                    ->where('id', $store->getKey());
            });
    }

    public function testDeleteStore(): void
    {
        /** @var Store $store */
        $store = Store::factory()->create();

        $response = $this->delete(route('api.v1.stores.shop.destroy', $store->id));

        $response->assertStatus(200);
        $this->assertNull(
            Store::find($store->getKey())
        );
    }

    public function testGetAllStore(): void
    {
        $stores = Store::factory(3)->create();
        foreach ($stores as $store) {
            StoreWorkTime::factory()->create(['store_id' => $store->getKey()]);
        }

        $response = $this->get(route('api.v1.stores.shop.index'));

        $response->assertSuccessful();
    }

    public function testCreateStore(): void
    {
        $storeTypes = StoreType::factory(4)->create();

        $name = fake()->name;
        $response = $this->post(route('api.v1.stores.shop.store'), [
            "name" => $name,
            "description" => "Dsesc",
            "address" => "г Москва, ул Промышленная, д 3",
            "phone" => "+79990000000",
            "latitude" => 12.5432,
            "longitude" => 13.5432,
            "types" => $storeTypes->pluck('id')->toArray(),
            "work_times" => [
                [
                    "day" => "monday",
                    "start_time" => "19:00",
                    "end_time" => "20:00"
                ],
                [
                    "day" => "tuesday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "day" => "wednesday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "day" => "thursday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "day" => "friday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "day" => "saturday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "day" => "sunday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ]
            ]
        ]);

        $response->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($name) {
                $json->hasAll(['id', 'name', 'description', 'address', 'phone', 'latitude', 'longitude',
                    'isWorkWeekdays', 'isWorkSaturday', 'isWorkSunday', 'types', 'work_times', 'subways'])
                    ->where('name', $name);
            });
    }

    public function testUpdateStore(): void
    {
        /** @var Store $store */
        $store = Store::factory()->create();
        StoreWorkTime::factory()->create(['store_id' => $store->getKey()]);

        $name = fake()->name;

        $response = $this->put(route('api.v1.stores.shop.update', $store->getKey()), [
            "name" => $name,
            "description" => "Dsesc",
            "address" => "г Москва, ул Промышленная, д 3",
            "phone" => "+79990000000",
            "latitude" => 12.5432,
            "longitude" => 13.5432,
            "types" => [],
            "work_times" => [
                [
                    "id" => null,
                    "day" => "monday",
                    "start_time" => "19:00",
                    "end_time" => "20:00"
                ],
                [
                    "id" => null,
                    "day" => "tuesday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "id" => null,
                    "day" => "wednesday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "id" => null,
                    "day" => "thursday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "id" => null,
                    "day" => "friday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "id" => null,
                    "day" => "saturday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ],
                [
                    "id" => null,
                    "day" => "sunday",
                    "start_time" => "09:00",
                    "end_time" => "20:00"
                ]
            ]
        ]);
        $response->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($store, $name) {
                $json->hasAll(['id', 'name', 'description', 'address', 'phone', 'latitude', 'longitude',
                    'isWorkWeekdays', 'isWorkSaturday', 'isWorkSunday', 'types', 'work_times', 'subways'])
                    ->where('id', $store->getKey())
                    ->where('name', $name);
            });
    }
}
