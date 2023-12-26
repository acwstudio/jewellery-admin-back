<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\CollectionImageUrl;

use App\Modules\Collections\Enums\CollectionImageUrlTypeEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'collections_create_collection_image_url_data',
    type: 'object'
)]
class CreateCollectionImageUrlData extends Data
{
    public function __construct(
        #[Property(property: 'collection_id', type: 'integer')]
        public readonly int $collection_id,
        #[Property(property: 'path', type: 'string')]
        public readonly string $path,
        #[Property(property: 'type', type: 'string')]
        public readonly CollectionImageUrlTypeEnum $type
    ) {
    }
}
