<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Storage;

use App\Modules\Storage\Models\File;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'storage_file_data',
    description: 'Файл',
    type: 'object'
)]
class FileData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int    $id,
        #[Property(property: 'file_name', type: 'string')]
        public readonly string $file_name,
        #[Property(property: 'url', type: 'string')]
        public readonly string $url,
        #[Property(property: 'type', type: 'string', enum: ['image', 'webp', 'pdf', 'svg', 'video', 'other'])]
        public readonly string $type
    ) {
    }

    public static function fromModel(File $file): self
    {
        /** @var \App\Modules\Storage\Models\Media $media */
        $media = $file->getFirstMedia();

        return new self(
            $file->getKey(),
            $media->getFileName(),
            $file->getFirstMediaUrl(),
            $media->getTypeFromExtension()
        );
    }
}
