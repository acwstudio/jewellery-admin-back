<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Packages\Enums\Catalog\PreviewImageSizeEnum;
use Database\Factories\Modules\Catalog\PreviewImageFactory;
use App\Modules\Storage\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenSearch\ScoutDriverPlus\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 */
class PreviewImage extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.preview_images';

    public function registerMediaCollections(): void
    {
        $classSize = Product::class;
        $conversionSM = PreviewImageSizeEnum::SM->value;
        $conversionMD = PreviewImageSizeEnum::MD->value;

        $this
            ->addMediaCollection('default')
            ->useFallbackUrl(config("media-library.sizes.{$classSize}.default"))
            ->useFallbackUrl(
                config("media-library.sizes.{$classSize}.{$conversionSM}.default"),
                $conversionSM
            )
            ->useFallbackUrl(
                config("media-library.sizes.{$classSize}.{$conversionMD}.default"),
                $conversionMD
            )
            ->registerMediaConversions(function (Media $media) use ($classSize, $conversionSM, $conversionMD) {
                $mediaWidth = $media->getCustomProperty('width');

                $widthSm = config("media-library.sizes.{$classSize}.{$conversionSM}.width");
                $mediaConversionSM = $this->addMediaConversion($conversionSM);

                if (!is_null($mediaWidth) && $mediaWidth > $widthSm) {
                    $mediaConversionSM->width($widthSm);
                }

                $widthMd = config("media-library.sizes.{$classSize}.{$conversionMD}.width");
                $mediaConversionMD = $this->addMediaConversion($conversionMD);

                if (!is_null($mediaWidth) && $mediaWidth > $widthMd) {
                    $mediaConversionMD->width($widthMd);
                }
            });
    }

    protected static function newFactory()
    {
        return app(PreviewImageFactory::class);
    }
}
