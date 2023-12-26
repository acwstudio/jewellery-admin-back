<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Favorite;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'get_list_favorite_data',
    description: 'Получение списка избранных коллекций',
    type: 'object'
)]
class GetListFavoriteData extends Data
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination
    ) {
    }
}
