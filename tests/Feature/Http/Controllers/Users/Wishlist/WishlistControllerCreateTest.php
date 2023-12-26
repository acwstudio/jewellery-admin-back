<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\Wishlist;

use App\Modules\Catalog\Models\Product;
use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;

class WishlistControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/user/wishlist/';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockMindboxApiClient();
    }

    public function testSuccessful(): void
    {
        Sanctum::actingAs($this->getUser());
        $product = Product::factory()->create(['setFull' => true]);

        $response = $this->post(self::METHOD . $product->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    public function testFailure(): void
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->post(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAuth(): void
    {
        $product = Product::factory()->create(['setFull' => true]);

        $response = $this->post(self::METHOD . $product->getKey());
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function mockMindboxApiClient(): void
    {
        $this->mock(
            MindboxApiClientContract::class,
            function (MockInterface $mock) {
                $mock->allows('send');
                $mock->allows('websiteSetWithList');
            }
        );
    }
}
