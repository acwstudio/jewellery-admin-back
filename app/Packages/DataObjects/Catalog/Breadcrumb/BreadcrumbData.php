<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Breadcrumb;

use App\Modules\Catalog\Models\Category;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'breadcrumb_data',
    description: 'Create category',
    type: 'object'
)]
class BreadcrumbData extends Data
{
    public function __construct(
        #[Property(property: 'category_id', type: 'integer')]
        public readonly int $category_id,
        #[Property(property: 'parent_id', type: 'integer', nullable: true)]
        public readonly ?int $parent_id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug
    ) {
    }

    public static function fromCategory(Category $category): self
    {
        return new self(
            $category->id,
            $category->parent?->id,
            $category->title,
            $category->slug
        );
    }
}
