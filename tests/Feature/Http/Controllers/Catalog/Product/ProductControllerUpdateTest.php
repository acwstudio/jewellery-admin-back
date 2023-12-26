<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Product;

use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Storage\Models\Media;
use App\Packages\Enums\LiquidityEnum;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerUpdateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product/';

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        /** @var Product $product */
        $product = Product::factory()->has(
            Category::factory()->count(3),
            'categories'
        )->create();

        /** @var Category $newCategory */
        $newCategory = Category::factory()->create();

        $brand = Brand::factory()->create();

        $response = $this->put(self::METHOD . $product->id, [
            'categories' => [$newCategory->getKey()],
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'catalog_number' => fake()->text(10),
            'supplier' => fake()->text(10),
            'liquidity' => fake()->randomElement(LiquidityEnum::cases())->value,
            'stamp' => fake()->randomFloat(),
            'meta_title' => fake()->text(50),
            'meta_description' => fake()->text(50),
            'meta_keywords' => fake()->text(50),
            'is_active' => true,
            'is_drop_shipping' => false,
            'popularity' => 1,
            'brand_id' => $brand->getKey()
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('categories', $content);
        self::assertIsArray($content['categories']);
        self::assertCount(1, $content['categories']);
        foreach ($content['categories'] as $category) {
            self::assertEquals($newCategory->getKey(), $category);
        }
    }

    public function testSuccessfulPreviewImage()
    {
        /** @var Product $product */
        $product = Product::factory()->has(
            Category::factory()->count(3),
            'categories'
        )->create();

        $previewImage = $this->getPreviewImage();

        $response = $this->put(self::METHOD . $product->id, [
            'categories' => $product->categories()->get()->pluck('id')->toArray(),
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'is_active' => true,
            'preview_image_id' => $previewImage->id
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('preview_image', $content);
        self::assertIsArray($content['preview_image']);
        self::assertArrayHasKey('id', $content['preview_image']);
        self::assertEquals($previewImage->id, $content['preview_image']['id']);
    }

    public function testSuccessfulImages()
    {
        /** @var Product $product */
        $product = Product::factory()->has(
            Category::factory()->count(3),
            'categories'
        )->create();

        $previewImage = $this->getPreviewImage();

        $response = $this->put(self::METHOD . $product->getKey(), [
            'categories' => $product->categories()->get()->pluck('id')->toArray(),
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'is_active' => true,
            'images' => [$previewImage->getKey()]
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('images', $content);
        self::assertIsArray($content['images']);
        self::assertNotEmpty($content['images']);

        foreach ($content['images'] as $image) {
            self::assertArrayHasKey('id', $image);
            self::assertNotEmpty($image['id']);

            self::assertArrayHasKey('image_url_sm', $image);
            self::assertNotEmpty($image['image_url_sm']);

            self::assertArrayHasKey('image_url_md', $image);
            self::assertNotEmpty($image['image_url_md']);

            self::assertArrayHasKey('image_url_lg', $image);
            self::assertNotEmpty($image['image_url_lg']);
        }
    }

    public function testSuccessfulUpdateOrderImages()
    {
        /** @var Product $product */
        $product = Product::factory()->has(
            Category::factory()->count(3),
            'categories'
        )->create();

        $this->getPreviewImage();

        $previewImage1 = $this->getPreviewImage();
        $previewImage2 = $this->getPreviewImage();

        $data = [
            'categories' => $product->categories()->get()->pluck('id')->toArray(),
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'is_active' => true,
            'images' => [$previewImage1->getKey(), $previewImage2->getKey()]
        ];

        $response = $this->put(self::METHOD . $product->getKey(), $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('images', $content);
        self::assertIsArray($content['images']);
        self::assertNotEmpty($content['images']);
        self::assertCount(2, $content['images']);

        $firstImage = $content['images'][0];
        self::assertArrayHasKey('id', $firstImage);
        self::assertEquals($previewImage1->getKey(), $firstImage['id']);

        $data['images'] = [$previewImage2->getKey(), $previewImage1->getKey()];

        $response = $this->put(self::METHOD . $product->getKey(), $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('images', $content);
        self::assertIsArray($content['images']);
        self::assertNotEmpty($content['images']);
        self::assertCount(2, $content['images']);

        $firstImage = $content['images'][0];
        self::assertArrayHasKey('id', $firstImage);
        self::assertEquals($previewImage2->getKey(), $firstImage['id']);
    }

    public function testSuccessfulAddNotExistImages()
    {
        /** @var Product $product */
        $product = Product::factory()->has(
            Category::factory()->count(3),
            'categories'
        )->create();

        $previewImage1 = $this->getPreviewImage();
        $previewImage2 = $this->getPreviewImage();

        $response = $this->put(self::METHOD . $product->getKey(), [
            'categories' => $product->categories()->get()->pluck('id')->toArray(),
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => fake()->randomDigitNotNull(),
            'is_active' => true,
            'images' => [$previewImage1->getKey(), $previewImage2->getKey(), 100500]
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('images', $content);
        self::assertIsArray($content['images']);
        self::assertNotEmpty($content['images']);
        self::assertCount(2, $content['images']);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD . 5, [
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => fake()->randomDigit(),
            'catalog_number' => fake()->text(10),
            'supplier' => fake()->text(10),
            'liquidity' => fake()->randomElement(LiquidityEnum::cases())->value,
            'stamp' => fake()->randomFloat(),
            'meta_title' => fake()->text(50),
            'meta_description' => fake()->text(50),
            'meta_keywords' => fake()->text(50),
            'is_active' => true,
            'is_drop_shipping' => false,
            'popularity' => fake()->randomDigit()
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureIntegerImages()
    {
        /** @var Product $product */
        $product = Product::factory()->has(
            Category::factory()->count(3),
            'categories'
        )->create();

        $response = $this->put(self::METHOD . $product->getKey(), [
            'categories' => $product->categories()->get()->pluck('id')->toArray(),
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'is_active' => true,
            'images' => ['image1', 'image2']
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureMinImages()
    {
        /** @var Product $product */
        $product = Product::factory()->has(
            Category::factory()->count(3),
            'categories'
        )->create();

        $response = $this->put(self::METHOD . $product->getKey(), [
            'categories' => $product->categories()->get()->pluck('id')->toArray(),
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'is_active' => true,
            'images' => [0, 1]
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
        /** @var Category $newCategory */
        $newCategory = Category::factory()->create();

        $brand = Brand::factory()->create();

        $response = $this->put(self::METHOD . $product->id, [
            'categories' => [$newCategory->getKey()],
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'catalog_number' => fake()->text(10),
            'supplier' => fake()->text(10),
            'liquidity' => fake()->randomElement(LiquidityEnum::cases())->value,
            'stamp' => fake()->randomFloat(),
            'meta_title' => fake()->text(50),
            'meta_description' => fake()->text(50),
            'meta_keywords' => fake()->text(50),
            'is_active' => true,
            'is_drop_shipping' => false,
            'popularity' => 1,
            'brand_id' => $brand->getKey()
        ]);
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
