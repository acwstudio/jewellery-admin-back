<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Category;

use App\Modules\Catalog\Enums\CategoryListOptionsEnum;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Storage\Models\Media;
use Tests\TestCase;

class CategoryListControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/category_list';

    public function testSuccessful()
    {
        /** @var Category $category */
        $category = Category::factory()
             ->has(
                 Category::factory(3),
                 'children'
             )
             ->create();
        $queryParams = http_build_query([
            'with' => [CategoryListOptionsEnum::CHILDREN->value]
        ]);
        $response = $this->get(self::METHOD . '?' . $queryParams);
        $response->assertSuccessful();

        $content = json_decode($response->content(), true);

        self::assertIsArray($content);
        foreach ($content as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertArrayHasKey('parent_id', $item);
            self::assertArrayHasKey('title', $item);
            self::assertArrayHasKey('h1', $item);
            self::assertArrayHasKey('children', $item);
            self::assertArrayHasKey('slug', $item);

            if ($item['id'] === $category->getKey()) {
                self::assertIsArray($item['children']);
                self::assertCount(3, $item['children']);
            }
        }
    }

    public function testSuccessfulByPreview()
    {
        $categories = Category::factory(3)->create();
        /** @var Category $category */
        foreach ($categories as $category) {
            $category->previewImage()->associate($this->getPreviewImage());
            $category->save();
        }

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->content(), true);

        self::assertIsArray($content);
        foreach ($content as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertArrayHasKey('parent_id', $item);
            self::assertArrayHasKey('title', $item);
            self::assertArrayHasKey('h1', $item);
            self::assertArrayHasKey('children', $item);
            self::assertArrayHasKey('slug', $item);
            self::assertArrayHasKey('preview_image', $item);
            self::assertNotEmpty($item['preview_image']);
        }
    }

    public function testSuccessfulFilterByHasProduct()
    {
        Category::factory(2)->create();

        $products = Product::factory(3)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($products as $product) {
            $product->categories()->sync(Category::factory()->create());
        }

        $productNotImages = Product::factory(2)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($productNotImages as $product) {
            $product->categories()->sync(Category::factory()->create());
            $product->previewImage()->getQuery()->delete();
            $product->imageUrls()->getQuery()->delete();
        }

        $productNotCounts = Product::factory(1)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($productNotCounts as $product) {
            $product->categories()->sync(Category::factory()->create());
            /** @var \App\Modules\Catalog\Models\ProductOffer $productOffer */
            $productOffer = $product->productOffers->first();
            $productOffer->productOfferStocks()->getQuery()->delete();
        }

        $productNotPrice = Product::factory(1)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($productNotPrice as $product) {
            $product->categories()->sync(Category::factory()->create());
            /** @var \App\Modules\Catalog\Models\ProductOffer $productOffer */
            $productOffer = $product->productOffers->first();
            $productOffer->productOfferPrices()->getQuery()->delete();
        }

        $query = [
            'with' => [CategoryListOptionsEnum::CHILDREN->value],
            'filter' => [
                'has_product' => false
            ]
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->content(), true);

        self::assertNotEmpty($content);
        self::assertCount(6, $content);

        $query['filter']['has_product'] = true;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->content(), true);

        self::assertNotEmpty($content);
        self::assertCount(3, $content);

        unset($query['filter']['has_product']);
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->content(), true);

        self::assertNotEmpty($content);
        self::assertCount(9, $content);
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
