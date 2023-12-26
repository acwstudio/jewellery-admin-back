<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Category;

use App\Modules\Blog\Models\Category;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'blog_category_short_data',
    description: 'Blog category short info',
    type: 'object'
)]
class CategoryShortData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer', example: 1)]
        public readonly int $id,
        #[Property(property: 'slug', type: 'string', example: 'new-category')]
        public readonly string $slug,
        #[Property(property: 'name', type: 'string', example: 'New Category')]
        public readonly string $name
    ) {
    }

    public static function fromModel(Category $category): self
    {
        return new self(
            $category->id,
            $category->slug,
            $category->name
        );
    }
}
