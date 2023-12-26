<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Common\Pagination;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'pagination_data',
    description: 'Пагинация',
    type: 'object'
)]
class PaginationData extends Data
{
    public function __construct(
        #[Property(property: 'page', description: 'Текущая страница', type: 'integer', nullable: true)]
        #[
            RequiredWith('per_page'),
            IntegerType,
            Min(1)
        ]
        public readonly ?int $page = null,
        #[Property(property: 'per_page', description: 'Количество на странице', type: 'integer', nullable: true)]
        #[
            RequiredWith('page'),
            IntegerType,
            Min(1)
        ]
        public readonly ?int $per_page = null,
        #[Property(property: 'total', type: 'integer', readOnly: true, example: 64, nullable: true)]
        public readonly ?int $total = null,
        #[Property(property: 'last_page', type: 'integer', readOnly: true, example: 64, nullable: true)]
        public readonly ?int $last_page = null,
    ) {
    }
}
