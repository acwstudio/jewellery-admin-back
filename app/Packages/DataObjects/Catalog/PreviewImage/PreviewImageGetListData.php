<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\PreviewImage;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_preview_image_get_list_data',
    description: 'Получение коллекции превью изображений',
    type: 'object'
)]
class PreviewImageGetListData extends Data
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination
    ) {
    }
}
