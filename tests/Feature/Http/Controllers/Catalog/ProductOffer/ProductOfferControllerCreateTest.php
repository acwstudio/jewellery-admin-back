<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductOffer;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductOfferControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/trade_offer';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('size', $content);
        self::assertArrayHasKey('count', $content);
        self::assertArrayHasKey('prices', $content);
        self::assertIsArray($content['prices']);
    }

    public function testSuccessfulSize()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'size' => '12.5'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('size', $content);
        self::assertEquals('12.5', $content['size']);
        self::assertArrayHasKey('count', $content);
        self::assertArrayHasKey('prices', $content);
        self::assertIsArray($content['prices']);
    }

    public function testSuccessfulSizeNull()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'size' => null
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('size', $content);
        self::assertEmpty($content['size']);
        self::assertArrayHasKey('count', $content);
        self::assertArrayHasKey('prices', $content);
        self::assertIsArray($content['prices']);
    }

    public function testSuccessfulNewSize()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        ProductOffer::factory()->create([
            'product_id' => $product->getKey(),
            'size' => '10'
        ]);

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'size' => '12.5'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('size', $content);
        self::assertArrayHasKey('count', $content);
        self::assertArrayHasKey('prices', $content);
        self::assertIsArray($content['prices']);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD, [
            'product_id' => 100500,
            'size' => '12.5'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureProductIdString()
    {
        $response = $this->post(self::METHOD, [
            'product_id' => 'id',
            'size' => '12.5'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureSizeInt()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'size' => 12
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureNewSize()
    {
        $size = '12.5';

        /** @var Product $product */
        $product = Product::factory()->create();
        ProductOffer::factory()->create([
            'product_id' => $product->getKey(),
            'size' => $size,
            'weight' => null
        ]);

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'size' => $size,
            'weight' => null
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureNewWeight()
    {
        $weight = '10.4';

        /** @var Product $product */
        $product = Product::factory()->create();
        ProductOffer::factory()->create([
            'product_id' => $product->getKey(),
            'size' => null,
            'weight' => $weight
        ]);

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'size' => null,
            'weight' => $weight
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAllNull()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        ProductOffer::factory()->create([
            'product_id' => $product->getKey(),
            'size' => null,
            'weight' => null
        ]);

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'size' => null,
            'weight' => null
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
