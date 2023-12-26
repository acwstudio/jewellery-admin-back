<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\ShopCart\ShopCartItem;

use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(schema: 'shop_cart_item_data', type: 'object')]
class ShopCartItemData extends Data
{
    public function __construct(
        #[Property(property: 'product_id', title: 'Идентификатор продукта', type: 'integer')]
        public readonly int $product_id,
        #[Property(property: 'product_offer_id', title: 'Идентификатор торгового предложения', type: 'integer')]
        public readonly int $product_offer_id,
        #[Property(property: 'size', title: 'Размер', type: 'string', nullable: true)]
        public readonly ?string $size,
        #[Property(property: 'count', title: 'Количество', type: 'integer')]
        public readonly int $count,
        #[Property(property: 'sku', type: 'string')]
        public readonly string $sku,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'selected', title: 'Выбран для оформления', type: 'boolean')]
        public readonly bool $selected,
        #[Property(property: 'preview_image', ref: '#/components/schemas/catalog_preview_image_data', type: 'object')]
        public readonly PreviewImageData $preview_image,
        #[Property(
            property: 'prices',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_offer_price_data')
        )]
        #[DataCollectionOf(ProductOfferPriceData::class)]
        public readonly DataCollection $prices,
        #[Property('external_id', type: 'string')]
        public readonly string $external_id,
        #[Property('slug', type: 'string')]
        public readonly string $slug
    ) {
    }

    public static function fromModel(ShopCartItem $shopCartItem): self
    {
        return new self(
            $shopCartItem->product->id,
            $shopCartItem->productOffer->id,
            $shopCartItem->productOffer->size,
            $shopCartItem->count,
            $shopCartItem->productOffer->product->sku,
            $shopCartItem->productOffer->product->name,
            $shopCartItem->selected,
            self::getProductPreviewImageData($shopCartItem->productOffer),
            self::getProductOfferPriceDataCollection($shopCartItem->productOffer),
            $shopCartItem->product->external_id,
            $shopCartItem->product->slug
        );
    }

    private static function getProductOfferPriceDataCollection(ProductOffer $productOffer): DataCollection
    {
        /** @var \Illuminate\Support\Collection<ProductOfferPrice> $productOfferPrices */
        $productOfferPrices = $productOffer->productOfferPrices()
            ->getQuery()
            ->whereNot('type', '=', OfferPriceTypeEnum::EMPLOYEE)
            ->where('is_active', '=', true)
            ->get();

        $productOfferPriceCollection = $productOfferPrices->map(
            fn (ProductOfferPrice $price) => ProductOfferPriceData::fromModel($price)
        );

        return ProductOfferPriceData::collection($productOfferPriceCollection);
    }

    private static function getProductPreviewImageData(ProductOffer $productOffer): PreviewImageData
    {
        /** @var ProductImageUrl|null $mainImageUrl */
        $mainImageUrl = $productOffer->product
            ->imageUrls()
            ->getQuery()
            ->where('is_main', '=', true)
            ->first();

        if (
            !$productOffer->product->previewImage instanceof PreviewImage
            && $mainImageUrl instanceof ProductImageUrl
        ) {
            return PreviewImageData::fromProductImageUrl($mainImageUrl);
        }

        return PreviewImageData::fromModel($productOffer->product->previewImage);
    }
}
