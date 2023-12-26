<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Seo;

use App\Modules\Catalog\Models\Seo;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_create_seo_data',
    description: 'Создание SEO',
    required: ['category_id', 'filters', 'h1'],
    type: 'object'
)]
class CreateSeoData extends Data
{
    public function __construct(
        #[Property(property: 'category_id', type: 'integer')]
        public readonly int $category_id,
        #[Property(property: 'filter', ref: '#/components/schemas/catalog_filter_product_data', nullable: true)]
        public readonly FilterProductData $filter,
        #[Property(property: 'h1', type: 'string')]
        public readonly string $h1,
        #[Property(property: 'url', type: 'string')]
        #[Unique(Seo::class, 'url')]
        public readonly string $url,
        #[Property(property: 'parent_id', type: 'integer', nullable: true)]
        public readonly ?int $parent_id = null,
        #[Property(property: 'meta_title', type: 'string', nullable: true)]
        public readonly ?string $meta_title = null,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description = null,
    ) {
    }
}
