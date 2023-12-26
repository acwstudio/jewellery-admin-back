<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Storage;

use App\Modules\Storage\Models\File;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'storage_file_list_data',
    description: 'List of files',
    type: 'object'
)]
class FileListData extends Data
{
    public function __construct(
        #[Property(property: 'files', type: 'array', items: new Items(ref: '#/components/schemas/storage_file_data'))]
        #[DataCollectionOf(FileData::class)]
        public readonly DataCollection $files
    ) {
    }

    public static function fromArray(array $fileList): self
    {
        $items = array_map(
            fn (File $file) => FileData::fromModel($file),
            $fileList
        );

        return new self(FileData::collection($items));
    }
}
