<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\VideoUrl;

use App\Modules\Catalog\Models\ProductVideoUrl;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_product_video_url_data',
    description: 'Видео продукта',
    required: ['id', 'src'],
    type: 'object'
)]
class ProductVideoUrlData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'src', type: 'string')]
        public readonly string $src
    ) {
    }

    public static function fromModel(ProductVideoUrl $productVideoUrl): self
    {
        $src = config('1c.cdn_url') . $productVideoUrl->path;
        return new self(
            id: $productVideoUrl->id,
            src: $src
        );
    }

    public static function customFromArrayProductVideoUrl(array $productVideoUrl): self
    {
        $src = config('1c.cdn_url') . $productVideoUrl['path'];
        return new self(
            id: $productVideoUrl['id'] ?? 0,
            src:  $src
        );
    }
}
