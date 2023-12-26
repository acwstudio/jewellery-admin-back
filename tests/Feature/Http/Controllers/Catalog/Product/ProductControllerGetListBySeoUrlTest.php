<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Product;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\Seo;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductControllerGetListBySeoUrlTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product-by-seo';

    public function testSuccessful()
    {
        Product::factory(3)->create(['setFull' => true]);
        $products = Product::factory(3)->create(['setFull' => true]);

        /** @var Feature $featureMetal */
        $featureMetal = Feature::factory()->create([
            'type' => FeatureTypeEnum::METAL,
            'value' => 'Золото',
            'slug' => 'iz_zolota'
        ]);

        /** @var Feature $featureMetalColor */
        $featureMetalColor = Feature::factory()->create([
            'type' => FeatureTypeEnum::METAL_COLOR,
            'value' => 'Белое',
            'slug' => 'belogo_cveta'
        ]);

        $this->addProductFeature($products, $featureMetal);
        $this->addProductFeature($products, $featureMetalColor);

        $seo = $this->createSeo([
            'feature' => [
                FeatureTypeEnum::METAL->value => $featureMetal->getKey(),
                FeatureTypeEnum::METAL_COLOR->value => $featureMetalColor->getKey(),
            ]
        ]);

        $data = [
            'seo_url' => $seo->url
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulByPagination()
    {
        Product::factory(2)->create(['setFull' => true]);
        $products = Product::factory(5)->create(['setFull' => true]);

        /** @var Feature $featureMetal */
        $featureMetal = Feature::factory()->create([
            'type' => FeatureTypeEnum::METAL,
            'value' => 'Золото',
            'slug' => 'iz_zolota'
        ]);
        /** @var Feature $featureMetalColor */
        $featureMetalColor = Feature::factory()->create([
            'type' => FeatureTypeEnum::METAL_COLOR,
            'value' => 'Белое',
            'slug' => 'belogo_cveta'
        ]);

        $this->addProductFeature($products, $featureMetal);
        $this->addProductFeature($products, $featureMetalColor);

        $seo = $this->createSeo([
            'feature' => [
                FeatureTypeEnum::METAL->value => $featureMetal->getKey(),
                FeatureTypeEnum::METAL_COLOR->value => $featureMetalColor->getKey(),
            ]
        ]);

        $data = [
            'seo_url' => $seo->url,
            'pagination' => ['page' => 1, 'per_page' => 3]
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
        self::assertArrayHasKey('pagination', $content);
        self::assertIsArray($content['pagination']);
        self::assertArrayHasKey('total', $content['pagination']);
        self::assertEquals(5, $content['pagination']['total']);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureNotFound()
    {
        $data = [
            'seo_url' => "url"
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function createSeo(array $filter): Seo
    {
        /** @var Seo $seo */
        $seo = Seo::factory()->create([
            'filters' => $filter
        ]);

        return $seo;
    }

    private function addProductFeature(Collection $products, Feature $feature): void
    {
        /** @var Product $product */
        foreach ($products as $product) {
            ProductFeature::factory()->create([
                'product_id' => $product,
                'feature_id' => $feature
            ]);
        }
    }
}
