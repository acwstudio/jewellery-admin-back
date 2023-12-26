<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\PreviewImage;

use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Packages\Enums\Catalog\PreviewImageSizeEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_preview_image_data',
    description: 'Превью изображение',
    required: ['id', 'image_url_sm', 'image_url_md', 'image_url_lg'],
    type: 'object'
)]
class PreviewImageData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'image_url_sm', type: 'string')]
        public readonly string $image_url_sm,
        #[Property(property: 'image_url_md', type: 'string')]
        public readonly string $image_url_md,
        #[Property(property: 'image_url_lg', type: 'string')]
        public readonly string $image_url_lg
    ) {
    }

    public static function fromModel(?PreviewImage $previewImage): self
    {
        $classSize = Product::class;
        $conversionSM = PreviewImageSizeEnum::SM->value;
        $conversionMD = PreviewImageSizeEnum::MD->value;

        if (null === $previewImage) {
            return new self(
                id: 0,
                image_url_sm: config("media-library.sizes.{$classSize}.{$conversionSM}.default"),
                image_url_md: config("media-library.sizes.{$classSize}.{$conversionMD}.default"),
                image_url_lg: config("media-library.sizes.{$classSize}.default")
            );
        }

        return new self(
            id: $previewImage->id,
            image_url_sm: $previewImage->getFirstMediaUrl(conversionName: $conversionSM),
            image_url_md: $previewImage->getFirstMediaUrl(conversionName: $conversionMD),
            image_url_lg: $previewImage->getFirstMediaUrl()
        );
    }

    public static function fromProductImageUrl(ProductImageUrl $productImageUrl): self
    {
        $url = config('1c.cdn_url') . $productImageUrl->path;

        $classSize = Product::class;
        $conversionSM = PreviewImageSizeEnum::SM->value;
        $conversionMD = PreviewImageSizeEnum::MD->value;

        $imageSmQuery = [
            'width' => config("media-library.sizes.{$classSize}.{$conversionSM}.width"),
            'quality' => config("media-library.sizes.{$classSize}.{$conversionSM}.quality")
        ];

        $imageMdQuery = [
            'width' => config("media-library.sizes.{$classSize}.{$conversionMD}.width"),
            'quality' => config("media-library.sizes.{$classSize}.{$conversionMD}.quality")
        ];

        return new self(
            id: -1,
            image_url_sm: $url . "?" . http_build_query($imageSmQuery),
            image_url_md: $url . "?" . http_build_query($imageMdQuery),
            image_url_lg: $url
        );
    }

    public static function customFromArray(array $previewImage): self
    {
        $classSize = Product::class;
        $conversionSM = PreviewImageSizeEnum::SM->value;
        $conversionMD = PreviewImageSizeEnum::MD->value;

        $imageSmQuery = [
            'width' => config("media-library.sizes.{$classSize}.{$conversionSM}.width"),
            'quality' => config("media-library.sizes.{$classSize}.{$conversionSM}.quality")
        ];

        $imageMdQuery = [
            'width' => config("media-library.sizes.{$classSize}.{$conversionMD}.width"),
            'quality' => config("media-library.sizes.{$classSize}.{$conversionMD}.quality")
        ];

        if (empty($previewImage)) {
            return new self(
                id: 0,
                image_url_sm: config("media-library.sizes.{$classSize}.{$conversionSM}.default"),
                image_url_md: config("media-library.sizes.{$classSize}.{$conversionMD}.default"),
                image_url_lg: config("media-library.sizes.{$classSize}.default")
            );
        }

        $url = $previewImage['url'] ?? config("media-library.sizes.{$classSize}.default");

        return new self(
            id: $previewImage['id'] ?? -1,
            image_url_sm: $url . "?" . http_build_query($imageSmQuery),
            image_url_md: $url . "?" . http_build_query($imageMdQuery),
            image_url_lg: $url
        );
    }

    public static function customFromArrayProductImageUrl(array $productImageUrl): self
    {
        $classSize = Product::class;
        $conversionSM = PreviewImageSizeEnum::SM->value;
        $conversionMD = PreviewImageSizeEnum::MD->value;

        if (empty($productImageUrl)) {
            return new self(
                0,
                config("media-library.sizes.{$classSize}.{$conversionSM}.default"),
                config("media-library.sizes.{$classSize}.{$conversionMD}.default"),
                config("media-library.sizes.{$classSize}.default")
            );
        }

        $url = config('1c.cdn_url') . $productImageUrl['path'];

        $imageSmQuery = [
            'width' => config("media-library.sizes.{$classSize}.{$conversionSM}.width"),
            'quality' => config("media-library.sizes.{$classSize}.{$conversionSM}.quality")
        ];

        $imageMdQuery = [
            'width' => config("media-library.sizes.{$classSize}.{$conversionMD}.width"),
            'quality' => config("media-library.sizes.{$classSize}.{$conversionMD}.quality")
        ];

        return new self(
            id: -1,
            image_url_sm: $url . "?" . http_build_query($imageSmQuery),
            image_url_md: $url . "?" . http_build_query($imageMdQuery),
            image_url_lg: $url
        );
    }
}
