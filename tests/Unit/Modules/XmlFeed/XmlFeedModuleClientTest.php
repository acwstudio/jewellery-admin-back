<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\XmlFeed;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Modules\Live\Models\LiveProduct;
use App\Modules\XmlFeed\Enums\FeedTypeEnum;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\XmlFeedModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Money\Money;
use Tests\TestCase;

class XmlFeedModuleClientTest extends TestCase
{
    private XmlFeedModuleClientInterface $xmlFeedModuleClient;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();
        $this->xmlFeedModuleClient = app(XmlFeedModuleClientInterface::class);
    }

    public function testSuccessfulAvito()
    {
        $this->createProducts();
        $this->expectNotToPerformAssertions();
        $this->xmlFeedModuleClient->generate(FeedTypeEnum::AVITO);
    }

    public function testSuccessfulVk()
    {
        $this->createProducts();
        $this->expectNotToPerformAssertions();
        $this->xmlFeedModuleClient->generate(FeedTypeEnum::VK);
    }

    public function testSuccessfulYandex()
    {
        $this->createProducts();
        $this->expectNotToPerformAssertions();
        $this->xmlFeedModuleClient->generate(FeedTypeEnum::YANDEX);
    }

    public function testSuccessfulMindbox()
    {
        $this->createProducts();
        $this->expectNotToPerformAssertions();
        $this->xmlFeedModuleClient->generate(FeedTypeEnum::MINDBOX);
    }

    public function testSuccessfulMindboxByPrice()
    {
        $products = $this->createProducts();
        /** @var Product $product */
        foreach ($products as $product) {
            $this->addProductOfferPrice($product, OfferPriceTypeEnum::PROMO, 1100);
            $this->addProductOfferPrice($product, OfferPriceTypeEnum::SALE, 1000);
        }

        $this->expectNotToPerformAssertions();
        $this->xmlFeedModuleClient->generate(FeedTypeEnum::MINDBOX);
    }

    public function testSuccessfulMindboxByNonPhoto()
    {
        $products = $this->createProducts();

        $nonPhotoProducts = $products->random(5);
        /** @var Product $product */
        foreach ($nonPhotoProducts as $product) {
            $product->imageUrls()->getQuery()->delete();
        }

        $this->expectNotToPerformAssertions();
        $this->xmlFeedModuleClient->generate(FeedTypeEnum::MINDBOX);
    }

    private function createProducts(): Collection
    {
        $categories = new Collection();
        $categoryTitle = ['Подвеска', 'Кольцо', 'Серьги', 'Цепь', 'Браслет', 'Другое'];
        foreach ($categoryTitle as $item) {
            $categories->add(Category::factory()->create(['title' => $item]));
        }

        $probeZoloto = Feature::factory()->create(['type' => FeatureTypeEnum::PROBE, 'value' => '585']);
        $probeSerebro = Feature::factory()->create(['type' => FeatureTypeEnum::PROBE, 'value' => '925']);

        $materials = new Collection();
        $materials->add(Feature::factory()->create(['type' => FeatureTypeEnum::METAL, 'value' => 'Золото']));
        $materials->add(Feature::factory()->create(['type' => FeatureTypeEnum::METAL, 'value' => 'Серебро']));

        $materialColors = new Collection();
        $materialColors->add(Feature::factory()->create([
            'type' => FeatureTypeEnum::METAL_COLOR,
            'value' => 'Белое'
        ]));
        $materialColors->add(Feature::factory()->create([
            'type' => FeatureTypeEnum::METAL_COLOR,
            'value' => 'Синий'
        ]));

        $inserts = new Collection();
        $inserts->add(Feature::factory()->create(['type' => FeatureTypeEnum::INSERT, 'value' => 'Бриллиант']));
        $inserts->add(Feature::factory()->create(['type' => FeatureTypeEnum::INSERT, 'value' => 'Аметист']));

        $insertColors = new Collection();
        $insertColors->add(Feature::factory()->create([
            'type' => FeatureTypeEnum::INSERT_COLOR,
            'value' => 'Красный'
        ]));
        $insertColors->add(Feature::factory()->create([
            'type' => FeatureTypeEnum::INSERT_COLOR,
            'value' => 'Черный'
        ]));

        $products = Product::factory(10)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($products as $product) {
            $product->categories()->attach($categories->random());
            $product->save();

            /** @var Feature $insert */
            $insert = $inserts->random();
            ProductFeature::factory()->create([
                'product_id' => $product,
                'feature_id' => $insert,
                'is_main' => true
            ]);

            $insertColor = $insertColors->random();
            ProductFeature::factory()->create([
                'product_id' => $product,
                'feature_id' => $insertColor
            ]);

            /** @var Feature $material */
            $randomMaterials = $materials->random(2);
            foreach ($randomMaterials as $material) {
                ProductFeature::factory()->create([
                    'product_id' => $product,
                    'feature_id' => $material,
                    'is_main' => true
                ]);
                if ($material->value === 'Золото') {
                    ProductFeature::factory()->create([
                        'product_id' => $product,
                        'feature_id' => $probeZoloto
                    ]);
                } else {
                    ProductFeature::factory()->create([
                        'product_id' => $product,
                        'feature_id' => $probeSerebro
                    ]);
                }

                $materialColor = $materialColors->random();
                ProductFeature::factory()->create([
                    'product_id' => $product,
                    'feature_id' => $materialColor
                ]);
            }

            $this->addToLiveProduct($product);
            $product->refresh();
        }

        $this->addToCollectionProducts($products);

        $qtyProducts = $products->random(5);
        foreach ($qtyProducts as $product) {
            /** @var \App\Modules\Catalog\Models\ProductOffer $productOffer */
            $productOffer = $product->productOffers->first();
            /** @var ProductOfferStock $productOfferStock */
            $productOfferStock = $productOffer->productOfferStocks->first();
            $productOfferStock->update(['count' => 10]);
        }

        return $products;
    }

    private function addToLiveProduct(Product $product): void
    {
        LiveProduct::factory()->create(['product_id' => $product]);
    }

    private function addToCollectionProducts(Collection $products): void
    {
        /** @var CollectionModel $collection */
        $collection = CollectionModel::factory()->create();
        $collection->products()->sync($products);
    }

    private function addProductOfferPrice(Product $product, OfferPriceTypeEnum $type, int $amount): void
    {
        $offer = $product->productOffers->first();
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $offer,
            'type' => $type,
            'price' => Money::RUB($amount * 100)
        ]);
    }
}
