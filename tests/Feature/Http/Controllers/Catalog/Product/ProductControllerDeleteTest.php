<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Product;

use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Storage\Models\Media;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerDeleteTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product/';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        $product = Product::factory()->create();

        $response = $this->delete(self::METHOD . $product->getKey());
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    public function testSuccessfulByPreviewImage()
    {
        $previewImage = $this->getPreviewImage();

        /** @var Product $product1 */
        $product1 = Product::factory()->create(['preview_image_id' => $previewImage->id]);
        /** @var Product $product2 */
        $product2 = Product::factory()->create(['preview_image_id' => $previewImage->id]);

        $response = $this->delete(self::METHOD . $product1->id);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        $product2 = $product2->refresh();

        self::assertEmpty($content);
        self::assertInstanceOf(PreviewImage::class, $product2->previewImage);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 5);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        $product = Product::factory()->create();

        $response = $this->delete(self::METHOD . $product->getKey());
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function getPreviewImage(): PreviewImage
    {
        /** @var PreviewImage $previewImage */
        $previewImage = PreviewImage::factory()->create();
        Media::factory()->create([
            'model_type' => PreviewImage::class,
            'model_id' => $previewImage->getKey()
        ]);

        return $previewImage;
    }
}
