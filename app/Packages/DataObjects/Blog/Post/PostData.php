<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Post;

use App\Modules\Blog\Models\Post;
use App\Modules\Storage\Models\File;
use App\Packages\DataObjects\Blog\Category\CategoryShortData;
use App\Packages\DataObjects\Storage\FileData;
use App\Packages\Enums\PostStatusEnum;
use Carbon\Carbon;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'blog_post_data',
    description: 'Blog post',
    type: 'object'
)]
class PostData extends Data
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
        #[Property(property: 'content', type: 'string')]
        public readonly string $content,
        #[Property(property: 'status')]
        public readonly PostStatusEnum $status,
        #[Property(property: 'image', ref: '#/components/schemas/storage_file_data', type: 'object', nullable: true)]
        public readonly ?FileData $image,
        #[Property(property: 'preview', ref: '#/components/schemas/storage_file_data', type: 'object', nullable: true)]
        public readonly ?FileData $preview,
        #[Property(property: 'published_at', type: 'string', example: '2023-03-09T10:56:55+00:00', nullable: true)]
        public readonly ?Carbon $published_at,
        #[Property(property: 'meta_title', type: 'string', nullable: true)]
        public readonly ?string $meta_title,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description,
        #[Property(property: 'related_posts', type: 'array', items: new Items(ref: '#/components/schemas/blog_post_short_data'))]
        #[DataCollectionOf(PostShortData::class)]
        public readonly DataCollection $related_posts,
        #[Property(property: 'is_main', type: 'bool', default: false, nullable: false)]
        public readonly bool $is_main = false
    ) {
    }

    public static function fromModel(Post $post): self
    {
        return new self(
            $post->id,
            self::getCategoryShortData($post),
            $post->slug,
            $post->title,
            $post->content,
            $post->status,
            self::getImageFileData($post),
            self::getPreviewFileData($post),
            $post->published_at,
            $post->meta_title,
            $post->meta_description,
            self::getRelatedPostDataCollection($post),
            $post->is_main
        );
    }

    private static function getCategoryShortData(Post $post): CategoryShortData
    {
        /** @var \App\Modules\Blog\Models\Category $category */
        $category = $post->category()->first();

        return CategoryShortData::fromModel($category);
    }

    private static function getImageFileData(Post $post): ?FileData
    {
        /** @var File|null $image */
        $image = $post->image()->first();

        if (!$image instanceof File) {
            return null;
        }

        return FileData::fromModel($image);
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

    private static function getRelatedPostDataCollection(Post $post): DataCollection
    {
        $relatedPosts = $post->relatedPosts()->getResults();

        $postShortDataList = array_map(
            fn (Post $relatedPost) => PostShortData::fromModel($relatedPost),
            $relatedPosts->all()
        );

        return PostShortData::collection($postShortDataList);
    }
}
