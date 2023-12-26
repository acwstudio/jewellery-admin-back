<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Filter;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'catalog_filter_feature_data', type: 'object')]
class FilterFeatureData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'type',
            description: 'Тип свойства',
            type: 'string',
            nullable: true
        )]
        public readonly ?FeatureTypeEnum $type,
        #[Property(
            property: 'value',
            description: 'Значение свойства',
            type: 'string',
            nullable: true
        )]
        public readonly ?string $value
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
