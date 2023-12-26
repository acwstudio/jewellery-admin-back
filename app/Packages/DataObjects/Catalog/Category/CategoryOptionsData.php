<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category;

use App\Modules\Catalog\Enums\CategoryOptionsEnum;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;

#[Schema(schema: 'category_options_data', type: 'object')]
class CategoryOptionsData extends Data
{
    public function __construct(
        #[Property(property: 'with', type: 'array', items: new Items(
            ref: '#/components/schemas/category_options_enum'
        ), nullable: true)]
        public readonly ?array $with = [],
    ) {
    }

    public static function rules(): array
    {
        return [
            'with.*' => [new Enum(CategoryOptionsEnum::class)]
        ];
    }
}
