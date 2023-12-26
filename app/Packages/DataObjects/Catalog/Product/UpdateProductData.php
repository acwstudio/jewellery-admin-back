<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\PreviewImage;
use App\Packages\Enums\LiquidityEnum;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Schema(
    schema: 'catalog_update_product_data',
    description: 'Обновление продукта',
    required: ['sku', 'name', 'summary', 'description', 'manufacture_country', 'rank', 'is_active'],
    type: 'object'
)]
class UpdateProductData extends Data
{
    public function __construct(
        public readonly int $id,
        #[Property(
            property: 'categories',
            description: 'Принимает массив идентификаторов категорий',
            type: 'array',
            items: new Items(type: 'integer'),
            nullable: true
        )]
        #[
            Required,
            ArrayType
        ]
        public readonly array $categories,
        #[Property(property: 'sku', type: 'string')]
        public readonly string $sku,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'summary', type: 'string')]
        public readonly string $summary,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'manufacture_country', type: 'string')]
        public readonly string $manufacture_country,
        #[Property(property: 'rank', type: 'integer')]
        #[
            IntegerType,
            Min(1)
        ]
        public readonly int $rank,
        #[Property(property: 'catalog_number', type: 'string', nullable: true)]
        public readonly ?string $catalog_number = null,
        #[Property(property: 'supplier', type: 'string', nullable: true)]
        public readonly ?string $supplier = null,
        #[Property(property: 'liquidity', nullable: true)]
        public readonly ?LiquidityEnum $liquidity = null,
        #[Property(property: 'stamp', type: 'float', nullable: true)]
        public readonly ?float $stamp = null,
        #[Property(property: 'meta_title', type: 'string', nullable: true)]
        public readonly ?string $meta_title = null,
        #[Property(property: 'meta_description', type: 'string', nullable: true)]
        public readonly ?string $meta_description = null,
        #[Property(property: 'meta_keywords', type: 'string', nullable: true)]
        public readonly ?string $meta_keywords = null,
        #[Property(property: 'is_active', type: 'bool', default: true)]
        public readonly bool $is_active = true,
        #[Property(property: 'is_drop_shipping', type: 'bool', nullable: true)]
        public readonly ?bool $is_drop_shipping = null,
        #[Property(property: 'popularity', type: 'integer', nullable: true)]
        public readonly ?int $popularity = null,
        #[Property(property: 'preview_image_id', type: 'integer', nullable: true)]
        #[
            Nullable,
            IntegerType,
            Min(1),
            Exists(PreviewImage::class, 'id')
        ]
        public readonly ?int $preview_image_id = null,
        #[Property(property: 'brand_id', type: 'integer', nullable: true)]
        #[
            Nullable,
            IntegerType,
            Min(1),
            Exists(Brand::class, 'id')
        ]
        public readonly ?int $brand_id = null,
        #[Property(
            property: 'images',
            description: 'Принимает массив идентификаторов превью изображений по аналогии с preview_image_id',
            type: 'array',
            items: new Items(type: 'integer'),
            nullable: true
        )]
        #[
            Nullable,
            ArrayType
        ]
        public readonly array|Optional $images = []
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'categories.*' => ['integer', 'min:1'],
            'images.*' => ['integer', 'min:1']
        ];
    }
}
