<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_create_feature_data',
    description: 'Создание свойства',
    required: ['type', 'value'],
    type: 'object'
)]
class CreateFeatureData extends Data
{
    public function __construct(
        #[Property(property: 'type', type: 'string')]
        public readonly FeatureTypeEnum $type,
        #[Property(property: 'value', type: 'string')]
        public readonly string $value,
        #[Property(property: 'slug', type: 'string', nullable: true)]
        #[
            Nullable,
            Unique(Feature::class, 'slug')
        ]
        public readonly ?string $slug = null,
        #[Property(property: 'position', type: 'integer', nullable: true)]
        public readonly ?int $position = null,
    ) {
    }
}
