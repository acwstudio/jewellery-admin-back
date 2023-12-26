<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Checkout;

use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'checkout_product_data', type: 'object')]
class ProductData extends Data
{
    public function __construct(
        #[MapName('product_id')]
        #[Property(property: 'product_id', type: 'object')]
        public readonly int $productId,
        #[MapName('preview_image')]
        #[Property(
            property: 'catalog_preview_image_data',
            ref: '#/components/schemas/catalog_preview_image_data',
            type: 'object',
        )]
        public readonly PreviewImageData $previewImage,
        #[Property('slug', type: 'string')]
        public readonly string $slug
    ) {
    }
}
