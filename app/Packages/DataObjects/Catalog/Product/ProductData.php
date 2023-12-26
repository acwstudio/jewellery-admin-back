<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductVideoUrl;
use App\Packages\DataObjects\Catalog\Brand\BrandData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use App\Packages\DataObjects\Catalog\Product\VideoUrl\ProductVideoUrlData;
use App\Packages\DataObjects\Catalog\ProductFeature\ProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\Enums\LiquidityEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'catalog_product_data',
    description: 'Продукт',
    required: ['id', 'category', 'sku', 'name', 'summary', 'description', 'manufacture_country', 'rank',
        'preview_image', 'is_active'],
    type: 'object'
)]
class ProductData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'external_id', type: 'string', nullable: true)]
        public readonly ?string $external_id,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
        #[Property(
            property: 'categories',
            type: 'array',
            items: new Items(type: 'integer')
        )]
        public readonly array $categories,
        #[Property(property: 'sku', type: 'string')]
        public readonly string $sku,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'summary', type: 'string')]
        public readonly string $summary,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'manufacture_country', type: 'string')]
        public readonly string $manufacture_country,
        #[Property(property: 'rank', type: 'integer')]
        public readonly int $rank,
        #[Property(property: 'preview_image', ref: '#/components/schemas/catalog_preview_image_data', type: 'object')]
        public readonly PreviewImageData $preview_image,
        #[Property(
            property: 'images',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_preview_image_data')
        )]
        #[DataCollectionOf(PreviewImageData::class)]
        public readonly DataCollection $images,
        #[Property(
            property: 'videos',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_video_url_data')
        )]
        #[DataCollectionOf(ProductVideoUrlData::class)]
        public readonly DataCollection $videos,
        #[Property(
            property: 'trade_offers',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_offer_data')
        )]
        #[DataCollectionOf(ProductOfferData::class)]
        public readonly DataCollection $trade_offers,
        #[Property(
            property: 'product_features',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_feature_data')
        )]
        #[DataCollectionOf(ProductFeatureData::class)]
        public readonly DataCollection $product_features,
        #[Property(property: 'catalog_number', type: 'string', nullable: true)]
        public readonly ?string $catalog_number = null,
        #[Property(property: 'supplier', type: 'string', nullable: true)]
        public readonly ?string $supplier = null,
        #[Property(property: 'liquidity', type: 'string', nullable: true)]
        public readonly ?LiquidityEnum $liquidity = null,
        #[Property(property: 'stamp', type: 'float', nullable: true)]
        public readonly ?float $stamp = null,
        #[Property(property: 'meta_title', type: 'string', nullable: true)]
        public readonly ?string $meta_title = null,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description = null,
        #[Property(property: 'meta_keywords', type: 'string', nullable: true)]
        public readonly ?string $meta_keywords = null,
        #[Property(property: 'is_active', type: 'bool', default: true)]
        public readonly bool $is_active = true,
        #[Property(property: 'is_drop_shipping', type: 'bool', nullable: true)]
        public readonly ?bool $is_drop_shipping = null,
        #[Property(property: 'popularity', type: 'integer', nullable: true)]
        public readonly ?int $popularity = null,
        #[Property(property: 'brand', ref: '#/components/schemas/brand_data', type: 'object')]
        public readonly ?BrandData $brand = null,
        #[Property(property: 'on_wishlist', type: 'boolean')]
        public readonly bool $on_wishlist = false,
    ) {
    }

    public static function fromModel(
        Product $product,
        bool $isFullData = false,
        array $wishlist = [],
        array $liveIds = []
    ): self {
        $brand = null;
        $onWishlist = false;
        $isLive = false;

        if ($product->brand) {
            $brand = BrandData::fromModel($product->brand);
        }

        if (in_array($product->id, $wishlist)) {
            $onWishlist = true;
        }

        if (in_array($product->id, $liveIds)) {
            $isLive = true;
        }

        return new self(
            id: $product->id,
            external_id: $product->external_id,
            slug: $product->slug,
            categories: self::getCategoryIds($product),
            sku: $product->sku,
            name: $product->name,
            summary: $product->summary,
            description: $product->description,
            manufacture_country: $product->manufacture_country,
            rank: $product->rank,
            preview_image: self::getPreviewImage($product),
            images: self::getImageDataCollection($product),
            videos: self::getVideoDataCollection($product),
            trade_offers: self::getProductOfferDataCollection($product, $isLive),
            product_features: self::getProductFeatureDataCollection($product, $isFullData),
            catalog_number: $product->catalog_number,
            supplier: $product->supplier,
            liquidity: $product->liquidity,
            stamp: !is_null($product->stamp) ? floatval($product->stamp) : null,
            meta_title: $product->meta_title,
            meta_description: $product->meta_description,
            meta_keywords: $product->meta_keywords,
            is_active: $product->is_active,
            is_drop_shipping: $product->is_drop_shipping,
            popularity: $product->popularity,
            brand: $brand,
            on_wishlist: $onWishlist
        );
    }

    public static function customFromArray(
        array $product,
        bool $isFullData = false,
        array $wishlist = [],
        array $liveIds = []
    ): self {
        $brand = null;
        $onWishlist = false;
        $isLive = false;
        if (isset($product['brand'])) {
            $brand = BrandData::customFromArray($product['brand']);
        }

        if (isset($product['id']) && in_array($product['id'], $wishlist, true)) {
            $onWishlist = true;
        }

        if (isset($product['id']) && in_array($product['id'], $liveIds, true)) {
            $isLive = true;
        }

        return new self(
            id: $product['id'] ?? null,
            external_id: $product['external_id'] ?? null,
            slug: $product['slug'],
            categories: self::getCategoryIdsByArray($product['categories'] ?? []),
            sku: $product['sku'],
            name: $product['name'],
            summary: $product['summary'],
            description: $product['description'],
            manufacture_country: $product['manufacture_country'],
            rank: $product['rank'],
            preview_image: self::getPreviewImageDataFromArray($product),
            images: self::getImageDataCollectionFromArray($product),
            videos: self::getVideoDataCollectionFromArray($product),
            trade_offers: self::getProductOfferFromArray($product['product_offers'] ?? [], $isLive),
            product_features: self::getProductFeatureDataCollectionFromArray($product, $isFullData),
            catalog_number: $product['catalog_number'] ?? null,
            supplier: $product['supplier'] ?? null,
            liquidity: !empty($product['liquidity']) ? LiquidityEnum::tryFrom($product['liquidity']) : null,
            stamp: !empty($product['stamp']) ? (float)$product['stamp'] : null,
            meta_title: $product['meta_title'] ?? null,
            meta_description: $product['meta_description'] ?? null,
            meta_keywords: $product['meta_keywords'] ?? null,
            is_active: $product['is_active'],
            is_drop_shipping: $product['is_drop_shipping'] ?? null,
            popularity: $product['popularity'] ?? null,
            brand: $brand,
            on_wishlist: $onWishlist
        );
    }


    private static function getPreviewImage(Product $product): PreviewImageData
    {
        /** @var ProductImageUrl|null $mainImageUrl */
        $mainImageUrl = $product->imageUrls->where('is_main', '=', true)->first();

        if (!$product->previewImage instanceof PreviewImage && $mainImageUrl instanceof ProductImageUrl) {
            return PreviewImageData::fromProductImageUrl($mainImageUrl);
        }

        return PreviewImageData::fromModel($product->previewImage);
    }

    private static function getProductOfferDataCollection(Product $product, bool $isLive = false): DataCollection
    {
        $productOffers = $product->productOffers;

        $items = $productOffers->map(
            fn (ProductOffer $productOffer) => ProductOfferData::fromModel(
                $productOffer,
                isActive: true,
                isLive: $isLive
            )
        );

        return ProductOfferData::collection($items->flatten());
    }

    private static function getPreviewImageDataCollection(Product $product): DataCollection
    {
        $productImages = $product->images;

        $items = $productImages->map(
            fn (PreviewImage $previewImage) => PreviewImageData::fromModel($previewImage)
        );

        return PreviewImageData::collection($items->flatten());
    }

    private static function getProductFeatureDataCollection(Product $product, bool $isFullData = false): DataCollection
    {
        if (!$isFullData) {
            return ProductFeatureData::collection([]);
        }
        $productFeatures = $product->productFeatures->whereNull('parent_uuid');

        $items = $productFeatures->map(
            fn (ProductFeature $model) => ProductFeatureData::fromModel($model)
        );

        return ProductFeatureData::collection($items->flatten());
    }

    private static function getImageDataCollection(Product $product): DataCollection
    {
        if ($product->images->count() > 0) {
            return self::getPreviewImageDataCollection($product);
        }

        $productImageUrls = $product->imageUrls->where('is_main', '=', false);

        $items = $productImageUrls->map(
            fn (ProductImageUrl $imageUrl) => PreviewImageData::fromProductImageUrl($imageUrl)
        );

        return PreviewImageData::collection($items->flatten());
    }

    private static function getVideoDataCollection(Product $product): DataCollection
    {
        $productVideoUrls = $product->videoUrls;

        $items = $productVideoUrls->map(
            fn (ProductVideoUrl $videoUrl) => ProductVideoUrlData::fromModel($videoUrl)
        );

        return ProductVideoUrlData::collection($items->flatten());
    }

    private static function getCategoryIds(Product $product): array
    {
        return $product->categories->pluck('id')->toArray();
    }

    private static function getProductOfferFromArray(array $productOffers, bool $isLive = false): DataCollection
    {
        $items = array_map(
            static fn ($productOffer) => ProductOfferData::customFromArray(
                $productOffer,
                true,
                $isLive
            ),
            $productOffers
        );

        return ProductOfferData::collection($items);
    }

    private static function getImageDataCollectionFromArray(array $product): DataCollection
    {
        if (count($product['images'] ?? []) > 0) {
            return self::getPreviewImageDataCollectionFromArray($product);
        }

        $productImageUrls = collect($product['image_urls'] ?? []);
        $productImageUrls = $productImageUrls->where('is_main', '=', false);

        /** @var Collection $items */
        $items = $productImageUrls->map(
            fn (array $previewImage) => PreviewImageData::customFromArrayProductImageUrl($previewImage)
        );

        return PreviewImageData::collection($items->flatten());
    }

    private static function getVideoDataCollectionFromArray(array $product): DataCollection
    {
        $productVideoUrls = collect($product['video_urls'] ?? []);

        /** @var Collection $items */
        $items = $productVideoUrls->map(
            fn (array $previewImage) => ProductVideoUrlData::customFromArrayProductVideoUrl($previewImage)
        );

        return ProductVideoUrlData::collection($items->flatten());
    }

    private static function getPreviewImageDataCollectionFromArray(array $product): DataCollection
    {
        $productImages = $product['images'] ?? [];

        $items = array_map(
            fn ($previewImage) => PreviewImageData::customFromArray($previewImage),
            $productImages
        );

        return PreviewImageData::collection($items);
    }

    private static function getProductFeatureDataCollectionFromArray(
        array $features,
        bool $isFullData = null
    ): DataCollection {
        $features = $features['product_features'] ?? [];
        if (!$isFullData) {
            return ProductFeatureData::collection([]);
        }

        $features = Arr::where($features, static fn($value) => $value['parent_uuid'] === null);
        $features = Arr::map($features, static fn($feature) => ProductFeatureData::customFromArray($feature));

        return ProductFeatureData::collection($features);
    }

    private static function getPreviewImageDataFromArray(array $product): PreviewImageData
    {
        $previewImage = $product['preview_image'] ?? [];

        $imageUrlsCollection = collect($product['image_urls']);
        $mainImageUrl = $imageUrlsCollection->where('is_main', '=', true)->first();

        if (empty($previewImage) && !empty($mainImageUrl)) {
            return PreviewImageData::customFromArrayProductImageUrl($mainImageUrl);
        }

        return PreviewImageData::customFromArray($previewImage);
    }

    private static function getCategoryIdsByArray(array $categories): array
    {
        $categories = collect($categories);
        return $categories->pluck('id')->toArray();
    }
}
