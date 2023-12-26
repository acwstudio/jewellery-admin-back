<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\PreviewImage;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Schema(
    schema: 'catalog_preview_image_upload_data',
    description: 'Загрузка превью изображения',
    required: ['image'],
    type: 'object'
)]
class PreviewImageUploadData extends Data
{
    public function __construct(
        #[Property(property: 'image', type: 'file')]
        public readonly UploadedFile $image
    ) {
    }
}
