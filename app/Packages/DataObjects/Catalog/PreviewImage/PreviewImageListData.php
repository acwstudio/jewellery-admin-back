<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\PreviewImage;

use App\Modules\Catalog\Models\PreviewImage;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'catalog_preview_image_list_data',
    description: 'Коллекция превью изображений',
    required: ['items'],
    type: 'object'
)]
class PreviewImageListData extends ListData
{
    #[Property(property: 'items', type: 'array', items: new Items(ref: '#/components/schemas/catalog_preview_image_data'))]
    #[DataCollectionOf(PreviewImageData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        /** @phpstan-ignore-next-line */
        $items = $paginator->getCollection()->map(
            fn (PreviewImage $previewImage) => PreviewImageData::fromModel($previewImage)
        );

        return new self(
            PreviewImageData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
