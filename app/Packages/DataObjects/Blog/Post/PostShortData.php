<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Post;

use App\Modules\Blog\Models\Post;
use App\Modules\Storage\Models\File;
use App\Packages\DataObjects\Blog\Category\CategoryShortData;
use App\Packages\DataObjects\Storage\FileData;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'blog_post_short_data',
    description: 'Blog post short info',
    type: 'object'
)]
class PostShortData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer', example: 1)]
        public readonly int $id,
        #[Property(property: 'category', ref: '#/components/schemas/blog_category_short_data', type: 'object')]
        public readonly CategoryShortData $category,
        #[Property(property: 'slug', type: 'string', example: 'new-post')]
        public readonly string $slug,
        #[Property(property: 'title', type: 'string', example: 'New Post')]
        public readonly string $title,
        #[Property(property: 'preview', ref: '#/components/schemas/storage_file_data', type: 'object', nullable: true)]
        public readonly ?FileData $preview,
        #[Property(property: 'published_at', type: 'string', example: '2023-03-09T10:56:55+00:00', nullable: true)]
        public readonly ?Carbon $published_at = null,
        #[Property(property: 'is_main', type: 'bool', default: false, nullable: false)]
        public readonly bool $is_main = false
    ) {
    }

    public static function fromModel(Post $post): self
    {
        /** @var \App\Modules\Blog\Models\Category $category */
        $category = $post->category()->first();

        return new self(
            $post->id,
            self::getCategoryShortData($post),
            $post->slug,
            $post->title,
            self::getPreviewFileData($post),
            $post->published_at,
            $post->is_main
        );
    }

    private static function getCategoryShortData(Post $post): CategoryShortData
    {
        /** @var \App\Modules\Blog\Models\Category $category */
        $category = $post->category()->first();

        return CategoryShortData::fromModel($category);
    }

    private static function getPreviewFileData(Post $post): ?FileData
    {
        /** @var File|null $preview */
        $preview = $post->preview()->first();

        if (!$preview instanceof File) {
            return null;
        }

        return FileData::fromModel($preview);
    }
}
