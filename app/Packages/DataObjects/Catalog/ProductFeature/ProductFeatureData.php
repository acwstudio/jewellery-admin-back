<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductFeature;

use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\ProductFeature;
use App\Packages\DataObjects\Catalog\Feature\FeatureData;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'catalog_product_feature_data',
    description: 'Свойство продукта',
    required: ['feature_id'],
    type: 'object'
)]
class ProductFeatureData extends Data
{
    public function __construct(
        #[Property(property: 'uuid', type: 'string')]
        public readonly string $uuid,
        #[Property(property: 'feature', ref: '#/components/schemas/catalog_feature_data', type: 'object')]
        public readonly FeatureData $feature,
        #[Property(
            property: 'children',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_feature_data')
        )]
        #[DataCollectionOf(ProductFeatureData::class)]
        public readonly DataCollection $children,
        #[Property(property: 'is_main', type: 'boolean')]
        public readonly bool $is_main,
        #[Property(property: 'value', type: 'string', nullable: true)]
        public readonly ?string $value = null
    ) {
    }

    public static function fromModel(ProductFeature $productFeature): self
    {
        return new self(
            $productFeature->uuid,
            self::getFeatureData($productFeature),
            self::getChildrenDataCollection($productFeature),
            $productFeature->is_main,
            $productFeature->value
        );
    }

    public static function customFromArray(array $productFeature): self
    {
        return new self(
            $productFeature['uuid'],
            self::getFeatureDataFromArray($productFeature),
            self::getChildrenDataCollectionFromArray($productFeature),
            $productFeature['is_main'],
            $productFeature['value']
        );
    }

    private static function getFeatureData(ProductFeature $productFeature): ?FeatureData
    {
        if (!$productFeature->feature instanceof Feature) {
            return null;
        }
        return FeatureData::fromModel($productFeature->feature);
    }

    private static function getChildrenDataCollection(ProductFeature $productFeature): DataCollection
    {
        $childrenProductFeatures = $productFeature->children;

        $items = $childrenProductFeatures->map(
            fn (ProductFeature $childrenProductFeature) => ProductFeatureData::fromModel($childrenProductFeature)
        );

        return self::collection($items->toArray());
    }

    private static function getFeatureDataFromArray(array $productFeature): ?FeatureData
    {
        if (!isset($productFeature['feature'])) {
            return null;
        }
        return FeatureData::customFromArray($productFeature['feature']);
    }

    private static function getChildrenDataCollectionFromArray(array $productFeature): DataCollection
    {
        $childrenProductFeatures = $productFeature['children'];

        $items = array_map(
            static fn($childrenProductFeature) => ProductFeatureData::customFromArray($childrenProductFeature),
            $childrenProductFeatures
        );

        return self::collection($items);
    }
}
