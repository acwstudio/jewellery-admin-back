<?php

/** @noinspection PhpCSValidationInspection */

declare(strict_types=1);

namespace Http\Controllers\Catalog;

use App\Modules\Catalog\Models\Brand;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BrandControllerTest extends TestCase
{
    public function testGetBrand()
    {
        Brand::factory()->create();

        $brand = Brand::query()->first();
        $response = $this->get(route('api.v1.catalog.brand.show', $brand->id));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($brand) {
                $json
                    ->hasAll(['id', 'name'])
                    ->where('id', $brand->id);
            });
    }

    /**
     * @return void
     */
    public function testDeleteBrand(): void
    {
        Brand::factory()->create();

        $brand = Brand::query()->first();

        $response = $this->delete(route('api.v1.catalog.brand.destroy', $brand->id));

        $response->assertStatus(200);
        $this->assertNull(
            Brand::query()->find($brand->id)
        );
    }

    /**
     * @return void
     */
    public function testGetAllBrand(): void
    {
        $ids = Brand::factory(3)->create()->pluck('id');

        $response = $this->get(route('api.v1.catalog.brand.index'));
        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($ids) {
                $json
                    ->has(count($ids));
            });
    }

    public function testCreateBrand()
    {
        $name = fake()->name;

        $response = $this->post(route('api.v1.catalog.brand.store'), [
            'name' => $name
        ]);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) use ($name) {
                $json->hasAll(['id', 'name'])
                    ->where('name', $name);
            });
    }

    public function testUpdateBrand()
    {
        Brand::factory()->create();

        $brand = Brand::query()->first();

        $name = fake()->name;

        $response = $this->put(route('api.v1.catalog.brand.update', $brand->id), [
            'name' => $name
        ]);
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($brand, $name) {
                $json->hasAll(['id', 'name'])
                    ->where('id', $brand->id)
                    ->where('name', $name);
            });
    }
}
