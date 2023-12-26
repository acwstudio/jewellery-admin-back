<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Post;

use App\Modules\Blog\Models\Post;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'blog_post_list_data',
    description: 'List of blog posts',
    type: 'object'
)]
class PostListData extends ListData
{
    #[Property(property: 'items', type: 'array', items: new Items(ref: '#/components/schemas/blog_post_short_data'))]
    #[DataCollectionOf(PostShortData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (Post $post) => PostShortData::fromModel($post),
            $paginator->items()
        );

        return new self(
            PostShortData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
