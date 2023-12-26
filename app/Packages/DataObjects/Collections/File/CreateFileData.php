<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\File;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Schema(
    schema: 'collections_create_file_data',
    description: 'Создание файла коллекции',
    required: ['file'],
    type: 'object'
)]
class CreateFileData extends Data
{
    public function __construct(
        #[Property(property: 'file', type: 'file')]
        public readonly UploadedFile $file
    ) {
    }
}
