<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_feature_data',
    description: 'Свойство',
    required: ['id', 'type', 'name_type', 'value'],
    type: 'object'
)]
class FeatureData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'type', type: 'string')]
        public readonly FeatureTypeEnum $type,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'value', type: 'string')]
        public readonly string $value,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
    ) {
    }

    public static function fromModel(Feature $feature): self
    {
        return new self(
            $feature->id,
            $feature->type,
            $feature->type->getLabel(),
            $feature->value,
            $feature->slug
        );
    }

    public static function customFromArray(array $feature): self
    {
        $type = FeatureTypeEnum::tryFrom($feature['type']);
        return new self(
            $feature['id'],
            $type,
            $type->getLabel(),
            $feature['value'],
            $feature['slug']
        );
    }
}
