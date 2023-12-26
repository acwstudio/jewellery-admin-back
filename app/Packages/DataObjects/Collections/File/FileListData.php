<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\File;

use App\Modules\Collections\Models\File;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'collections_file_list_data',
    description: 'Список изображений коллекции',
    required: ['items'],
    type: 'object'
)]
class FileListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/collections_file_data')
    )]
    #[DataCollectionOf(FileData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (File $file) => FileData::fromModel($file),
            $paginator->items()
        );

        return new self(
            FileData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
