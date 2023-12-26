<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Storage;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'storage_upload_files',
    description: 'Upload files',
    type: 'object'
)]
class UploadFilesData extends Data
{
    public function __construct(
        #[Property(property: 'files', type: 'array', items: new Items(type: 'file'))]
        public readonly array $files
    ) {
    }
}
