<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category\Slug;

use App\Modules\Catalog\Models\CategorySlugAlias;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'category_alias_data',
    description: 'Create CategorySlugAlias',
    type: 'object'
)]
class CategorySlugAliasData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
    ) {
    }


    public static function fromModel(CategorySlugAlias $alias): self
    {
        return new self(
            $alias->id,
            $alias->slug
        );
    }
}
