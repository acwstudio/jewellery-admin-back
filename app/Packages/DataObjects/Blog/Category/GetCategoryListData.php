<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Blog\Category;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Common\Sort\SortData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'blog_get_category_list_data',
    description: 'Get a list of blog categories',
    type: 'object'
)]
class GetCategoryListData extends Data
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination,
        #[Property(property: 'sort', ref: '#/components/schemas/sort_data', nullable: true)]
        public readonly ?SortData $sort,
    ) {
    }
}
