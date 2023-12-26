<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Category;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'blog_create_category_data',
    description: 'Create blog category',
    type: 'object'
)]
class CreateCategoryData extends Data
{
    public function __construct(
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
