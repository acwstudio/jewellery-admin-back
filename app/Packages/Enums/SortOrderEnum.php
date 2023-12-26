<?php

declare(strict_types=1);

namespace App\Packages\Enums;

use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'sort_order',
    description: 'Порядок сортировки',
    type: 'string',
    example: SortOrderEnum::ASC
)]
enum SortOrderEnum: string
{
    case ASC = 'asc';
    case DESC = 'desc';
}
