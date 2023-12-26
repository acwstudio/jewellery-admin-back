<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Category;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'blog_update_category_data',
    description: 'Update blog category',
    type: 'object'
)]
class UpdateCategoryData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer', example: 1)]
        public int $id,
        #[Property(property: 'slug', type: 'string', example: 'new-category')]
        public readonly string $slug,
        #[Property(property: 'name', type: 'string', example: 'New Category')]
        public readonly string $name,
        #[Property(property: 'position', description: 'Category position in the list', type: 'integer', example: 10, nullable: true)]
        public readonly ?int $position,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description,
    ) {
    }
}
