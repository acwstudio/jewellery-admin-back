<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category;

use App\Modules\Catalog\Models\Category;
use App\Packages\Rules\NotEqual;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'update_category_data',
    type: 'object'
)]
class UpdateCategoryData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        public readonly int $id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'h1', type: 'string')]
        public readonly string $h1,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'meta_title', type: 'string', nullable: true)]
        public readonly ?string $meta_title = null,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description = null,
        #[Property(property: 'meta_keywords', type: 'string', nullable: true)]
        public readonly ?string $meta_keywords = null,
        #[Property(property: 'parent_id', type: 'integer', nullable: true)]
        #[Nullable, Rule(new NotEqual('id'))]
        public readonly ?int $parent_id = null,
        #[Property(property: 'external_id', type: 'string', nullable: true)]
        #[Unique(Category::class)]
        public readonly ?string $external_id = null,
        #[Property(property: 'slug', type: 'string', nullable: true)]
        #[Unique(Category::class)]
        public readonly ?string $slug = null,
        #[Property(property: 'preview_image_id', type: 'integer', nullable: true)]
        public readonly ?int $preview_image_id = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
