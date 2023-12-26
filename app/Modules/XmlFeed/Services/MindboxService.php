<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\Services;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\XmlFeed\Traits\ProductTrait;
use App\Modules\XmlFeed\UseCases\GetCollectionProducts;
use App\Modules\XmlFeed\UseCases\GetLiveProducts;
use App\Modules\XmlFeed\UseCases\GetProductCategories;
use App\Modules\XmlFeed\Contracts\XmlFeedContract;
use App\Packages\DataObjects\Catalog\Category\CategoryListItemData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\ProductFeature\ProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\DataObjects\Collections\CollectionProduct\CollectionProductListItemData;
use App\Packages\DataObjects\Live\LiveProduct\LiveProductListItemData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Carbon\Carbon;
use DOMDocument;
use DOMImplementation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class MindboxService implements XmlFeedContract
{
    use ProductTrait;

    /** @var Collection<CategoryListItemData> */
    private Collection $productCategories;

    /** @var Collection<LiveProductListItemData> */
    private Collection $liveProducts;

    /** @var Collection<CollectionProductListItemData> */
    private Collection $collectionProducts;

    private DOMDocument $xml;
    private string $productUrl;

    public function getDocument(Collection $products, ?callable $onEach = null): DOMDocument
    {
        $this->productCategories = App::call(GetProductCategories::class);
        $this->liveProducts = App::call(GetLiveProducts::class);
        $this->collectionProducts = App::call(GetCollectionProducts::class);

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
            $this->createOffers($offers, $product);

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
        $DOMCategories = $this->xml->createElement('categories');
        $parentCategoryId = 0;

        $parentCategory = $this->xml->createElement('category', 'Все украшения');
        $parentCategory->setAttribute('id', (string)$parentCategoryId);
        $DOMCategories->appendChild($parentCategory);
        /** @var CategoryListItemData $category */
        foreach ($this->productCategories as $category) {
            $item = $this->xml->createElement('category', $category->title);
            $item->setAttribute('id', (string)$category->id);
            $item->setAttribute('parentId', (string)($category->parent_id ?? $parentCategoryId));
            $DOMCategories->appendChild($item);
        }

        return $DOMCategories;
    }

    private function createOffers(\DOMNode $offers, ProductData $product): void
    {
        foreach ($product->trade_offers as $productOffer) {
            if ($this->isAddToFeed($productOffer)) {
                $offers->appendChild($this->createOffer($product, $productOffer));
            }
        }
    }

    private function createOffer(ProductData $product, ProductOfferData $productOffer): \DOMNode
    {
        $offer = $this->xml->createElement('offer');

        $offerId = $product->sku;
        if (!empty($productOffer->size)) {
            $offerId .= '_' . Str::slug($productOffer->size, '_');
        }

        $offer->setAttribute('id', $offerId);
        $offer->setAttribute('available', $this->isAvailable($productOffer));
        $offer->setAttribute('group_id', $product->sku);

        /** CategoryId */
        $this->addCategoryAndTypePrefix($offer, $product);

        /** Url */
        $offer->appendChild(
            $this->xml->createElement('url', $this->productUrl . $product->slug)
        );

        /** Price */
        $this->addPrice($offer, $productOffer);

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

        $this->addProductFeatureParams($offer, $product);
        $this->addProductSizeParam($offer, $productOffer);
        $this->addProductWeightParam($offer, $productOffer);
        $this->addCollectionProductParam($offer, $productOffer);
        $this->addLiveProductParam($offer, $productOffer);

        return $offer;
    }

    private function isAddToFeed(ProductOfferData $productOffer): bool
    {
        $priceRegular = $this->getPriceAmount($productOffer, OfferPriceTypeEnum::REGULAR);
        if (0 === $priceRegular) {
            return false;
        }

        return true;
    }

    private function isAvailable(ProductOfferData $productOfferData): string
    {
        if ($productOfferData->count > 0) {
            return 'true';
        }

        return 'false';
    }

    private function addCategoryAndTypePrefix(\DOMNode $offer, ProductData $product): void
    {
        if (empty($product->categories)) {
            return;
        }

        /** @var int $categoryId */
        foreach ($product->categories as $categoryId) {
            $offer->appendChild($this->xml->createElement('categoryId', (string)$categoryId));
        }

        $parentCategories = $this->getProductCategoryParents($product);

        /** @var CategoryListItemData $parentCategory */
        $parentCategory = $parentCategories->first();
        $offer->appendChild($this->xml->createElement('typePrefix', $parentCategory->title));
        $this->addTypeParam($offer, $parentCategories->pluck('title')->all());
    }

    private function addPrice(\DOMNode $offer, ProductOfferData $productOffer): void
    {
        $prices = $this->getCorrectPriceAmounts($productOffer);

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
        if ($productImages->isEmpty()) {
            return;
        }

        /** @var PreviewImageData $productImage */
        foreach ($productImages->take(1) as $productImage) {
            $url = str_replace(' ', '%20', $productImage->image_url_lg);
            $offer->appendChild($this->xml->createElement('picture', $url));
        }
    }

    private function addProductFeatureParams(\DOMNode $offer, ProductData $product): void
    {
        $productFeatures = $product->product_features
            ->where('feature.type', '!=', FeatureTypeEnum::BOOLEAN)
            ->toCollection();

        if ($productFeatures->isEmpty()) {
            return;
        }

        $features = [];

        /** @var ProductFeatureData $productFeature */
        foreach ($productFeatures as $productFeature) {
            $paramValue = $productFeature->feature->value;
            $name = $productFeature->feature->type->getLabel();
            if (FeatureTypeEnum::DYNAMIC === $productFeature->feature->type) {
                $paramValue = $productFeature->value;
                $name = $productFeature->feature->value;
            }

            $name = $this->convertFeatureName($name);

            if (empty($name)) {
                continue;
            }

            if (!empty($features[$name]) && in_array($paramValue, $features[$name])) {
                continue;
            }

            $features[$name][] = $paramValue;
        }

        foreach ($features as $name => $values) {
            $param = $this->xml->createElement('param', implode('|', $values));
            $param->setAttribute('name', $name);
            $offer->appendChild($param);
        }
    }

    private function addProductSizeParam(\DOMNode $offer, ProductOfferData $productOfferData): void
    {
        if (empty($productOfferData->size)) {
            return;
        }

        $param = $this->xml->createElement('param', $productOfferData->size);
        $param->setAttribute('name', 'Размер');
        $offer->appendChild($param);
    }

    private function addProductWeightParam(\DOMNode $offer, ProductOfferData $productOfferData): void
    {
        if (empty($productOfferData->weight)) {
            return;
        }

        $value = round(floatval($productOfferData->weight), 1);
        $param = $this->xml->createElement('param', (string)$value);
        $param->setAttribute('name', 'Вес');
        $offer->appendChild($param);
    }

    private function addCollectionProductParam(\DOMNode $offer, ProductOfferData $productOfferData): void
    {
        $collections = $this->collectionProducts
            ->where('product_id', '=', $productOfferData->product_id);

        if ($collections->isEmpty()) {
            return;
        }

        $values = $collections->pluck('collection_name')->all();

        $param = $this->xml->createElement('param', implode('|', $values));
        $param->setAttribute('name', 'Коллекция');
        $offer->appendChild($param);
    }

    private function addLiveProductParam(\DOMNode $offer, ProductOfferData $productOfferData): void
    {
        $collections = $this->liveProducts
            ->where('product_id', '=', $productOfferData->product_id);

        if ($collections->isEmpty()) {
            return;
        }

        /** @var Carbon $started_at */
        $started_at = $collections->pluck('started_at')->first();

        $param = $this->xml->createElement('param', $started_at->utc()->format('Y-m-d H:i:s'));
        $param->setAttribute('name', 'Последняя дата эфира');
        $offer->appendChild($param);
    }

    private function addTypeParam(\DOMNode $offer, array $values): void
    {
        if (empty($values)) {
            return;
        }

        $param = $this->xml->createElement('param', implode('|', $values));
        $param->setAttribute('name', 'Тип');
        $offer->appendChild($param);
    }

    /**
     * @param ProductData $product
     * @return Collection<CategoryListItemData>
     */
    private function getProductCategoryParents(ProductData $product): Collection
    {
        $parentCategories = $this->productCategories->whereNull('parent_id')->collect();
        $productCategories = new Collection();

        foreach ($product->categories as $categoryId) {
            $productCategory = $parentCategories->where('id', '=', $categoryId)->first();
            if (null !== $productCategory) {
                $productCategories->add($productCategory);
            }
        }

        return $productCategories;
    }

    private function getProductImages(ProductData $product): Collection
    {
        $images = [];
        $productImages = array_merge([$product->preview_image], $product->images->all());
        $default_url = config("media-library.sizes.App\Modules\Catalog\Models\Product.default");
        foreach ($productImages as $productImage) {
            /** Исключение дефолтных изображений */
            if (str_contains($productImage->image_url_lg, $default_url)) {
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

    private function convertFeatureName(string $name): ?string
    {
        return match ($name) {
            FeatureTypeEnum::METAL->getLabel() => 'Металл',
            FeatureTypeEnum::INSERT->getLabel() => 'Вставка',
            FeatureTypeEnum::METAL_COLOR->getLabel(),
            FeatureTypeEnum::INSERT_COLOR->getLabel() => 'Цвет',
            default => null
        };
    }
}
