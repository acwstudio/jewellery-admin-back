<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\File;

use App\Modules\Collections\Models\CollectionImageUrl;
use App\Modules\Collections\Models\File;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'collections_file_data', type: 'object')]
class FileData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'url', type: 'string')]
        public readonly string $url,
        #[Property(property: 'mime_type', type: 'string', nullable: true)]
        public readonly ?string $mime_type = null
    ) {
    }

    public static function fromModel(File $file): self
    {
        return new self(
            $file->id,
            $file->getFirstMediaUrl(),
            $file->getFirstMedia()?->getCustomProperty('mimeType')
        );
    }

    public static function fromCollectionImageUrl(CollectionImageUrl $model): self
    {
        $url = config('1c.cdn_url') . $model->path;

        return new self(
            $model->id,
            $url
        );
    }
}
