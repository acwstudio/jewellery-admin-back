<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category;

use App\Modules\Catalog\Enums\CategoryListOptionsEnum;
use App\Packages\DataObjects\Catalog\Filter\FilterCategoryData;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;

#[Schema(schema: 'category_list_options_data', type: 'object')]
class CategoryListOptionsData extends Data
{
    public function __construct(
        #[Property(property: 'with', type: 'array', items: new Items(
            ref: '#/components/schemas/category_list_options_enum'
        ), nullable: true)]
        public readonly ?array $with = [],
        #[Property(property: 'filter', ref: '#/components/schemas/catalog_filter_category_data', nullable: true)]
        public readonly ?FilterCategoryData $filter = null,
    ) {
    }

    public static function rules(): array
    {
        return [
            'with.*' => [new Enum(CategoryListOptionsEnum::class)]
        ];
    }
}
