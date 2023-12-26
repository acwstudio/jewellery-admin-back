<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Category;

use App\Modules\Blog\Models\Category;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'blog_category_list_data',
    description: 'List of blog categories',
    type: 'object'
)]
class CategoryListData extends ListData
{
    #[Property(property: 'items', type: 'array', items: new Items(ref: '#/components/schemas/blog_category_short_data'))]
    #[DataCollectionOf(CategoryShortData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (Category $category) => CategoryShortData::fromModel($category),
            $paginator->items()
        );

        return new self(
            CategoryShortData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
