<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Collection;

use App\Modules\Collections\Models\Collection;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Schema(
    schema: 'collections_create_collection_data',
    description: 'Создание коллекции',
    type: 'object'
)]
class CreateCollectionData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'slug', type: 'string')]
        #[Unique(Collection::class, 'slug')]
        public readonly string $slug,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'is_active', type: 'boolean')]
        public readonly bool $is_active,
        #[Property(property: 'is_hidden', type: 'boolean')]
        public readonly bool $is_hidden,
        #[Property(property: 'preview_image_id', type: 'integer', nullable: true)]
        #[Nullable, IntegerType, Min(1)]
        public readonly ?int $preview_image_id = null,
        #[Property(property: 'preview_image_mob_id', type: 'integer', nullable: true)]
        #[Nullable, IntegerType, Min(1)]
        public readonly ?int $preview_image_mob_id = null,
        #[Property(property: 'banner_image_id', type: 'integer', nullable: true)]
        #[Nullable, IntegerType, Min(1)]
        public readonly ?int $banner_image_id = null,
        #[Property(property: 'banner_image_mob_id', type: 'integer', nullable: true)]
        #[Nullable, IntegerType, Min(1)]
        public readonly ?int $banner_image_mob_id = null,
        #[Property(
            property: 'stones',
            description: 'Принимает массив идентификаторов Collections.Stones',
            type: 'array',
            items: new Items(type: 'integer')
        )]
        #[ArrayType]
        public readonly array $stones = [],
        #[Property(
            property: 'products',
            description: 'Принимает массив идентификаторов Catalog.Products',
            type: 'array',
            items: new Items(type: 'integer')
        )]
        #[ArrayType]
        public readonly array $products = [],
        #[Property(
            property: 'images',
            description: 'Принимает массив идентификаторов Collections.Files',
            type: 'array',
            items: new Items(type: 'integer'),
            nullable: true
        )]
        #[Nullable, ArrayType]
        public readonly array|Optional $images = [],
        #[Property(property: 'extended_name', type: 'string', nullable: true)]
        public readonly ?string $extended_name = null,
        #[Property(property: 'extended_description', type: 'string', nullable: true)]
        public readonly ?string $extended_description = null,
        #[Property(property: 'extended_image_id', type: 'integer', nullable: true)]
        #[Nullable, IntegerType, Min(1)]
        public readonly ?int $extended_image_id = null,
        #[Property(property: 'external_id', type: 'string', nullable: true)]
        public readonly ?string $external_id = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'stones.*' => ['integer', 'min:1'],
            'products.*' => ['integer', 'min:1'],
            'images.*' => ['integer', 'min:1']
        ];
    }
}
