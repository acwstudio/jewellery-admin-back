<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category\Slug;

use App\Modules\Catalog\Rules\CatalogSlugAliasRule;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

#[Schema(
    schema: 'create_alias_data',
    description: 'Create CategorySlugAlias',
    type: 'object'
)]
class CreateCategorySlugAliasData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'slug', type: 'string')]
        #[Required, StringType, Rule(new CatalogSlugAliasRule())]
        public readonly string $slug,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
