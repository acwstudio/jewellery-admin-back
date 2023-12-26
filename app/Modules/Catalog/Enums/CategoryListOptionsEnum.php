<?php

namespace App\Modules\Catalog\Enums;

use OpenApi\Attributes\Schema;

#[Schema(schema: 'category_list_options_enum', type: 'string', default: CategoryListOptionsEnum::CHILDREN, example: CategoryListOptionsEnum::CHILDREN)]
enum CategoryListOptionsEnum: string
{
    case CHILDREN = 'children';
}
