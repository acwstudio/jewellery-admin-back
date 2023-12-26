<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Seo\Filter;

use App\Packages\DataCasts\CollectionCast;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_seo_filter_data',
    description: 'Фильтр SEO',
    type: 'object'
)]
class SeoFilterData extends Data
{
    public function __construct(
        #[WithCast(CollectionCast::class)]
        public readonly ?Collection $id = null,
        public readonly ?string $url = null
    ) {
    }
}
