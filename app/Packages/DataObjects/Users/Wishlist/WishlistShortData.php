<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\Wishlist;

use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'users_wishlist_short_data',
    description: 'Краткая информация по списку избранного пользователя',
    type: 'object'
)]
class WishlistShortData extends Data
{
    public function __construct(
        #[Property(property: 'count', type: 'integer')]
        public readonly int $count
    ) {
    }
}
