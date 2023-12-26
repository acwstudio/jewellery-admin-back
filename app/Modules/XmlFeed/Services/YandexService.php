<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\Services;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\XmlFeed\Traits\ProductTrait;
use App\Modules\XmlFeed\UseCases\GetProductCategories;
use App\Modules\XmlFeed\Contracts\XmlFeedContract;
use App\Packages\DataObjects\Catalog\Category\CategoryListItemData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\ProductFeature\ProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Carbon\Carbon;
use DOMDocument;
use DOMImplementation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class YandexService implements XmlFeedContract
{
    use ProductTrait;

    /** @var Collection<CategoryListItemData> */
    private Collection $productCategories;
    private DOMDocument $xml;
    private string $productUrl;

    public function getDocument(Collection $products, ?callable $onEach = null): DOMDocument
    {
        $this->productCategories = App::call(GetProductCategories::class);
        $this->productUrl = config('xml_feed.product_url');

        $implementation = new DOMImplementation();
        $this->xml = $implementation->createDocument('', '');
        $this->xml->encoding = 'utf-8';
        $this->xml->formatOutput = true;

        $ymlCatalog = $this->xml->createElement("yml_catalog");
        $ymlCatalog->setAttribute("date", Carbon::now()->toRfc3339String());

        $shop = $this->xml->createElement('shop');
        $shop->appendChild($this->xml->createElement('name', 'uvi.ru'));
        $shop->appendChild($this->xml->createElement('company', 'UVI'));
        $shop->appendChild($this->xml->createElement('url', 'https://uvi.ru/'));
        $shop->appendChild($this->createCurrencies());
        $shop->appendChild($this->createDeliveryOptions());

        if (!$products->isEmpty()) {
            $shop->appendChild($this->createCategories());
        }

        $offers = $this->xml->createElement('offers');
        /** @var \App\Packages\DataObjects\Catalog\Product\ProductData $product */
        foreach ($products as $product) {
            if ($this->isAddToFeed($product)) {
                $offers->appendChild($this->createOffer($product));
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        }
        $shop->appendChild($offers);

        $ymlCatalog->appendChild($shop);
        $this->xml->appendChild($ymlCatalog);

        return $this->xml;
    }

    private function createCurrencies(): \DOMNode
    {
        $currencies = $this->xml->createElement('currencies');

        $currency = $this->xml->createElement('currency');
        $currency->setAttribute('rate', '1');
        $currency->setAttribute('id', 'RUR');
        $currencies->appendChild($currency);

        return $currencies;
    }

    private function createDeliveryOptions(): \DOMNode
    {
        $deliveryOptions = $this->xml->createElement('delivery-options');

        $deliveryOption = $this->xml->createElement('option');
        $deliveryOption->setAttribute('order-before', '18');
        $deliveryOption->setAttribute('days', '6');
        $deliveryOption->setAttribute('cost', '350');
        $deliveryOptions->appendChild($deliveryOption);

        return $deliveryOptions;
    }

    private function createCategories(): \DOMNode
    {
        $categories = $this->xml->createElement('categories');
        $parentCategoryId = 33333333;

        $parentCategory = $this->xml->createElement('category', 'Все украшения');
        $parentCategory->setAttribute('id', (string)$parentCategoryId);
        $categories->appendChild($parentCategory);

        $yandexCategories = $this->getYandexCategoryIds();
        foreach ($yandexCategories as $name => $id) {
            $category = $this->xml->createElement('category', $name);
            $category->setAttribute('id', (string)$id);
            $category->setAttribute('parentId', (string)$parentCategoryId);
            $categories->appendChild($category);
        }

        return $categories;
    }

    private function createOffer(ProductData $product): \DOMNode
    {
        $offer = $this->xml->createElement('offer');
        $offer->setAttribute('id', (string)$product->id);
        $offer->setAttribute('available', 'true');

        /** CategoryId */
        $this->addCategoryIdAndTypePrefix($offer, $product);

        /** Url */
        $offer->appendChild(
            $this->xml->createElement('url', $this->productUrl . $product->slug)
        );

        /** Price */
        $this->addPrice($offer, $product);

        /** Picture */
        $this->addProductPictures($offer, $this->getProductImages($product));

        $offer->appendChild($this->xml->createElement('delivery', 'true'));
        $offer->appendChild($this->xml->createElement('store', 'true'));
        $offer->appendChild($this->xml->createElement('pickup', 'true'));

        $offer->appendChild($this->xml->createElement('name', $product->name));
        $offer->appendChild($this->xml->createElement('vendor', 'UVI'));
        $offer->appendChild(
            $this->xml->createElement('description', $this->getDescription($product))
        );
        $offer->appendChild($this->xml->createElement('model', $product->name));
        $offer->appendChild($this->xml->createElement('vendorCode', $product->sku));

        $productFeatures = $product->product_features
            ->where('feature.type', '!=', FeatureTypeEnum::BOOLEAN)
            ->all();
        $this->addProductFeatureParams($offer, new Collection($productFeatures));
        $this->addProductSizeParams($offer, new Collection($product->trade_offers->all()));

        return $offer;
    }

    private function isAddToFeed(ProductData $product): bool
    {
        $productImages = $this->getProductImages($product);
        if ($productImages->isEmpty()) {
            return false;
        }

        return true;
    }

    private function addCategoryIdAndTypePrefix(\DOMNode $offer, ProductData $product): void
    {
        $parentCategory = $this->getProductCategoryParent($product);
        if ($parentCategory instanceof CategoryListItemData) {
            $categoryName = $this->getYandexCategoryName(
                $parentCategory->title,
                $this->getProductMaterialName($product)
            );
            $categoryId = $this->getYandexCategoryIds($categoryName);

            $offer->appendChild($this->xml->createElement('categoryId', (string)$categoryId));
            $offer->appendChild($this->xml->createElement('typePrefix', $categoryName));
        }
    }

    private function addPrice(\DOMNode $offer, ProductData $product): void
    {
        $prices = $this->getProductPriceAmounts($product);

        $priceRegular = $prices[OfferPriceTypeEnum::REGULAR->value] ?? 0;
        if (empty($priceRegular)) {
            return;
        }

        $pricePromo = $prices[OfferPriceTypeEnum::PROMO->value] ?? 0;
        if (empty($pricePromo)) {
            $offer->appendChild($this->xml->createElement('price', (string)$priceRegular));
        } else {
            $offer->appendChild($this->xml->createElement('price', (string)$pricePromo));
            $offer->appendChild($this->xml->createElement('oldprice', (string)$priceRegular));
        }
        $offer->appendChild($this->xml->createElement('currencyId', 'RUR'));
    }

    private function addProductPictures(\DOMNode $offer, Collection $productImages): void
    {
        /** @var PreviewImageData $productImage */
        foreach ($productImages->take(5) as $productImage) {
            $url = str_replace(' ', '%20', $productImage->image_url_lg);
            $offer->appendChild($this->xml->createElement('picture', $url));
        }
    }

    private function addProductFeatureParams(\DOMNode $offer, Collection $productFeatures): void
    {
        /** @var ProductFeatureData $productFeature */
        foreach ($productFeatures as $productFeature) {
            $paramValue = $productFeature->feature->value;
            $name = $productFeature->feature->type->getLabel();
            if (FeatureTypeEnum::DYNAMIC === $productFeature->feature->type) {
                $paramValue = $productFeature->value;
                $name = $productFeature->feature->value;
            }

            $param = $this->xml->createElement('param', $paramValue);
            $param->setAttribute('name', $name);
            $offer->appendChild($param);
        }
    }

    private function addProductSizeParams(\DOMNode $offer, Collection $productOffers): void
    {
        /** @var ProductOfferData $productOffer */
        foreach ($productOffers as $productOffer) {
            if (!empty($productOffer->size)) {
                $param = $this->xml->createElement('param', $productOffer->size);
                $param->setAttribute('name', 'Размер');
                $offer->appendChild($param);
            }
        }
    }

    private function getProductCategoryParent(ProductData $product): ?CategoryListItemData
    {
        $parentCategories = $this->productCategories->whereNull('parent_id')->collect();

        $productCategory = null;
        foreach ($product->categories as $categoryId) {
            /** @var CategoryListItemData|null $productCategory */
            $productCategory = $parentCategories->where('id', '=', $categoryId)->first();
            if ($productCategory instanceof CategoryListItemData) {
                break;
            }
        }

        return $productCategory;
    }

    private function getProductImages(ProductData $product): Collection
    {
        $images = [];
        $productImages = array_merge([$product->preview_image], $product->images->all());
        $default_url = config("media-library.sizes.App\Modules\Catalog\Models\Product.default");
        foreach ($productImages as $productImage) {
            /** Исключение технических изображений */
            if (
                str_contains($productImage->image_url_lg, 'Technical')
                || str_contains($productImage->image_url_lg, $default_url)
            ) {
                continue;
            }
            $images[] = $productImage;
        }

        return new Collection($images);
    }

    private function getDescription(ProductData $product): string
    {
        $desc = $product->name . '.';

        $productMaterial = $this->getProductFeatures($product, FeatureTypeEnum::METAL)->first();
        if ($productMaterial instanceof ProductFeatureData) {
            $desc .= ' Материал: ' . $productMaterial->feature->value . '.';
        }

        $productInsert = $this->getProductFeatures($product, FeatureTypeEnum::INSERT)->first();
        if ($productInsert instanceof ProductFeatureData) {
            $desc .= ' Вставки: ' . $productInsert->feature->value . '.';
        }

        $desc .= ' Большой выбор украшений с натуральными камнями в золоте и серебре от российских производителей.';
        return $desc;
    }

    private function getProductFeatures(ProductData $product, FeatureTypeEnum $featureType): Collection
    {
        $productFeatureData = $product->product_features
            ->where('feature.type', '=', $featureType)
            ->all();
        return new Collection($productFeatureData);
    }

    /** Сопоставление типов изделий юви и Яндекс */
    private function getYandexCategoryName(string $categoryTitle, ?string $material = null): string
    {
        $materialCategories = [
            'Золото' => [
                'Браслеты' => 'Браслет из золота',
                'Броши' => 'Брошь из золота',
                'Колье' => 'Колье из золота',
                'Кольца и перстни' => 'Кольцо из золота',
                'Серьги' => 'Серьги из золота',
                'Цепи' => 'Цепь из золота'
            ],
            'Серебро' => [
                'Браслеты' => 'Браслет из серебра',
                'Броши' => 'Брошь из серебра',
                'Колье' => 'Колье из серебра',
                'Кольца и перстни' => 'Кольцо из серебра',
                'Серьги' => 'Серьги из серебра',
                'Цепи' => 'Цепь из серебра'
            ],
        ];

        $categoryName = match ($categoryTitle) {
            'Браслеты', 'Браслет' => 'Браслеты',
            'Броши', 'Брошь' => 'Броши',
            'Бусы' => 'Бусы',
            'Запонки' => 'Запонки и зажимы',
            'Колье' => 'Колье',
            'Кольцо', 'Кольца' => 'Кольца и перстни',
            'Комплекты', 'Комплект' => 'Комплекты',
            'Подвески', 'Подвеска' => 'Кулоны и подвески',
            'Пирсинг' => 'Пирсинг',
            'Серьги', 'Серьга' => 'Серьги',
            'Цепи', 'Цепь' => 'Цепи',
            'Посуда', 'Посуды', 'Сувенир', 'Сувениры' => 'Ювелирная посуда и сувениры',
            default => 'Другое',
        };

        if (empty($material)) {
            return $categoryName;
        }

        return $materialCategories[$material][$categoryName] ?? $categoryName;
    }

    private function getYandexCategoryIds(?string $categoryName = null): array|int
    {
        $categories = [
            'Браслеты' => 100,
            'Браслет из золота' => 101,
            'Браслет из серебра' => 102,
            'Броши' => 200,
            'Брошь из золота' => 201,
            'Брошь из серебра' => 202,
            'Бусы' => 300,
            'Запонки и зажимы' => 400,
            'Колье' => 500,
            'Колье из золота' => 501,
            'Колье из серебра' => 502,
            'Кольца и перстни' => 600,
            'Кольцо из золота' => 601,
            'Кольцо из серебра' => 602,
            'Кулоны и подвески' => 700,
            'Пирсинг' => 800,
            'Серьги' => 900,
            'Серьги из золота' => 901,
            'Серьги из серебра' => 902,
            'Цепи' => 1000,
            'Цепь из золота' => 1001,
            'Цепь из серебра' => 1002,
            'Ювелирная посуда и сувениры' => 1100,
            'Другое' => 1200
        ];

        if (empty($categoryName)) {
            return $categories;
        }

        return $categories[$categoryName] ?? $categories['Другое'];
    }

    private function getProductMaterialName(ProductData $product): ?string
    {
        $productMaterial = $this->getProductFeatures($product, FeatureTypeEnum::METAL)->first();
        if ($productMaterial instanceof ProductFeatureData) {
            return $productMaterial->feature->value;
        }

        return null;
    }
}
