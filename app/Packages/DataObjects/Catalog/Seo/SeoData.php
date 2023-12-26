<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Seo;

use App\Modules\Catalog\Models\Seo;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_seo_data',
    description: 'SEO',
    required: ['id', 'category_id', 'filter', 'h1'],
    type: 'object'
)]
class SeoData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'parent_id', type: 'integer', nullable: true)]
        public readonly ?int $parent_id,
        #[Property(property: 'category_id', type: 'integer')]
        public readonly int $category_id,
        #[Property(property: 'filter', ref: '#/components/schemas/catalog_filter_product_data', nullable: true)]
        public readonly FilterProductData $filter,
        #[Property(property: 'h1', type: 'string')]
        public readonly string $h1,
        #[Property(property: 'url', type: 'string', nullable: true)]
        public readonly ?string $url = null,
        #[Property(property: 'meta_title', type: 'string', nullable: true)]
        public readonly ?string $meta_title = null,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description = null,
    ) {
    }

    public static function fromModel(Seo $seo): self
    {
        return new self(
            $seo->id,
            $seo->parent_id,
            $seo->category_id,
            FilterProductData::from($seo->filters),
            $seo->h1,
            $seo->url,
            $seo->meta_title,
            $seo->meta_description
        );
    }
}
