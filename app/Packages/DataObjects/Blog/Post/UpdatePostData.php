<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Post;

use App\Packages\Enums\PostStatusEnum;
use Carbon\Carbon;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

#[Schema(
    schema: 'blog_update_post_data',
    description: 'Update blog post',
    type: 'object'
)]
class UpdatePostData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer', example: 1)]
        public readonly int $id,
        #[Property(property: 'category_id', type: 'integer', example: 5)]
        public readonly int $category_id,
        #[Property(property: 'slug', type: 'string', example: 'new-post')]
        public readonly string $slug,
        #[Property(property: 'title', type: 'string', example: 'New Post')]
        public readonly string $title,
        #[Property(property: 'content', type: 'string')]
        public readonly string $content,
        #[Property(property: 'status')]
        public readonly PostStatusEnum $status,
        #[Property(property: 'image_id', type: 'integer', example: 2, nullable: true)]
        public readonly ?int $image_id,
        #[Property(property: 'preview_id', type: 'integer', example: 4, nullable: true)]
        public readonly ?int $preview_id,
        #[Property(property: 'published_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00', nullable: true)]
        public readonly ?Carbon $published_at,
        #[Property(property: 'meta_title', type: 'string', nullable: true)]
        public readonly ?string $meta_title,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description,
        #[Property(property: 'related_posts', type: 'array', items: new Items(type: 'integer'), nullable: true)]
        public readonly array|Optional $related_posts = [],
        #[Property(property: 'is_main', type: 'bool', default: false, nullable: false)]
        public readonly bool $is_main = false
    ) {
    }
}
