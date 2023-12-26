<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Favorite;

use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\File;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'collections_create_favorite_data',
    description: 'Создание избранной коллекции',
    required: [
        'slug',
        'name',
        'description',
        'background_color',
        'collection_id',
        'image_id',
        'image_mob_id'
    ],
    type: 'object'
)]
class CreateFavoriteData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'slug', type: 'string')]
        #[Unique(Favorite::class, 'slug')]
        public readonly string $slug,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'background_color', type: 'string')]
        public readonly string $background_color,
        #[Property(property: 'collection_id', type: 'integer',)]
        #[IntegerType, Min(1), Exists(Collection::class, 'id')]
        public readonly int $collection_id,
        #[Property(property: 'image_id', type: 'integer')]
        #[IntegerType, Min(1), Exists(File::class, 'id')]
        public readonly int $image_id,
        #[Property(property: 'image_mob_id', type: 'integer')]
        #[IntegerType, Min(1), Exists(File::class, 'id')]
        public readonly int $image_mob_id,
        #[Property(property: 'font_color', type: 'string', nullable: true)]
        public readonly ?string $font_color = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
