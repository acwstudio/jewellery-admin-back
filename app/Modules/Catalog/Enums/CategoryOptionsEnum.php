<?php

namespace App\Modules\Catalog\Enums;

use OpenApi\Attributes\Schema;

#[Schema(schema: 'category_options_enum', type: 'string', default: CategoryOptionsEnum::CHILDREN, example: CategoryOptionsEnum::SLUG_ALIASES)]
enum CategoryOptionsEnum: string
{
    case CHILDREN = 'children';
    case SLUG_ALIASES = 'slug_aliases';
}
