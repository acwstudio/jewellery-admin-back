<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\CollectionProduct;

use App\Modules\Collections\Models\CollectionProductListItem;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'collections_collection_product_list_item_data', type: 'object')]
class CollectionProductListItemData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'product_id', type: 'integer')]
        public readonly int $product_id,
        #[Property(property: 'collection_id', type: 'integer')]
        public readonly int $collection_id,
        #[Property(property: 'collection_name', type: 'string')]
        public readonly string $collection_name
    ) {
    }

    public static function fromModel(CollectionProductListItem $model): self
    {
        return new self(
            $model->id,
            $model->product_id,
            $model->collection->id,
            $model->collection->name
        );
    }
}
