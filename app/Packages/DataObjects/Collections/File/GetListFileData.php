<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\File;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'collections_get_list_file_data',
    description: 'Получение списка изображений коллекций',
    type: 'object'
)]
class GetListFileData extends Data
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination
    ) {
    }
}
