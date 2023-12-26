<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Collection;

use App\Modules\Collections\Enums\CollectionImageUrlTypeEnum;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Modules\Collections\Models\File;
use App\Modules\Collections\Models\Stone;
use App\Modules\Collections\UseCases\GetCollectionProducts;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Collections\File\FileData;
use App\Packages\DataObjects\Collections\Stone\StoneData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(schema: 'collections_collection_data', type: 'object')]
class CollectionData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(
            property: 'preview_image',
            ref: '#/components/schemas/collections_file_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?FileData $preview_image,
        #[Property(
            property: 'preview_image_mob',
            ref: '#/components/schemas/collections_file_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?FileData $preview_image_mob,
        #[Property(
            property: 'banner_image',
            ref: '#/components/schemas/collections_file_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?FileData $banner_image,
        #[Property(
            property: 'banner_image_mob',
            ref: '#/components/schemas/collections_file_data',
            type: 'object',
            nullable: true
        )]
        public readonly FileData $banner_image_mob,
        #[Property(
            property: 'stones',
            type: 'array',
            items: new Items(ref: '#/components/schemas/collections_stone_data')
        )]
        #[DataCollectionOf(StoneData::class)]
        public readonly DataCollection $stones,
        #[Property(
            property: 'products',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_data')
        )]
        #[DataCollectionOf(ProductData::class)]
        public readonly DataCollection $products,
        #[Property(
            property: 'categories',
            type: 'array',
            items: new Items(type: 'integer')
        )]
        public readonly array $categories,
        #[Property(
            property: 'images',
            type: 'array',
            items: new Items(ref: '#/components/schemas/collections_file_data')
        )]
        #[DataCollectionOf(FileData::class)]
        public readonly DataCollection $images,
        #[Property(
            property: 'extended_image',
            ref: '#/components/schemas/collections_file_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?FileData $extended_image = null,
        #[Property(property: 'extended_name', type: 'string', nullable: true)]
        public readonly ?string $extended_name = null,
        #[Property(property: 'extended_description', type: 'string', nullable: true)]
        public readonly ?string $extended_description = null,
    ) {
    }

    public static function fromModel(CollectionModel $collection): self
    {
        return new self(
            id: $collection->id,
            slug: $collection->slug,
            name: $collection->name,
            description: $collection->description,
            preview_image: self::getImage($collection, CollectionImageUrlTypeEnum::PREVIEW),
            preview_image_mob: self::getImage($collection, CollectionImageUrlTypeEnum::PREVIEW_MOB),
            banner_image: self::getImage($collection, CollectionImageUrlTypeEnum::BANNER),
            banner_image_mob: self::getImage($collection, CollectionImageUrlTypeEnum::BANNER_MOB),
            stones: self::getStoneDataCollection($collection),
            products: self::getProductDataCollection($collection),
            categories: $collection->categories()->allRelatedIds()->all(),
            images: self::getFileDataCollection($collection),
            extended_image: self::getImage($collection, CollectionImageUrlTypeEnum::EXTENDED_PREVIEW),
            extended_name: $collection->extended_name,
            extended_description: $collection->extended_description,
        );
    }

    private static function getStoneDataCollection(CollectionModel $collection): DataCollection
    {
        $stones = $collection->stones;

        $items = $stones->map(
            fn (Stone $stone) => StoneData::fromModel($stone)
        );

        return StoneData::collection($items);
    }

    private static function getProductDataCollection(CollectionModel $collection): DataCollection
    {
        if ($collection->products()->allRelatedIds()->count() === 0) {
            return ProductData::collection([]);
        }

        /** @var Collection<ProductData> $products */
        $products = App::call(GetCollectionProducts::class, [
            'collection' => $collection,
            'limit' => config('collections.products.limit')
        ]);

        return new DataCollection(ProductData::class, $products);
    }

    private static function getFileDataCollection(CollectionModel $collection): DataCollection
    {
        $images = $collection->images;

        $items = $images->map(
            fn (File $file) => FileData::fromModel($file)
        );

        return FileData::collection($items);
    }

    private static function getImage(CollectionModel $collection, CollectionImageUrlTypeEnum $type): ?FileData
    {
        $imageUrl = $collection->imageUrls->where('type', '=', $type)->first();

        if (null === $imageUrl) {
            return self::getFileData($collection, $type);
        }

        return FileData::fromCollectionImageUrl($imageUrl);
    }

    private static function getFileData(CollectionModel $collection, CollectionImageUrlTypeEnum $type): ?FileData
    {
        $file = match ($type) {
            CollectionImageUrlTypeEnum::PREVIEW => $collection->previewImage,
            CollectionImageUrlTypeEnum::PREVIEW_MOB => $collection->previewImageMob,
            CollectionImageUrlTypeEnum::BANNER => $collection->bannerImage,
            CollectionImageUrlTypeEnum::BANNER_MOB => $collection->bannerImageMob,
            CollectionImageUrlTypeEnum::EXTENDED_PREVIEW => $collection->extendedImage
        };

        if (null === $file) {
            return null;
        }

        return FileData::fromModel($file);
    }
}
