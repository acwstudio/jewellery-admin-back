<?php

declare(strict_types=1);

namespace App\Packages\Enums;

use OpenApi\Attributes\Schema;

#[Schema(description: 'Типы фильтров', type: 'string')]
enum FilterTypeEnum: string
{
    case SELECT = 'select';
    case NUM = 'num';
    case BUTTON = 'button';
}
