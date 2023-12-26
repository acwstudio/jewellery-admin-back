<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Product;

use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Storage\Models\Media;
use App\Packages\Enums\LiquidityEnum;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product';

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $brand = Brand::factory()->create();

        $response = $this->post(self::METHOD, [
            'categories' => [$category->getKey()],
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
            'popularity' => fake()->randomDigit(),
            'brand_id' => $brand->getKey()
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('categories', $content);
        self::assertArrayHasKey('sku', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('summary', $content);
        self::assertArrayHasKey('description', $content);
        self::assertArrayHasKey('manufacture_country', $content);
        self::assertArrayHasKey('rank', $content);
        self::assertArrayHasKey('preview_image', $content);
        self::assertArrayHasKey('catalog_number', $content);
        self::assertArrayHasKey('supplier', $content);
        self::assertArrayHasKey('liquidity', $content);
        self::assertArrayHasKey('stamp', $content);
        self::assertArrayHasKey('meta_title', $content);
        self::assertArrayHasKey('meta_description', $content);
        self::assertArrayHasKey('meta_keywords', $content);
        self::assertArrayHasKey('is_active', $content);
        self::assertArrayHasKey('is_drop_shipping', $content);
        self::assertArrayHasKey('popularity', $content);
    }

    public function testSuccessfulPreviewImage()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $previewImage = $this->getPreviewImage();

        $response = $this->post(self::METHOD, [
            'categories' => [$category->getKey()],
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'is_active' => true,
            'preview_image_id' => $previewImage->getKey()
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('preview_image', $content);

        self::assertArrayHasKey('id', $content['preview_image']);
        self::assertNotEmpty($content['preview_image']['id']);

        self::assertArrayHasKey('image_url_sm', $content['preview_image']);
        self::assertNotEmpty($content['preview_image']['image_url_sm']);

        self::assertArrayHasKey('image_url_md', $content['preview_image']);
        self::assertNotEmpty($content['preview_image']['image_url_md']);

        self::assertArrayHasKey('image_url_lg', $content['preview_image']);
        self::assertNotEmpty($content['preview_image']['image_url_lg']);
    }

    public function testSuccessfulImages()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $previewImage1 = $this->getPreviewImage();
        $previewImage2 = $this->getPreviewImage();

        $response = $this->post(self::METHOD, [
            'categories' => [$category->getKey()],
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => fake()->randomDigit(),
            'is_active' => true,
            'images' => [$previewImage2->getKey(), $previewImage1->getKey()]
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

    public function testSuccessfulDescriptionString()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $brand = Brand::factory()->create();

        $response = $this->post(self::METHOD, [
            'categories' => [$category->getKey()],
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(300),
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
            'popularity' => fake()->randomDigit(),
            'brand_id' => $brand->getKey()
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('description', $content);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD, [
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

    public function testFailureNotArrayImages()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'categories' => [$category->getKey()],
            'sku' => fake()->text(10),
            'name' => fake()->text(50),
            'summary' => fake()->text(50),
            'description' => fake()->text(50),
            'manufacture_country' => fake()->text(50),
            'rank' => 1,
            'is_active' => true,
            'images' => 'image1'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureIntegerImages()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'categories' => [$category->getKey()],
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
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'categories' => [$category->getKey()],
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

        /** @var Category $category */
        $category = Category::factory()->create();

        $brand = Brand::factory()->create();

        $response = $this->post(self::METHOD, [
            'categories' => [$category->getKey()],
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
            'popularity' => fake()->randomDigit(),
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
