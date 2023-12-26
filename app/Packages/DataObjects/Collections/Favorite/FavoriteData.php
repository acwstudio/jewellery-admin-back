<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Favorite;

use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\File;
use App\Packages\DataObjects\Collections\Collection\CollectionData;
use App\Packages\DataObjects\Collections\File\FileData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'collections_favorite_data', type: 'object')]
class FavoriteData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'background_color', type: 'string')]
        public readonly string $background_color,
        #[Property(property: 'collection_id', type: 'integer')]
        public readonly int $collection_id,
        #[Property(property: 'collection_slug', type: 'string')]
        public readonly string $collection_slug,
        #[Property(property: 'image', ref: '#/components/schemas/collections_file_data', type: 'object')]
        public readonly FileData $image,
        #[Property(property: 'image_mob', ref: '#/components/schemas/collections_file_data', type: 'object')]
        public readonly FileData $image_mob,
        #[Property(property: 'font_color', type: 'string', nullable: true)]
        public readonly ?string $font_color = null,
    ) {
    }

    public static function fromModel(Favorite $favorite): self
    {
        return new self(
            $favorite->id,
            $favorite->slug,
            $favorite->name,
            $favorite->description,
            $favorite->background_color,
            $favorite->collection->id,
            $favorite->collection->slug,
            self::getFile($favorite->image),
            self::getFile($favorite->imageMob),
            $favorite->font_color
        );
    }

    private static function getFile(?File $file): ?FileData
    {
        if (!$file instanceof File) {
            return null;
        }

        return FileData::fromModel($file);
    }
}
