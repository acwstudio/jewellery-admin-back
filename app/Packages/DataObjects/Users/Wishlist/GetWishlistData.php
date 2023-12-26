<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\Wishlist;

use App\Modules\Users\Enums\WishlistSortColumnEnum;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\Enums\SortOrderEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'users_get_wishlist_data',
    description: 'Получение коллекции продуктов',
    type: 'object'
)]
class GetWishlistData extends Data
{
    public function __construct(
        #[Property(
            property: 'sort_by',
            ref: '#/components/schemas/users_wishlist_sort_column',
            type: 'string',
            nullable: true
        )]
        #[RequiredWith('sort_order')]
        public readonly ?WishlistSortColumnEnum $sort_by = null,
        #[Property(property: 'sort_order', ref: '#/components/schemas/sort_order', type: 'string', nullable: true)]
        #[RequiredWith('sort_by')]
        public readonly ?SortOrderEnum $sort_order = null,
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination = null,
    ) {
    }
}
