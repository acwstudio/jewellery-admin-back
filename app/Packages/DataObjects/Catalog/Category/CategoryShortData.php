<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category;

use App\Modules\Catalog\Models\Category;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_category_short_data',
    description: 'Категория с минимальными данными',
    type: 'object'
)]
class CategoryShortData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'slug', type: 'string', nullable: true)]
        public readonly string $slug,
    ) {
    }

    public static function fromModel(Category $category): self
    {
        return new self(
            $category->getKey(),
            $category->title,
            $category->slug
        );
    }
}
